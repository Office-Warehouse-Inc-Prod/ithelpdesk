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

$sql = "SELECT * FROM vwc1";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
	
	$final_array = array ();
	while ($row = $result->fetch_assoc()) {
		$arr = array (
			'cat_name' => $row['cat_desc'],
			'cat_num'  => $row['num']

		);
		$final_array [] =$arr;
	}
	return json_encode($final_array);




} else {
	return "FAILURE";
}


$conn->close(); 
?> 



