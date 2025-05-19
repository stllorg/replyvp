<?php

namespace ReplyVP\Repositories;

use ReplyVP\Entities\Ticket;

class TicketRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create(Ticket $ticket): Ticket {
        $stmt = $this->db->prepare("INSERT INTO tickets (subject, status, user_id) VALUES (?, ?, ?)");
        $subject = $ticket->getSubject();
        $status = Ticket::STATUS_OPEN;
        $userId = $ticket->getUserId();
        $stmt->bind_param("ssi", $subject, $status , $userId);
        $stmt->execute();
        $ticket->setId($stmt->insert_id);
        return $ticket;
    }

    public function findByUserId($userId): array {
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

    // Find a ticket by ID, if sucess returns the ticket, else returns null
    public function findById($id): ?Ticket {
        $stmt = $this->db->prepare("SELECT id, subject, user_id FROM tickets WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Ticket($row['id'], $row['subject'], $row['user_id']);
        }
        return null;
    }

    public function findAllOpenTickets(): array {
        $query = "SELECT t.id, t.subject, t.created_at FROM tickets t JOIN users u ON t.user_id = u.id WHERE t.status = 'open'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $tickets = [];
        while ($row = $result->fetch_assoc()) {
            $tickets[] = [
                "id" => $row['id'],
                "subject" => $row['subject'],
                "created_at" => $row['created_at']
            ];
        }
        return $tickets;
    }
} 