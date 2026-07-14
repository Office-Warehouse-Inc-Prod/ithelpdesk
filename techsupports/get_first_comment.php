<?php
require_once __DIR__ . '/../condb.php';
$conn = new dbconfig();
$ticket_no = isset($_POST['ticket_no']) ? trim($_POST['ticket_no']) : '';

$response = [
    'purpose'             => '',
    'cat_desc'            => '',
    'sub_cat'             => '',
    'date_created'        => '',
    'requested_by_name'   => '',
    'requested_db_name'   => '',
    'requesting_dept'     => '',
    'requesting_employee' => '',
    'serial_number'       => '',
    'status'              => '',
    'date_submitted'      => '',
    'date_noted'          => '',
    'date_validated'      => '',
    'date_printed'        => '',
    'date_recorded'       => '',
    'date_verified'       => '',
    'date_approved'       => '',
    'date_completed'      => ''
];

if ($ticket_no !== '') {
    $query = "SELECT comment_details FROM reports_comments WHERE ticket_no = ? ORDER BY comment_date ASC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $ticket_no);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $response['purpose'] = $row['comment_details'];
    }

    $threadQuery = "SELECT rc.comment_details, rc.comment_date, u.fname, u.lstname
                    FROM reports_comments rc
                    LEFT JOIN users u ON rc.userId = u.id
                    WHERE rc.ticket_no = ?
                    ORDER BY rc.comment_date ASC";
    $stmtThread = $conn->prepare($threadQuery);
    $stmtThread->bind_param("s", $ticket_no);
    $stmtThread->execute();
    $threadResult = $stmtThread->get_result();
    $response['thread'] = [];
    while ($threadRow = $threadResult->fetch_assoc()) {
        $response['thread'][] = [
            'comment' => $threadRow['comment_details'],
            'date' => $threadRow['comment_date'],
            'user' => trim($threadRow['fname'] . ' ' . $threadRow['lstname'])
        ];
    }
} else {
    $response['thread'] = [];
}

$query2 = "SELECT c.cat_desc, s.sub_cat, r.date_created, u.fname, u.lstname, b.str_name, r.userId, r.store
           FROM reports r
           LEFT JOIN categories c ON r.cat_id = c.cat_id
           LEFT JOIN subcat s ON r.sub_id = s.sub_id
           LEFT JOIN users u ON r.userId = u.id
           LEFT JOIN tbl_branch b ON r.store = b.str_num
           WHERE r.ticket_no = ?";

$stmt2 = $conn->prepare($query2);
$stmt2->bind_param("s", $ticket_no);
$stmt2->execute();
$result2 = $stmt2->get_result();
if ($row2 = $result2->fetch_assoc()) {
    $response['cat_desc']            = $row2['cat_desc'];
    $response['sub_cat']             = $row2['sub_cat'];
    $response['date_created']        = $row2['date_created'];
    $response['requested_by_name']   = $row2['fname'] . ' ' . $row2['lstname'];
    $response['requested_db_name']   = $row2['str_name'];
    $response['requesting_dept']     = $row2['store'];
    $response['requesting_employee'] = $row2['userId'];
}

$query3 = "SELECT serial_number, status, date_submitted, date_noted, date_validated, 
                  date_printed, date_recorded, date_verified, date_approved, date_completed 
           FROM asset_requests 
           WHERE ticket_no = ?";

$stmt3 = $conn->prepare($query3);
$stmt3->bind_param("s", $ticket_no);
$stmt3->execute();
$result3 = $stmt3->get_result();
if ($row3 = $result3->fetch_assoc()) {
    $response['serial_number']  = $row3['serial_number'];
    $response['status']         = $row3['status'];
    $response['date_submitted'] = $row3['date_submitted'];
    $response['date_noted']     = $row3['date_noted'];
    $response['date_validated'] = $row3['date_validated'];
    $response['date_printed']   = $row3['date_printed'];
    $response['date_recorded']  = $row3['date_recorded'];
    $response['date_verified']  = $row3['date_verified'];
    $response['date_approved']  = $row3['date_approved'];
    $response['date_completed'] = $row3['date_completed'];
}

header('Content-Type: application/json');
echo json_encode($response);
?>