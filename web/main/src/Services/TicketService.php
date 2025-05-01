<?php

namespace Services;

use Entities\Ticket;
use Repositories\TicketRepository;

class TicketService {
    private $ticketRepository;

    public function __construct(TicketRepository $ticketRepository) {
        $this->ticketRepository = $ticketRepository;
    }

    public function createTicket($subject, $userId) {
        $ticket = new Ticket(null, $subject, $userId);
        return $this->ticketRepository->create($ticket);
    }

    public function getUserTickets($userId) {
        return $this->ticketRepository->findByUserId($userId);
    }

    public function getTicket($ticketId) {
        return $this->ticketRepository->findById($ticketId);
    }
} 