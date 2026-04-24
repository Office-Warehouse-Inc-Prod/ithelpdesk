<?php
// Include the database configuration file
include_once 'dbConfig.php';

if (isset($_POST['fileName'])) {
    $fileName = $_POST['fileName'];
    $insert = $db->query("INSERT INTO images (file_name, uploaded_on) VALUES ('{$fileName}', NOW())");
    echo ($insert) ? "success" : "error";
}
?>