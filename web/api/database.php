<?php
function getConnection() {
    $host = 'db';
    $dbname = 'api';
    $username = 'root';
    $password = 'root';


    $mysqli = new mysqli($host, $username, $password, $dbname);

    if ($mysqli->connect_error) {
        die("Error while trying to connect." . $mysqli->connect_error);
    }

    if (!$mysqli->set_charset("utf8mb4")) {
        die("An error ocurred whille trying to set the charset: " . $mysqli->error);
    }

    return $mysqli;
}