<?php
include 'database.php';

header("Content-Type: application/json");

try {
    $connection = getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['message']) || strlen($input['message']) > 250) {
            http_response_code(400);
            echo json_encode(["error" => "The message is invalid or too long."]);
            exit;
        }

        $stmt = $connection->prepare("INSERT INTO messages (content) VALUES (?)");
        $stmt->bind_param('s', $input['message']);
        $stmt->execute();

        echo json_encode(["success" => true, "id" => $connection->insert_id]);
    } else {
        http_response_code(405);
        echo json_encode(["error" => "405 Method not allowed."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro no servidor.", "details" => $e->getMessage()]);
}
