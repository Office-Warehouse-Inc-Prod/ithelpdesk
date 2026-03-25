    
<?php
session_start();
include('../connection/db.php');
date_default_timezone_set("Asia/Manila");
class dbconfig extends dbconn
{
public function getstrreports(){
    $query = '';
$output = array();
$query = "SELECT * FROM vw_usertable WHERE userId = '{$_SESSION['user_id']}' AND
vw_usertable.`status` IN ('OPEN', 'NEW REPORT')

 ";
$statement = $this->connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();

foreach($result as $row)
{
    $output[] = array(
        'TicketNum'    => $row["ticket_no"],
        'Scode'=> $row["str_code"],
        'Dt_Created'=> date('m/d/Y H:i:s',strtotime($row["date_created"])),
        'Concern'=>$row["concern"],
        'Tos'=>$row["service_desc"],
        'Sbjct'=>$row["subject"],
        'Status'=>$row["status"],
        'AsgnSup'=>$row["it_desc"],
        'NewRpt'=>$row["msg_cnt"],
        'NewMes'=>$row["nmsg_stat"]
       );


}

return $output;
}

public function inserdata(){
$qry =  $this->connection->prepare(" SELECT ticket_no FROM counter");
$qry->execute();
$res = $qry->fetch(PDO::FETCH_ASSOC); 
$ticknum = $res['ticket_no']+1;
$statement =$this->connection->prepare("
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
 if($result){
    $this->updatetickno($ticknum);
    $this->msgnewrpt($ticknum);
    $this->insertrptmessages($ticknum);
   


 }
$msg='<div class=" alert alert-success  col-md-12"><span class="fas fa-check-circle fa-lg"></span> Successfully submitted to I.T helpdesk. </div>';
      
      $data = array('Response' => true,'m'=>$msg);

    // echo json_encode($data);
   
return $data;
}

public function msgnewrpt($t){
    $numnew = '1';
    $restatnew =$this->connection->prepare("
    INSERT INTO reports_msgcnt (ticket_no, msg_cnt) 
   VALUES (:ticket_no, :msgcnt)
  ");
  $remarkresnew= $restatnew->execute(
    array(

      ':ticket_no' => $t,
      ':msgcnt' => $numnew
      // ':remarks_date' => date('Y-m-d H:i:s'),
      // ':itsup' => $tchnum

    ));

}

public function insertrptmessages($t){
$nummsg = '0';
    $restat = $this->connection->prepare("
    INSERT INTO reports_newmsg (ticket_no, nmsg_stat) 
   VALUES (:ticket_no, :nmsg_stat)
  ");
  $remarkres= $restat->execute(
    array(

      ':ticket_no' => $t,
      ':nmsg_stat' => $nummsg
      // ':remarks_date' => date('Y-m-d H:i:s'),
      // ':itsup' => $tchnum

    ));

}


public function updatetickno($t){

    $statement = $this->connection->prepare(
        "UPDATE counter SET ticket_no = :ticket_no");
      $result = $statement->execute(
        array(
          ':ticket_no' => $t,)
      );
}


public function getmsgs(){
  $query="SELECT
  reports_comments.comment_details AS comment_details,
  reports_comments.comment_date AS comment_date,
  users.fname AS fname,
  CONCAT(users.fname,' ',users.lstname) AS fullname,
  reports_comments.userId as usrid
  FROM
  users
  INNER JOIN reports_comments ON users.id = reports_comments.userId
  WHERE ticket_no = ".$_POST['tickid'] ."
  ORDER BY comment_date DESC";

  $statement = $this->connection->prepare($query);
   $statement->execute();
   $result = $statement->Fetchall();
  $data = array();

  foreach($result as $row)
   {
    $detls= $row['comment_details'];
    $dt= $row['comment_date'];
    $tdt= $row['fullname'];
     $uid =$row['usrid'];
    $data[] = array('desc' => $detls,'dt' => $dt, 'tech' => $tdt ,'usrid'=> $uid);
   }
   return $data;

}
public function insertcomm(){
  $msgcnt = '0'; // read
  $nmsgcnt= '1'; // new msg from user"
  $unmsgcnt= '2'; // user"
  $makecom = $this->connection->prepare("
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

  $nmsgcntres = $this->connection->prepare("
  UPDATE reports_newmsg
  SET nmsg_stat = :nmsg_stat
 WHERE ticket_no = :ticket_no
 ");
 $nmakemsgcnt= $nmsgcntres->execute(
   array(
 
     ':ticket_no' => $_POST["ModalTicket_no"],
     ':nmsg_stat' => $unmsgcnt
 
   ));
   
   $msgcntres = $this->connection->prepare("
  UPDATE reports_msgcnt
  SET msg_cnt = :msgcnt
 WHERE ticket_no = :ticket_no
 ");
 $makemsgcnt= $msgcntres->execute(
   array(
 
     ':ticket_no' => $_POST["ModalTicket_no"],
     ':msgcnt' => $nmsgcnt
 
   ));
   
   $msg='<div class=" alert alert-info  col-md-12"><span class="fas fa-check-circle fa-lg"></span> Comment Saved </div>';

return $msg;



}


}//end class




?>
   