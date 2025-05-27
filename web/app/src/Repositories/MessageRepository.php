<?php

namespace ReplyVP\Repositories;

use ReplyVP\Entities\Message;

class MessageRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /*
    Stores a message with:
    - ticket_id: Id of the ticket that will get the new message
    - user_id: User id
    - message: Message content
    
    Returns the created message with id
    */
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

    /**
     * Find unique ticket IDs of all tickets where a specific User interacted by message at least once.
     *
     * @param int $userId The ID of the user whose ticket activity is being queried.
     * @return array An array of unique ticket IDs where the user has interacted by message.
     */
    public function findTicketIdsByUserId(int $userId): array {
        $stmt = $this->db->prepare("SELECT DISTINCT ticket_id FROM ticket_messages WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $ticketIds = [];
        
        while ($row = $result->fetch_assoc()) {
            $ticketIds[] = $row['ticket_id'];
        }

        return $ticketIds;
    }
} 