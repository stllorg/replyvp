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

        if (!isset($input['username']) || strlen($input['username']) > 50 || empty($input['username'])) {
            http_response_code(400);
            echo json_encode(["error" => "Username is invalid or too long/empty."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (!isset($input['email']) || !filter_var($input['email'], FILTER_VALIDATE_EMAIL) || strlen($input['email']) > 100) {
            http_response_code(400);
            echo json_encode(["error" => "Email is invalid."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (!isset($input['password']) || strlen($input['password']) < 6 || strlen($input['password']) > 255) { // Minimum password length 6 (adjust as needed)
            http_response_code(400);
            echo json_encode(["error" => "Password is invalid. Must be at least 6 characters."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $stmtCheck = $connection->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmtCheck->bind_param('ss', $input['username'], $input['email']);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows > 0) {
            http_response_code(409);
            echo json_encode(["error" => "Username or email already exists."], JSON_UNESCAPED_UNICODE);
            $stmtCheck->close();
            exit;
        }
        $stmtCheck->close();

        $hashedPassword = password_hash($input['password'], PASSWORD_DEFAULT);

        $stmt = $connection->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $input['username'], $input['email'], $hashedPassword);
        $stmt->execute();

        http_response_code(201);
        echo json_encode(["success" => true, "id" => $connection->insert_id], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(405);
        echo json_encode(["error" => "405 Method not allowed."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error.", "details" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}