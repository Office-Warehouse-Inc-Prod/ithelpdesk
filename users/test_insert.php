<?php
require 'database.php';

$sql = "INSERT INTO tbl_ticket_items 
(ticket_no, alu, description, serial_no, defect, vendor, qty, classification)
VALUES
('TEST-001','12345','Test Item','SN123','Defective','Vendor A',1,'STORE_UNIT')";

$stmt = $connection->prepare($sql);
$stmt->execute();

echo "Inserted!";
?>