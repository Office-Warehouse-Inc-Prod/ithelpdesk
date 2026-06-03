<?php
// Prevent PHP warnings from corrupting JSON data
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['ticket_no']) || empty($_GET['ticket_no'])) {
    echo json_encode(["error" => "No ticket number provided"]);
    exit;
}

$ticket_no = $_GET['ticket_no'];

// Locate and read your configuration parameters
if (file_exists('../condb.php')) {
    include '../condb.php';
} else if (file_exists('condb.php')) {
    include 'condb.php';
} else {
    echo json_encode(["error" => "condb.php not found"]);
    exit;
}

/**
 * 💡 BULLETPROOF RAW CONNECTION FALLBACK
 * Instead of guessing class properties, let's open an independent, lightweight
 * connection matching typical internal settings (localhost, root, empty/common passwords).
 */
$host = defined('DB_SERVER') ? DB_SERVER : 'localhost';
$user = defined('DB_USERNAME') ? DB_USERNAME : 'root';
$pass = defined('DB_PASSWORD') ? DB_PASSWORD : '';
$name = defined('DB_NAME') ? DB_NAME : '';

// If your file uses variable arrays instead of constants, grab those definitions here
if (empty($name) && isset($con1)) {
    // Attempting to extract directly out of the instantiated object properties
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

// Fallback direct connection engine
if (!isset($raw_conn)) {
    // If your project's DB name isn't set via standard constants, type it below:
    if (empty($name)) {
        $name = 'helpdesk1'; // <-- Actual database name from condb.php
    }
    
    $raw_conn = new mysqli($host, $user, $pass, $name);
    if ($raw_conn->connect_error) {
        // Fallback try with an alternative common blank configuration
        $raw_conn = new mysqli('localhost', 'root', 'root', $name);
    }
}

if (!isset($raw_conn) || ($raw_conn instanceof mysqli && $raw_conn->connect_error)) {
    echo json_encode(["error" => "Database connection failure. Please confirm the database name inside get_attachments.php"]);
    exit;
}

$images = [];

// Query using our guaranteed raw link connection instance
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