     
<?php
session_start();
/**
 * 
 */
class dbconn {
	
var $connection = false;
 function __construct()
	{
$username = 'root';
$password = '';
$this->connection = new PDO( 'mysql:host=localhost;dbname=helpdesk1', $username, $password );

return $this->connection;
	
	}

	public function conn(){
if ($this->connection) {
echo "connected";
}else{
	echo "error";
}

	}
}
// $init = new dbconn();
// $init->conn();


?>