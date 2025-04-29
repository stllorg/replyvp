<?php
require 'api.php';

// Allow requests from any origin
header("Access-Control-Allow-Origin: *");

// Specify allowed methods (GET, POST, etc.)
header("Access-Control-Allow-Methods: GET, OPTIONS"); // Only GET and OPTIONS are needed for listing

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
        $stmt = $connection->prepare("
            SELECT 
                u.id AS user_id, 
                u.username, 
                ur.role_id
            FROM users u
            INNER JOIN user_roles ur ON u.id = ur.user_id
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];

        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        $stmt->close();

        http_response_code(200);
        echo json_encode($users, JSON_UNESCAPED_UNICODE);

    } else {
        http_response_code(405);
        echo json_encode(["error" => "405 Method Not Allowed"]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error", "details" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}