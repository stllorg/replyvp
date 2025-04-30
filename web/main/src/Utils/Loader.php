<?php

function myAutoloader(string $className): void
{
    $classMap = [
        'User' => __DIR__ . '/../Entity/User.php',
        'Ticket' => __DIR__ . '/../Entity/Ticket.php',
        'Message' => __DIR__ . '/../Entity/Message.php',
        'UserRepository' => __DIR__ . '/../Repository/UserRepository.php',
        'TicketRepository' => __DIR__ . '/../Repository/TicketRepository.php',
        'MessageRepository' => __DIR__ . '/../Repository/MessageRepository.php',
        'UserService' => __DIR__ . '/../Service/UserService.php',
        'TicketService' => __DIR__ . '/../Service/TicketService.php',
        'MessageService' => __DIR__ . '/../Service/MessageService.php',
        'UserController' => __DIR__ . '/../Controller/UserController.php',
        'TicketController' => __DIR__ . '/../Controller/TicketController.php',
        'Database' => __DIR__ . '/Database.php',
    ];

    if (isset($classMap[$className]) && file_exists($classMap[$className])) {
        require_once $classMap[$className];
    } else {
        //  If the class is not in the map, you can try aPSR-4 style loading (commented out here, for now).
        /*
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $namespace = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
            $fileName = __DIR__ . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        if (file_exists($fileName)) {
            require_once $fileName;
        }
        */
    }
}

spl_autoload_register('myAutoloader');

//  Create a Database connection.
class Database
{
    private static ?\PDO $connection = null;

    public static function getConnection(): \PDO
    {
        if (self::$connection === null) {
            $config = require __DIR__ . '/../../config/config.php'; // Adjust the path if needed
            $dsn = "mysql:host={$config['database']['host']};dbname={$config['database']['dbname']}";
            $user = $config['database']['user'];
            $password = $config['database']['password'];

            try {
                self::$connection = new \PDO($dsn, $user, $password);
                self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                //  In a real application, you would log this error.
                echo "Database connection error: " . $e->getMessage();
                die(); //  Important: Stop execution on connection failure.
            }
        }
        return self::$connection;
    }
}