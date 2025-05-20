<?php

namespace ReplyVP\Repositories;

use ReplyVP\Entities\Message;

class MessageRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Stores a message and returns a Message with id
    public function create(Message $msg): Message {
        $stmt = $this->db->prepare("INSERT INTO ticket_messages (ticket_id, message, user_id) VALUES (?, ?, ?)");
        $ticketId = $msg->getTicketId();
        $content = $msg->getContent();
        $userId = $msg->getUserId();
        $stmt->bind_param("isi", $ticketId, $content, $userId);
        $stmt->execute();
        $msg->setId($stmt->insert_id);
        return $msg;
    }

    public function findByTicketId($ticketId): array {
        $stmt = $this->db->prepare("SELECT id, ticket_id, message, created_at, user_id FROM ticket_messages WHERE ticket_id = ?");
        $stmt->bind_param("i", $ticketId);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = [];
        
        while ($row = $result->fetch_assoc()) {
            $messages[] = new Message(
                id: $row['id'],
                ticketId: $row['ticket_id'],
                userId: $row['user_id'],
                content: $row['message'],
                createdAt: new \DateTime($row['created_at']));
        }

        return $messages;
    }
} 