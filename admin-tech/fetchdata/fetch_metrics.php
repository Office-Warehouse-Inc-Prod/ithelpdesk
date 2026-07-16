<?php
include '../condb.php';
$conn = (new dbconfig())->connection;

$month = $_POST['month'] ?? '';
$year = $_POST['year'] ?? '';

$where = " WHERE 1=1 ";
if (!empty($month)) $where .= " AND MONTH(created_at) = '$month' ";
if (!empty($year))  $where .= " AND YEAR(created_at) = '$year' ";
case 'fa_reports_tbl':
    // $fn->fareportsthist() already returns ['table_data' => ..., 'metrics' => ...]
    $records = $fn->fareportsthist(); 
    break;
$query = "SELECT status, COUNT(*) as count FROM asset_requests $where GROUP BY status";
$stmt = $conn->prepare($query);
$stmt->execute();
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>