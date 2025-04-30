<?php

use TicketSys\Controller\TicketController;
use TicketSys\Controller\MessageController;
use TicketSys\Controller\UserController;

$ticketController = new TicketController();
$messageController = new MessageController();
$userController = new UserController();

$routes = [
    [
        'method' => 'GET',
        'pattern' => '/tickets',
        'handler' => [$ticketController::class, 'getAllTickets'],
    ],
    [
        'method' => 'POST',
        'pattern' => '/tickets',
        'handler' => [$ticketController::class, 'createTicket'],
    ],
    [
        'method' => 'GET',
        'pattern' => '/tickets/{id}',
        'handler' => [$ticketController::class, 'getTicket'],
    ],
    [
        'method' => 'GET',
        'pattern' => '/tickets/{id}/messages',
        'handler' => [$messageController, 'getTicketMessages']
    ],
    [
        'method' => 'POST',
        'pattern' => '/tickets/{id}/messages',
        'handler' => [$messageController::class, 'postTicketMessage'],
    ],
    [
        'method' => 'POST',
        'pattern' => '/register',
        'handler' => [$userController::class, 'register'],
    ],
    [
        'method' => 'POST',
        'pattern' => '/login',
        'handler' => [$userController::class, 'login'],
    ],
];

function route(array $routes): void
{
    $requestUri = $_SERVER['REQUEST_URI'];
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    // Remove query string from the URI
    $uri = strtok($requestUri, '?');
    // Remove trailing slash
    $uri = rtrim($uri, '/');
    // Explode URI segments
    $uriSegments = explode('/', $uri);
    array_shift($uriSegments); // Remove the first empty element

    foreach ($routes as $route) {
        $pattern = $route['pattern'];
        $patternSegments = explode('/', $pattern);
        array_shift($patternSegments);

        $params = [];
        $match = true;

        if (count($uriSegments) === count($patternSegments)) {
            foreach ($patternSegments as $index => $segment) {
                if (strpos($segment, '{') === 0 && substr($segment, -1) === '}') {
                    // Extract parameter name
                    $paramName = substr($segment, 1, -1);
                    $params[$paramName] = $uriSegments[$index];
                } elseif ($segment !== $uriSegments[$index]) {
                    $match = false;
                    break;
                }
            }
        } else {
            $match = false;
        }

        if ($requestMethod === strtoupper($route['method']) && $match) {
            // Merge POST data and URL parameters
            $data = array_merge($_POST, $params);
            // Instantiate the controller and call the handler method
            $controllerName = $route['handler'][0];
            $methodName = $route['handler'][1];

            //  Added this block to handle class instantiation and method calling
            switch ($controllerName) {
                case UserController::class:
                    $controller = new UserController(new UserService(new UserRepository(Database::getConnection())));
                    break;
                case ProductController::class:
                    $controller = new ProductController(new ProductService(new ProductRepository(Database::getConnection()), new UserRepository(Database::getConnection())));
                    break;
                case RatingController::class:
                    $controller = new RatingController(new RatingService(new RatingRepository(Database::getConnection()), new ProductRepository(Database::getConnection()), new UserRepository(Database::getConnection())));
                    break;
                default:
                    throw new Exception("Controller class not found: $controllerName");
            }
            $controller->$methodName($data);
            return; // Exit after successful match
        }
    }
    // Handle 404 Not Found if no route matches
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Route not found']);
}

// Process the routes
route($routes);