<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); 

date_default_timezone_set('Asia/Manila');

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "helpdesk1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
            ar.date_submitted,
            ar.approve_method_tech,
            ar.approve_method_adminsup,
            ar.approve_method_head,
            ar.approve_method_agm,
            ar.revised_request,
            ar.date_noted,
            ar.date_validated, 
            ar.date_verified,
            ar.is_technical,
            ar.technical_workoutput, 
            received_by.it_desc AS received_by_name, 
            ar.date_received, 
            noted_by.it_desc AS noted_by_name,
            fat.problem_reported,
            fat.verification_findings,
            fat.work_done,
            fat.status_workoutput,
            fat.recommendation
        FROM asset_requests ar
        LEFT JOIN tbl_branch b ON ar.requested_db = b.str_num
        LEFT JOIN users u ON ar.requested_by = u.id
        LEFT JOIN it_tech received_by ON ar.item_received_by = received_by.itsup
        LEFT JOIN it_tech noted_by ON ar.noted_by = noted_by.itsup
        LEFT JOIN fixed_asset_techoutput fat ON ar.ticket_no = fat.ticket_no
        WHERE ar.ticket_no = ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("MySQL Query Error: " . $conn->error);
}

$stmt->bind_param("s", $ticket_no);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();

if (!$ticket) { 
    die("Ticket not found."); 
}

include('../fpdf/fpdf.php');
if (ob_get_length()) ob_clean(); 

class CustomPDF extends FPDF {
    function Footer() {
        $this->SetY(-25); 
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(150, 150, 150);
        $date_printed = date('M d, Y h:i A');
        $this->Cell(0, 10, 'Date Printed: ' . $date_printed, 0, 0, 'R');
    }
}

$pdf = new CustomPDF('P', 'mm', 'A4');
$pdf->SetAutoPageBreak(false);

$pdf->AddPage();
$pdf->Image('../Fixed_Asset_Requisition_Transfer Form.png', 0, 0, 210, 297);
$pdf->SetFont('Arial', '', 8);

$is_technical = isset($ticket['is_technical']) ? (int)$ticket['is_technical'] : 0;

$pdf->SetXY(53, 17.2); 
$pdf->Cell(105, 5, $ticket['str_name'] ?? 'N/A', 0, 0);

$pdf->SetXY(155, 17.2); 
$pdf->Cell(30, 5, !empty($ticket['ticket_created']) ? date('Y-m-d', strtotime($ticket['ticket_created'])) : 'N/A', 0, 0);

$pdf->SetXY(53, 21);   
$pdf->Cell(80, 5, $ticket['full_name'] ?? 'N/A', 0, 0);

$pdf->SetXY(53, 34); 
$pdf->Cell(120, 5, $ticket['description'] ?? 'N/A', 0, 0);

$pdf->SetXY(53, 38); 
$pdf->Cell(120, 5, $ticket['serial_number'] ?? 'N/A', 0, 0);

$pdf->SetXY(128.5, 21);   
$pdf->Cell(120, 5, 'TICKET NO:              ' . $ticket['ticket_no'], 0, 0);

if ($is_technical === 1) {
    $pdf->SetXY(53, 30);    
    $pdf->Cell(120, 5, $ticket['item_code'] ?? 'N/A', 0, 0);

    $pdf->SetXY(53, 34); 
    $pdf->Cell(120, 5, $ticket['description'] ?? 'N/A', 0, 0);

    $pdf->SetXY(53, 38); 
    $pdf->Cell(120, 5, $ticket['serial_number'] ?? 'N/A', 0, 0);

    $pdf->SetXY(53, 42); 
    $pdf->Cell(120, 5, $ticket['asset_tag_number'] ?? 'N/A', 0, 0);

    $pdf->SetXY(53, 55); 
    $pdf->Cell(120, 5, $ticket['received_by_name'] ?? 'N/A', 0, 0);

    $pdf->SetXY(53, 59.5); 
    $pdf->Cell(120, 5, !empty($ticket['date_received']) ? date('Y-m-d', strtotime($ticket['date_received'])) : 'N/A', 0, 0);
}

$display_text = !empty($ticket['revised_request']) ? $ticket['revised_request'] : ($ticket['purpose_of_request'] ?? 'N/A');
$pdf->SetXY(53, 46.5); 
$pdf->Cell(120, 5, $display_text, 0, 0);

$date_noted     = !empty($ticket['date_noted']) ? date('Y-m-d', strtotime($ticket['date_noted'])) : 'N/A';
$date_validated = !empty($ticket['date_validated']) ? date('Y-m-d', strtotime($ticket['date_validated'])) : 'N/A';
$date_verified  = !empty($ticket['date_verified']) ? date('Y-m-d', strtotime($ticket['date_verified'])) : 'N/A';

$base_dir = __DIR__ . '/../';
$techImagePath = !empty($ticket['approve_method_tech']) ? '../admin-tech/' . ltrim(trim($ticket['approve_method_tech']), '/') : '';
$adminSupImagePath = !empty($ticket['approve_method_adminsup']) ? '../admin-admin-support/' . ltrim(trim($ticket['approve_method_adminsup']), '/') : '';

if ($is_technical === 1) {
    if (!empty($techImagePath) && file_exists($techImagePath)) {
        $pdf->Image($techImagePath, 143, 25.5, 19, 5); 
    }
    $pdf->SetXY(163, 25.5); 
    $pdf->Cell(120, 5, $date_noted, 0, 0);
} else {
    if (!empty($adminSupImagePath) && file_exists($adminSupImagePath)) {
        $pdf->Image($adminSupImagePath, 143, 25.5, 19, 5); 
    }
    $pdf->SetXY(163, 25.5); 
    $pdf->Cell(120, 5, $date_validated, 0, 0);
}

$pdf->SetXY(143, 29.5); 
$pdf->Cell(120, 5, $ticket['noted_by_name'] ?? 'N/A', 0, 0);

if (!empty($ticket['approve_method_head'])) {
    $headImagePath = '../admin-head/' . ltrim(trim($ticket['approve_method_head']), '/');
    if (file_exists($headImagePath)) {
        $pdf->Image($headImagePath, 19, 148.5, 19, 5); 
    }
    $pdf->SetXY(41, 148.5);
    $pdf->Cell(110, 5, $date_verified, 0, 0);
}

if (!empty($ticket['approve_method_agm'])) {
    $agmImagePath = '../admin-agm/' . ltrim(trim($ticket['approve_method_agm']), '/');
    if (file_exists($agmImagePath)) {
        $pdf->Image($agmImagePath, 98, 148.5, 19, 5); 
    }
    $pdf->SetXY(115, 148.5);
    $pdf->Cell(110, 5, date('Y-m-d'), 0, 0);
}


if ($is_technical === 1) {
    $pdf->AddPage();

    $primaryColor   = [44, 62, 80];    
    $secondaryColor = [107, 114, 128]; 
    $accentColor    = [59, 130, 246];  
    $fillColor      = [243, 244, 246]; 

    $logoPath = '../owi.jpg';
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 15, 10, 25); 
    }

    $pdf->SetY(15);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
    $pdf->Cell(0, 6, 'INFORMATION TECHNOLOGY DEPARTMENT', 0, 1, 'C');

    $pdf->SetFont('Arial', 'B', 19);
    $pdf->SetTextColor($accentColor[0], $accentColor[1], $accentColor[2]);
    $pdf->Cell(0, 8, 'TECHNICAL SERVICE REPORT', 0, 1, 'C');
    
    $pdf->SetY(40); 
    $currentY = $pdf->GetY();

    $pdf->SetFillColor($fillColor[0], $fillColor[1], $fillColor[2]); 
    $pdf->Rect(10, $currentY, 190, 12, 'F');
    $pdf->SetXY(12, $currentY + 3.5);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor($accentColor[0], $accentColor[1], $accentColor[2]);
    $pdf->Cell(85, 5, 'TICKET NO: ' . $ticket['ticket_no'], 0, 0, 'L');

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
    $pdf->Cell(100, 5, 'DEPT/BRANCH: ' . strtoupper($ticket['str_name'] ?? 'N/A'), 0, 1, 'R');
    
    $pdf->Ln(8);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
    $pdf->Cell(100, 5, 'Technical Support: ' . ($ticket['received_by_name'] ?? 'Unknown'), 0, 0);

    $pdf->SetFont('Arial', '', 9);
    $pdf->SetTextColor($secondaryColor[0], $secondaryColor[1], $secondaryColor[2]);
    $date_sub = !empty($ticket['date_submitted']) ? date('M d, Y', strtotime($ticket['date_submitted'])) : 'N/A';
    $pdf->Cell(90, 5, 'Date: ' . $date_sub, 0, 1, 'R');
    $pdf->Ln(5);

    $sections = [
        'PROBLEM REPORTED'                         => $ticket['problem_reported'] ?? '',
        'VERIFICATION / FINDINGS'                  => $ticket['verification_findings'] ?? '', 
        'WORK DONE / TECHNICAL SOLUTIONS PROVIDED' => $ticket['work_done'] ?? '',
        'STATUS / WORK OUTPUT'                     => $ticket['status_workoutput'] ?? '',
        'RECOMMENDATIONS / SUGGESTIONS'            => $ticket['recommendation'] ?? ''
    ];

    foreach ($sections as $title => $content) {
        $content = trim($content);
        if (empty($content)) $content = 'N/A'; 

        $pdf->SetFillColor(235, 240, 245); 
        $pdf->SetDrawColor(200, 210, 220); 
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor(30, 60, 90); 
        $pdf->Cell(190, 7, '  ' . $title, 'L T R', 1, 'L', true);

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->MultiCell(190, 6, "  " . $content . "\n", 'L B R', 'L', true);
        $pdf->Ln(3); 
    }

    $pdf->Ln(5);

    $sql3 = "SELECT far.remarks_note, far.date_remarks , CONCAT(u.fname, ' ', u.lstname) AS remarks
             FROM fixed_asset_remarks far
             LEFT JOIN users u ON u.id = far.remarks_by
             WHERE far.ticket_no = ? AND far.remarks_by = 7 
             ORDER BY far.date_remarks DESC LIMIT 1";
             
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param("s", $ticket_no);
    $stmt3->execute();
    $remark_result = $stmt3->get_result()->fetch_assoc();
    
    if ($remark_result) {
        if ($pdf->GetY() > 220) {
            $pdf->AddPage();
        } 
        
        $pdf->SetFillColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $pdf->SetTextColor(255, 255, 255); 
        
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(140, 7, '  MANAGER / SUPERVISOR-ON-DUTY REMARKS / COMMENTS', 1, 0, 'L', true);
        
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(50, 7, date('M d, Y', strtotime($remark_result['date_remarks'])) . '  ', 1, 1, 'R', true);
        $pdf->Cell(190, 6, '  ' . $remark_result['remarks'], 1, 1, 'L', true);
        
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(31, 41, 55);
        $pdf->SetFont('Arial', 'I', 9);
        $pdf->Cell(190, 6, '  ' . $remark_result['remarks_note'], 1, 1, 'L', true);
    }

    $pdf->Ln(20);
    if ($pdf->GetY() > 240) {
        $pdf->AddPage();
    }
    
    $sigY = $pdf->GetY() + 10;
    
    $techImagePath = !empty($ticket['approve_method_tech']) ? '../admin-tech/' . ltrim(trim($ticket['approve_method_tech']), '/') : '';
    if (!empty($techImagePath) && file_exists($techImagePath)) {
        $pdf->Image($techImagePath, 20, $sigY - 5, 47, 10);
    }

    $pdf->SetDrawColor(0, 0, 0); 
    
    $pdf->SetXY(25, $sigY);
    $pdf->Cell(36, 5, '', 'B', 0, 'C'); 
    $date_noted_display = !empty($ticket['date_noted']) ? date('m-d-Y', strtotime($ticket['date_noted'])) : 'N/A';
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(25, 5, '' . $date_noted_display, 0, 0, 'L');
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(31, 41, 55);
    $pdf->SetXY(15, $sigY + 5);
    $pdf->Cell(60, 5, strtoupper($ticket['noted_by_name'] ?? 'N/A'), 0, 0, 'C');
    
    // Roles
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetXY(15, $sigY + 10);
    $pdf->Cell(60, 5, 'Manager/Supervisor-on-Duty', 0, 0, 'C');
   

} elseif ($is_technical === 0) {
    $pdf->AddPage();

    $primaryColor   = [44, 62, 80];   
    $secondaryColor = [107, 114, 128];
    $accentColor    = [59, 130, 246]; 
    $fillColor      = [243, 244, 246]; 

    $logoPath = '../owi.jpg';
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 15, 10, 25); 
    }

    $pdf->SetY(15);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
    $pdf->Cell(0, 8, 'ADMIN DEPARTMENT', 0, 1, 'C');

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor($accentColor[0], $accentColor[1], $accentColor[2]);
    $pdf->Cell(0, 6, 'ADMIN SUPPORT SERVICE REPORT', 0, 1, 'C');
    
    $pdf->SetY(40); 
    $currentY = $pdf->GetY();

    $pdf->SetFillColor($fillColor[0], $fillColor[1], $fillColor[2]); 
    $pdf->Rect(10, $currentY, 190, 12, 'F');
    $pdf->SetXY(12, $currentY + 3.5);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor($accentColor[0], $accentColor[1], $accentColor[2]);
    $pdf->Cell(85, 5, 'TICKET NO: ' . $ticket['ticket_no'], 0, 0, 'L');

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
    $pdf->Cell(100, 5, 'DEPT/BRANCH: ' . strtoupper($ticket['str_name'] ?? 'N/A'), 0, 1, 'R');

    $pdf->Ln(8);
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
    $pdf->Cell(100, 5, 'Admin Support: ' . ($ticket['received_by_name'] ?? 'Unknown'), 0, 0);
    
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetTextColor($secondaryColor[0], $secondaryColor[1], $secondaryColor[2]);
    $date_sub = !empty($ticket['date_validated']) ? date('M d, Y', strtotime($ticket['date_validated'])) : 'N/A';
    $pdf->Cell(90, 5, 'Date: ' . $date_sub, 0, 1, 'R');
    
    $pdf->Ln(8);
    
    $pdf->SetFillColor(235, 240, 245); 
    $pdf->SetDrawColor(200, 210, 220); 
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(30, 60, 90); 
    $pdf->Cell(190, 7, '  WORK OUTPUT DETAILS', 'L T R', 1, 'L', true);

    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(40, 40, 40);
    $workoutput = !empty($ticket['technical_workoutput']) ? trim($ticket['technical_workoutput']) : 'N/A';
    $pdf->MultiCell(190, 6, "  " . $workoutput . "\n", 'L B R', 'L', true);
    
    $pdf->Ln(8);
    
    $sqlImg = "SELECT files_name FROM images WHERE ticket_no = ? LIMIT 1";
    $stmtImg = $conn->prepare($sqlImg);
    $stmtImg->bind_param("s", $ticket_no);
    $stmtImg->execute();
    $imgResult = $stmtImg->get_result()->fetch_assoc();

    if ($imgResult && !empty($imgResult['files_name'])) {
        $fileNameRaw = trim($imgResult['files_name']);
        $fileNameFormatted = str_replace('\\', '/', $fileNameRaw);
        $extractedFileName = basename($fileNameFormatted);
        $dynamicImagePath = '../users/image/' . $extractedFileName;

        if (file_exists($dynamicImagePath)) {
            
            // Define fixed maximum dimensions for the image so it is standard across all reports
            $maxWidth = 150;
            $maxHeight = 80;
            
            $imgInfo = @getimagesize($dynamicImagePath);
            $finalWidth = $maxWidth;
            $finalHeight = $maxHeight;

            // Maintain aspect ratio while fitting into our strict maximum boundaries
            if ($imgInfo && $imgInfo[0] > 0 && $imgInfo[1] > 0) {
                $ratio = $imgInfo[0] / $imgInfo[1];
                if (($maxWidth / $ratio) <= $maxHeight) {
                    $finalWidth = $maxWidth;
                    $finalHeight = $maxWidth / $ratio;
                } else {
                    $finalHeight = $maxHeight;
                    $finalWidth = $maxHeight * $ratio;
                }
            }

            // Calculate total space needed for (Label 7) + (Image) + (Gap 20) + (Signature 20)
            // If it won't fit on this page, bump the whole block to a new page together
            if (($pdf->GetY() + 7 + $finalHeight + 40) > 270) {
                $pdf->AddPage();
            }

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetTextColor(30, 60, 90); 
            $pdf->Cell(0, 7, 'IMAGE ATTACHED VIA HELPDESK:', 0, 1, 'L');
            
            $imgY = $pdf->GetY();
            
            // Print the image with fixed constraints
            $pdf->Image($dynamicImagePath, 10, $imgY, $finalWidth, $finalHeight); 
            
            // Set the exact Y position below the image, plus a 20mm uniform gap
            $pdf->SetY($imgY + $finalHeight + 20); 

        } else {
            $pdf->SetFont('Arial', 'I', 9);
            $pdf->SetTextColor(150, 150, 150); 
            $pdf->Cell(0, 7, 'Image file specified in database was not found on the server.', 0, 1, 'L');
        }
    }

    // Ensure space for the signature if the image block didn't run
    if ($pdf->GetY() > 250) {
        $pdf->AddPage();
    }
    
    $sigY = $pdf->GetY(); // Position for the signature block
    
    $adminSupImagePath = !empty($ticket['approve_method_head']) ? '../admin-head/' . ltrim(trim($ticket['approve_method_head']), '/') : '';
    if (!empty($adminSupImagePath) && file_exists($adminSupImagePath)) {
        $pdf->Image($adminSupImagePath, 15, $sigY - 2, 60, 10);
    }

    $pdf->SetDrawColor(0, 0, 0);
    
    $date_validated_display = !empty($ticket['date_validated']) ? date('m-d-Y', strtotime($ticket['date_validated'])) : 'N/A';
    
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetTextColor(31, 41, 55);
    $pdf->SetXY(85, $sigY + 5); 
    $pdf->Cell(50, 5, $date_validated_display, 0, 1, 'L');
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetXY(15, $sigY + 5);
    $pdf->Cell(60, 5, 'ALMA M. VILLANUEVA', 0, 1, 'C');
    
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetXY(15, $sigY + 10);
    $pdf->Cell(60, 5, 'Admin', 0, 1, 'C');

}

$pdf->AddPage(); 
$primaryColor   = [44, 62, 80];    
$secondaryColor = [107, 114, 128];
$accentColor    = [59, 130, 246]; 
$borderColor    = [209, 213, 219]; 
$fillColor      = [249, 250, 251]; 

$pdf->SetY(15);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
$pdf->Cell(0, 8, 'OWI HELPDESK COMMENT THREAD', 0, 1, 'C');
$pdf->Ln(8);

$currentY = $pdf->GetY();
$pdf->SetFillColor(243, 244, 246); 
$pdf->Rect(10, $currentY, 190, 12, 'F');
$pdf->SetXY(12, $currentY + 3.5);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor($accentColor[0], $accentColor[1], $accentColor[2]);
$pdf->Cell(85, 5, 'TICKET NO: ' . $ticket['ticket_no'], 0, 0, 'L');

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
$pdf->Cell(100, 5, 'DEPT/BRANCH: ' . strtoupper($ticket['str_name'] ?? 'N/A'), 0, 1, 'R');

$pdf->Ln(10);

$sql2 = "SELECT rc.comment_details, rc.comment_date, u.fname, u.lstname, it.cmp_role
         FROM reports_comments rc
         LEFT JOIN users u ON rc.userId = u.id
         LEFT JOIN it_tech it ON u.tech_id = it.itsup
         WHERE rc.ticket_no = ?
         ORDER BY rc.comment_date ASC";

$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("s", $ticket_no);
$stmt2->execute();
$comments_result = $stmt2->get_result();

if ($comments_result->num_rows > 0) {
    while ($comment = $comments_result->fetch_assoc()) {
        if ($pdf->GetY() > 250) {
            $pdf->AddPage();
        }

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $pdf->Cell(100, 5, ($comment['fname'] ?? '') . ' ' . ($comment['lstname'] ?? ''), 0, 0);

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor($secondaryColor[0], $secondaryColor[1], $secondaryColor[2]);
        
        $formatted_date = !empty($comment['comment_date']) ? date('Y-m-d H:i', strtotime($comment['comment_date'])) : '';
        $pdf->Cell(90, 5, $formatted_date, 0, 1, 'R');

        $pdf->SetFont('Arial', 'I', 8);
        $pdf->SetTextColor($accentColor[0], $accentColor[1], $accentColor[2]);
        $pdf->Cell(0, 4, !empty($comment['cmp_role']) ? $comment['cmp_role'] : 'REQUESTING EMPLOYEE', 0, 1, 'L');

        $pdf->Ln(2);

        $pdf->SetDrawColor($borderColor[0], $borderColor[1], $borderColor[2]); 
        $pdf->SetFillColor($fillColor[0], $fillColor[1], $fillColor[2]);
        $pdf->SetTextColor(31, 41, 55);
        $pdf->SetFont('Arial', '', 10);
        
        $pdf->MultiCell(190, 7, $comment['comment_details'] ?? '', 1, 'L', true);
        $pdf->Ln(5); 
    }
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->SetTextColor($secondaryColor[0], $secondaryColor[1], $secondaryColor[2]);
    $pdf->Cell(0, 10, 'No comments available for this ticket.', 0, 1, 'L');
}

$pdf->Output('I', 'Fixed_Asset_Form_' . $ticket_no . '.pdf');
exit;
?>