<?php
include 'database.php';

header("Content-Type: application/json");

try {
    $connection = getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $connection->prepare("SELECT * FROM messages");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $messages = [];
            while ($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }

            http_response_code(200);
            echo json_encode($messages, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No messages found."], JSON_UNESCAPED_UNICODE);
        }

    } else {
        http_response_code(405);
        echo json_encode(["error" => "405 Method not allowed."], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error.", "details" => $e->getMessage()]);
}