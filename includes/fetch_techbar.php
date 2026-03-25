
<?php

//fetch.php

include('../db.php');

if(isset($_POST["year"]))
{
 $query = "
 SELECT * FROM tsgraph
 WHERE year = '".$_POST["year"]."' 
 ";
 $statement = $connection->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  $output[] = array(
   'it_desc'   => $row["it_desc"],
   's_total'  => floatval($row["s_total"])
  );
 }
 echo json_encode($output);
}

?>