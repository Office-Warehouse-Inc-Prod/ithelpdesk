<?php
// get-pdf.php (located in users folder)
$PDtkt = isset($_GET['ticket']) ? $_GET['ticket'] : '';

if (empty($PDtkt)) {
    header("HTTP/1.0 400 Bad Request");
    echo "Ticket parameter is required";
    exit;
}

// Since this file is in /ithelpdesk/users/, the pdf folder is now adjacent
$pdfDir = __DIR__ . '/pdf/';
// This resolves to: C:/xampp/htdocs/ithelpdesk/users/pdf/

// Alternative absolute path:
// $pdfDir = 'C:/xampp/htdocs/ithelpdesk/users/pdf/';

error_log("Searching for PDF with ticket: " . $PDtkt);
error_log("Directory: " . $pdfDir);

// Find PDF files that start with the ticket number
$files = glob($pdfDir . $PDtkt . '*.pdf');

if (count($files) > 0) {
    $pdfPath = $files[0];
    
    error_log("Found PDF: " . $pdfPath);
    
    if (file_exists($pdfPath)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($pdfPath) . '"');
        header('Content-Length: ' . filesize($pdfPath));
        readfile($pdfPath);
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "PDF file not found at: " . $pdfPath;
    }
} else {
    header("HTTP/1.0 404 Not Found");
    echo "No RARS found for ticket: " . htmlspecialchars($PDtkt) . "<br>";
}
?>