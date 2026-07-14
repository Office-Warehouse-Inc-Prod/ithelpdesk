<?php 

session_start();

?>


<!DOCTYPE html>
<html lang="en">
<head>
         <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <title>IT Helpdesk</title>
<link rel="stylesheet" href="../css/4bootstrap.min.css" />
<script src="../js/jquery-3.5.1.js"></script>
<script src="../js/moment.min.js"></script>
<link rel="stylesheet" href="../plugins/DataTables-1.10.25/media/css/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="../assets/Date-Time-Picker-Bootstrap-4/src/sass/bootstrap-datetimepicker-build.css" />
<script src="../assets/Date-Time-Picker-Bootstrap-4/src/js/bootstrap-datetimepicker.js"></script>
<link rel="stylesheet" href="styles.css" />

<script src="../plugins/DataTables-1.10.25/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<script src="../js/ellipsis.js"></script>

<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" type="text/css" href="../dist/fontawesome/css/fontawesome.min.css" />
<link rel="stylesheet" href="../vendor/sweetalert/dist/sweetalert2.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Edu+NSW+ACT+Foundation&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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


/*@media (max-width: 768px) {
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
}*/

 :root {
    --primary-color: #E1AD01;
    --primary-light: #F4F0FF;
    --bg-body: #F4F5FA;
    --sidebar-width: 260px;
    --topbar-height: 70px;
    --card-shadow: 0 4px 12px 0 rgba(58, 53, 65, 0.1);
  }

  body {
  background: linear-gradient(to bottom, #ffffff, #99aac8);
  background-attachment: fixed; 
  margin: 0; 
   overflow-x: hidden;
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

  .owi-navbar .nav-link,
  .owi-navbar .navbar-brand {
    color: #fff !important;
    font-weight: 600;
    letter-spacing: .3px;
  }

  .owi-navbar .nav-link i {
    margin-right: 2px;
    font-size: 20px;
  }

  .owi-navbar .nav-link:hover,
  .owi-navbar .navbar-brand:hover {
    opacity: .92;
  }

  .owi-navbar .dropdown-menu {
    background-color: #ffffff;
    border: none;
    min-width: 220px;
    padding: .35rem;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
    border-radius: 12px;
  }

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
    border-top: 1px solid rgba(255, 255, 255, 0.2);
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

  .owi-navbar .badge-danger {
    background-color: #ff4d4d;
  }

  .owi-navbar .badge-info {
    background-color: #28c7ff;
    color: #002a4a;
    font-weight: 700;
  }

  .owi-navbar .navbar-toggler {
    border-color: rgba(255, 255, 255, 0.35);
  }

  .owi-navbar .navbar-toggler-icon {
    filter: brightness(0) invert(1);
  }

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

  .owi-navbar .nav-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 3px;
    bottom: 5px;
    left: 50%;
    background-color: var(--primary-color);
    transition: width 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), left 0.3s ease;
    transform: translateX(-50%);
    border-radius: 10px;
  }

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

  /* Smooth flash styling for highlighting rows */
  .highlight-row {
    animation: flashYellow 2.5s ease-in-out;
  }

  @keyframes flashYellow {
    0% { background-color: #ffff99; }
    100% { background-color: transparent; }
  }

    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark owi-navbar sticky-top">
  <a class="navbar-brand" href="techdashboard.php"> <img src="../images/owi.ico" width="35" height="35" class="d-inline-block align-top" alt="OWI Logo">OWI - IT HELPDESK</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>


  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
<!--       <li class="nav-item active">
        <a class="nav-link" href="tech_header.php">
          <i class="fa fa-home"></i>
          Home
          <span class="sr-only">(current)</span>
          </a>
      </li> -->
<!--       <li class="nav-item">
        <a class="nav-link " href="javascript:void(0)" onclick="pager(this.id)" id="adminwfit">
          <i class="fa fa-envelope-o">
            <span class="badge badge-danger" id="notif_newrep"></span>
          </i>
          Unassigned Reports
        </a>
      </li> -->
<!--       <li class="nav-item ">
        <a class="nav-link" href="javascript:void(0)" onclick="pager(this.id)" id="genrep">
          <i class="fa fas fa-print">
          </i>
          Generate Report
        </a>  
      </li> -->
      <li class="nav-item netdash">
  <a class="nav-link" href="networkpanel.php" >
    <i class="fa-solid fa-ethernet"></i>
    <span>Network Maintenance</span>
  </a>
</li>
    </ul>
    
  <form action="testcalendar.php" method="POST" style="display: inline;">
    <input type="hidden" name="u_id" id="u_id" value="<?php echo $_SESSION['user_id']; ?>">
    <button type="submit" id="showCalendarBtn" class="btn btn-primary">Show Calendar</button>
</form>
        <form class="form-inline my-2 my-lg-0">
        <ul class="navbar-nav mr-auto">
           <li class="nav-item">
            <a class="nav-link" href="fix_asset.php">
              <i class="fa fa-envelope-o">
                <span class="badge badge-danger" id="fixed_asset"></span>
              </i>
              Fixed Asset
            </a>
          </li>

                <li class="nav-item dropdown">
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
      </li>
        </ul>

    <form class="form-inline my-2 my-lg-0">
        <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <!-- <i class="fa fa-envelope-o"> -->
           <img src="../images/users/<?= $_SESSION['imguser'];?>" alt="User Image" style="width: 35px; border-radius: 20px;"/>
            <?php echo $_SESSION['fname']. '  ' . $_SESSION['lstname'];?>
          
        </a>
        <div class="dropdown-menu " aria-labelledby="navbarDropdown">
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
</body>
</html>

<script type="text/javascript">

$( document ).ready(function() {
countNwMsg();
/**
 * Getdata.
 */
function getdata(){
$.post('fetchdata/fetch_data.php',{mode:'notif_support'},function(data){
// console.log(data);
notifdatas(data);
},'json');
}
getdata();

var table
/**
 * Notifdatas.
 */
function notifdatas(t){
const dataset=t.ntfsupdata;
table =  $("#notif_data").DataTable({

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

$('#notif_data tbody').on( 'click', 'tr', function () {
  var data =  table.row( this ).data();
  var ticketVal = data.ticket_no;
    $('#myInput').val(ticketVal).trigger('input');
    $.post('change_notif.php', {ticketVal: ticketVal}, function(data, textStatus, xhr) {
      getdata();
       $('#dtbsecond').click();
    });
} );

} // end of data table

var technet = $('#u_id').val();

if (technet != '8') {
  $('.nav-item.netdash').hide();
}


});//document ready close

/**
 * Getdata.
 */
function getdata(){
$.post('fetchdata/fetch_data.php',{mode:'notif_support'},function(data){
// console.log(data);
notif_data(data);
},'json');
}


   /**
    * Count nw msg.
    */
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





</script>