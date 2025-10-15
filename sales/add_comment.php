<?php


session_start();

if ($_SESSION['login']!='true'){
    header("Location: index.php");
    exit();
}

$tchnum = $_SESSION['tech_id'];
$msgcnt = '0'; //  read & save
$nmsgcnt= '1'; // new message from user
$unmsgcnt= '2'; //  new message from admin"r"
date_default_timezone_set("Asia/Manila");

include('db.php');
include('function.php');
if(isset($_POST["Modal_operation"]))
{

 

 if($_POST["Modal_operation"] == "Comment")
 { 
   
     $optval =$_POST["ModalSelect_support"];
     $optcval =$_POST["ModalSelect_cat"];
     $optsval =$_POST["ModalSelect_subcat"];

if ($optval  == '0' || $optcval  == '0' || $optsval  == '0') {
$itsup="";
$cat_id="";
$sub_id="";
$data=   array(
    ':ticket_no' => $_POST["ModalTicket_no"],
    ':via' => $_POST["Modalvia"],
    ':status' => $_POST["Modalstatus"],
    ':Modalclose_by' => $_POST["Modalclose_by"],
    ':date_closed' => date('Y-m-d H:i:s',strtotime($_POST["ModalDate_close"])),
    ':close_by' => $_POST["Modalclose_by"],
    ':remarks' => $_POST["Modalremarks"]
   ) ;
}else{
    $itsup = "itsup = :itsup,";
    $cat_id="cat_id = :cat_id,";
    $sub_id="sub_id =:sub_id,";

      $data=   array(
    ':ticket_no' => $_POST["ModalTicket_no"],
    ':via' => $_POST["Modalvia"],
    ':status' => $_POST["Modalstatus"],
    ':itsup' => $_POST["ModalSelect_support"],
    ':cat_id' => $_POST["ModalSelect_cat"],
    ':sub_id' => $_POST["ModalSelect_subcat"],
    ':date_closed' => date('Y-m-d H:i:s',strtotime($_POST["ModalDate_close"])),
    ':close_by' => $_POST["Modalclose_by"],
    ':remarks' => $_POST["Modalremarks"]
   ) ;
}

  $statement = $connection->prepare(
   "UPDATE reports
   SET ticket_no = :ticket_no, via = :via, status = :status, $itsup $cat_id $sub_id date_closed = :date_closed, close_by = :close_by, remarks = :remarks
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

     ':ticket_no' => $_POST["ModalTicket_no"],
      ':remarks_detail' => $_POST["Modalremarks"],
      ':remarks_date' => date('Y-m-d H:i:s'),
      ':itsup' => $tchnum
    ));


    $msgcntres = $connection->prepare("
   UPDATE reports_msgcnt
   SET msg_cnt = :msgcnt
  WHERE ticket_no = :ticket_no
  ");
  $makemsgcnt= $msgcntres->execute(
    array(

      ':ticket_no' => $_POST["ModalTicket_no"],
      ':msgcnt' => $unmsgcnt

    ));


     $makecom = $connection->prepare("
    INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) 
   VALUES (:ticket_no, :comment_details, :comment_date, :userId )
  ");
  $remarkres= $makecom->execute(
    array(

     ':ticket_no' => $_POST["ModalTicket_no"],
      ':comment_details' => $_POST["Modal_reply"],
      ':comment_date' => date('Y-m-d H:i:s'),
      ':userId' => $_POST["Modal_uId"]
    ));


  $nmsgcntres = $connection->prepare("
   UPDATE reports_newmsg
   SET nmsg_stat = :nmsg_stat
  WHERE ticket_no = :ticket_no
  ");
  $nmakemsgcnt= $nmsgcntres->execute(
    array(

      ':ticket_no' => $_POST["ModalTicket_no"],
      ':nmsg_stat' => $nmsgcnt

    ));

   echo 'Replied successfully.';



 }
}
}

?>