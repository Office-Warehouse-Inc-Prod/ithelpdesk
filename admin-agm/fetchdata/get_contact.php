<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'helpdesk1';

try {
    $conn = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("Connection failed: " . $e->getMessage());
}

if (isset($_POST['dept_id'])) {

    $dept_id = $_POST['dept_id'];

    $stmt = $conn->prepare("
        SELECT contactNumber 
        FROM tbl_dept 
        WHERE dept_id = :dept_id
        LIMIT 1
    ");
    $stmt->bindParam(':dept_id', $dept_id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo $row ? $row['contactNumber'] : '';
}
