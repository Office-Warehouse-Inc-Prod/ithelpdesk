<?php
// Database configuration
$dbHost     = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName     = "helpdesk1";

// Create database connection
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}






if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tktnum = $_POST['ticket_no'];

    $files = $_FILES['files'];

    foreach ($files['name'] as $index => $name) {
        $fileTmp = $files['tmp_name'][$index];
        $filename = $files['name'][$index];
        $filePath = '../users/image/'. $name;

        $sql = "INSERT INTO images(files_tmp, files_name, uploaded_on, ticket_no) VALUE ('$fileTmp',' $filename', NOW(), '$tktnum') ";

        mysqli_query($db, $sql);


       if(move_uploaded_file($fileTmp, $filePath));
    }

   

}

$db->close();

