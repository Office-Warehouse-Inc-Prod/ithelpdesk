 <?php
 include('db.php');
  $query="select sub_id, sub_cat from subact";
 $statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
foreach($result as $row)
{

 	$data[] = array('id' => $row['sub_id'],'desc' => $row['sub_cat']);
}

echo json_encode($data);

                    ?>
                   
            