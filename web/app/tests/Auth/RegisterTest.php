<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class RegisterTest extends TestCase
{
    public function testRegisterEndpointReturnsSuccessMessage()
    {
        $client = new Client([
            'base_uri' => 'http://host.docker.internal:8080',
            'http_errors' => false,
        ]);

        $response = $client->post('/auth/register', [
            'json' => [
                'username' => 'newuser3636',
                'email' => 'tester3636@testmail.com',
                'password' => 'testerz1Bz2R',
            ],
        ]);

        $statusCode = $response->getStatusCode();
        $body = json_decode((string)$response->getBody(), true);

        $this->assertEquals(201, $statusCode);
        $this->assertArrayHasKey('message', $body);
        $this->assertEquals('User registered successfully', $body['message']);
    }
}
