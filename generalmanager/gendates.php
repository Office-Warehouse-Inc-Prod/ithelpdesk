<?php
//gendates.php
session_start();

$connect = mysqli_connect("localhost", "root", "", "helpdesk1");
$columns = array('ticket_no', 'str_code', 'date_created', 'concern', 'via', 'status', 'it_desc','category', 'sub_category', 'date_closed', 'tdc', 'remarks', 'clusers');


$query = "SELECT
vw6.ticket_no,
vw6.str_code,
vw6.date_created,
vw6.concern,
vw6.via,
vw6.`status`,
vw6.it_desc,
vw6.category,
vw6.sub_category,
vw6.date_closed,
vw6.tdc,
vw6.remarks,
vw6.clusers

FROM
vw6
WHERE sub_id NOT IN ('15','28','34','35') AND ";

if($_POST["is_date_search"] == "yes")
{
 $query .= 'date_created BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" AND ';
}

if(isset($_POST["search"]["value"]))
{

 $query .= '
  (ticket_no LIKE "%'.$_POST["search"]["value"].'%" 
  OR str_code LIKE "%'.$_POST["search"]["value"].'%" 
  OR date_created LIKE "%'.$_POST["search"]["value"].'%" 
  OR concern LIKE "%'.$_POST["search"]["value"].'%" 
  OR via LIKE "%'.$_POST["search"]["value"].'%" 
  OR status LIKE "%'.$_POST["search"]["value"].'%" 
  OR it_desc LIKE "%'.$_POST["search"]["value"].'%" 
  OR category LIKE "%'.$_POST["search"]["value"].'%" 
  OR sub_category LIKE "%'.$_POST["search"]["value"].'%" 
  OR date_closed LIKE "%'.$_POST["search"]["value"].'%" 
  OR remarks LIKE "%'.$_POST["search"]["value"].'%" 
  OR clusers LIKE "%'.$_POST["search"]["value"].'%")
 ';
}

if(isset($_POST["order"]))
{
 $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' 
 ';
}
else
{
 $query .= 'ORDER BY ticket_no ASC ';
}

$query1 = '';

if($_POST["length"] != -1)
{
 $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$number_filter_row = mysqli_num_rows(mysqli_query($connect, $query));

$result = mysqli_query($connect, $query . $query1);

$data = array();

while($row = mysqli_fetch_array($result))
{
 $sub_array = array();
 $sub_array[] = $row["ticket_no"];
 $sub_array[] = $row["str_code"];
 $sub_array[] = date('m/d/Y H:i',strtotime($row["date_created"]));
 $sub_array[] = $row["concern"];
 $sub_array[] = $row["via"];
 $sub_array[] = $row["status"];
 $sub_array[] = $row["it_desc"];
 $sub_array[] = $row["category"];
 $sub_array[] = $row["sub_category"];
 $sub_array[] = date('m/d/Y H:i',strtotime($row["date_closed"]));
 $sub_array[] = $row["tdc"];
 $sub_array[] = $row["remarks"];
 $sub_array[] = $row["clusers"];
 $data[] = $sub_array;
}

function get_all_data($connect)
{
 $query = "SELECT * FROM vw6 WHERE itsup = '{$_SESSION['tech_id']}'";
 $result = mysqli_query($connect, $query);
 return mysqli_num_rows($result);
}

$output = array(
 "draw"    => intval($_POST["draw"]),
 "recordsTotal"  =>  get_all_data($connect),
 "recordsFiltered" => get_all_data($connect),
 "data"    => $data
);

echo json_encode($output);

?>
