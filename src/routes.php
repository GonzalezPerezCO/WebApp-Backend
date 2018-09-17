<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;

//Traer cupos 
$app->get('/api', function (Request $request, Response $response) {
    // Sample log message
    //$this->logger->info("Slim-Skeleton '/api' route");
    $arr = array();
    $mysqli = conect();
    $query = "SELECT * FROM tncupo";
    if ($resul = $mysqli->query($query)) {
        while ($row = $resul->fetch_assoc()) {
            $arr[] = $row;
        }
        //$response = json_encode($arr);
    }
    $newResponse = $response->withJson($arr);
    return $newResponse->withHeader('Content-type', 'application/json;charset=utf-8');
});

//Agregar estudiante
$app->post('/api/estudiante', function (Request $request, Response $response) {
    // Sample log message
    //$this->logger->info("Slim-Skeleton '/api' route");
    $mysqli = conect();
    $query = "CALL addEstud(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    //retrieve passw from request body and pass it to password_hash() function
    if ($stmt = $mysqli->prepare($query)) {
        //$stmt->bind_param('issisisisis', );
        $stmt->execute();
        $stmt->close();
    }
});

//Agregar horario
$app->post('/api/horario', function (Request $request, Response $response) {
    // Sample log message
    //$this->logger->info("Slim-Skeleton '/api' route");
    $mysqli = conect();
    $query = "CALL addHorario(?, ?, ?)";
    if ($stmt = $mysqli->prepare($query)) {
        //$stmt->bind_param('sss', );
        $stmt->execute();
        $stmt->close();
    }
});

//transform this function from pdo to oo mysqli 
$app->post('/login', function (Request $request, Response $response, array $args) {

    $input = $request->getParsedBody();
    $sql = "SELECT * FROM testudiantes WHERE email= :email";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("email", $input['email']);
    $sth->execute();
    $user = $sth->fetchObject();
 
    // verify email address.
    if (!$user) {
        return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);
    }
 
    // verify password.
    if (!password_verify($input['password'], $user->password)) {
        return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);
    }

    $settings = $this->get('settings')['jwt']; // get settings array.
    $key = base64_decode($settings['secret']);
    $token = JWT::encode(['id' => $user->id, 'email' => $user->email], $key, "HS256");

    return $this->response->withJson(['token' => $token]);

});