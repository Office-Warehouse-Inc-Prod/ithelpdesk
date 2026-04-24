<?php
$conn = new mysqli("localhost", "root", "", "helpdesk1");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) as total
        FROM reports
        WHERE status = 'ASSIGNED'
        AND deptsel = '11'
        AND store IS NOT NULL";

$result = $conn->query($sql);

$row = $result->fetch_assoc();

echo $row['total'];

$conn->close();
?>