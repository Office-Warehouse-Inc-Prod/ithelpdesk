<?php 

session_start();

?>


<!DOCTYPE html>
<html lang="en">
<head>
         <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <title>Helpdesk</title>
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
  <a class="navbar-brand" href="techdashboard.php">OWI HELPDESK</a>
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
 
    </ul>
        <form class="form-inline my-2 my-lg-0">
        <ul class="navbar-nav mr-auto">
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
           <img src="../images/users/<?= $_SESSION['imguser'];?>" alt="User Image" style="width: 35px;"/>
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
function getdata(){
$.post('fetchdata/fetch_data.php',{mode:'notif_support'},function(data){
// console.log(data);
notifdatas(data);
},'json');
}
getdata();

var table
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




});//document ready close

function getdata(){
$.post('fetchdata/fetch_data.php',{mode:'notif_support'},function(data){
// console.log(data);
notif_data(data);
},'json');
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





</script>