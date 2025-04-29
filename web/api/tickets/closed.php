<?php
require 'api.php';

// Allow requests from any origin
header("Access-Control-Allow-Origin: *");

// Specify allowed methods (GET, POST, etc.)
header("Access-Control-Allow-Methods: GET, OPTIONS");

// Specify allowed headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $connection = getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $stmtTickets = $connection->prepare("SELECT id, subject, created_at FROM tickets WHERE status = 'closed'");
        $stmtTickets->execute();
        $stmtTickets->store_result();

        if ($stmtTickets->num_rows === 0) {
            http_response_code(404);
            echo json_encode(["message" => "No closed tickets found for this user."], JSON_UNESCAPED_UNICODE);
            $stmtTickets->close();
            exit;
        }

        $tickets = [];
        $stmtTickets->bind_result($ticket_id, $subject, $created_at);

        while ($stmtTickets->fetch()) {
            $tickets[] = [
                "id" => $ticket_id,
                "subject" => $subject,
                "status" => "closed",
                "created_at" => $created_at
            ];
        }

        $stmtTickets->close();

        http_response_code(200);
        echo json_encode(["closed_tickets" => $tickets], JSON_UNESCAPED_UNICODE);

    } else {
        http_response_code(405);
        echo json_encode(["error" => "405 Method Not Allowed."]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error.", "details" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}