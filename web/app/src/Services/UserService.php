<?php

namespace ReplyVP\Services;

use ReplyVP\Entities\User;
use ReplyVP\Repositories\UserRepository;

class UserService {
    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function createUser($user): User {
        return $this->userRepository->create($user);
    }

    public function getUserById($userId): ?User {
        return $this->userRepository->findById($userId);
    }

    public function getUserByUsername(string $userName): ?User {
        return $this->userRepository->findByUsername($userName);
    }

    public function getUserByEmail(string $email): ?User {
        return $this->userRepository->findByEmail($email);
    }

    public function getUserRoles($userId): array {
        return $this->userRepository->findRolesByUserId($userId);
    }
} 