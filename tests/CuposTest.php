<?php

namespace Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class CuposTest extends TestCase {
    private $client;

    public function setUp(){
       
       $this->client = new Client([
        'base_uri' => 'http://estudiantes.is.escuelaing.edu.co/deportes/api/public/',
        'headers' => ['X-Powered-By' => 'PHP/7.0.8']
        ]);
    }

    public function testGetCupo(){

        $response = $this->client->request('GET', 'cupos');
        $this->assertEquals(200, $response->getStatusCode());
        
        $contentType = $response->getHeaders()["Content-Type"];
        $this->assertContains("application/json", $contentType);
        
        $cupos = json_decode($response->getBody(), true);
        $this->assertGreaterThanOrEqual($cupos[8]["Jueves"], "20");
    }

    public function testOnlyGetAllowed(){

        $response = $this->client->request('POST', 'cupos', ['http_errors' => false]);
        $this->assertEquals(404, $response->getStatusCode());

        $response = $this->client->request('PUT', 'cupos', ['http_errors' => false]);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
