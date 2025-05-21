<?php

namespace ReplyVP\Repositories;

use ReplyVP\Entities\User;

class UserRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create(User $user): User {
        $this->db->autocommit(FALSE);

        try {
            $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Database prepare failed for user: " . $this->db->error);
            }

            $username = $user->getUsername();
            $email = $user->getEmail();
            $password = $user->getPassword();
            
            $stmt->bind_param("sss", $username, $email, $password);
            $stmt->execute();

            if ($stmt->error) {
                throw new Exception("Failed to insert new user: " . $stmt->error);
            }

            $user->setId($stmt->insert_id);
            $stmt->close();

            $userId = $user->getId();

            $stmtRole = $this->db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, 4)");

            if (!$stmtRole) {
                throw new Exception("Database prepare failed for role data: " . $this->db->error);
            }

            $stmtRole->bind_param("i", $userId);

            $stmtRole->execute();

            if ($stmtRole->error) {
                throw new Exception("Failed to create Role data: " . $stmtRole->error);
            }

            $stmtRole->close();

            $this->db->commit();

            return $user;
        }
        catch (Exception $e) {
            $this->db->rollback();
            error_log("Error creating user: " . $e->getMessage());
            throw $e;
        }
    }

    public function findByEmail($email): ?User {
        $stmt = $this->db->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new User($row['id'], $row['username'], $row['email'], $row['password']);
        }
        return null;
    }

    public function findByUsername(string $username): ?User {
        $stmt = $this->db->prepare("SELECT id, username, email, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $user = new User($row['id'], $row['username'], $row['email'], $row['password']);
            return $user;
        }
        return null;
    } 

    public function findById($id): ?User {
        $stmt = $this->db->prepare("SELECT id, username, email, password FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new User($row['id'], $row['username'], $row['email'], $row['password']);
        }
        return null;
    }

    public function findRolesByUserId($id): array {
        $roles = [];

        $stmt = $this->db->prepare("SELECT r.name FROM roles r INNER JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = ?");

        if (!$stmt) {
            throw new Exception("Database prepare failed: " . $this->db->error);
        }

        $stmt->bind_param('i',$id);
        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result-> fetch_assoc()) {
            $roles[] = $row['name'];
        }

        $stmt->close();
        return $roles;
    }
} 