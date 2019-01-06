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
    }

    public function testPostLogin(){

        $response = $this->client->request('POST', 'login');
        $this->assertEquals(200, $response->getStatusCode());
        
    }
    
    public function testNoGetAllowed(){

        $response = $this->client->request('GET', 'login');
        $this->assertEquals(404, $response->getStatusCode());
    }
}
