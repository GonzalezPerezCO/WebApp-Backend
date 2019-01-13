<?php

namespace Tests;

class EndpointsTest extends BaseCase {

    public function testCupos(){

        $response = $this->runApp('GET', '/cupos');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('OK', $response->getReasonPhrase());
    }

    public function testHorarioWithEmail(){

        $response = $this->runApp('GET', '/horario', ['test@mail.com']);
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('OK', $response->getReasonPhrase());
    }

    public function testEstudiante(){

        $response = $this->runApp('POST', '/estudiante');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('OK', $response->getReasonPhrase());
    }

    public function testHorario(){

        $response = $this->runApp('POST', '/horario');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('OK', $response->getReasonPhrase());
    }

    public function testLogin(){

        $response = $this->runApp('POST', '/login');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('OK', $response->getReasonPhrase());
    }
}
