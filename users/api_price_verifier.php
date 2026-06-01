<?php
header('Content-Type: application/json; charset=utf-8');

/*
    API for VB.NET Price Verifier
    Place this file in the same folder as fetch.php and function.php.

    This does NOT use PHP session.
    The VB app must send:
    - api_key
    - kprvr
    - sbs_no
    - price_lvl
*/

include('function.php');

$VALID_API_KEY = 'OWI_PRICE_VERIFIER_2026';

$api_key = $_POST['api_key'] ?? '';

if ($api_key !== $VALID_API_KEY) {
    echo json_encode([
        [
            'FDetails' => 'UNAUTHORIZED REQUEST',
            'Price_WT' => ' '
        ]
    ]);
    exit;
}

$kprvr = trim($_POST['kprvr'] ?? '');
$sbs_no = trim($_POST['sbs_no'] ?? '');
$price_lvl = trim($_POST['price_lvl'] ?? '');

if ($kprvr === '' || $sbs_no === '' || $price_lvl === '') {
    echo json_encode([
        [
            'FDetails' => 'MISSING REQUIRED VALUE',
            'Price_WT' => ' '
        ]
    ]);
    exit;
}

// Normalize barcode:
// 000993 -> 993
// 00993  -> 993
// 0993   -> 0993
$kprvr = preg_replace('/^0{2,}(?=\d)/', '', $kprvr);

// Set POST values because your existing function pv_res() reads from $_POST.
$_POST['kprvr'] = $kprvr;
$_POST['sbs_no'] = $sbs_no;
$_POST['price_lvl'] = $price_lvl;

try {
    $fn = new dbconfig();
    $output = $fn->pv_res();

    echo json_encode($output);
    exit;
} catch (Throwable $e) {
    echo json_encode([
        [
            'FDetails' => 'API ERROR: ' . $e->getMessage(),
            'Price_WT' => ' '
        ]
    ]);
    exit;
}
?>
