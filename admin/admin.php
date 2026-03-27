<?php 
session_start();
if ($_SESSION['login']!='true'){
header("Location: index.php");
exit();
}
 date_default_timezone_set('Asia/Manila');

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>OWI HELPDESK</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="../images/owi.ico" type="image/icon type">
<link rel="stylesheet" href="../css/4bootstrap.min.css" />
<link rel="stylesheet" href="../vendor/sweetalert/dist/sweetalert2.min.css" />
<script src="../js/jquery-3.5.1.js"></script>
<script src="../js/moment.min.js"></script>
<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" type="text/css" href="../dist/fontawesome/css/fontawesome.min.css" />
<script src="https://kit.fontawesome.com/426b4bab4c.js" crossorigin="anonymous"></script> 
<script src="../js/jquery.timeago.js"></script>
<script src="../js/adminhelpdesk.js"></script> 
<script src="../js/coms.js"></script> 
<script src="../js/amcharts/core.js"></script>
<script src="../js/amcharts/charts.js"></script>
<script src="../js/amcharts/material.js"></script>
<script src="../js/amcharts/animated.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/4bootstrap.min.js"></script>
<script src="../vendor/sweetalert/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="../plugins/DataTables-1.10.25/media/css/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.css"/>
<link rel="stylesheet" href="../assets/Date-Time-Picker-Bootstrap-4/src/sass/bootstrap-datetimepicker-build.css" />
<link href="https://fonts.googleapis.com/css2?family=Edu+NSW+ACT+Foundation&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />

<script src="../assets/Date-Time-Picker-Bootstrap-4/src/js/bootstrap-datetimepicker.js"></script>
<!-- <link rel="stylesheet" href="styles.css" /> -->
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600&display=swap" rel="stylesheet">
<script src="../plugins/DataTables-1.10.25/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.js"></script>
<script src="../js/ellipsis.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
</head>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>

#notif_newrep{
    margin-left:6px;
    font-size:11px;
    padding:4px 6px;
}



/* Change Password */
.change-pass {
    color: #1e3a8a !important; /* navy blue */
    font-weight: 500;
}

.change-pass:hover {
    background-color: #f1f5ff;
    color: #1e3a8a !important;
}

/* Logout */
.logout-btn {
    color: #dc2626 !important; /* red */
    font-weight: 600;
}

.logout-btn:hover {
    background-color: #ffe5e5;
    color: #dc2626 !important;
}


/* Force dropdown text color */
.dropdown-menu .dropdown-item {
    color: #1f2937 !important; /* dark gray */
}

/* Change Password */
.dropdown-menu .change-pass {
    color: #1e3a8a !important; /* navy */
}

/* Logout */
.dropdown-menu .logout-btn {
    color: #dc2626 !important; /* red */
    font-weight: 600;
}

/* Hover behavior */
.dropdown-menu .dropdown-item:hover {
    background-color: #f3f4f6;
    color: inherit !important;
}

/* Make icons visible and aligned */
.dropdown-menu .dropdown-item i {
    width: 18px;
    margin-right: 8px;
    color: inherit !important;
}

/* Change Password */
.change-pass {
    color: #1e40af !important; /* navy */
}

/* Logout */
.logout-btn {
    color: #dc2626 !important; /* red */
    font-weight: 600;
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

<body>
  
<nav class="navbar navbar-icon-top navbar-expand-lg navbar-dark bg-dark">
<a class="navbar-brand" href="#">OWI HELPDESK</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarSupportedContent">
<ul class="navbar-nav mr-auto">
<li class="nav-item active">
<a class="nav-link" href="adminpanel.php">
<i class="fa fa-home"></i>
HOME
<span class="sr-only">(current)</span>
</a>
</li>
<li class="nav-item">
<a class="nav-link " href="adminwfit.php">
<i class="fa fa-envelope-o">
  <span class="badge badge-danger" id="notif_newrep"></span>
</i>
NEW REPORTS
</a>
</li>
<li class="nav-item">
<a class="nav-link " href="admintransfer.php">
<i class="fa fa-envelope-o">
  <span class="badge badge-danger" id="notif_transfer"></span>
</i>
REQUEST TO TRANSFER
</a>
</li>
<li class="nav-item">

</li>
<!-- <li class="nav-item">
<a class="nav-link " href="adminwfit.php">
<i class="fa fa-file-signature">
  <span class="badge badge-danger" id="">2</span>
</i>
NEW SUPPLIES REQUEST
</a>
</li> -->
<li class="nav-item dropdown">
<a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<i class="fa fa-chart-line"></i>
</i>
GENERATE REPORT
</a>
<div class="dropdown-menu" aria-labelledby="navbarDropdown" style="background-color: rgb(52,58,64);">
<!-- <a class="dropdown-item text-white"  href="genreports.php"><i class="fa fa-calendar-day"></i> Generate Report By Area</a> -->
<a class="dropdown-item text-white" href="genrep_bycat.php"><i class="fa fa-calendar-day"></i>Generate By Categories</a>
<div class="dropdown-divider"></div>
<!-- <a class="dropdown-item" href="#">Something else here</a> -->
</div>
</li>

</ul>
<ul class="navbar-nav ml-auto"> 
<!-- <li class="nav-item">
  <a class="nav-link" href="networkpanel.php" >
    <i class="fa-solid fa-ethernet"></i>
    <span>Network Maintenance</span>
  </a>
</li> -->
<!-- Changed mr-auto to ml-auto for right alignment -->
  <li class="nav-item dropdown">
    <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fa fa-sliders-h"></i>
      Maintenance
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="background-color: rgb(52,58,64);">
      <a class="dropdown-item text-white" href="user_maintenance.php">
        <i class="fas fa-user-cog"></i> User Maintenance
      </a>
      <a class="dropdown-item text-white" href="store_maintenance.php">
        <i class="fas fa-store"></i> Store Maintenance
      </a>
    </div>
  </li>
  
  <li class="nav-item dropdown">
    <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fa fa-bell">
        <span class="badge badge-info" id="notif_newmsg"></span>
      </i>
      Notification
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
      <div class="col-md-12 col-xs-12">
        <table id="notif_dataxx" class="table text-dark table-dark table-responsive table-sm bg-dark" style="width: auto;"></table>
      </div>
    </div>
  </li>
  
  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <img class="rounded-circle" src="../images/users/<?= $_SESSION['imguser'];?>" alt="User Image" style="width: 35px; height: 35px; object-fit: cover;">
      <span class="ml-2 d-none d-lg-inline"><?php echo $_SESSION['fname'].' '.$_SESSION['lstname']; ?></span>
    </a>
    <!-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
      <a class="dropdown-item" href="change_password.php">
        <i class="fas fa-key mr-2"></i>Change Password
      </a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="../logout.php">
        <i class="fas fa-sign-out-alt mr-2"></i>Log Out
      </a>
    </div> -->

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
  
  <a class="dropdown-item change-pass" href="change_password.php">
    <i class="fas fa-key mr-2"></i>Change Password
  </a>

  <div class="dropdown-divider"></div>

  <a class="dropdown-item logout-btn" href="../logout.php">
    <i class="fas fa-sign-out-alt mr-2"></i>Log Out
  </a>

</div>
  </li>
</ul>
</div>
</nav>


<script type="text/javascript">
$(document).ready(function(){
// countnewrep();
countNwMsg();

function getdata(){
    $.post('fetchdata/fetch_data.php', { mode: 'notif_support' }, function(data){
        // console.log(data);
        notifdatas(data);
    }, 'json');
}

// Run getdata() every 1 second
setInterval(getdata, 1000);


var table
function notifdatas(t){
const dataset=t.ntfsupdata;
table =  $("#notif_dataxx").DataTable({

"dom":
'<"pull-left"lf><"pull-right">tip',
// stateSave: true,
"pagingType": "full_numbers",
"bDestroy": true,
"responsive": true, "lengthChange": false, "autoWidth": false,
"bInfo": false,
"bFilter": false,
"paging": false,
"select": true,
"pageLength":10,
"language": {
"emptyTable": "No new Notification"
},
"data": dataset,
// "order": [[ 0, "Asc" ]],

"columns": [

{title:"NOTIFICATION", data:'notif_data',"defaultContent": ""}
],
"columnDefs": [
{
targets: 0,
className: 'bolded'
}
]

});

$('#notif_dataxx tbody').on('click', 'tr', function () {
    var data = table.row(this).data();
    var ticketVal = data.ticket_no;

    $('#myInput').val(ticketVal).trigger('input');

    $.post('change_notif.php', { ticketVal: ticketVal }, function(data, textStatus, xhr) {
        getdata();
    });

    // Scroll to bottom smoothly after click
  $('html, body').animate(
        { scrollTop: $(document).height() },
        800,
        'swing',
        function () {
            // Add highlight effect
            let tableDiv = $('#report_data');
            tableDiv.css('transition', 'background-color 0.8s');
            tableDiv.css('background-color', '#ffff99'); // highlight yellow

            setTimeout(() => {
                tableDiv.css('background-color', '#ffffff'); // back to white
            }, 800); // delay before returning to white
        }
    );
});


} // end of data table


});




// function countnewrep() {


// setInterval(function(){

// var xhttp = new XMLHttpRequest();
// xhttp.onreadystatechange = function() {
// if (this.readyState == 4 && this.status == 200) {
// document.getElementById("notif_newrep").innerHTML = this.responseText;
// }
// };
// xhttp.open("GET", "fetchdata/notif_newrep.php", true);
// xhttp.send();

// },1000);


// }




function countNwMsg() {


setInterval(function(){

var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
document.getElementById("notif_newmsg").innerHTML = this.responseText;
}
};
xhttp.open("GET", "fetchdata/fetch_newmsg.php", true);
xhttp.send();

},1000);


}




document.addEventListener("DOMContentLoaded", function () {

    getNewReportCount();      // run immediately
    getTransferCount();
    setInterval(getNewReportCount,getTransferCount, 5000); // every 5 seconds (DO NOT use 1s)

});


async function getNewReportCount() {

    try {

        const response = await fetch("fetchdata/notif_newrep.php?_=" + Date.now());
        const count = (await response.text()).trim();

        const badge = document.getElementById("notif_newrep");

        if (!badge) return;

        if (count === "0" || count === "") {
            badge.style.display = "none";
        } else {
            badge.style.display = "inline-block";
            badge.innerHTML = count;
        }

    } catch (error) {
        console.error("Notification count error:", error);
    }
}


async function getTransferCount() {

    try {

        const xresponse = await fetch("fetchdata/notif_transfer.php?_=" + Date.now());
        const xcount = (await xresponse.text()).trim();

        const xbadge = document.getElementById("notif_transfer");

        if (!xbadge) return;

        if (xcount === "0" || xcount === "") {
            xbadge.style.display = "none";
        } else {
            xbadge.style.display = "inline-block";
            xbadge.innerHTML = xcount;
        }

    } catch (error) {
        console.error("Notification count error:", error);
    }
}



</script>

<script type="text/javascript">
$(document).ready(function(){
countnewrep();
countNwMsg();

function getdata(){
    $.post('fetchdata/fetch_data.php', { mode: 'notif_support' }, function(data){
        // console.log(data);
        notifdatas(data);
    }, 'json');
}

// Run getdata() every 1 second
setInterval(getdata, 1000);


var table
function notifdatas(t){
const dataset=t.ntfsupdata;
table =  $("#notif_dataxx").DataTable({

"dom":
'<"pull-left"lf><"pull-right">tip',
// stateSave: true,
"pagingType": "full_numbers",
"bDestroy": true,
"responsive": true, "lengthChange": false, "autoWidth": false,
"bInfo": false,
"bFilter": false,
"paging": false,
"select": true,
"pageLength":10,
"language": {
"emptyTable": "No new Notification"
},
"data": dataset,
// "order": [[ 0, "Asc" ]],

"columns": [

{title:"NOTIFICATION", data:'notif_data',"defaultContent": ""}
],
"columnDefs": [
{
targets: 0,
className: 'bolded'
}
]

});

$('#notif_dataxx tbody').on('click', 'tr', function () {
    var data = table.row(this).data();
    var ticketVal = data.ticket_no;

    $('#myInput').val(ticketVal).trigger('input');

    $.post('change_notif.php', { ticketVal: ticketVal }, function(data, textStatus, xhr) {
        getdata();
    });

    // Scroll to bottom smoothly after click
  $('html, body').animate(
        { scrollTop: $(document).height() },
        800,
        'swing',
        function () {
            // Add highlight effect
            let tableDiv = $('#report_data');
            tableDiv.css('transition', 'background-color 0.8s');
            tableDiv.css('background-color', '#ffff99'); // highlight yellow

            setTimeout(() => {
                tableDiv.css('background-color', '#ffffff'); // back to white
            }, 800); // delay before returning to white
        }
    );
});


} // end of data table


});



function countnewrep() {


setInterval(function(){

var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
document.getElementById("notif_newrep").innerHTML = this.responseText;
}
};
xhttp.open("GET", "fetchdata/notif_newrep.php", true);
xhttp.send();

},1000);


}




function countNwMsg() {


setInterval(function(){

var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
document.getElementById("notif_newmsg").innerHTML = this.responseText;
}
};
xhttp.open("GET", "fetchdata/fetch_newmsg.php", true);
xhttp.send();

},1000);


}

// Auto-detect current page and set 'active' class
var currentUrl = window.location.pathname.split("/").pop();

// If index or empty, default to home
if (currentUrl === "" || currentUrl === "index.php") {
    currentUrl = "adminpanel.php"; 
}

$('.navbar-nav .nav-item').each(function() {
    var $this = $(this);
    var linkHref = $this.find('a').attr('href');

    // Remove default 'active' class first to prevent duplicates
    $this.removeClass('active');

    // Check if the link href matches the current URL
    if (linkHref === currentUrl) {
        $this.addClass('active');
    }
    
    // Special case for dropdown items
    if ($this.hasClass('dropdown')) {
        $this.find('.dropdown-item').each(function() {
            if ($(this).attr('href') === currentUrl) {
                $this.addClass('active'); // Highlight parent if child is active
            }
        });
    }
});

</script>

