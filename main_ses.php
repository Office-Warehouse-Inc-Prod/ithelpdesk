<?php
// require_once 'includes/head1.php';

session_start();
error_reporting(0);
ini_set('display_errors', 0);

// if(isset($_SESSION['user_id']) ){
//   header("Location: index.php");
//   exit;
  
// }

require 'database.php';
$dflpass= 'owi123456';
if(!empty($_POST['email']) && !empty($_POST['password'])):
  
  $records = $conn->prepare('SELECT id,fname,lstname,email,password,role, tech_id, dept_id, str_num, img_name, area_num FROM users WHERE email = :email');
  $records->bindParam(':email', $_POST['email']);
  $records->execute();
  $results = $records->fetch(PDO::FETCH_ASSOC);


$user = NULL;

  if( count($results) > 0){
    $user = $results;
  }
  $message = 'Hello user';
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['dept_id'] = $results['dept_id'];
    $_SESSION['str_num'] = $results['str_num'];
    $_SESSION['tech_id'] = $results['tech_id'];
    $_SESSION['email'] = $results['email'];
    $_SESSION['password'] = $results['password'];
    $_SESSION['fname'] = $results['fname'];
    $_SESSION['lstname'] = $results['lstname'];
    $_SESSION['imguser'] = $results['img_name'];
    $_SESSION['area_num'] = $results['area_num'];
  // if(count($results) > 0 && password_verify($_POST['password'], $results['password']) && $results['role'] == 'admin' )
  if(count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'admin' )

  {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    header("Location: admin/adminpanel.php");
    exit();

  }
  elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'techsup' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    header("Location: techsupports/techdashboard.php");
    exit();
   } 
     elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'user' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['str_num'] = $results['str_num'];
    $_SESSION['password'] = $results['password'];
        if(base64_decode($_SESSION['password'])!= $dflpass) {
           header("Location: users/userpanel.php");
        }
        else {
           header("Location: users/resetpassword.php");
        }
    exit();
   }
     elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'AM' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['str_num'] = $results['str_num'];
    $_SESSION['password'] = $results['password'];
    $_SESSION['area_num'] = $results['area_num'];
    header("Location: areamanagers/dashboard.php");   
    exit();
   } 
   else {
    $message = 'Invalid log in credentials.';
  }


endif;

?>