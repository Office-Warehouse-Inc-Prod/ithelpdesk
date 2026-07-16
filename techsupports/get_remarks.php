<?php
session_start();
include('../db.php');

if (isset($_POST['ticket_no'])) {
    $ticket_no = $_POST['ticket_no'];
    
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM fixed_asset_remarks WHERE ticket_no = ? ORDER BY date_remarks ASC");
    $stmt->bind_param("s", $ticket_no);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $remarks = array();
    
    while ($row = $result->fetch_assoc()) {
        $remarks[] = $row;
    }
    
    // Return the data as a JSON object
    header('Content-Type: application/json');
    echo json_encode($remarks);
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode([]);
}
?>