<?php
$servername = "localhost";
$username = 'root';
$password = '';
$db="helpdesk1";
$concat = mysqli_connect($servername, $username, $password,$db);
$connection = new PDO( 'mysql:host=localhost;dbname=helpdesk1', $username, $password );

?>