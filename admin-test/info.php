<?php
 include('db.php');

 $tid= $_GET['tid'];
 $gettype = $_GET['gettype'];
 $data="";
 switch ($gettype) {

				case 'remarks':
					$query="SELECT
							reports_comments.comment_details AS comment_details,
							reports_comments.comment_date AS comment_date,
							users.fname AS fname,
							CONCAT(users.fname,' ',users.lstname) AS fullname,
							reports_comments.userId
							FROM
							users
							INNER JOIN reports_comments ON users.id = reports_comments.userId
							WHERE ticket_no = '".$tid."'
							ORDER BY comment_date DESC";

							$statement = $connection->prepare($query);
						   $statement->execute();
						   $result = $statement->Fetchall();
							$data = array();
				   
							foreach($result as $row)
							 {
								$detls= $row['comment_details'];
								$dt= $row['comment_date'];
								$tdt= $row['fullname'];
								 
								$data[] = array('desc' => $detls,'dt' => $dt, 'tech' => $tdt ,'uid'=> $row['userId']);
							 }

					break;

	case 'tech_info':
 		$query="SELECT it.itsup as itsup,it.it_desc as it_desc FROM it_tech it INNER JOIN vw6 v ON v.itsup = it.itsup where v.ticket_no='".$tid."'";
 		$statement = $connection->prepare($query);
		$statement->execute();
		$result = $statement->Fetchall();
 		$data = array();

 		foreach($result as $row)
  		{
  			$id= $row['itsup'];
 			$desc= $row['it_desc'];
 			$data[] = array('id' => $id,'desc' => $desc );
  		}
  		break;

  	case 'subdesc':

  		$query="SELECT * FROM subcat";
  		$statement = $connection->prepare($query);
		$statement->execute();
		$result = $statement->Fetchall();
 		$data = array();
 		 		foreach($result as $row)
  		{
  			$id= $row['subid'];
 			$desc= $row['sub_cat'];
 			$data[] = array('id' => $id,'desc' => $desc );
  		}
  		break;

}

echo json_encode($data);

?>