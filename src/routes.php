<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT;


$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

//Traer cupos 
$app->get('/cupos', function (Request $request, Response $response) {

    $mysqli = conect();
    $query = "SELECT * FROM tcupos";
    if ($resul = $mysqli->query($query)) {
        while ($row = $resul->fetch_assoc()) {
            $arr[] = $row;
        }
    }
    return $response->withJson($arr)->withHeader('Content-type', 'application/json');
});

//Traer horario
$app->get('/horario/{email}', function (Request $request, Response $response, $args) {
    
    $mysqli = conect();
    $query = "SELECT * FROM thorarios WHERE email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $args['email']);
    $stmt->execute();
    $resul = $stmt->get_result();
    if ($resul->num_rows === 0) 
        $arr[] = ['error' => true, 'message' => 'No rows', 'email' => $args[email]];
    while ($row = $resul->fetch_assoc()) {
        $arr[] = $row;
    }
    return $response->withJson($arr)->withHeader('Content-type', 'application/json');
});

//Agregar estudiante
$app->post('/estudiante', function (Request $request, Response $response) {

    $input = $request->getParsedBody();
    $mysqli = conect();
    $query = "CALL addEstud(?, ?, ?, ?, ?)";
    //retrieve passw from request body and pass it to password_hash() function
    $hash = password_hash($input['password'], PASSWORD_DEFAULT);
    $fname = strtoupper($input['nombre']);
    $lname = strtoupper($input['apellido']);
    if ($stmt = $mysqli->prepare($query)) {
        try {
            $stmt->bind_param('ssiss', $fname, $lname, $input['codigo'], $input['email'], $hash);
            $stmt->execute();
        }catch (Exception $e){
            $error = $e->getMessage();
        }
    }
    return $response->withJson($error)->withHeader('Content-type', 'application/json');
});

//Agregar horario
$app->post('/horario', function (Request $request, Response $response) {

    $input = $request->getParsedBody();
    $mysqli = conect();
    $query = "CALL addHorario(?, ?, ?)";
    if ($stmt = $mysqli->prepare($query)) {
        try {
            $stmt->bind_param('iss', $input['hora'], $input['dia'], $input['email']);
            $stmt->execute();
        }catch (Exception $e){
            $error = $e->getMessage();
        }
    }
    return $response->withJson($error)->withHeader('Content-type', 'application/json');
});

//Loguearse al sistema 
$app->post('/login', function (Request $request, Response $response) {

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
        return $this->response->withJson(['error' => true, 'message' => 'Email o contraseña incorrectos.']);
    }
    // verify password.
    if (!password_verify($input['password'], $row['password'])) {
        return $this->response->withJson(['error' => true, 'message' => 'Email o contraseña incorrectos.']);
    }
    $settings = $this->get('settings')['jwt']; // get settings array.
    $key = base64_decode($settings['secret']); // decode the secret key
    $token = JWT::encode(['id' => $row['id'], 'email' => $row['email']], $key, "HS256");
    return $this->response->withJson(['token' => $token]);
});

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});
