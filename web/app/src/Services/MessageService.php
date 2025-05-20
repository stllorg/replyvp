<?php

namespace ReplyVP\Services;

use ReplyVP\Entities\Message;
use ReplyVP\Repositories\MessageRepository;
use ReplyVP\Repositories\TicketRepository;

class MessageService {
    private $messageRepository;
    private $ticketService;

    public function __construct(MessageRepository $messageRepository, TicketService $ticketService) {
        $this->messageRepository = $messageRepository;
        $this->ticketService = $ticketService;
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

    public function getTicketMessages($ticketId) {
        $ticket = $this->ticketService->getTicketById($ticketId);
        if (!$ticket) {
            throw new \Exception('Ticket not found');
        }

        $messages = $this->messageRepository->findByTicketId($ticketId);
        return $messages;
    }
} 