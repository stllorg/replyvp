<?php

namespace ReplyVP\Controllers;

use ReplyVP\Services\MessageService;
use ReplyVP\Services\AuthService;

class MessageController {
    private $messageService;
    private $authService;

    public function __construct(MessageService $messageService, AuthService $authService) {
        $this->messageService = $messageService;
        $this->authService = $authService;
    }

    private function authenticate(): ?array {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No token provided']);
            return null;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $user = $this->authService->validateToken($token);
        
        if (!$user) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid token']);
            return null;
        }

        return $user;
    }

    public function createMessage($ticketId): void {
        $user = $this->authenticate();
        if (!$user) return;

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['message'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing message value']);
            return;
        }

        try {
            $message = $this->messageService->createMessage($ticketId, $data['message']);
            http_response_code(201);
            echo json_encode([
                'id' => $message->getId(),
                'ticketId' => $message->getTicketId(),
                'message' => $message->getMessage()
            ]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getTicketMessages($ticketId): ?array {
        $user = $this->authenticate();
        if (!$user) return null;

        try {
            $messages = $this->messageService->getTicketMessages($ticketId, $user['userId']);
            echo json_encode(array_map(function($message) {
                return [
                    'id' => $message->getId(),
                    'ticketId' => $message->getTicketId(),
                    'message' => $message->getMessage()
                ];
            }, $messages));
        } catch (\Exception $e) {
            http_response_code(403);
            echo json_encode(['error' => $e->getMessage()]);
            return null
        }
    }
} 