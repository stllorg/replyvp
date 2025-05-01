<?php

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function getConnection() {
    $dbHost = $_ENV['DB_HOST'];
    $dbName = $_ENV['DB_NAME'];
    $dbUser = $_ENV['DB_USER'];
    $dbPassword = $_ENV['DB_PASS'];


    $mysqli = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

    if ($mysqli->connect_error) {
        die("Error while trying to connect." . $mysqli->connect_error);
    }

    if (!$mysqli->set_charset("utf8mb4")) {
        die("An error ocurred whille trying to set the charset: " . $mysqli->error);
    }

    return $mysqli;
}