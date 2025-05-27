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

    public function update(Ticket $editedTicket): bool {
        $ticketId = $editedTicket->getId();
        $updatedSubject = $editedTicket->getSubject();
        $updatedStatus = $editedTicket->getStatus();

        if ($updatedStatus != null && $updatedStatus != null) {
            $stmt = $this->db->prepare("UPDATE tickets SET subject = ?, status = ? WHERE id = ?");
            $stmt->bind_param("ssi", $updatedSubject, $updatedStatus, $ticketId);
            $stmt->execute();

            return $stmt->affected_rows > 0;
        } else if ($updatedSubject != null && $updatedStatus == null) {
            $stmt = $this->db->prepare("UPDATE tickets SET subject = ? WHERE id = ?");
            $stmt->bind_param("si", $updatedSubject, $ticketId);
            $stmt->execute();   

            return $stmt->affected_rows > 0;
        } else if ($updatedSubject == null && $updatedStatus != null && ) {
            $stmt = $this->db->prepare("UPDATE tickets SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $updatedStatus, $ticketId);
            $stmt->execute();   

            return $stmt->affected_rows > 0;
        }
    }

    public function findByUserId($userId): array {
        $stmt = $this->db->prepare("SELECT id, subject, status, created_at FROM tickets WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $tickets = [];

        while ($row = $result->fetch_assoc()) {
            $tickets[] = new Ticket(
                id: $row['id'],
                subject: $row['subject'],
                status: $row['status'],
                createdAt: new \DateTime($row['created_at']));
        }

        return $tickets;
    }

    // Find a ticket by ID, if sucess returns the ticket, else returns null
    public function findById($id): ?Ticket {
        $stmt = $this->db->prepare("SELECT id, subject, status, is_repeat, created_at, user_id FROM tickets WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Ticket(
                id: $row['id'],
                subject: $row['subject'],
                status: $row['status'],
                createdAt: new \DateTime($row['created_at']),
                userId: $row['user_id']);
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

    public function findCreatorIdByTicketId(int $id): ?int {
        $stmt = $this->db->prepare("SELECT id, user_id FROM tickets WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $creatorId = $row['user_id'];
            return $creatorId;
        }
        return null;
    }
} 