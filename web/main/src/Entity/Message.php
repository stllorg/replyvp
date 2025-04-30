<?php

namespace TicketSys\Entity;

class Message {
    public int $id;
    public int $ticket_id;
    public int $user_id;
    public string $message;
    public \DateTime $created_at;

    public function __construct(
        int $id,
        int $ticket_id,
        int $user_id,
        string $message,
        \DateTime $created_at;
    ) {
        $this->id = $id;
        $this->ticket_id = $ticket_id;
        $this->user_id = $user_id;
        $this->message = $message;
        $this->created_at = $created_at;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getTicketId(): string {
        return $this->ticket_id;
    }

    public function getUserId(): string {
        return $this->user_id;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function setMessage(string $message): void {
        $this->message = $message;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }
}