<?php

namespace ReplyVP\Controllers;

use ReplyVP\Services\UserService;
use ReplyVP\Services\AuthService;
use ReplyVP\Services\MessageService;
use ReplyVP\Services\TicketService;

class UserController {
    private $userService;
    private $authService;
    private $messageService;
    private $ticketService;

    public function __construct(UserService $userService, AuthService $authService, MessageService $messageService, TicketService $ticketService) {
        $this->userService = $userService;
        $this->authService = $authService;
        $this->messageService = $messageService;
        $this->ticketService = $ticketService;
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

    public function register(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            exit;
        }

        try {
            $user = $this->authService->register($data['username'], $data['email'], $data['password']);
            http_response_code(201);
            echo json_encode(['message' => 'User registered successfully']);
            return;
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }

    // TODO: Return the updated user id?
    public function updateUserRole($targetId): void {
        $user = $this->authenticate();
        if (!$user) return;

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['roles'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing roles value']);
            return;
        }

        try {
            $userId = $user['userId'];
            $userRoles = $this->userService->getUserRolesByUserId($userId);
            $isUserAdmin = in_array("admin", $userRoles);

            if (!$isUserAdmin) { // Check if user is not admin
                http_response_code(403);
                echo json_encode(["error" => "You do not have permission to access this resource."]);
                return;
            }

            // Get the user targeted to receive updated roles.
            $targetUser = $this->userService->getUserById($targetId);

            if (!$targetUser) {
                sendResponse(404, ['error' => 'User not found']);
                return;
            }
            // Delete current Roles
            if(!$this->userService->removeAllUserRoles($targetId)){
                http_response_code(412);
                echo json_encode(["error" => "Failed to delete existing roles resource."]);
                return;
            }

            // array of strings of roles labels 
            $updatedRoles = $data['roles'];

            $mapRoles = [
                'admin'   => 1,
                'manager' => 2,
                'support' => 3,
                'user'    => 4,
            ];


            // array to store roles codes
            $rolesCodes = [];

            foreach ($updatedRoles as $roleName) {
                $normalizedRoleName = strtolower($roleName);
                if (array_key_exists($normalizedRoleName, $mapRoles)) {
                    $rolesCodes[] = $mapRoles[$normalizedRoleName];
                } else {
                    $rolesCodes[] = $defaultRoleId;
                }
            }

            $newRole = 4; // The number 4 is the default role
            if (!empty($rolesCodes)) {
                $newRole = min($rolesCodes);
            }

            $result = $this->userService->assignRoleToUser($targetId, $newRole);

            if (!$result) {
                http_response_code(412);
                echo json_encode(["error" => "Failed to add role to existing user."]);
                return;
            }

            http_response_code(204);
            return;
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function fetchUsers(): void {
        $user = $this->authenticate();
        if (!$user) return;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 15;

        try {

            $userId = $user['userId'];


            $guestRoles = $this->userService->getUserRolesByUserId($userId);
            $isGuestStaff = in_array("admin", $guestRoles);

            if (!$isGuestStaff) { // Check if user is not staff
                http_response_code(403);
                echo json_encode(["error" => "You do not have permission to access this ticket."]);
                return;
            }

            $users = $this->userService->getUsers($page, $limit = 15);

            if (!$users) {
                sendResponse(404, ['error' => 'Users not found']);
                return;
            }

            http_response_code(200);
            echo json_encode($users);
            return;

        } catch (\Exception $e) {
            http_response_code(403);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

    }

    public function fetchUserRoles($targetId): void {
        $user = $this->authenticate();
        if (!$user) return;

        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $userId = $user['userId'];

            if ($userId != $targetId) { // Check if User is not the target

                $guestRoles = $this->userService->getUserRolesByUserId($userId);
                $isGuestStaff = in_array("admin", $guestRoles);

                if (!$isGuestStaff) { // Check if user is not staff
                    http_response_code(403);
                    echo json_encode(["error" => "You do not have permission to access this ticket."]);
                    return;
                }
            }

            $targetUser = $this->userService->getUserById($targetId);

            if (!$targetUser) {
                sendResponse(404, ['error' => 'User not found']);
                return;
            }

            $roles = $this->userService->getUserRolesByUserId($targetId);
            
            http_response_code(201); // Created object
            echo json_encode(['roles' => $roles,]);
            return;
        } catch (\Exception $e) {
            http_response_code(403);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }
    }

    public function fetchUserInteractionsOnTickets(): void {
        $user = $this->authenticate();
        if (!$user) return;

        try {
            $userId = $user['userId'];
            $ticketIds = $this->messageService->getTicketIdsWithUserMessages($userId);
            if (!$ticketIds) {
                sendResponse(404, ['error' => 'Messages or tickets not found']);
                return;
            }

            $tickets = [];

            foreach ($ticketIds as $id) {
                $ticket = $this->ticketService->getTicketById($id);

                if ($ticket !== null) {
                    $tickets[] = [
                        "id" => $ticket->getId(),
                        "subject" => $ticket->getSubject(),
                        "status" => $ticket->getStatus(),
                        "createdAt" => $ticket->getCreatedAt()->format(\DateTime::ATOM),
                    ];
                }
            }

            echo json_encode($tickets);
            return;
        } catch (\Exception $e) {
            http_response_code(403);
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }
    }
} 