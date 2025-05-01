<?php
// This file should be placed in your web root to help diagnose issues

// Display PHP info
echo "<h1>PHP Configuration</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Check loaded extensions
echo "<h2>Loaded Extensions</h2>";
echo "<ul>";
$extensions = get_loaded_extensions();
sort($extensions);
foreach ($extensions as $extension) {
    echo "<li>" . $extension . "</li>";
}
echo "</ul>";

// Check Apache modules if available
echo "<h2>Server Information</h2>";
echo "<p>Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";

if (function_exists('apache_get_modules')) {
    echo "<h2>Apache Modules</h2>";
    echo "<ul>";
    $modules = apache_get_modules();
    sort($modules);
    foreach ($modules as $module) {
        echo "<li>" . $module . "</li>";
    }
    echo "</ul>";
}

// Check .htaccess functionality
echo "<h2>.htaccess Test</h2>";
echo "<p>REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p>SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'] . "</p>";

// Check database connection
echo "<h2>Database Connection Test</h2>";
try {
    require_once __DIR__ . '/vendor/autoload.php';
    
    // Try to load .env file
    if (file_exists(__DIR__ . '/.env')) {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->safeLoad();
        echo "<p>.env file found and loaded</p>";
    } else {
        echo "<p>.env file not found. Using environment variables</p>";
    }
    
    // Get DB connection parameters
    $dbHost = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'db';
    $dbName = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'replyvp';
    $dbUser = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'root';
    $dbPassword = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?? 'root';
    
    echo "<p>Using connection parameters:</p>";
    echo "<ul>";
    echo "<li>Host: " . $dbHost . "</li>";
    echo "<li>Database: " . $dbName . "</li>";
    echo "<li>User: " . $dbUser . "</li>";
    echo "<li>Password: " . (empty($dbPassword) ? "Empty" : "Set") . "</li>";
    echo "</ul>";
    
    // Try connection
    $mysqli = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
    
    if ($mysqli->connect_error) {
        throw new Exception("Database connection failed: " . $mysqli->connect_error);
    }
    
    echo "<p style='color: green'>Database connection successful!</p>";
    $mysqli->close();
    
} catch (Exception $e) {
    echo "<p style='color: red'>Error: " . $e->getMessage() . "</p>";
}

// Routing test
echo "<h2>Routing Test</h2>";
echo "<p>Testing how the application would route the current request:</p>";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

// Handle potential subfolder installation
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$scriptPath = trim($scriptName, '/');

if (!empty($scriptPath) && strpos($uri, $scriptPath) === 0) {
    $uri = substr($uri, strlen($scriptPath));
}

// Split into segments
$uriParts = explode('/', trim($uri, '/'));

echo "<p>Parsed URI: " . print_r($uriParts, true) . "</p>";
echo "<p>Method: " . $_SERVER['REQUEST_METHOD'] . "</p>";