<?php


session_start();

if ($_SESSION['login']!='true'){
    header("Location: index.php");
    exit();
}

$tchnum = $_SESSION['tech_id'];
// $usernum = $_SESSION['id'];
date_default_timezone_set("Asia/Manila");

include('db.php');
include('function.php');
if(isset($_POST["operation"]))
{

 if($_POST["operation"] == "Add")
 {

$qry = $connection->prepare(" SELECT ticket_no FROM counter");
$qry->execute();
$res = $qry->fetch(PDO::FETCH_ASSOC); 
$ticknum = $res['ticket_no']+1;
$msgcnt = '1'; //new reports
$nmsgcnt= '0'; // new "meesage"

  $statement = $connection->prepare("
   INSERT INTO reports (ticket_no, date_created, store, concern, service_desc, status, subject, userId) 
   VALUES (:ticket_no, :date_created, :store, :concern, :service_desc, :status, :subject, :userId)
  ");
  $result = $statement->execute(
   array(
    ':ticket_no' => $ticknum,
    ':date_created' => date('Y-m-d H:i:s',strtotime($_POST["currentDate"])),
    ':store' => $_POST["sesstr_num"],
    ':concern' => strtoupper($_POST["subject"]),
    ':service_desc' => $_POST["select_tos"],
    ':status' => $_POST["status"],
    ':subject' => $_POST["concern"],
    ':userId' => $_POST["uId"]
    
   )
  );
  $restat = $connection->prepare("
    INSERT INTO reports_msgcnt (ticket_no, msg_cnt) 
   VALUES (:ticket_no, :msgcnt)
  ");
  $remarkres= $restat->execute(
    array(

      ':ticket_no' => $ticknum,
      ':msgcnt' => $msgcnt
      // ':remarks_date' => date('Y-m-d H:i:s'),
      // ':itsup' => $tchnum

    ));
    $restat = $connection->prepare("
    INSERT INTO reports_newmsg (ticket_no, nmsg_stat) 
   VALUES (:ticket_no, :nmsg_stat)
  ");
  $remarkres= $restat->execute(
    array(

      ':ticket_no' => $ticknum,
      ':nmsg_stat' => $nmsgcnt 
      // ':remarks_date' => date('Y-m-d H:i:s'),
      // ':itsup' => $tchnum

    ));

  if(!empty($result))
  {
    $statement = $connection->prepare(
      "UPDATE counter SET ticket_no = :ticket_no");
    $result = $statement->execute(
      array(
        ':ticket_no' => $ticknum,)
    );
    
   $msg='<div class=" alert alert-success  col-md-12"><span class="fas fa-check-circle fa-lg"></span> Successfully submitted to I.T helpdesk. </div>';
      
      $data = array('Response' => true,'m'=>$msg);

    echo json_encode($data);
  
};
 }

 


 
}

?>