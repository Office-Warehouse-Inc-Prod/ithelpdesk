 <?php
	include 'db.php';
	// $subdesc=$_POST["sub"];
	$result = mysqli_query($concat,"SELECT * FROM subcat where sub_id=$_POST["sub"]");

 		 		foreach($result as $row)
  		{
  			$id= $row['subid'];
 			$desc= $row['sub_cat'];
 			$data[] = array('id' => $id,'desc' => $desc );
  		}

  		echo $data;
?>
