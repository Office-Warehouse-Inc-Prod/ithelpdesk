<?php


session_start();

if ($_SESSION['login']!='true'){
    header("Location: index.php");
    exit();
}

$tchnum = $_SESSION['tech_id'];
date_default_timezone_set("Asia/Manila");

include('../db.php');
// include('function.php');
if(isset($_POST["operation"]))
{

 if($_POST["operation"] == "Add")
 {

$qry = $connection->prepare(" SELECT ticket_no FROM counter");
$qry->execute();
$res = $qry->fetch(PDO::FETCH_ASSOC); 
$ticknum = $res['ticket_no']+1;

  $statement = $connection->prepare("
   INSERT INTO reports (ticket_no, store, date_created, concern, via, status, itsup, cat_id, sub_id, isp_id, refNo, date_closed, close_by, remarks) 
   VALUES (:ticket_no, :store, :date_created, :concern, :via, :status, :itsup, :cat_id, :sub_id, :isp_id, :refNo, :date_closed, :close_by, :remarks)
  ");
  $result = $statement->execute(
   array(
    ':ticket_no' => $ticknum,
    ':store' => $_POST["store"],
    ':date_created' => date('Y-m-d H:i:s',strtotime($_POST["date_created"])),
    ':concern' => $_POST["concern"],
    ':via' => $_POST["via"],
    ':status' => $_POST["status"],
    ':itsup' => $_POST["itsup"],
    ':cat_id' => $_POST["catx"],
    ':sub_id' => $_POST["sub"],
    ':isp_id' => $_POST["isp"],
    ':refNo' => $_POST["refNo"],
    ':date_closed' => date('Y-m-d H:i:s',strtotime($_POST["date_closed"])),
    ':close_by' => $_POST["close_by"],
    ':remarks' => $_POST["remarks"]
    
   )
  );
  $restat = $connection->prepare("
    INSERT INTO reports_remarks (ticket_no, remarks_detail, remarks_date, itsup) 
   VALUES (:ticket_no, :remarks_detail, :remarks_date, :itsup )
  ");
  $remarkres= $restat->execute(
    array(

      ':ticket_no' => $ticknum,
      ':remarks_detail' => $_POST["remarks"],
      ':remarks_date' => date('Y-m-d H:i:s'),
      ':itsup' => $tchnum

    ));

  if(!empty($result))
  {
    $statement = $connection->prepare(
      "UPDATE counter SET ticket_no = :ticket_no");
    $result = $statement->execute(
      array(
        ':ticket_no' => $ticknum,));
    
    echo 'Data Inserted';
  
};
 }


 if($_POST["operation"] == "Edit")
 { 
     $optbrval =$_POST["store"];
     $optval =$_POST["itsup"];
     $optcval =$_POST["catx"];
     $optsval =$_POST["sub"];
     $opclbval=$_POST["close_by"];
     $opispval=$_POST["isp"];
     $tmpval = '0';
if ( ($optbrval == '0') || ($optval == '0') || ($optcval == '0') || ($optsval == '0') || ($opclbval == '0') || ($opispval == '0') ) {
$brid=""; // store
$itsup=""; //itsupport
$cat_id=""; // cat
$sub_id=""; //sub
$isp=""; // service provider
$clby=""; // closeby
$data=   array(
    ':ticket_no' => $_POST["ticket_no"], //ticket# 
    ':date_created' => date('Y-m-d H:i:s',strtotime($_POST["date_created"])), //datecreated
    ':concern' => $_POST["concern"], //concern
    ':via' => $_POST["via"], //via
    ':status' => $_POST["status"], //status
    // ':close_by' => $_POST["close_by"],
    ':refNo' => $_POST["refNo"], //refNo
    ':date_closed' => date('Y-m-d H:i:s',strtotime($_POST["date_closed"])), // dateclosed
    ':remarks' => $_POST["remarks"] // workoutput
   ) ;
}
else{

    $brid="store = :store,";
    $itsup = "itsup = :itsup,";
    $cat_id="cat_id = :cat_id,";
    $sub_id="sub_id =:sub_id,";
    $clby="close_by = :close_by,";
    $isp="isp_id = :isp_id,";

      $data=   array(
    ':ticket_no' => $_POST["ticket_no"],
    ':store' => $_POST["store"],
    ':date_created' => date('Y-m-d H:i:s',strtotime($_POST["date_created"])),
    ':concern' => $_POST["concern"],
    ':via' => $_POST["via"],
    ':status' => $_POST["status"],
    ':itsup' => $_POST["itsup"],
    ':cat_id' => $_POST["catx"],
    ':sub_id' => $_POST["sub"],
    ':isp_id' => $_POST["isp"],
    ':refNo' => $_POST["refNo"],
    ':date_closed' => date('Y-m-d H:i:s',strtotime($_POST["date_closed"])),
    ':close_by' => $_POST["close_by"],
    ':remarks' => $_POST["remarks"]
   ) ;
}

  $statement = $connection->prepare(
   "UPDATE reports
   SET ticket_no = :ticket_no, $brid date_created = :date_created, concern = :concern, via = :via, 
                    status = :status, $itsup $cat_id $sub_id $isp refNo = :refNo, date_closed = :date_closed, $clby remarks = :remarks
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


   echo 'Updated successfully.';
 }
}
}

?>