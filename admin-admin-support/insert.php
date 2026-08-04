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
    ':deptsel' => '2' // admin dept

    
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
      ':date_created' => date('Y-m-d H:i:s'),
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
if (isset($_POST["operation"]) && $_POST["operation"] === "update_request") {
    
    header('Content-Type: application/json');
    
    if (empty($_POST['ticket_no'])) {
        echo json_encode(["status" => "error", "message" => "Missing Ticket Number."]);
        exit();
    }

    $ticketNo = $_POST['ticket_no'];

    try {
        $connection->beginTransaction();
        $stmtCheck = $connection->prepare("
            SELECT is_technical, item_received_by, noted_by 
            FROM asset_requests 
            WHERE ticket_no = :ticket_no
        ");
        $stmtCheck->execute([':ticket_no' => $ticketNo]);
        $existingRecord = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        $final_item_received_by = $_POST['item_received_by'] ?? '';
        $final_noted_by = $_POST['noted_by'] ?? '';

        if ($existingRecord && $existingRecord['is_technical'] == 1) {
            $final_item_received_by = $existingRecord['item_received_by'];
            $final_noted_by = $existingRecord['noted_by'];
        } else {
            if (empty($final_item_received_by) && isset($_SESSION['tech_id'])) {
                $final_item_received_by = $_SESSION['tech_id'];
            }
            if (empty($final_noted_by) && isset($_SESSION['tech_id'])) {
                $final_noted_by = $_SESSION['tech_id'];
            }
        }

        $statement = $connection->prepare("
            UPDATE asset_requests
            SET
                serial_number = :serial_number,
                asset_tag_number = :asset_tag_number,
                revised_request = :revised_request,
                date_received = :date_received,
                date_validated = :date_validated,
                validated_by = :validated_by,
                item_received_by = :item_received_by,
                technical_workoutput = :technical_workoutput,
                purpose_of_request = :purpose_of_request,
                noted_by = :noted_by,
                approve_method_adminsup = :approve_method_adminsup,
                status = :status
            WHERE ticket_no = :ticket_no
        ");

        $result = $statement->execute([
            ':serial_number'    => !empty($_POST['serial_number']) ? $_POST['serial_number'] : 'N/A',
            ':asset_tag_number' => !empty($_POST['asset_tag_number']) ? $_POST['asset_tag_number'] : 'N/A',
            ':revised_request'  => $_POST['revised_request'] ?? '',
            ':date_received'    => $_POST['date_received'] ?? '',
            ':date_validated'   => date('Y-m-d H:i:s'),
            ':validated_by'     => $_SESSION['user_id'] ?? null, 
            ':item_received_by' => $final_item_received_by,
            ':technical_workoutput' => $_POST['technical_workoutput'] ?? '',
            ':purpose_of_request' => $_POST['purpose_of_request'] ?? '',
            ':noted_by'         => $final_noted_by,
            ':approve_method_adminsup'  => '2',
            ':status'           => 'VALIDATED',
            ':ticket_no'        => $ticketNo
        ]);

        if ($result) {
            $connection->commit();
            try {
                $stmtEmail = $connection->prepare("SELECT email FROM fixed_asset_email WHERE val = '3' LIMIT 1");
                $stmtEmail->execute();
                $emailRow = $stmtEmail->fetch(PDO::FETCH_ASSOC);
                
                if ($emailRow && !empty($emailRow['email'])) {
                    $receiverEmail = trim($emailRow['email']);
                    $stmtDetails = $connection->prepare("
                        SELECT 
                            ar.ticket_no, 
                            b.str_name, 
                            CONCAT(u.fname, ' ', u.lstname) AS full_name, 
                            ar.ticket_created, 
                            ar.item_code,
                            ar.description, 
                            ar.serial_number, 
                            ar.asset_tag_number, 
                            ar.purpose_of_request, 
                            ar.technical_workoutput, 
                            it.it_desc,
                            it.itsup,          
                            ar.date_received, 
                            ar.created_at,
                            ar.noted_by,       
                            ar.status          
                        FROM asset_requests ar
                        LEFT JOIN it_tech it ON ar.item_received_by = it.itsup
                        LEFT JOIN reports r ON ar.ticket_no = r.ticket_no
                        LEFT JOIN users u ON r.userId = u.id
                        LEFT JOIN tbl_branch b ON r.store = b.str_num 
                        WHERE ar.ticket_no = :ticket_no
                        LIMIT 1
                    ");
                    $stmtDetails->execute([':ticket_no' => $ticketNo]);
                    $ticketData = $stmtDetails->fetch(PDO::FETCH_ASSOC);
                    
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
                    
                    $mail->Subject = "Asset Request Validated: {$ticketNo}";
                    $mailBody = '
                    <html>
                    <body style="margin:0;padding:20px;background:#f4f6f9;font-family:Arial,sans-serif;">
                        <table width="700" align="center" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #cabb89;border-radius:8px;overflow:hidden;">
                            <tr>
                                <td style="background:#E1AD01;color:#ffffff;padding:18px 24px;font-size:20px;font-weight:bold;">
                                    Helpdesk AI: Asset Request Validated
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:24px;font-size:14px;color:#333;">
                                    <p>Good day,</p>
                                    <p>A fixed asset request has been validated and approved. Please see the details below:</p>
                                    
                                    <table cellpadding="8" cellspacing="0" width="100%" style="border-collapse:collapse;margin-top:10px;">
                                        <tr style="background:#f3e8c3;">
                                            <td style="border:1px solid #cabb89;width:180px;"><strong>Ticket No.</strong></td>
                                            <td style="border:1px solid #cabb89;">' . htmlspecialchars($ticketNo) . '</td>
                                        </tr>
                                        <tr>
                                            <td style="border:1px solid #cabb89;"><strong>Status</strong></td>
                                            <td style="border:1px solid #cabb89;">VALIDATED</td>
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
                                    
                                    <p style="margin-top:20px;">Please log in to the <strong>OWI Helpdesk</strong> to review this asset request.</p>
                                    <div style="text-align:center;margin-top:25px;">
                                        <a href="https://owihelpdesk.officewarehouse.com.ph" 
                                           style="background:#627bc5;color:#ffffff;padding:12px 24px;text-decoration:none;border-radius:5px;display:inline-block;font-weight:bold;">
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
                    </html>';

                    $mail->Body = $mailBody;
                    $mail->AltBody = "Asset Request Validated: {$ticketNo}. Requested By: {$display_user} ({$display_dept}). Item Received By: {$display_receiver}.";

                    $mail->send();
                }
            } catch (Exception $emailEx) {
            }

            echo json_encode(["status" => "success", "message" => "Request approved successfully."]);
        } else {
            $connection->rollBack();
            echo json_encode(["status" => "error", "message" => "Failed to update the database."]);
        }

    } catch (PDOException $e) {
        if ($connection->inTransaction()) {
            $connection->rollBack();
        }
        echo json_encode(["status" => "error", "message" => "SQL Error: " . $e->getMessage()]);
    }
    
    exit(); 
}



if (isset($_POST["operation"]) && $_POST["operation"] === "update_recording_request") {
    
    if (empty($_POST['ticket_no'])) {
        echo json_encode(["status" => "error", "message" => "Missing Ticket Number."]);
        exit();
    }

    try {
        

        $statement = $connection->prepare("
            UPDATE asset_requests
            SET
                serial_number = :serial_number,
                asset_tag_number = :asset_tag_number,
                revised_request = :revised_request,
                date_received = :date_received,
                date_recorded    = :date_recorded,
                status        = :status
            WHERE ticket_no   = :ticket_no
        ");

       $result = $statement->execute([
          ':serial_number'    => !empty($_POST['serial_number']) ? $_POST['serial_number'] : 'N/A',
            ':asset_tag_number' => !empty($_POST['asset_tag_number']) ? $_POST['asset_tag_number'] : 'N/A',
              ':revised_request' => $_POST['revised_request'] ?? '',
            ':date_received' => $_POST['date_received'] ?? '',
            ':date_recorded'    => date('Y-m-d H:i:s'),
            ':status'        => 'RECORDED',
            ':ticket_no'     => $_POST['ticket_no']
        ]);

        if ($result) {
            echo json_encode(["status" => "success", "message" => "Request Recorded successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update the database."]);
        }

    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "SQL Error: " . $e->getMessage()]);
    }
    
    exit(); 
}



if (isset($_POST["operation"]) && $_POST["operation"] === "update_printing_request") {
    
    if (empty($_POST['ticket_no'])) {
        echo json_encode(["status" => "error", "message" => "Missing Ticket Number."]);
        exit();
    }

    try {
        

        $statement = $connection->prepare("
            UPDATE asset_requests
            SET
                serial_number = :serial_number,
                asset_tag_number = :asset_tag_number,
                revised_request = :revised_request,
                date_received = :date_received,
                date_printed    = :date_printed,
                status        = :status
            WHERE ticket_no   = :ticket_no
        ");

       $result = $statement->execute([
           ':serial_number'    => !empty($_POST['serial_number']) ? $_POST['serial_number'] : 'N/A',
            ':asset_tag_number' => !empty($_POST['asset_tag_number']) ? $_POST['asset_tag_number'] : 'N/A',
              ':revised_request' => $_POST['revised_request'] ?? '',
            ':date_received' => $_POST['date_received'] ?? '',
            ':date_printed'    => date('Y-m-d H:i:s'),
            ':status'        => 'PRINTED',
            ':ticket_no'     => $_POST['ticket_no']
        ]);

        if ($result) {
            echo json_encode(["status" => "success", "message" => "Fixed Asset Request Printed successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update the database."]);
        }

    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "SQL Error: " . $e->getMessage()]);
    }
    
    exit(); 
}


if (isset($_POST["operation"]) && $_POST["operation"] === "save_request") {
    
    header('Content-Type: application/json');
    
    if (empty($_POST['ticket_no'])) {
        echo json_encode(["status" => "error", "message" => "Missing Ticket Number."]);
        exit();
    }

    $status = !empty($_POST['status']) ? $_POST['status'] : null;
    $ticket_no = $_POST['ticket_no'];
    $currentDate = date('Y-m-d H:i:s'); 

    $allowed_statuses = [
        'PRINTED'   => 'date_printed',
        'RECORDED'  => 'date_recorded',
        'VERIFIED'  => 'date_verified',
        'APPROVED'  => 'date_approved',
        'COMPLETED' => 'date_completed'
    ];

    try {
        // Start database transaction
        $connection->beginTransaction();

        $sql = "UPDATE asset_requests SET status = :status";
        $params = [
            ':status'    => $status,
            ':ticket_no' => $ticket_no
        ];

       // Ensure these editable fields are ALSO saved to the database
        $editable_fields = ['serial_number', 'asset_tag_number', 'revised_request', 'date_received'];
        foreach ($editable_fields as $field) {
            if (isset($_POST[$field])) {
                $sql .= ", {$field} = :{$field}";
                $params[":{$field}"] = trim($_POST[$field]);
            }
        }

        if (array_key_exists($status, $allowed_statuses)) {
            $target_column = $allowed_statuses[$status];
            
            $posted_date = !empty($_POST[$target_column]) ? $_POST[$target_column] : $currentDate;
            $formatted_date = str_replace('T', ' ', $posted_date);

            $sql .= ", {$target_column} = :target_date";
            $params[':target_date'] = $formatted_date;
        }

        $sql .= " WHERE ticket_no = :ticket_no";

        $statement = $connection->prepare($sql);
        $result = $statement->execute($params);

        if ($result) {
            
            if ($status === 'RECORDED') {
                $statement2 = $connection->prepare("
                    INSERT INTO tbl_notif (
                        ticket_no, store, itsup, notif_data, notif_val, notif_date
                    ) VALUES (
                        :ticket_no, :store, :itsup, :notif_data, :notif_val, :notif_date
                    )
                ");

                $statement2->execute([
                    ':ticket_no'  => $ticket_no,
                    ':store'      => $_SESSION["str_num"] ?? "",
                    ':itsup'      => $_SESSION["tech_id"] ?? "",
                    ':notif_data' => "Fixed asset " . $ticket_no . " has been already validated and recorded and waiting for verification",
                    ':notif_val'  => '7',
                    ':notif_date' => $currentDate
                ]);
            }

            $connection->commit();
            if ($status === 'PRINTED') {
                try {
                   
                    $stmtEmail = $connection->prepare("SELECT email FROM fixed_asset_email WHERE val = '4' LIMIT 1");
                    $stmtEmail->execute();
                    $emailRow = $stmtEmail->fetch(PDO::FETCH_ASSOC);
                    
                    if ($emailRow && !empty($emailRow['email'])) {
                        $receiverEmail = trim($emailRow['email']);
                        $stmtDetails = $connection->prepare("
                            SELECT 
                                ar.ticket_no, 
                                b.str_name, 
                                CONCAT(u.fname, ' ', u.lstname) AS full_name, 
                                ar.ticket_created, 
                                ar.item_code,
                                ar.description, 
                                ar.serial_number, 
                                ar.asset_tag_number, 
                                ar.purpose_of_request, 
                                ar.technical_workoutput, 
                                it.it_desc,
                                it.itsup,          
                                ar.date_received, 
                                ar.created_at,
                                ar.noted_by,       
                                ar.status          
                            FROM asset_requests ar
                            LEFT JOIN it_tech it ON ar.item_received_by = it.itsup
                            LEFT JOIN reports r ON ar.ticket_no = r.ticket_no
                            LEFT JOIN users u ON r.userId = u.id
                            LEFT JOIN tbl_branch b ON r.store = b.str_num 
                            WHERE ar.ticket_no = :ticket_no
                            LIMIT 1
                        ");
                        $stmtDetails->execute([':ticket_no' => $ticket_no]);
                        $ticketData = $stmtDetails->fetch(PDO::FETCH_ASSOC);
                        
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
                        
                        $mail->Subject = "Asset Request For Approval: {$ticket_no}";
                        $mailBody = '
                        <html>
                        <body style="margin:0;padding:20px;background:#f4f6f9;font-family:Arial,sans-serif;">
                            <table width="700" align="center" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #cabb89;border-radius:8px;overflow:hidden;">
                                <tr>
                                    <td style="background:#E1AD01;color:#ffffff;padding:18px 24px;font-size:20px;font-weight:bold;">
                                        Helpdesk AI: Asset Request For Approval
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:24px;font-size:14px;color:#333;">
                                        <p>Good day,</p>
                                        <p>A fixed asset request has been printed and is now <strong>For Approval</strong>. Please see the details below:</p>
                                        
                                        <table cellpadding="8" cellspacing="0" width="100%" style="border-collapse:collapse;margin-top:10px;">
                                            <tr style="background:#f3e8c3;">
                                                <td style="border:1px solid #cabb89;width:180px;"><strong>Ticket No.</strong></td>
                                                <td style="border:1px solid #cabb89;">' . htmlspecialchars($ticket_no) . '</td>
                                            </tr>
                                            <tr>
                                                <td style="border:1px solid #cabb89;"><strong>Status</strong></td>
                                                <td style="border:1px solid #cabb89;">PRINTED (For Approval)</td>
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
                                        
                                        <p style="margin-top:20px;">Please log in to the <strong>OWI Helpdesk</strong> to review and approve this request.</p>
                                        <div style="text-align:center;margin-top:25px;">
                                            <a href="https://owihelpdesk.officewarehouse.com.ph" 
                                               style="background:#627bc5;color:#ffffff;padding:12px 24px;text-decoration:none;border-radius:5px;display:inline-block;font-weight:bold;">
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
                        </html>';

                        $mail->Body = $mailBody;
                        $mail->AltBody = "Asset Request For Approval: {$ticket_no}. Requested By: {$display_user} ({$display_dept}). Item Received By: {$display_receiver}.";

                        $mail->send();
                    }
                } catch (Exception $emailEx) {
                   
                }
            }

            echo json_encode(["status" => "success", "message" => "Request updated successfully."]);

        } else {
            $connection->rollBack();
            echo json_encode(["status" => "error", "message" => "Failed to update the database."]);
        }

    } catch (PDOException $e) {
        if ($connection->inTransaction()) {
            $connection->rollBack();
        }
        echo json_encode(["status" => "error", "message" => "SQL Error: " . $e->getMessage()]);
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
            ':notif_data' => "Admin Support added a remarks on fixed asset ticket no " . $ticketNo,
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
// if($_POST["operation"] == "Save and Reply")
//  { 
//      $optbrval = $_POST["store"];
//      $optval = $_POST["itsup"];
//      $optcval = $_POST["cat"];
//      $optsval = $_POST["sub_num"];
//      $opclbval= $_POST["close_by"];
//      $tmpval = '0';
// if ( ($optbrval == '0') || ($optval == '0') || ($optcval == '0') || ($optsval == '0') || ($opclbval == '0') ) {
// $brid="";
// $itsup="";
// $cat_id="";
// $sub_id="";
// $clby="";
// $ispid="";
// $data=   array(
//     ':ticket_no' => $_POST["ticket_no"],
//     ':date_created' => date('Y-m-d H:i:s',strtotime($_POST["date_createdx"])),
//     // ':concern' => $_POST["concern"],
//     ':via' => $_POST["via"],
//     ':status' => $_POST["status"],
//     // ':isp_id' => $_POST["isp_id"],
//     ':date_closed' => date('Y-m-d H:i:s',strtotime($_POST["date_closed"])),
//     ':close_by' => $_POST["close_by"],
//     ':remarks' => $_POST["remarks"],
//     ':refNo' => $_POST["refNo"],
//     ':date_refNo' => date('Y-m-d H:i:s',strtotime($_POST["date_refNo"]))

//    ) ;
// }
// else{

//     $brid="store = :store,";
//     $itsup = "itsup = :itsup,";
//     $cat_id="cat_id = :cat_id,";
//     $sub_id="sub_id =:sub_id,";
//     $clby="close_by = :close_by,";
//     $ispid="isp_id = :isp_id,";

//       $data=   array(
//     ':ticket_no' => $_POST["ticket_no"],
//     ':store' => $_POST["store"],
//     ':date_created' => date('Y-m-d H:i:s',strtotime($_POST["date_createdx"])),
//     // ':concern' => $_POST["concern"],
//     ':via' => $_POST["via"],
//     ':status' => $_POST["status"],
//     ':itsup' => $_POST["itsup"],
//     // ':itsup' => 'TEST',
//     ':cat_id' => $_POST["cat"],
//     ':sub_id' => $_POST["sub_num"],
//     ':isp_id' => '0',
//     ':refNo' => $_POST["refNo"],
//     ':date_refNo' => date('Y-m-d H:i:s',strtotime($_POST["date_refNo"])),
//     ':date_closed' => date('Y-m-d H:i:s',strtotime($_POST["date_closed"])),
//     ':close_by' => $_POST["close_by"],
//     ':remarks' => $_POST["remarks"]
//    ) ;

// }

//   $statement = $connection->prepare(
//    "UPDATE reports
//    SET ticket_no = :ticket_no, $brid date_created = :date_created,  via = :via, 
//                     status = :status, $itsup $cat_id $sub_id $ispid refNo = :refNo, date_refNo = :date_refNo, date_closed = :date_closed, $clby remarks = :remarks
//    WHERE ticket_no = :ticket_no"
//   );

//   $result = $statement->execute($data);

// // if ($result) {
// // echo "Record updated successfully.";
// // } else {
// // echo "Error updating record: " . implode(", ", $statement->errorInfo());
// // }

// if($_POST['it_num'] != $_POST['itsup'])
//   {
//      $reasgn = $connection->prepare("
//     INSERT INTO tbl_reassigned (ticket_no, date_created, itsup, nw_sup, r_remarks, date_rasigned) 
//    VALUES (:ticket_no, :date_created, :itsup, :nw_sup, :r_remarks, :date_rasigned )
//   ");
//   $reasgnres= $reasgn->execute(
//     array(
//       ':ticket_no' => $_POST["ticket_no"],
//       ':date_created' => date('Y-m-d H:i:s',strtotime($_POST["date_createdx"])),
//       ':itsup' => $_POST["it_num"],
//       ':nw_sup' => $_POST["itsup"],
//       ':r_remarks' => $_POST["remarks"],
//       ':date_rasigned' => date('Y-m-d H:i:s')
//     ));
//   }

//   if(!empty($result))
//   {
//      $restat = $connection->prepare("
//     INSERT INTO reports_remarks (ticket_no, remarks_detail, remarks_date, itsup) 
//    VALUES (:ticket_no, :remarks_detail, :remarks_date, :itsup )
//   ");
//   $remarkres1= $restat->execute(
//     array(

//      ':ticket_no' => $_POST["ticket_no"],
//       ':remarks_detail' => $_POST["remarks"],
//       ':remarks_date' => date('Y-m-d H:i:s'),
//       ':itsup' => $tchnum
//     ));

//     $makecom = $connection->prepare("
//     INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) 
//    VALUES (:ticket_no, :comment_details, :comment_date, :userId )
//   ");

//   $tickhisres = $connection->prepare("
//   INSERT INTO tbl_tickethist (ticket_no, date_updated) 
//  VALUES (:ticket_no, :date_updated)
// ");

//   }

//     $msgcntres = $connection->prepare("
//    UPDATE reports_msgcnt
//    SET msg_cnt = :msg_cnt
//   WHERE ticket_no = :ticket_no
//   ");
//   $makemsgcnt= $msgcntres->execute(
//     array(

//       ':ticket_no' => $_POST["ticket_no"],
//       ':msg_cnt' => '0'

//     ));

//    if(!empty($result)) {
//       $resasgn = $connection->prepare("
//       INSERT INTO tbl_notif (ticket_no, store, itsup, notif_data, notif_val, notif_date, assigned_by)
//       VALUES (:ticket_no, :store, :itsup, :notif_data, :notif_val, :notif_date, :assigned_by)");
//       $assigned= $resasgn->execute(
//     array(

//      ':ticket_no' => $_POST["ticket_no"],
//      ':store' => $_POST["store"],
//       ':itsup' => $_POST["itsup"],
//       ':notif_data' => "New Ticket"." ".$_POST["ticket_no"]." ". "Has been assigned.",
//       ':notif_val' => '1',
//       ':notif_date' => date('Y-m-d H:i:s'),
//       ':assigned_by' => $userid
     
//     ));
//  }
//     //  echo 'Data has been updated';

//       $addmsg=   array(
//     ':comment_details' => $_POST["admsg"],
//    ) ;

//  if(!empty($addmsg))
//   {

//     $makecom = $connection->prepare("
//     INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) 
//    VALUES (:ticket_no, :comment_details, :comment_date, :userId )
//   ");

//   $remarkres= $makecom->execute(
//     array(

//      ':ticket_no' => $_POST["ticket_no"],
//       ':comment_details' => $_POST["admsg"],
//       ':comment_date' => date('Y-m-d H:i:s'),
//       ':userId' => $_POST["u_id"]
//     ));

    
//   $nmsgcntres = $connection->prepare("
//    UPDATE reports_newmsg
//    SET nmsg_stat = :nmsg_stat
//   WHERE ticket_no = :ticket_no
//   ");
//   $nmakemsgcnt= $nmsgcntres->execute(
//     array(

//       ':ticket_no' => $_POST["ticket_no"],
//       ':nmsg_stat' => '2'

//     ));

//    // echo 'Data has been updated';
//  }


//  // ticket_trail

//  if(!empty($addmsg))
//  {

//   $tickhisres = $connection->prepare("
//   INSERT INTO tbl_tickethist (ticket_no, date_updated, status, userID) 
//   VALUES (:ticket_no, :date_updated, :status, :userID )
// ");
// $tickhisres1= $tickhisres->execute(
//   array(

//     ':ticket_no' => $_POST["ticket_no"] ,
//     ':date_updated' => date('Y-m-d H:i:s'),
//     ':status' => $_POST["status"],
//     ':userID' => $_POST["u_id"]

//   ));

// }




// } // old code working without transfer feature......
if ($_POST["operation"] == "Save and Reply") { 
    try {
        if (ob_get_length() !== false) {
            ob_clean();
        }

        $connection->beginTransaction();
        $computed_ticket = $_POST["ticket_no"];
        $userId          = $_POST["u_id"] ?? $_SESSION["user_id"] ?? ""; 
        $tchnum          = $_POST["u_id"] ?? ""; 
        $userid          = $_POST["u_id"] ?? ""; 

        $optbrval = $_POST["store"];
        $optval   = $_POST["itsup"];
        $optcval  = $_POST["cat"];
        $optsval  = $_POST["sub_num"];
        $opclbval = $_POST["close_by"];

        $is_transfer = (isset($_POST['is_transfer']) && $_POST['is_transfer'] == '1') ? 1 : 0;

        if (($optbrval == '0') || ($optval == '0') || ($optcval == '0') || ($optsval == '0') || ($opclbval == '0')) {
            $brid   = "";
            $itsup  = "";
            $cat_id = "";
            $sub_id = "";
            $clby   = "";
            $ispid  = "";

            $data = array(
                ':ticket_no'     => $_POST["ticket_no"],
                ':date_created'  => !empty($_POST["date_createdx"]) ? date('Y-m-d H:i:s', strtotime($_POST["date_createdx"])) : null,
                ':via'           => $_POST["via"],
                ':status'        => $_POST["status"],
                ':date_closed'   => !empty($_POST["date_closed"]) ? date('Y-m-d H:i:s', strtotime($_POST["date_closed"])) : null,
                ':remarks'       => $_POST["remarks"],
                ':refNo'         => $_POST["refNo"],
                ':date_refNo'    => !empty($_POST["date_refNo"]) ? date('Y-m-d H:i:s', strtotime($_POST["date_refNo"])) : null,
                ':is_transfer'   => $is_transfer
            );
        } else {
            $brid   = "store = :store,";
            $itsup  = "itsup = :itsup,";
            $cat_id = "cat_id = :cat_id,";
            $sub_id = "sub_id = :sub_id,";
            $clby   = "close_by = :close_by,";
            $ispid  = "isp_id = :isp_id,";

            $data = array(
                ':ticket_no'     => $_POST["ticket_no"],
                ':store'         => $_POST["store"],
                ':date_created'  => !empty($_POST["date_createdx"]) ? date('Y-m-d H:i:s', strtotime($_POST["date_createdx"])) : null,
                ':via'           => $_POST["via"],
                ':status'        => $_POST["status"],
                ':itsup'         => $_POST["itsup"],
                ':cat_id'        => $_POST["cat"],
                ':sub_id'        => $_POST["sub_num"],
                ':isp_id'        => '0',
                ':refNo'         => $_POST["refNo"],
                ':date_refNo'    => !empty($_POST["date_refNo"]) ? date('Y-m-d H:i:s', strtotime($_POST["date_refNo"])) : null,
                ':date_closed'   => !empty($_POST["date_closed"]) ? date('Y-m-d H:i:s', strtotime($_POST["date_closed"])) : null,
                ':close_by'      => $_POST["close_by"],
                ':remarks'       => $_POST["remarks"],
                ':is_transfer'   => $is_transfer
            );
        }

        $statement = $connection->prepare(
            "UPDATE reports
             SET ticket_no = :ticket_no,
                 $brid
                 date_created = :date_created,
                 via = :via,
                 status = :status,
                 $itsup
                 $cat_id
                 $sub_id
                 $ispid
                 refNo = :refNo,
                 date_refNo = :date_refNo,
                 date_closed = :date_closed,
                 $clby
                 remarks = :remarks,
                 is_transfer = :is_transfer
             WHERE ticket_no = :ticket_no"
        );
        $result = $statement->execute($data);

        $statement2 = $connection->prepare("
            UPDATE tbl_notif 
            SET 
                store      = :store, 
                itsup      = :itsup, 
                notif_data = :notif_data, 
                notif_val  = :notif_val, 
                notif_date = :notif_date
            WHERE ticket_no = :ticket_no
        ");

        $statement2->execute(array(
            ':ticket_no'  => $computed_ticket,
            ':store'      => $_SESSION["str_num"] ?? "",
            ':itsup'      => $userId,
            ':notif_data' => "Ticket $computed_ticket is On Process.",
            ':notif_val'  => '0',
            ':notif_date' => date('Y-m-d H:i:s')
        ));

        if (isset($_POST['itsup']) && !empty($_POST['itsup'])) {
            $updateReassign = $connection->prepare("
                UPDATE tbl_reassigned 
                SET nw_sup = :nw_sup,
                    date_rasigned = :date_rasigned,
                    r_remarks = :r_remarks
                WHERE ticket_no = :ticket_no 
                  AND (nw_sup IS NULL OR nw_sup = '' OR nw_sup = '0')
            ");
            
            $updateReassign->execute(array(
                ':nw_sup'        => $_POST["itsup"], 
                ':date_rasigned' => date('Y-m-d H:i:s'),
                ':r_remarks'     => $_POST["remarks"],
                ':ticket_no'     => $_POST["ticket_no"]
            ));
        }

        if (!empty($result)) {
            $restat = $connection->prepare("
                INSERT INTO reports_remarks (ticket_no, remarks_detail, remarks_date, itsup) 
                VALUES (:ticket_no, :remarks_detail, :remarks_date, :itsup )
            ");

            $restat->execute(array(
                ':ticket_no'      => $_POST["ticket_no"],
                ':remarks_detail' => $_POST["remarks"],
                ':remarks_date'   => date('Y-m-d H:i:s'),
                ':itsup'          => $tchnum
            ));
        }

        $msgcntres = $connection->prepare("
            UPDATE reports_msgcnt
            SET msg_cnt = :msg_cnt
            WHERE ticket_no = :ticket_no
        ");
        $msgcntres->execute(array(
            ':ticket_no' => $_POST["ticket_no"],
            ':msg_cnt'   => '0'
        ));

        if (!empty($result)) {
            $resasgn = $connection->prepare("
                INSERT INTO tbl_notif (ticket_no, store, itsup, notif_data, notif_val, notif_date, assigned_by)
                VALUES (:ticket_no, :store, :itsup, :notif_data, :notif_val, :notif_date, :assigned_by)
            ");

            $resasgn->execute(array(
                ':ticket_no'   => $_POST["ticket_no"],
                ':store'       => $_POST["store"],
                ':itsup'       => $_POST["itsup"],
                ':notif_data'  => "New Ticket ".$_POST["ticket_no"]." Has been assigned.",
                ':notif_val'   => '0',
                ':notif_date'  => date('Y-m-d H:i:s'),
                ':assigned_by' => $userid
            ));
        }

        if (!empty($_POST["admsg"])) {
            $makecom = $connection->prepare("
                INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) 
                VALUES (:ticket_no, :comment_details, :comment_date, :userId )
            ");

            $makecom->execute(array(
                ':ticket_no'       => $_POST["ticket_no"],
                ':comment_details' => $_POST["admsg"],
                ':comment_date'    => date('Y-m-d H:i:s'),
                ':userId'          => $_POST["u_id"]
            ));

            $nmsgcntres = $connection->prepare("
                UPDATE reports_newmsg
                SET nmsg_stat = :nmsg_stat
                WHERE ticket_no = :ticket_no
            ");

            $nmsgcntres->execute(array(
                ':ticket_no' => $_POST["ticket_no"],
                ':nmsg_stat' => '2'
            ));
            $tickhisres = $connection->prepare("
                INSERT INTO tbl_tickethist (ticket_no, date_updated, status, userID) 
                VALUES (:ticket_no, :date_updated, :status, :userID )
            ");

            $tickhisres->execute(array(
                ':ticket_no'    => $_POST["ticket_no"],
                ':date_updated' => date('Y-m-d H:i:s'),
                ':status'       => $_POST["status"],
                ':userID'       => $_POST["u_id"]
            ));
        }

        if (!empty($result) && $is_transfer === 1) {
            $checkTransfer = $connection->prepare("
                SELECT transfer_id
                FROM tbl_reports_transfer_logs
                WHERE ticket_no = :ticket_no
                LIMIT 1
            ");
            $checkTransfer->execute(array(
                ':ticket_no' => $_POST["ticket_no"]
            ));
            $existingTransfer = $checkTransfer->fetch(PDO::FETCH_ASSOC);

            $insertTransfer = $connection->prepare("
                INSERT INTO tbl_reports_transfer_logs
                (ticket_no, store, itsup, cat_id, sub_id, status, remarks, created_by, created_at, deptsel)
                VALUES
                (:ticket_no, :store, :itsup, :cat_id, :sub_id, :status, :remarks, :created_by, :created_at, :deptsel)
            ");
            $insertTransfer->execute(array(
                ':ticket_no'  => $_POST["ticket_no"],
                ':store'      => $_POST["store"],
                ':itsup'      => $_POST["itsup"],
                ':cat_id'     => $_POST["cat"],
                ':sub_id'     => $_POST["sub_num"],
                ':status'     => $_POST["status"],
                ':remarks'    => $_POST["remarks"],
                ':created_by' => $_POST["u_id"],
                ':created_at' => date('Y-m-d H:i:s'),
                 ':deptsel'     => '2'
            ));

            if ($existingTransfer) {
                $insertReassigned = $connection->prepare("
                    INSERT INTO tbl_reassigned 
                    (ticket_no, date_created, itsup, nw_sup, r_remarks, date_rasigned, deptsel) 
                    VALUES 
                    (:ticket_no, :date_created, :itsup, :nw_sup, :r_remarks, :date_rasigned, :deptsel)
                ");

                $insertReassigned->execute(array(
                    ':ticket_no'     => $_POST["ticket_no"],
                    ':date_created'  => date('Y-m-d H:i:s'),
                    ':itsup'         => $_POST["itsup"],
                    ':nw_sup'        => $_POST["nw_sup"] ?? '',       
                    ':r_remarks'     => $_POST["remarks"],      
                    ':date_rasigned' => date('Y-m-d H:i:s'),  
                    ':deptsel'       => '2'      
                ));
            } else {
                $insertTransfer2 = $connection->prepare("
                   INSERT INTO tbl_reassigned
                    (date_created)
                    VALUES
                    (:date_created)
                ");

                $insertTransfer2->execute(array(
                      ':date_created' => date('Y-m-d H:i:s')
                ));
            }
        }
       
        $connection->commit();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'success',
            'message' => 'Ticket saved successfully.'
        ]);
        exit;
    } catch (Exception $e) {
        if ($connection->inTransaction()) {
            $connection->rollBack();
        }
        if (ob_get_length() !== false) {
            ob_clean();
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
        exit;
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
