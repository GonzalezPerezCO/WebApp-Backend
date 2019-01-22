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

    public function tearDown(){

        $this->client = null;
    }

    /*Esta prueba verifica que al agregar un nuevo estudiante 
      se deben pasar sus datos una sola vez, de otra forma se 
      genera un error de estudiante duplicado 
      @covers ::postEstudiante  */
    public function testPostEstudiante(){

        $response = $this->client->request('POST', 'estudiante', 
            ['form_params' => [
                'nombre' => 'andres',
                'apellido' => 'correa',
                'codigo' => 975310,
                'email' => 'andres.correa@mail.com',
                'password' => '5up3r'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getBody(), true);
        $this->assertSame(null, $body);
    }

    /*** @coversNothing */
    public function testPostWithoutData(){

        $response = $this->client->request('POST', 'estudiante');
        $this->assertEquals(200, $response->getStatusCode());
        
        $contentType = $response->getHeader('Content-Type');
        $this->assertContains("application/json", $contentType);
        
        $msg = json_decode($response->getBody(), true);
        $this->assertContains("cannot be null", $msg);
    }

    /*** @coversNothing */
    public function testOnlyPostAllowed(){

        $response = $this->client->request('DELETE', 'estudiante', 
        ['http_errors' => false]);
        $this->assertEquals(405, $response->getStatusCode());

        $response = $this->client->request('PUT', 'estudiante', 
        ['http_errors' => false]);
        $this->assertEquals(405, $response->getStatusCode());

        $response = $this->client->request('GET', 'login', 
        ['http_errors' => false]);
        $this->assertEquals(405, $response->getStatusCode());
    }
}
