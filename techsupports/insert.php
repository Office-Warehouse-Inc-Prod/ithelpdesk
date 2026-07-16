

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


}
if (isset($_POST["operation"]) && $_POST["operation"] == "submit_request") {
    try {
        $connection->beginTransaction();
        $statement = $connection->prepare("
            INSERT INTO asset_requests (
                ticket_no, requested_db, requested_by, ticket_created, 
                item_code, description, serial_number, purpose_of_request, technical_workoutput,
                item_received_by, date_received, status, date_submitted
            ) VALUES (
                :ticket_no, :requested_db, :requested_by, :ticket_created, 
                :item_code, :description, :serial_number, :purpose_of_request, :technical_workoutput, 
                :item_received_by, :date_received, :status, :date_submitted
            )
        ");

        $ticketNo = $_POST['ticket_no'] ?? $computed_ticket ?? null;
        $currentDate = date('Y-m-d H:i:s');

        $result = $statement->execute([
            ':ticket_no'          => $ticketNo,
            ':requested_db'       => $_POST['requesting_dept'] ?? null,
            ':requested_by'       => $_POST['requesting_employee'] ?? null,
            ':ticket_created'     => $_POST['date_created'] ?? null,
            ':item_code'          => $_POST['item_code'] ?? null,
            ':description'        => $_POST['description'] ?? null,
            ':serial_number'      => $_POST['serial_number'] ?? null,
            ':purpose_of_request' => $_POST['purpose_of_request'] ?? null,
             ':technical_workoutput' => $_POST['technical_workoutput'] ?? null,
            ':item_received_by'   => $_POST['received_by'] ?? null,
            ':date_received'      => $_POST['date_received'] ?? null,
            ':status'             => 'SUBMITTED',
            ':date_submitted'     => $currentDate
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

        if ($result && $result2) {
            $connection->commit();
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
                technical_workoutput = :technical_workoutput,
                  description = :description,
                    serial_number = :serial_number,
               
                date_received = :date_received
      
            WHERE ticket_no = :ticket_no
        ");

        $result = $statement->execute([
            ':technical_workoutput'      => $_POST['technical_workoutput'],
            ':serial_number'      => $_POST['serial_number'],
            ':date_received'      => $_POST['date_received'],
            ':ticket_no'          => $_POST['ticket_no']
        ]);

        if($result){
            echo json_encode(["status" => "success", "message" => "Request updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update the database."]);
        }

    } catch(PDOException $e) {
        echo json_encode(["status" => "error", "message" => "SQL Error: " . $e->getMessage()]);
    }
    exit(); // Ensure the script stops here so it doesn't output trailing HTML
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