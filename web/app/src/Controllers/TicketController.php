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

    // Authenticates the user, if sucess returns an array with all tickets created by the user
    public function getUserTickets(): void {
        $user = $this->authenticate();
        if (!$user) return;

        try {
            $tickets = $this->ticketService->getUserTickets($user['userId']);
            echo json_encode(array_map(function($ticket) {
                return [
                    'id' => $ticket->getId(),
                    'subject' => $ticket->getSubject(),
                    'status' => $ticket->getStatus(),
                    'createdAt' => $ticket->getCreatedAt()->format(\DateTime::ATOM),
                ];
            }, $tickets));
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }
    }

    public function getTicketById($id): void {
        $user = $this->authenticate();
        if (!$user) return;

        try {
            $ticket = $this->ticketService->getTicketById((int)$id);
            if (!$ticket) {
                sendResponse(404, ['error' => 'Ticket not found']);
                return;
            }

            // Check if USER is NOT the Ticket Creator or Staff member 
            $userId = $user['userId'];
            $ticketCreatorId = $ticket->getUserId();

            if ($userId != $ticketCreatorId) {

                $guestRoles = $this->userService->getUserRolesByUserId($userId);
                $isGuestStaff = in_array("admin", $guestRoles);
                
                if (!$isGuestStaff) { // Check if is not staff
                    http_response_code(403);
                    echo json_encode(["error" => "You do not have permission to access this ticket."]);
                    return;
                }
            }
        
            $foundTicket = [
                'id' => $ticket->getId(),
                'subject' => $ticket->getSubject(),
                'userId' => $ticket->getUserId(),
                'status' => $ticket->getStatus(),
                'createdAt' => $ticket->getCreatedAt()->format(\DateTime::ATOM),
            ];
            http_response_code(200);
            echo json_encode($foundTicket);
            return;
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

    public function updateTicket($id): void {
        $user = $this->authenticate();
        if (!$user) return;

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['status']) && !isset($data['subject'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing updated data']);
            return;
        }

        // Check if status is invalid
        if (isset($data['status'])) {
            if ($data['status'] != 'open' && $data['status'] != 'in_progress' && $data['status'] != 'closed') {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid status data']);
                return;
            }
        }



        try {
            $ticket = $this->ticketService->getTicketById((int)$id);
            if (!$ticket) {
                sendResponse(404, ['error' => 'Ticket not found']);
                return;
            }

            // Check if USER is NOT the Ticket Creator or Staff member 
            $userId = $user['userId'];
            $ticketCreatorId = $ticket->getUserId();

            if ($userId != $ticketCreatorId) {

                $guestRoles = $this->userService->getUserRolesByUserId($userId);
                $isGuestStaff = in_array("admin", $guestRoles);
                
                if (!$isGuestStaff) { // Check if is not staff
                    http_response_code(403);
                    echo json_encode(["error" => "You do not have permission to access this ticket."]);
                    return;
                }
            }

            $editedSubject = null;
            $editedStatus = null;

            if (isset($data['subject'])) {
                if ($userId != $ticketCreatorId) { // Check if is the ticket creator
                    http_response_code(403);
                    echo json_encode(["error" => "Only ticket creator can update ticket subject."]);
                    return;
                }

                $editedSubject = $data['subject'];
            } else if (isset($data['status'])) {
                $editedStatus = $data['status'];
            }

            $editedTicket = new Ticket(
                id: $id,
                subject: $editedSubject,
                status: $editedStatus,
                createdAt: null);

            $this->ticketService->updateTicket($editedTicket);
        
            http_response_code(204);
            return;
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
} 