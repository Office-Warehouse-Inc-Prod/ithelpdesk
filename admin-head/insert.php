<?php

session_start();
$tchnum = $_SESSION['tech_id'] ?? '';
$userid = $_SESSION['user_id'] ?? '';
date_default_timezone_set("Asia/Manila");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('db.php');
// include('function.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; 
// require '/opt/lampp/htdocs/ithelpdesk/vendor/autoload.php';


if(isset($_POST["chcksbjcls"]))
{
  if($_POST["chcksbjcls"] == "check")
  {
    try {
      $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sqlai = "
      INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId)
      SELECT DISTINCT
        reports.ticket_no,
         ' (3 DAYS NO RESPONSE)  CLOSED BY HELPDESK.AI',
         CURRENT_TIMESTAMP,
         '1'
      FROM
        reports
        LEFT JOIN
        reports_comments
        ON 
          reports.ticket_no = reports_comments.ticket_no
          
      WHERE reports.`status` = 'SUBJECT FOR CLOSING' AND DATE(DATE_ADD(reports.date_closed,INTERVAL +3 DAY)) < CURRENT_DATE
      ";
      $sql = "UPDATE reports
      SET status = 'CLOSED' 
      WHERE `status` = 'SUBJECT FOR CLOSING' AND DATE(DATE_ADD(date_closed,INTERVAL +3 DAY)) < CURRENT_DATE";

      $stmt2 = $connection->prepare($sqlai);
      $stmt1 = $connection->prepare($sql);
    
      $stmt2->execute();
      $stmt1->execute();

    } catch(PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }
}

if(isset($_POST["operation"]))
{
 if($_POST["operation"] == "Add")
 {
    $qry = $connection->prepare(" SELECT ticket_no FROM counter");
    $qry->execute();
    $res = $qry->fetch(PDO::FETCH_ASSOC); 
    $ticknum = $res['ticket_no']+1;

    $statement = $connection->prepare("
     INSERT INTO reports (ticket_no, store, date_created, subject,  via, status, itsup, cat_id, sub_id, date_closed, close_by, remarks, isp_id, date_refNo, deptsel) 
     VALUES (:ticket_no, :store, :date_created, :subject, :via, :status, :itsup, :cat_id, :sub_id, :date_closed, :close_by, :remarks, :isp_id, :date_refNo, :deptsel)
    ");
    $dcval = $_POST["date_created"];
    $dclval = $_POST["date_closed"];
    $datetime = date_create($dcval)->format('Y-m-d H:i:s');
    $datetimecl = date_create($dcval)->format('Y-m-d H:i:s');
    $result = $statement->execute(
     array(
      ':ticket_no' => $ticknum,
      ':store' => $_POST["store"],
      ':date_created' => $datetime,
      ':subject' => strtoupper($_POST["subjct"]),
      ':via' => $_POST["via"],
      ':status' => $_POST["setStatus"],
      ':itsup' => $_POST["itsup"],
      ':cat_id' => $_POST["cat"],
      ':sub_id' => $_POST["sub"],
      ':date_closed' => $datetimecl,
      ':close_by' => $_POST["close_by"],
      ':remarks' => ucfirst($_POST["remarks"]),
      ':isp_id' => '0',
      ':date_refNo' => date('Y-m-d H:i:s',strtotime($_POST["date_refNo"])),
      ':deptsel' => '1' // it dept
     )
    );

   if($_POST['remarks'] != NULL){
    $restat = $connection->prepare("
      INSERT INTO reports_remarks (ticket_no, remarks_detail, remarks_date, itsup) 
     VALUES (:ticket_no, :remarks_detail, :remarks_date, :itsup )
    ");
    $remarkres= $restat->execute(
      array(
        ':ticket_no' =>  $ticknum,
        ':remarks_detail' => $_POST["remarks"],
        ':remarks_date' => date('Y-m-d H:i:s'),
        ':itsup' => $tchnum
      ));
   }

   if($_POST['setStatus'] == "Assigned") {
       $resasgn = $connection->prepare("
       INSERT INTO tbl_notif (ticket_no, store, itsup, notif_data, notif_val, notif_date, assigned_by)
       VALUES (:ticket_no, :store, :itsup, :notif_data, :notif_val, :notif_date, :assigned_by)");
       $assigned= $resasgn->execute(
     array(
      ':ticket_no' =>  $ticknum,
      ':store' => $_POST["store"],
       ':itsup' => $_POST["itsup"],
       ':notif_data' => "New Ticket"." ".$ticknum." ". "Has been assigned.",
       ':notif_val' => '1',
       ':notif_date' => date('Y-m-d H:i:s'),
       ':assigned_by' => $userid
     ));
  } 

   if($_POST['admsg'] != NULL) {
      $resasgn1 = $connection->prepare("
     INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) 
     VALUES (:ticket_no, :comment_details, :comment_date, :userId )
    ");
      $assigned1= $resasgn1->execute(
     array(
      ':ticket_no' =>  $ticknum,
       ':comment_details' => $_POST["admsg"],
       ':comment_date' => date('Y-m-d H:i:s'),
       ':userId' => $userid
     ));
  } 

  $tickhisres = $connection->prepare("
   INSERT INTO tbl_tickethist (ticket_no, date_updated, status, userID) 
   VALUES (:ticket_no, :date_updated, :status, :userID )
  ");
  $tickhisres1= $tickhisres->execute(
    array(
      ':ticket_no' =>  $ticknum,
      ':date_updated' => date('Y-m-d H:i:s'),
      ':status' => $_POST["status"],
      ':userID' => $_POST["u_id"]
    ));

  $msgcntres1 = $connection->prepare("
   INSERT INTO reports_msgcnt (ticket_no, msg_cnt)
   VALUES (:ticket_no, :msg_cnt)
  ");
  $makemsgcnt1= $msgcntres1->execute(
    array(
      ':ticket_no' => $ticknum,
      ':msg_cnt' => '0'
    ));

  if(!empty($result))
  {
    $statement = $connection->prepare(
      "UPDATE counter SET ticket_no = :ticket_no");
    $result = $statement->execute(
      array(
        ':ticket_no' => $ticknum,));
    echo 'Data Inserted.';
  }
 }

 if($_POST["operation"] == "Edit")
 { 
     $optbrval =$_POST["store"];
     $optval =$_POST["itsup"];
     $optcval =$_POST["cat"];
     $optsval =$_POST["sub_num"];
     $opclbval=$_POST["close_by"];
     $tmpval = '0';

  if ( ($optbrval == '0') || ($optval == '0') || ($optcval == '0') || ($optsval == '0') || ($opclbval == '0') ) {
    $brid="";
    $itsup="";
    $cat_id="";
    $sub_id="";
    $clby="";
    $ispid="";
    $data=   array(
      ':ticket_no' => $_POST["ticket_no"],
      ':date_created' => date('Y-m-d H:i:s',strtotime($_POST["date_created"])),
      ':via' => $_POST["via"],
      ':status' => $_POST["status"],
      ':date_closed' => date('Y-m-d H:i:s',strtotime($_POST["date_closed"])),
      ':close_by' => $_POST["close_by"],
      ':remarks' => $_POST["remarks"],
      ':deptsel' => $_POST["deptsel"],
      ':f_deptsel' => $_POST["f_deptsel"],
      ':refNo' => $_POST["refNo"],
      ':date_refNo' => date('Y-m-d H:i:s',strtotime($_POST["date_refNo"]))
     );
  }
  else{
      $brid="store = :store,";
      $itsup = "itsup = :itsup,";
      $cat_id="cat_id = :cat_id,";
      $sub_id="sub_id =:sub_id,";
      $clby="close_by = :close_by,";
      $ispid="isp_id = :isp_id,";

        $data=   array(
      ':ticket_no' => $_POST["ticket_no"],
      ':store' => $_POST["store"],
      ':date_created' => date('Y-m-d H:i:s',strtotime($_POST["date_created"])),
      ':via' => $_POST["via"],
      ':status' => $_POST["status"],
      ':itsup' => $_POST["itsup"],
      ':cat_id' => $_POST["cat"],
      ':sub_id' => $_POST["sub_num"],
      ':isp_id' => $_POST["isp_num"],
      ':deptsel' => $_POST["deptsel"],
      ':f_deptsel' => $_POST["f_deptsel"],
      ':refNo' => $_POST["refNo"],
      ':date_refNo' => date('Y-m-d H:i:s',strtotime($_POST["date_refNo"])),
      ':date_closed' => date('Y-m-d H:i:s',strtotime($_POST["date_closed"])),
      ':close_by' => $_POST["close_by"],
      ':remarks' => $_POST["remarks"]
     ) ;
  }

  $statement = $connection->prepare(
   "UPDATE reports
   SET ticket_no = :ticket_no, $brid date_created = :date_created, via = :via, 
                    status = :status, $itsup $cat_id $sub_id $ispid refNo = :refNo, date_refNo = :date_refNo, date_closed = :date_closed, $clby remarks = :remarks
   WHERE ticket_no = :ticket_no"
  );
  $result = $statement->execute($data);

  $old_dept = trim($_POST['old_dept'] ?? '');
  $new_dept = trim($_POST['f_deptsel'] ?? '');

  if ($old_dept !== '' && $old_dept !== $new_dept) {
      $reasgn = $connection->prepare("
          INSERT INTO tbl_reassigned (ticket_no,  itsup, deptsel, f_deptsel, r_remarks, date_rasigned) 
          VALUES (:ticket_no,  :itsup, :deptsel, :f_deptsel, :r_remarks, :date_rasigned)
      ");
      $reasgn->execute(
        array(
          ':ticket_no'     => $_POST["ticket_no"],
          ':itsup'         => $old_dept,
          ':deptsel'       => $_POST["deptsel"] ?? '0',
          ':f_deptsel'     => $new_dept,
          ':r_remarks'     => $_POST["remarks"],
          ':date_rasigned' => date('Y-m-d H:i:s')
      ));
  }

  $msgcntres = $connection->prepare("
   UPDATE reports_msgcnt
   SET msg_cnt = :msg_cnt
  WHERE ticket_no = :ticket_no
  ");
  $makemsgcnt= $msgcntres->execute(
    array(
      ':ticket_no' => $_POST["ticket_no"],
      ':msg_cnt' => '0'
    ));

  if(!empty($result))
  {
     $restat = $connection->prepare("
    INSERT INTO reports_remarks (ticket_no, remarks_detail, remarks_date, itsup) 
   VALUES (:ticket_no, :remarks_detail, :remarks_date, :itsup )
  ");
  $remarkres1= $restat->execute(
    array(
     ':ticket_no' => $_POST["ticket_no"],
      ':remarks_detail' => $_POST["remarks"],
      ':remarks_date' => date('Y-m-d H:i:s'),
      ':itsup' => $tchnum
    ));
  }

 if(!empty($result)) {
      $resasgn = $connection->prepare("
      INSERT INTO tbl_notif (ticket_no, store, itsup, notif_data, notif_val, notif_date, assigned_by)
      VALUES (:ticket_no, :store, :itsup, :notif_data, :notif_val, :notif_date, :assigned_by)");
      $assigned= $resasgn->execute(
    array(
     ':ticket_no' => $_POST["ticket_no"],
     ':store' => $_POST["store"],
      ':itsup' => $_POST["itsup"],
      ':notif_data' => "New Ticket"." ".$_POST["ticket_no"]." ". "Has been assigned.",
      ':notif_val' => '1',
      ':notif_date' => date('Y-m-d H:i:s'),
      ':assigned_by' => $userid
    ));
 }
     echo 'Data has been updated'; 
}

if ($_POST["operation"] == "New_Report") {
    $ticket_no = trim($_POST["ticket_no"] ?? '');
    if ($ticket_no === '') {
        http_response_code(400);
        exit("Missing ticket_no");
    }

    $store       = trim($_POST["store"] ?? '0');
    $dept        = trim($_POST["f_deptsel"] ?? '0');
    $concern     = trim($_POST["concern"] ?? '');
    $subject     = trim($_POST["subject"] ?? '');
    $via         = 'PENDING';
    $cat         = '31';
    $sub         = '199';
    $close_by    = trim($_POST["close_by"] ?? '0');
    $remarks     = trim($_POST["remarks"] ?? '');
    $status      = trim($_POST["setStatus"] ?? '');
    $refNo       = trim($_POST["refNo"] ?? '');
    $plvl        = trim($_POST["priority_level"] ?? '0');
    $sla_days    = trim($_POST["sla_days"] ?? '0');

    $date_created = !empty($_POST["date_createdx"])
        ? date('Y-m-d H:i:s', strtotime($_POST["date_createdx"]))
        : date('Y-m-d H:i:s');

    $date_closed = !empty($_POST["date_closed"])
        ? date('Y-m-d H:i:s', strtotime($_POST["date_closed"]))
        : null;

    $date_refNo = !empty($_POST["date_refNo"])
        ? date('Y-m-d H:i:s', strtotime($_POST["date_refNo"]))
        : null;

    $contactNumber = '';
    if ($dept !== '0' && $dept !== '') {
        $stmtCN = $connection->prepare("
            SELECT contactNumber
            FROM tbl_dept
            WHERE dept_id = :dept
            LIMIT 1
        ");
        $stmtCN->execute([':dept' => $dept]);
        $rowCN = $stmtCN->fetch(PDO::FETCH_ASSOC);
        $contactNumber = $rowCN['contactNumber'] ?? '';
    }

    $fields = [
        "date_created = :date_created",
        "via = :via",
        "status = :status",
        "refNo = :refNo",
        "remarks = :remarks"
    ];

    $data = [
        ':date_created' => $date_created,
        ':via'          => $via,
        ':status'       => $status,
        ':refNo'        => $refNo,
        ':remarks'      => $remarks
    ];

    if ($subject !== '') {
        $fields[] = "subject = :subject";
        $data[':subject'] = $subject;
    }

    if ($concern !== '') {
        $fields[] = "concern = :concern";
        $data[':concern'] = $concern;
    }

    if ($date_refNo !== null) {
        $fields[] = "date_refNo = :date_refNo";
        $data[':date_refNo'] = $date_refNo;
    }

    if ($date_closed !== null) {
        $fields[] = "date_closed = :date_closed";
        $data[':date_closed'] = $date_closed;
    }

    if ($store != '0' && $store != '') {
        $fields[] = "store = :store";
        $data[':store'] = $store;
    }

    if ($dept != '0' && $dept != '') {
        $fields[] = "f_deptsel = :f_deptsel";
        $data[':f_deptsel'] = $dept;
    }

    if ($cat != '0' && $cat != '') {
        $fields[] = "cat_id = :cat_id";
        $data[':cat_id'] = $cat;
    }

    if ($sub != '0' && $sub != '') {
        $fields[] = "sub_id = :sub_id";
        $data[':sub_id'] = $sub;
    }

    if ($close_by != '0' && $close_by != '') {
        $fields[] = "close_by = :close_by";
        $data[':close_by'] = $close_by;
    }

    if ($plvl != '0' && $plvl != '') {
        $fields[] = "priority_level = :priority_level";
        $data[':priority_level'] = $plvl;
    }

    if ($sla_days != '0' && $sla_days != '') {
        $fields[] = "sla_days = :sla_days";
        $data[':sla_days'] = $sla_days;
    }

    if ($contactNumber !== '') {
        $fields[] = "contactNumber = :contactNumber";
        $data[':contactNumber'] = $contactNumber;
    }

    $fields[] = "isp_id = '0'";
    $fields[] = "is_transfer = '0'";

    $sql = "UPDATE reports SET " . implode(", ", $fields) . " WHERE ticket_no = :where_ticket_no";
    $data[':where_ticket_no'] = $ticket_no;

    $statement = $connection->prepare($sql);
    $result = $statement->execute($data);
   
    $old_dept = trim($_POST['old_dept'] ?? '');
    if ($old_dept !== '' && $old_dept !== $dept) {
        $reasgn = $connection->prepare("
            INSERT INTO tbl_reassigned (ticket_no,  itsup, deptsel, f_deptsel, r_remarks, date_rasigned) 
            VALUES (:ticket_no, :itsup, :deptsel, :f_deptsel, :r_remarks, :date_rasigned)
        ");

        $reasgn->execute(array(
            ':ticket_no'     => $ticket_no,
            ':itsup'         => $old_dept,    
            ':deptsel'       => $_POST["deptsel"] ?? '0',
            ':f_deptsel'     => $dept,        
            ':r_remarks'     => $remarks,
            ':date_rasigned' => date('Y-m-d H:i:s')
        ));
    }

    if ($result) {
        $restat = $connection->prepare("
            INSERT INTO reports_remarks
            (ticket_no, remarks_detail, remarks_date, f_deptsel)
            VALUES (:ticket_no, :remarks, NOW(), :dept)
        ");

        $restat->execute([
            ':ticket_no' => $ticket_no,
            ':remarks'   => $remarks,
            ':dept'      => $dept
        ]);
    }

    $msgcntres = $connection->prepare("
        UPDATE reports_msgcnt
        SET msg_cnt = 0
        WHERE ticket_no = :ticket_no
    ");
    $msgcntres->execute([':ticket_no' => $ticket_no]);

    if ($result) {
        $notif = $connection->prepare("
            INSERT INTO tbl_notif
            (ticket_no, store, f_deptsel, notif_data, notif_val, notif_date, assigned_by)
            VALUES (:ticket_no, :store, :dept, :msg, '1', NOW(), :assigned_by)
        ");

        $notif->execute([
            ':ticket_no'    => $ticket_no,
            ':store'        => $store,
            ':dept'         => $dept,
            ':msg'          => "Ticket $ticket_no has been assigned to a department.",
            ':assigned_by'  => $userid
        ]);
    }
    
    if (!empty($_POST["admsg"])) {
        $makecom = $connection->prepare("
            INSERT INTO reports_comments
            (ticket_no, comment_details, comment_date, userId)
            VALUES (:ticket_no, :comment, NOW(), :uid)
        ");

        $makecom->execute([
            ':ticket_no' => $ticket_no,
            ':comment'   => $_POST["admsg"],
            ':uid'       => $_POST["u_id"]
        ]);

        $connection->prepare("
            UPDATE reports_newmsg
            SET nmsg_stat = '2'
            WHERE ticket_no = :ticket_no
        ")->execute([':ticket_no' => $ticket_no]);

        $connection->prepare("
            INSERT INTO tbl_tickethist
            (ticket_no, date_updated, status, userID)
            VALUES (:ticket_no, NOW(), :status, :uid)
        ")->execute([
            ':ticket_no' => $ticket_no,
            ':status'    => $status,
            ':uid'       => $_POST["u_id"]
        ]);
    }
    
    if ($_POST['setStatus'] == "Assigned") {
        $resasgn = $connection->prepare("INSERT INTO tbl_notif (ticket_no, store, itsup, notif_data, notif_val, notif_date, assigned_by) VALUES (:ticket_no, :store, :itsup, :notif_data, :notif_val, :notif_date, :assigned_by)");
        $resasgn->execute([
            ':ticket_no'   => $ticket_no,
            ':store'       => $store,
            ':itsup'       => $_POST["itsup"] ?? '0',
            ':notif_data'  => "New Ticket $ticket_no Has been assigned.",
            ':notif_val'   => '1',
            ':notif_date'  => date('Y-m-d H:i:s'),
            ':assigned_by' => $userid
        ]);
    }

    if ($result) {
        try {
            $deptEmail = '';
            $deptName  = '';
            $storeName = '';

            $stmtDept = $connection->prepare("
                SELECT dept_id, dept_desc, dept_shrtdesc, str_num, contactNumber, dept_email
                FROM tbl_dept
                WHERE dept_id = :dept
                LIMIT 1
            ");
            $stmtDept->execute([':dept' => $dept]);
            $rowDept = $stmtDept->fetch(PDO::FETCH_ASSOC);

            if ($rowDept) {
                $deptName  = $rowDept['dept_desc'] ?? '';
                $deptEmail = trim($rowDept['dept_email'] ?? '');
            }

            $stmtStore = $connection->prepare("
                SELECT str_name
                FROM tbl_branch
                WHERE str_num = :store
                LIMIT 1
            ");
            $stmtStore->execute([':store' => $store]);
            $rowStore = $stmtStore->fetch(PDO::FETCH_ASSOC);

            if ($rowStore) {
                $storeName = $rowStore['str_name'] ?? '';
            }

            if (empty($deptEmail)) {
                throw new Exception("No department email found for department ID: " . $dept);
            }

            $priorityLabel = '';
            switch ($plvl) {
                case '4': $priorityLabel = 'Low'; break;
                case '3': $priorityLabel = 'Normal'; break;
                case '2': $priorityLabel = 'High'; break;
                case '1': $priorityLabel = 'Critical'; break;
                default:  $priorityLabel = 'Not Set'; break;
            }

            $slaLabel = '';
            switch ((string)$sla_days) {
                case '2':  $slaLabel = '24 - 48 hours'; break;
                case '5':  $slaLabel = '3 - 5 days'; break;
                case '7':  $slaLabel = '5 - 7 days'; break;
                case '14': $slaLabel = '1 - 2 weeks'; break;
                case '21': $slaLabel = '2 - 3 weeks'; break;
                case '28': $slaLabel = '3 - 4 weeks'; break;
                default:   $slaLabel = ($sla_days && $sla_days != '0') ? $sla_days . ' day(s)' : 'Not Set'; break;
            }

            $dueDate = '';
            if (!empty($sla_days) && $sla_days != '0') {
                $dueDate = date('Y-m-d H:i:s', strtotime($date_created . " +{$sla_days} days"));
            }

            $mail = new PHPMailer(true);
            
            // Bypass strict SSL validation (common corporate network issue)
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->isSMTP();
            $mail->Host       = 'mail.officewarehouse.com.ph';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'helpdesk_noreply@officewarehouse.com.ph';
            $mail->Password   = 'Owi@123456**';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('helpdesk_noreply@officewarehouse.com.ph', 'HELPDESK AI');
            $mail->addAddress($deptEmail, $deptName);

            $mail->isHTML(true);
            $mail->Subject = "Ticket {$ticket_no} Assigned to {$deptName}";

            $mailBody = '
            <html>
            <body style="margin:0;padding:20px;background:#f4f6f9;font-family:Arial,sans-serif;">
                <table width="700" align="center" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #d6def7;border-radius:8px;overflow:hidden;">
                    <tr>
                        <td style="background:#627bc5;color:#ffffff;padding:18px 24px;font-size:20px;font-weight:bold;">
                            Helpdesk AI Ticket Assignment
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;font-size:14px;color:#333;">
                            <p>Good day,</p>
                            <p>A ticket has been assigned to your department for review and action.</p>
                            <table cellpadding="8" cellspacing="0" width="100%" style="border-collapse:collapse;margin-top:10px;">
                                <tr style="background:#f3f6ff;">
                                    <td style="border:1px solid #d6def7;width:180px;"><strong>Ticket No.</strong></td>
                                    <td style="border:1px solid #d6def7;">' . htmlspecialchars($ticket_no) . '</td>
                                </tr>
                                <tr>
                                    <td style="border:1px solid #d6def7;"><strong>Store</strong></td>
                                    <td style="border:1px solid #d6def7;">' . htmlspecialchars($storeName) . '</td>
                                </tr>
                                <tr style="background:#f3f6ff;">
                                    <td style="border:1px solid #d6def7;"><strong>Department</strong></td>
                                    <td style="border:1px solid #d6def7;">' . htmlspecialchars($deptName) . '</td>
                                </tr>
                                <tr>
                                    <td style="border:1px solid #d6def7;"><strong>Status</strong></td>
                                    <td style="border:1px solid #d6def7;">' . htmlspecialchars($status) . '</td>
                                </tr>
                                <tr style="background:#f3f6ff;">
                                    <td style="border:1px solid #d6def7;"><strong>Priority</strong></td>
                                    <td style="border:1px solid #d6def7;">' . htmlspecialchars($priorityLabel) . '</td>
                                </tr>
                                <tr>
                                    <td style="border:1px solid #d6def7;"><strong>SLA</strong></td>
                                    <td style="border:1px solid #d6def7;">' . htmlspecialchars($slaLabel) . '</td>
                                </tr>
                                <tr style="background:#f3f6ff;">
                                    <td style="border:1px solid #d6def7;"><strong>SLA Max Days</strong></td>
                                    <td style="border:1px solid #d6def7;">' . htmlspecialchars($sla_days) . '</td>
                                </tr>
                                <tr>
                                    <td style="border:1px solid #d6def7;"><strong>Due Date</strong></td>
                                    <td style="border:1px solid #d6def7;">' . htmlspecialchars($dueDate) . '</td>
                                </tr>
                                <tr style="background:#f3f6ff;">
                                    <td style="border:1px solid #d6def7;"><strong>Subject</strong></td>
                                    <td style="border:1px solid #d6def7;">' . htmlspecialchars($subject) . '</td>
                                </tr>
                                <tr>
                                    <td style="border:1px solid #d6def7;"><strong>Concern</strong></td>
                                    <td style="border:1px solid #d6def7;">' . nl2br(htmlspecialchars($concern)) . '</td>
                                </tr>
                                <tr style="background:#f3f6ff;">
                                    <td style="border:1px solid #d6def7;"><strong>Date Created</strong></td>
                                    <td style="border:1px solid #d6def7;">' . htmlspecialchars($date_created) . '</td>
                                </tr>
                            </table>
                            <p style="margin-top:20px;">Please log in to the <strong>OWI Helpdesk</strong> for complete details and necessary action.</p>
                            <div style="text-align:center;margin-top:25px;">
                                <a href="https://owihelpdesk.officewarehouse.com.ph" 
                                   style="background:#627bc5;
                                          color:#ffffff;
                                          padding:12px 24px;
                                          text-decoration:none;
                                          border-radius:5px;
                                          display:inline-block;
                                          font-weight:bold;">
                                    Open OWI Helpdesk
                                </a>
                            </div>
                            <p style="margin-top:20px;">Thank you.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#8bacf6;color:#ffffff;text-align:center;padding:10px;font-size:12px;">
                            OWI Helpdesk System Notification
                        </td>
                    </tr>
                </table>
            </body>
            </html>
            ';

            $mail->Body    = $mailBody;
            $mail->AltBody = "Ticket {$ticket_no} has been assigned to {$deptName}. ";

            $mail->send();
            echo "Report updated successfully and email sent to {$deptEmail}.";

        } catch (Exception $e) {
            echo "Report updated successfully, but email failed: " . $e->getMessage();
        }
    } else {
        echo "Failed to update report.";
    }
}

// new save and reply for admin module
if ($_POST["operation"] == "Save and Reply") {
    $ticket_no = trim($_POST["ticket_no"] ?? '');
    if ($ticket_no === '') {
        exit("Ticket number is required.");
    }

    $store     = $_POST["store"] ?? '0';
    $close_by  = $_POST["close_by"] ?? '0';
    $remarks   = $_POST["remarks"] ?? '';
    $status    = $_POST["status"] ?? '';
    $plvl      = $_POST["priority_level"] ?? ($_POST["prioty_level"] ?? '0');
    $dept      = $_POST["f_deptsel"] ?? ($_POST["dept_desc"] ?? '0');
    $userid    = $_POST["u_id"] ?? '0';

    $date_created = !empty($_POST["date_createdx"])
        ? date('Y-m-d H:i:s', strtotime($_POST["date_createdx"]))
        : date('Y-m-d H:i:s');

    $date_closed = !empty($_POST["date_closed"])
        ? date('Y-m-d H:i:s', strtotime($_POST["date_closed"]))
        : null;

    $fields = [];
    $data   = [':ticket_no' => $ticket_no];

    if ($store !== '0' && $store !== '') {
        $fields[] = "store = :store";
        $data[':store'] = $store;
    }

    if ($status !== '') {
        $fields[] = "status = :status";
        $data[':status'] = $status;
    }

    if ($remarks !== '') {
        $fields[] = "remarks = :remarks";
        $data[':remarks'] = $remarks;
    }

    if ($plvl !== '0' && $plvl !== '') {
        $fields[] = "priority_level = :priority_level";
        $data[':priority_level'] = $plvl;
    }

    if ($close_by !== '0' && $close_by !== '') {
        $fields[] = "close_by = :close_by";
        $data[':close_by'] = $close_by;
    }

    if ($dept !== '0' && $dept !== '') {
        $fields[] = "f_deptsel = :dept";
        $data[':dept'] = $dept;
    }

    $fields[] = "date_closed = :date_closed";
    $data[':date_closed'] = $date_closed;

    if (empty($fields)) {
        exit("No fields to update.");
    }

    $sql = "UPDATE reports 
            SET " . implode(", ", $fields) . "
            WHERE ticket_no = :ticket_no";

    $stmt = $connection->prepare($sql);
    $result = $stmt->execute($data);

    if ($result) {
        if ($stmt->rowCount() > 0) {
            echo "Ticket updated successfully.";
        } else {
            echo "No changes detected.";
        }

        if (!empty($remarks)) {
            $restat = $connection->prepare("
                INSERT INTO reports_remarks
                (ticket_no, remarks_detail, remarks_date, deptsel)
                VALUES (:ticket_no, :remarks, NOW(), :dept)
            ");
            $restat->execute([
                ':ticket_no' => $ticket_no,
                ':remarks'   => $remarks,
                ':dept'      => $dept
            ]);
        }

        $msgcntres = $connection->prepare("
            UPDATE reports_msgcnt
            SET msg_cnt = 0
            WHERE ticket_no = :ticket_no
        ");
        $msgcntres->execute([':ticket_no' => $ticket_no]);

        $notif = $connection->prepare("
            INSERT INTO tbl_notif
            (ticket_no, store, f_deptsel, notif_data, notif_val, notif_date, assigned_by)
            VALUES (:ticket_no, :store, :dept, :msg, '1', NOW(), :assigned_by)
        ");
        $notif->execute([
            ':ticket_no'   => $ticket_no,
            ':store'       => $store,
            ':dept'        => $dept,
            ':msg'         => "Ticket $ticket_no has been assigned to a department.",
            ':assigned_by' => $userid
        ]);

        if (!empty($_POST["admsg"])) {
            $makecom = $connection->prepare("
                INSERT INTO reports_comments
                (ticket_no, comment_details, comment_date, userId)
                VALUES (:ticket_no, :comment, NOW(), :uid)
            ");
            $makecom->execute([
                ':ticket_no' => $ticket_no,
                ':comment'   => $_POST["admsg"],
                ':uid'       => $userid
            ]);

            $connection->prepare("
                UPDATE reports_newmsg
                SET nmsg_stat = '2'
                WHERE ticket_no = :ticket_no
            ")->execute([':ticket_no' => $ticket_no]);

            $connection->prepare("
                INSERT INTO tbl_tickethist
                (ticket_no, date_updated, status, userID)
                VALUES (:ticket_no, NOW(), :status, :uid)
            ")->execute([
                ':ticket_no' => $ticket_no,
                ':status'    => $status,
                ':uid'       => $userid
            ]);
        }
    } else {
        echo "Update failed.";
    }
}

// -------------------------------------------------------------------------
// THIS IS THE REFINED update_request BLOCK (val = 2)
// -------------------------------------------------------------------------
if (isset($_POST["operation"]) && $_POST["operation"] === "update_request") {
    
    header('Content-Type: application/json');
    
    if (empty($_POST['ticket_no'])) {
        echo json_encode(["status" => "error", "message" => "Missing Ticket Number."]);
        exit();
    }

    $ticketNo = $_POST['ticket_no'];

    try {
        $connection->beginTransaction();
        
        $currentDate = date('Y-m-d H:i:s');
        $techId = $_POST['tech_id'] ?? $_SESSION['tech_id'] ?? '';
        $storeNum = $_SESSION["str_num"] ?? '';

        $fileNameToSave = '';
        if (isset($_FILES['files']['name'][0]) && !empty($_FILES['files']['name'][0]) && $_FILES['files']['error'][0] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/image/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $tmpName = $_FILES['files']['tmp_name'][0];
            if (is_uploaded_file($tmpName)) {
                $originalName = basename($_FILES['files']['name'][0]);
                $uniqueName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
                $dest = $uploadDir . $uniqueName;
                
                if (move_uploaded_file($tmpName, $dest)) {
                    $fileNameToSave = 'image/' . $uniqueName;
                }
            }
        }

        $standardRemark = "The Fixed Asset Request has been successfully validated and verified by the Admin Department. The request is now in the printing stage and is awaiting the approval of the General Manager, Ma'am Althea Bunachita, before proceeding to the next step of the asset request process.";
        
        $stmtUpdate = $connection->prepare("
            UPDATE asset_requests
            SET
                serial_number = :serial_number,
                date_received = :date_received,
                date_verified = :date_verified,
                approve_method_head = :approve_method_head,
                status = :status
            WHERE ticket_no = :ticket_no
        ");

        $updateSuccess = $stmtUpdate->execute([
            ':serial_number'       => $_POST['serial_number'] ?? '',
            ':date_received'       => $_POST['date_received'] ?? '',
            ':date_verified'       => $currentDate,
            ':status'              => 'VERIFIED',
            ':approve_method_head' => $fileNameToSave,
            ':ticket_no'           => $ticketNo
        ]);

        if ($stmtUpdate->rowCount() === 0) {
            $connection->rollBack();
            echo json_encode(["status" => "error", "message" => "Update failed: Ticket {$ticketNo} does not exist."]);
            exit();
        }

        $stmtNotif = $connection->prepare("
            INSERT INTO tbl_notif (ticket_no, store, itsup, notif_data, notif_val, notif_date) 
            VALUES (:ticket_no, :store, :itsup, :notif_data, :notif_val, :notif_date)
        ");
        $notifSuccess = $stmtNotif->execute([
            ':ticket_no'  => $ticketNo,
            ':store'      => $storeNum,
            ':itsup'      => $techId,
            ':notif_data' => "Fixed asset {$ticketNo} has been Verified by Ma'am Alma Villanueva and waiting for approval",
            ':notif_val'  => '8',
            ':notif_date' => $currentDate
        ]);

        $stmtRemarks = $connection->prepare("
            INSERT INTO reports_remarks (ticket_no, remarks_detail, remarks_date, itsup) 
            VALUES (:ticket_no, :remarks_detail, :remarks_date, 78)
        ");
        $stmtRemarks->execute([
            ':ticket_no'      => $ticketNo,
            ':remarks_detail' => $standardRemark,
            ':remarks_date'   => $currentDate
        ]);

        $stmtComments = $connection->prepare("
            INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) 
            VALUES (:ticket_no, :comment_details, :comment_date, :userId)
        ");
        $stmtComments->execute([
            ':ticket_no'       => $ticketNo,
            ':comment_details' => $standardRemark,
            ':comment_date'    => $currentDate,
            ':userId'          => '414'
        ]);

        if ($updateSuccess && $notifSuccess) {
            $connection->commit(); 
            
            $email_status_msg = "";
            
            try {
                $stmtEmail = $connection->prepare("SELECT * FROM fixed_asset_email WHERE val = '2' LIMIT 1");
                $stmtEmail->execute();
                $emailRow = $stmtEmail->fetch(PDO::FETCH_ASSOC);
                
                if ($emailRow && !empty($emailRow['email'])) {
                    $receiverEmail = trim($emailRow['email']);
                    
                    if (filter_var($receiverEmail, FILTER_VALIDATE_EMAIL)) {
                        
                        $stmtDetails = $connection->prepare("
                            SELECT 
                                ar.ticket_no, b.str_name, CONCAT(u.fname, ' ', u.lstname) AS full_name, 
                                ar.ticket_created, ar.item_code, ar.description, ar.serial_number, 
                                ar.purpose_of_request, ar.technical_workoutput, it.it_desc,
                                ar.date_received, fat.problem_reported, fat.verification_findings,
                                fat.work_done, fat.status_workoutput, fat.recommendation, 
                                r.is_technical, ar.status
                            FROM asset_requests ar
                            LEFT JOIN fixed_asset_techoutput fat ON ar.ticket_no = fat.ticket_no
                            LEFT JOIN it_tech it ON ar.item_received_by = it.itsup
                            LEFT JOIN reports r ON ar.ticket_no = r.ticket_no
                            LEFT JOIN users u ON r.userId = u.id
                            LEFT JOIN tbl_branch b ON r.store = b.str_num 
                            WHERE ar.ticket_no = :ticket_no LIMIT 1
                        ");
                        $stmtDetails->execute([':ticket_no' => $ticketNo]);
                        $ticketData = $stmtDetails->fetch(PDO::FETCH_ASSOC);

                        if ($ticketData) {
                            $display_dept     = $ticketData['str_name'] ?? 'N/A';
                            $display_user     = $ticketData['full_name'] ?? 'N/A';
                            $display_receiver = $ticketData['it_desc'] ?? 'N/A';
                            $display_date_rec = $ticketData['date_received'] ?? 'N/A';
                            $display_item     = $ticketData['item_code'] ?? 'N/A';
                            $display_desc     = $ticketData['description'] ?? 'N/A';
                            $display_serial   = $ticketData['serial_number'] ?? 'N/A';
                            $display_purpose  = $ticketData['purpose_of_request'] ?? 'N/A';
                            $display_tech_out = $ticketData['technical_workoutput'] ?? 'N/A';
                            $display_created  = $ticketData['ticket_created'] ?? 'N/A';

                            $mail = new PHPMailer(true);
                            
                          
                            $mail->SMTPOptions = array(
                                'ssl' => array(
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true
                                )
                            );

                            $mail->isSMTP();
                            $mail->Host       = 'mail.officewarehouse.com.ph';
                            $mail->SMTPAuth   = true;
                            $mail->Username   = 'helpdesk_noreply@officewarehouse.com.ph';
                            $mail->Password   = 'Owi@123456**';
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port       = 587;
                            
                            $mail->setFrom('helpdesk_noreply@officewarehouse.com.ph', 'HELPDESK AI');
                            $mail->addAddress($receiverEmail);
                            $mail->isHTML(true);
                            
                            $mail->Subject = "Asset Request Verified: {$ticketNo}";
                            
                            $techWorkOutputHtml = '';
                            if (isset($ticketData['is_technical']) && $ticketData['is_technical'] == 1) {
                                $techWorkOutputHtml = '
                                <h1><strong>Technical Work Output</strong></h1>
                                <table cellpadding="8" cellspacing="0" width="100%" style="border-collapse:collapse;margin-top:10px;">
                                    <tr>
                                        <td style="border:1px solid #cabb89;width:200px;"><strong>Problem Reported</strong></td>
                                        <td style="border:1px solid #cabb89;">' . nl2br(htmlspecialchars($_POST['problem_reported'] ?? $ticketData['problem_reported'] ?? '')) . '</td>
                                    </tr>
                                    <tr>
                                        <td style="border:1px solid #cabb89;"><strong>Verification/Findings</strong></td>
                                        <td style="border:1px solid #cabb89;">' . nl2br(htmlspecialchars($_POST['verification_findings'] ?? $ticketData['verification_findings'] ?? '')) . '</td>
                                    </tr>
                                    <tr>
                                        <td style="border:1px solid #cabb89;"><strong>Work Done/Technical Solutions Provided</strong></td>
                                        <td style="border:1px solid #cabb89;">' . nl2br(htmlspecialchars($_POST['work_done'] ?? $ticketData['work_done'] ?? '')) . '</td>
                                    </tr>
                                    <tr>
                                        <td style="border:1px solid #cabb89;"><strong>Status/Work Output</strong></td>
                                        <td style="border:1px solid #cabb89;">' . htmlspecialchars($_POST['status_workoutput'] ?? $ticketData['status_workoutput'] ?? '') . '</td>
                                    </tr>
                                    <tr>
                                        <td style="border:1px solid #cabb89;"><strong>Recommendations/Suggestions</strong></td>
                                        <td style="border:1px solid #cabb89;">' . nl2br(htmlspecialchars($_POST['recommendation'] ?? $ticketData['recommendation'] ?? '')) . '</td>
                                    </tr>       
                                </table>';
                            }

                            $mailBody = '
                            <html>
                            <body style="margin:0;padding:20px;background: #f4f6f9;font-family:Arial,sans-serif;">
                                <table width="700" align="center" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #cabb89;border-radius:8px;overflow:hidden;">
                                    <tr>
                                        <td style="background: #E1AD01;color:#ffffff;padding:18px 24px;font-size:20px;font-weight:bold;">
                                            Helpdesk AI: Asset Request Verified
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:24px;font-size:14px;color:#333;">
                                            <p>Good day,</p>
                                            <p>A fixed asset request has been verified and is ready for printing. Please see the details below:</p>
                                            
                                            <table cellpadding="8" cellspacing="0" width="100%" style="border-collapse:collapse;margin-top:10px;">
                                                <tr style="background:#f3e8c3;">
                                                    <td style="border:1px solid #cabb89;width:200px;"><strong>Ticket No.</strong></td>
                                                    <td style="border:1px solid #cabb89;">' . htmlspecialchars($ticketNo) . '</td>
                                                </tr>
                                                <tr>
                                                    <td style="border:1px solid #cabb89;"><strong>Status</strong></td>
                                                    <td style="border:1px solid #cabb89;">VERIFIED</td>
                                                </tr>
                                                <tr style="background:#f3e8c3;">
                                                    <td style="border:1px solid #cabb89;"><strong>Requesting Dept</strong></td>
                                                    <td style="border:1px solid #cabb89;">' . htmlspecialchars($display_dept) . '</td>
                                                </tr>
                                                <tr>
                                                    <td style="border:1px solid #cabb89;"><strong>Requested By</strong></td>
                                                    <td style="border:1px solid #cabb89;">' . htmlspecialchars($display_user) . '</td>
                                                </tr>
                                                <tr style="background:#f3e8c3;">
                                                    <td style="border:1px solid #cabb89;"><strong>Item Received By</strong></td>
                                                    <td style="border:1px solid #cabb89;">' . htmlspecialchars($display_receiver) . '</td>
                                                </tr>
                                                <tr>
                                                    <td style="border:1px solid #cabb89;"><strong>Date Received</strong></td>
                                                    <td style="border:1px solid #cabb89;">' . htmlspecialchars($display_date_rec) . '</td>
                                                </tr>
                                                <tr style="background:#f3e8c3;">
                                                    <td style="border:1px solid #cabb89;"><strong>Item Code</strong></td>
                                                    <td style="border:1px solid #cabb89;">' . htmlspecialchars($display_item) . '</td>
                                                </tr>
                                                <tr>
                                                    <td style="border:1px solid #cabb89;"><strong>Description</strong></td>
                                                    <td style="border:1px solid #cabb89;">' . htmlspecialchars($display_desc) . '</td>
                                                </tr>
                                                <tr style="background:#f3e8c3;">
                                                    <td style="border:1px solid #cabb89;"><strong>Serial Number</strong></td>
                                                    <td style="border:1px solid #cabb89;">' . htmlspecialchars($display_serial) . '</td>
                                                </tr>
                                                <tr>
                                                    <td style="border:1px solid #cabb89;"><strong>Purpose of Request</strong></td>
                                                    <td style="border:1px solid #cabb89;">' . nl2br(htmlspecialchars($display_purpose)) . '</td>
                                                </tr>
                                                <tr style="background:#f3e8c3;">
                                                    <td style="border:1px solid #cabb89;"><strong>Technical Workoutput</strong></td>
                                                    <td style="border:1px solid #cabb89;">' . nl2br(htmlspecialchars($display_tech_out)) . '</td>
                                                </tr>
                                                <tr>
                                                    <td style="border:1px solid #cabb89;"><strong>Date Created</strong></td>
                                                    <td style="border:1px solid #cabb89;">' . htmlspecialchars($display_created) . '</td>
                                                </tr>
                                            </table>

                                            ' . $techWorkOutputHtml . '
                                            
                                            <p style="margin-top:20px;">Please log in to the <strong>OWI Helpdesk</strong> to review this asset request.</p>
                                            <div style="text-align:center;margin-top:25px;">
                                                <a href="https://owihelpdesk.officewarehouse.com.ph" 
                                                   style="background:#627bc5;color:#ffffff;padding:12px 24px;text-decoration:none;border-radius:5px;display:inline-block;font-weight:bold;">
                                                    Open OWI Helpdesk
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background: #E1AD01;color:#ffffff;text-align:center;padding:10px;font-size:12px;">
                                            OWI Helpdesk System Notification
                                        </td>
                                    </tr>
                                </table>
                            </body>
                            </html>';

                            $mail->Body = $mailBody;
                            $mail->AltBody = "Asset Request Verified: {$ticketNo}. Requested By: {$display_user} ({$display_dept}). Item Received By: {$display_receiver}.";

                            $mail->send();
                            $email_status_msg = " Email successfully sent to {$receiverEmail}.";
                        }
                    } else {
                        $email_status_msg = " (Email failed: The database contains an invalid email format for val 2).";
                    }
                } else {
                     $email_status_msg = " (Email skipped: No email record found in fixed_asset_email where val = 2).";
                }
            } catch (Exception $emailEx) {
               
                $email_status_msg = " (Email failed: " . $emailEx->getMessage() . ")";
                error_log("Email sending failed for Ticket {$ticketNo}: " . $emailEx->getMessage());
            }

            echo json_encode(["status" => "success", "message" => "Fixed Asset Request Verified successfully." . $email_status_msg]);
        } else {
            $connection->rollBack();
            echo json_encode(["status" => "error", "message" => "SQL Error: Execution failed during database update."]);
        }
    } catch (PDOException $e) {
        if ($connection->inTransaction()) {
            $connection->rollBack();
        }
        error_log("Database Error (update_request): " . $e->getMessage());
        echo json_encode(["status" => "error", "message" => "Database error occurred. Please contact IT support."]);
    }
    
    exit();
}

if (isset($_POST["operation"]) && $_POST["operation"] === "add_remarks_only") {
    
    header('Content-Type: application/json');
    
    if (empty($_POST['ticket_no'])) {
        echo json_encode(["status" => "error", "message" => "Missing Ticket Number."]);
        exit();
    }
    if (empty($_POST['remarks_adtech'])) {
        echo json_encode(["status" => "error", "message" => "Remarks details cannot be empty."]);
        exit();
    }

    try {
        $connection->beginTransaction();

        $ticketNo = $_POST['ticket_no'];
        $currentDate = date('Y-m-d H:i:s');
        $techId = $_SESSION['tech_id'] ?? $_POST['tech_id'] ?? 'Unknown Tech';
        $remarksNote = trim($_POST['remarks_adtech']);

        $statementRemarks = $connection->prepare("
            INSERT INTO fixed_asset_remarks (
                ticket_no, remarks_note, remarks_by, date_remarks
            ) VALUES (
                :ticket_no, :remarks_note, :remarks_by, :date_remarks
            )
        ");

        $resultRemarks = $statementRemarks->execute([
            ':ticket_no'    => $ticketNo,
            ':remarks_note' => $remarksNote,
            ':remarks_by'   => $techId,
            ':date_remarks' => $currentDate
        ]);

        $statementNotif = $connection->prepare("
            INSERT INTO tbl_notif (
                ticket_no, store, itsup, notif_data, notif_val, notif_date
            ) VALUES (
                :ticket_no, :store, :itsup, :notif_data, :notif_val, :notif_date
            )
        ");

        $resultNotif = $statementNotif->execute([
            ':ticket_no'  => $ticketNo,
            ':store'      => $_SESSION["str_num"] ?? "",
            ':itsup'      => $_SESSION["tech_id"] ?? "",
            ':notif_data' => "Ma'am Alma Villanueva added a remarks on fixed asset ticket no " . $ticketNo,
            ':notif_val'  => '10',
            ':notif_date' => $currentDate
        ]);

        if ($resultRemarks && $resultNotif) {
            $connection->commit();
            echo json_encode(["status" => "success", "message" => "Remarks saved successfully."]);
        } else {
            $connection->rollBack();
            echo json_encode(["status" => "error", "message" => "SQL Error: Saving remarks failed."]);
        }

    } catch (PDOException $e) {
        if ($connection->inTransaction()) {
            $connection->rollBack();
        }
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        exit();
    }
}

if (isset($_POST["operation"]) && $_POST["operation"] == "changepass") {
    
    $curpass  = $_POST['curpass'] ?? '';
    $newpass  = $_POST['newpass'] ?? '';
    $confpass = $_POST['confrm_nwpass'] ?? '';

    if ($newpass !== $confpass) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'New passwords do not match.'
        ]);
        exit;
    }

    $qry = $connection->prepare("SELECT password FROM users WHERE id = :id LIMIT 1");
    $qry->execute([':id' => $userid]);
    $res = $qry->fetch(PDO::FETCH_ASSOC);

    if ($res) {
        $oldpass = $res['password'];
        $dcdeold_pass = base64_decode($oldpass);

        if ($curpass === $dcdeold_pass) {
            
            $statement = $connection->prepare("UPDATE users SET `password` = :password WHERE id = :id");
            $result = $statement->execute([
                ':password' => base64_encode($newpass),
                ':id'       => $userid
            ]);

            if ($result) {
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Password successfully changed.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Database error. Could not update password.'
                ]);
            }

        } else {
            echo json_encode([
                'status' => 'error', 
                'message' => 'The current password you entered is incorrect.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error', 
            'message' => 'User account not found.'
        ]);
    }
    
    exit;
}

 if($_POST["operation"] == "3")
 { 
$defrole = 'user'; 
$tmppas = 'owi123456';
$preset_username = substr($_POST['fname'], 0, 1).$_POST['lstname'];
$preset_username2 = substr($_POST['fname'], 0, 2).$_POST['lstname'];
$set_username = str_replace(" ", "", trim($preset_username));
$set_username2 = str_replace(" ", "", trim($preset_username2));
$techid = '0';
$isrtmalepic = 'default_male.jpg';
$isrtfmalepic = 'default_female.jpg';
$qry = $connection->prepare(" SELECT * FROM users");
$qry->execute();
$res = $qry->fetch(PDO::FETCH_ASSOC);
$valdtuser = $res['email'];
$statement = $connection->prepare("
INSERT INTO users (fname, lstname, dept_id, email, password, role, str_num , gender_id, img_name, usr_stat) VALUES (:fname, :lstname, :dept_id, :email, :password, :role, :str_num, :gender_id, :img_name, :usr_stat)
");

   $result = $statement->execute(
   array(
    ':fname' => strtoupper($_POST["fname"]),
    ':lstname' => strtoupper($_POST["lstname"]),
    ':email' => str_replace(" ", "", trim($set_username)),
    ':dept_id' => $_POST["select_dept"],
    ':password' => base64_encode($tmppas),
    ':role' => $defrole,
    ':str_num' => $_POST["strslt_num"],
    ':gender_id' => $_POST["slct_gender"],
    ':img_name' => ($_POST["slct_gender"] == '1') ? $isrtmalepic : $isrtfmalepic,
    ':usr_stat' => 'A'
   )
  ); 

  echo ("INSERTED");
 }

 if($_POST['operation'] == "4")
 {
$slctrestusr= $_POST['restusr_id'];
$dflpass= 'owi123456';
$qry = $connection->prepare(" SELECT * FROM users WHERE id = $slctrestusr ");
$qry->execute();
$res = $qry->fetch(PDO::FETCH_ASSOC); 
$statement = $connection->prepare("UPDATE users
SET `password` = :password
WHERE id = $slctrestusr");
  $result = $statement->execute(
   array(
    ':password' => base64_encode($dflpass)
  
   )
  );
    echo ("PASSWORD CHANGED");
 }

 if($_POST['operation'] == "stredit")
 {
$slctrestusr= $_POST['usrID'];
$qry = $connection->prepare(" SELECT * FROM users WHERE id = $slctrestusr ");
$qry->execute();
$res = $qry->fetch(PDO::FETCH_ASSOC); 
$statement = $connection->prepare("UPDATE users
SET `str_num` = :str_num
WHERE id = $slctrestusr");
  $result = $statement->execute(
   array(
    ':str_num' => $_POST["strslt_num"]
  
   )
  );
    echo ("Updated!");
 }

  if($_POST['operation'] == "Dactivate")
 {
$IDx = $_POST['IDx'];
$statement = $connection->prepare("UPDATE users SET usr_stat = :usr_stat, usr_upt_date =:updtex WHERE id = $IDx");
  $result = $statement->execute(
   array(
    ':usr_stat' => 'D',
    ':updtex' => date('Y-m-d H:i:s')
  
   )
  );
    echo ("Updated!");
 }

 if($_POST['operation'] == "Activate")
 {
$IDx = $_POST['IDx'];
$statement = $connection->prepare("UPDATE users SET usr_stat = :usr_stat, usr_upt_date =:updtex WHERE id = $IDx");
  $result = $statement->execute(
   array(
    ':usr_stat' => 'A',
    ':updtex' => date('Y-m-d H:i:s')
  
   )
  );
    echo ("Updated!");
 }

 if($_POST['operation'] == "str_add")
 {
  $strNo = $_POST['strNo'];
  $strCode = $_POST['strCode'];
  $strArea = $_POST['strArea'];
  $strName = $_POST['strName'];
  $strAddrs = $_POST['strAddrs'];
  $strContact = $_POST['strContact'];
  $slctAM = $_POST['slctAM'];
  $slctTech = $_POST['slctTech'];

$statement = $connection->prepare("INSERT INTO tbl_branch (str_num, str_code, area_num, str_name, str_adrs, str_contact, itsup, AM, SBS_NO, PRICE_LVL)
VALUES (:str_num, :str_code, :area_num, :str_name, :str_adrs, :str_contact, :itsup, :AM, :SBS_NO, :PRICE_LVL)");
  $result = $statement->execute(
   array(
    ':str_num' => $strNo,
    ':str_code' => $strCode,
    ':area_num' => $strArea,
    ':str_name' => $strName,
    ':str_adrs' => $strAddrs,
    ':str_contact' => $strContact,
    ':itsup' => $slctTech,
    ':AM' => $slctAM,
    ':SBS_NO' => '1',
    ':PRICE_LVL' => '1',
  
   )
  );
    echo ("Updated!");
 }

 if($_POST['operation'] == "stredit")
 {
  $strId = $_POST['strId'];
  $strNo = $_POST['strNo'];
  $strCode = $_POST['strCode'];
  $strArea = $_POST['strArea'];
  $strName = $_POST['strName'];
  $strAddrs = $_POST['strAddrs'];
  $strContact = $_POST['strContact'];
  $slctAM = $_POST['slctAM'];
  $slctTech = $_POST['slctTech'];

  $statement = $connection->prepare("UPDATE tbl_branch SET str_num = :str_num, str_code =:str_code, area_num = :area_num, str_name = :str_name, str_adrs = :str_adrs, str_contact = :str_contact, itsup =:itsup, AM = :AM  WHERE str_id = $strId");
  $result = $statement->execute(
   array(
    ':str_num' => $strNo,
    ':str_code' => $strCode,
    ':area_num' => $strArea,
    ':str_name' => $strName,
    ':str_adrs' => $strAddrs,
    ':str_contact' => $strContact,
    ':itsup' => $slctTech,
    ':AM' => $slctAM,
  
   )
  );
    echo ("Updated!");
 }

 if($_POST['operation'] == "ClosedStore")
 {
$strIDx = $_POST['strIDx'];
$statement = $connection->prepare("UPDATE tbl_branch SET str_add = :str_add WHERE str_id = $strIDx");
  $result = $statement->execute(
   array(
    ':str_add' => 'CLOSED'
  
   )
  );
    echo ("Updated!");
 }

 if($_POST['operation'] == "OpenStore")
 {
$strIDx = $_POST['strIDx'];
$statement = $connection->prepare("UPDATE tbl_branch SET str_add = :str_add WHERE str_id = $strIDx");
  $result = $statement->execute(
   array(
    ':str_add' => ''
  
   )
  );
    echo ("Updated!");
 }

} // end 
?>