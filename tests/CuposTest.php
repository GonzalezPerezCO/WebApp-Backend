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

    public function tearDown(){

        $this->client = null;
    }

    /*** @covers ::getCupos */
    public function testGetCupo(){

        $response = $this->client->request('GET', 'cupos');
        $this->assertEquals(200, $response->getStatusCode());
        
        $contentType = $response->getHeader('Content-Type');
        $this->assertContains("application/json", $contentType);
        
        $cupos = json_decode($response->getBody(), true);
        $this->assertGreaterThanOrEqual($cupos[8]["JUEVES"], "20");
    }

    /*** @coversNothing*/
    public function testOnlyGetAllowed(){

        $response = $this->client->request('POST', 'cupos', ['http_errors' => false]);
        $this->assertEquals(405, $response->getStatusCode());

        $response = $this->client->request('PUT', 'cupos', ['http_errors' => false]);
        $this->assertEquals(405, $response->getStatusCode());
        
        $response = $this->client->request('DELETE', 'cupos', ['http_errors' => false]);
        $this->assertEquals(405, $response->getStatusCode());
    }
}
