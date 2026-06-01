
<?php

//fetch.php

include('../db.php');

if(isset($_POST["store_area"]))
{
 $query = "
 SELECT * FROM bargraph2
 WHERE store_area = '".$_POST["store_area"]."' AND mth = '".$_POST["mth"]."' AND  yr = '".$_POST["yrs"]."'
 ";
 $statement = $connection->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 if ($result){
 foreach($result as $row)
 {
  $output[] = array(
   'store'   => $row["store"],
   's_total'  => floatval($row["s_total"])
  );
 }


}
else{
$output[] = array(
   'store'   =>'NO RECORD FOUND',
   's_total'  => 'error'
  );
}
 echo json_encode($output);
}

?>