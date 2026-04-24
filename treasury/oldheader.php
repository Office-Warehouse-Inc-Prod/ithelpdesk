 <?php

session_start();

if ($_SESSION['login']!='true'){
    header("Location: index.php");
    exit();
    
}


// ======== header =========
// include 'plug.php';

?>
<!DOCTYPE HTML>
<html lang="en">
 <head>

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- ================== PLUG INS =============================== -->
  <title>TechReports</title>
  <link rel="stylesheet" href="../css/header-user-dropdown.css">
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
  <link rel="stylesheet" href="../css/4bootstrap.min.css" />
  <script src="../js/moment.min.js"></script>
  <link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/>
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/jquery.dataTables.min.css" />  
  <link rel="stylesheet" type="text/css" href="../css/responsive.dataTables.min.css" />
  <script src="../js/jquery-3.5.1.js"></script>
  <script src="../js/helpdesk.js"></script> 
  <script src="../js/jquery.timeago.js"></script>
  <script src="https://use.fontawesome.com/f942a1dc17.js"></script>
  <script src="../js/bootstrap-datetimepicker.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/4bootstrap.min.js"></script>
  <script src="../js/jquery.dataTables.min.js"></script>
  <script src="../js/ellipsis.js"></script>
  <script src="../js/dataTables.responsive.min.js"></script>
  <script src="../dist/jquery.alphanum-master/jquery.alphanum.js"></script>

 <body>

  <header class="header-user-dropdown fixed-top">

  <div class="header-limiter ">
    <h1><a href="javascript:void(0)" onclick="pager(this.id)" id="user_panel">Helpdesk<span>System</span></a></h1>

    <nav>

            <a href="contact_us.php" target="_blank">Contact Us</a><i class="fas fa-phone"></i>
    </nav>

    <div class="header-user-menu">
      <img src="../images/users/<?= $_SESSION['imguser'];?>" alt="User Image"/>

      <ul>
        <li><a href="#">Settings</a></li>
        <li><a href="javascript:void(0)" onclick="pager(this.id)" id="changepass">Change Password</a></li>

        <!-- <li><a href="changepass.php">Change password</a></li> -->
        <li class="divider"><a href="../logout.php" class="highlight">Logout</a></li>
      </ul>
    </div>

  </div>

</header>

<div class="maincontainer" id="container"></div>
</body>

</html>


<script type="text/javascript">
  $(document).ready(function(){
pager('userpanel');
  });
 
</script>

