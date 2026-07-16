<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['ticket_no']) || empty($_GET['ticket_no'])) {
    echo json_encode(["error" => "No ticket number provided"]);
    exit;
}

$ticket_no = $_GET['ticket_no'];
if (file_exists('../condb.php')) {
    include '../condb.php';
} else if (file_exists('condb.php')) {
    include 'condb.php';
} else {
    echo json_encode(["error" => "condb.php not found"]);
    exit;
}

$host = defined('DB_SERVER') ? DB_SERVER : 'localhost';
$user = defined('DB_USERNAME') ? DB_USERNAME : 'root';
$pass = defined('DB_PASSWORD') ? DB_PASSWORD : '';
$name = defined('DB_NAME') ? DB_NAME : '';

if (empty($name) && isset($con1)) {
    $reflect = new ReflectionClass($con1);
    $props   = $reflect->getProperties();
    foreach ($props as $prop) {
        $prop->setAccessible(true);
        $val = $prop->getValue($con1);
        if (is_object($val) && ($val instanceof mysqli || $val instanceof PDO)) {
            $raw_conn = $val;
            break;
        }
    }
}
if (!isset($raw_conn)) {
    if (empty($name)) {
        $name = 'helpdesk1'; 
    }
    
    $raw_conn = new mysqli($host, $user, $pass, $name);
    if ($raw_conn->connect_error) {
        $raw_conn = new mysqli('localhost', 'root', 'root', $name);
    }
}

if (!isset($raw_conn) || ($raw_conn instanceof mysqli && $raw_conn->connect_error)) {
    echo json_encode(["error" => "Database connection failure. Please confirm the database name inside get_attachments.php"]);
    exit;
}

$images = [];
if ($raw_conn instanceof PDO) {
    $stmt = $raw_conn->prepare("SELECT files_name FROM images WHERE ticket_no = ?");
    $stmt->execute([$ticket_no]);
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $raw_conn->prepare("SELECT files_name FROM images WHERE ticket_no = ?");
    if ($stmt) {
        $stmt->bind_param("s", $ticket_no);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $images[] = $row;
        }
        $stmt->close();
    }
}

echo json_encode($images);
exit;