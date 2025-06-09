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


if($_POST["operation"] == "Save and Reply")
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
    ':isp_id' => '0',
    ':refNo' => $_POST["refNo"],
    ':date_refNo' => date('Y-m-d H:i:s',strtotime($_POST["date_refNo"])),
    ':date_closed' => date('Y-m-d H:i:s',strtotime($_POST["date_closed"])),
    ':close_by' => $_POST["close_by"],
    ':remarks' => $_POST["remarks"]
   ) ;
}

  $statement = $connection->prepare(
   "UPDATE reports
   SET ticket_no = :ticket_no, $brid date_created = :date_created,  via = :via, 
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

  $tickhisres = $connection->prepare("
  INSERT INTO tbl_tickethist (ticket_no, date_updated) 
 VALUES (:ticket_no, :date_updated)
");

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

      $addmsg=   array(
    ':comment_details' => $_POST["admsg"],
   ) ;

 if(!empty($addmsg))
  {

    $makecom = $connection->prepare("
    INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) 
   VALUES (:ticket_no, :comment_details, :comment_date, :userId )
  ");

  $remarkres= $makecom->execute(
    array(

     ':ticket_no' => $_POST["ticket_no"],
      ':comment_details' => $_POST["admsg"],
      ':comment_date' => date('Y-m-d H:i:s'),
      ':userId' => $_POST["u_id"]
    ));

    
  $nmsgcntres = $connection->prepare("
   UPDATE reports_newmsg
   SET nmsg_stat = :nmsg_stat
  WHERE ticket_no = :ticket_no
  ");
  $nmakemsgcnt= $nmsgcntres->execute(
    array(

      ':ticket_no' => $_POST["ticket_no"],
      ':nmsg_stat' => '2'

    ));

   // echo 'Data has been updated';
 }


 // ticket_trail

 if(!empty($addmsg))
 {

  $tickhisres = $connection->prepare("
  INSERT INTO tbl_tickethist (ticket_no, date_updated, status, userID) 
  VALUES (:ticket_no, :date_updated, :status, :userID )
");
$tickhisres1= $tickhisres->execute(
  array(

    ':ticket_no' => $_POST["ticket_no"] ,
    ':date_updated' => date('Y-m-d H:i:s'),
    ':status' => $_POST["status"],
    ':userID' => $_POST["u_id"]

  ));

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


} // end 
?>
