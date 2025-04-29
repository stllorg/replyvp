<?php
require 'api.php';

// Allow requests from any origin
header("Access-Control-Allow-Origin: *");

// Specify allowed methods (GET, POST, etc.)
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Specify allowed headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");

header("Content-Type: application/json");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $connection = getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(400);
            echo json_encode(["error" => "400 Bad Request: Invalid ID"]);
            exit;
        }

        // TODO: Adicionar verificação com senha e email

        $stmtUser = $connection->prepare("SELECT id FROM users WHERE id = ?");
        $stmtUser->bind_param('i', $id);
        $stmtUser->execute();
        $resultUser = $stmtUser->get_result();
        $user = $resultUser->fetch_assoc();
        $stmtUser->close();

        if (!$user) {
            http_response_code(404);
            echo json_encode(["error" => "User not found"]);
            exit;
        }

        $stmtDelete = $connection->prepare("DELETE FROM users WHERE id = ?");
        $stmtDelete->bind_param('i', $id);
        $stmtDelete->execute();
        $stmtDelete->close();

        // TODO: Anular informações da conta do usuário mantendi id e email
        // Manter registros associados ao email/tickets/mensagens  do usuário

        http_response_code(200);
        echo json_encode(["message" => "User and their tickets deleted successfully"]);

    } else {
        http_response_code(405);
        echo json_encode(["error" => "405 Method Not Allowed"]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error", "details" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}