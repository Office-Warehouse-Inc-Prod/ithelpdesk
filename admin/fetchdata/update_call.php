<?php
session_start();
require_once '../../database.php';

if(!isset($_SESSION['user_id'])){
    http_response_code(401);
    exit('Unauthorized');
}

$call_id     = $_POST['call_id'] ?? 0;
$call_status = $_POST['call_status'] ?? '';

$allowed = ['ANSWERED','NO_ANSWER','BUSY','FAILED','VOICEMAIL'];

if(!$call_id || !in_array($call_status, $allowed, true)){
    http_response_code(400);
    exit('Invalid data');
}

$stmt = $conn->prepare("
    UPDATE ticket_call_logs
    SET call_endDate = NOW(),
        call_status = :call_status
    WHERE call_id = :call_id
");
$stmt->execute([
    ':call_status' => $call_status,
    ':call_id'     => $call_id
]);

echo "OK";
