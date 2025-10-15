<?php
// require_once 'includes/head1.php';

session_start();
error_reporting(0);
ini_set('display_errors', 0);

// if(isset($_SESSION['user_id']) ){
//   header("Location: index.php");
//   exit;
  
// }
// p 
require 'database.php';
$dflpass= 'owi123456';
if(!empty($_POST['email']) && !empty($_POST['password'])):
  
  $records = $conn->prepare("SELECT
	users.id, 
	users.fname, 
	users.lstname, 
	users.email, 
	users.`password`, 
	users.tech_id, 
	users.role, 
	users.dept_id, 
	users.str_num, 
	users.img_name, 
	users.area_num, 
	users.usr_stat,
  users.deptsel, 
	tbl_branch.str_code AS str_code,
	tbl_branch.str_adrs AS str_adrs,
	tbl_branch.str_contact AS str_contact,
  tbl_branch.SBS_NO AS SBS_NO,
  tbl_branch.PRICE_LVL AS PRICE_LVL
FROM
	users
	LEFT JOIN
	tbl_branch
	ON 
		users.str_num = tbl_branch.str_num
WHERE
	users.usr_stat = 'A' AND 
	email = :email");
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
    $_SESSION['str_code'] = $results['str_code'];
    $_SESSION['str_adrs'] = $results['str_adrs'];
    $_SESSION['str_contact'] = $results['str_contact'];
    $_SESSION['deptsel'] = $results['deptsel'];
    $_SESSION['SBS_NO'] = $results['SBS_NO'];
    $_SESSION['PRICE_LVL'] = $results['PRICE_LVL'];
  // if(count($results) > 0 && password_verify($_POST['password'], $results['password']) && $results['role'] == 'admin' )
  if(count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'admin' )

  {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    header("Location: it/adminpanel.php");
    exit();

  }
  elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'admin-admin' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['deptsel'] = $results['deptsel'];
    header("Location: admin/adminpanel.php");
    exit();
   } 
   elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'admin-user' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['deptsel'] = $results['deptsel'];
    header("Location: adminsupport/techdashboard.php");
    exit();
   } 
  elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'mktg-admin' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['deptsel'] = $results['deptsel'];
    header("Location: mktg/adminpanel.php");
    exit();
   } 
   elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'mktg-user' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['deptsel'] = $results['deptsel'];
    header("Location: mktgsupport/techdashboard.php");
    exit();
   } 
  elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'ld-admin' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    header("Location: ldlocal/adminpanel.php");
    exit();
   } 
   elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'ld-user' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['deptsel'] = $results['deptsel'];
    header("Location: logisticsupport/techdashboard.php");
    exit();
   }
   elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'ld-import' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    header("Location: ldimport/adminpanel.php");
    exit();
   }  
   elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'pd-local' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    header("Location: pdlocal/adminpanel.php");
    exit();
   } 
   elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'pd-import' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    header("Location: pdimport/adminpanel.php");
    exit();
   }
   elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'icg-admin' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    header("Location: icg/adminpanel.php");
    exit();
   }  
  elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'techsup' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    header("Location: techsupports/techdashboard.php");
    exit();
   }
   elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'visual-admin' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['deptsel'] = $results['deptsel'];
    header("Location: visual/adminpanel.php");
    exit();
   } 
   elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'visual-user' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['deptsel'] = $results['deptsel'];
    header("Location: visualsupports/techdashboard.php");
    exit();
   }
   elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'sales-admin' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['deptsel'] = $results['deptsel'];
    header("Location: sales/adminpanel.php");
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
   elseif (count($results) > 0 && base64_encode($_POST['password']) == $results['password'] && $results['role'] == 'GM' ) {
    $_SESSION['login'] = 'true';
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['str_num'] = $results['str_num'];
    $_SESSION['password'] = $results['password'];
    header("Location: generalmanager/dashboard.php");   
    exit();
   }  
   else {
    $message = 'Invalid log in credentials.';
  }


endif;

?>
