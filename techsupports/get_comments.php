<?php

session_start();
error_reporting(E_ERROR | E_PARSE);

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "helpdesk1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$ticket_no = $_POST['ticket_no'] ?? '';
if (empty($ticket_no)) { 
    die("Error: No ticket number provided."); 
}

if (isset($_POST['ticket_no'])) {
    $ticket_no = mysqli_real_escape_string($conn, $_POST['ticket_no']);
    
    // Adjust column names to match your reports_comments table structure
    $query = "SELECT CONCAT(u.fname, ' ', u.lstname) AS userId, rc.comment_details, rc.comment_date
FROM reports_comments rc
LEFT JOIN users u ON rc.userId = u.id
WHERE rc.ticket_no = '$ticket_no' 
ORDER BY rc.comment_date ASC";
              
    $result = mysqli_query($conn, $query);
    $comments = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $comments[] = $row;
    }
    
    echo json_encode($comments);
}
?>