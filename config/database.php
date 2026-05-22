<?php
// config/database.php — PDO database connection
// Supports Railway environment variables

$host     = getenv('MYSQLHOST')     ?: getenv('DB_HOST')    ?: 'db';
$name     = getenv('MYSQLDATABASE') ?: getenv('DB_NAME')    ?: 'buildrent';
$user     = getenv('MYSQLUSER')     ?: getenv('DB_USER')    ?: 'buildrent_user';
$pass     = getenv('MYSQLPASSWORD') ?: getenv('DB_PASS')    ?: 'buildrent_pass';
$port     = getenv('MYSQLPORT')     ?: getenv('DB_PORT')    ?: '3306';

define('DB_HOST',    $host);
define('DB_NAME',    $name);
define('DB_USER',    $user);
define('DB_PASS',    $pass);
define('DB_PORT',    $port);
define('DB_CHARSET', 'utf8mb4');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST
             . ";port=" . DB_PORT
             . ";dbname=" . DB_NAME
             . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            die(json_encode(['error' => 'Database connection failed.']));
        }
    }
    return $pdo;
}
