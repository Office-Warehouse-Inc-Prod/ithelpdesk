<?php

// ======== db  =========
include 'admin.php';
include '../condb.php';
include 'main_js.php';
include 'chrtdashboard.php';
include 'sub_graph_modal.php';
// include 'testcalendar.php';

// $conn=new dbconfig();

?>
<head>
  <link rel="stylesheet" href="adminpanel.css">
</head>
<style>


::-webkit-scrollbar {
  width: 8px;
}
::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}
::-webkit-scrollbar-thumb {
background: linear-gradient(135deg, #837031, #E1AD01);
  border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(135deg, #837031, #E1AD01);
}


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

<!-- =========================
     DASHBOARD MAIN WRAPPER
     ========================= -->
<div class="container-fluid">
  <div id="wrapper">
    <div id="layoutSidenav_content">
      <div class="container-fluid">
        <!-- Hidden Form -->
        <form method="post" name="cof_form" id="cof_form" enctype="multipart/form-data">
          <div class="row">
            <input type="hidden" name="chcksbjcls" id="chcksbjcls" value="check">
          </div>
        </form>
        <div class="action-bar-container" style="box-shadow: 0 5px 10px 2px #2d3c597f; margin-bottom: 20px;">
          <div class="year-picker-group">
            <div class="input-group">
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="fas fa-history me-2"></i>LOGS IN YEAR OF:
                </span>
              </div>
              <select class="form-control" name="yearpicker" id="yearpicker"required>
                <option value="2019,2020,2021,2022,2023,2024,2025,2026">OVERALL</option>
                <option value="2026" selected>2026</OPTION>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
              </select>
            </div>
          </div>
          <div class="d-flex align-items-center gap-3">
            
            <div class="form-check form-switch float-right m-3">
              <input class="form-check-input" style="margin-left:-50px;" type="checkbox" id="darkModeToggle">
              <label class="form-check-label text-dark" for="darkModeToggle">Dark Mode</label>
          </div>
        </div>
      </div>
    </div>

   
        <!-- CHARTS -->
        <div class="row" id="ovrall">

          <div class="col-12 col-lg-6 mb-3">
            <div class="card card2 h-100">
              <h5 class="card-header" style="background-color: #95a2b9b4; color:black;">Overall Status</h5>
              <div class="card-body">
                <div id="chartdiv1"></div>
              </div>
            </div>
          </div>

            <div class="col-12 col-lg-6 mb-3">
            <div class="card card2 h-100">
              <h5 class="card-header" style="background-color: #95a2b9b4; color:black;">Department Tickets</h5>
              <div class="card-body">
                <div id="chartdiv2"></div>
              </div>
            </div>
          </div>


          <div class="col-12 mb-3">
            <div class="card card2">
              <h5 class="card-header text-black"  style="background-color: #95a2b9b4; color:black;">Number of Escalated Reports Per Area</h5>
              <div class="card-body">
                <div id="chart_area"></div>
              </div>
            </div>
          </div>

            

          


        

        </div><!-- /#ovrall -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" />

        

        <div class="col-lg-12 Down" id="Down">
          <input type="hidden" id="myInput">
        </div>

      </div><!-- /.container-fluid -->
    </div><!-- /#layoutSidenav_content -->
  </div><!-- /#wrapper -->
</div><!-- /.container-fluid -->


<script>
  $(document).ready(function() {
    // KPI Card Click Functionality
    $('.dashcard-clickable').on('click', function() {
        const filterValue = $(this).data('filter');
      
        if ($.fn.DataTable.isDataTable('#report_data')) {
            const table = $('#report_data').DataTable();
            table.search(filterValue).draw();
        }

        $('html, body').animate({
            scrollTop: $("#report_data").offset().top - 100
        }, 600);

        $(this).fadeOut(100).fadeIn(100);
    });
});
</script>


