<?php
$servername = "localhost";
$username = 'root';
$password = '';
$db = "helpdesk1";

try {
    $connection = new PDO(
        "mysql:host=$servername;dbname=$db;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die("DB Connection failed.");
}
