<?php

namespace ReplyVP\Services;

use Entities\User;
use Repositories\UserRepository;
use \Firebase\JWT\JWT;

class AuthService {
    private $userRepository;
    private $jwtSecret = 'secretIuK2f4eg3H_teSc_s'; // before defined as secretKey

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function register($username, $email, $password) {
        if ($this->userRepository->findByEmail($email)) {
            throw new \Exception('Email already registered');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = new User(null, $username, $email, $hashedPassword);
        return $this->userRepository->create($user);
    }

    public function login($email, $password) {
        $user = $this->userRepository->findByEmail($email);
        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new \Exception('Invalid credentials');
        }

        return $this->generateToken($user);
    }

    public function validateToken($token) {
        try {
            $decoded = JWT::decode($token, $this->jwtSecret, array('HS256'));
            return $this->userRepository->findById($decoded->userId);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function generateToken(User $user) {
        $payload = [
            'userId' => $user->getId(),
            'email' => $user->getEmail(),
            'exp' => time() + (60 * 60 * 24) // token expiration time : 24 hours
        ];

        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }
} 