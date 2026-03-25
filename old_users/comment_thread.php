<?php
 include('db.php');

 $tid= $_GET['tid'];
 $gettype = $_GET['gettype'];
 $data="";
 switch ($gettype) {

				case 'comnt':
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
								 
								$data[] = array('desc' => $detls,'dt' => $dt, 'tech' => $tdt );
							 }

					break;
}

echo json_encode($data);

?>