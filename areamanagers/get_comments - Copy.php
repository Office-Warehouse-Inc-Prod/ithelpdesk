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
if(isset($_POST['action']) && $_POST['action'] == 'post_comment') {
    $ticket_no = $_POST['ticket_no'];
    $message = $_POST['admsg'];
    $user_id = $_POST['u_id'];
    
    // Set your timezone
    $datetime = new DateTime();
    $timezone = new DateTimeZone('Asia/Manila');
    $datetime->setTimezone($timezone);
    $current_time = $datetime->format('Y-m-d H:i:s');

    // Example Insert Query - Adjust table name and columns to match your database schema
    $query = "INSERT INTO tbl_comments (ticket_no, user_id, comment_details, comment_date) 
              VALUES (?, ?, ?, ?)";
              
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssss", $ticket_no, $user_id, $message, $current_time);
    
    if($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
}
?>