<?php

namespace ReplyVP\Controllers;

use ReplyVP\Services\AuthService;

class AuthController {
    private $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    // Validate JWT Token, if valid returns an array with userId and userRole
    public function validate(): ?array {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["error" => "Authorization header is missing."]);
            exit;
        }

        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader);

        try {
            $userData = $this->authService->validateToken($token);
            echo json_encode($userData);
            return null;

        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid or expired token.", "details" => $e->getMessage()]);
            exit;
        }
    }

    public function login(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['username']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        try {
            $response_json = $this->authService->login($data['username'], $data['password']);
            echo $response_json;
            return;
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
} 