 <?php
session_start(); 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "helpdesk1";
// $itval = $_SESSION['tech_id'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT
tbl_notif.ticket_no, 
tbl_notif.store, 
tbl_notif.itsup, 
tbl_notif.notif_data, 
tbl_notif.notif_date, 
tbl_notif.notif_val, 
tbl_notif.assigned_by
FROM
tbl_notif
LEFT JOIN
reports
ON 
    tbl_notif.ticket_no = reports.ticket_no
WHERE
notif_val IN ('2','3') AND
reports.deptsel = 4 AND reports.service_desc = 'REPAIR IMPORT'";
$result = $conn->query($sql);

echo $result->num_rows;
/*
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Notification: " . $row["description"];
    }
} else {
    echo "0 results";
}
*/
$conn->close();
?>