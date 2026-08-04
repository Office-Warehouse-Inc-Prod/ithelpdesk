<?php
session_start();
include '../condb.php';

$con1 = new dbconfig();
$conn = $con1->dbcon(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_no'])) {
    try {
        $ticket_no = $_POST['ticket_no'];

        $sql = "SELECT far.remarks_note, 
                                                 CONCAT(u.fname, ' ', u.lstname) AS user_fullname, 
                                                 far.date_remarks 
                                          FROM fixed_asset_remarks far 
                                          LEFT JOIN users u ON far.remarks_by = u.id 
                                          WHERE far.ticket_no = ? 
                                          ORDER BY far.date_remarks ASC";

        $stmt = $conn->prepare($sql);
        $stmt->execute(['ticket_no' => $ticket_no]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($results);
        
    } catch (Exception $e) {
        // Return error as JSON if query fails
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
?>