<?php 

$dbhost = 'localhost';
$dbname = 'helpdesk1';
$dbuser = 'root';
$dbpass = '';

try{
	$dbcon = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
	$dbcon -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}

$stmt = $dbcon->prepare("SELECT cat_id, cat_desc, num,sub_cat FROM vwc1");
$stmt->execute();


$row1 = [];
while($row=$stmt->fetch(PDO::FETCH_ASSOC))
{
	extract($row);
	$row1[] = [ 'type' => $cat_desc , 'percent' => $num,'id' => $cat_id];
	
}

echo  json_encode($row1);

 ?>