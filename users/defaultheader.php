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



.owi-navbar {
  background: linear-gradient(135deg, #FFDB58 0%, #D4AF37 100%);
  box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.15);
  padding: 0.5rem 1.5rem !important;
  transition: all 0.3s ease;
}

.navbar-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  font-family: 'Orbitron', sans-serif;
  font-size: 1.4rem;
  letter-spacing: 1.5px;
  font-weight: 600;
}

.owi-navbar .nav-link,
.owi-navbar .navbar-brand {
  color: #213456 !important;
  font-weight: 500;
  letter-spacing: .3px;
}

.owi-navbar .nav-item,
.owi-navbar .nav-link,
.owi-navbar .nav-item.active,
.owi-navbar .nav-item.active .nav-link,
.owi-navbar .nav-link.active {
  border-bottom: 0px solid transparent !important;
  border-top: 0px solid transparent !important;
  text-decoration: none !important;
  box-shadow: none !important;
}

.owi-navbar .nav-link {
  position: relative;
  padding: 0.8rem 1rem !important;
  color: #213456 !important;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: all 0.3s ease;
}

.owi-navbar .nav-link i {
  font-size: 1.05rem;
  color: inherit !important; 
}

.owi-navbar .badge {
  position: absolute;
  top: 4px;
  right: 0px;
  font-size: 0.65rem;
  padding: 3px 6px;
  border-radius: 50rem;
  font-weight: 700;
  line-height: 1;
}

.owi-navbar .badge-danger {
  background-color: #ff4d4d !important;
  color: #fff !important;
}

.owi-navbar .badge-info {
  background-color: #28c7ff !important;
  color: #002a4a !important;
}

.owi-navbar .nav-item {
  position: relative;
  margin: 0 3px;
}

.owi-navbar .nav-link::after {
  content: '';
  position: absolute;
  width: 0;
  height: 3px;
  bottom: 0px; 
  left: 50%;
  background-color: var(--primary-color) !important;
  transition: width 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), left 0.3s ease;
  transform: translateX(-50%);
  border-radius: 10px;
}

.owi-navbar .nav-item:hover .nav-link {
  color: linear-gradient(135deg, #FFDB58 0%, #D4AF37 100%) !important;
  opacity: 1;
}

.owi-navbar .nav-item:not(.dropdown):hover .nav-link::after {
  width: 70%; 
}

.owi-navbar .nav-item.active .nav-link {
  color: var(--primary-color) !important;
  font-weight: 700;
}

.owi-navbar .nav-item.active:not(.dropdown) .nav-link::after {
  width: 70% !important; 
  background-color: var(--primary-color) !important;
}

.owi-navbar .dropdown-menu {
  background-color: #ffffff !important;
  border: none !important;
  min-width: 240px;
  padding: 0.5rem !important;
  box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
  border-radius: 8px !important;
  border-top: 4px solid var(--primary-color) !important;
  margin-top: 5px !important;
}

.owi-navbar .dropdown-item {
  color: #1f2937 !important;
  background-color: transparent !important;
  border-radius: 6px;
  padding: 0.6rem 0.9rem;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: all 0.2s ease;
}

.owi-navbar .dropdown-item i {
  width: 20px;
  text-align: center;
  color: #4b5563 !important; 
  transition: color 0.2s ease;
}

.owi-navbar .dropdown-item:hover {
  background-color: #b5c1d7 !important;
  color: linear-gradient(135deg, #FFDB58 0%, #D4AF37 100%);
}

.owi-navbar .dropdown-item:hover i {
  color: linear-gradient(135deg, #FFDB58 0%, #D4AF37 100%); 
}

.owi-navbar .change-pass {
  color: #1e40af !important;
}
.owi-navbar .change-pass i {
  color: #1e40af !important;
}
.owi-navbar .change-pass:hover {
  background-color: #eff6ff !important;
}

.owi-navbar .logout-btn {
  color: #dc2626 !important;
  font-weight: 600;
}
.owi-navbar .logout-btn i {
  color: #dc2626 !important;
}
.owi-navbar .logout-btn:hover {
  background-color: #fef2f2 !important;
}

.owi-navbar .dropdown-divider {
  border-top: 1px solid #e5e7eb !important;
  margin: 0.4rem 0;
}

.notif-dropdown {
  width: 360px;
  max-width: 92vw;
}

.owi-navbar .navbar-toggler {
  border-color: rgba(255,255,255,0.25);
  padding: 0.4rem 0.6rem;
}

.owi-navbar .navbar-toggler-icon {
  filter: brightness(0) invert(1);
}

  .notification-dropdown-wrapper {
  position: relative;
}

.notif-bell-icon {
  font-size: 1.25rem;
  color: #ffffff;
  transition: color 0.2s ease;
}
.nav-link:hover .notif-bell-icon {
  color: linear-gradient(135deg, #FFDB58 0%, #D4AF37 100%);
}

.notif-badge {
  position: absolute;
  top: 4px;
  right: 2px;
  font-size: 0.65rem;
  font-weight: 700;
  padding: 0.25em 0.5em;
  border-radius: 50rem;
  border: 2px solid #fff; 
  transform: translate(20%, -20%);
}
.notif-badge:empty {
  display: none;
}

.modern-notif-dropdown {
  width: 340px;
  max-width: 90vw;
  padding: 0;
  border-radius: 12px !important; 
  margin-top: 10px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
}

.notif-scroll-area {
  max-height: 400px;
  overflow-y: auto;
}
.notif-scroll-area::-webkit-scrollbar {
  width: 5px;
}
.notif-scroll-area::-webkit-scrollbar-track {
  background: transparent;
}
.notif-scroll-area::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}

.style-clean-table {
  border-collapse: collapse;
}
.style-clean-table tr {
  border-bottom: 1px solid #f1f5f9;
  transition: background-color 0.15s ease;
}
.style-clean-table tr:last-child {
  border-bottom: none;
}
.style-clean-table tr:hover {
  background-color: #f8fafc;
}

.notif-cell {
  padding: 12px 16px !important;
  display: block;
}
.notif-cell.unread {
  background-color: #f0f9ff;
}
.notif-cell.unread:hover {
  background-color: #e0f2fe;
}

.notif-text {
  font-size: 0.875rem;
  color: #334155;
  line-height: 1.4;
  white-space: normal; 
}
.notif-date {
  font-size: 0.75rem;
  display: block;
  margin-top: 4px;
  color: #94a3b8 !important;
}

#notif_dataxx thead {
  display: none;
}

#notif_dataxx td {
  padding: 0 !important;
  border-top: none !important;
}

.custom-notif-icon-badge {
  background-color: linear-gradient(135deg, #FFDB58 0%, #D4AF37 100%);
  color: #ffffff !important;
  width: 32px;
  height: 32px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%; 
  font-size: 0.85rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.notif-cell {
  padding: 12px 16px !important;
}

    </style>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark owi-navbar sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="adminpanel.php">
        <img src="../images/owi.ico" width="35" height="35" class="d-inline-block align-top" alt="OWI Logo">
        <span>OWI <span style="color: var(--primary-color);">HELPDESK</span></span>
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
         

          <li class="nav-item">
            <a class="nav-link" href="contact_us.php">
              <i class="fa fas fa-phone">
                <span class="badge badge-danger" id="notif_newrep"></span>
              </i>
              CONTACT US
            </a>
          </li>

         
        </ul>

        <ul class="navbar-nav ml-auto">
       

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown">
              <img class="rounded-circle border border-light" src="../images/users/<?= $_SESSION['imguser']; ?>" alt="User" style="width: 30px; height: 30px; object-fit: cover;">
              <span class="ml-2 d-none d-lg-inline">
                <?php echo $_SESSION['fname'] . ' ' . $_SESSION['lstname']; ?>
              </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
              <a class="dropdown-item" href="change_password.php"><i class="fas fa-key mr-2"></i>Change Password</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item text-danger" href="../logout.php"><i class="fas fa-sign-out-alt mr-2"></i>Log Out</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
<div class="container p-0">
<footer class="footer" style="background: linear-gradient(135deg, #FFDB58 0%, #D4AF37 100%); ">
      <div class="container">
        <span class=" pull-left" style="color: #213456;">Developed by: IT </span>
      </div>
    </footer>
</div>
</body>
</html>

<script type="text/javascript">
 
</script>
