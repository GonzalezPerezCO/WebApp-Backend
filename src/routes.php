<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;

//Traer cupos 
$app->get('/api', function (Request $request, Response $response) {

    $this->logger->info("Slim-Skeleton '/api' route");
    $arr = array();
    $mysqli = conect();
    $query = "SELECT * FROM tncupo";
    if ($resul = $mysqli->query($query)) {
        while ($row = $resul->fetch_assoc()) {
            $arr[] = $row;
        }
    }
    $newResponse = $response->withJson($arr);
    return $newResponse->withHeader('Content-type', 'application/json;charset=utf-8');
});

//Agregar estudiante
$app->post('/api/estudiante', function (Request $request, Response $response) {

    $this->logger->info("Slim-Skeleton '/api/estudiante' route");
    $input = $request->getParsedBody();
    $mysqli = conect();
    $query = "CALL addEstud(?, ?, ?, ?)";
    //retrieve passw from request body and pass it to password_hash() function
    $hash = password_hash($input['password'], PASSWORD_DEFAULT);
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param('ssis', $input['nombre'], $input['email'], $input['documento'], $hash);
        $stmt->execute();
        $stmt->close();
    }
});

//Agregar horario
$app->post('/api/horario', function (Request $request, Response $response) {

    $this->logger->info("Slim-Skeleton '/api/horario' route");
    $input = $request->getParsedBody();
    $mysqli = conect();
    $query = "CALL addHorario(?, ?, ?)";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param('iss', $input['hora'], $input['dia'], $input['email']);
        $stmt->execute();
        $stmt->close();
    }
});

//Loguearse al sistema 
$app->post('/login', function (Request $request, Response $response, array $args) {

    $input = $request->getParsedBody();
    $mysqli = conect();
    $query = "SELECT * FROM testudiantes WHERE email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $input['email']);
    $stmt->execute();
    $resul = $stmt->get_result();
    $row = $resul->fetch_assoc();
    // verify email address.
    if (!$row) {
        return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);
    }
    // verify password.
    if (!password_verify($input['password'], $row['password'])) {
        return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);
    }
    $settings = $this->get('settings')['jwt']; // get settings array.
    $key = base64_decode($settings['secret']); // decode the secret key
    $token = JWT::encode(['id' => $row['id'], 'email' => $row['email']], $key, "HS256");
    return $this->response->withJson(['token' => $token]);
});