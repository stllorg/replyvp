<?php

namespace ReplyVP\Services;

use ReplyVP\Entities\User;
use ReplyVP\Repositories\UserRepository;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AuthService {
    private $userRepository;
    private $jwtSecret;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
        $this->jwtSecret = $_ENV['JWT_SECRET'];
    }

    // Creates a new User and returns it
    public function register($username, $email, $password): User {
        if ($this->userRepository->findByEmail($email)) {
            throw new \Exception('Email already registered');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = new User(0, $username, $email, $hashedPassword);
        return $this->userRepository->create($user);
    }

    // Check user email and password and returns a JSON string
    public function loginWithEmail($email, $password): string {
        $user = $this->userRepository->findByEmail($email);
        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new \Exception('Invalid credentials');
        }

        return $this->generateToken($user);
    }

    // Check user credentials and returns JSON string
    public function login($username, $password): string {
        $user = $this->userRepository->findByUsername($username);
        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new \Exception('Invalid credentials');
        }

        return $this->generateToken($user);
    }

    // Decode token, if valid returns an array
    public function validateToken($token): ?array {
        try {
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            $userId = $decoded->data->id;
            $roles = $decoded->data->roles; 
            return [
                'userId' => $userId,
                'roles' => $roles,
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    // Generates a JWT token and returns a JSON strings
    private function generateToken(User $user): string {
        $userId = $user->getId();
        $userUsername = $user->getUsername(); 
        $roles = $this->userRepository->getUserRole($userId);
        $payload = [
            "iat" => time(),
            'exp' => time() + (60 * 60 * 24),
            'data' => [
                'id' => $userId,
                'roles' => $roles
            ]
        ];

        $jwt = JWT::encode($payload, $this->jwtSecret, 'HS256');

        $responseData =  [
            "success" => true,
            "message" => "Login successful.",
            "token" => $jwt,
            "user" => [
                "id" => $userId,
                "username" => $userUsername,
                "roles" => $roles
                ]
            ];
        
        return json_encode($responseData);
    }
} 