<?php

session_start();
$tchnum = $_SESSION['tech_id'];
$userid = $_SESSION['user_id'];
date_default_timezone_set("Asia/Manila");

include('db.php');
// include('function.php');


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

if ($_POST["operation"] == "New_Report") {

    // SAFE POST (avoid undefined errors)
    $ticket_no   = $_POST["ticket_no"];
    $store       = $_POST["store"] ?? '0';
    $dept        = $_POST["f_deptsel"] ?? '0';
    $via         = 'PENDING';

    // $cat       = $_POST["cat"] ?? '0';
    $cat         = '31';  // default to "General" category

    // $sub       = $_POST["sub_num"] ?? '0';
    $sub         = '199'; // default to "General" sub-category

    $close_by    = $_POST["close_by"] ?? '0';
    $remarks     = $_POST["remarks"] ?? '';
    $status      = 'ASSIGNED'; // force status to ASSIGNED
    $refNo       = $_POST["refNo"] ?? '';
    $plvl        = $_POST["priority_level"] ?? '0'; // ✅ PRIORITY

    // safer date parsing (avoid 1970-01-01 when empty)
    $date_created = !empty($_POST["date_createdx"])
        ? date('Y-m-d H:i:s', strtotime($_POST["date_createdx"]))
        : date('Y-m-d H:i:s');

    $date_closed  = !empty($_POST["date_closed"])
        ? date('Y-m-d H:i:s', strtotime($_POST["date_closed"]))
        : null;

    $date_refNo   = !empty($_POST["date_refNo"])
        ? date('Y-m-d H:i:s', strtotime($_POST["date_refNo"]))
        : null;

    /* ---------------------------------
       ✅ GET CONTACT NUMBER FROM tbl_dept (BACKEND SOURCE OF TRUTH)
       --------------------------------- */
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

    /* ---------------------------------
       BUILD UPDATE QUERY DYNAMICALLY
       --------------------------------- */

    $fields = [
        "ticket_no = :ticket_no",
        "date_created = :date_created",
        "via = :via",
        "status = :status",
        "refNo = :refNo",
        "remarks = :remarks"
    ];

    // add nullable dates only if present (or explicitly set to NULL)
    if ($date_refNo !== null)  $fields[] = "date_refNo = :date_refNo";
    if ($date_closed !== null) $fields[] = "date_closed = :date_closed";

    $data = [
        ':ticket_no'    => $ticket_no,
        ':date_created' => $date_created,
        ':via'          => $via,
        ':status'       => $status,
        ':refNo'        => $refNo,
        ':remarks'      => $remarks
    ];

    if ($date_refNo !== null)  $data[':date_refNo']  = $date_refNo;
    if ($date_closed !== null) $data[':date_closed'] = $date_closed;

    if ($store != '0') {
        $fields[] = "store = :store";
        $data[':store'] = $store;
    }

    if ($dept != '0') {
        $fields[] = "f_deptsel = :f_deptsel";
        $data[':f_deptsel'] = $dept;
    }

    if ($cat != '0') {
        $fields[] = "cat_id = :cat_id";
        $data[':cat_id'] = $cat;
    }

    if ($sub != '0') {
        $fields[] = "sub_id = :sub_id";
        $data[':sub_id'] = $sub;
    }

    if ($close_by != '0') {
        $fields[] = "close_by = :close_by";
        $data[':close_by'] = $close_by;
    }

    // ✅ PRIORITY LEVEL SAVE (only update when user selected something)
    if ($plvl != '0' && $plvl != '') {
        $fields[] = "priority_level = :priority_level";
        $data[':priority_level'] = $plvl;
    }

    // ✅ CONTACT NUMBER SAVE (only if found)
    if ($contactNumber !== '') {
        $fields[] = "contactNumber = :contactNumber";
        $data[':contactNumber'] = $contactNumber;
    }

    // ISP default
    $fields[] = "isp_id = '0'";

    $sql = "UPDATE reports SET " . implode(", ", $fields) . " WHERE ticket_no = :ticket_no";

    $statement = $connection->prepare($sql);
    $result = $statement->execute($data);

    /* ---------------------------------
       REASSIGN HISTORY (DEPARTMENT)
       --------------------------------- */

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

    /* ---------------------------------
       REMARKS
       --------------------------------- */

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

    /* ---------------------------------
       RESET MSG COUNT
       --------------------------------- */

    $msgcntres = $connection->prepare("
        UPDATE reports_msgcnt
        SET msg_cnt = 0
        WHERE ticket_no = :ticket_no
    ");

    $msgcntres->execute([':ticket_no' => $ticket_no]);

    /* ---------------------------------
       NOTIFICATION
       --------------------------------- */

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

    /* ---------------------------------
       ADDITIONAL COMMENT
       --------------------------------- */

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
