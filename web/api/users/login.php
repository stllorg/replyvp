<?php
include 'api.php';

// Allow requests from any origin
header("Access-Control-Allow-Origin: *");

// Specify allowed methods (GET, POST, etc.)
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Specify allowed headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");

header("Content-Type: application/json");

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $connection = getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['username']) || empty($input['username'])) {
            http_response_code(400);
            echo json_encode(["error" => "Username is required."]);
            exit;
        }

        if (!isset($input['password']) || empty($input['password'])) {
            http_response_code(400);
            echo json_encode(["error" => "Password is required."]);
            exit;
        }

        $stmt = $connection->prepare("SELECT id, password, email FROM users WHERE username = ?");
        $stmt->bind_param('s', $input['username']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid username or password."]);
            exit;
        }

        $user = $result->fetch_assoc();
        $stmt->close();

        if (!password_verify($input['password'], $user['password'])) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid username or password."]);
            exit;
        }

        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "Login successful.",
            "user" => [
                "id" => $user['id'],
                "username" => $input['username'],
                "email" => $user['email']
            ],
        ]);
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error.", "details" => $e->getMessage()]);
}
