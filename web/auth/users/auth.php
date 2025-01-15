<?php
require 'connection.php';
require '../libs/php-jwt-6.10.2/src/JWT.php';
require '../libs/php-jwt-6.10.2/src/Key.php';
require '../libs/php-jwt-6.10.2/src/JWTExceptionWithPayloadInterface.php';
require '../libs/php-jwt-6.10.2/src/SignatureInvalidException.php';
require '../libs/php-jwt-6.10.2/src/BeforeValidException.php';
require '../libs/php-jwt-6.10.2/src/ExpiredException.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\JWTExceptionWithPayloadInterface;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;

$secretKey = 'secretIuK2f4eg3H_teSc_s';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

function authenticate() {
    global $secretKey;
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(["error" => "Authorization header is missing."]);
        exit;
    }

    $authHeader = $headers['Authorization'];
    $token = str_replace('Bearer ', '', $authHeader);

    try {
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
        return $decoded->data;
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid or expired token.", "details" => $e->getMessage()]);
        exit;
    }
}

function getUserRoles($userId, $connection) {
    $stmt = $connection->prepare("SELECT r.name FROM roles r INNER JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = ?");
    $stmt->bind_param('i',$userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $roles = [];
    while ($row = $result-> fetch_assoc()) {
        $roles[] = $row['name'];
    }

    $stmt->close();
    return $roles;
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

        $stmt = $connection->prepare("SELECT id, password, email, username FROM users WHERE username = ?");
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

        $roles = getUserRoles($user['id'], $connection);
        
        $payload = [
            "iat" => time(),
            "exp" => time() + 3600,
            "data" => [
                "id" => $user['id'],
                "username" => $user['username'],
                "email" => $user['email'],
                "roles" => $roles
            ]
        ];

        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "Login successful.",
            "token" => $jwt,
            "user" => [
                "username" => $input['username'],
                "email" => $user['email'],
                "roles" => $roles
            ]
        ]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $userData = authenticate();
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "Access granted.",
            "user" => $userData
        ]);
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error.", "details" => $e->getMessage()]);
}