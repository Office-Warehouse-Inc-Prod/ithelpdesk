<?php

session_start();

if ($_SESSION['login']!='true'){
    header("Location: index.php");
    exit();
}

include('db.php');
include('function.php');
$msgcnt = '2'; // '2' admin message count

$msgcntres = $connection->prepare("
   UPDATE reports_msgcnt
   SET msg_cnt = :msgcnt
  WHERE ticket_no = :ticket_no
  ");
  $msgcntres->execute(
    array(

      ':ticket_no' => $_POST["tickno"],
      ':msgcnt' =>'2'

    ));
  // echo $_POST["tickno"];

?>