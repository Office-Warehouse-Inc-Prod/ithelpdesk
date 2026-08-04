<?php
session_start();
include('../db.php');

if(isset($_POST['ticket_no'])) {
    $ticket_no = mysqli_real_escape_string($connect, $_POST['ticket_no']);
    
    // Check if record exists in fixed_asset_remarks
    $query = "SELECT count(*) as total FROM fixed_asset_remarks WHERE ticket_no = '$ticket_no'";
    $result = mysqli_query($connect, $query);
    $data = mysqli_fetch_assoc($result);
    
    echo json_encode(['has_remarks' => ($data['total'] > 0)]);
}
?>