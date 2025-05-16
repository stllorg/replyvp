<?php

require_once __DIR__ . '/vendor/autoload.php';

use ReplyVP\Controllers\AuthController;
use ReplyVP\Controllers\TicketController;
use ReplyVP\Controllers\MessageController;
use ReplyVP\Repositories\UserRepository;
use ReplyVP\Repositories\TicketRepository;
use ReplyVP\Repositories\MessageRepository;
use ReplyVP\Services\AuthService;
use ReplyVP\Services\TicketService;
use ReplyVP\Services\MessageService;
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Set headers for API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

/**
 * Database connection
 */
function getConnection() {
    $dbHost = $_ENV['DB_HOST'] ?? 'db';
    $dbName = $_ENV['DB_NAME'] ?? 'api';
    $dbUser = $_ENV['DB_USER'] ?? 'root';
    $dbPassword = $_ENV['DB_PASSWORD'] ?? 'root';

    try {
        $mysqli = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

        if ($mysqli->connect_error) {
            throw new Exception("Database connection failed: " . $mysqli->connect_error);
        }

        if (!$mysqli->set_charset("utf8mb4")) {
            throw new Exception("Failed to set charset: " . $mysqli->error);
        }

        return $mysqli;
    } catch (Exception $e) {
        sendResponse(500, ['error' => 'Database connection error: ' . $e->getMessage()]);
    }
}

/**
 * Helper function to send JSON responses
 */
function sendResponse($statusCode, $data) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}
    // Get database connection
$db = getConnection();

// Initialize repositories
$userRepository = new UserRepository($db);
$ticketRepository = new TicketRepository($db);
$messageRepository = new MessageRepository($db);

// Initialize services
$authService = new AuthService($userRepository);
$ticketService = new TicketService($ticketRepository);
$messageService = new MessageService($messageRepository, $ticketRepository);

// Initialize controllers
$authController = new AuthController($authService);
$ticketController = new TicketController($ticketService, $authService);
$messageController = new MessageController($messageService, $authService);

// Parse request URI
$uri = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));

// Route logic
function routeRequest($uri, $authController, $ticketController, $messageController) {
    try {
        switch ($uri[0] ?? '') {
            case 'auth':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (($uri[1] ?? '') === 'register') {
                        $authController->register();
                    } elseif (($uri[1] ?? '') === 'login') {
                        $authController->login();
                    } elseif (($uri[1] ?? '') === 'authenticate') {
                        $authController->validate();
                    } else {
                        http_response_code(404);
                        echo json_encode(['error' => 'Not found']);
                    }
                } else {
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                }
                break;

            case 'tickets':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && count($uri) === 1) {
                    $ticketController->createTicket();
                } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && count($uri) === 1) {
                    $ticketController->getUserTickets();
                } else {
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                }
                break;

            case 'messages':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($uri[1])) {
                    $messageController->createMessage($uri[1]);
                } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($uri[1])) {
                    $messageController->getTicketMessages($uri[1]);
                } else {
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                }
                break;

            default:
                http_response_code(404);
                echo json_encode(['error' => 'Not found']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal server error']);
    }
}

// Run routing
routeRequest($uri, $authController, $ticketController, $messageController);