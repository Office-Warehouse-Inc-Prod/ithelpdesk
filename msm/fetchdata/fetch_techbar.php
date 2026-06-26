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
$sql="SELECT
reports.itsup,
it_tech.f_name AS it_name,
Count(reports.itsup) AS total,
reports.`status`,
Count( CASE reports.`status` when 'CLOSED' then 1 else null end) as completed
FROM
it_tech
LEFT JOIN reports ON reports.itsup = it_tech.itsup
WHERE
reports.sub_id NOT IN (15,28,34,35) AND reports.itsup NOT IN ('8')and
YEAR(reports.date_created) = '".$_POST['yr'] ."'
GROUP BY
reports.itsup,
YEAR(reports.date_created)
ORDER BY
reports.`status` ASC
";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	$final_array = array();
	while ($row = $result->fetch_assoc()) {
		$arr = array(
			'it_name' => $row ['it_name'],
			'total' => $row ['total'],
			'completed' => $row ['completed']
		);
		$final_array [] = $arr;
	}
	echo json_encode ($final_array);
}
else {
	return "fail"; 
}

$conn->close();
?>