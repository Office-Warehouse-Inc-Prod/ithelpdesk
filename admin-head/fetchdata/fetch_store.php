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
$sql = "
    select `reports`.`store` AS `store`,`tbl_branch`.`str_code` AS `str_code`,`tbl_branch`.`area_num` AS `area_num`,`tbl_area`.`area_desc` AS `area_desc`,year(`reports`.`date_created`) AS `dc`,count(`reports`.`date_created`) AS `cnt_ttl` from ((`reports` join `tbl_branch` on(`reports`.`store` = `tbl_branch`.`str_num`)) join `tbl_area` on(`tbl_area`.`area_num` = `tbl_branch`.`area_num`)) WHERE YEAR(`reports`.`date_created`) = '".$_POST['yr'] ."' AND area_desc = '".$_POST['area_desc'] ."' group by str_code, area_desc ORDER BY str_code ASC
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $final_array = array();
  while ($row = $result->fetch_assoc()) {
    $arr = array(
      'str_code' => $row ['str_code'],
      'cnt_ttl' => $row ['cnt_ttl']
    
    );
    $final_array [] = $arr;
  }
  echo json_encode ($final_array);
}
else {
  echo "fail"; 
}

$conn->close();
?>