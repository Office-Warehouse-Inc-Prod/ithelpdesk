    <?php
$conn = new mysqli("localhost", "root", "", "helpdesk1");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) as total
        FROM asset_requests ar
          LEFT JOIN reports r ON ar.ticket_no = r.ticket_no
        WHERE ar.status = 'NOTED' AND (
                      (ar.is_technical = 0 AND r.status = 'ON PROCESS') 
                      OR 
                      (ar.is_technical = 1)
                  )";

$result = $conn->query($sql);

$row = $result->fetch_assoc();

echo $row['total'];

$conn->close();
?>