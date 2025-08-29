<?php  

/**
 * 
 */

class pg2 
{

	function __construct(){


	}
	
	public function donut()
	{
$username = 'root';
$password = '';
$connection = new PDO( 'mysql:host=localhost;dbname=helpdesk1', $username, $password );
 $query="SELECT `status` as stat_name, COUNT(`status`) as points, date_created as date_created from reports WHERE date_created BETWEEN '".$_POST['start_date']."' AND  '".$_POST['end_date']."' AND sub_id <> '15' and sub_id <> '28' and sub_id <> '34' and sub_id <> '35' GROUP BY `status`";
 $statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();

foreach($result as $row)
{
  $data[] = array('stat_name' => $row['stat_name'],'points' => $row['points']);
}




echo json_encode($data);

	}

}

$p = new pg2();
$p->donut();
 ?>  

