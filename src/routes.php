<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Traer cupos 
$app->get('/api', function (Request $request, Response $response) {
    $this->logger->info("Slim-Skeleton '/api' route");
    $stmt = $this->db->sql("SELECT * FROM tncupo");
    $data = $stmt->fetchAll();
    return $this->response->withJson($data);
});

// traer estudiante por id
$app->get('api/estudiante/{id}', function (Request $request, Response $response, array $args) {
    //$this->logger->info("Slim-Skeleton '/api/estudiante/id' route");
    $stmt = $this->db->prepare("SELECT * FROM testudiantes WHERE id = ?");
    $stmt->execute($args['id']);
    $data = $stmt->fetchObject();
    return $this->response->withJson($data);
});

//Agregar estudiante
$app->post('/api/estudiante', function (Request $request, Response $response) {
    //$this->logger->info("Slim-Skeleton '/api/estudiante' route");
    $input = $request->getParsedBody();
    $sql = "CALL addEstud(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(
        $input['reserva'],
        $input['nombre'],
        $input['apellido'],
        $input['codigo'],
        $input['carrera'],
        $input['semestre'],
        $input['email'],
        $input['documento'],
        $input['password'],
        $input['bloqueado'],
        $input['observacion']
    );
    $input['id'] = $this->db->lastInserId();
    return $this->response->withJson($input);
});

//Agregar horario
$app->post('/api/horario', function (Request $request, Response $response) {
    //$this->logger->info("Slim-Skeleton '/api/horario' route");
    $input = $request->getParsedBody();
    $sql = "CALL addHorario(?, ?, ?)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute($input['email'], $input['dia1'], $input['hora1']);
    $input['id'] = $this->db->lastInserId();
    return $this->response->withJson($input);
});
