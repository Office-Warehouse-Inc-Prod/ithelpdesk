<?php

session_start();

if ($_SESSION['login']!='true'){
    header("Location: index.php");
    exit();
}

include('db.php');
include('function.php');
$msgcnt = '0'; //admin

  $msgcntres = $connection->prepare("
   UPDATE reports_newmsg
   SET nmsg_stat = :nmsg_stat
  WHERE ticket_no = :ticket_no
  ");
  $makemsgcnt= $msgcntres->execute(
    array(

      ':ticket_no' => $_POST["tickno"],
      ':nmsg_stat' => '0'

    ));
  // echo $_POST["tickno"];

?>