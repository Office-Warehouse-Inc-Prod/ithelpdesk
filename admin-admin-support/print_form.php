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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ticket_no'])) {
    $ticket_no = $_POST['ticket_no'];
    $desc = $_POST['desc'];
    $serial = $_POST['serial'];
    $asset_tag = $_POST['asset_tag'];
    $purpose = $_POST['purpose'];
    $received_by = $_POST['received_by'];
    $date_received = $_POST['date_received'];
    $noted_by = $_POST['noted_by'];

    $stmt = $conn->prepare("INSERT INTO asset_requests 
        (ticket_no, description, serial_number, asset_tag_number, purpose_of_request, item_received_by, date_received, noted_by) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        description=?, serial_number=?, asset_tag_number=?, purpose_of_request=?, item_received_by=?, date_received=?, noted_by=?");
    
    $stmt->bind_param("sssssssssssssss", 
        $ticket_no, $desc, $serial, $asset_tag, $purpose, $received_by, $date_received, $noted_by,
        $desc, $serial, $asset_tag, $purpose, $received_by, $date_received, $noted_by);
    
    $stmt->execute();
}

$ticket_no = $_POST['ticket_no'] ?? '';
if (empty($ticket_no)) { die("Error: No ticket number provided."); }

$sql = "SELECT r.*, u.fname, u.lstname, c.cat_desc, s.sub_cat, b.str_name 
        FROM reports r
        LEFT JOIN users u ON r.userId = u.id
        LEFT JOIN categories c ON r.cat_id = c.cat_id
        LEFT JOIN subcat s ON r.sub_id = s.sub_id
        LEFT JOIN tbl_branch b ON r.store = b.str_num
        WHERE r.ticket_no = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ticket_no);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) { die("Ticket not found."); }
include('../fpdf/fpdf.php');
if (ob_get_length()) ob_clean();

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();
$pdf->Image('../Fixed_Asset_Requisition_Transfer Form.jpg', 0, 0, 210, 297);

$pdf->SetFont('Arial', '', 8);


$pdf->SetXY(53, 17.2); 
$pdf->Cell(105, 5, $row['str_name'] ?? 'N/A', 0, 0);


$pdf->SetXY(155, 17.2); 
$pdf->Cell(30, 5, $row['date_created'] ?? 'N/A', 0, 0);

$pdf->SetXY(53, 21);   
 $pdf->Cell(80, 5, ($row['fname'] ?? '') . ' ' . ($row['lstname'] ?? ''), 0, 0);

$pdf->SetXY(128.5, 21);   
$pdf->Cell(120, 5, 'TICKET NO:              ' . $row['ticket_no'], 0, 0);

$pdf->SetXY(53, 30);    
$pdf->Cell(120, 5, ($row['cat_desc'] ?? 'N/A') . ' - ' . ($row['sub_cat'] ?? 'N/A'), 0, 0);

$pdf->SetXY(53, 34); 
$pdf->Cell(120, 5, $_POST['desc'] ?? 'N/A', 0, 0);

$pdf->SetXY(53, 38); 
$pdf->Cell(120, 5, $_POST['serial'] ?? 'N/A', 0, 0);

$pdf->SetXY(53, 42); 
$pdf->Cell(120, 5, $_POST['asset_tag'] ?? 'N/A', 0, 0);

$pdf->SetXY(53, 46.5); 
$pdf->Cell(120, 5, $_POST['purpose'] ?? 'N/A', 0, 0);

$pdf->SetXY(53, 55); 
$pdf->Cell(120, 5, $_POST['received_by'] ?? 'N/A', 0, 0);

$pdf->SetXY(53, 59.5); 
$pdf->Cell(120, 5, $_POST['date_received'] ?? 'N/A', 0, 0);

$pdf->SetXY(144, 29.5); 
$pdf->Cell(120, 5, $_POST['noted_by'] ?? 'N/A', 0, 0);



$sql2 = "SELECT rc.comment_details, rc.comment_date, u.fname, u.lstname 
         FROM reports_comments rc
         LEFT JOIN users u ON rc.userId = u.id
         WHERE rc.ticket_no = ?
         ORDER BY rc.comment_date ASC";

$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("s", $ticket_no);
$stmt2->execute();
$comments_result = $stmt2->get_result();

$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(33, 52, 86);
$pdf->Cell(0, 10, 'Comment Thread', 0, 1, 'L');
$pdf->Ln(5);


$sql2 = "SELECT rc.comment_details, rc.comment_date, u.fname, u.lstname 
         FROM reports_comments rc
         LEFT JOIN users u ON rc.userId = u.id
         WHERE rc.ticket_no = ?
         ORDER BY rc.comment_date ASC";

$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("s", $ticket_no);
$stmt2->execute();
$result2 = $stmt2->get_result();

while ($row = $result2->fetch_assoc()) {
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(33, 52, 86);
    $pdf->Cell(100, 6, $row['fname'] . ' ' . $row['lstname'], 0, 0);

    
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0, 6, $row['comment_date'], 0, 1, 'R');

    $pdf->SetDrawColor(128); 
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 10);
    
    $pdf->MultiCell(0, 8, $row['comment_details'], 1, 'L', true);
    
    $pdf->Ln(5); 
    
    if ($pdf->GetY() > 250) {
        $pdf->AddPage();
    }
}
$pdf->Output('I', 'Fixed_Asset_Form_' . $ticket_no . '.pdf');
exit;
?>