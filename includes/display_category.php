 <?php
 include('db.php');
  $query="select cat_id,cat_desc from categories";
 $statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
foreach($result as $row)
{

 	$data[] = array('id' => $row['cat_id'],'desc' => $row['cat_desc']);
}

echo json_encode($data);

                    ?>
                   
            

 