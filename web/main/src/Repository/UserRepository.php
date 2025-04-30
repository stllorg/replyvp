<?php

class UserRepository
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetchObject('User');
        return $user ?: null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetchObject('User');
        return $user ?: null;
    }

    public function save(User $user): ?User
    {
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $user->username);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':password', $user->password); // Remember to hash the password before saving!

        if ($stmt->execute()) {
            $user->id = $this->db->lastInsertId();
            return $user;
        }
        return null;
    }
}