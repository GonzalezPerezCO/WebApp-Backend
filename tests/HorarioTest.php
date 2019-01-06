<?php

namespace Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class HorarioTest extends TestCase {
    private $client;

    public function setUp(){
       
       $this->client = new Client([
        'base_uri' => 'http://estudiantes.is.escuelaing.edu.co/deportes/api/public/',
        'headers' => ['X-Powered-By' => 'PHP/7.0.8']
        ]);
    }

    public function testGetHorarioWithEmail(){

        $response = $this->client->request('GET', 'horario');
        $this->assertEquals(200, $response->getStatusCode());
        
    }

    public function testPostHorario(){

        $response = $this->client->request('POST', 'horario');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPostHorarioWithoutData(){

        $response = $this->client->request('POST', 'horario');
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"];
        $this->assertContains("application/json", $contentType);
    }
}
