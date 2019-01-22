<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT;


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
})->setName('getCupos');

$app->get('/mail', function (Request $request, Response $response) {
    
    $mail = imap_open("{outlook.office365.com:993/imap/ssl/novalidate-cert/authuser=juan.gonzalez@mail.escuelaing.edu.co}", "juan.gonzalez@mail.escuelaing.edu.co", "Fr4nc!5c0");
    $headers = imap_headers($mail);
    $last = imap_num_msg($mail);
    var_dump($last);
    $header = imap_header($mail, $last);
    imap_close($mail);
    return $response;
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
})->setName('getHorario');


//Agregar estudiante
$app->post('/estudiante', function (Request $request, Response $response) {

    $input = $request->getParsedBody();
    $mysqli = conect();
    $query = "CALL addEstud(?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $mysqli->prepare($query)) {
        try {
            $stmt->bind_param('ssiiisi', 
            $input['nombre'], $input['apellido'], $input['codigo'], $input['reserva'], 
            $input['documento'], $input['carrera'], $input['semestre']);
            $stmt->execute();
        }catch (Exception $e){
            $error = $e->getMessage();
        }
    }
    return $response->withJson($error)->withHeader('Content-type', 'application/json');
})->setName('postEstudiante');

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
})->setName('postHorario');

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
})->setName('login');

$app->get('/registro/{email}', function (Request $request, Response $response, $args) {
    
    $mysqli = conect();
    $query = "SELECT registro FROM testudiantes WHERE email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $args['email']);
    $stmt->execute();
    $resul = $stmt->get_result();
    $row = $resul->fetch_assoc();

    return $response->withJson($row)->withHeader('Content-type', 'application/json');
});

$app->post('/registro', function (Request $request, Response $response) {

    $input = $request->getParsedBody();
    $mysqli = conect();
    $query = "CALL addRegistro(?, ?)";

    if ($stmt = $mysqli->prepare($query)) {
        try {
            $stmt->bind_param('is', $input['estado'], $input['email']);
            $stmt->execute();
        }catch (Exception $e){
            $error = $e->getMessage();
        }
    }
    return $response->withJson($error)->withHeader('Content-type', 'application/json');
})
;
