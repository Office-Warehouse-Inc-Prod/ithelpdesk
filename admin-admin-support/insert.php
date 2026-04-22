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
// end of isset statOps



 
 

?>