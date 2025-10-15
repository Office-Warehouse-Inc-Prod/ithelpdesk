<?php
// Include your actual PDF class
require('fpdf/fpdf.php'); // Adjust path as needed

// Mock session and POST data for testing
session_start();
$_SESSION['first_name'] = 'Test';
$_SESSION['last_name'] = 'User';

$_POST = [
    'strname1' => 'Test Source',
    'seriesIn2' => 'TEST-001',
    'goto' => 'Test Destination', 
    'docs' => 'Test Document',
    'reason' => 'Testing PDF layout for incoming items',
    'pulname' => 'John Smith',
    'recdate' => date('Y-m-d')
];

// Create a simple test version of your PDF class
class TestPDF extends FPDF {
    public function __construct() {
        parent::__construct();
    }
    
    public function gcountIncoming() {
        return 3; // Test count
    }
    
    public function gpdetailsIncoming() {
        return [
            ['nox' => '1','descx' => 'Laptop Computer', 'attr' => 'Dell XPS 13', 'serialx' => 'DLXPS132023001',  'qty' => '1', 'nature' => 'broken'],
            ['nox' => '2','descx' => 'Wireless Mouse', 'attr' => 'Logitech MX Master', 'serialx' => 'LGMXMS2023001', 'qty' => '5', 'nature' => 'broken'],
            ['nox' => '3','descx' => 'Mechanical Keyboard', 'attr' => 'Keychron K8', 'serialx' => 'KCK82023001', 'qty' => '3', 'nature' => 'broken']
        ];
    }
}

$date = date('Y-m-d');
$pdf = new TestPDF();
$pdf->AddPage('P', array(215.9, 279));
$pdf->SetMargins(10, 10, 10);
$pdf->Ln(10);

// PDF Header - Centered with improved spacing
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(195, 25, $pdf->Image('icons/owi_logo.jpg', 95, 22, 23), 0, 1, 'C'); // center image

$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(195, 8, 'OFFICE WAREHOUSE, INC.', 0, 1, 'C'); // center below the image

$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(195, 8, 'STORE BRANCH', 0, 1, 'C');

$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(195, 5, 'Lot 1 Blk 13 e. rodreiguez jr. ave. brgy. bagumbayan quezon city', 0, 1, 'C');

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(195, 5, '+63 8376 0887', 0, 1, 'C');

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
$pdf->Cell(70, 4, $_POST['strname1']);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Ln();
$pdf->Cell(35, 4, 'Date Issued:');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(70, 4, $_POST['seriesIn2']);
$pdf->Ln();

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(35, 4, 'RS E-Ticket Ref. No.');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(70, 4, $_POST['goto']);
$pdf->Ln(8);

$pdf->Ln();
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(35, 4, 'Supplier Name:');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(70, 4, date('m-d-Y', strtotime($date)));
$pdf->Ln();

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(35, 4, 'Pick-up / Delivery Date:');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(160, 4, strtoupper($_POST['docs']));
$pdf->Ln(8);

// Table Header
$pdf->Ln();
$pdf->Cell(10, 5, '', '');
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
// $pdf->Cell(10, 5, 'QTY', 'TRB', '', 'C');
$pdf->Cell(10, 5, '', 'L');
$pdf->Ln();

$gcount = $pdf->gcountIncoming();
$getarr = $pdf->gpdetailsIncoming();
// $getarr = '';

// Check if there are any items to display
if (!empty($getarr)) {
    foreach ($getarr as $items) {
        // Add a new row in the PDF
        $pdf->Cell(1, 5, '', 'R');
        $pdf->SetFont('Arial', '', 9);

        // Add the data cells
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
    // $pdf->Cell(10, 5, '', 'R');
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
$pdf->Cell(40, 5, $_SESSION['first_name'] . " " . $_SESSION['last_name'], 'B', '', 'C');
$pdf->Cell(60,5,'','','','C'); // space between
$pdf->SetFont('Arial','B',9);
$pdf->Cell(25,5,'Name:','','','');
$pdf->SetFont('Arial','',9);
$pdf->Cell(40,5,$_POST['pulname'],'B','','C');
$pdf->Cell(10,5,'','');
$pdf->Ln();
$pdf->Cell(195,5,'','');

$pdf->Ln();
$pdf->SetFont('Arial','B',9);
$pdf->Cell(20,5,'Signature:','','','');
$pdf->SetFont('Arial','',9);
$pdf->Cell(40, 5, $_SESSION['first_name'] . " " . $_SESSION['last_name'], 'B', '', 'C');
$pdf->Cell(60,5,'','','','C'); // space between
$pdf->SetFont('Arial','B',9);
$pdf->Cell(25,5,'Signature:','','','');
$pdf->SetFont('Arial','',9);
$pdf->Cell(40,5,$_POST['pulname'],'B','','C');
$pdf->Cell(10,5,'','');
$pdf->Ln();
$pdf->Cell(195,5,'','');

$pdf->Ln();
$pdf->SetFont('Arial','B',9);
$pdf->Cell(20,5,'Date:','','','');
$pdf->SetFont('Arial','',9);
$pdf->Cell(40, 5, $_SESSION['first_name'] . " " . $_SESSION['last_name'], 'B', '', 'C');
$pdf->Cell(60,5,'','','','C'); // space between
$pdf->SetFont('Arial','B',9);
$pdf->Cell(25,5,'Date:','','','');
$pdf->SetFont('Arial','',9);
$pdf->Cell(40,5,$_POST['pulname'],'B','','C');
$pdf->Cell(10,5,'','');
$pdf->Ln();
$pdf->Cell(195,5,'','');


$pdf->Ln();
$pdf->SetFont('Arial','I',8);
$pdf->Cell(60,5,'Distribution of Copies:','','','L');
$pdf->Ln();
$pdf->Cell(60,2,'NCR: 1st Copy - Supplier, 2nd Copy - Store  |  Provincial: 1st & 2nd Copies - Supplier & Warehouse, 3rd Copy - Store','','','L');


// Output the PDF
$pdf->Output('I', 'incoming_items_preview.pdf');
?>