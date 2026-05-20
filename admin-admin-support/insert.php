<?php

session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] != 'true') {
    header("Location: index.php");
    exit();
}

$tchnum = isset($_SESSION['tech_id']) ? $_SESSION['tech_id'] : '';
$userid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
date_default_timezone_set("Asia/Manila");

include('db.php');

// statOps checks (existing logic)
if (isset($_POST["statOps"])) {
    if ($_POST["statOps"] == "SUBJECT FOR CLOSING") {
        $statement = $connection->prepare(
            "UPDATE reports
            SET `status` = 'CLOSED', confirm_close_date = :cfdate
            WHERE ticket_no = :ticket_no"
        );
        $makemsgcnt = $statement->execute(array(
            ':ticket_no' => $_POST["nticknum"],
            ':cfdate'    => date('Y-m-d H:i:s')
        ));
        echo json_encode(array("status" => "success"));
        exit();
    }

    if ($_POST["statOps"] == "READY FOR PULL OUT") {
        $statement = $connection->prepare(
            "UPDATE reports
            SET `status` = 'CONFIRM PULL OUT'
            WHERE ticket_no = :ticket_no"
        );
        $makemsgcnt = $statement->execute(array(
            ':ticket_no' => $_POST["nticknum"]
        ));
        echo json_encode(array("status" => "success"));
        exit();
    }

    if ($_POST["statOps"] == "DIRECT PULL OUT") {
        $statement = $connection->prepare(
            "UPDATE reports
            SET `status` = 'CONFIRM PICK UP'
            WHERE ticket_no = :ticket_no"
        );
        $makemsgcnt = $statement->execute(array(
            ':ticket_no' => $_POST["nticknum"]
        ));
        echo json_encode(array("status" => "success"));
        exit();
    }

    if ($_POST["statOps"] == "RETURN TO STORE") {
        $statement = $connection->prepare(
            "UPDATE reports
            SET `status` = 'ITEM-RECEIVED'
            WHERE ticket_no = :ticket_no"
        );
        $makemsgcnt = $statement->execute(array(
            ':ticket_no' => $_POST["nticknum"]
        ));
        echo json_encode(array("status" => "success"));
        exit();
    }

    if ($_POST["statOps"] == "RETURN BY SUPPLIER") {
        $statement = $connection->prepare(
            "UPDATE reports
            SET `status` = 'CLOSED', confirm_close_date = :cfdate
            WHERE ticket_no = :ticket_no"
        );
        $makemsgcnt = $statement->execute(array(
            ':ticket_no' => $_POST["nticknum"],
            ':cfdate'    => date('Y-m-d H:i:s')
        ));
        echo json_encode(array("status" => "success"));
        exit();
    }
}

// standard operations
if (isset($_POST["operation"])) {
    if ($_POST["operation"] == "delete") {
        $IDx = $_POST['IDx'];
        $ticketx = $_POST['ticktx'];
        $statement = $connection->prepare(
            "DELETE FROM tbl_pditems WHERE id = '$IDx' AND ticket_no = '$ticketx'"
        );
        $statement->execute();
        echo "Deleted";
        exit();
    }

    if ($_POST["operation"] == "Add") {
        $qry = $connection->prepare("SELECT ticket_no FROM counter");
        $qry->execute();
        $res = $qry->fetch(PDO::FETCH_ASSOC); 
        $ticknum = $res['ticket_no'] + 1;

        $statement = $connection->prepare("
            INSERT INTO reports (ticket_no, store, date_created, subject, via, status, itsup, cat_id, sub_id, date_closed, close_by, remarks, isp_id, deptsel) 
            VALUES (:ticket_no, :store, :date_created, :subject, :via, :status, :itsup, :cat_id, :sub_id, :date_closed, :close_by, :remarks, :isp_id, :deptsel)
        ");
        $dcval = $_POST["date_created"];
        $dclval = $_POST["date_closed"];
        $datetime = date_create($dcval)->format('Y-m-d H:i:s');
        $datetimecl = date_create($dcval)->format('Y-m-d H:i:s');
        
        $result = $statement->execute(array(
            ':ticket_no'    => $ticknum,
            ':store'        => $_POST["store"],
            ':date_created' => $datetime,
            ':subject'      => strtoupper($_POST["subjct"]),
            ':via'          => $_POST["via"],
            ':status'       => $_POST["status"],
            ':itsup'        => $_POST["itsup"],
            ':cat_id'       => $_POST["cat"],
            ':sub_id'       => $_POST["sub"],
            ':date_closed'  => $datetimecl,
            ':close_by'     => $_POST["close_by"],
            ':remarks'      => ucfirst($_POST["remarks"]),
            ':isp_id'       => '0',
            ':deptsel'      => '2' // Admin Support Dept
        ));

        if (!empty($_POST['remarks'])) {
            $restat = $connection->prepare("
                INSERT INTO reports_remarks (ticket_no, remarks_detail, remarks_date, itsup) 
                VALUES (:ticket_no, :remarks_detail, :remarks_date, :itsup)
            ");
            $restat->execute(array(
                ':ticket_no'      => $ticknum,
                ':remarks_detail' => $_POST["remarks"],
                ':remarks_date'   => date('Y-m-d H:i:s'),
                ':itsup'          => $tchnum
            ));
        }

        if ($_POST['status'] == "OPEN") {
            $resasgn = $connection->prepare("
                INSERT INTO tbl_notif (ticket_no, store, itsup, notif_data, notif_val, notif_date, assigned_by)
                VALUES (:ticket_no, :store, :itsup, :notif_data, :notif_val, :notif_date, :assigned_by)
            ");
            $resasgn->execute(array(
                ':ticket_no'   => $ticknum,
                ':store'       => $_POST["store"],
                ':itsup'       => $_POST["itsup"],
                ':notif_data'  => "New Ticket " . $ticknum . " Has been assigned.",
                ':notif_val'   => '1',
                ':notif_date'  => date('Y-m-d H:i:s'),
                ':assigned_by' => $userid
            ));
        }

        if (!empty($_POST['admsg'])) {
            $resasgn1 = $connection->prepare("
                INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) 
                VALUES (:ticket_no, :comment_details, :comment_date, :userId)
            ");
            $resasgn1->execute(array(
                ':ticket_no'       => $ticknum,
                ':comment_details' => $_POST["admsg"],
                ':comment_date'    => date('Y-m-d H:i:s'),
                ':userId'          => $userid
            ));
        }

        if (!empty($result)) {
            $statement = $connection->prepare("UPDATE counter SET ticket_no = :ticket_no");
            $statement->execute(array(':ticket_no' => $ticknum));
            echo 'Data Inserted.';
        }
        exit();
    }

    if ($_POST["operation"] == "Edit" || $_POST["operation"] == "Save and Reply") {
        $optbrval = $_POST["store"];
        $optval   = $_POST["itsup"];
        $optcval  = $_POST["cat"];
        $optsval  = $_POST["sub_num"];
        $opclbval = $_POST["close_by"];

        $date_created_val = !empty($_POST["date_createdx"]) ? $_POST["date_createdx"] : (!empty($_POST["date_created"]) ? $_POST["date_created"] : date('Y-m-d H:i:s'));

        if (($optbrval == '0') || ($optval == '0') || ($optcval == '0') || ($optsval == '0') || ($opclbval == '0')) {
            $brid  = "";
            $itsup = "";
            $cat_id = "";
            $sub_id = "";
            $clby  = "";
            $ispid = "";
            $data = array(
                ':ticket_no'    => $_POST["ticket_no"],
                ':date_created' => date('Y-m-d H:i:s', strtotime($date_created_val)),
                ':via'          => $_POST["via"],
                ':status'       => $_POST["status"],
                ':date_closed'  => !empty($_POST["date_closed"]) ? date('Y-m-d H:i:s', strtotime($_POST["date_closed"])) : null,
                ':close_by'     => $_POST["close_by"],
                ':remarks'      => $_POST["remarks"],
                ':refNo'        => $_POST["refNo"],
                ':date_refNo'   => !empty($_POST["date_refNo"]) ? date('Y-m-d H:i:s', strtotime($_POST["date_refNo"])) : null
            );
        } 
        else {
            $brid   = "store = :store,";
            $itsup  = "itsup = :itsup,";
            $cat_id = "cat_id = :cat_id,";
            $sub_id = "sub_id = :sub_id,";
            $clby   = "close_by = :close_by,";
            $ispid  = "isp_id = :isp_id,";
            $data = array(
                ':ticket_no'    => $_POST["ticket_no"],
                ':store'        => $_POST["store"],
                ':date_created' => date('Y-m-d H:i:s', strtotime($date_created_val)),
                ':via'          => $_POST["via"],
                ':status'       => $_POST["status"],
                ':itsup'        => $_POST["itsup"],
                ':cat_id'       => $_POST["cat"],
                ':sub_id'       => $_POST["sub_num"],
                ':isp_id'       => '0',
                ':refNo'        => $_POST["refNo"],
                ':date_refNo'   => !empty($_POST["date_refNo"]) ? date('Y-m-d H:i:s', strtotime($_POST["date_refNo"])) : null,
                ':date_closed'  => !empty($_POST["date_closed"]) ? date('Y-m-d H:i:s', strtotime($_POST["date_closed"])) : null,
                ':close_by'     => $_POST["close_by"],
                ':remarks'      => $_POST["remarks"]
            );
        }

        $statement = $connection->prepare(
            "UPDATE reports
             SET ticket_no = :ticket_no, $brid date_created = :date_created, via = :via, 
                              status = :status, $itsup $cat_id $sub_id $ispid refNo = :refNo, date_refNo = :date_refNo, date_closed = :date_closed, $clby remarks = :remarks
             WHERE ticket_no = :ticket_no"
        );
        $result = $statement->execute($data);

        if ($_POST['it_num'] != $_POST['itsup']) {
            $reasgn = $connection->prepare("
                INSERT INTO tbl_reassigned (ticket_no, date_created, itsup, nw_sup, r_remarks, date_rasigned) 
                VALUES (:ticket_no, :date_created, :itsup, :nw_sup, :r_remarks, :date_rasigned)
            ");
            $reasgn->execute(array(
                ':ticket_no'     => $_POST["ticket_no"],
                ':date_created'  => date('Y-m-d H:i:s', strtotime($date_created_val)),
                ':itsup'         => $_POST["it_num"],
                ':nw_sup'        => $_POST["itsup"],
                ':r_remarks'     => $_POST["remarks"],
                ':date_rasigned' => date('Y-m-d H:i:s')
            ));
        }

        if (!empty($result)) {
            $restat = $connection->prepare("
                INSERT INTO reports_remarks (ticket_no, remarks_detail, remarks_date, itsup) 
                VALUES (:ticket_no, :remarks_detail, :remarks_date, :itsup)
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
                ':notif_data'  => "New Ticket " . $_POST["ticket_no"] . " Has been assigned.",
                ':notif_val'   => '1',
                ':notif_date'  => date('Y-m-d H:i:s'),
                ':assigned_by' => $userid
            ));
        }

        echo 'Data has been updated';

        if (!empty($_POST["admsg"])) {
            $makecom = $connection->prepare("
                INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) 
                VALUES (:ticket_no, :comment_details, :comment_date, :userId)
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
                ':nmsg_stat' => '1'
            ));

            // TICKET HISTORY
            $tickhisres = $connection->prepare("
                INSERT INTO tbl_tickethist (ticket_no, date_updated, status, userID) 
                VALUES (:ticket_no, :date_updated, :status, :userID)
            ");
            $tickhisres->execute(array(
                ':ticket_no'    => $_POST["ticket_no"],
                ':date_updated' => date('Y-m-d H:i:s'),
                ':status'       => $_POST["status"],
                ':userID'       => $_POST["u_id"]
            ));
        }
        exit();
    }

    if ($_POST["operation"] == "changepass") { 
        $qry = $connection->prepare("SELECT * FROM users WHERE id = $userid");
        $qry->execute();
        $res = $qry->fetch(PDO::FETCH_ASSOC); 
        $oldpass = $res['password'];
        $dcdeold_pass = base64_decode($oldpass);
        $newpass = $_POST['newpass'];
        if ($_POST["curpass"] == $dcdeold_pass && $_POST['newpass'] == $_POST['confrm_nwpass']) {
            $statement = $connection->prepare("UPDATE users SET `password` = :password WHERE id = $userid");
            $statement->execute(array(':password' => base64_encode($newpass)));
            echo "PASSWORD CHANGED";
        } else {
            echo "ERROR";
        }
        exit();
    }
}
?>