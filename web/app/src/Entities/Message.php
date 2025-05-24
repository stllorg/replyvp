<?php

namespace ReplyVP\Entities;

class Message {
    private int $id; // Generated on database
    private int $ticketId;
    private int $userId;
    private string $content;
    private ?\DateTime $createdAt; // Generated on database

    public function __construct(
        int $id,
        int $ticketId,
        int $userId,
        string $content,
        ?\DateTime $createdAt = null
    ) {
        $this->id = $id;
        $this->ticketId = $ticketId;
        $this->userId = $userId;
        $this->content = $content;
        $this->createdAt = $createdAt;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTicketId(): int {
        return $this->ticketId;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function getContent(): string {
        return $this->content;
    }
    
    public function getCreatedAt(): \DateTime {
        return $this->createdAt;
    } 

    public function setId(int $id): void {
        $this->id = $id;
    }
    
    public function setTicketId(int $ticketId): void {
        $this->ticketId = $ticketId;
    }

    public function setContent(string $content): void {
        $this->content = $content;
    }

    public function setCreatedAt(\DateTime $createdAt): void {
        $this->createdAt = $createdAt;
    }
}