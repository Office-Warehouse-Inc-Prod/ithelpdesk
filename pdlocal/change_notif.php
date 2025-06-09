<?php

session_start();

if ($_SESSION['login']!='true'){
    header("Location: index.php");
    exit();
}



$ticketVal = $_POST['ticketVal'];

date_default_timezone_set("Asia/Manila");

include('db.php');
$qry = $connection->prepare(" SELECT ticket_no, notif_val FROM tbl_notif WHERE ticket_no = '{$ticketVal}'");
$qry->execute();
$res = $qry->fetch(PDO::FETCH_ASSOC); 
$statement = $connection->prepare("UPDATE tbl_notif
SET `notif_val` = :notif_val
WHERE ticket_no = '{$ticketVal}'");

  $result = $statement->execute(
   array(
    ':notif_val' => '0'
   )
  );

 ?>