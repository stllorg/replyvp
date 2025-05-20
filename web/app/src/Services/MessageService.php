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

    // Executes logic to create Message, if sucess returns the new Message with id
    public function createMessage($ticketId, $userId, $content): Message { // ticket id and message content 
        $length = strlen($content);
        if ($length < 1 || $length > 249) {
            throw new \Exception('Message must be between 1 and 249 characters');
        }

        $message = new Message($ticketId, $userId, $content); // ticket id, user id, message content
        $message = new Message(
            ticketId: $ticketId,
            userId: $userId,
            content: $content);
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