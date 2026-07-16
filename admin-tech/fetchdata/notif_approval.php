<?php
$conn = new mysqli("localhost", "root", "", "helpdesk1");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) as total
        FROM asset_requests
        WHERE status = 'SUBMITTED'
        AND item_received_by = '47'
        ";

$result = $conn->query($sql);

$row = $result->fetch_assoc();

echo $row['total'];

$conn->close();
?>