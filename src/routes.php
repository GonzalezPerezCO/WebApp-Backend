<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Traer cupos 
$app->get('/api', function (Request $request, Response $response) {
    // Sample log message
    //$this->logger->info("Slim-Skeleton '/api' route");
    $arr = array();
    $mysqli = conect();
    $query = "SELECT * FROM tncupo";
    if($resul = $mysqli->query($query)){
        while($row = $resul->fetch_assoc()){   
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
    if($stmt = $mysqli->prepare($query)){
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
    if($stmt = $mysqli->prepare($query)){
        //$stmt->bind_param('sss', );
        $stmt->execute();
        $stmt->close();
    }
});
