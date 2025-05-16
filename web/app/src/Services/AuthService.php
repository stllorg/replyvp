<?php

namespace ReplyVP\Services;

use ReplyVP\Entities\User;
use ReplyVP\Repositories\UserRepository;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AuthService {
    private $userRepository;
    private $jwtSecret; // before defined as secretKey

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
        $this->jwtSecret = $_ENV['JWT_SECRET'];
    }

    public function register($username, $email, $password) {
        if ($this->userRepository->findByEmail($email)) {
            throw new \Exception('Email already registered');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = new User(0, $username, $email, $hashedPassword);
        return $this->userRepository->create($user);
    }

    public function loginWithEmail($email, $password) {
        $user = $this->userRepository->findByEmail($email);
        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new \Exception('Invalid credentials');
        }

        return $this->generateToken($user);
    }

    public function login($username, $password) {
        $user = $this->userRepository->findByUsername($username);
        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new \Exception('Invalid credentials');
        }

        return $this->generateToken($user);
    }

    public function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            $userId = $decoded->userId;
            $userRoles = $decoded->roles;
            return [
                'userId' => $userId,
                'roles' => $roles,
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    private function generateToken(User $user) {
        $payload = [
            'userId' => $user->getId(),
            'roles' => $user->getUserRoles(),
            "iat" => time(),
            'exp' => time() + (60 * 60 * 24) // token expiration time : 24 hours
        ];

        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }
} 