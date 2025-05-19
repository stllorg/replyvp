<?php

namespace ReplyVP\Entities;

class Ticket {
    private int $id;
    private string $subject;
    private string $status;
    private bool $isRepeat;
    private ?\DateTime $createdAt;
    private int $userId;

    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_CLOSED = 'closed';

    public function __construct(
        int $id,
        string $subject,
        int $userId = 0,
    ) {
        $this->id = $id; // Ticket ID created by database
        $this->subject = $subject;
        $this->status = self::STATUS_OPEN;
        $this->isRepeat = false;
        $this->createdAt = null; // TIMESTAMP created by database
        $this->userId = $userId;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getSubject(): string {
        return $this->subject;
    }


    public function getStatus(): bool {
        return $this->status;
    }

    public function isRepeat(): bool {
        return $this->isRepeat;
    }

    public function getCreatedAt(): \DateTime {
        return $this->createdAt;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function setSubject(string $subject): void {
        $this->subject = $subject;
    }
    
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setStatus(string $status): void {
        if (!in_array($status, [self::STATUS_OPEN, self::STATUS_IN_PROGRESS, self::STATUS_CLOSED], true)) {
            throw new \InvalidArgumentException("Invalid value for ENUM status: {$status}");
        }
        $this->status = $status;
    }

    public function setStatus(string $status): void {
        if (!in_array($status, [self::STATUS_OPEN, self::STATUS_IN_PROGRESS, self::STATUS_CLOSED], true)) {
            throw new \InvalidArgumentException("Invalid value for ENUM status: {$status}");
        }
        $this->status = $status;
    }
}