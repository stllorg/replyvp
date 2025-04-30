<?php

class TicketRepository
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function findById(int $id): ?Ticket
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetchObject('Ticket');
        return $product ?: null;
    }

    public function save(Ticket $product): ?Ticket
    {
        $stmt = $this->db->prepare("INSERT INTO products (subject, user_id) VALUES (:subject, :user_id)");
        $stmt->bindParam(':subject', $product->subject);
        $stmt->bindParam(':user_id', $product->user_id, \PDO::PARAM_INT);

        if ($stmt->execute()) {
            $product->id = $this->db->lastInsertId();
            return $product;
        }
        return null;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM products");
        return $stmt->fetchAll(\PDO::FETCH_CLASS, 'Ticket');
    }
}