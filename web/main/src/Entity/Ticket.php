<?php

namespace TicketSys\Entity;

class Ticket {
    public int $id;
    public string $subject;
    public bool $status;
    public bool $is_repeat;
    public \DateTime $created_at;
    public int $user_id;

    public function __construct(
        int $id,
        string $subject,
        bool $status,
        bool $is_repeat,
        \DateTime $created_at,
        int $user_id = 0,
    ) {
        $this->id = $id;
        $this->subject = $subject;
        $this->status = $status;
        $this->is_repeat = $is_repeat;
        $this->created_at = $created_at;
        $this->user_id = $user_id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getSubject(): string {
        return $this->subject;
    }

    public function setSubject(string $subject): void {
        $this->subject = $subject;
    }

    public function getStatus(): bool {
        return $this->status;
    }

    public function isRepeat(): bool {
        return $this->is_repeat;
    }

    public function getCreatedAt(): \DateTime {
        return $this->created_at;
    }

    public function getUserId(): int {
        return $this->user_id;
    }
}