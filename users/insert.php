    <?php


    session_start();

    if ($_SESSION['login']!='true'){
        header("Location: index.php");
        exit();
    }

    // $usernum = $_SESSION['id'];
    date_default_timezone_set("Asia/Manila");

    include('db.php');

    if(isset($_POST["statOps"]))
    {

    if($_POST["statOps"] == "SUBJECT FOR CLOSING")
    {

    
    $statement = $connection->prepare(
        "UPDATE reports
        SET `status` = 'CLOSED' , confirm_close_date = :cfdate
    WHERE ticket_no = :ticket_no");
    
    $makemsgcnt= $statement->execute(
    array(

        ':ticket_no' => $_POST["nticknum"],
        ':cfdate' => date('Y-m-d H:i:s'),
        
    ));
    echo json_encode($data);


    }

    if($_POST["statOps"] == "READY FOR PULL OUT")
    {

    
    $statement = $connection->prepare(
        "UPDATE reports
        SET `status` = 'CONFIRM PULL OUT'
    WHERE ticket_no = :ticket_no");
    
    $makemsgcnt= $statement->execute(
    array(

        ':ticket_no' => $_POST["nticknum"]
        // ':cfdate' => date('Y-m-d H:i:s'),
        
    ));
    echo json_encode($data);


    }

    if($_POST["statOps"] == "DIRECT PULL OUT")
    {

    
    $statement = $connection->prepare(
        "UPDATE reports
        SET `status` = 'CONFIRM PICK UP'
    WHERE ticket_no = :ticket_no");
    
    $makemsgcnt= $statement->execute(
    array(

        ':ticket_no' => $_POST["nticknum"]
        // ':cfdate' => date('Y-m-d H:i:s'),
        
    ));
    echo json_encode($data);


    }


    if($_POST["statOps"] == "RETURN TO STORE")
    {

    
    $statement = $connection->prepare(
        "UPDATE reports
        SET `status` = 'ITEM-RECEIVED'
    WHERE ticket_no = :ticket_no");
    
    $makemsgcnt= $statement->execute(
    array(

        ':ticket_no' => $_POST["nticknum"]
        // ':cfdate' => date('Y-m-d H:i:s')
        
    ));
    echo json_encode($data);


    }

    if($_POST["statOps"] == "RETURN BY SUPPLIER")
    {

    
    $statement = $connection->prepare(
        "UPDATE reports
        SET `status` = 'CLOSED', confirm_close_date = :cfdate
    WHERE ticket_no = :ticket_no");
    
    $makemsgcnt= $statement->execute(
    array(

        ':ticket_no' => $_POST["nticknum"],
        ':cfdate' => date('Y-m-d H:i:s')
        
    ));
    echo json_encode($data);


    }

    }

    if (isset($_POST['operation']) && $_POST['operation'] == 'delete') 
    {
    $IDx = $_POST['IDx'];
    $ticketx = $_POST['ticktx'];
    $statement = $connection->prepare(
        "DELETE FROM tbl_pditems WHERE id = '$IDx' AND ticket_no = '$ticketx' ");
        $statement->execute();
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
    // end of isset statOps



    
    

    ?>