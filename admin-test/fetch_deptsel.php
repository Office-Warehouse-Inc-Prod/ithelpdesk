<?php
session_start();
include '../condb.php';

$con1 = new dbconfig(); 

if (isset($_POST['itsup']) && !empty($_POST['itsup'])) {
    $itsup = $_POST['itsup'];
    $query = "SELECT deptsel FROM it_tech WHERE itsup = ?"; 
    
    $stmt = $con1->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("i", $itsup);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            echo trim($row['deptsel']);
        } else {
            echo "Unknown Department"; 
        }
        $stmt->close();
    } else {
        echo "Query Failed: " . $con1->error;
    }
    exit;
}
?>