<?php

session_start();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- This file has been downloaded from Bootsnipp.com. Enjoy! -->
    <title>OWI HELPDESK</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link href="http://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet"> -->
      <meta http-equiv='cache-control' content='no-cache'>
  <meta http-equiv='expires' content='0'>
  <meta http-equiv='pragma' content='no-cache'>
    <link rel="stylesheet" href="../css/header-user-dropdown.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <!-- <link rel="stylesheet" href="../css/4bootstrap.min.css" /> -->
    <script src="../js/moment.min.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/>
    <link rel="stylesheet" href="../css/dashboard.css">


    <link rel="stylesheet" href="../css/jquery.dataTables.min.css" />  
    <link rel="stylesheet" href="../css/dataTables.bootstrap4.min.css" />  
    
    <link rel="stylesheet" type="text/css" href="../css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="../vendor/sweetalert/dist/sweetalert2.min.css" />
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!--     <script src="../js/jquery-3.5.1.js"></script>
        <script src="../js/popper.min.js"></script>
    <script src="../js/4bootstrap.min.js"></script> -->
<!--     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="../js/helpdesk.js"></script>  -->
    <script src="../js/coms.js"></script> 
    <script src="../js/jquery.timeago.js"></script>
    <script src="https://use.fontawesome.com/f942a1dc17.js"></script>
    <script src="../js/bootstrap-datetimepicker.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/ellipsis.js"></script>
    <!-- <script src="../js/loadoverlay.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
    <script src="../js/dataTables.responsive.min.js"></script>
    <script src="../dist/jquery.alphanum-master/jquery.alphanum.js"></script>
    <script src="../vendor/sweetalert/dist/sweetalert2.all.min.js"></script>


    <!-- Custom styles for this template -->
    <link href="../css/footer.css" rel="stylesheet">

<style>

:root {
  --primary-color: #E1AD01;
  --primary-light: #F4F0FF;
  --bg-body: #F4F5FA;
  --sidebar-width: 260px;
  --topbar-height: 70px;
  --card-shadow: 0 4px 12px 0 rgba(58, 53, 65, 0.1);
}

body {
  font-family: 'Public Sans', sans-serif;
  background-color: var(--bg-body);
  color: #3A3541DE;
  overflow-x: hidden;
  background: linear-gradient(rgba(218, 219, 207, 0.3), rgba(113, 114, 136, 0.27)), 
    url('images/bg_login.png'); 
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  background-repeat: no-repeat;
  min-height: 100vh;
}

.owi-navbar {
  background-color: #213456 !important;
  box-shadow: 0 2px 10px 2px #66738e;
  margin-bottom: 10px;
}

/* Make links clean + readable */
.owi-navbar .nav-link,
.owi-navbar .navbar-brand {
  color: #fff !important;
  font-weight: 600;
  letter-spacing: .3px;
}

/* Icon spacing */
.owi-navbar .nav-link i {
  margin-right: 6px;
}

/* Hover states */
.owi-navbar .nav-link:hover,
.owi-navbar .navbar-brand:hover {
  opacity: .92;
}

/* Dropdown */
.owi-navbar .dropdown-menu {
  background-color: #ffffff;
  border: none;
  min-width: 220px;
  padding: .35rem;
  box-shadow: 0 12px 24px rgba(0,0,0,0.25);
  border-radius: 12px;
}

/* Dropdown items */
.owi-navbar .dropdown-item {
  color: black;
  border-radius: 10px;
  padding: .55rem .75rem;
  white-space: normal; 
}

.owi-navbar .dropdown-item i {
  margin-right: 8px;
}


.owi-navbar .dropdown-item:hover {
  background-color: #54699e;
  color: #fff;
}

.owi-navbar .dropdown-divider {
  border-top: 1px solid rgba(255,255,255,0.2);
}
.notif-dropdown {
  width: 360px;
  max-width: 92vw;
}

@media (max-width: 576px) {
  .notif-dropdown {
    width: 92vw;
  }
}

/* Badges keep visible on blue */
.owi-navbar .badge-danger {
  background-color: #ff4d4d;
}

.owi-navbar .badge-info {
  background-color: #28c7ff;
  color: #002a4a;
  font-weight: 700;
}

/* Toggler icon visibility on blue */
.owi-navbar .navbar-toggler {
  border-color: rgba(255,255,255,0.35);
}

.owi-navbar .navbar-toggler-icon {
  filter: brightness(0) invert(1);
}

/* Modern Underline Animation */
/* Modern Underline Animation Refined */
.owi-navbar .nav-item {
  position: relative;
  margin: 0 5px;
  display: flex;
  align-items: center;
}

.owi-navbar .nav-link {
  position: relative;
  padding: 0.8rem 1rem !important;
  color: rgba(255, 255, 255, 0.8) !important;
  transition: all 0.3s ease;
}

/* The Underline - Modernized */
.owi-navbar .nav-link::after {
  content: '';
  position: absolute;
  width: 0;
  height: 3px;
  bottom: 5px; /* Lifted slightly from the bottom */
  left: 50%;
  background-color: var(--primary-color);
  transition: width 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), left 0.3s ease;
  transform: translateX(-50%);
  border-radius: 10px;
}

/* Hover State */
.owi-navbar .nav-item:hover .nav-link {
  color: #fff !important;
}

.owi-navbar .nav-item:hover .nav-link::after {
  width: 70%; 
}

.owi-navbar .nav-item.active .nav-link {
  color: var(--primary-color) !important;
  font-weight: 700;
}

.owi-navbar .nav-item.active .nav-link::after {
  width: 70%; 
  background-color: var(--primary-color);
}

.owi-navbar .dropdown-menu {
  border-top: 3px solid var(--primary-color) !important;
  border-radius: 0 0 8px 8px !important;
  margin-top: 0;
}
.navbar-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  font-family: 'Orbitron', sans-serif;
  font-size: 1.4rem;
  letter-spacing: 1px;
}

.navbar-brand img {
  transition: transform 0.3s ease;
}

.navbar-brand:hover img {
  transform: rotate(-10deg) scale(1.1);
}

.owi-navbar .dropdown-menu {
  border-top: 3px solid var(--primary-color);
  margin-top: 10px;
}
</style>

</head>
<body>

<nav class="navbar navbar-icon-top navbar-expand-lg bg-warning navbar-dark">
  <a class="navbar-brand logo" href="#">OWI <span class="logo">Helpdesk</span></a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <!-- <span class="navbar-toggler-icon"></span> -->
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="userpanel.php">
          <i class="fa fa-home text-dark"></i>
         <span class="text-white">Home</span>
          <span class="sr-only">(current)</span>
          </a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="price_verifier.php" target="_blank">
          <!-- <i class="fa fas fa-phone text-dark"> -->
          <i class="fa fas fa-search text-dark"></i>
            <!-- <span class="badge badge-danger"></span> -->
          </i>
         <span class="text-white">Price Verifier</span> 
        </a>
      </li>    
      <li class="nav-item active">
        <a class="nav-link" href="contact_us.php" target="_blank">
          <i class="fa fas fa-phone text-dark">
            <!-- <span class="badge badge-danger"></span> -->
          </i>
         <span class="text-white">Contact Us</span> 
        </a>
      </li>
    </ul>

    <form class="form-inline my-2 my-lg-0">
        <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <!-- <i class="fa fa-envelope-o"> -->
           <img src="../images/users/<?= $_SESSION['imguser'];?>" alt="User Image" />
           <span class="text-dark font-weight-bold debug "><?php  echo $_SESSION['str_code']. ' |  '  .   $_SESSION['fname']. '  ' . $_SESSION['lstname'];?></span> 
           <input type="hidden" name="lm_tag" id="lm_tag" value="<?php echo $_SESSION['SBS_NO']; ?>">
           <input type="hidden" name="lm_tag" id="lm_tag" value="<?php echo $_SESSION['PRICE_LVL']; ?>">
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
  $(document).ready(function(){
    var lmtag = $('#lm_tag').val();
console.log(lmtag);

if (lmtag === 'Y') {

  // alert('LM 4');
  
} else {
  // alert('PL 1');
}
  });
 var debg = $('.debug').text();

if (debg.includes('Undefined')) {

  window.location="logout.php";  
}
</script>
