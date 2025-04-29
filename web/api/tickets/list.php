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

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(["error" => "405 Method Not Allowed."]);
        exit;
    }
    // TODO: Adicionar validação com token

    $user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$user_id) {
        http_response_code(400);
        echo json_encode(["error" => "400 Bad Request: Invalid ID"]);
        exit;
    }

    $stmtUser = $connection->prepare("SELECT id FROM users WHERE id = ?");
    $stmtUser->bind_param('i', $user_id);
    $stmtUser->execute();

    $resultUser = $stmtUser->get_result();
    $user = $resultUser->fetch_assoc();
    $stmtUser->close();

    if (!$user) {
        http_response_code(404);
        echo json_encode(["error" => "User not found"]);
        exit;
    }
    
    $stmtTickets = $connection->prepare("SELECT id, subject, status, created_at FROM tickets WHERE user_id = ?");
    $stmtTickets->bind_param('i', $user_id);
    $stmtTickets->execute();
    $stmtTickets->store_result();

    if ($stmtTickets->num_rows === 0) {
        http_response_code(404);
        echo json_encode(["message" => "No tickets found for this user."], JSON_UNESCAPED_UNICODE);
        $stmtTickets->close();
        exit;
    }

    $tickets = [];
    $stmtTickets->bind_result($ticket_id, $subject, $status, $created_at);

    while ($stmtTickets->fetch()) {
        $tickets[] = [
            "id" => $ticket_id,
            "subject" => $subject,
            "status" => $status,
            "created_at" => $created_at
        ];
    }

    $stmtTickets->close();

    http_response_code(200);
    echo json_encode(["tickets" => $tickets], JSON_UNESCAPED_UNICODE);

    

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error.", "details" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}