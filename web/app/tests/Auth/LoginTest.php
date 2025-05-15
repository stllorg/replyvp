<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class LoginTest extends TestCase
{
    public function testLoginEndpointReturns200()
    {
        $client = new Client([
            'base_uri' => 'http://host.docker.internal:8080',
            'http_errors' => false,
        ]);

        $response = $client->post('/auth/login', [
            'json' => [
                'email' => 'adm@testmail.com',
                'password' => 'test@test.com'
            ]
        ]);

        $statusCode = $response->getStatusCode();
        $body = json_decode((string) $response->getBody(), true);

        $this->assertEquals(200, $statusCode, "Expected 200 OK, got $statusCode");

        $this->assertArrayHasKey('token', $body, "Response should contain a token");
    }
}
