<?php
session_start(); 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "helpdesk1";

if (!isset($_SESSION['tech_id'])) {
    die("Error: No technician is logged in.");
}
$itval = $_SESSION['tech_id'];


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT tbl_notif.* 
        FROM tbl_notif 
        LEFT JOIN reports ON tbl_notif.ticket_no = reports.ticket_no
        WHERE reports.itsup = ? 
          AND (
              (tbl_notif.notif_val IN ('1', '2') AND reports.f_deptsel = 1)
              OR 
              (tbl_notif.notif_val IN ('9','10') AND reports.f_deptsel IS NOT NULL)
          )
          AND tbl_notif.ticket_no NOT LIKE '%MKTG%'
          AND tbl_notif.ticket_no NOT LIKE '%ADMIN%'
          AND tbl_notif.ticket_no NOT LIKE '%PD%'
          AND tbl_notif.ticket_no NOT LIKE '%VISUAL%'
          AND tbl_notif.ticket_no NOT LIKE '%LD%'";


$stmt = $conn->prepare($sql);
if ($stmt) {
   
    $stmt->bind_param("s", $itval);
    $stmt->execute();

    $result = $stmt->get_result();
 
    echo $result->num_rows;

    /*
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Note: Make sure 'id' and 'description' actually exist in tbl_notif 
            // if you decide to uncomment this out later!
            echo "id: " . $row["ticket_no"]. " - Data: " . $row["notif_data"];
        }
    } else {
        echo "0 results";
    }
    */
    
    $stmt->close();
} else {
    echo "Query preparation failed: " . $conn->error;
}

$conn->close();
?>