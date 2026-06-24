<?php
include('../fpdf/fpdf.php');

$pdf = new FPDF('P', 'mm', 'A4'); 

$pdf->SetAutoPageBreak(false, 0);
$pdf->AddPage();

$pdf->Image('C:\xampp\htdocs\helpdesk\ithelpdesk\Fixed_Asset_Requisition_Transfer Form.jpg', 0, 0, 210, 297);

$pdf->SetAutoPageBreak(true, 15);


$pdf->SetXY(10, 10); 

$pdf->SetFont("Arial", "", 8);

$pdf->Cell(75, 35, 'CENTRAL OFFICE - LIBIS', 0, 0,'R');
$pdf->Cell(100, 35, '06/24/2026', 0, 0,'R');
$pdf->Cell(-99, 45, 'KARL ANGELO MAGPAYO', 0, 0,'R');
$pdf->Cell(-99, 55, 'NOT WORKING', 0, 0,'R');



$pdf->Output();
?>