 <?php
 include('db.php');

  $query="SELECT
reports.ticket_no as ticket_no,
reports.store as store,
reports.date_created as date_created,
reports.concern as concern,
reports.status as status,
reports.itsup as itsup,
subcat.sub_cat as sub_cat,
reports.date_closed as date_closed,
reports.cat_id as cat_id,
count(*) as num
FROM
reports
INNER JOIN subcat ON subcat.sub_id = reports.sub_id AND subcat.cat_id = reports.cat_id
WHERE reports.cat_id= ".$_GET['cid']."
GROUP BY
reports.cat_id,
subcat.sub_id ";
 $statement = $dbcon->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
foreach($result as $row)
{

 	$data[] = array('desc' => $row['sub_cat'],'num' => $row['num']);
}

echo json_encode($data);

                    ?>
                   
            

 