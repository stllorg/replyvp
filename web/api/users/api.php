<?php
function getConnection() {
    $host = getenv('MYSQL_HOST') ?: 'db';
    $dbname = getenv('MYSQL_DATABASE') ?: 'api';
    $user = getenv('MYSQL_USER') ?: 'root';
    $password = getenv('MYSQL_PASSWORD') ?: 'root';

    $connection = new mysqli($host, $user, $password, $dbname);

    if ($connection->connect_error) {
        throw new Exception("Connection failed: " . $connection->connect_error);
    }

    if (!$connection->set_charset("utf8mb4")) {
        $connection->close();
        throw new Exception("An error occurred while trying to set the charset: " . $connection->error);
    }

    return $connection;
}
?>