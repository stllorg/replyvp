<?php
require 'api.php';

// Allow requests from any origin
header("Access-Control-Allow-Origin: *");

// Specify allowed methods (GET, POST, etc.)
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Specify allowed headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $connection = getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['ticket_id']) || !is_numeric($input['ticket_id'])) {
            http_response_code(400);
            echo json_encode(["error" => "Ticket ID is invalid."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (!isset($input['user_id']) || !is_numeric($input['user_id'])) {
            http_response_code(400);
            echo json_encode(["error" => "User ID is invalid."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (!isset($input['message']) || empty(trim($input['message']))) {
            http_response_code(400);
            echo json_encode(["error" => "Message cannot be empty."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $ticket_id = (int) $input['ticket_id'];
        $user_id = (int) $input['user_id'];
        $message = trim($input['message']);

        $stmtCheckTicket = $connection->prepare("SELECT id FROM tickets WHERE id = ?");
        $stmtCheckTicket->bind_param('i', $ticket_id);
        $stmtCheckTicket->execute();
        $stmtCheckTicket->store_result();

        if ($stmtCheckTicket->num_rows === 0) {
            http_response_code(404);
            echo json_encode(["error" => "Ticket not found."], JSON_UNESCAPED_UNICODE);
            $stmtCheckTicket->close();
            exit;
        }
        $stmtCheckTicket->close();

        $stmtMessage = $connection->prepare("INSERT INTO ticket_messages (ticket_id, message, user_id) VALUES (?, ?, ?)");
        $stmtMessage->bind_param('isi', $ticket_id, $message, $user_id);
        $stmtMessage->execute();
        $message_id = $connection->insert_id;

        $stmtGetTimestamp = $connection->prepare("SELECT created_at FROM ticket_messages WHERE id = ?");
        $stmtGetTimestamp->bind_param('i', $message_id);
        $stmtGetTimestamp->execute();
        $stmtGetTimestamp->bind_result($created_at);
        $stmtGetTimestamp->fetch();
        $stmtGetTimestamp->close();

        http_response_code(201);
        echo json_encode([
            "success" => true,
            "added" => [
                "ticket_id" => $ticket_id,
                "message_id" => $message_id,
                "text" => $message,
                "sender" => $user_id,
                "timestamp" => $created_at
            ]
        ], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(405);
        echo json_encode(["error" => "405 Method not allowed."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error.", "details" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}