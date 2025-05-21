<?php

namespace ReplyVP\Controllers;

use ReplyVP\Services\UserService;
use ReplyVP\Services\AuthService;

class UserController {
    private $userService;
    private $authService;

    public function __construct(UserService $userService, AuthService $authService) {
        $this->userService = $userService;
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

    // TODO: Return the updated user id?
    public function updateUserRoles(): void {
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
            $result = $this->userService->updateUserRoles($ticketId, $userId, $data['content']);
            http_response_code(201); // Created object
            echo json_encode([
                'result' => $result
            ]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

} 