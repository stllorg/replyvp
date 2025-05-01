<?php

namespace ReplyVP\Repositories;

use Entities\User;

class UserRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create(User $user) {
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $stmt->bind_param("sss", $username, $email, $password);
        $stmt->execute();
        $user->setId($stmt->insert_id);
        return $user;
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new User($row['id'], $row['username'], $row['email'], $row['password']);
        }
        return null;
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT id, username, email, password FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new User($row['id'], $row['username'], $row['email'], $row['password']);
        }
        return null;
    }
} 