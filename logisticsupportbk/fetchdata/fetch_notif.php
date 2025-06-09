<?php
session_start();
include('../db.php');
include('../function.php');
$query = '';
$output = array();
$query = "SELECT * FROM vw_notif WHERE role NOT IN ('admin')
 ";

if(isset($_POST["order"]))
{
 $query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
 $query .= 'ORDER BY ticket_no + 0 DESC ';
}
if($_POST["length"] != -1)
{
 $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
$statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
foreach($result as $row)
{

 $sub_array = array();

  $sub_array[] = 'Message from '.$row["fname"].' on ticket '.$row["ticket_no"].' in '.$row["str_code"].' . "'.$row["comment_details"].'"... on '.date('m/d/Y H:i:s',strtotime($row["comment_date"])).'<br>';
 $sub_array[] = $row["nmsg_stat"];

 $data[] = $sub_array;
}
$output = array(
 "draw"    => intval($_POST["draw"]),
 "recordsTotal"  =>  $filtered_rows,
 "recordsFiltered" => get_total_all_records(),
 "data"    => $data
);
echo json_encode($output);
?>