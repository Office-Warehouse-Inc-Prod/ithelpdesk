 <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "helpdesk1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT
reports.ticket_no,
reports.`status`,
reports_msgcnt.msg_cnt,
reports_msgcnt.itsup
FROM
reports
INNER JOIN reports_msgcnt ON reports_msgcnt.ticket_no = reports.ticket_no

WHERE status = 'NEW REPORT' and msg_cnt = '1' and deptsel = '4' AND reports.service_desc = 'REPAIR IMPORT'";
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