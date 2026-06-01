<?php
// Database configuration
$dbHost     = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName     = "helpdesk1";

// Create database connection
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}






if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tktnum = isset($_POST['ticket_no']) ? $_POST['ticket_no'] : '';

    if (!isset($_FILES['files'])) {
        // no files uploaded
        exit;
    }

    $files = $_FILES['files'];
    $uploadDir = __DIR__ . '/image/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // prepare statement
    $stmt = $db->prepare("INSERT INTO images (files_tmp, files_name, uploaded_on, ticket_no) VALUES (?, ?, NOW(), ?)");

    foreach ($files['name'] as $index => $name) {
        $fileTmp = $files['tmp_name'][$index];
        $originalName = basename($files['name'][$index]);
        $uniqueName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
        $filePath = $uploadDir . $uniqueName;

        if (is_uploaded_file($fileTmp) && move_uploaded_file($fileTmp, $filePath)) {
            $storedPath = 'image/' . $uniqueName; // relative path to save in DB
            $stmt->bind_param('sss', $storedPath, $originalName, $tktnum);
            $stmt->execute();
        }
    }

    $stmt->close();

}

$db->close();

