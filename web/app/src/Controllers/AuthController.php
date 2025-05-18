<?php

namespace ReplyVP\Controllers;

use ReplyVP\Services\AuthService;

class AuthController {
    private $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function validate() {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["error" => "Authorization header is missing."]);
            exit;
        }

        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader);

        try {
            $userData = $this->authService->validate($token);
            echo json_encode($userData);
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid or expired token.", "details" => $e->getMessage()]);
            exit;
        }
    }

    public function register() {
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

    public function login() {
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