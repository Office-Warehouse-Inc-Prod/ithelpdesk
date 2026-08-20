<?php
session_start();
include('db.php'); 

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['Response' => false, 'm' => '<div class="alert alert-danger">Error: Unauthorized access. Please log in.</div>']);
    exit;
}

try {
    $ticket_no = trim($_POST['ticket_no'] ?? '');
    $store = trim($_POST['store'] ?? '');
    $deptsel = trim($_POST['deptsel'] ?? '');
    $cat_id = trim($_POST['subject'] ?? '');
    $sub_id = trim($_POST['sub'] ?? '');
    $concern = trim($_POST['concern'] ?? '');
    $userId = $_SESSION['user_id'];
    $status = 'NEW REPORT';

    if (empty($ticket_no) || empty($store) || empty($deptsel) || empty($cat_id) || empty($concern)) {
        echo json_encode(['Response' => false, 'm' => '<div class="alert alert-danger">Error: All fields are required.</div>']);
        exit;
    }

    $stmtCat = $connection->prepare("SELECT cat_desc FROM categories WHERE cat_id = :cat_id LIMIT 1");
    $stmtCat->execute([':cat_id' => $cat_id]);
    $catRow = $stmtCat->fetch(PDO::FETCH_ASSOC);
    $subject_desc = $catRow ? strtoupper($catRow['cat_desc']) : '';

    $connection->beginTransaction();
    $stmt = $connection->prepare("INSERT INTO reports (ticket_no, date_created, deptsel, store, concern, service_desc, status, subject, userId, cat_id, sub_id) 
        VALUES (:ticket_no, :date_created, :deptsel, :store, :concern, :service_desc, :status, :subject, :userId, :cat_id, :sub_id)");

    $result = $stmt->execute([
        ':ticket_no' => $ticket_no,
        ':date_created' => date('Y-m-d H:i:s'),
        ':deptsel' => $deptsel,
        ':store' => $store,
        ':concern' => $concern,
        ':service_desc' => 'GENERAL',
        ':status' => $status,
        ':subject' => $subject_desc,
        ':userId' => $userId,
        ':cat_id' => $cat_id,
        ':sub_id' => !empty($sub_id) ? $sub_id : null
    ]);

    if (!$result) {
        throw new Exception("Failed to insert report header.");
    }

    $counter = 'counter';
    $stmtCounter = $connection->prepare("UPDATE `$counter` SET ticket_no = :ticket_no");
    $stmtCounter->execute([':ticket_no' => $ticket_no]);

    $stmtMsgCnt = $connection->prepare("INSERT INTO reports_msgcnt (ticket_no, msg_cnt) VALUES (:ticket_no, :msgcnt)");
    $stmtMsgCnt->execute([':ticket_no' => $ticket_no, ':msgcnt' => '1']);

    $stmtNewMsg = $connection->prepare("INSERT INTO reports_newmsg (ticket_no, nmsg_stat) VALUES (:ticket_no, :nmsg_stat)");
    $stmtNewMsg->execute([':ticket_no' => $ticket_no, ':nmsg_stat' => '1']);

    $stmtComment = $connection->prepare("INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) 
        VALUES (:ticket_no, :comment_details, :comment_date, :userId)");
    $stmtComment->execute([
        ':ticket_no' => $ticket_no,
        ':comment_details' => $concern,
        ':comment_date' => date('Y-m-d H:i:s'),
        ':userId' => $userId
    ]);

   $stmtTrail = $connection->prepare("INSERT INTO tbl_tickethist (ticket_no, status, date_updated, userID) 
        VALUES (:ticket_no, :status, :date_updated, :userID)");
    $stmtTrail->execute([
        ':ticket_no' => $ticket_no,
        ':status' => $status,
        ':date_updated' => date('Y-m-d H:i:s'),
        ':userID' => $userId
    ]);

   if (isset($_POST['is_fix_asset']) && $_POST['is_fix_asset'] == '1') {
        $asset_ticket   = $ticket_no; 
        $requested_by   = $userId; 
        $requested_db   = $store; 
        $item_code      = NULL; 
        
        $serial_num     = isset($_POST['inline_fa_serial_number']) ? trim($_POST['inline_fa_serial_number']) : '';
        $description    = isset($_POST['inline_fa_description_sel']) ? trim($_POST['inline_fa_description_sel']) : '';
        
        if ($description === 'OTHER' || empty($description)) {
            $description = isset($_POST['inline_fa_description_txt']) ? trim($_POST['inline_fa_description_txt']) : $description;
        }
        
        $ticket_created = date('Y-m-d H:i:s');
        
        $is_technical   = 0; 
        
        $stmt_asset = $connection->prepare("
            INSERT INTO asset_requests (
                ticket_no, requested_by, requested_db, item_code, 
                serial_number, status, description, ticket_created, is_technical
            ) VALUES (
                :ticket_no, :requested_by, :requested_db, :item_code, 
                :serial_num, :status, :description, :ticket_created, :is_technical
            )
        ");
        
        $stmt_asset->execute([
            ':ticket_no'      => $asset_ticket,
            ':requested_by'   => $requested_by,
            ':requested_db'   => $requested_db,
            ':item_code'      => $item_code,
            ':serial_num'     => $serial_num,
            ':status'         => 'NOTED',
            ':description'    => $description,
            ':ticket_created' => $ticket_created,
            ':is_technical'   => $is_technical
        ]);
    }

    $connection->commit();

    $msg = '<div class="alert alert-success col-md-12"><span class="fas fa-check-circle fa-lg"></span> Successfully created report ' . htmlspecialchars($ticket_no) . '.</div>';
    echo json_encode(['Response' => true, 'm' => $msg, 'ticket_no' => $ticket_no]);
    exit;

} catch (Exception $e) {
    if ($connection->inTransaction()) {
        $connection->rollBack();
    }
    echo json_encode(['Response' => false, 'm' => '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>']);
    exit;
}
