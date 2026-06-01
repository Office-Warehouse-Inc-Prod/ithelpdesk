<?php  
 
session_start();

if ($_SESSION['login']!='true'){
    header("Location: index.php");
    exit;
}
/**
 * 
 */

class pg 
{

	function __construct(){


	}
	
	public function pie()
	{
$username = 'root';
$password = '';
$connection = new PDO( 'mysql:host=localhost;dbname=helpdesk1', $username, $password );
 $query="SELECT cat_desc,clr,cat_id, count(*) as ctn, date_created
FROM vwp 
WHERE date_created BETWEEN '".$_POST['start_date']."' AND  '".$_POST['end_date']."' AND sub_id <> '15' and sub_id <> '28' and sub_id <> '34' and sub_id <> '35' GROUP BY cat_id ORDER BY cat_desc ASC";
 $statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();

foreach($result as $row)
{

  $data[] = array('type' => $row['cat_desc'],'percent' => $row['ctn'],'color' => $row['clr'],'subs' => $this->subs($row['cat_id']));
}
// else {
// 	$data[] = array('type' => 'NO RECORDS FOUND','percent' => 'error','ctn' => 'error',
// 			'subs' => 'error');
// }

return $data;

	}

	
 function subs($id){
$username = 'root';
$password = '';
$connection = new PDO( 'mysql:host=localhost;dbname=helpdesk1', $username, $password );
 $query= "SELECT sub_cat, count(*) as sctn, date_created FROM vwp where date_created BETWEEN '".$_POST['start_date']."' AND '".$_POST['end_date']."' AND sub_id <> '15' and sub_id <> '28' and sub_id <> '34' and sub_id <> '35' AND cat_id='".$id."' GROUP BY sub_cat ORDER BY cat_desc ASC";
 $statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
foreach($result as $row)
{
$data[] = array('type' => $row['sub_cat'],'percent' => $row['sctn']);

}
return $data;
}


}

//insert post
$px = new pg();
$px->pie();

 ?>  

