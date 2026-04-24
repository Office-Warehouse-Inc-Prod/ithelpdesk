<?php
session_start();

// Correct the include paths based on the file location
$base_dir = __DIR__; // This gives the directory of insertpdf.php (users folder)

// Include database connection - adjust path as needed
if (file_exists($base_dir . '/database.php')) {
    include($base_dir . '/database.php');
} elseif (file_exists($base_dir . '/../database.php')) {
    include($base_dir . '/../database.php');
} else {
    die(json_encode(['success' => false, 'error' => 'Database configuration not found']));
}

// Include FPDF - adjust path as needed
if (file_exists($base_dir . '/../fpdf/fpdf.php')) {
    require($base_dir . '/../fpdf/fpdf.php');
} elseif (file_exists($base_dir . '/fpdf/fpdf.php')) {
    require($base_dir . '/fpdf/fpdf.php');
} else {
    die(json_encode(['success' => false, 'error' => 'FPDF library not found']));
}

class PDF extends FPDF {
    private $connection;

    public function __construct($dbConnection) {
        parent::__construct();
        $this->connection = $dbConnection;
    }

    public function gcounts() {
        // Add input validation
        if (!isset($_POST['ticket_no']) || empty($_POST['ticket_no'])) {
            return 0;
        }
        
        $series = $_POST['ticket_no'];
        $query = "SELECT COUNT(id) FROM `tbl_pditems` WHERE ticket_no = :series";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':series', $series);
        
        if ($statement->execute()) {
            $count = $statement->fetchColumn();
            return $count ?: 0;
        }
        
        return 0;
    }

    public function gdetails() {
        // Add input validation
        if (!isset($_POST['ticket_no']) || empty($_POST['ticket_no'])) {
            return array();
        }
        
        $series = $_POST['ticket_no'];
        $query = "SELECT * FROM tbl_pditems WHERE ticket_no = :series ORDER BY id"; // Added ORDER BY for consistency
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':series', $series);
        
        if ($statement->execute()) {
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            $fetchdata = array();
            $itemNumber = 1;
            
            foreach ($result as $row) {
                $fetchdata[] = array(
                    'nox' => $itemNumber, // Sequential item number
                    'serialx' => $row['serial_no'] ?? '',
                    'descx' => $row['description'] ?? '',
                    'qty' => $row['qty'] ?? '1', // Use actual qty from database if available
                    'nature' => $row['defect'] ?? 'broken' // Use actual nature from database if available
                );
                $itemNumber++;
            }
            
            return $fetchdata;
        }
        
        return array();
    }
}

if ($_POST["operation"] == "PRINTPDF") {
    // Add validation for required fields
    if (!isset($_POST['ticket_no']) || empty($_POST['ticket_no'])) {
        echo json_encode(['success' => false, 'error' => 'Missing ticket number']);
        exit;
    }
    
    if (!isset($_POST['reason']) || !isset($_POST['Supplierx'])) {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit;
    }
    
    try {
        $date = date('Y-m-d');
        $pdf = new PDF($connection);
        $pdf->AddPage('P', array(215.9, 279));
        $pdf->SetMargins(10, 10, 10);
        $pdf->Ln(10);

        // Handle logo path
        $logoPath = '';
        if (file_exists($base_dir . '/../icons/owi_logo.jpg')) {
            $logoPath = $base_dir . '/../icons/owi_logo.jpg';
        } elseif (file_exists($base_dir . '/icons/owi_logo.jpg')) {
            $logoPath = $base_dir . '/icons/owi_logo.jpg';
        }

        // PDF Header - Centered with improved spacing
        $pdf->SetFont('Arial', 'B', 10);
        if ($logoPath) {
            $pdf->Cell(195, 25, $pdf->Image($logoPath, 95, 22, 23), 0, 1, 'C');
        } else {
            $pdf->Cell(195, 25, 'LOGO NOT FOUND', 0, 1, 'C');
        }

        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Cell(195, 8, 'OFFICE WAREHOUSE, INC.', 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Cell(195, 8, $_POST['Store'] . ' BRANCH', 0, 1, 'C');

        $pdf->SetFont('Arial', 'I', 9);
        $pdf->Cell(195, 5, $_POST['Adrs'], 0, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(195, 5, $_POST['Contact'], 0, 1, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(195, 10, 'RETURN AUTHORIZATION RELEASE SLIP (RARS)', 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 30);
        $pdf->Cell(195, 3, '', '', 1, 'C');

        // Header info - Left aligned, no borders with better spacing
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(35, 4, 'RARS No.');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(70, 4, $_POST['RarsNo']);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Ln();
        $pdf->Cell(35, 4, 'Date Issued:');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(70, 4, date('m-d-Y', strtotime($date)));
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(35, 4, 'RS E-Ticket Ref. No.');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(70, 4, $_POST['ticket_no']);
        $pdf->Ln(8);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(35, 4, 'Supplier Name:');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(70, 4, $_POST['Supplierx']);
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(35, 4, 'Pick-up / Delivery Date:');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(160, 4, '');
        $pdf->Ln(8);

        // Table Header
        $pdf->Ln();
        $pdf->Cell(10, 5, '');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(195, 0, 'Item Details:', 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->Cell(1, 5, '', 'R');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(10, 5, 'No.', 'TRB', '', 'C');
        $pdf->Cell(50, 5, 'Serial No.', 'TRB', '', 'C');
        $pdf->Cell(84, 5, 'Description Brand / Size / Attr.', 'TRB', '', 'C');
        $pdf->Cell(10, 5, 'Qty', 'TRB', '', 'C');
        $pdf->Cell(40, 5, 'Nature of Defect', 'TRB', '', 'C');
        $pdf->Cell(10, 5, '', 'L');
        $pdf->Ln();

        $gcount = $pdf->gcounts();
        $getarr = $pdf->gdetails();

        // Check if there are any items to display
        if (!empty($getarr)) {
            foreach ($getarr as $items) {
                $pdf->Cell(1, 5, '', 'R');
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(10, 5, strtoupper($items['nox']), 'TRB', '', 'C');
                $pdf->Cell(50, 5, strtoupper($items['serialx']), 'TRB', '', 'C');
                $pdf->Cell(84, 5, strtoupper($items['descx']), 'TRB', '', 'C');
                $pdf->Cell(10, 5, strtoupper($items['qty']), 'TRB', '', 'C');
                $pdf->Cell(40, 5, strtoupper($items['nature']), 'TRB', '', 'C');
                $pdf->Cell(10, 5, '', '');
                $pdf->Ln();
            }
        } else {
            $pdf->Cell(1, 5, '', 'R');
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(193, 5, 'No items found.', 'B', 0, 'C');
            $pdf->Cell(1, 5, '', 'BR');
            $pdf->Ln();
        }

        // Reason section
        $pdf->Cell(1,5,'','R');
        $pdf->Cell(144,5,'Total QTY:','LTRB','','R');
        $pdf->Cell(10,5, $gcount, 'LBR', 0, 'C');
        $pdf->Cell(40,5,'','LBR');
        $pdf->Ln();

        $pdf->Ln();
        $pdf->SetFont('Arial','BUI',9);
        $pdf->Cell(60,5,'Remarks/Special Instructions:','','','L');

        $pdf->Ln();
        $pdf->Cell(10,5,'','LT');
        $pdf->Cell(185,5,'','RT');
        $pdf->Ln();
        $pdf->Cell(10,5,'','L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(110,0,strtoupper($_POST['reason']),'');
        $pdf->Cell(75,5,'','R');

        $pdf->Ln();
        $pdf->Cell(10,5,'','LB');
        $pdf->Cell(185,5,'','BR');
        $pdf->Ln();

        // Footer section
        $pdf->Ln();
        $pdf->SetFont('Arial','BUI',9);
        $pdf->Cell(60,5,'Acknowledgement:','','','L');

        $pdf->Ln();
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(120,5,'Released by: (Store/Warehouse)','','','');
        $pdf->Cell(60,5,'Received by: (Supplier/Logistics)','','','');

        $pdf->Ln();
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(20,5,'Name:','','','');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(40, 5, ($_SESSION['first_name'] ?? '') . " " . ($_SESSION['last_name'] ?? ''), 'B', '', 'C');
        $pdf->Cell(60,5,'','','','C');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(25,5,'Name:','','','');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(40,5,'','B','','C');
        $pdf->Cell(10,5,'','');
        $pdf->Ln();
        $pdf->Cell(195,5,'','');

        $pdf->Ln();
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(20,5,'Signature:','','','');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(40, 5, '', 'B', '', 'C');
        $pdf->Cell(60,5,'','','','C');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(25,5,'Signature:','','','');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(40,5,'','B','','C');
        $pdf->Cell(10,5,'','');
        $pdf->Ln();
        $pdf->Cell(195,5,'','');

        $pdf->Ln();
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(20,5,'Date:','','','');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(40, 5, '', 'B', '', 'C');
        $pdf->Cell(60,5,'','','','C');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(25,5,'Date:','','','');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(40,5,'','B','','C');
        $pdf->Cell(10,5,'','');
        $pdf->Ln();
        $pdf->Cell(195,5,'','');

        $pdf->Ln();
        $pdf->SetFont('Arial','I',8);
        $pdf->Cell(60,5,'Distribution of Copies:','','','L');
        $pdf->Ln();
        $pdf->Cell(60,2,'NCR: 1st Copy - Supplier, 2nd Copy - Store  |  Provincial: 1st & 2nd Copies - Supplier & Warehouse, 3rd Copy - Store','','','L');

        // Create PDF directory if it doesn't exist
        $targetDir = $base_dir . '/pdf/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Clean the filename
        $cleanTicketNo = preg_replace('/[^a-zA-Z0-9_-]/', '_', $_POST['ticket_no']);
        $pdfFileName = $cleanTicketNo . '_' . date('Y-m-d') . '.pdf';
        $pdfFilePath = $targetDir . $pdfFileName;
        
        // Output PDF to file
        $pdf->Output('F', $pdfFilePath);

        // Return JSON response with web-accessible URL
        $pdfUrl = 'pdf/' . $pdfFileName;
        
        echo json_encode([
            'success' => true, 
            'pdfUrl' => $pdfUrl,
            'message' => 'PDF generated successfully',
            'filePath' => $pdfFilePath
        ]);
        
    } catch (Exception $e) {
        error_log("PDF generation error: " . $e->getMessage());
        echo json_encode([
            'success' => false, 
            'error' => 'PDF generation failed: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid operation']);
}
?>