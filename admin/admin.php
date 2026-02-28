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
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
      <a class="dropdown-item" href="change_password.php">
        <i class="fas fa-key mr-2"></i>Change Password
      </a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="../logout.php">
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
    setInterval(getNewReportCount, 5000); // every 5 seconds (DO NOT use 1s)

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



</script>
