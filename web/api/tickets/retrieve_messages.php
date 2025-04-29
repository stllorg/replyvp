<?php
require 'api.php';

// Allow requests from any origin
header("Access-Control-Allow-Origin: *");

// Specify allowed methods (GET, OPTIONS)
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
        $ticket_id = isset($_GET['ticket_id']) ? (int) $_GET['ticket_id'] : null;
        $user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : null;

        if (!$ticket_id || !$user_id) {
            http_response_code(400);
            echo json_encode(["error" => "Ticket ID and User ID are required."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $stmtCheckTicket = $connection->prepare("SELECT id FROM tickets WHERE id = ? AND user_id = ?");
        $stmtCheckTicket->bind_param('ii', $ticket_id, $user_id);
        $stmtCheckTicket->execute();
        $stmtCheckTicket->store_result();

        if ($stmtCheckTicket->num_rows === 0) {
            http_response_code(404);
            echo json_encode(["error" => "Ticket not found or does not belong to the user."], JSON_UNESCAPED_UNICODE);
            $stmtCheckTicket->close();
            exit;
        }
        $stmtCheckTicket->close();

        $stmtMessages = $connection->prepare("SELECT id, message, user_id, created_at FROM ticket_messages WHERE ticket_id = ? AND user_id = ? ORDER BY created_at ASC");
        $stmtMessages->bind_param('ii', $ticket_id, $user_id);
        $stmtMessages->execute();
        $result = $stmtMessages->get_result();

        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = [
                "message_id" => $row['id'],
                "text" => $row['message'],
                "sender" => "user",
                "timestamp" => $row['created_at']
            ];
        }
        $stmtMessages->close();

        http_response_code(200);
        echo json_encode(["success" => true, "messages" => $messages], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(405);
        echo json_encode(["error" => "405 Method not allowed."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error.", "details" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}