<?php

class MessageRepository
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function findById(int $id): ?Message
    {
        $stmt = $this->db->prepare("SELECT * FROM messages WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $message = $stmt->fetchObject('Message');
        return $message ?: null;
    }

    public function save(Message $message): ?Message
    {
        $stmt = $this->db->prepare("INSERT INTO messages (ticket_id, user_id, message) VALUES (:ticket_id, :user_id, :message)");
        $stmt->bindParam(':ticket_id', $message->ticket_id, \PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $message->user_id, \PDO::PARAM_INT);
        $stmt->bindParam(':message', $message->message, \PDO::PARAM_INT);

        try {
            if ($stmt->execute()) {
                $message->id = $this->db->lastInsertId();
                return $message;
            }
        } catch (\PDOException $e) {
            // Handle potential duplicate entry error
            return null;
        }
        return null;
    }

    public function getAverageMessageForTicket(int $ticketId): float
    {
        $stmt = $this->db->prepare("SELECT AVG(message) AS average FROM messages WHERE ticket_id = :ticket_id");
        $stmt->bindParam(':ticket_id', $ticketId, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['average'] ?? 0;
    }
}