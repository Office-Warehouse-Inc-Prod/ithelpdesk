<?php
// session_start();
// require('../fpdf/fpdf.php');
include('../connection/db.php');
date_default_timezone_set("Asia/Manila");

class dbconfig extends dbconn
{
    /**
     * Getstrreports.
     */
    public function getstrreports()
    {
        if (isset($_SESSION['dept_id']) && $_SESSION['dept_id'] == "10") {
            $usrval = "str_num = :session_str_num";
        } else {
            $usrval = "userId = :session_user_id";
        }

        $flter = isset($_POST['filter']) ? $_POST['filter'] : 'DEFAULT';
        switch ($flter) {
            case 'CLOSED':
                $fltrval = "IN ('CLOSED')";
                break;
            case 'ALL':
                $fltrval = "IN ('CLOSED','ASSIGNED', 'NEW REPORT')";
                break;
            default:
                $fltrval = "IN ('ASSIGNED', 'NEW REPORT', 'WAREHOUSE PULL OUT','SUPPLIER PULL OUT','READY FOR PULL OUT','CONFIRM PULL OUT','PULL OUT BY SUPPLIER','REPAIRED','REPLACE SAME MODEL','REPLACE DIFFERENT MODEL','RTV','RETURN TO STORE','RETURN BY SUPPLIER','ON PROCESS','SUBJECT FOR CLOSING','ITEM RECEIVED','APPROVED','EVALUATE','SCHEDULE FOR DISPOSAL','SUBJECT FOR ADJUSTMENT','APPROVED SUMMARY ADJUSTMENT','LIST FOR DISPOSAL','OKAY FOR PULL OUT','ITEM-RECEIVED','PENDING')";
                break;
        }

        $query = "SELECT * FROM vw_usertable WHERE $usrval AND vw_usertable.`status` $fltrval";
        $statement = $this->connection->prepare($query);
        
        if (isset($_SESSION['dept_id']) && $_SESSION['dept_id'] == "10") {
            $statement->bindValue(':session_str_num', $_SESSION['str_num']);
        } else {
            $statement->bindValue(':session_user_id', $_SESSION['user_id']);
        }
        
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $output = array();

        foreach ($result as $row) {
            $output[] = array(
                'TicketNum' => $row["ticket_no"],
                'Scode' => $row["str_code"],
                'brncd_dptdesc' => ($row["store"] == 201) ? $row["str_code"] . " | " . $row["dept_desc"] : $row["str_code"],
                'Dt_Created' => date('m/d/Y H:i:s', strtotime($row["date_created"])),
                'Concern' => $row["subject"],
                'Tos' => $row["service_desc"],
                'Sbjct' => $row["concern"],
                'Status' => $row["status"],
                'AsgnSup' => $row["it_desc"],
                'NewRpt' => $row["msg_cnt"],
                'NewMes' => $row["nmsg_stat"],
                'deptsel_val' => $row["deptsel_val"],
                'series_id' => $row["series_id"]
            );
        }

        return $output;
    }

    /**
     * Inserdata.
     */
    public function inserdata()
    {
        $deptselectvalue = isset($_POST['deptsel']) ? $_POST['deptsel'] : '';
        $counter = 'counter';
        $deptabr = '';
        $Qitems = isset($_POST["QItems"]) ? $_POST["QItems"] : '';
        $result = false;

        switch ($deptselectvalue) {
            case '1': case '2': case '3': case '6': case '7':
            case '11': case '12': case '13': case '14': case '15': case '16':
                $counter = 'counter';
                $deptabr = '';
                break;
            case '4':
                $counter = 'counter';
                $deptabr = '';
                break;
            default:
                break;
        }

        $qry = $this->connection->prepare("SELECT ticket_no FROM {$counter} LIMIT 1");
        $service_desc = isset($_POST["select_tos"]) ? trim($_POST["select_tos"]) : '';
        $qry2 = $this->connection->prepare("SELECT count FROM rars_counter LIMIT 1");

        if (($service_desc === 'LOCAL' || $service_desc === 'IMPORT') && $Qitems === 'SINGLE') {
            $qry->execute();
            $res = $qry->fetch(PDO::FETCH_ASSOC);
            $ticknum = $res['ticket_no'] + 1;
            $userId = $_POST["uId"];
            $status = $_POST["status"];
            
            $statement = $this->connection->prepare("INSERT INTO reports (ticket_no, date_created, deptsel, store, concern, service_desc, status, subject, userId, sub_ticket, alu, serial_no, type_unit, pd_tag) 
                VALUES (:ticket_no, :date_created, :deptsel, :store, :concern, :service_desc, :status, :subject, :userId, :sub_ticket, :alu, :serial_no, :type_unit, :pd_tag)");
            
            $result = $statement->execute(array(
                ':ticket_no' => $deptabr . '' . $ticknum,
                ':date_created' => date('Y-m-d H:i:s'),
                ':store' => $_SESSION["str_num"],
                ':concern' => trim($_POST["concern"]),
                ':service_desc' => $service_desc,
                ':status' => $status,
                ':subject' => strtoupper(trim($_POST["subject"])),
                ':userId' => $userId,
                ':deptsel' => $_POST["deptsel"],
                ':sub_ticket' => $deptabr . '' . $ticknum,
                ':alu' => $_POST["Alu"],
                ':serial_no' => $_POST["SerialNo"],
                ':type_unit' => $_POST["TypesOfUnit"],
                ':pd_tag' => 'Y'
            ));

            $statement2 = $this->connection->prepare("INSERT INTO tbl_pditems (ticket_no, alu_no, description, serial_no, defect, supplier, save_tag) 
                VALUES (:ticket_no, :alu_no, :description, :serial_no, :defect, :supplier, :save_tag)");
            $statement2->execute(array(
                ':ticket_no' => $deptabr . '' . $ticknum,
                ':alu_no' => $_POST["Alu"],
                ':description' => $_POST["Desc"],
                ':serial_no' => $_POST["SerialNo"],
                ':defect' => $_POST["Defect"],
                ':supplier' => $_POST["Supplier"],
                ':save_tag' => 'Y'
            ));

            $qry2->execute();
            $res2 = $qry2->fetch(PDO::FETCH_ASSOC);
            $rarsnum = $res2['count'] + 1;

            $statement3 = $this->connection->prepare("UPDATE rars_counter SET count = :count");
            $statement3->execute(array(':count' => $rarsnum));

            if ($result) {
                $this->updatetickno($ticknum);
            }

        } elseif (($service_desc === 'LOCAL' || $service_desc === 'IMPORT') && $Qitems === 'MULTIPLE') {
            $qry->execute();
            $res = $qry->fetch(PDO::FETCH_ASSOC);
            $ticknum = $res['ticket_no'] + 1;
            $userId = $_POST["uId"];
            $status = $_POST["status"];
            $ticknox = $_POST['ticket_no'];
            
            $statement = $this->connection->prepare("INSERT INTO reports (ticket_no, date_created, deptsel, store, concern, service_desc, status, subject, userId, sub_ticket, type_unit, pd_tag, multitag) 
                VALUES (:ticket_no, :date_created, :deptsel, :store, :concern, :service_desc, :status, :subject, :userId, :sub_ticket, :type_unit, :pd_tag, :multitag)");
            
            $result = $statement->execute(array(
                ':ticket_no' => $deptabr . '' . $ticknum,
                ':date_created' => date('Y-m-d H:i:s'),
                ':store' => $_SESSION["str_num"],
                ':concern' => trim($_POST["concern"]),
                ':service_desc' => $service_desc,
                ':status' => $status,
                ':subject' => strtoupper(trim($_POST["subject"])),
                ':userId' => $userId,
                ':deptsel' => $_POST["deptsel"],
                ':sub_ticket' => $deptabr . '' . $ticknum,
                ':type_unit' => $_POST["TypesOfUnit"],
                ':pd_tag' => 'Y',
                ':multitag' => 'Y'
            ));

            $statement2 = $this->connection->prepare("UPDATE tbl_pditems SET save_tag = :save_tag WHERE ticket_no = :ticket_nox");
            $statement2->execute(array(':save_tag' => 'Y', ':ticket_nox' => $ticknox));

            $qry2->execute();
            $res2 = $qry2->fetch(PDO::FETCH_ASSOC);
            $rarsnum = $res2['count'] + 1;

            $statement3 = $this->connection->prepare("UPDATE rars_counter SET count = :count");
            $statement3->execute(array(':count' => $rarsnum));

            if ($result) {
                $this->updatetickno($ticknum);
            }

        } else {
            try {
                $this->connection->beginTransaction();
                $qry->execute();
                $res = $qry->fetch(PDO::FETCH_ASSOC);
                $ticknum = $res['ticket_no'] + 1;
                $userId = $_POST["uId"];
                $status = $_POST["status"];
                $computed_ticket = $deptabr . '' . $ticknum;

                $statement = $this->connection->prepare("INSERT INTO reports (ticket_no, date_created, deptsel, store, concern, service_desc, status, subject, userId) VALUES (:ticket_no, :date_created, :deptsel, :store, :concern, :service_desc, :status, :subject, :userId)");
                $result = $statement->execute(array(
                    ':ticket_no' => $computed_ticket,
                    ':date_created' => date('Y-m-d H:i:s'),
                    ':store' => $_SESSION["str_num"],
                    ':concern' => trim($_POST["concern"]),
                    ':service_desc' => $service_desc,
                    ':status' => $status,
                    ':subject' => strtoupper(trim($_POST["subject"])),
                    ':userId' => $userId,
                    ':deptsel' => $_POST["deptsel"]
                ));

                $reportId = $this->connection->lastInsertId();

                $statement2 = $this->connection->prepare("INSERT INTO tbl_notif (ticket_no, store, itsup, notif_data, notif_val, notif_date) VALUES (:ticket_no, :store, :itsup, :notif_data, :notif_val, :notif_date)");
                $statement2->execute(array(
                    ':ticket_no' => $computed_ticket,
                    ':store' => $_SESSION["str_num"],
                    ':itsup' => $userId,
                    ':notif_data'  => "New Ticket $computed_ticket Has been submitted.",
                    ':notif_val'   => '3',
                    ':notif_date' => date('Y-m-d H:i:s')
                ));

                $this->connection->commit();
                
                if ($result) {
                    $this->updatetickno($ticknum);
                }
            } catch (Exception $e) {
                $this->connection->rollBack();
                $result = false;
            }
        }

        if ($result && isset($ticknum)) {
            $this->msgnewrpt($ticknum, $deptabr);
            $this->insertrptmessages($ticknum, $deptabr);
            $this->frscommt($ticknum, $deptabr, $userId);
            $this->ticket_trail($ticknum, $deptabr, $status, $userId);
            if (isset($_POST['fix_asset_completed']) && $_POST['fix_asset_completed'] == '1') {
                $asset_ticket   = $deptabr . '' . $ticknum;
                $requested_by   = $_POST["uId"]; 
                $requested_db   = $_POST["sesstr_num"]; 
                $item_code      = $_POST['fa_item_code'];
                $serial_num     = $_POST['fa_serial_number'];
                $description    = $_POST['fa_description'];
                $ticket_created = date('Y-m-d H:i:s');
                
                try {
                    $stmt_asset = $this->connection->prepare(
                        "INSERT INTO asset_requests (
                            ticket_no, requested_by, requested_db, item_code, serial_number,  status, description, ticket_created, is_technical
                        ) VALUES (
                            :ticket_no, :requested_by, :requested_db, :item_code, :serial_num, :status, :description, :ticket_created, 0
                        )"
                    );
                    $stmt_asset->execute(array(
                        ':ticket_no'      => $asset_ticket,
                        ':requested_by'   => $requested_by,
                        ':requested_db'   => $requested_db,
                        ':item_code'      => $item_code,
                        ':serial_num'     => $serial_num,
                         ':status'    => 'NOTED',
                        ':description'    => $description,
                        ':ticket_created' => $ticket_created
                    ));
                } catch (Exception $e) {
                  
                }
            }

        }

        if (!empty($_FILES['files']) && isset($ticknum)) {
            $uploadDir = __DIR__ . '/image/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $files = $_FILES['files'];
            for ($i = 0; $i < count($files['name']); $i++) {
                if (is_uploaded_file($files['tmp_name'][$i])) {
                    $originalName = basename($files['name'][$i]);
                    $uniqueName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
                    $dest = $uploadDir . $uniqueName;
                    if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                        $storedPath = 'image/' . $uniqueName;
                        $ins = $this->connection->prepare("INSERT INTO images (files_tmp, files_name, uploaded_on, ticket_no) VALUES (:files_tmp, :files_name, NOW(), :ticket_no)");
                        $ins->execute(array(':files_tmp' => $storedPath, ':files_name' => $originalName, ':ticket_no' => $deptabr . '' . $ticknum));
                    }
                }
            }
        }

        $msg = '<div class="alert alert-success col-md-12"><span class="fas fa-check-circle fa-lg"></span> Successfully submitted to OWI HELPDESK. </div>';
        return array('Response' => true, 'm' => $msg);
    }

    /**
     * Search desc.
     */
    public function search_desc()
    {
        $search = isset($_POST['alu']) ? $_POST['alu'] : '';
        $query = "SELECT item_masterfile_refine.ALU, item_masterfile_refine.DESCRIPTION1 AS Desc1 FROM item_masterfile_refine WHERE item_masterfile_refine.ALU = :search";

        $statement = $this->connection->prepare($query);
        $statement->execute(array(':search' => $search));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $fetchdata = array();

        foreach ($result as $row) {
            $fetchdata[] = array(
                'Desc1' => $row['Desc1'],
                'Alu' => $row['ALU']
            );
        }
        return $fetchdata;
    }

    /**
     * Get ticket details for modal editing.
     */
    public function get_ticket_details()
    {
        $ticketNo = isset($_POST['ticket_no']) ? trim($_POST['ticket_no']) : '';

        if ($ticketNo === '') {
            return [];
        }

        $query = "SELECT
            r.ticket_no,
            r.store,
            b.str_name,
            CONCAT_WS(' ', u.fname, u.lstname) AS crtd_by,
            r.date_created,
            r.subject,
            r.concern,
            r.service_desc,
            r.via,
            r.itsup,
            r.cat_id,
            c.cat_desc,
            r.sub_id,
            sc.sub_cat,
            r.status,
            r.remarks,
            r.date_closed,
            r.close_by,
            r.isp_id,
            r.refNo,
            r.date_refNo,
            r.deptsel,
            GROUP_CONCAT(i.files_name SEPARATOR '|') AS attachment_files
        FROM reports r
        LEFT JOIN tbl_branch b ON b.str_num = r.store
        LEFT JOIN users u ON u.id = r.userId
        LEFT JOIN categories c ON c.cat_id = r.cat_id
        LEFT JOIN subcat sc ON sc.sub_id = r.sub_id
        LEFT JOIN images i ON i.ticket_no = r.ticket_no
        WHERE r.ticket_no = :ticket_no
        GROUP BY r.ticket_no";

        $statement = $this->connection->prepare($query);
        $statement->execute([':ticket_no' => $ticketNo]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Search tkt.
     */
    public function search_tkt()
    {
        $deptselectvalue = isset($_POST['iN']) ? $_POST['iN'] : '';
        $counter = 'counter';
        $deptabr = '';

        switch ($deptselectvalue) {
            case '1': case '2': case '3': case '4': case '6': case '7':
            case '11': case '12': case '13': case '14': case '15': case '16':
                $counter = 'counter';
                $deptabr = '';
                break;
            default:
                break;
        }

        $query = "SELECT ticket_no FROM {$counter}";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $fetchdata = array();

        foreach ($result as $row) {
            $fetchdata[] = array(
                'ticket_no' => $row['ticket_no'] + 1,
                'dept' => $deptabr
            );
        }
        return $fetchdata;
    }

    /**
     * Ticket trail.
     */
    public function ticket_trail($t, $deptabr, $status, $userId)
    {
        $restatnew = $this->connection->prepare("INSERT INTO tbl_tickethist (ticket_no, status, date_updated, userID) VALUES (:ticket_no, :status, :date_updated, :userID)");
        $restatnew->execute(array(
            ':ticket_no' => $deptabr . '' . $t,
            ':status' => $status,
            ':date_updated' => date('Y-m-d H:i:s'),
            ':userID' => $userId
        ));
    }

    /**
     * Msgnewrpt.
     */
    public function msgnewrpt($t, $deptabr)
    {
        $numnew = '1';
        $restatnew = $this->connection->prepare("INSERT INTO reports_msgcnt (ticket_no, msg_cnt) VALUES (:ticket_no, :msgcnt)");
        $restatnew->execute(array(
            ':ticket_no' => $deptabr . '' . $t,
            ':msgcnt' => $numnew
        ));
    }

    /**
     * Insertrptmessages.
     */
    public function insertrptmessages($t, $deptabr)
    {
        $nummsg = '1';
        $restat = $this->connection->prepare("INSERT INTO reports_newmsg (ticket_no, nmsg_stat) VALUES (:ticket_no, :nmsg_stat)");
        $restat->execute(array(
            ':ticket_no' => $deptabr . '' . $t,
            ':nmsg_stat' => $nummsg
        ));
    }

    /**
     * Updatetickno.
     */
    public function updatetickno($t)
    {
        $deptselectvalue = isset($_POST['deptsel']) ? $_POST['deptsel'] : '';
        $counter = 'counter';

        switch ($deptselectvalue) {
            case '1': case '2': case '3': case '4': case '6': case '7':
            case '11': case '12': case '13': case '14': case '15': case '16':
                $counter = 'counter';
                break;
            default:
                break;
        }

        $statement = $this->connection->prepare("UPDATE {$counter} SET ticket_no = :ticket_no");
        $statement->execute(array(':ticket_no' => $t));
    }

    /**
     * Getmsgs.
     */
    public function getmsgs()
    {
        $tickid = isset($_POST['tickid']) ? $_POST['tickid'] : '';
        $query = "SELECT reports_comments.comment_details AS comment_details, reports_comments.comment_date AS comment_date, users.fname AS fname, CONCAT(users.fname,' ',users.lstname) AS fullname, reports_comments.userId as usrid, users.img_name FROM users INNER JOIN reports_comments ON users.id = reports_comments.userId WHERE ticket_no = :tickid ORDER BY comment_date DESC";

        $statement = $this->connection->prepare($query);
        $statement->execute(array(':tickid' => $tickid));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $data = array();

        foreach ($result as $row) {
            $data[] = array(
                'desc' => $row['comment_details'], 
                'dt' => $row['comment_date'], 
                'tech' => $row['fullname'], 
                'usrid' => $row['usrid'], 
                'usrimg' => $row['img_name']
            );
        }
        return $data;
    }

    /**
     * Insertcomm.
     */
    public function insertcomm()
    {
        $unmsgcnt = '2';

        $makecom = $this->connection->prepare("INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) VALUES (:ticket_no, :comment_details, :comment_date, :userId)");
        $makecom->execute(array(
            ':ticket_no' => $_POST["ModalTicket_no"],
            ':comment_details' => $_POST["Modal_reply"],
            ':comment_date' => date('Y-m-d H:i:s'),
            ':userId' => $_POST["Modal_uId"]
        ));

        $notifprep = $this->connection->prepare("INSERT INTO tbl_notif (ticket_no, store, notif_data, notif_date, notif_val) VALUES (:ticket_no, :store, :notif_data, :notif_date, :notif_val)");
        $notifprep->execute(array(
            ':ticket_no' => $_POST["ModalTicket_no"],
            ':store' => $_SESSION['str_num'],
            ':notif_data' => $_SESSION['fname'] . ' ' . $_SESSION['lstname'] . ' from ' . $_POST['ModalStore'] . ' added a new comment on ticket number: ' . $_POST["ModalTicket_no"],
            ':notif_date' => date('Y-m-d H:i:s'),
            ':notif_val' => 2
        ));

        $nmsgcntres = $this->connection->prepare("UPDATE reports_newmsg SET nmsg_stat = :nmsg_stat WHERE ticket_no = :ticket_no");
        $nmsgcntres->execute(array(
            ':ticket_no' => $_POST["ModalTicket_no"],
            ':nmsg_stat' => $unmsgcnt
        ));

        $msgcntres = $this->connection->prepare("UPDATE reports_msgcnt SET msg_cnt = :msgcnt WHERE ticket_no = :ticket_no");
        $msgcntres->execute(array(
            ':ticket_no' => $_POST["ModalTicket_no"],
            ':msgcnt' => '1'
        ));

        return '<div class="alert alert-info col-md-12"><span class="fas fa-check-circle fa-lg"></span> Comment Saved </div>';
    }

    /**
     * Frscommt.
     */
    public function frscommt($t, $deptabr, $userId = null)
    {
        $uid = !empty($userId) ? $userId : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);
        $makecom = $this->connection->prepare("INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) VALUES (:ticket_no, :comment_details, :comment_date, :userId)");
        $makecom->execute(array(
            ':ticket_no' => $deptabr . '' . $t,
            ':comment_details' => $_POST["concern"],
            ':comment_date' => date('Y-m-d H:i:s'),
            ':userId' => $uid
        ));
    }

    /**
     * User change password.
     */
    public function user_change_password()
    {
        $userid = $_SESSION['user_id'];
        $qry = $this->connection->prepare("SELECT * FROM users WHERE id = :userid");
        $qry->execute(array(':userid' => $userid));
        $res = $qry->fetch(PDO::FETCH_ASSOC);
        
        if ($res) {
            $oldpass = $res['password'];
            $dcdeold_pass = base64_decode($oldpass);

            if ($_POST["curpass"] == $dcdeold_pass && $_POST['newpass'] == $_POST['confrm_nwpass']) {
                $statement = $this->connection->prepare("UPDATE users SET `password` = :password WHERE id = :userid");
                $statement->execute(array(
                    ':password' => base64_encode($_POST['newpass']),
                    ':userid' => $userid
                ));
            }
        }
    }

    /**
     * Get tos.
     */
    public function get_tos()
    {
        $deptVal = isset($_POST['deptval']) ? $_POST['deptval'] : '';
        $query = "SELECT * FROM tbl_typeofservice WHERE dept_id = :deptVal";

        $statement = $this->connection->prepare($query);
        $statement->execute(array(':deptVal' => $deptVal));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $fetchdata = array();

        foreach ($result as $row) {
            $fetchdata[] = array(
                'service_id' => $row['service_id'],
                'service_desc' => $row['service_desc']
            );
        }
        return $fetchdata;
    }

    /**
     * Pditems.
     */
    public function pditems()
    {
        $tktnoxx = isset($_POST['tickt']) ? $_POST['tickt'] : '';
        $query = "SELECT * FROM tbl_pditems WHERE ticket_no = :tktnoxx";

        $statement = $this->connection->prepare($query);
        $statement->execute(array(':tktnoxx' => $tktnoxx));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $fetchdata = array();

        foreach ($result as $row) {
            $fetchdata[] = array(
                'id' => $row['id'],
                'alu' => $row['alu_no'],
                'desc' => $row['description'],
                'serial' => $row['serial_no'],
                'supplier' => $row['supplier']
            );
        }
        return $fetchdata;
    }

    /**
     * Pv res.
     */
    public function pv_res()
    {
        $kprvr = isset($_POST['kprvr']) ? $_POST['kprvr'] : '';
        $sbs_no = isset($_POST['sbs_no']) ? $_POST['sbs_no'] : '';
        $price_lvl = isset($_POST['price_lvl']) ? $_POST['price_lvl'] : '';

        $query = "SELECT SBS_NO, ALU, LOCAL_UPC, DESCRIPTION1, Price, PRICE_LVL FROM item_masterfile_refine WHERE (ALU = :kprvr OR Local_UPC = :kprvr) AND SBS_NO = :sbs_no AND PRICE_LVL = :price_lvl";
        
        $statement = $this->connection->prepare($query);
        $statement->execute(array(
            ':kprvr' => $kprvr,
            ':sbs_no' => $sbs_no,
            ':price_lvl' => $price_lvl
        ));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $fetchdata = array();

        if ($statement->rowCount() > 0) {
            foreach ($result as $row) {
                $fetchdata[] = array(
                    'FDetails' => (trim($row['DESCRIPTION1']) == "") ? "No Data Found" : strtoupper($row["ALU"] . '     ' . $row["LOCAL_UPC"] . '   ' . $row["DESCRIPTION1"]),
                    'Price_WT' => (trim($row['Price']) == "") ? "No Data Found" : strtoupper($row["Price"])
                );
            }
        } else {
            $fetchdata[] = array(
                "FDetails" => "NO ITEM FOUND",
                "Price_WT" => "   "
            );
        }

        return $fetchdata;
    }

    /**
     * Rars count.
     */
    public function rars_count()
    {
        $query = "SELECT * FROM rars_counter";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $fetchdata = array();

        foreach ($result as $row) {
            $fetchdata[] = array(
                'rarscount' => $row['count'] + 1
            );
        }
        return $fetchdata;
    }
} //end class
?>