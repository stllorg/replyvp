<?php
require_once 'api.php';

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
        // Verificar roles
        // 1 = admin
        // 2 = manager
        // 3 = support


        $query = "SELECT t.id, t.subject, t.status, t.created_at, u.username FROM tickets t JOIN users u ON t.user_id = u.id WHERE t.status = 'open'";
        $stmtTickets = $connection->prepare($query);
        $stmtTickets->execute();
        $stmtTickets->store_result();

        if ($stmtTickets->num_rows === 0) {
            http_response_code(404);
            echo json_encode(["message" => "No open tickets found."], JSON_UNESCAPED_UNICODE);
            $stmtTickets->close();
            exit;
        }

        $tickets = [];
        $stmtTickets->bind_result($ticket_id, $subject, $status, $created_at, $username);

        while ($stmtTickets->fetch()) {
            $tickets[] = [
                "id" => $ticket_id,
                "subject" => $subject,
                "status" => $status,
                "created_at" => $created_at,
                "username" => $username
            ];
        }

        $stmtTickets->close();

        http_response_code(200);
        echo json_encode(["open_tickets" => $tickets], JSON_UNESCAPED_UNICODE);

    } else {
        http_response_code(405);
        echo json_encode(["error" => "405 Method Not Allowed."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error.", "details" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}