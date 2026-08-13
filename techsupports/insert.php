

<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if ($_SESSION['login']!='true'){
    header("Location: index.php");
    exit();
}

$tchnum = $_SESSION['tech_id'];
$userid = $_SESSION['user_id'];
$supfrstname = $_SESSION['fname'];
$suplstname= $_SESSION['lstname'];
date_default_timezone_set("Asia/Manila");

include('db.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; 

if(isset($_POST["operation"]))
{

 if($_POST["operation"] == "Add")
 {
    header('Content-Type: application/json');

    try {
        $qry = $connection->prepare(" SELECT ticket_no FROM counter");
        $qry->execute();
        $res = $qry->fetch(PDO::FETCH_ASSOC);
        $ticknum = $res['ticket_no'] + 1;

        $statement = $connection->prepare("
         INSERT INTO reports (ticket_no, store, date_created, subject,  via, status, itsup, cat_id, sub_id, isp_id, refNo, date_refNo, date_closed, close_by, remarks, deptsel) 
         VALUES (:ticket_no, :store, :date_created, :subject, :via, :status, :itsup, :cat_id, :sub_id, :isp_id, :refNo, :date_refNo, :date_closed, :close_by, :remarks, :deptsel)
        ");
        $result = $statement->execute(
         array(
          ':ticket_no' => $ticknum,
          ':store' => $_POST["store"],
          ':date_created' => date('Y-m-d H:i:s',strtotime($_POST["date_created"])),
          ':subject' => strtoupper($_POST["subjct"]),
          ':via' => $_POST["via"],
          ':status' => $_POST["status"],
          ':itsup' => $_POST["it_num"],
          ':cat_id' => $_POST["cat"],
          ':sub_id' => $_POST["sub"],
          ':isp_id' => $_POST["isp"],
          ':refNo' => $_POST["refNo"],
          ':date_refNo' => date('Y-m-d H:i:s',strtotime($_POST["date_refNo"])),
          ':date_closed' => date('Y-m-d H:i:s',strtotime($_POST["date_closed"])),
          ':close_by' => $_POST["close_by"],
          ':remarks' => $_POST["remarks"],
          ':deptsel' => '1'
         )
        );

        $restat = $connection->prepare("
          INSERT INTO reports_remarks (ticket_no, remarks_detail, remarks_date, itsup) 
         VALUES (:ticket_no, :remarks_detail, :remarks_date, :itsup )
        ");
        $remarkres = $restat->execute(
          array(
            ':ticket_no' => $ticknum,
            ':remarks_detail' => $_POST["remarks"],
            ':remarks_date' => date('Y-m-d H:i:s'),
            ':itsup' => $tchnum
          )
        );

        $resasgn1 = $connection->prepare("
          INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) 
          VALUES (:ticket_no, :comment_details, :comment_date, :userId )
        ");
        $assigned1 = $resasgn1->execute(
          array(
            ':ticket_no' => $ticknum,
            ':comment_details' => $_POST["admsg"],
            ':comment_date' => date('Y-m-d H:i:s'),
            ':userId' => $userid
          )
        );

        $msgcntres1 = $connection->prepare("
          INSERT INTO reports_msgcnt (ticket_no, msg_cnt)
          VALUES (:ticket_no, :msg_cnt)
        ");
        $makemsgcnt1 = $msgcntres1->execute(
          array(
            ':ticket_no' => $ticknum,
            ':msg_cnt' => '0'
          )
        );

        if (!empty($result)) {
          $statement = $connection->prepare("UPDATE counter SET ticket_no = :ticket_no");
          $statement->execute(array(':ticket_no' => $ticknum));
          echo json_encode(["status" => "success", "message" => "Add data success.", "ticket_no" => $ticknum]);
        } else {
          echo json_encode(["status" => "error", "message" => "Add failed."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
    exit();
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
    ':store' => $_POST["str_num"],
    ':date_created' => date('Y-m-d H:i:s',strtotime($_POST["date_created"])),
    // ':concern' => $_POST["concern"],
    ':via' => $_POST["via"],
    ':status' => $_POST["status"],
    ':itsup' => $_POST["it_num"],
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

    $makecom = $connection->prepare("
    INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) 
   VALUES (:ticket_no, :comment_details, :comment_date, :userId )
  ");
  }
     echo 'Data has been updated';


}if (isset($_POST["operation"]) && $_POST["operation"] == "submit_request") {
    try {
        $connection->beginTransaction();
        $statement = $connection->prepare("
            INSERT INTO asset_requests (
                ticket_no, requested_db, requested_by, ticket_created, 
                item_code, description, serial_number, purpose_of_request, 
                item_received_by, date_received, is_technical, status, date_submitted
            ) VALUES (
                :ticket_no, :requested_db, :requested_by, :ticket_created, 
                :item_code, :description, :serial_number, :purpose_of_request, 
                :item_received_by, :date_received, :is_technical, :status, :date_submitted
            )
        ");

        $ticketNo     = $_POST['ticket_no'] ?? $computed_ticket ?? null;
        $currentDate  = date('Y-m-d H:i:s');
        
        $result = $statement->execute([
            ':ticket_no'           => $ticketNo,
            ':requested_db'        => $_POST['requesting_dept'] ?? null,
            ':requested_by'        => $_POST['requesting_employee'] ?? null,
            ':ticket_created'      => $_POST['date_created'] ?? null,
            ':item_code'           => $_POST['item_code'] ?? null,
            ':description'         => $_POST['description'] ?? null,
            ':serial_number'       => $_POST['serial_number'] ?? null,
            ':purpose_of_request'  => $_POST['purpose_of_request'] ?? null,
           
            ':item_received_by'    => $_POST['received_by'] ?? null,
            ':date_received'       => $_POST['date_received'] ?? null,
            ':is_technical'        => '1',
            ':status'              => 'SUBMITTED',
            ':date_submitted'      => $currentDate
        ]);

        $statement2 = $connection->prepare("
            INSERT INTO tbl_notif (
                ticket_no, store, itsup, notif_data, notif_val, notif_date
            ) VALUES (
                :ticket_no, :store, :itsup, :notif_data, :notif_val, :notif_date
            )
        ");

        $result2 = $statement2->execute([
            ':ticket_no'  => $computed_ticket ?? $ticketNo,
            ':store'      => $_SESSION["str_num"] ?? "",
            ':itsup'      => $_SESSION["tech_id"] ?? "",
            ':notif_data' => "Fixed asset " . ($computed_ticket ?? $ticketNo) . " submitted and for approval",
            ':notif_val'  => '5',
            ':notif_date' => $currentDate
        ]);

        $statement3 = $connection->prepare("
            INSERT INTO fixed_asset_techoutput (
                ticket_no, problem_reported, verification_findings, work_done, status_workoutput, recommendation
            ) VALUES (
                :ticket_no, :problem_reported, :verification_findings, :work_done, :status_workoutput, :recommendation
            )
        ");

        $result3 = $statement3->execute([
            ':ticket_no'             => $computed_ticket ?? $ticketNo,
            ':problem_reported'      => $_POST['problem_reported'] ?? null,
            ':verification_findings' => $_POST['verification_findings'] ?? null,
            ':work_done'             => $_POST['work_done'] ?? null,
            ':status_workoutput'     => $_POST['status_workoutput'] ?? null,
            ':recommendation'        => $_POST['recommendation'] ?? null
        ]);

        if ($result && $result2 && $result3) {
            $connection->commit();
            
            $stmtEmail = $connection->prepare("SELECT email FROM fixed_asset_email WHERE val = '1' LIMIT 1");
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
                
                $display_dept     = $ticketData['str_name'] ?? $_POST['requesting_dept'] ?? 'N/A';
                $display_user     = $ticketData['full_name'] ?? $_POST['requesting_employee'] ?? 'N/A';
                $display_receiver = $ticketData['it_desc'] ?? $_POST['received_by'] ?? 'N/A';
                $display_date_rec = $ticketData['date_received'] ?? $_POST['date_received'] ?? 'N/A';

                try {
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
                    $mail->Subject = "New Fixed Asset Request: {$ticketNo}";
                    
                    $mailBody = '
                    <html>
                    <body style="margin:0;padding:20px;background:#f4f6f9;font-family:Arial,sans-serif;">
                        <table width="700" align="center" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #cabb89;border-radius:8px;overflow:hidden;">
                            <tr>
                                <td style="background:#E1AD01;color:#ffffff;padding:18px 24px;font-size:20px;font-weight:bold;">
                                    Helpdesk AI: New Asset Request
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:24px;font-size:14px;color:#333;">
                                    <p>Good day,</p>
                                    <p>A new fixed asset request has been submitted for approval. Please see the details below:</p>
                                    
                                    <table cellpadding="8" cellspacing="0" width="100%" style="border-collapse:collapse;margin-top:10px;">
                                        <tr style="background:#f3e8c3;">
                                            <td style="border:1px solid #cabb89;width:180px;"><strong>Ticket No.</strong></td>
                                            <td style="border:1px solid #cabb89;">' . htmlspecialchars($ticketNo) . '</td>
                                        </tr>
                                        <tr>
                                            <td style="border:1px solid #cabb89;"><strong>Status</strong></td>
                                            <td style="border:1px solid #cabb89;">SUBMITTED</td>
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
                                            <td style="border:1px solid #cabb89;"><strong>Assigned Tech Support</strong></td>
                                            <td style="border:1px solid #cabb89;">' . htmlspecialchars($display_receiver) . '</td>
                                        </tr>
                                        <tr>
                                            <td style="border:1px solid #cabb89;"><strong>Date Received</strong></td>
                                            <td style="border:1px solid #cabb89;">' . htmlspecialchars($display_date_rec) . '</td>
                                        </tr>
                                        <tr style="background:#f3e8c3;">
                                            <td style="border:1px solid #cabb89;"><strong>Item Code</strong></td>
                                            <td style="border:1px solid #cabb89;">' . htmlspecialchars($_POST['item_code'] ?? '') . '</td>
                                        </tr>
                                        <tr>
                                            <td style="border:1px solid #cabb89;"><strong>Description</strong></td>
                                            <td style="border:1px solid #cabb89;">' . htmlspecialchars($_POST['description'] ?? '') . '</td>
                                        </tr>
                                        <tr style="background:#f3e8c3;">
                                            <td style="border:1px solid #cabb89;"><strong>Serial Number</strong></td>
                                            <td style="border:1px solid #cabb89;">' . htmlspecialchars($_POST['serial_number'] ?? '') . '</td>
                                        </tr>
                                        <tr>
                                            <td style="border:1px solid #cabb89;"><strong>Purpose of Request</strong></td>
                                            <td style="border:1px solid #cabb89;">' . nl2br(htmlspecialchars($_POST['purpose_of_request'] ?? '')) . '</td>
                                        </tr>
                                        
                                    </table>
                                      
                                   
                                    <h1><strong>Technical Work Output</strong></h1>
                                       

                                    <table cellpadding="8" cellspacing="0" width="100%" style="border-collapse:collapse;margin-top:10px;">
                    
                                        
                                        <tr>
                                            <td style="border:1px solid #cabb89;"><strong>Problem Reported</strong></td>
                                            <td style="border:1px solid #cabb89;">' . nl2br(htmlspecialchars($_POST['problem_reported'] ?? '')) . '</td>
                                        </tr>
                                        <tr>
                                            <td style="border:1px solid #cabb89;"><strong>Verification/Findings</strong></td>
                                            <td style="border:1px solid #cabb89;">' . nl2br(htmlspecialchars($_POST['verification_findings'] ?? '')) . '</td>
                                        </tr>
                                        <tr>
                                            <td style="border:1px solid #cabb89;"><strong>Work Done/Technical Solutions Provided</strong></td>
                                            <td style="border:1px solid #cabb89;">' . nl2br(htmlspecialchars($_POST['work_done'] ?? '')) . '</td>
                                        </tr>
                                        <tr>
                                            <td style="border:1px solid #cabb89;"><strong>Status/Work Output</strong></td>
                                            <td style="border:1px solid #cabb89;">' . htmlspecialchars($_POST['status_workoutput'] ?? '') . '</td>
                                        </tr>
                                        <tr>
                                            <td style="border:1px solid #cabb89;"><strong>Recommendations/Suggestions</strong></td>
                                            <td style="border:1px solid #cabb89;">' . nl2br(htmlspecialchars($_POST['recommendation'] ?? '')) . '</td>
                                        </tr>       
                                       <tr>
                                            <td style="border:1px solid #cabb89;"><strong>Date Created</strong></td>
                                            <td style="border:1px solid #cabb89;">' . htmlspecialchars($_POST['date_created'] ?? '') . '</td>
                                        </tr>
                                    </table>
                                    
                                    <p style="margin-top:20px;">Please log in to the <strong>OWI Helpdesk</strong> to review and approve this asset request.</p>
                                    <div style="text-align:center;margin-top:25px;">
                                        <a href="https://owihelpdesk.officewarehouse.com.ph" 
                                           style="background:#cabb89;color:#ffffff;padding:16px 24px;text-decoration:none;border-radius:5px;display:inline-block;font-weight:bold;">
                                            Open OWI Helpdesk
                                        </a>
                                    </div>
                                    <p style="margin-top:20px;">Thank you.</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="background: #cabb89;color:#ffffff;text-align:center;padding:10px;font-size:12px;">
                                    OWI Helpdesk System Notification
                                </td>
                            </tr>
                        </table>
                    </body>
                    </html>';

                    $mail->Body = $mailBody;
                    $mail->AltBody = "New Fixed Asset Request: {$ticketNo}. Requested By: {$display_user} ({$display_dept}). Item Received By: {$display_receiver}.";

                    $mail->send();
                    
                } catch (Exception $e) {
                }
            }
            
            echo "success";
            
        } else {
            $connection->rollBack();
            echo "SQL Error: Execution failed.";
        }

    } catch (PDOException $e) {
        if ($connection->inTransaction()) {
            $connection->rollBack();
        }
        echo "SQL Error: " . $e->getMessage();
        exit();
    }
}
if(isset($_POST["operation"]) && $_POST["operation"] == "update_request") {
    try {
        $statement = $connection->prepare("
            UPDATE asset_requests
            SET
               item_code = :item_code,
              
               description = :description,
               serial_number = :serial_number,
               date_received = :date_received
            WHERE ticket_no = :ticket_no
        ");

        $result = $statement->execute([
            ':item_code'      => $_POST['item_code'],
           
            ':description'      => $_POST['description'],
            ':serial_number'      => $_POST['serial_number'],
            ':date_received'      => $_POST['date_received'],
            ':ticket_no'          => $_POST['ticket_no']
        ]);

        $statement2 = $connection->prepare("
            UPDATE fixed_asset_techoutput
            SET
               problem_reported = :problem_reported,
               verification_findings = :verification_findings,
               work_done = :work_done,
               status_workoutput = :status_workoutput,
               recommendation = :recommendation
            WHERE ticket_no = :ticket_no
        ");

        $result2 = $statement2->execute([
            ':problem_reported'      => $_POST['problem_reported'],
            ':verification_findings'      => $_POST['verification_findings'],
            ':work_done'      => $_POST['work_done'],
            ':status_workoutput'      => $_POST['status_workoutput'],
            ':recommendation'      => $_POST['recommendation'],
            ':ticket_no'          => $_POST['ticket_no']
        ]);

        if($result && $result2) {
            echo json_encode(["status" => "success", "message" => "Request updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update the database."]);
        }

    } catch(PDOException $e) {
        echo json_encode(["status" => "error", "message" => "SQL Error: " . $e->getMessage()]);
    }
    exit(); 
}
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
            ':notif_data' => "You added a remarks on fixed asset ticket no " . $ticketNo,
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

if (isset($_POST["operation"]) && $_POST["operation"] == "Save and Reply") {
    header('Content-Type: application/json');

    function normalizeIntValue($value) {
        if (!isset($value)) {
            return null;
        }
        $value = trim((string)$value);
        return $value === '' ? null : $value;
    }

    try {
        $data = [
            ':ticket_no' => $_POST["ticket_no"],
            ':store' => normalizeIntValue($_POST["store"] ?? $_POST["str_num"] ?? null),
            ':date_created' => !empty($_POST["date_created"]) ? date('Y-m-d H:i:s', strtotime($_POST["date_created"])) : null,
            ':via' => $_POST["via"] ?? null,
            ':status' => $_POST["status"] ?? null,
            ':itsup' => normalizeIntValue($_POST["itsup"] ?? $_POST["it_num"] ?? null),
            ':cat_id' => normalizeIntValue($_POST["cat"] ?? null),
            ':sub_id' => normalizeIntValue($_POST["sub"] ?? $_POST["sub_num"] ?? null),
            ':isp_id' => normalizeIntValue($_POST["isp"] ?? $_POST["isp_num"] ?? null),
            ':refNo' => $_POST["refNo"] ?? null,
            ':date_refNo' => !empty($_POST["date_refNo"]) ? date('Y-m-d H:i:s', strtotime($_POST["date_refNo"])) : null,
            ':date_closed' => !empty($_POST["date_closed"]) ? date('Y-m-d H:i:s', strtotime($_POST["date_closed"])) : null,
            ':close_by' => normalizeIntValue($_POST["close_by"] ?? null),
            ':remarks' => $_POST["remarks"] ?? null
        ];

        $sql = "UPDATE reports SET store = :store, date_created = :date_created, via = :via, status = :status, itsup = :itsup, cat_id = :cat_id, sub_id = :sub_id, isp_id = :isp_id, refNo = :refNo, date_refNo = :date_refNo, date_closed = :date_closed, close_by = :close_by, remarks = :remarks WHERE ticket_no = :ticket_no";
        $statement = $connection->prepare($sql);
        $result = $statement->execute($data);

        if (!$result) {
            $errorInfo = $statement->errorInfo();
            echo json_encode(["status" => "error", "message" => "Update failed: " . ($errorInfo[2] ?? 'Unknown database error')]);
            exit();
        }

        $restat = $connection->prepare(
            "INSERT INTO reports_remarks (ticket_no, remarks_detail, remarks_date, itsup) VALUES (:ticket_no, :remarks_detail, :remarks_date, :itsup)"
        );
        $restat->execute([
            ':ticket_no' => $_POST["ticket_no"],
            ':remarks_detail' => $_POST["remarks"],
            ':remarks_date' => date('Y-m-d H:i:s'),
            ':itsup' => $tchnum
        ]);

        if (!empty(trim($_POST["admsg"]))) {
            $makecom = $connection->prepare(
                "INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) VALUES (:ticket_no, :comment_details, :comment_date, :userId)"
            );
            $makecom->execute([
                ':ticket_no' => $_POST["ticket_no"],
                ':comment_details' => $_POST["admsg"],
                ':comment_date' => date('Y-m-d H:i:s'),
                ':userId' => $_POST['u_id']
            ]);

            $nmsgcntres = $connection->prepare(
                "UPDATE reports_newmsg SET nmsg_stat = :nmsg_stat WHERE ticket_no = :ticket_no"
            );
            $nmsgcntres->execute([
                ':ticket_no' => $_POST["ticket_no"],
                ':nmsg_stat' => '1'
            ]);

            $resasgn = $connection->prepare(
                "INSERT INTO tbl_notif (ticket_no, store, itsup, notif_data, notif_val, notif_date, assigned_by) VALUES (:ticket_no, :store, :itsup, :notif_data, :notif_val, :notif_date, :assigned_by)"
            );
            $resasgn->execute([
                ':ticket_no' => $_POST["ticket_no"],
                ':store' => $_POST["store"] ?? $_POST["str_num"] ?? null,
                ':itsup' => $_POST["itsup"] ?? $_POST["it_num"] ?? null,
                ':notif_data' => ($_POST['itsup'] ?? $_POST['it_num'] ?? 'Support') . " add a new comment on ticket number: " . $_POST['ticket_no'] . ": " . $_POST['admsg'],
                ':notif_val' => '3',
                ':notif_date' => date('Y-m-d H:i:s'),
                ':assigned_by' => $userid
            ]);
        }

        echo json_encode(["status" => "success", "message" => "Data has been updated"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
    exit();
} // end 
?>