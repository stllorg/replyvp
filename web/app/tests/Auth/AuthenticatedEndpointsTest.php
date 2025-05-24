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
                'username'    => $this->testUsername,
                'password' => $this->testPassword
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

        // Test 3 - CreateTicket
        $this->createTicketWithAuthenticatedUser($this->testUsername);
    }

    private function createTicketWithAuthenticatedUser(): void
    {
        // Ensure a token exists before trying to create a ticket
        $this->assertNotNull($this->authToken, "Authentication token must be set before creating a ticket.");
        $this->assertNotEmpty($this->authToken, "Authentication token must not be empty.");

        $ticketSubject = 'New ticket created from user: ' . $this->testUsername . ' - ' . uniqid();

        $createTicketResponse = $this->client->post('/users/tickets', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->authToken, // Use the class property here
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'subject' => $ticketSubject,
            ]
        ]);

        $createTicketStatusCode = $createTicketResponse->getStatusCode();
        $createTicketBody = json_decode((string) $createTicketResponse->getBody(), true);

        $this->assertEquals(201, $createTicketStatusCode, "Expected 201 Created for ticket creation, got $createTicketStatusCode. Body: " . json_encode($createTicketBody));
        $this->assertArrayHasKey('id', $createTicketBody, "Ticket creation response should contain an 'id'.");
        $this->assertArrayHasKey('subject', $createTicketBody, "Ticket creation response should contain the 'subject'.");
        $this->assertArrayHasKey('userId', $createTicketBody, "Ticket creation response should contain the 'userId'.");
        $this->assertEquals($ticketSubject, $createTicketBody['subject'], "The returned ticket subject should match the sent subject.");
        $this->assertIsInt($createTicketBody['id'], "The ticket ID should be an integer.");
        $this->assertIsInt($createTicketBody['userId'], "The userId should be an integer.");
    }
}