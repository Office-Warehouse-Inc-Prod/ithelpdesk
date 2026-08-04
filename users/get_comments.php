<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json');

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "helpdesk1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

$ticket_no = $_POST['ticket_no'] ?? '';

if (empty($ticket_no)) { 
    echo json_encode(["error" => "No ticket number provided."]);
    exit; 
}

$query = "SELECT CONCAT(u.fname, ' ', u.lstname) AS userId, rc.comment_details, rc.comment_date
          FROM reports_comments rc
          LEFT JOIN users u ON rc.userId = u.id
          WHERE rc.ticket_no = ? 
          ORDER BY rc.comment_date ASC";

$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["error" => "Query Prepare Failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("s", $ticket_no);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}

echo json_encode($comments);

$stmt->close();
$conn->close();
?>