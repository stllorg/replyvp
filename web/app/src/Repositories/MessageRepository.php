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
        $stmt = $this->db->prepare("INSERT INTO messages (ticket_id, message) VALUES (?, ?)");
        $ticketId = $msg->getTicketId();
        $msgText = $msg->getMessage();
        $stmt->bind_param("ii", $ticketId, $msgText);
        $stmt->execute();
        $reply->setId($stmt->insert_id);
        return $msg;
    }

    public function findByTicketId($ticketId): array {
        $stmt = $this->db->prepare("SELECT id, ticket_id, message FROM messages WHERE ticket_id = ?");
        $stmt->bind_param("i", $ticketId);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = new Message($row['id'], $row['ticket_id'], $row['message']);
        }
        return $messages;
    }
} 