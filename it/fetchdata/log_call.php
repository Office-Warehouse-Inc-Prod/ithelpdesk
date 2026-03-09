<?php
session_start();
require_once '../../database.php';

if(!isset($_SESSION['user_id'])){
    http_response_code(401);
    exit('Unauthorized');
}

$ticket_no = $_POST['ticket_no'] ?? '';
$dept_id   = $_POST['dept_id'] ?? 0;
$user_id   = $_SESSION['user_id'];

if($ticket_no === '' || !$dept_id){
    http_response_code(400);
    exit('Invalid parameters');
}

$stmt = $conn->prepare("
    INSERT INTO ticket_call_logs (ticket_no, call_startdate, call_status, user_id, dept_id)
    VALUES (:ticket_no, NOW(), 'INITIATED', :user_id, :dept_id)
");
$stmt->execute([
    ':ticket_no' => $ticket_no,
    ':user_id'   => $user_id,
    ':dept_id'   => $dept_id
]);

$call_id = $conn->lastInsertId();

// also return call_startdate for accurate timer baseline
$start = $conn->query("SELECT NOW() AS call_startdate")->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode([
    'call_id' => $call_id,
    'call_startdate' => $start['call_startdate']
]);
