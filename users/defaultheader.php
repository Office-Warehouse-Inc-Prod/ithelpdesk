<?php

session_start();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- This file has been downloaded from Bootsnipp.com. Enjoy! -->
    <title>I.T HELPDESK</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link href="http://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="../css/header-user-dropdown.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <link rel="stylesheet" href="../css/4bootstrap.min.css" />
    <script src="../js/moment.min.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/>
    <link rel="stylesheet" href="../css/dashboard.css">


    <link rel="stylesheet" href="../css/jquery.dataTables.min.css" />  
    <link rel="stylesheet" href="../css/dataTables.bootstrap4.min.css" />  
    
    <link rel="stylesheet" type="text/css" href="../css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="../vendor/sweetalert/dist/sweetalert2.min.css" />

    <script src="../js/jquery-3.5.1.js"></script>
    <script src="../js/helpdesk.js"></script> 
    <script src="../js/coms.js"></script> 
    <script src="../js/jquery.timeago.js"></script>
    <script src="https://use.fontawesome.com/f942a1dc17.js"></script>
    <script src="../js/bootstrap-datetimepicker.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/4bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/ellipsis.js"></script>
    <script src="../js/dataTables.responsive.min.js"></script>
    <script src="../dist/jquery.alphanum-master/jquery.alphanum.js"></script>
    <script src="../vendor/sweetalert/dist/sweetalert2.all.min.js"></script>
    
    <!-- Custom styles for this template -->
    <link href="../css/footer.css" rel="stylesheet">


    <style type="text/css">
        @import url("//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");

.navbar-icon-top .navbar-nav .nav-link > .fa {
  position: relative;
  width: 36px;
  font-size: 24px;
}

.navbar-icon-top .navbar-nav .nav-link > .fa > .badge {
  font-size: 0.75rem;
  position: absolute;
  right: 0;
  font-family: sans-serif;
}

.navbar-icon-top .navbar-nav .nav-link > .fa {
  top: 3px;
  line-height: 12px;
}

.navbar-icon-top .navbar-nav .nav-link > .fa > .badge {
  top: -10px;
}

@media (min-width: 576px) {
  .navbar-icon-top.navbar-expand-sm .navbar-nav .nav-link {
    text-align: center;
    display: table-cell;
    height: 70px;
    vertical-align: middle;
    padding-top: 0;
    padding-bottom: 0;
  }

  .navbar-icon-top.navbar-expand-sm .navbar-nav .nav-link > .fa {
    display: block;
    width: 48px;
    margin: 2px auto 4px auto;
    top: 0;
    line-height: 24px;
  }

  .navbar-icon-top.navbar-expand-sm .navbar-nav .nav-link > .fa > .badge {
    top: -7px;
  }
}

@media (min-width: 768px) {
  .navbar-icon-top.navbar-expand-md .navbar-nav .nav-link {
    text-align: center;
    display: table-cell;
    height: 70px;
    vertical-align: middle;
    padding-top: 0;
    padding-bottom: 0;
  }

  .navbar-icon-top.navbar-expand-md .navbar-nav .nav-link > .fa {
    display: block;
    width: 48px;
    margin: 2px auto 4px auto;
    top: 0;
    line-height: 24px;
  }

  .navbar-icon-top.navbar-expand-md .navbar-nav .nav-link > .fa > .badge {
    top: -7px;
  }
}

@media (min-width: 992px) {
  .navbar-icon-top.navbar-expand-lg .navbar-nav .nav-link {
    text-align: center;
    display: table-cell;
    height: 70px;
    vertical-align: middle;
    padding-top: 0;
    padding-bottom: 0;
  }

  .navbar-icon-top.navbar-expand-lg .navbar-nav .nav-link > .fa {
    display: block;
    width: 48px;
    margin: 2px auto 4px auto;
    top: 0;
    line-height: 24px;
  }

  .navbar-icon-top.navbar-expand-lg .navbar-nav .nav-link > .fa > .badge {
    top: -7px;
  }
}

@media (min-width: 1200px) {
  .navbar-icon-top.navbar-expand-xl .navbar-nav .nav-link {
    text-align: center;
    display: table-cell;
    height: 70px;
    vertical-align: middle;
    padding-top: 0;
    padding-bottom: 0;
  }

  .navbar-icon-top.navbar-expand-xl .navbar-nav .nav-link > .fa {
    display: block;
    width: 48px;
    margin: 2px auto 4px auto;
    top: 0;
    line-height: 24px;
  }

  .navbar-icon-top.navbar-expand-xl .navbar-nav .nav-link > .fa > .badge {
    top: -7px;
  }
}

.logo{

  color: #5383d3;
  font: normal 28px Cookie, Arial, Helvetica, sans-serif;

}
img{
  border-radius: 50px;
  width: 50px;
  padding:5px;
}

    </style>

</head>
<body>

<nav class="navbar navbar-icon-top navbar-expand-lg bg-warning navbar-dark">
  <a class="navbar-brand logo" href="#">I.T <span class="logo">Helpdesk</span></a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <!-- <span class="navbar-toggler-icon"></span> -->
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
<!--       <li class="nav-item active">
        <a class="nav-link" href="userpanel.php">
          <i class="fa fa-home"></i>
          Home
          <span class="sr-only">(current)</span>
          </a>
      </li> -->
      <li class="nav-item active">
        <a class="nav-link" href="contact_us.php" target="_blank">
          <i class="fa fas fa-phone">
            <!-- <span class="badge badge-danger"></span> -->
          </i>
          Contact Us
        </a>
      </li>
    </ul>

    <form class="form-inline my-2 my-lg-0">
        <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <!-- <i class="fa fa-envelope-o"> -->
           <img src="../images/users/<?= $_SESSION['imguser'];?>" alt="User Image" />
            <?php echo $_SESSION['fname']. '  ' . $_SESSION['lstname'];?>
          
        </a>
        <div class="dropdown-menu  " aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="javascript:void(0)" onclick="pager(this.id)" id="user_profile">Profile</a>
          <a class="dropdown-item" href="change_password.php">Change Password</a>
          <a class="dropdown-item" href="../logout.php">Log Out</a>

        </div>
      </li>
        </ul>
    </form>
  </div>
</nav>
<div class="container p-0">
<footer class="footer bg-warning">
      <div class="container">
        <span class="text-muted pull-left">Developed by: IT</span>
      </div>
    </footer>
</div>
</body>
</html>

<script type="text/javascript">
 
</script>
