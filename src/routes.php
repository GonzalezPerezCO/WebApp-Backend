<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;

//Traer cupos 
$app->get('/api', function (Request $request, Response $response) {

    $this->logger->info("Slim-Skeleton '/api' route");
    $arr = array();
    $mysqli = conect();
    $query = "SELECT * FROM tcupos";
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
    $query = "CALL addEstud(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    //retrieve passw from request body and pass it to password_hash() function
    $hash = password_hash($input['password'], PASSWORD_DEFAULT);
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param('issisisisis', $input['reserva'], $input['nombre'], $input['codigo'], $input['carrera'], $input['semestre'], $input['email'], $input['documento'], $hash ,$input['bloqueado'], $input['observacion']);
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
        echo $mysqli->info;
    }
});

//Loguearse al sistema 
$app->post('/login', function (Request $request, Response $response, array $args) {

    $input = $request->getParsedBody();
    $arr = array();
    $mysqli = conect();
    $query = "SELECT * FROM testudiantes WHERE email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $input['email']);
    $stmt->execute();
    $resul = $stmt->get_result();
    while ($row = $resul->fetch_object("Student")) {
        $arr[] = $row;
    }
    // verify email address.
    if (!$arr) {
        return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);
    }
 
    // verify password.
    if (!password_verify($input['password'], $arr->password)) {
        return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);
    }

    $settings = $this->get('settings')['jwt']; // get settings array.
    $key = base64_decode($settings['secret']);
    $token = JWT::encode(['id' => $user->id, 'email' => $user->email], $key, "HS256");

    return $this->response->withJson(['token' => $token]);

});