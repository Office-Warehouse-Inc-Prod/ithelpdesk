<?php

session_start();
$tchnum = $_SESSION['tech_id'];
$userid = $_SESSION['user_id'];
date_default_timezone_set("Asia/Manila");

include('db.php');
// include('function.php');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; 
// require '/opt/lampp/htdocs/ithelpdesk/vendor/autoload.php';
// or if manual:
// require 'PHPMailer/src/Exception.php';
// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMTP.php';



if(isset($_POST["chcksbjcls"]))
{

  if($_POST["chcksbjcls"] == "check")
  {

    try {
      // $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      // set the PDO error mode to exception
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

      // Prepare statement
      $stmt2 = $connection->prepare($sqlai);
      $stmt1 = $connection->prepare($sql);
    
      // execute the query

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



  // $qry =  $connection->prepare(" SELECT ticket_no FROM counter");
  // $qry->execute();
  // $res = $qry->fetch(PDO::FETCH_ASSOC); 
  // $ticknum = $res['ticket_no']+1;
  // $statement =$connection->prepare("
  // INSERT INTO reports (ticket_no, store, date_created, subject, via, status, itsup, cat_id, sub_id, isp_id, refNo,) 
  // VALUES (:ticket_no, :store, :date_created, :subject, :via,  :status, :itsup, :cat_id, :sub_id, :isp_id, :refNo)
  // ");
  // $dcval = $_POST["date_created"];
  // $dclval = $_POST["date_closed"];
  // $datetime = date_create($dcval)->format('Y-m-d H:i:s');
  // $result = $statement->execute(
  //   array(
  //   'ticket_no' => $ticknum,
  //   ':store' => $_POST["store"],
  //   ':date_created' => $datetime,
  //   ':subject' => strtoupper($_POST["subjct"]),
  //   ':via' => $_POST["via"],
  //   ':status' => $_POST["status"],
  //   ':itsup' => $_POST["itsup"],
  //   ':cat_id' => $_POST["cat"],
  //   ':sub_id' => $_POST["sub"],

  //   )
  //   );



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
    // ':concern' => $_POST["concern"],
    ':via' => $_POST["via"],
    ':status' => $_POST["status"],
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

  if($_POST['status'] == "OPEN") {
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

 
 // ticket_trail

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

};


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
    // ':concern' => $_POST["concern"],
    ':via' => $_POST["via"],
    ':status' => $_POST["status"],
    // ':isp_id' => $_POST["isp_id"],
    ':date_closed' => date('Y-m-d H:i:s',strtotime($_POST["date_closed"])),
    ':close_by' => $_POST["close_by"],
    ':remarks' => $_POST["remarks"],
    ':refNo' => $_POST["refNo"],
    ':date_refNo' => date('Y-m-d H:i:s',strtotime($_POST["date_refNo"]))

   ) ;
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
    // ':concern' => $_POST["concern"],
    ':via' => $_POST["via"],
    ':status' => $_POST["status"],
    ':itsup' => $_POST["itsup"],
    ':cat_id' => $_POST["cat"],
    ':sub_id' => $_POST["sub_num"],
    ':isp_id' => $_POST["isp_num"],
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

  if($_POST['it_num'] != $_POST['itsup'])
  {
     $reasgn = $connection->prepare("
    INSERT INTO tbl_reassigned (ticket_no, date_created, itsup, nw_sup, r_remarks, date_rasigned) 
   VALUES (:ticket_no, :date_created, :itsup, :nw_sup, :r_remarks, :date_rasigned )
  ");
  $reasgnres= $reasgn->execute(
    array(
      ':ticket_no' => $_POST["ticket_no"],
      ':date_created' => date('Y-m-d H:i:s',strtotime($_POST["date_created"])),
      ':itsup' => $_POST["it_num"],
      ':nw_sup' => $_POST["itsup"],
      ':r_remarks' => $_POST["remarks"],
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

  //   $makecom = $connection->prepare("
  //   INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) 
  //  VALUES (:ticket_no, :comment_details, :comment_date, :userId )
  // ");

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
     echo 'Data has been updated'; // update alert 
}



// if ($_POST["operation"] == "New_Report") {

//     // ✅ SAFETY GUARD
//     if (empty($_POST["ticket_no"])) {
//         http_response_code(400);
//         exit("Missing ticket_no");
//     }

//     // SAFE POST (avoid undefined errors)
//     $ticket_no   = $_POST["ticket_no"];
//     $store       = $_POST["store"] ?? '0';
//     $dept        = $_POST["f_deptsel"] ?? '0';
//     $via         = 'PENDING';

//     // default category/subcategory
//     $cat         = '31';  // General
//     $sub         = '199'; // General

//     $close_by    = $_POST["close_by"] ?? '0';
//     $remarks     = $_POST["remarks"] ?? '';
//     $status      = $_POST["setStatus"] ?? '';
//     $refNo       = $_POST["refNo"] ?? '';
//     $plvl        = $_POST["priority_level"] ?? '0'; // ✅ PRIORITY

//     // safer date parsing (avoid 1970-01-01 when empty)
//     $date_created = !empty($_POST["date_createdx"])
//         ? date('Y-m-d H:i:s', strtotime($_POST["date_createdx"]))
//         : date('Y-m-d H:i:s');

//     $date_closed  = !empty($_POST["date_closed"])
//         ? date('Y-m-d H:i:s', strtotime($_POST["date_closed"]))
//         : null;

//     $date_refNo   = !empty($_POST["date_refNo"])
//         ? date('Y-m-d H:i:s', strtotime($_POST["date_refNo"]))
//         : null;

//     /* ---------------------------------
//        ✅ GET CONTACT NUMBER FROM tbl_dept (BACKEND SOURCE OF TRUTH)
//        --------------------------------- */
//     $contactNumber = '';

//     if ($dept != '0' && $dept != '') {
//         $stmtCN = $connection->prepare("
//             SELECT contactNumber
//             FROM tbl_dept
//             WHERE dept_id = :dept
//             LIMIT 1
//         ");
//         $stmtCN->execute([':dept' => $dept]);
//         $rowCN = $stmtCN->fetch(PDO::FETCH_ASSOC);
//         $contactNumber = $rowCN['contactNumber'] ?? '';
//     }

//     /* ---------------------------------
//        ✅ BUILD UPDATE QUERY DYNAMICALLY (FIXED)
//        DO NOT UPDATE ticket_no
//        --------------------------------- */

//     $fields = [
//         "date_created = :date_created",
//         "via = :via",
//         "status = :status",
//         "refNo = :refNo",
//         "remarks = :remarks"
//     ];

//     $data = [
//         ':date_created' => $date_created,
//         ':via'          => $via,
//         ':status'       => $status,
//         ':refNo'        => $refNo,
//         ':remarks'      => $remarks
//     ];

//     // add nullable dates only if present
//     if ($date_refNo !== null) {
//         $fields[] = "date_refNo = :date_refNo";
//         $data[':date_refNo'] = $date_refNo;
//     }

//     if ($date_closed !== null) {
//         $fields[] = "date_closed = :date_closed";
//         $data[':date_closed'] = $date_closed;
//     }

//     if ($store != '0' && $store != '') {
//         $fields[] = "store = :store";
//         $data[':store'] = $store;
//     }

//     if ($dept != '0' && $dept != '') {
//         $fields[] = "f_deptsel = :f_deptsel";
//         $data[':f_deptsel'] = $dept;
//     }

//     if ($cat != '0' && $cat != '') {
//         $fields[] = "cat_id = :cat_id";
//         $data[':cat_id'] = $cat;
//     }

//     if ($sub != '0' && $sub != '') {
//         $fields[] = "sub_id = :sub_id";
//         $data[':sub_id'] = $sub;
//     }

//     if ($close_by != '0' && $close_by != '') {
//         $fields[] = "close_by = :close_by";
//         $data[':close_by'] = $close_by;
//     }

//     // ✅ PRIORITY LEVEL SAVE (only update when user selected something)
//     if ($plvl != '0' && $plvl != '') {
//         $fields[] = "priority_level = :priority_level";
//         $data[':priority_level'] = $plvl;
//     }

//     // ✅ CONTACT NUMBER SAVE (only if found)
//     if ($contactNumber !== '') {
//         $fields[] = "contactNumber = :contactNumber";
//         $data[':contactNumber'] = $contactNumber;
//     }

//     // ISP default
//     $fields[] = "isp_id = '0'";

//     // ✅ IMPORTANT FIX: WHERE uses separate placeholder
//     $sql = "UPDATE reports SET " . implode(", ", $fields) . " WHERE ticket_no = :where_ticket_no";
//     $data[':where_ticket_no'] = $ticket_no;

//     $statement = $connection->prepare($sql);
//     $result = $statement->execute($data);

//     /* ---------------------------------
//        REASSIGN HISTORY (DEPARTMENT)
//        --------------------------------- */
//     if (($_POST['old_dept'] ?? '') != $dept) {

//         $reasgn = $connection->prepare("
//             INSERT INTO tbl_reassigned 
//             (ticket_no, date_created, old_dept, new_dept, r_remarks, date_rasigned)
//             VALUES (:ticket_no, :date_created, :old_dept, :new_dept, :remarks, NOW())
//         ");

//         $reasgn->execute([
//             ':ticket_no'    => $ticket_no,
//             ':date_created' => $date_created,
//             ':old_dept'     => $_POST['old_dept'] ?? '0',
//             ':new_dept'     => $dept,
//             ':remarks'      => $remarks
//         ]);
//     }

//     /* ---------------------------------
//        REMARKS
//        --------------------------------- */
//     if ($result) {

//         $restat = $connection->prepare("
//             INSERT INTO reports_remarks
//             (ticket_no, remarks_detail, remarks_date, f_deptsel)
//             VALUES (:ticket_no, :remarks, NOW(), :dept)
//         ");

//         $restat->execute([
//             ':ticket_no' => $ticket_no,
//             ':remarks'   => $remarks,
//             ':dept'      => $dept
//         ]);
//     }

//     /* ---------------------------------
//        RESET MSG COUNT
//        --------------------------------- */
//     $msgcntres = $connection->prepare("
//         UPDATE reports_msgcnt
//         SET msg_cnt = 0
//         WHERE ticket_no = :ticket_no
//     ");
//     $msgcntres->execute([':ticket_no' => $ticket_no]);

//     /* ---------------------------------
//        NOTIFICATION
//        --------------------------------- */
//     if ($result) {

//         $notif = $connection->prepare("
//             INSERT INTO tbl_notif
//             (ticket_no, store, f_deptsel, notif_data, notif_val, notif_date, assigned_by)
//             VALUES (:ticket_no, :store, :dept, :msg, '1', NOW(), :assigned_by)
//         ");

//         $notif->execute([
//             ':ticket_no'    => $ticket_no,
//             ':store'        => $store,
//             ':dept'         => $dept,
//             ':msg'          => "Ticket $ticket_no has been assigned to a department.",
//             ':assigned_by'  => $userid
//         ]);
//     }

//     /* ---------------------------------
//        ADDITIONAL COMMENT
//        --------------------------------- */
//     if (!empty($_POST["admsg"])) {

//         $makecom = $connection->prepare("
//             INSERT INTO reports_comments
//             (ticket_no, comment_details, comment_date, userId)
//             VALUES (:ticket_no, :comment, NOW(), :uid)
//         ");

//         $makecom->execute([
//             ':ticket_no' => $ticket_no,
//             ':comment'   => $_POST["admsg"],
//             ':uid'       => $_POST["u_id"]
//         ]);

//         $connection->prepare("
//             UPDATE reports_newmsg
//             SET nmsg_stat = '2'
//             WHERE ticket_no = :ticket_no
//         ")->execute([':ticket_no' => $ticket_no]);

//         // Ticket trail
//         $connection->prepare("
//             INSERT INTO tbl_tickethist
//             (ticket_no, date_updated, status, userID)
//             VALUES (:ticket_no, NOW(), :status, :uid)
//         ")->execute([
//             ':ticket_no' => $ticket_no,
//             ':status'    => $status,
//             ':uid'       => $_POST["u_id"]
//         ]);
//     }
// }



if ($_POST["operation"] == "New_Report") {

    if (empty($_POST["ticket_no"])) {
        http_response_code(400);
        exit("Missing ticket_no");
    }

    $ticket_no   = $_POST["ticket_no"];
    $store       = $_POST["store"] ?? '0';
    $dept        = $_POST["f_deptsel"] ?? '0';
    $concern        = $_POST["concern"] ?? '0';
    $via         = 'PENDING';

    $cat         = '31';
    $sub         = '199';

    $close_by    = $_POST["close_by"] ?? '0';
    $remarks     = $_POST["remarks"] ?? '';
    $status      = $_POST["setStatus"] ?? '';
    $refNo       = $_POST["refNo"] ?? '';
    $plvl        = $_POST["priority_level"] ?? '0';
    $sla_days    = $_POST["sla_days"] ?? '0';

    $date_created = !empty($_POST["date_createdx"])
        ? date('Y-m-d H:i:s', strtotime($_POST["date_createdx"]))
        : date('Y-m-d H:i:s');

    $date_closed  = !empty($_POST["date_closed"])
        ? date('Y-m-d H:i:s', strtotime($_POST["date_closed"]))
        : null;

    $date_refNo   = !empty($_POST["date_refNo"])
        ? date('Y-m-d H:i:s', strtotime($_POST["date_refNo"]))
        : null;

    $contactNumber = '';
    if ($dept != '0' && $dept != '') {
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

    $sql = "UPDATE reports SET " . implode(", ", $fields) . " WHERE ticket_no = :where_ticket_no";
    $data[':where_ticket_no'] = $ticket_no;

    $statement = $connection->prepare($sql);
    $result = $statement->execute($data);

    if (($_POST['old_dept'] ?? '') != $dept) {
        $reasgn = $connection->prepare("
            INSERT INTO tbl_reassigned 
            (ticket_no, date_created, old_dept, new_dept, r_remarks, date_rasigned)
            VALUES (:ticket_no, :date_created, :old_dept, :new_dept, :remarks, NOW())
        ");

        $reasgn->execute([
            ':ticket_no'    => $ticket_no,
            ':date_created' => $date_created,
            ':old_dept'     => $_POST['old_dept'] ?? '0',
            ':new_dept'     => $dept,
            ':remarks'      => $remarks
        ]);
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

    /* =========================================================
       EMAIL SENDING PART
       ========================================================= */
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
                case '1': $priorityLabel = 'Low'; break;
                case '2': $priorityLabel = 'Normal'; break;
                case '3': $priorityLabel = 'High'; break;
                case '4': $priorityLabel = 'Urgent'; break;
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

            // $mail->SMTPDebug = 2;
            // $mail->Debugoutput = 'html';

            $mail->isSMTP();
            $mail->Host       = 'mail.officewarehouse.com.ph';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'portal_noreply@officewarehouse.com.ph';
            $mail->Password   = 'Owi@123456**';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('portal_noreply@officewarehouse.com.ph', 'HELPDESK AI');
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
                                    <td style="border:1px solid #d6def7;"><strong>Reference No.</strong></td>
                                    <td style="border:1px solid #d6def7;">' . htmlspecialchars($refNo) . '</td>
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
                            <p>Thank you.</p>

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
            $mail->AltBody = "Ticket {$ticket_no} has been assigned to {$deptName}. "
                           . "Store: {$storeName}, Status: {$status}, Priority: {$priorityLabel}, "
                           . "SLA: {$slaLabel}, SLA Max Days: {$sla_days}, Due Date: {$dueDate}, "
                           . "Ref No: {$refNo}, Remarks: {$remarks}";

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

    // SAFE POST (avoid undefined errors)
    $ticket_no   = $_POST["ticket_no"];
    $store       = $_POST["store"] ?? '0';
    $close_by    = $_POST["close_by"] ?? '0';
    $remarks     = $_POST["remarks"] ?? '';
    $status      = $_POST["status"] ?? '';
    $plvl        = $_POST["priority_level"] ?? '0'; // ✅ PRIORITY

    // safer date parsing (avoid 1970-01-01 when empty)
    $date_created = !empty($_POST["date_createdx"])
        ? date('Y-m-d H:i:s', strtotime($_POST["date_createdx"]))
        : date('Y-m-d H:i:s');

    $date_closed  = !empty($_POST["date_closed"])
        ? date('Y-m-d H:i:s', strtotime($_POST["date_closed"]))
        : null;
    /* ---------------------------------
       BUILD UPDATE QUERY DYNAMICALLY
       --------------------------------- */

// REQUIRED
$ticket_no = trim($_POST["ticket_no"] ?? '');

if ($ticket_no === '') {
    exit("Ticket number is required.");
}

// FIELDS
$store     = $_POST["store"] ?? '0';
$close_by  = $_POST["close_by"] ?? '0';
$remarks   = $_POST["remarks"] ?? '';
$status    = $_POST["status"] ?? '';
$plvl      = $_POST["priority_level"] ?? ($_POST["prioty_level"] ?? '0'); // fallback fix

// SAFE DATES
$date_created = !empty($_POST["date_createdx"])
    ? date('Y-m-d H:i:s', strtotime($_POST["date_createdx"]))
    : date('Y-m-d H:i:s');

$date_closed = !empty($_POST["date_closed"])
    ? date('Y-m-d H:i:s', strtotime($_POST["date_closed"]))
    : null;


// -----------------------------
// BUILD UPDATE QUERY
// -----------------------------

$fields = [];
$data   = [':ticket_no' => $ticket_no];


// Always update date_created (optional — remove if you don't want it touched)
// $fields[] = "date_created = :date_created";
// $data[':date_created'] = $date_created;


// OPTIONAL FIELDS

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


// VERY IMPORTANT:
// Always explicitly set date_closed so reopening works too.

$fields[] = "date_closed = :date_closed";
$data[':date_closed'] = $date_closed;


// Prevent empty UPDATE
if (empty($fields)) {
    exit("No fields to update.");
}


// FINAL SQL
$sql = "UPDATE reports 
        SET " . implode(", ", $fields) . "
        WHERE ticket_no = :ticket_no";

$stmt = $connection->prepare($sql);

if ($stmt->execute($data)) {

    // Optional but VERY useful for debugging
    if ($stmt->rowCount() > 0) {
        echo "Ticket updated successfully.";
    } else {
        echo "No changes detected.";
    }

} else {
    echo "Update failed.";
}



    /* ---------------------------------
       REMARKS
       --------------------------------- */

    if($result){

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

    /* ---------------------------------
       RESET MSG COUNT
       --------------------------------- */

    $msgcntres = $connection->prepare("
        UPDATE reports_msgcnt
        SET msg_cnt = 0
        WHERE ticket_no = :ticket_no
    ");

    $msgcntres->execute([':ticket_no'=>$ticket_no]);

    /* ---------------------------------
       NOTIFICATION
       --------------------------------- */

    if($result){

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

    /* ---------------------------------
       ADDITIONAL COMMENT
       --------------------------------- */

    if(!empty($_POST["admsg"])){

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
        ")->execute([':ticket_no'=>$ticket_no]);

        // Ticket trail
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
}





 if($_POST["operation"] == "changepass")
 { 
$qry = $connection->prepare(" SELECT * FROM users WHERE id = $userid");
$qry->execute();
$res = $qry->fetch(PDO::FETCH_ASSOC); 
$oldpass = $res['password'];
$dcdeold_pass= base64_decode($oldpass);
$newpass= $_POST['newpass'];
if ($_POST["curpass"] == $dcdeold_pass && $_POST['newpass'] == $_POST['confrm_nwpass']) {
$statement = $connection->prepare("UPDATE users
SET `password` = :password
WHERE id = $userid");
  $result = $statement->execute(
   array(
    ':password' => base64_encode($newpass)
  
   )
  );
    echo ("PASSWORD CHANGED");
    } else {
     echo ("ERROR");
     return false;
        }
 }

 if($_POST["operation"] == "3")
 { 
$defrole = 'user'; //default value of role when registered. 
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
// $statement = $connection->prepare("
// INSERT INTO users (fname, lstname, email) VALUES (:fname, :lstname, :email)
// ");

   $result = $statement->execute(
   array(
    ':fname' => strtoupper($_POST["fname"]),
    ':lstname' => strtoupper($_POST["lstname"]),
    ':email' => str_replace(" ", "", trim($set_username)),
    // // ':email' => ($valdtuser == "TDoe") ? $set_username2 : $set_username,
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
// $dflpass= 'owi123456';
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
