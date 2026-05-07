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
 $query="select `b`.`str_num` AS `str_num`,count(`r`.`store`) AS `points`,count(case `r`.`status` when 'CLOSED' then 1 else NULL end) AS `completed`,`b`.`str_code` AS `str_name`,`b`.`area_num` AS `area_num`,`tbl_area`.`area_desc` AS `area_desc`,`r`.`status` AS `stats`,`r`.`date_created` AS `date_created` from ((`tbl_branch` `b` left join `reports` `r` on(`b`.`str_num` = `r`.`store`)) join `tbl_area` on(`b`.`area_num` = `tbl_area`.`area_num`)) WHERE date_created BETWEEN '".$_POST['start_date']."' AND  '".$_POST['end_date']."' group by `b`.`area_num`,`b`.`str_num`";
 $statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();

foreach($result as $row)
{
  $data[] = array('str_name' => $row['str_name'],'points' => $row['points']);
}




echo json_encode($data);

	}

}

$p = new pg2();
$p->donut();
 ?>  

