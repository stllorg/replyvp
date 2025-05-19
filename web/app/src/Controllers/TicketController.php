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

    // Validate token, if valid returns an array with userId and userRoles
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

    // TODO: Return the new user id?
    public function createTicket(): void {
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

    // Authenticates the user, if sucess returns an array with all user tickets
    public function getUserTickets(): ?array {
        $user = $this->authenticate();
        if (!$user) return null;

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

    // Validates admin, if sucess returns all pending tickets from database.
    public function getAllPendingTickets(): ?array {
        $admin = $this->authenticate();
        if (!$admin) return null;
        if (!isset($admin['roles']) || !in_array("admin", $admin['roles'])) {
            http_response_code(403);
            echo json_encode(["error" => "The 'admin' role is required to access this resource."]);
            return null;
        };

        try {
            $tickets[] = $this->ticketService->getAllOpenTickets();
            header('Content-Type: application/json');
            echo json_encode($tickets);
            return null;
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
            return null;
        }
    }
} 