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
            $userRoles = $this->userService->getUserRoles($userId);
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

            // 1 - Delete current Roles
            if(!$this->userService->removeAllUserRoles($targetId)){
                http_response_code(412);
                echo json_encode(["error" => "Failed to delete existing roles resource."]);
                return;
            }

            // array of strings of roles labels 
            $updatedRoles = $data['roles'];

            $mapRoles = [
                'admin' => 1,
                'manager' => 2,
                'support' => 3,
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
            $minCode = null; // Initialize with null or a default value
            if (!empty($rolesCodes)) {
                $minCode = min($rolesCodes);
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
} 