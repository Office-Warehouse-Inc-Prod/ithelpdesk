<?php
session_start();
include '../condb.php';

$con1 = new dbconfig();
$conn = $con1->dbcon(); // Ensure this method correctly returns your PDO connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_no'])) {
    try {
        $ticket_no = $_POST['ticket_no'];

        // Based on the schema in Screenshot 2026-07-14 091802.png
        // We join fixed_asset_remarks (r) with your tech table (t) to get the name (it_desc)
        $sql = "SELECT 
                    r.remarks_note, 
                    r.date_remarks, 
                    t.it_desc 
                FROM fixed_asset_remarks r
                LEFT JOIN it_tech t ON r.remarks_by = t.tech_id 
                WHERE r.ticket_no = :ticket_no
                ORDER BY r.date_remarks ASC";

        $stmt = $conn->prepare($sql);
        $stmt->execute(['ticket_no' => $ticket_no]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the results as a JSON array for your JavaScript to map
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