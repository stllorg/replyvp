<?php

namespace ReplyVP\Controllers;

use ReplyVP\Services\TicketService;
use ReplyVP\Services\AuthService;

class TicketController {
    private $ticketService;
    private $authService;

    public function __construct(TicketService $ticketService, AuthService $authService) {
        $this->ticketService = $ticketService;
        $this->authService = $authService;
    }

    private function authenticate() {
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

    public function createTicket() {
        $user = $this->authenticate();
        if (!$user) return;

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['subject'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing ticket subject']);
            return;
        }

        try {
            $ticket = $this->ticketService->createTicket($data['subject'], $user['userId']);
            http_response_code(201);
            echo json_encode([
                'id' => $ticket->getId(),
                'subject' => $ticket->getSubject(),
                'userId' => $ticket->getUserId()
            ]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getUserTickets() {
        $user = $this->authenticate();
        if (!$user) return;

        try {
            $tickets = $this->ticketService->getUserTickets($user->getId());
            echo json_encode(array_map(function($ticket) {
                return [
                    'id' => $ticket->getId(),
                    'subject' => $ticket->getSubject(),
                    'userId' => $ticket->getUserId()
                ];
            }, $tickets));
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getAllPendingTickets() {
        try {
            $tickets[] = $this->ticketService->getAllOpenTickets();
            
            header('Content-Type: application/json');
            echo json_encode($tickets);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }

    }
} 