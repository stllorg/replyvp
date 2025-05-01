<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/libs/php-jwt-6.10.2/src/JWT.php';
require_once __DIR__ . '/libs/php-jwt-6.10.2/src/Key.php';
require_once __DIR__ . '/routes.php';

use ReplyVP\Controllers\AuthController;
use ReplyVP\Controllers\TicketController;
use ReplyVP\Controllers\MessageController;
use ReplyVP\Repositories\UserRepository;
use ReplyVP\Repositories\TicketRepository;
use ReplyVP\Repositories\MessageRepository;
use ReplyVP\Services\AuthService;
use ReplyVP\Services\TicketService;
use ReplyVP\Services\MessageService;


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

// Route the request with routes.php
routeRequest($uri, $authController, $ticketController, $messageController);