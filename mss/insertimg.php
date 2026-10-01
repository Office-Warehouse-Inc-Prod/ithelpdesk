<?php
require_once __DIR__ . '/../includes/upload_validation.php';

if (!owi_upload_session_ok()) {
    owi_upload_fail(401);
}
if (!isset($_SERVER['REQUEST_METHOD']) || strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
    owi_upload_fail(405);
}

$ticketNo = isset($_POST['ticket_no']) && is_scalar($_POST['ticket_no']) ? (string) $_POST['ticket_no'] : null;
if ($ticketNo === null) {
    owi_upload_fail(400);
}
$validatedFiles = array();
if (isset($_FILES['files']) && owi_upload_field_has_file($_FILES['files'])) {
    $validatedFiles = owi_upload_validate_collection($_FILES['files']);
    if ($validatedFiles === false) {
        owi_upload_fail(400);
    }
}
$uploadDirectory = dirname(__DIR__) . '/users/image/';
if (!empty($validatedFiles) && (!is_dir($uploadDirectory) || !is_writable($uploadDirectory))) {
    owi_upload_fail(500);
}

mysqli_report(MYSQLI_REPORT_OFF);
try {
    $db = new mysqli('localhost', 'root', '', 'helpdesk1');
    if ($db->connect_errno) {
        owi_upload_fail(500);
    }
    $statement = $db->prepare('INSERT INTO images (files_tmp, files_name, uploaded_on, ticket_no) VALUES (?, ?, NOW(), ?)');
    if (!$statement) {
        owi_upload_fail(500);
    }

    foreach ($validatedFiles as $file) {
        $serverName = $file['name'];
        $targetPath = $uploadDirectory . $serverName;
        if (file_exists($targetPath) || !move_uploaded_file($file['tmp_name'], $targetPath)) {
            owi_upload_fail(500);
        }

        $storedPath = 'image/' . $serverName;
        $displayName = ' ' . $file['name'];
        $statement->bind_param('sss', $storedPath, $displayName, $ticketNo);
        if (!$statement->execute()) {
            @unlink($targetPath);
            owi_upload_fail(500);
        }
    }

    $statement->close();
    $db->close();
} catch (Throwable $e) {
    if (isset($targetPath) && is_file($targetPath)) {
        @unlink($targetPath);
    }
    owi_upload_fail(500);
}