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

        $uniqueId = uniqid();

        $userInfo = [
            'username' => 'test_user_' . $uniqueId,
            'email' => 'test_email_' . $uniqueId . '@testmail.com',
            'password' => 'test@test.com'
        ];

        $response = $client->post('/auth/register', [
            'json' => [
                'username' => $userInfo['username'],
                'email' => $userInfo['email'],
                'password' => $userInfo['password'],
            ],
        ]);

        $statusCode = $response->getStatusCode();
        $body = json_decode((string)$response->getBody(), true);

        $this->assertEquals(201, $statusCode);
        $this->assertArrayHasKey('message', $body);
        $this->assertEquals('User registered successfully', $body['message']);
    }
}
