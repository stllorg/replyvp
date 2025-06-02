<?php

require_once __DIR__ . '/vendor/autoload.php';

use ReplyVP\Controllers\AuthController;
use ReplyVP\Controllers\TicketController;
use ReplyVP\Controllers\MessageController;
use ReplyVP\Controllers\UserController;
use ReplyVP\Repositories\UserRepository;
use ReplyVP\Repositories\TicketRepository;
use ReplyVP\Repositories\MessageRepository;
use ReplyVP\Services\AuthService;
use ReplyVP\Services\TicketService;
use ReplyVP\Services\MessageService;
use ReplyVP\Services\UserService;
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
$userService = new UserService($userRepository);
$authService = new AuthService($userService);
$ticketService = new TicketService($ticketRepository);
$messageService = new MessageService($messageRepository, $ticketService, $userService);

// Initialize controllers
$authController = new AuthController($authService);
$ticketController = new TicketController($ticketService, $authService);
$messageController = new MessageController($messageService, $authService, $userService, $ticketService);
$userController = new UserController($userService, $authService, $messageService, $ticketService);

// Parse request URI
$uri = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));

// Route logic
function routeRequest($uri, $authController, $ticketController, $messageController, $userController) {
    $routes = [
        'POST' => [
            'users'                                => [$userController, 'register'],
            'auth/login'                           => [$authController, 'login'],
            'auth/authenticate'                    => [$authController, 'validate'],
            'tickets'                              => [$ticketController, 'createTicket'],
            'tickets/{id}/messages'                => [$messageController, 'createMessage'],
        ],
        'GET' => [
            'users'                                => [$userController, 'fetchUsers'],
            'tickets'                              => [$ticketController, 'getUserTickets'],
            'users/{id}/roles'                     => [$userController, 'fetchUserRoles'],
            'users/{id}/interactions'              => [$userController, "fetchUserInteractionsOnTickets"],
            'tickets/open'                         => [$ticketController, 'getAllPendingTickets'],
            'tickets/{id}'                         => [$ticketController, 'getTicketById'],
            'tickets/{id}/messages'                => [$messageController, 'getTicketMessages'],
        ],
        'PATCH' => [
            'users/{id}/roles'                     => [$userController, 'updateUserRole'],
            'tickets/{id}'                         => [$ticketController, 'updateTicket']
        ],
    ];

    $method = $_SERVER['REQUEST_METHOD'];
    $path = implode('/', $uri);

    foreach ($routes[$method] ?? [] as $pattern => $handler) {
        if (preg_match('#^' . preg_replace('#\{[^/]+\}#', '([^/]+)', $pattern) . '$#', $path, $matches)) {
            array_shift($matches); // remove full match
            call_user_func_array($handler, $matches);
            return;
        }
    }

    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}

// Run routing
routeRequest($uri, $authController, $ticketController, $messageController, $userController);