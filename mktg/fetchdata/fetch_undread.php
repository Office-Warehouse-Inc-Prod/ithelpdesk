<?php
session_start();
include('../db.php');
include('../function.php');
$query = '';
$output = array();
$query = "SELECT * FROM vw_wfittable WHERE nmsg_stat = '2'
 ";
// if(isset($_POST["search"]["value"]))
// {
//  $query .= 'OR ticket_no LIKE "%'.$_POST["search"]["value"].'%" ';
//  $query .= 'OR date_created LIKE "%'.$_POST["search"]["value"].'%" ';
//  $query .= 'OR str_code LIKE "%'.$_POST["search"]["value"].'%" ';
//  $query .= 'OR concern LIKE "%'.$_POST["search"]["value"].'%" ';
//  $query .= 'OR service_desc LIKE "%'.$_POST["search"]["value"].'%" ';
//  $query .= 'OR subject LIKE "%'.$_POST["search"]["value"].'%" ';
//  $query .= 'OR status LIKE "%'.$_POST["search"]["value"].'%" ';

// }
 
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
 // $sub_array[] = $image;
 $sub_array[] = $row["ticket_no"];
 $sub_array[] = date('m/d/Y',strtotime($row["date_created"]));
 $sub_array[] = $row["str_code"];
 $sub_array[] = $row["concern"];
 $sub_array[] = $row["service_desc"];
 $sub_array[] = $row["subject"];
 $sub_array[] = $row["via"];
 $sub_array[] = $row["status"];              
 $sub_array[] = $row["it_desc"];;
 $sub_array[] = $row["cat_desc"];
 $sub_array[] = $row["sub_cat"];
 $sub_array[] = '<button type="button" name="update" id="'.$row["ticket_no"].'" class="btn btn-warning btn-xs update"><i class="far fa-comments"></i></button>';
 // $sub_array[] = '<input type="text" name="msgcntid" id="'.$row["ticket_no"].'" class="form-control msgcnt" value ="'.$row["msg_cnt"].'">';
 $sub_array[] = $row["msg_cnt"];
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