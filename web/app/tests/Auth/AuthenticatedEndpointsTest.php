<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class AuthenticatedEndpointsTest extends TestCase
{
    private $client;
    private $authToken;
    private $testUsername;
    private $testEmail;
    private $testPassword;

    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri' => 'http://host.docker.internal:8080',
            'http_errors' => false,
        ]);
    }

    public function testRegisterAndLoginUser()
    {
        $uniqueId = uniqid();
        
        $this->testUsername = 'test_user_' . $uniqueId;
        $this->testEmail = 'test_email_' . $uniqueId . '@testmail.com';
        $this->testPassword = 'test@test.com';

        // Test 1 - Register a new User
        $registerResponse = $this->client->post('/auth/register', [
            'json' => [
                'username' => $this->testUsername,
                'email'    => $this->testEmail,
                'password' => $this->testPassword
            ]
        ]);


        $registerStatusCode = $registerResponse->getStatusCode();
        $registerBody = json_decode((string) $registerResponse->getBody(), true);

        $this->assertEquals(201, $registerStatusCode, "Expected 201 Created for registration, got $registerStatusCode. Body: " . json_encode($registerBody));
        $this->assertArrayHasKey('message', $registerBody, "Registration response should have a 'message'.");
        $this->assertEquals("User registered successfully", $registerBody['message'], "Registration message mismatch.");

        // Test 2 - Login the new user
        $loginResponse = $this->client->post('/auth/login', [
            'json' => [
                'username'    => $username,
                'password' => $password
            ]
        ]);


        $loginStatusCode = $loginResponse->getStatusCode();
        $loginBody = json_decode((string) $loginResponse->getBody(), true);

        $this->assertEquals(200, $loginStatusCode, "Expected 200 OK for login after registration, got $loginStatusCode. Body: " . json_encode($loginBody));
        $this->assertArrayHasKey('success', $loginBody, "Login response should have 'success' key.");
        $this->assertTrue($loginBody['success'], "Login should be successful.");
        $this->assertArrayHasKey('message', $loginBody, "Login response should have 'message' key.");
        $this->assertEquals("Login successful.", $loginBody['message'], "Login message mismatch.");
        $this->assertArrayHasKey('token', $loginBody, "Login response should contain a token.");

        $this->authToken = $loginBody['token'];
        $this->assertIsString($this->authToken, "The token should be a string.");
        $this->assertNotEmpty($this->authToken, "The token should not be empty.");
    }
}