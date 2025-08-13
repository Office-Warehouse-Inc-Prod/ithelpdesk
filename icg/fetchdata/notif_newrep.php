 <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "helpdesk1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT
	reports.deptsel AS deptsel,
	reports.ticket_no AS ticket_no,
	reports.date_created AS date_created,
	reports.store AS store,
	tbl_branch.str_code AS str_code,
	reports.concern AS concern,
	reports.service_desc AS service_desc,
	reports.`subject` AS `subject`,
	reports.`status` AS `status`,
	reports.userId AS userId,
	reports.via AS via,
	reports.itsup AS itsup,
	it_tech.it_desc AS it_desc,
	reports.cat_id AS cat_id,
	categories.cat_desc AS cat_desc,
	concat_ws( '-', `reports`.`cat_id`, `categories`.`cat_desc` ) AS cat_x,
	reports.sub_id AS sub_id,
	subcat.sub_cat AS sub_cat,
	reports.date_closed AS date_closed,
	reports.remarks AS remarks,
	reports_msgcnt.msg_cnt AS msg_cnt,
	reports_newmsg.nmsg_stat AS nmsg_stat,
	users.fname AS fname,
	users.lstname AS lstname,
	concat_ws( '-', `users`.`fname`, `users`.`lstname` ) AS full_name,
	item_masterfile.ALU,
	item_masterfile.Desc1,
	item_masterfile.Desc2,
	reports.serial_no,
	reports.multitag
FROM
	(
		(
			(
				(
					(
						(
							( reports JOIN tbl_branch ON ( tbl_branch.str_num = reports.store ) )
							LEFT JOIN it_tech ON ( it_tech.itsup = reports.itsup ) 
						)
						LEFT JOIN categories ON ( categories.cat_id = reports.cat_id ) 
					)
					LEFT JOIN subcat ON ( subcat.sub_id = reports.sub_id ) 
				)
				LEFT JOIN reports_msgcnt ON ( reports_msgcnt.ticket_no = reports.ticket_no ) 
			)
			LEFT JOIN reports_newmsg ON ( reports_newmsg.ticket_no = reports.ticket_no ) 
		)
		LEFT JOIN users ON ( users.id = reports.userId ) 
	)
	LEFT JOIN item_masterfile ON reports.alu = item_masterfile.ALU 
WHERE reports.status = 'NEW REPORT' 
	AND reports.deptsel = '4' 
	AND reports.service_desc IN ('LOCAL', 'IMPORT')
ORDER BY
	reports.series_id DESC
";
$result = $conn->query($sql);

echo $result->num_rows;
/*
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Notification: " . $row["description"];
    }
} else {
    echo "0 results";
}
*/
$conn->close();
?>