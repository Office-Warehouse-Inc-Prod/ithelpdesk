<?php


session_start();

if ($_SESSION['login']!='true'){
    header("Location: index.php");
    exit();
}

$tchnum = $_SESSION['tech_id'];
$msgcnt = '0'; // read
$nmsgcnt= '1'; // new msg from user"
$unmsgcnt= '2'; // user"
// $usernum = $_SESSION['id'];
date_default_timezone_set("Asia/Manila");

include('db.php');
include('function.php');
if(isset($_POST["Modal_operation"]))
{

 

 if($_POST["Modal_operation"] == "Add")
 { 


   echo 'Replied successfully.';
 }
}

?>