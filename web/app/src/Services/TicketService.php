<?php

namespace ReplyVP\Services;

use ReplyVP\Entities\Ticket;
use ReplyVP\Repositories\TicketRepository;

class TicketService {
    private $ticketRepository;

    public function __construct(TicketRepository $ticketRepository) {
        $this->ticketRepository = $ticketRepository;
    }

    public function createTicket($subject, $userId): Ticket {
        // Assign 0 to ticket id
        $ticket = new Ticket(
            id: 0,
            subject: $subject,
            userId: $userId);
        return $this->ticketRepository->create($ticket);
    }

    public function getUserTickets($userId): array {
        return $this->ticketRepository->findByUserId($userId);
    }

    public function getAllOpenTickets(): array {
        return $this->ticketRepository->findAllOpenTickets();
    }

    public function getTicketById($ticketId): ?Ticket {
        return $this->ticketRepository->findById($ticketId);
    }
} 