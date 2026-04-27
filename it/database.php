<?php
$servername = "localhost";
$username = 'root';
$password = '';
$db = "helpdesk1";

try {
    // Create a new PDO instance
    $connection = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
    
    // Set the PDO error mode to exception
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optional: Set the character set to UTF-8
    $connection->exec("set names utf8");
    
} catch (PDOException $e) {
    // Handle connection error
    echo "Connection failed: " . $e->getMessage();
}
?>