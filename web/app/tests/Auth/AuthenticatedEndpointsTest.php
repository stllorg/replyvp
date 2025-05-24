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
        $createdTicketData = $this->createTicketWithAuthenticatedUser($this->testUsername);
        $this->assertArrayHasKey('id', $createdTicketData, "Created ticket must have an ID.");
        $ticketId = $createdTicketData['id'];

        // Test 4 - Get created ticket by id
        $fetchedTicket = $this->getTicketById($ticketId);
        $this->assertArrayHasKey('id', $fetchedTicket, "Fetched ticket should have an 'id'.");
        $this->assertEquals($ticketId, $fetchedTicket['id'], "Fetched ticket ID should match the created ticket ID.");

        // Test 5 
        $message1Content = "This is the first test message for ticket ID: $ticketId - " . uniqid();
        $addedMessage1 = $this->postMessageByTicketId($ticketId, $message1Content);

        $message2Content = "This is the second test message for ticket ID: $ticketId - " . uniqid();
        $addedMessage2 = $this->postMessageByTicketId($ticketId, $message2Content);

        // Test 6
        $ticketMessages = $this->getTicketMessagesByTicketId($ticketId);
        $this->assertIsArray($ticketMessages, "Response for getting messages should be an array.");
        $this->assertNotEmpty($ticketMessages, "The messages array should not be empty.");
        $this->assertCount(2, $ticketMessages, "Expected 2 messages for the ticket.");
        


    }

    private function createTicketWithAuthenticatedUser(): array
    {
        // Ensure a token exists before trying to create a ticket
        $this->assertNotNull($this->authToken, "Authentication token must be set before creating a ticket.");
        $this->assertNotEmpty($this->authToken, "Authentication token must not be empty.");

        $ticketSubject = 'New ticket created from user: ' . $this->testUsername . ' - ' . uniqid();

        $response = $this->client->post('/users/tickets', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->authToken, // Use the class property here
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'subject' => $ticketSubject,
            ]
        ]);

        $createTicketStatusCode = $response->getStatusCode();
        $createTicketBody = json_decode((string) $response->getBody(), true);

        $this->assertEquals(201, $createTicketStatusCode, "Expected 201 Created for ticket creation, got $createTicketStatusCode. Body: " . json_encode($createTicketBody));
        $this->assertArrayHasKey('id', $createTicketBody, "Ticket creation response should contain an 'id'.");
        $this->assertArrayHasKey('subject', $createTicketBody, "Ticket creation response should contain the 'subject'.");
        $this->assertArrayHasKey('userId', $createTicketBody, "Ticket creation response should contain the 'userId'.");
        $this->assertEquals($ticketSubject, $createTicketBody['subject'], "The returned ticket subject should match the sent subject.");
        $this->assertIsInt($createTicketBody['id'], "The ticket ID should be an integer.");
        $this->assertIsInt($createTicketBody['userId'], "The userId should be an integer.");

        return $createTicketBody;
    }

    private function getTicketById(int $ticketId): array
    {
        $this->assertNotNull($this->authToken, "Authentication token must be set before fetching a ticket.");
        $this->assertNotEmpty($this->authToken, "Authentication token must not be empty.");

        $getResponse = $this->client->get("/tickets/{$ticketId}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->authToken,
                'Content-Type'  => 'application/json',
            ],
        ]);

        $getStatusCode = $getResponse->getStatusCode();
        $getBody = json_decode((string) $getResponse->getBody(), true);

        $this->assertEquals(200, $getStatusCode, "Expected 200 OK for fetching ticket ID $ticketId, got $getStatusCode. Body: " . json_encode($getBody));
        $this->assertIsArray($getBody, "Fetched ticket response body should be an array.");

        $this->assertArrayHasKey('id', $getBody, "Fetched ticket should have an 'id' key.");
        $this->assertIsInt($getBody['id'], "Ticket 'id' should be an integer.");
        $this->assertEquals($ticketId, $getBody['id'], "Fetched ticket 'id' should match the requested ID."); // Add this to confirm the correct ticket is fetched

        $this->assertArrayHasKey('subject', $getBody, "Fetched ticket should have a 'subject' key.");
        $this->assertIsString($getBody['subject'], "Ticket 'subject' should be a string.");

        $this->assertArrayHasKey('userId', $getBody, "Fetched ticket should have a 'userId' key.");
        $this->assertIsInt($getBody['userId'], "Ticket 'userId' should be an integer.");

        $this->assertArrayHasKey('status', $getBody, "Fetched ticket should have a 'status' key.");
        $this->assertIsString($getBody['status'], "Ticket 'status' should be a string.");

        $this->assertArrayHasKey('createdAt', $getBody, "Fetched ticket should have a 'createdAt' key.");
        $this->assertIsString($getBody['createdAt'], "Ticket 'createdAt' should be a string.");

        return $getBody;
    }

    private function postMessageByTicketId(int $ticketId, string $messageContent): array
    {
        $this->assertNotNull($this->authToken, "Authentication token must be set before adding a message.");
        $this->assertNotEmpty($this->authToken, "Authentication token must not be empty.");

        $postResponse = $this->client->post("/tickets/{$ticketId}/messages", [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->authToken,
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'content' => $messageContent,
            ]
        ]);

        $postStatusCode = $postResponse->getStatusCode();
        $postBody = json_decode((string) $postResponse->getBody(), true);

        $this->assertEquals(201, $postStatusCode, "Expected 201 Created for adding message to ticket $ticketId, got $postStatusCode. Body: " . json_encode($postBody));
        $this->assertIsArray($postBody, "Added message response body should be an array.");

        $this->assertArrayHasKey('messageId', $postBody, "Message response should have an 'id'.");
        $this->assertIsInt($postBody['messageId'], "Message 'id' should be an integer.");

        $this->assertArrayHasKey('ticketId', $postBody, "Message response should have a 'ticketId'.");
        $this->assertEquals($ticketId, $postBody['ticketId'], "Message 'ticketId' should match the target ticket ID.");

        $this->assertArrayHasKey('userId', $postBody, "Message response should have a 'userId'.");
        $this->assertIsInt($postBody['userId'], "Message 'userId' should be an integer.");

        $this->assertArrayHasKey('content', $postBody, "Message response should have 'content'.");
        $this->assertEquals($messageContent, $postBody['content'], "Message 'content' should match the sent message.");
        $this->assertIsString($postBody['content'], "Message 'content' should be a string.");

        return $postBody;
    }

    private function getTicketMessagesByTicketId(int $ticketId): array
    {
        $this->assertNotNull($this->authToken, "Authentication token must be set before fetching a ticket.");
        $this->assertNotEmpty($this->authToken, "Authentication token must not be empty.");

        $getResponse = $this->client->get("/tickets/{$ticketId}/messages", [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->authToken,
                'Content-Type'  => 'application/json',
            ],
        ]);

        $getStatusCode = $getResponse->getStatusCode();
        $getBody = json_decode((string) $getResponse->getBody(), true);

        $this->assertEquals(200, $getStatusCode, "Expected 200 OK for fetching ticket ID $ticketId, got $getStatusCode. Body: " . json_encode($getBody));
        $this->assertIsArray($getBody, "Fetched ticket response body should be an array.");

        return $getBody;
    }
}