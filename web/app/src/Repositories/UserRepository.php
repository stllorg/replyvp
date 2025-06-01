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

    /**
     * Inserts a new user role assignment.
     *
     * @param int $userId
     * @param int $roleId
     * @return bool True on success, false on failure.
     */
    public function insertUserRole(int $userId, int $roleId): bool {
        $query = "INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $userId, $roleId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    /**
     * Deletes all roles for a given user.
     *
     * @param int $userId
     * @return bool True on success, false on failure.
     */
    public function deleteAllUserRoles(int $userId): bool {
        $stmt = $this->db->prepare("DELETE FROM user_roles WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function findUsers(int $page, int $limit = 15): array {
        $offset = ($page - 1) * $limit;

        $countQuery = "SELECT COUNT(*) AS total FROM users";
        $countResult = $this->db->query($countQuery);
        $totalUsers = 0;

        if ($countResult) {
            $row = $countResult->fetch_assoc();
            $totalUsers = $row['total'];
        }


        $dataQuery = "SELECT id, username, email FROM users ORDER BY id ASC LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($dataQuery);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = [
                    "id" => $row['id'],
                    "username" => $row['username'],
                    "email" => $row['email']
                ];
            }
        }

        $totalPages = ceil($totalUsers / $limit);

        $response = [
            "users" => $users,
            "pagination" => [
                "currentPage" => $page,
                "perPage" => $limit,
                "totalUsers" => $totalUsers,
                "totalPages" => $totalPages
            ]
        ];

        return $response;
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