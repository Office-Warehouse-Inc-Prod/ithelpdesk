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

include('../fpdf/fpdf.php');

$ticket_no   = $_POST['ticket_no'] ?? '';

if (empty($ticket_no)) {
    die("Error: No ticket number provided.");
}

$description = $_POST['desc'] ?? 'N/A';
$serial      = $_POST['serial'] ?? 'N/A';
$asset_tag   = $_POST['asset_tag'] ?? 'N/A';

$sql = "SELECT r.*, u.fname, u.lstname, c.cat_desc, s.sub_cat, b.str_name 
        FROM reports r
        LEFT JOIN users u ON r.userId = u.id
        LEFT JOIN categories c ON r.cat_id = c.cat_id
        LEFT JOIN subcat s ON r.sub_id = s.sub_id
        LEFT JOIN tbl_branch b ON r.store = b.str_num
        WHERE r.ticket_no = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) { die("Prepare Failed: " . $conn->error); }

$stmt->bind_param("s", $ticket_no);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) { die("Ticket not found."); }


if (ob_get_length()) ob_clean();

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();

$pdf->Image('../Fixed_Asset_Requisition_Transfer Form.jpg', 0, 0, 210, 297);

if (file_exists($bg_image)) {
    $pdf->Image($bg_image, 0, 0, 210, 297);
}

$pdf->SetFont('Arial', '', 8);


$pdf->SetXY(50, 25);
$pdf->Cell(105, 5, $row['str_name'] ?? 'N/A', 0, 0);


$pdf->SetXY(170, 25);
$pdf->Cell(30, 5, $row['date_created'] ?? 'N/A', 0, 0);


$pdf->SetXY(50, 29.5);
$pdf->Cell(80, 5, ($row['fname'] ?? '') . ' ' . ($row['lstname'] ?? ''), 0, 0);

$full_ticket =' TICKET NO:                    ' . ($row['ticket_no'] ?? 'N/A');
$pdf->SetXY(138, 30);
$pdf->Cell(120, 5, $full_ticket, 0, 0);

$full_category = ($row['cat_desc'] ?? 'N/A') . ' - ' . ($row['sub_cat'] ?? 'N/A');
$pdf->SetXY(50, 43);
$pdf->Cell(120, 5, $full_category, 0, 0);


$pdf->SetFont('Arial', '', 8);

$pdf->SetXY(50, 48.5);
$pdf->Cell(120, 5, $description, 0, 0);

$pdf->SetXY(50, 53);
$pdf->Cell(120, 5, $serial, 0, 0);

$pdf->SetXY(50, 57.5);
$pdf->Cell(120, 5, $asset_tag, 0, 0);

$pdf->Output('I', 'Fixed_Asset_Form_' . $ticket_no . '.pdf');
exit;
?>