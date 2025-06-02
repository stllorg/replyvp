<?php

namespace ReplyVP\Controllers;

use ReplyVP\Services\MessageService;
use ReplyVP\Services\AuthService;
use ReplyVP\Services\UserService;
use ReplyVP\Services\TicketService;

class MessageController {
    private $messageService;
    private $authService;
    private $userService;
    private $ticketService;

    public function __construct(MessageService $messageService, AuthService $authService, UserService $userService, TicketService $ticketService) {
        $this->messageService = $messageService;
        $this->authService = $authService;
        $this->userService = $userService;
        $this->ticketService = $ticketService;
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
        
        if (!isset($data['content'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing message value']);
            return;
        }

        try {
            $userId = $user['userId'];
            $ticketCreatorId = $this->ticketService->getTicketCreator($ticketId);
            $guestRoles = $this->userService->getUserRolesByUserId($userId);

            if ($userId != $ticketCreatorId) { // Check if is not the ticket creator

                $isGuestStaff = in_array("admin", $guestRoles);

                if (!$isGuestStaff) { // Check if is not staff
                    http_response_code(403);
                    echo json_encode(["error" => "You do not have permission to access this ticket."]);
                    return;
                }
            }

            $message = $this->messageService->createMessage($ticketId, $userId, $data['content']);
            http_response_code(201); // Created object
            echo json_encode([
                'messageId' => $message->getId(),
                'ticketId'  => $message->getTicketId(),
                'userId'    => $message->getUserId(),
                'content'   => $message->getContent(),
                'roles'     => $guestRoles
            ]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getTicketMessages($ticketId): void {
        $user = $this->authenticate();
        if (!$user) return;

        try {
            $userId = $user['userId'];
            $ticketCreatorId = $this->ticketService->getTicketCreator($ticketId);

            if ($userId != $ticketCreatorId) { // Check if is not the ticket creator

                $guestRoles = $this->userService->getUserRolesByUserId($userId);
                $isGuestStaff = in_array("admin", $guestRoles);

                if (!$isGuestStaff) { // Check if is not staff
                    http_response_code(403);
                    echo json_encode(["error" => "You do not have permission to access this ticket."]);
                    return;
                }
            }

            $messages = $this->messageService->getTicketMessages($ticketId);
            if (!$messages) {
                sendResponse(404, ['error' => 'Messages not found']);
                return;
            }

            $outputMessage = function($message) {
                return [
                    'id' => $message->getId(),
                    'ticketId' => $message->getTicketId(),
                    'userId' => $message->getUserId(),
                    'content' => $message->getContent(),
                    'createdAt' => $message->getCreatedAt()->format(\DateTime::ATOM),
                    'roles' => $this->userService->getUserRolesByUserId($message->getUserId()),
                ];
            };

            echo json_encode(array_map($outputMessage, $messages));
            return;
        } catch (\Exception $e) {
            http_response_code(403);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }
    }
} 