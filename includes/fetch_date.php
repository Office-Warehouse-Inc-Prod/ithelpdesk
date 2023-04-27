<?php
//fetch.php
$connect = mysqli_connect("localhost", "root", "", "helpdesk1");
$columns = array('ticket_no');

$query = "SELECT * FROM vw6 WHERE";

if($_POST["is_date_search"] == "yes")
{
 $query .= 'date_created BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" AND ';
}

if(isset($_POST["search"]["value"]))
{
 $query .= '
  (ticket_no LIKE "%'.$_POST["search"]["value"].'%"
  OR store LIKE "%'.$_POST["search"]["value"].'%" 
  OR concern LIKE "%'.$_POST["search"]["value"].'%" 
  OR via LIKE "%'.$_POST["search"]["value"].'%" 
  OR status LIKE "%'.$_POST["search"]["value"].'%" 
  OR it_desc LIKE "%'.$_POST["search"]["value"].'%" 
  OR category LIKE "%'.$_POST["search"]["value"].'%" 
  OR sub_category LIKE "%'.$_POST["search"]["value"].'%" 
  OR date_closed LIKE "%'.$_POST["search"]["value"].'%" 
  OR tdc LIKE "%'.$_POST["search"]["value"].'%" 

   )';
}

if(isset($_POST["order"]))
{
 $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' 
 ';
}
else
{
 $query .= 'ORDER BY ticket_no DESC ';
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
 $sub_array[] = $row["store"];
 // $sub_array[] = $row["date_created"];
 $sub_array[] = date('m/d/Y H:i',strtotime($row["date_created"]));
 $sub_array[] = $row["concern"];
 $sub_array[] = $row["via"];
 $sub_array[] = $row["status"];
 $sub_array[] = $row["it_desc"];
 $sub_array[] = $row["category"];
 $sub_array[] = $row["sub_category"];
 $sub_array[] = date('m/d/Y H:i',strtotime($row["date_closed"]));
 $sub_array[] = $row["tdc"];
 // $sub_array[] = $row["tdc"].' days';


 // $sub_array[] = $row["date_closed"];

 $data[] = $sub_array;
}

function get_all_data($connect)
{
 $query = "SELECT * FROM vw6";
 $result = mysqli_query($connect, $query);
 return mysqli_num_rows($result);
}

$output = array(
 "draw"    => intval($_POST["draw"]),
 "recordsTotal"  =>  get_all_data($connect),
 "recordsFiltered" => $number_filter_row,
 "data"    => $data
);

echo json_encode($output);

?>
