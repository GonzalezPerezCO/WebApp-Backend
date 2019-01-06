<?php

namespace Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class EstudianteTest extends TestCase {
    private $client;

    public function setUp(){
       
       $this->client = new Client([
        'base_uri' => 'http://estudiantes.is.escuelaing.edu.co/deportes/api/public/',
        'headers' => ['X-Powered-By' => 'PHP/7.0.8']
        ]);
    }

    public function testPostEstudiante(){

        $response = $this->client->request('POST', 'estudiante');
        $this->assertEquals(200, $response->getStatusCode());
        //send the stud data in the body as json
        $contentLength = $response->getHeaders()["Content-Length"];
        $this->assertEquals("4", $contentLength);

        $body = $response->getBody();
        //$this->assertContains("null", $body);
    }

    public function testPostWithoutData(){

        $response = $this->client->request('POST', 'estudiante');
        $this->assertEquals(200, $response->getStatusCode());
        
        $contentType = $response->getHeaders()["Content-Type"];
        $this->assertContains("application/json", $contentType);
        
        $msg = json_decode($response->getBody(), true);
        $this->assertContains("cannot be null", $msg);
    }

    public function testOnlyPostAllowed(){

        $response = $this->client->request('DELETE', 'estudiante', 
        ['http_errors' => false]);
        $this->assertEquals(404, $response->getStatusCode());

        $response = $this->client->request('PUT', 'estudiante', 
        ['http_errors' => false]);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
