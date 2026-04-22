    
<?php
// session_start();
// require('../fpdf/fpdf.php');
include('../connection/db.php');
date_default_timezone_set("Asia/Manila");




class dbconfig extends dbconn
{

public function getstrreports(){

if ($_SESSION['dept_id'] == "10") {
   $usrval = "str_num = '{$_SESSION['str_num']}'";
} else {
   $usrval = "userId = '{$_SESSION['user_id']}'";
}


  $flter =$_POST['filter'];
  switch ($flter) {
    case 'CLOSED':
      $fltrval = "IN ('CLOSED')";
      break;
      case 'ALL':
        $fltrval = "IN ('CLOSED','ASSIGNED', 'NEW REPORT')";
        break;
       
    default:
    $fltrval = "IN ('ASSIGNED', 'NEW REPORT', 'WAREHOUSE PULL OUT','SUPPLIER PULL OUT','READY FOR PULL OUT','CONFIRM PULL OUT','PULL OUT BY SUPPLIER','REPAIRED','REPLACE SAME MODEL','REPLACE DIFFERENT MODEL','RTV','RETURN TO STORE','RETURN BY SUPPLIER','ON PROCESS','SUBJECT FOR CLOSING','ITEM RECEIVED','APPROVED','EVALUATE','REPAIRED','SCHEDULE FOR DISPOSAL','SUBJECT FOR ADJUSTMENT','APPROVED SUMMARY ADJUSTMENT','RETURN BY SUPPLIER','LIST FOR DISPOSAL','OKAY FOR PULL OUT','ITEM-RECEIVED','PENDING')";
      break;
  }
$query = '';
$output = array();
$query = "SELECT * FROM vw_usertable WHERE $usrval AND
vw_usertable.`status` $fltrval";
 // $query = "SELECT * FROM vw_usertable WHERE $valqry";
$statement = $this->connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();

foreach($result as $row)
{

    $output[] = array(
        'TicketNum'    => $row["ticket_no"],
        'Scode'=> $row["str_code"],
        'brncd_dptdesc'=> ($row["store"]==201)? $row["str_code"] . " | " . $row["dept_desc"]: $row["str_code"],
        'Dt_Created'=> date('m/d/Y H:i:s',strtotime($row["date_created"])),
        'Concern'=>$row["subject"],
        'Tos'=>$row["service_desc"],
        'Sbjct'=>$row["concern"],
        'Status'=>$row["status"],
        'AsgnSup'=>$row["it_desc"],
        'NewRpt'=>$row["msg_cnt"],
        'NewMes'=>$row["nmsg_stat"],
        'deptsel_val'=>$row["deptsel_val"],
        'series_id' => $row["series_id"]

       );


}

return $output;
}

public function inserdata(){

$deptselectvalue = $_POST['deptsel'];

switch ($deptselectvalue) {
    case '1':
      $counter = 'counter';
      $deptabr = '';
    break;
    case '2':
      $counter = 'counter';
      $deptabr = '';
    break;
    case '3':
      $counter = 'counter';
      $deptabr = '';
    break;
    case '4':
      $counter = 'counter';
      $deptabr = '';
      $Qitems = $_POST["QItems"];
    break;
    case '6':
      $counter = 'counter';
      $deptabr = '';
    break;
    case '7':
      $counter = 'counter';
      $deptabr = '';
    break;
    case '11':
      $counter = 'counter';
      $deptabr = '';
    break;
    case '12':
      $counter = 'counter';
      $deptabr = '';
    break;
    case '13':
      $counter = 'counter';
      $deptabr = '';
    break;
    case '14':
      $counter = 'counter';
      $deptabr = '';
    break;
     case '15':
      $counter = 'counter';
      $deptabr = '';
    break;
    case '16':
      $counter = 'counter';
      $deptabr = '';
    break;
  default:
    # code...
    break;
}

$qry =  $this->connection->prepare(" SELECT ticket_no FROM {$counter}");
$service_desc =  trim($_POST["select_tos"]);
// $Qitems = trim($_POST["QItems"]);

$qry2 =  $this->connection->prepare(" SELECT count FROM rars_counter");



if (($service_desc === 'LOCAL' || $service_desc === 'IMPORT') && $Qitems === 'SINGLE') {
$qry->execute();
$res = $qry->fetch(PDO::FETCH_ASSOC); 
$ticknum = $res['ticket_no']+1;
$userId = $_POST["uId"];
$status = $_POST["status"];
$statement = $this->connection->prepare("INSERT INTO reports (ticket_no, date_created, deptsel, store, concern, service_desc, status, subject, userId, sub_ticket, alu, serial_no, type_unit, pd_tag) 
VALUES (:ticket_no, :date_created, :deptsel, :store, :concern, :service_desc, :status, :subject, :userId, :sub_ticket, :alu, :serial_no, :type_unit, :pd_tag)
");
$result = $statement->execute(
array(
 ':ticket_no' => $deptabr.''.$ticknum,
 ':date_created' => date('Y-m-d H:i:s'),
 ':store' => $_SESSION["str_num"],
 ':concern' => trim($_POST["concern"]),
 ':service_desc' => trim($_POST["select_tos"]),
 ':status' => $_POST["status"],
 ':subject' => strtoupper(trim($_POST["subject"])),
 ':userId' => $_POST["uId"],
 ':deptsel' => $_POST["deptsel"],
 ':sub_ticket' => $deptabr.''.$ticknum,
//  ':receipt_no' => $_POST["receiptNo"],
 ':alu' => $_POST["Alu"],
 ':serial_no' => $_POST["SerialNo"],
 ':type_unit' => $_POST["TypesOfUnit"],
 ':pd_tag' => 'Y'
));

$statement2 = $this->connection->prepare("INSERT INTO tbl_pditems (ticket_no, alu_no, description, serial_no, defect, supplier, save_tag) 
VALUES (:ticket_no, :alu_no, :description, :serial_no, :defect, :supplier, :save_tag)");
$result2 = $statement2->execute(
array(
':ticket_no' => $deptabr.''.$ticknum,
':alu_no' => $_POST["Alu"],
':description' => $_POST["Desc"],
':serial_no' => $_POST["SerialNo"],
':defect' => $_POST["Defect"],
':supplier' => $_POST["Supplier"],
':save_tag' => 'Y'
));


$qry2->execute();
$res2 = $qry2->fetch(PDO::FETCH_ASSOC); 
$rarsnum = $res2['count']+1;

$statement3 = $this->connection->prepare("UPDATE rars_counter SET count = :count ");
$result3 = $statement3->execute(
array(
':count' => $rarsnum
));

}

elseif (($service_desc === 'LOCAL' || $service_desc === 'IMPORT') && $Qitems === 'MULTIPLE') {
$qry->execute();
$res = $qry->fetch(PDO::FETCH_ASSOC); 
$ticknum = $res['ticket_no']+1;
$userId = $_POST["uId"];
$status = $_POST["status"];
$ticknox = $_POST['ticket_no'];
$statement =$this->connection->prepare("INSERT INTO reports (ticket_no, date_created, deptsel, store, concern, service_desc, status, subject, userId, sub_ticket, type_unit, pd_tag, multitag) 
VALUES (:ticket_no, :date_created, :deptsel, :store, :concern, :service_desc, :status, :subject, :userId, :sub_ticket, :type_unit, :pd_tag, :multitag)
");
$result = $statement->execute(
array(
 ':ticket_no' => $deptabr.''.$ticknum,
 ':date_created' => date('Y-m-d H:i:s'),
 ':store' => $_SESSION["str_num"],
 ':concern' => trim($_POST["concern"]),
 ':service_desc' => trim($_POST["select_tos"]),
 ':status' => $_POST["status"],
 ':subject' => strtoupper(trim($_POST["subject"])),
 ':userId' => $_POST["uId"],
 ':deptsel' => $_POST["deptsel"],
 ':sub_ticket' => $deptabr.''.$ticknum,
 ':type_unit' => $_POST["TypesOfUnit"],
 ':pd_tag' => 'Y',
 ':multitag' => 'Y'
)
);

$statement2 = $this->connection->prepare("UPDATE tbl_pditems SET save_tag = :save_tag WHERE ticket_no = '$ticknox' ");
$result2 = $statement2->execute(
array(
':save_tag' => 'Y'
));

$qry2->execute();
$res2 = $qry2->fetch(PDO::FETCH_ASSOC); 
$rarsnum = $res2['count']+1;

$statement3 = $this->connection->prepare("UPDATE rars_counter SET count = :count ");
$result3 = $statement3->execute(
array(
':count' => $rarsnum
));


}



else {
  $qry->execute();
  $res = $qry->fetch(PDO::FETCH_ASSOC); 
  $ticknum = $res['ticket_no']+1;
  $userId = $_POST["uId"];
  $status = $_POST["status"];
  $statement =$this->connection->prepare("INSERT INTO reports (ticket_no, date_created, deptsel, store, concern, service_desc, status, subject, userId) 
  VALUES (:ticket_no, :date_created, :deptsel, :store, :concern, :service_desc, :status, :subject, :userId)
  ");
  $result = $statement->execute(
  array(
   ':ticket_no' => $deptabr.''.$ticknum,
   ':date_created' => date('Y-m-d H:i:s'),  
   ':store' => $_SESSION["str_num"],
   ':concern' => trim($_POST["concern"]),
   ':service_desc' => trim($_POST["select_tos"]),
   ':status' => $_POST["status"],
   ':subject' => strtoupper(trim($_POST["subject"])),
   ':userId' => $_POST["uId"],
   ':deptsel' => $_POST["deptsel"]

   )
  );

}




 if($result){
    $this->updatetickno($ticknum,$deptabr);
    $this->msgnewrpt($ticknum,$deptabr);  
    $this->insertrptmessages($ticknum,$deptabr);
    $this->frscommt($ticknum,$deptabr,$userId);
    $this->ticket_trail($ticknum,$deptabr,$status,$userId);
   


 }




  
  
  
$msg='<div class="alert alert-success  col-md-12"><span class="fas fa-check-circle fa-lg"></span> Successfully submitted to OWI HELPDESK. </div>';
      
      $data = array('Response' => true,'m'=>$msg);

    // echo json_encode($data);
   
return $data;


}


public function search_desc(){
  $search = $_POST['alu'];
  $query="SELECT
  item_masterfile_refine.ALU, 
  item_masterfile_refine.DESCRIPTION1 AS Desc1
FROM
  item_masterfile_refine
WHERE
  item_masterfile_refine.ALU = '$search'
  ";
  
  $statement = $this->connection->prepare($query);
  $statement-> execute();
  $result = $statement->fetchAll();
  $data[] = array();
  // $fetchdata[] = array();
  
  foreach($result as $row)
  {
  $fetchdata[] = array(
  'Desc1' => $row['Desc1'],
  'Alu' => $row['ALU']
  
  
  );
  
  }
  $data = array_filter($fetchdata);
  // echo json_encode($data);
  return $data;
  
  }


  public function search_tkt(){

    $deptselectvalue = $_POST['iN'];

    switch ($deptselectvalue) {
        case '1':
          $counter = 'counter';
          $deptabr = '';
        break;
        case '2':
          $counter = 'counter';
          $deptabr = '';
        break;
        case '3':
          $counter = 'counter';
          $deptabr = '';
        break;
        case '4':
          $counter = 'counter';
          $deptabr = '';
        break;
        case '6':
          $counter = 'counter';
          $deptabr = '';
          break;
          case '7':
            $counter = 'counter';
            $deptabr = '';
            break;
          case '11':
            $counter = 'counter';
            $deptabr = '';
            break;
          case '12':
            $counter = 'counter';
            $deptabr = '';
            break;
          case '13':
            $counter = 'counter';
            $deptabr = '';
            break;
          case '14':
            $counter = 'counter';
            $deptabr = '';
            break;
          case '15':
            $counter = 'counter';
            $deptabr = '';
            break;
        case '16':
            $counter = 'counter';
            $deptabr = '';
            break;
      default:
        # code...
        break;
    }



    $query="SELECT ticket_no FROM {$counter} ";
    $statement = $this->connection->prepare($query);
    $statement-> execute();
    $result = $statement->fetchAll();
    $data[] = array();
    
    foreach($result as $row)
    {
    $fetchdata[] = array(
    'ticket_no' => $row['ticket_no']+1,
    'dept' => $deptabr
    
    
    );
    
    }
    $data = array_filter($fetchdata);
    // echo json_encode($data);
    return $data;
    
    }



public function ticket_trail($t,$deptabr,$status,$userId){
  $restatnew =$this->connection->prepare("
  INSERT INTO tbl_tickethist (ticket_no, status,date_updated,userID) 
 VALUES (:ticket_no, :status, :date_updated, :userID)
");
$remarkresnew= $restatnew->execute(
  array(
    ':ticket_no' => $deptabr.''.$t,
    ':status' => $status,
    ':date_updated' => date('Y-m-d H:i:s'),
    ':userID' => $userId
  ));

}


public function msgnewrpt($t,$deptabr){
    $numnew = '1';
    $restatnew =$this->connection->prepare("
    INSERT INTO reports_msgcnt (ticket_no, msg_cnt) 
   VALUES (:ticket_no, :msgcnt)
  ");
  $remarkresnew= $restatnew->execute(
    array(

      ':ticket_no' => $deptabr.''.$t,
      ':msgcnt' => $numnew
      // ':remarks_date' => date('Y-m-d H:i:s'),
      // ':itsup' => $tchnum

    ));

}

public function insertrptmessages($t,$deptabr){
$nummsg = '1';
    $restat = $this->connection->prepare("
    INSERT INTO reports_newmsg (ticket_no, nmsg_stat) 
   VALUES (:ticket_no, :nmsg_stat)
  ");
  $remarkres= $restat->execute(
    array(

      ':ticket_no' => $deptabr.''.$t,
      ':nmsg_stat' => $nummsg
      // ':remarks_date' => date('Y-m-d H:i:s'),
      // ':itsup' => $tchnum

    ));

}


public function updatetickno($t){


  $deptselectvalue = $_POST['deptsel'];

  switch ($deptselectvalue) {
    case '1':
     $counter = '`counter`';
      break;
    case '2':
      $counter = 'counter';
      break;
    case '3':
      $counter = 'counter';
      break;
    case '4':
      $counter = 'counter';
      break;
    case '6':
      $counter = 'counter';
      break;
    case '7':
      $counter = 'counter';
      break;
    case '11':
      $counter = 'counter';
      break;
    case '12':
      $counter = 'counter';
      break;
    case '13':
      $counter = 'counter';
      break;
    case '14':
      $counter = 'counter';
      break;
    case '15':
      $counter = 'counter';
      break;
    case '16':
      $counter = 'counter';
      break;
    default:
      # code...
      break;
  }


    $statement = $this->connection->prepare(
        "UPDATE $counter SET ticket_no = :ticket_no");
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
  reports_comments.userId as usrid,
  users.img_name
  FROM
  users
  INNER JOIN reports_comments ON users.id = reports_comments.userId
  -- WHERE ticket_no = ".$_POST['tickid'] ."
  WHERE ticket_no = '{$_POST['tickid']}'
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
     $img = $row['img_name'];
    $data[] = array('desc' => $detls,'dt' => $dt, 'tech' => $tdt ,'usrid'=> $uid,'usrimg'=> $img);
   }
   return $data;

}
public function insertcomm(){
  $msgcnt = '0'; // read
  $nmsgcnt= '1';
  $unmsgcnt= '2'; 
 

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

  $notifprep = $this->connection->prepare("
    INSERT INTO tbl_notif (ticket_no, store, notif_data, notif_date, notif_val )
    VALUES (:ticket_no, :store, :notif_data, :notif_date, :notif_val )");
  $notifres= $notifprep->execute(
    array(

    ':ticket_no' => $_POST["ModalTicket_no"],
    ':store' => $_SESSION['str_num'],
    ':notif_data' => $_SESSION['fname']. '  ' . $_SESSION['lstname'].' '.'from'.' '.$_POST['ModalStore'].' '.'add a new comment on ticket number:'.' '. $_POST["ModalTicket_no"],
    ':notif_date' => date('Y-m-d H:i:s'),
    ':notif_val' => 2

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
     ':msgcnt' => '1'
 
   ));


   
   $msg='<div class=" alert alert-info  col-md-12"><span class="fas fa-check-circle fa-lg"></span> Comment Saved </div>';

return $msg;



}

public function frscommt($t,$deptabr){
 // reports comments fix
  $msgcnt = '0'; // read
  $nmsgcnt= '1'; // new msg from user"
  $unmsgcnt= '2'; // user"
  $makecom = $this->connection->prepare("
  INSERT INTO reports_comments (ticket_no, comment_details, comment_date, userId) 
 VALUES (:ticket_no, :comment_details, :comment_date, :userId )
");
$remarkres= $makecom->execute(
  array(

   ':ticket_no' => $deptabr.''.$t,
    ':comment_details' => $_POST["concern"],
    ':comment_date' => date('Y-m-d H:i:s'),
    ':userId' => $_SESSION['user_id']
  ));



}

public function user_change_password(){
$userid = $_SESSION['user_id'];
$qry = $this->connection->prepare(" SELECT * FROM users WHERE id = $userid");
$qry->execute();
$res = $qry->fetch(PDO::FETCH_ASSOC); 
$oldpass = $res['password'];
$dcdeold_pass= base64_decode($oldpass);


if ($_POST["curpass"] == $dcdeold_pass && $_POST['newpass'] == $_POST['confrm_nwpass']) {
$statement = $this->connection->prepare("UPDATE users
SET `password` = :password
WHERE id = $userid");
  $result = $statement->execute(
   array(
    ':password' => base64_encode($_POST['newpass'])
   )
  );
  // $msg = 'Password updated!';
  // // // 
  //     return $msg;
}

}



public function get_tos(){

$deptVal = $_POST['deptval'];
  
  $query=" 
  SELECT * FROM tbl_typeofservice WHERE dept_id = '{$deptVal}'
  
  ";
  
  $statement = $this->connection->prepare($query);
  $statement-> execute();
  $result = $statement->fetchAll();
  $data[] = array();
  // $fetchdata[] = array();
  
  foreach($result as $row)
  {
  $fetchdata[] = array(
  'service_id' => $row['service_id'],
  'service_desc' => $row['service_desc']
  
  );
  
  }
  $data = array_filter($fetchdata);
  // echo json_encode($data);
  return $data;
  
  }

// viewing of table for pd items
  public function pditems(){

    $tktnoxx = $_POST['tickt'];  

      $query=" SELECT * FROM tbl_pditems WHERE ticket_no = '$tktnoxx' ";
      
      $statement = $this->connection->prepare($query);
      $statement-> execute();
      $result = $statement->fetchAll();
      $data[] = array();
      // $fetchdata[] = array();
      
      foreach($result as $row)
      {
      $fetchdata[] = array(
      'id' => $row['id'],
      'alu' => $row['alu_no'],
      'desc' => $row['description'],
      'serial' => $row['serial_no'],
      'supplier' => $row['supplier']

      
      );
      
      }
      $data = array_filter($fetchdata);
      // echo json_encode($data);
      return $data;
      
      }

      public function pv_res(){

        $kprvr = $_POST['kprvr'];
        $sbs_no = $_POST['sbs_no'];
        $price_lvl = $_POST['price_lvl'];
    
        // $query="SELECT SBS_NO, ALU,  LOCAL_UPC, DESCRIPTION1, Price, PRICE_LVL FROM item_masterfile_refine WHERE SBS_NO = '$sbs_no' AND PRICE_LVL = '$price_lvl' AND ALU = '$kprvr' OR Local_UPC = '$kprvr'";
        //new code for price verifier
        $query="SELECT SBS_NO, ALU, LOCAL_UPC, DESCRIPTION1, Price, PRICE_LVL
FROM item_masterfile_refine
WHERE (ALU = '$kprvr' OR Local_UPC = '$kprvr')
  AND SBS_NO = '$sbs_no'
  AND PRICE_LVL = '$price_lvl';";
        $statement = $this->connection->prepare($query);
        $statement-> execute();
        $result = $statement->fetchAll();
        $data[] = array();
    
      if ($statement->rowCount() > 0) {
       
        foreach($result as $row)
        {
        $fetchdata[] = array(
    
        'FDetails' => ($row['DESCRIPTION1'] == " ") ? "No Data Found": strtoupper($row["ALU"].''.'     '.$row["LOCAL_UPC"].'   '. $row["DESCRIPTION1"]),
        // 'FDetails' => $row['FDetails'],
        'Price_WT' => ($row['Price'] == " ") ? "No Data Found": strtoupper($row["Price"]),
        // 'Price_WT' => 'PHP.'.' ' .$row['Price_WT'],
    
        
        );
        
        }
        $data = array_filter($fetchdata);
    // echo json_encode($data);
      }
    
      else {
    
        $fetchdata[] = array(
          "FDetails" => "NO ITEM FOUND",
          "Price_WT" => "   "
          // "city" => "No Data Found"
      );
    
    
        $data = array_filter($fetchdata);
        // echo json_encode($data);
      }
        
        return $data;
        
        }

        public function rars_count(){

          $query="SELECT * FROM rars_counter";
          $statement = $this->connection->prepare($query);
          $statement-> execute();
          $result = $statement->fetchAll();
          $data[] = array();
          // $fetchdata[] = array();
          
          foreach($result as $row)
          {
          
          $fetchdata[] = array(
          'rarscount' => $row['count']+1
          
          );
          
          }
          $data = array_filter($fetchdata);
          // echo json_encode($data);
          return $data;
          
          }
          



}//end class




?>
   