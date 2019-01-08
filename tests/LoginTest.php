<?php

namespace Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase {
    private $client;

    public function setUp(){
       
       $this->client = new Client([
        'base_uri' => 'http://estudiantes.is.escuelaing.edu.co/deportes/api/public/',
        'headers' => ['X-Powered-By' => 'PHP/7.0.8']
        ]);
    }

    public function testPostLoginWithoutData(){ 

        $response = $this->client->request('POST', 'login');
        $this->assertEquals(200, $response->getStatusCode());

        $result = json_decode($response->getBody(), true);
        $this->assertContains("error", $result);
    }
    
    /* Esta prueba muestra que sucede cuando un usuario se autentica
       correctamente al sistema y obtiene ingreso, va a fallar si se 
       corre antes que testPostEstudiante y arroja un error de datos
       de usuario incorrectos pues el usuario todavia no existe  */
    public function testPostLogin(){

        $response = $this->client->request('POST', 'login', 
            ['form_params' => [
                'email' => 'andres.correa@mail.com',
                'password' => '5up3r'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        
        $result = json_decode($response->getBody(), true);
        $token = strlen($result["token"]);
        $this->assertEquals(140, $token);
    }
    
    public function testNoOtherMethodsAllowed(){

        $response = $this->client->request('GET', 'login', 
        ['http_errors' => false]);
        $this->assertEquals(404, $response->getStatusCode());

        $response = $this->client->request('PUT', 'login', 
        ['http_errors' => false]);
        $this->assertEquals(404, $response->getStatusCode());

        $response = $this->client->request('DELETE', 'estudiante', 
        ['http_errors' => false]);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
