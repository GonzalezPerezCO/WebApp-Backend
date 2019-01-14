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

    public function tearDown(){

        $this->client = null;
    }

    /*** @covers ::getHorario */
    public function testGetHorarioWithEmail(){

        $response = $this->client->request('GET', 'horario/prueba@mail.com');
        $this->assertEquals(200, $response->getStatusCode());
        
        $contentType = $response->getHeader('Content-Type');
        $this->assertContains("application/json", $contentType);
    }
    
    /*** @coversNothing */
    public function testGetHorarioWithoutEmail(){
        
        $response = $this->client->request('GET', 'horario', 
        ['http_errors' => false]);
        $this->assertEquals(405, $response->getStatusCode());
        
        $contentLength = $response->getHeader('Content-Length');
        $this->assertSame("556", $contentLength[0]);
    }

    /*Esta prueba muestra que para agregar un nuevo turno debe 
      existir el estudiante y el dia y hora solo se puede escoger 
      una vez, de lo contrario se genera un error al agregar horario 
      @covers ::postHorario */
    public function testPostHorario(){

        $response = $this->client->request('POST', 'horario', 
            ['form_params' => [
                'hora' => 11,
                'dia' => 'miercoles',
                'email' => 'andres.correa@mail.com'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getBody(), true);
        $this->assertSame(null, $body);
    }

    /*** @coversNothing */
    public function testPostHorarioWithoutData(){

        $response = $this->client->request('POST', 'horario');
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeader('Content-Type');
        $this->assertContains("application/json", $contentType);

        $msg = json_decode($response->getBody(), true);
        $this->assertContains("error", $msg);
    }
}
