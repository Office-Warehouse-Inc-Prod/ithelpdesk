<?php

include('db.php');
include("function.php");

if(isset($_POST["user_id"]))
{
 $statement = $connection->prepare(
  "DELETE FROM reports WHERE ticket_no = :ticket_no"
 );
 $result = $statement->execute(
  array(
   ':ticket_no' => $_POST["user_id"]
  )
 );
 
 if(!empty($result))
 {
  echo 'Data Deleted';
 }
}



?>