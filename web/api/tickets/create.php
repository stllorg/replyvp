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

        if (!isset($input['subject']) || empty($input['subject']) || strlen($input['subject']) > 255) {
            http_response_code(400);
            echo json_encode(["error" => "Subject is invalid or too long/empty."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (!isset($input['message']) || empty($input['message'])) {
            http_response_code(400);
            echo json_encode(["error" => "Message cannot be empty."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (!isset($input['user_id']) || !is_numeric($input['user_id'])) {
            http_response_code(400);
            echo json_encode(["error" => "User ID is invalid."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $user_id = (int) $input['user_id'];

        $stmtCheck = $connection->prepare("SELECT id FROM users WHERE id = ?");
        $stmtCheck->bind_param('i', $user_id);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows === 0) {
            http_response_code(404);
            echo json_encode(["error" => "User not found."], JSON_UNESCAPED_UNICODE);
            $stmtCheck->close();
            exit;
        }
        $stmtCheck->close();

        // Criar o ticket com status open como padrão
        $stmtTicket = $connection->prepare("INSERT INTO tickets (subject, status, user_id) VALUES (?, 'open', ?)");
        $stmtTicket->bind_param('si', $input['subject'], $user_id);
        $stmtTicket->execute();
        $ticket_id = $connection->insert_id;

        // Adicionar lógica para is_repeat
        // is_repeat por padrão será FALSE
        // is_repeat vai ser TRUE se o usuário tiver um outro ticket nas últimas 24 horas.

        // Inserir a primeira mensagem do ticket
        $stmtMessage = $connection->prepare("INSERT INTO ticket_messages (ticket_id, message, user_id) VALUES (?, ?, ?)");
        $stmtMessage->bind_param('isi', $ticket_id, $input['message'], $user_id);
        $stmtMessage->execute();

        http_response_code(201);
        echo json_encode(["success" => true, "ticket_id" => $ticket_id], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(405);
        echo json_encode(["error" => "405 Method not allowed."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error.", "details" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}