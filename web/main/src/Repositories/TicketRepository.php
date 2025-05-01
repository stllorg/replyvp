<?php

namespace ReplyVP\Repositories;

use Entities\Ticket;

class TicketRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create(Ticket $ticket) {
        $stmt = $this->db->prepare("INSERT INTO tickets (subject, user_id) VALUES (?, ?)");
        $subject = $ticket->getName();
        $userId = $ticket->getUserId();
        $stmt->bind_param("si", $subject, $userId);
        $stmt->execute();
        $ticket->setId($stmt->insert_id);
        return $ticket;
    }

    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT id, subject, user_id FROM tickets WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $tickets = [];
        while ($row = $result->fetch_assoc()) {
            $tickets[] = new Ticket($row['id'], $row['subject'], $row['user_id']);
        }
        return $tickets;
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT id, subject, user_id FROM tickets WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Ticket($row['id'], $row['subject'], $row['user_id']);
        }
        return null;
    }
} 