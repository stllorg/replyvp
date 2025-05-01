<?php

require_once __DIR__ . '/src/Autoloader.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/libs/php-jwt-6.10.2/src/JWT.php';
require_once __DIR__ . '/libs/php-jwt-6.10.2/src/Key.php';

use Autoloader;
use Controllers\AuthController;
use Controllers\ProductController;
use Controllers\RatingController;
use Repositories\UserRepository;
use Repositories\ProductRepository;
use Repositories\RatingRepository;
use Services\AuthService;
use Services\ProductService;
use Services\RatingService;

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
$productRepository = new ProductRepository($db);
$ratingRepository = new RatingRepository($db);

// Initialize services
$authService = new AuthService($userRepository);
$productService = new ProductService($productRepository);
$ratingService = new RatingService($ratingRepository, $productRepository);

// Initialize controllers
$authController = new AuthController($authService);
$productController = new ProductController($productService, $authService);
$ratingController = new RatingController($ratingService, $authService);

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

        case 'products':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    if (count($uri) === 1) {
                        $productController->createProduct();
                    }
                    break;
                case 'GET':
                    if (count($uri) === 1) {
                        $productController->getUserProducts();
                    }
                    break;
                default:
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
            }
            break;

        case 'ratings':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    if (isset($uri[1])) {
                        $ratingController->createRating($uri[1]);
                    }
                    break;
                case 'GET':
                    if (isset($uri[1])) {
                        $ratingController->getProductRatings($uri[1]);
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