<?php
include 'dbConfig.php';


if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

$tickt = $_POST['tickt'];
$alu = $_POST['alu'];
$desc = $_POST['desc'];
$serialNo = $_POST['serialNo'];
$defect = $_POST['Defect'];
$supplier = $_POST['supplier'];


$sql = "INSERT INTO tbl_pditems (ticket_no, alu_no, description, serial_no, defect, supplier) VALUES ('$tickt', '$alu', '$desc', '$serialNo', '$defect', '$supplier')";

if (mysqli_query($db, $sql)) {
    echo "Data inserted successfully!";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($db);
}

mysqli_close($db);
?>