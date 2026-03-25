<?php
session_start();
include('db.php');
include('function.php');
if(isset($_POST["ticketId"]))
{
 $output = array();
 $statement = $connection->prepare(
  "SELECT * FROM reports 
  WHERE ticket_no = '".$_POST["ticketId"]."' 
  LIMIT 1"
 );
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
 $sub_array[] = $row["ticket_no"];
 $sub_array[] = date('m/d/Y',strtotime($row["date_created"]));
 $sub_array[] = $row["str_code"];
 $sub_array[] = $row["concern"];
 $sub_array[] = $row["service_desc"];
 $sub_array[] = $row["subject"];
 $sub_array[] = $row["status"];

  // if($row["image"] != '')
  // {
  //  $output['user_image'] = '<img src="upload/'.$row["image"].'" class="img-thumbnail" width="50" height="35" /><input type="hidden" name="hidden_user_image" value="'.$row["image"].'" />';
  // }
  // else
  // {
  //  $output['user_image'] = '<input type="hidden" name="hidden_user_image" value="" />';
  // }
 }
 // echo($output);
 echo json_encode($output);
}
?>
