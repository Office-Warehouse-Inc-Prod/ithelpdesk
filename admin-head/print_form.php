<?php
session_start();
error_reporting(E_ERROR | E_PARSE);

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "helpdesk1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure ticket number is passed
$ticket_no = $_POST['ticket_no'] ?? '';
if (empty($ticket_no)) { 
    die("Error: No ticket number provided."); 
}

$sql = "SELECT 
    b.str_name, 
    CONCAT(u.fname, ' ', u.lstname) AS full_name, 
    ar.ticket_created, 
    ar.ticket_no,
    ar.item_code, 
    ar.description, 
    ar.serial_number, 
    ar.asset_tag_number, 
    ar.purpose_of_request,
    received_by.it_desc AS received_by_name, 
    ar.date_received, 
    noted_by.it_desc AS noted_by_name
FROM asset_requests ar
LEFT JOIN tbl_branch b 
    ON ar.requested_db = b.str_num
LEFT JOIN users u 
    ON ar.requested_by = u.id
LEFT JOIN it_tech received_by 
    ON ar.item_received_by = received_by.itsup
LEFT JOIN it_tech noted_by 
    ON ar.noted_by = noted_by.itsup
WHERE ar.ticket_no = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ticket_no);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();

if (!$ticket) { 
    die("Ticket not found."); 
}

include('../fpdf/fpdf.php');
if (ob_get_length()) ob_clean();

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->SetAutoPageBreak(false);

$pdf->AddPage();
$pdf->Image('../Fixed_Asset_Requisition_Transfer Form.jpg', 0, 0, 210, 297);
$pdf->SetFont('Arial', '', 8);

// Populate Template Form Fields
$pdf->SetXY(53, 17.2); 
$pdf->Cell(105, 5, $ticket['str_name'] ?? 'N/A', 0, 0);

$pdf->SetXY(155, 17.2); 
$pdf->Cell(30, 5, $ticket['ticket_created'] ?? 'N/A', 0, 0);

$pdf->SetXY(53, 21);   
$pdf->Cell(80, 5, $ticket['full_name'] ?? 'N/A', 0, 0);

$pdf->SetXY(128.5, 21);   
$pdf->Cell(120, 5, 'TICKET NO:               ' . $ticket['ticket_no'], 0, 0);

$pdf->SetXY(53, 30);    
$pdf->Cell(120, 5, $ticket['item_code'] ?? 'N/A', 0, 0);

$pdf->SetXY(53, 34); 
$pdf->Cell(120, 5, $ticket['description'] ?? 'N/A', 0, 0);

$pdf->SetXY(53, 38); 
$pdf->Cell(120, 5, $ticket['serial_number'] ?? 'N/A', 0, 0);

$pdf->SetXY(53, 42); 
$pdf->Cell(120, 5, $ticket['asset_tag_number'] ?? 'N/A', 0, 0);

$pdf->SetXY(53, 46.5); 
$pdf->Cell(120, 5, $ticket['purpose_of_request'] ?? 'N/A', 0, 0);

$pdf->SetXY(53, 55); 
$pdf->Cell(120, 5, $ticket['received_by_name'] ?? 'N/A', 0, 0);

$pdf->SetXY(53, 59.5); 
$pdf->Cell(120, 5, $ticket['date_received'] ?? 'N/A', 0, 0);

$pdf->SetXY(144, 29.5); 
$pdf->Cell(120, 5, $ticket['noted_by_name'] ?? $_POST['noted_by'] ?? 'N/A', 0, 0);


$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(33, 52, 86);
$pdf->Cell(0, 10, 'COMMENT THREAD', 0, 1, 'L');

$pdf->SetDrawColor(255, 255, 0); 
$pdf->SetFont('Arial', 'B', 9);

$pdf->SetXY(170.5, 19);   
$pdf->Cell(120, 5, 'TICKET NO:  ' . $ticket['ticket_no'], 0, 0);
$pdf->SetXY(10, 19); 
$pdf->Cell(105, 5, 'DEPT/BRANCH:  ' . $ticket['str_name'], 0, 0);
$pdf->Ln(5);

$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

$sql2 = "SELECT rc.comment_details, rc.comment_date, u.fname, u.lstname 
         FROM reports_comments rc
         LEFT JOIN users u ON rc.userId = u.id
         WHERE rc.ticket_no = ?
         ORDER BY rc.comment_date ASC";

$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("s", $ticket_no);
$stmt2->execute();
$comments_result = $stmt2->get_result();

while ($comment = $comments_result->fetch_assoc()) {
   
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(33, 52, 86);
    $pdf->Cell(100, 6, $comment['fname'] . ' ' . $comment['lstname'], 0, 0);

    $pdf->SetFont('Arial', '', 8);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0, 6, $comment['comment_date'], 0, 1, 'R');

    $pdf->SetDrawColor(128); 
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 10);
    
    $pdf->MultiCell(0, 8, $comment['comment_details'], 1, 'L', true);
    $pdf->Ln(5); 
    
    if ($pdf->GetY() > 250) {
        $pdf->AddPage();
    }
}

$pdf->Output('I', 'Fixed_Asset_Form_' . $ticket_no . '.pdf');
exit;
?>