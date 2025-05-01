<?php

namespace ReplyVP\Services;

use ReplyVP\Entities\Message;
use ReplyVP\Repositories\MessageRepository;
use ReplyVP\Repositories\TicketRepository;

class MessageService {
    private $messageRepository;
    private $ticketRepository;

    public function __construct(MessageRepository $messageRepository, TicketRepository $ticketRepository) {
        $this->messageRepository = $messageRepository;
        $this->ticketRepository = $ticketRepository;
    }

    public function createMessage($ticketId, $message) { // ticket id and message content 
        $length = strlen($message);
        if ($length < 1 || $length > 249) {
            throw new \Exception('Message must be between 1 and 249 characters');
        }

        $message = new Message(null, $ticketId, $message); // message id, ticket id, message content
        return $this->messageRepository->create($message);
    }

    public function getTicketMessages($ticketId, $requestingUserId) {
        $ticket = $this->ticketRepository->findById($ticketId);
        if (!$ticket) {
            throw new \Exception('Ticket not found');
        }

        if ($ticket->getUserId() !== $requestingUserId) { // restricts ticket's messages visualization to the creathor of the ticket
            throw new \Exception('Unauthorized to view messages');
        }

        return $this->messageRepository->findByTicketId($ticketId);
    }
} 