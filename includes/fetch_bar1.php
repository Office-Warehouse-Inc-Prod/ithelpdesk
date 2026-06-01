
<?php

//fetch.php

include('../db.php');

if(isset($_POST["year"]))
{
 $query = "
 SELECT * FROM bar1
 WHERE year = '".$_POST["year"]."' 
 ";
 $statement = $connection->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  $output[] = array(
   'store_area'   => $row["store_area"],
   's_total'  => floatval($row["s_total"])
  );
 }
 echo json_encode($output);
}

?>