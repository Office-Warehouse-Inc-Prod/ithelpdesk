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
        $error = $stmt->errorInfo();
        throw new Exception("Failed to insert report header. Error: " . $error[2]);
    }

    $upload_dir = '../images/'; 

    if (!empty($_FILES['file']['name'][0])) {
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $stmtImage = $connection->prepare("INSERT INTO images (files_tmp, files_name, uploaded_on, ticket_no) 
                                           VALUES (:files_tmp, :files_name, :uploaded_on, :ticket_no)");

        foreach ($_FILES['file']['name'] as $key => $filename) {
            $tmp_name = $_FILES['file']['tmp_name'][$key];
            $error = $_FILES['file']['error'][$key];
            $size = $_FILES['file']['size'][$key];

            if ($error === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $allowed_exts = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'gif'];

                if (in_array($ext, $allowed_exts) && $size <= 2097152) { 
                    $generated_name = uniqid('tkt_' . $ticket_no . '_') . '.' . $ext;
                    $target_file = $upload_dir . $generated_name;

                    if (move_uploaded_file($tmp_name, $target_file)) {
                        
                        $stmtImage->execute([
                            ':files_tmp' => $generated_name, 
                            ':files_name' => $filename,
                            ':uploaded_on' => date('Y-m-d H:i:s'),
                            ':ticket_no' => $ticket_no
                        ]);
                    }
                }
            }
        }
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
?>