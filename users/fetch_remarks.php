<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mode'])) {
    
    header('Content-Type: application/json');
    
  if ($_POST['mode'] === 'fetch_remarks') {
        try {
            $ticket_no = $_POST['ticket_no'] ?? '';
            $stmt = $connection->prepare("SELECT far.remarks_note, 
                                                 CONCAT(u.fname, ' ', u.lstname) AS user_fullname, 
                                                 far.date_remarks 
                                          FROM fixed_asset_remarks far 
                                          LEFT JOIN users u ON far.remarks_by = u.id 
                                          WHERE far.ticket_no = ? 
                                          ORDER BY far.date_remarks ASC");
                                          
            $stmt->execute([$ticket_no]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            
        } catch (Exception $e) {
         
            echo json_encode([["remarks_note" => "Error loading remarks.", "user_fullname" => "System", "date_remarks" => ""]]);
        }
        exit(); 
    }
}
?>