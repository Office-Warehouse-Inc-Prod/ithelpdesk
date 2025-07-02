<?php 

session_start();
if ($_SESSION['login']!='true'){
header("Location: index.php");
exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- This file has been downloaded from Bootsnipp.com. Enjoy! -->
    <title>OWI I.T HELPDESK</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link href="http://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet"> -->
      <meta http-equiv='cache-control' content='no-cache'>
  <meta http-equiv='expires' content='0'>
  <meta http-equiv='pragma' content='no-cache'>
<link rel="icon" href="../images/owi.ico" type="image/icon type">
<link rel="stylesheet" href="../css/4bootstrap.min.css" />
<link rel="stylesheet" href="../vendor/sweetalert/dist/sweetalert2.min.css" />
<script src="../js/jquery-3.5.1.js"></script>
<script src="../js/moment.min.js"></script>

<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" type="text/css" href="../dist/fontawesome/css/fontawesome.min.css" />

      <!-- ============= -->
  <script src="https://kit.fontawesome.com/426b4bab4c.js" crossorigin="anonymous"></script> 

      <script src="../js/jquery.timeago.js"></script>
      <script src="../js/helpdesk.js"></script> 
      <script src="../js/coms.js"></script> 
      <script src="../js/amcharts/core.js"></script>
      <script src="../js/amcharts/charts.js"></script>
      <script src="../js/amcharts/material.js"></script>
      <script src="../js/amcharts/animated.js"></script>
<!--       <script src="../js/responsive.bootstrap.min.js"></script> -->
      <script src="../js/popper.min.js"></script>
      <script src="../js/4bootstrap.min.js"></script>
      <script src="../vendor/sweetalert/dist/sweetalert2.all.min.js"></script>
    <style type="text/css">
        @import url("//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");


@media (max-width: 768px) {
html, body {
width: auto !important;
overflow-x: hidden !important;
}
}

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

.bolded {
  font-weight:bold;
 
}
th {
  color: white;
}
tr {
   color: black; 
}

    </style>
</head>
<body>
<nav class="navbar navbar-icon-top navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">HELPDESK</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="dashboard.php">
          <i class="fa fa-home"></i>
          Home
          <span class="sr-only">(current)</span>
          </a>
      </li>

    </ul>
    <ul class="navbar-nav ">

       <!-- <li class="nav-item dropdown">
        <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           <i class="fa fa-bell">
            <span class="badge badge-info" id="notif_newmsg"></span>
          </i>
          Notification
          
        </a>
        <div class="dropdown-menu"  aria-labelledby="navbarDropdown">
        <div class="col-md-12 col-xs-12">
        <table id="notif_data" class="table table-dark table-responsive table-sm" style="width: auto;"></table>
        </div>

        </div>
      </li> -->
      <li class="nav-item">
    <form class="form-inline my-2 my-lg-0">
        <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <!-- <i class="fa fa-envelope-o"> -->
           <img src="../images/users/<?= $_SESSION['imguser'];?>" alt="User Image" style="width: 35px;"/>
            <?php echo $_SESSION['fname']. '  ' . $_SESSION['lstname'];?>
          
        </a>
        <div class="dropdown-menu " aria-labelledby="navbarDropdown">
         <!-- <a class="dropdown-item" href="javascript:void(0)" onclick="pager(this.id)" id="change_password"></a> -->
          <a class="dropdown-item" href="change_password.php">Change Password</a>
          <a class="dropdown-item" href="../logout.php">Log Out</a>

        </div>
      </li>
        </ul>
    </form>
      </li>
    </ul>
  </div>
</nav>
<!-- <div class="maincontainer" id="container"></div> -->
</body>
</html>

<script type="text/javascript">
  $(document).ready(function(){
// countnewrep();
// countNwMsg();
  });



//  function countnewrep() {
  

//   setInterval(function(){

//    var xhttp = new XMLHttpRequest();
//    xhttp.onreadystatechange = function() {
//     if (this.readyState == 4 && this.status == 200) {
//      document.getElementById("notif_newrep").innerHTML = this.responseText;
//     }
//    };
//    xhttp.open("GET", "fetchdata/notif_newrep.php", true);
//    xhttp.send();

//   },1000);


//  }




//    function countNwMsg() {
  

//   setInterval(function(){

//    var xhttp = new XMLHttpRequest();
//    xhttp.onreadystatechange = function() {
//     if (this.readyState == 4 && this.status == 200) {
//      document.getElementById("notif_newmsg").innerHTML = this.responseText;
//     }
//    };
//    xhttp.open("GET", "fetchdata/fetch_newmsg.php", true);
//    xhttp.send();

//   },1000);


//  }



</script>