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

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        // Obtém os dados da requisição
        $input = json_decode(file_get_contents('php://input'), true);

        // Valida os campos obrigatórios
        if (!isset($input['id']) || !isset($input['old_password']) || !isset($input['old_email']) || !isset($input['new_password']) || !isset($input['new_email'])) {
            http_response_code(400);
            echo json_encode(["error" => "400 Bad Request: Missing required fields."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Valida o ID
        $id = filter_var($input['id'], FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(400);
            echo json_encode(["error" => "400 Bad Request: Invalid ID."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Valida o novo email
        if (!filter_var($input['new_email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(["error" => "400 Bad Request: Invalid email format."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Valida a nova senha
        if (strlen($input['new_password']) < 6 || strlen($input['new_password']) > 255) {
            http_response_code(400);
            echo json_encode(["error" => "400 Bad Request: Password must be between 6 and 255 characters."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Verifica se o usuário existe
        $stmtUser = $connection->prepare("SELECT id, password, email FROM users WHERE id = ?");
        $stmtUser->bind_param('i', $id);
        $stmtUser->execute();
        $resultUser = $stmtUser->get_result();
        $user = $resultUser->fetch_assoc();
        $stmtUser->close();

        if (!$user) {
            http_response_code(404);
            echo json_encode(["error" => "404 Not Found: User not found."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Verifica a senha antiga
        if (!password_verify($input['old_password'], $user['password'])) {
            http_response_code(400);
            echo json_encode(["error" => "400 Bad Request: Old password is incorrect."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Verifica o email antigo
        if ($input['old_email'] !== $user['email']) {
            http_response_code(400);
            echo json_encode(["error" => "400 Bad Request: Old email is incorrect."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Verifica se o novo email já está em uso
        $stmtCheckEmail = $connection->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmtCheckEmail->bind_param('si', $input['new_email'], $id);
        $stmtCheckEmail->execute();
        $stmtCheckEmail->store_result();
        if ($stmtCheckEmail->num_rows > 0) {
            http_response_code(409);
            echo json_encode(["error" => "409 Conflict: Email already exists."], JSON_UNESCAPED_UNICODE);
            $stmtCheckEmail->close();
            exit;
        }
        $stmtCheckEmail->close();

        // Atualiza os dados do usuário
        $hashedPassword = password_hash($input['new_password'], PASSWORD_DEFAULT);
        $stmtUpdate = $connection->prepare("UPDATE users SET email = ?, password = ? WHERE id = ?");
        $stmtUpdate->bind_param('ssi', $input['new_email'], $hashedPassword, $id);
        $stmtUpdate->execute();
        $stmtUpdate->close();

        // Retorna sucesso
        http_response_code(200);
        echo json_encode(["message" => "User updated successfully."], JSON_UNESCAPED_UNICODE);

    } else {
        http_response_code(405);
        echo json_encode(["error" => "405 Method Not Allowed"]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error", "details" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}