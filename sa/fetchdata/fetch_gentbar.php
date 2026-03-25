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
 $query="SELECT
reports.itsup,
it_tech.f_name AS it_name,
Count(reports.itsup) AS total,
YEAR(reports.date_created) AS yr,
date_created as dc,
date_closed as dcc,
reports.`status`,
Count( CASE reports.`status` when 'CLOSED' then 1 else null end) as completed
FROM
it_tech
LEFT JOIN reports ON reports.itsup = it_tech.itsup
WHERE
reports.sub_id NOT IN (15,28,34,35) AND reports.itsup <> '8' AND reports.date_created  BETWEEN '".$_POST['start_date']."' AND  '".$_POST['end_date']."' 
GROUP BY 
reports.itsup
ORDER BY `status` ASC";
 $statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();

foreach($result as $row)
{
  $data[] = array('it_name' => $row['it_name'],'total' => $row['total'],'completed'  => $row['completed']);
}




echo json_encode($data);

	}

}

$p = new pg2();
$p->donut();
 ?>  

