<?php

require_once __DIR__ . '/src/Autoloader.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/libs/php-jwt-6.10.2/src/JWT.php';
require_once __DIR__ . '/libs/php-jwt-6.10.2/src/Key.php';

use Autoloader;
use Controllers\AuthController;
use Controllers\TicketController;
use Controllers\MessageController;
use Repositories\UserRepository;
use Repositories\TicketRepository;
use Repositories\MessageRepository;
use Services\AuthService;
use Services\TicketService;
use Services\MessageService;

// Register autoloader
Autoloader::register();

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
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

// Parse the URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);
array_shift($uri); // Remove the first empty element

// Route the request
try {
    switch ($uri[0]) {
        case 'auth':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    if ($uri[1] === 'register') {
                        $authController->register();
                    } elseif ($uri[1] === 'login') {
                        $authController->login();
                    }
                    break;
                default:
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
            }
            break;

        case 'tickets':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    if (count($uri) === 1) {
                        $ticketController->createTicket();
                    }
                    break;
                case 'GET':
                    if (count($uri) === 1) {
                        $ticketController->getUserTickets();
                    }
                    break;
                default:
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
            }
            break;

        case 'messages':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    if (isset($uri[1])) {
                        $messageController->createMessage($uri[1]);
                    }
                    break;
                case 'GET':
                    if (isset($uri[1])) {
                        $messageController->getTicketMessages($uri[1]);
                    }
                    break;
                default:
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