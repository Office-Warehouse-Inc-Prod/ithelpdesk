 
<?php
session_start();
$tech = $_SESSION['tech_id'];
include('db.php');
include('function.php');
$query = '';
$output = array();
$query .= "SELECT * FROM vw6 WHERE tech_list = '2-JOEL BALLESTEROS' ";
// if ($tech != ""){

//  $query .= "WHERE itsup = '2'";

// }



		
if(isset($_POST["search"]["value"]))
{
 $query .= 'or ticket_no LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR store LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR date_created LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR via LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR status LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR itsup LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR tech_list LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR category LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR sub_category LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR date_closed LIKE "%'.$_POST["search"]["value"].'%" ';

}
if(isset($_POST["order"]))
{
 $query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
 $query .= 'ORDER BY ticket_no DESC ';
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
 if($row["ticket_no"] != '');
 $sub_array = array();
 $sub_array[] = $row["ticket_no"];
 $sub_array[] = $row["store"];
 // $sub_array[] = $row["date_created"];
 $sub_array[] = date('m/d/Y H:i',strtotime($row["date_created"]));
 $sub_array[] = $row["concern"];
 $sub_array[] = $row["via"];
 $sub_array[] = $row["status"];
 // $sub_array[] = $row["itsup"];
 $sub_array[] = $row["tech_list"];
 $sub_array[] = $row["cat_list"];
 $sub_array[] = $row["sub_list"];
 $sub_array[] = date('m/d/Y H:i',strtotime($row["date_closed"]));
 $sub_array[] = '<button type="button" name="update" id="'.$row["ticket_no"].'" class="btn btn-warning btn-xs update "><i class="material-icons">update</i></button>';

 
 $sub_array[] = '<button type="button" name="delete" id="'.$row["ticket_no"].'" class="btn btn-danger btn-xs delete"><i class="material-icons">delete</i></button>';
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