<?php
include '../condb.php';

if (isset($_POST['ticket_no'])) {
    // Instantiate the connection exactly as done in your main file
    $conn = new dbconfig(); 

    $ticket_no = $_POST['ticket_no'];

    // SQL statement executing the LEFT JOIN
    $sql = "
        SELECT 
            rc.comment_details, 
            DATE_FORMAT(rc.comment_date, '%m/%d/%Y %h:%i %p') as comment_date, 
            rc.userId, 
            COALESCE(u.fname, 'Unknown') as fname, 
            COALESCE(u.lstname, 'User') as lstname 
        FROM reports_comments rc 
        LEFT JOIN users u ON rc.userId = u.id 
        WHERE rc.ticket_no = ? 
        ORDER BY rc.comment_date ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ticket_no);
    $stmt->execute();
    $result = $stmt->get_result();

    $comments = array();

    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }

    // Return the JSON encoded array
    echo json_encode($comments);
    
    $stmt->close();
    $conn->close();
}
?>