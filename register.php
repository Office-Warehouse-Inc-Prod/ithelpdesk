<?php
include 'includes/head1.php';


session_start();

if( isset($_SESSION['user_id']) ){
	header("Location: /");
	exit();
}

require 'database.php';

$message = '';


if(!empty($_POST['fname']) && !empty($_POST['lstname']) && !empty($_POST['email']) && !empty($_POST['password'])):
	
	// Enter the new user in the database
	$sql = "INSERT INTO users (fname, lstname, dept_id, email, password, role, str_num) VALUES (:fname, :lstname, :dept_id, :email, :password, :role, :str_num)";
	$stmt = $conn->prepare($sql);
	$defrole = 'user'; //default value of role when registered. 

	$stmt->bindParam(':fname', $_POST['fname']);
	$stmt->bindParam(':lstname', $_POST['lstname']);
	$stmt->bindParam(':email', $_POST['email']);
	$stmt->bindParam(':dept_id', $_POST['select_dept']);
	$stmt->bindValue(':password', password_hash($_POST['password'], PASSWORD_BCRYPT));
	$stmt->bindParam(':role', $defrole);
	$stmt->bindParam(':str_num', $_POST['select_strcd']);

	// $stmt->bindValue(':password',$_POST['password']);


	if( $stmt->execute() ):
		$message = 'Successfully created new user';
		header("Location: index.php");
	else:
		$message = 'Sorry there must have been an issue creating your account';
	endif;

endif;


include 'condb.php';
$regcon=new dbconfig();
?>

<style type="text/css">

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}


	.login-form {
		width: 400px;
    	margin: 50px auto;
	}
    .login-form form {
    	margin-bottom: 15px;
        background: #f7f7f7;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        padding: 30px;
    }
    .login-form h2 {
        margin: 0 0 15px;
    }
    .form-control, .btn {
        min-height: 38px;
        border-radius: 2px;
    }
    .btn {        
        font-size: 15px;
        font-weight: bold;

    }

    input, select {
    -webkit-box-sizing: border-box;
       -moz-box-sizing: border-box;
            box-sizing: border-box;

}



</style>
<!DOCTYPE html>
<html>
<head>
	<title>Register Below</title>
  <script src="js/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link href='http://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	


</head>
<body>


<!--       <div id="demo-content">


    <div id="loader-wrapper">
      <div id="loader"></div>

      <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>

    </div>


  </div> -->





<div class="login-form">
	<form action="register.php" id="login_form"  method="POST">
	<h3>Register</h3>
	<span>or <a href="index.php">login here</a></span>
	<br />
		<?php if(!empty($message)): ?>
		<p><?= $message ?></p>
	<?php endif; ?>

<div class="row">
	

				<div class="form-group">
		<input type="text" class="form-control" placeholder="Enter your first name" name="fname" id="fname" required="" minlength="2">
	</div>
	<div class="form-group">
		<input type="text" class="form-control" placeholder="Enter your last name" name="lstname" id="fname" required="" minlength="2">
	</div>

	<div class="form-group">
 <select class="form-control" name="select_dept" id="select_dept" required>
 <option value=""> &larr; Select Department &rarr;</option>
       <?php
                $query="select * from tbl_dept";
                $run=$regcon->prepare($query);
                $run->execute();
                $rs=$run->get_result();
                while ($res=$rs->fetch_assoc()) {
                	$deptid = $res['dept_id'];
                	$deptdesc = $res['dept_desc'];
                ?>
                <option value="<?php echo $deptid;?>"><?=$deptdesc; ?></option>
                <?php }?>
                ?>   
    </select>
	</div>
		<div class="form-group">
			 <select class="form-control" name="select_strcd" id="select_strcd" required>
			 <option value="201">Assign Branch</option>
			       <?php
			                $query="select * from tbl_branch";
			                $run=$regcon->prepare($query);
			                $run->execute();
			                $rs=$run->get_result();
			                while ($res=$rs->fetch_assoc()) {
			                	$str_id = $res['str_num'];
			                	$str_desc = $res['str_code'];
			                	$str_fulldesc = $res['str_name'];
			                ?>
			                <option value="<?php echo $str_id;?>"><?=$str_desc." - ". $str_fulldesc; ?></option>
			                <?php }?>
			                ?>   
			    </select>
	</div>
	<div class="form-group">
		<input type="checkbox" id="chk_am" name="chk_am" value="Area Manager">
		<label for="chk_am">Area Manager</label>

		<input type="checkbox" id="chk_bmco" name="chk_bmco" value="Branch Manager/S.S/C.O">
		<label for="chk_am">BM/S.S/C.O</label>
	</div>
	<div class="form-group">
		<input type="text" class="form-control" placeholder="Username" name="email" required="">
	</div>
	<div class="form-group">
		<input type="password" class="form-control" placeholder="Password" name="password" id="password" required="">
	</div>
	<div class="form-group">
		<input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" id="confirm_password" required="">
	</div>
	<br/>
		<input type="submit" id="btnSubmit" value="Submit" onclick="return ValidatePassword();">



	</div>


	</form>
</div>
</body>
</html>

<script type="text/javascript">


$(document).ready(function){
	$('#fname').css('textTransform','capitalize');
}



    function ValidatePassword() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm_password").value;
        // var btnSubmit = document.getElementById("btnSubmit");
              	if(password != confirmPassword){
              		alert('Password mismatch');
              		 return false;
         		} else if (password ==  confirmPassword) {
         			alert('Account Created Successfully');
         			return true;
       			 }
    }



</script>
