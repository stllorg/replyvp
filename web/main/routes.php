<?php

function routeRequest($uri, $authController, $ticketController, $messageController) {
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
} 