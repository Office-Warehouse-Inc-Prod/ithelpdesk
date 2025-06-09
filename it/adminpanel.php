<?php

// ======== db  =========
include 'admin.php';
include '../condb.php';
include 'main_js.php';
include 'chrtdashboard.php';
include 'sub_graph_modal.php';

// $conn=new dbconfig();




?>
<style>

 body {
font-family: 'Poppins', Tahoma, Geneva, Verdana, sans-serif;
  }

  body.dark-mode {
    background: linear-gradient(145deg, #0f0f0f, #1a1a1a);
    color: #e0e0e0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  body.dark-mode .card,
  body.dark-mode .card2 {
    background: linear-gradient(145deg, #1a1a1a, #2a2a2a);
    color: #ffffff;
    border: 1px solid #2a2a2a;
    box-shadow: 0 0 10px rgba(0, 255, 255, 0.1);
    border-radius: 15px;
    transition: all 0.3s ease;
  }

  body.dark-mode .card-header {
    background-color: transparent;
    border-bottom: 1px solid #444;
    font-weight: bold;
    font-size: 18px;
    text-shadow: 0 0 5px rgba(0, 255, 255, 0.4);
  }

  body.dark-mode .form-check-label,
  body.dark-mode .input-group-text,
  body.dark-mode label {
    color: #00ffff;
  }

  body.dark-mode select,
  body.dark-mode .form-control {
    background-color: #1d1d1d;
    color: #00ffff;
    border: 1px solid #00ffff;
    border-radius: 10px;
  }

  body.dark-mode .table {
    color: #ffffff;
    background-color: #1b1b1b;
    border-collapse: collapse;
  }

  body.dark-mode .table th,
  body.dark-mode .table td {
    border: 1px solid #2c2c2c;
  }

  body.dark-mode .form-control::placeholder {
    color: #888;
  }

  .dashcard {
    border-radius: 15px;
    transition: transform 0.3s ease-in-out;
  }

  .dashcard:hover {
    transform: scale(1.05);
    box-shadow: 0 0 15px rgba(0, 255, 255, 0.6);
  }

  .dashcard .card-body {
    font-size: 16px;
    font-weight: 500;
  }

  .dashcard .card-title {
    font-size: 18px;
    font-weight: 600;
    text-shadow: 0 0 5px rgba(0, 255, 255, 0.6);
  }

  .dashcard .card-footer {
    background-color: rgba(255, 255, 255, 0.05);
    border-top: 1px solid #333;
  }

  /* body.dark-mode #chartdiv1,
  body.dark-mode #chartdiv2,
  body.dark-mode #chartdiv5,
  body.dark-mode #chartdiv8,
  body.dark-mode #chart_area {
    background-color: #131313;
    border-radius: 15px;
    box-shadow: inset 0 0 10px #00ffff20;
  } */

  .form-check-input:checked {
    background-color: #00ffff;
    border-color: #00ffff;
  }

  ::selection {
    background: #00ffff;
    color: #000;
  }

  .btn-success, .btn-danger {
    border-radius: 5px;
    padding: 0.5em 1.5em;
    font-weight: bold;
    transition: 0.3s ease-in-out;
  }

  .btn-success:hover {
    background-color: #00ffaa;
    color: #000;
  }

  .btn-danger:hover {
    background-color: #ff4d4d;
    color: #fff;
  }

  /* .modal-content {
    background: #1c1c1c;
    border-radius: 20px;
    border: 1px solid #2c2c2c;
    box-shadow: 0 0 30px rgba(0, 255, 255, 0.1);
  } */


  @keyframes pulse-glow {
  0% {
    box-shadow: 0 0 10px #00ffff55;
  }
  50% {
    box-shadow: 0 0 20px #00ffffaa;
  }
  100% {
    box-shadow: 0 0 10px #00ffff55;
  }
}

.dashcard:hover {
  animation: pulse-glow 1.5s infinite;
}

#report_data {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 6px; /* less vertical spacing */
  background: #f9f9f9; /* very soft off-white background */
  color: #ccc; /* lighter text but not pure white */
  font-family: 'Poppins', Tahoma, Geneva, Verdana, sans-serif;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: none; /* remove glow */
}

#report_data thead tr {
  background: #eaeaea; /* subtle light grey header */
  color: #666; /* muted heading text */
  text-transform: uppercase;
  letter-spacing: 1px;
  font-weight: 700; /* bold */
  font-size: 1.2rem; /* slightly bigger than data */
  text-align: center;
  border-bottom: 1px solid #ccc; /* soft divider line */
}


#report_data tbody tr {
  background: transparent; /* no bright white */
  transition: background 0.25s ease, color 0.25s ease;
  box-shadow: none;
}

#report_data tbody tr:hover {
  background: #999999; /* subtle highlight */
  color: #fff; /* brighten text on hover */
}

#report_data th,
#report_data td {
  padding: 10px 12px;
  border: none;
  border-bottom: 1px solid #333; /* soft row separator */
}

#report_data th:last-child,
#report_data td:last-child {
  border-right: none;
}

#report_data tbody tr td {
  font-weight: 400;
  font-size: 0.9rem;
}

/* Scrollbar for responsive tables */
#report_data::-webkit-scrollbar {
  height: 6px;
}

#report_data::-webkit-scrollbar-thumb {
  background: #666; /* muted scrollbar */
  border-radius: 3px;
}



.status-open td {
  color: red !important;
  border: 1px solid red;
}

.status-fixed td {
  color: red !important;
  border: 1px solid red;
}

.status-closed td {
  color: green !important;
  border: 1px solid green;
}

.status-subject-closing td {
  color: #890188 !important;
  border: 1px solid #890188;
}


</style>





<div class="container-fluid">

<div id="wrapper">

<div id="layoutSidenav_content">
<div class="container-fluid">
<div class="form-check form-switch float-right m-3">
  <input class="form-check-input" type="checkbox" id="darkModeToggle">
  <label class="form-check-label text-dark" for="darkModeToggle">Dark Mode</label>
</div>


<form method="post" name="cof_form" id="cof_form" enctype="multipart/form-data">
        <div class="row">


        <input type="hidden" name="chcksbjcls" id="chcksbjcls" value="check" >

            </div>
        </div>
      </form>
    <div class="col-md-4 mt-4 d-inline-flex p-2">
      <label class="sr-only" for="inlineFormInputGroup">Start Date</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text">LOGS IN YEAR OF:</div>
        </div>
                <select class="form-contro"  name="yearpicker" id="yearpicker" required>
                 <option value="2019,2020,2021,2022,2023,2024,2025" >OVERALL</option>
                 <option value="2025" selected >2025</option>
		             <option value="2024" >2024</option>
                 <option value="2023" >2023</option>
                 <option value="2022" >2022</option>
                 <option value="2021" >2021</option>
                 <option value="2020">2020</option>
                 <option value="2019">2019</option>
  



                </select>
      </div>
    </div>

        </div>
 
    </div>
  </div>

<div class="card-deck align-items-center mb-3">


<div class="dashcard card text-white mb-4 bg-primary border-dark" style="width: 18rem; height: 9rem;">
<div class="card-body">

<div class="card-title">TOTAL REPORTS: <span class="float-right" id="count_total" style="font-size: 32px;"></span></div>
             
</div>                                  
<div class="card-footer d-flex align-items-center justify-content-between">
<a class=" text-white stretched-link" id="card_totalval" href="#bottom" value="" ><span class="small text-white">Click here for more info.</span></a>
                          <div class="go-arrow">  </div>
                          
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-danger" style="width: 18rem; height: 9rem; ">
<div class="card-body">

<div class="card-title">OPEN REPORTS: <span class="float-right" id="count_open" style="font-size: 32px;"></span></div>

</div>
<div class="card-footer d-flex align-items-center justify-content-between">

                       <a class=" text-white stretched-link" id="card_openval" href="#bottom" value="OPEN" ><span class="small text-white">Click here for more info.</span></a>
                   

                          <div class="go-arrow">  </div>
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-warning" style="width: 18rem; height: 9rem;">
<div class="card-body">

<div class="card-title" style="font-size: 15px;">ATTENDED WITH FIX ASSET: <span class="float-right" id="count_owfa" style="font-size: 32px;"></span></div>

</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                      
                           <a class="text-white stretched-link" id="card_openwfaval" href="#bottom" value="ATTENDED WITH FIX ASSET" ><span class="small text-white">Click here for more info.</span></a>
                          <div class="go-arrow">  </div>
                      </div>

</div>

<div class="dashcard card text-white mb-4 bg-success" style="width: 18rem; height: 9rem;">
<div class="card-body">

<div class="card-title">CLOSED REPORTS <span class="float-right" id="count_closed" style="font-size: 32px;"></span></div>
<div class="card-subtitle clcktxt" value="SUBJECT FOR CLOSING">SUBJECT FOR  CLOSING <span class="float-none" id="today_closed" style="font-size: 23px; margin-left: 15px;"></span></div>    
</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="text-white stretched-link" id="card_closedval" href="#bottom" value="CLOSED" ><span class="small text-white">Click here for more info.</span></a>
                          <div class="go-arrow">  </div>
                      </div>


</div>

</div>



<div class="row " id="ovrall">

<div class="card card2 col-12 col-md-12 col-lg-6">
<h5 class="card-header text-black">Overall Status</h5>
<div class="card-body">
<div id="chartdiv5"></div>
</div>
</div>

<div class="card card2 col-12 col-md-12 col-lg-6">
<h5 class="card-header text-black">I.T Support Logs</h5>
<div class="card-body">
<div id="chartdiv8"></div>
</div>
</div>





<div class="card card2 col-12 col-md-12 col-lg-6">
<h5 class="card-header text-black">Recently enrolled reports.</h5>
<div class="card-body">
<div id="chartdiv1"></div>
</div>
</div>


<div class="card card2 col-12 col-md-12 col-lg-6">
<h5 class="card-header text-black">CATEGORIES</h5>
<div class="card-body">
<div id="chartdiv2" name="chartdiv2"></div>
</div>
</div>

<div class="card card2 col-12 col-lg-12">
<h5 class="card-header text-black">Number of Escalated Reports Per Area</h5>
<div class="card-body">
<div class="col-xl-12 col-lg-12">
</div>

<div id="chart_area"></div>
</div>     
</div>

</div>
<div class="row">


<div class="card card2">
<h5 class="card-header text-black">TICKETS</h5>
<div class="card-body">
<div class="row col-md-12 mb-3">
  
<div class="col-md-12">
<table id="report_data" class="table  table-striped table-responsive table-condensed text-center borderless"></table>

</div>
<!-- <button type="button" id="add_button" class=" second btn btn-xs btn-success" data-toggle="modal" data-target="#userModal"><i class="fas fa-plus"></i></button> -->
</div>

<div class="col-md-12">
<table id="network_tb" class="table  table-striped table-responsive table-condensed text-center borderless"></table>

</div>




</div>
</div>



</div>

<div class="col-lg-12 Down" id="Down">
  <input type="hidden" id="myInput">
</div>
</div> <!--end of container-->




<!-- Start of Add/Edit Modal -->

<div class="col-12 col-lg-12 modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" style="max-width: 100%;">
<form method="post" id="report_form" enctype="multipart/form-data">
<div class="modal-content" >
<div class="modal-header">
<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
<h4 class="modal-title" id="userModal_header" value="Add Report"></h4>
<!-- <button type="button" id="prntForm" class="btn btn-info float-right" data-dismiss="modal"><i class="fas fa-print"></i></button> -->
</div>


<div class="modal-body">
 <div class="row">
<!-- <form> -->
<div class= "m_col col-6 col-md-6 col-lg-6">
<div class="row">
<div class="form-group col-6 col-md-6 col-lg-6">
<label>STORE</label>
<input type="hidden" name="str_num" id="str_num" readonly="" value="">
<select class="form-control form-control-sm" name="store" id="store" required>
<option value="">Select Store...</option>  
     <?php
              $query="select * from tbl_branch ";
              $run=$conn->prepare($query);
              $run->execute();
              $rs=$run->get_result();
              while ($res=$rs->fetch_assoc()) {
                $brcnhid = $res['str_num'];
                $brnchcd = $res['str_code'].' | '.$res['str_name'];
              ?>

              <option value="<?php echo $brcnhid;?>"><?= $brnchcd; ?></option>
              <?php }?>
              ?>   
  </select> 
</div>
<input type = "hidden" class="form-control form-control-sm" name = "ticket_no" id="ticket_no">


<div class="form-group col-6 col-md-6 col-lg-6">

<label>DATE CREATED</label>
<div class="input-group date" id="datetimepicker1" data-target-input="nearest">
<input type="text" name="date_created" id="date_created" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker1" value="<?php echo $datetime->format('m/d/Y g:i A');?>" />
<div class="input-group-append" data-target="#date_created" data-toggle="datetimepicker">
<div class="input-group-text"><i class="fa fa-calendar"></i></div>
</div>
</div>
</div>

<div class="form-group col-12 col-md-12 col-lg-12">
<label>SUBJECT/CONCERN</label>
<textarea name="subjct" id="subjct" class="form-control form-control-sm" placeholder="Input Concern" 
style="text-transform:uppercase" onkeyup="this.value = this.value;"></textarea>
</div>

<div class="form-group col-3 col-md-3 col-lg-3">
<label>VIA</label>
<select class="form-control form-control-sm" name="via" id="via" required>
<option value=""> &larr; VIA &rarr;</option>
<?php
      $query="select * from via_main";
      $run=$conn->prepare($query);
      $run->execute();
      $rs=$run->get_result();
      while ($res=$rs->fetch_assoc()) {
      ?>
      <option><?=$res['via_desc'] ?></option>
      <?php }?>
      ?>   
</select>
</div>
<div class="form-group col-9 col-md-9 col-lg-9">
<label>I.T SUPPORT</label>
<input type="hidden" name="it_num" id="it_num" readonly="">
<select class="form-control form-control-sm" name="itsup" id="itsup" required>
<option value="">Assign support...</option>  
     <?php
              $query="select * from it_tech WHERE deptsel = '1' AND itsup NOT IN ('4','7','8','12','14')";
              $run=$conn->prepare($query);
              $run->execute();
              $rs=$run->get_result();
              while ($res=$rs->fetch_assoc()) {
                $tchid = $res['itsup'];
                $tchdesc = $res['it_desc'];
              ?>

              <option value="<?php echo $tchid;?>"><?= $tchdesc; ?></option>
              <?php }?>
              ?>   
  </select> 
</div>
<div class="form-group col-4 col-md-4 col-lg-4">

<label>CATEGORY</label>
<input type="hidden" name="cat_num" id="cat_num" readonly="">
<select class="form-control form-control-sm" name="cat" id="cat" required >
<option value=""> &larr; CATEGORY &rarr;</option>  
     <?php
              $query="select * from category WHERE deptsel = '1'";
              $run=$conn->prepare($query);
              $run->execute();
              $rs=$run->get_result();
              while ($res=$rs->fetch_assoc()) {
                $supid = $res['id'];
                $suppdesc = $res['category_name'];
              ?>

              <option value="<?php echo $supid;?>"><?= $suppdesc; ?></option>
              <?php }?>
              ?>   
</select> 
</div>
<div class="form-group col-4 col-md-4 col-lg-4">

<label>SUB CATEGORY</label>
<input type="hidden" name="sub_num" id="sub_num" readonly>


<select class="form-control form-control-sm" name="sub" id="sub">
</select>
</div>
<div class="form-group col-4 col-md-4 col-lg-4 hide_isp">

<label for="isp" id="lbl_isp">Service Provider</label>
<input type="hidden" name="isp_num" id="isp_num" readonly="">
<select class="form-control form-control-sm" name="isp" id="isp">
<option value="">Select Network Provider</option>  
     <?php
              $query="select * from tbl_isp";
              $run=$conn->prepare($query);
              $run->execute();
              $rs=$run->get_result();
              while ($res=$rs->fetch_assoc()) {
                $ispid = $res['isp_id'];
                $ispdesc = $res['isp_shortDesc'];
              ?>

              <option value="<?php echo $ispid;?>"><?= $ispdesc; ?></option>
              <?php }?>
              ?>   
</select> 
</div>
<div class="form-group col-4 col-md-4 col-lg-4 hide_isp">
<label id="lbl_refNo" for="refNo">Reference No:</label>
<input type="text" class="form-control form-control-sm" name="refNo" id="refNo">
</div>


<div class="form-group col-4 col-md-4 col-lg-4 hide_isp">

<label for="date_refNo" class="hidden" id="lbl_DtRefNo">Date of RefNo</label>
<div class="input-group date" id="datetimepicker3" data-target-input="nearest">
<input type="text" name="date_refNo" id="date_refNo" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker3"/>
<div class="input-group-append" data-target="#date_created" data-toggle="datetimepicker">
<div class="input-group-text" id="ico_cal3"><i class="fa fa-calendar"></i></div>
</div>
</div>
</div>

<div class="form-group col-4 col-md-4 col-lg-4">
<label>STATUS</label>
<select class = "form-control form-control-sm" name= "status" id="status" required>
<option value=""> &larr; Status &rarr;</option>
<?php
      $query="select * from status  WHERE it_module_tag = 'Y'";
      $run=$conn->prepare($query);
      $run->execute();
      $rs=$run->get_result();
      while ($res=$rs->fetch_assoc()) {
      ?>
      <option><?=$res['stat_desc'] ?></option>
      
      <?php }?>
      ?>   
      <option value="CLOSED" readonly>CLOSED</option>

</select>
</div>
<div class="form-group col-4 col-md-4 col-lg-4 hide_cl">
<label id="dateclabel" class="hidden">DATE CLOSED</label>
<div class="input-group date" id="datetimepicker2" data-target-input="nearest">
<input type="text" name="date_closed" id="date_closed" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker2" autocomplete="off" />
<div class="input-group-append" data-target="#date_closed" autocomplete="off" data-toggle="datetimepicker">
<div class="input-group-text" id="ico_cal" name="ico_cal"><i class="fa fa-calendar"></i></div>
</div>
</div>
</div>

<div class="form-group col-4 col-md-4 col-lg-4 hide_cl">

<label id="clby_label" class="hidden">CLOSED BY</label>
<input type="hidden" name="close_by" id="close_by" value="<?php echo $_SESSION['tech_id'];?>"> 
<input type="text" class="form-control form-control-sm" name="cl_desc" id="cl_desc" readonly="" value="<?php echo $_SESSION['fname']. '  ' . $_SESSION['lstname'];?>">
</div>

<div class="form-group col-lg-12">
<label>Work Output:</label>
<textarea name="remarks" id="remarks" class="form-control form-control-sm" placeholder="Your Workoutput" ></textarea>
</div>
</div>


<div class="col-md-12">
<label style="font-weight: bold;">Attached File:</label>
<p>
<input id="file-input" type="file" name="file" Multiple>
</p>
</div>
<hr/>
<div class="row">
    
<div class=" col-6 col-md-8">
<input type="submit" name="action" id="action" class="btn btn-success" value="Add" />   
</div>
<div class=" col-6 col-md-4 ">
<button type="button" name="btnClose" id="btnClose" class="btn btn-danger float-right" data-dismiss="modal">Close</button>  
</div>
</div>

<hr/>

<div class="card" id="img" name="img">


</div>

<div class="form-group col-lg-12">
<p>



</p>
</div>

</div>

</p>




<div class="col-6 col-md-6 col-lg-6">

  
<div class="" id="msg_thread">

<div  class="col-12 col-lg-12 mb-3">

       <label style="font-weight: bold;">Add Comment:</label>
<textarea name="admsg" id="addmsg" class="form-control form-control-sm" placeholder="Reply to their message or give an updates regarding on this ticket..." required></textarea> 
</div>
<div class="col-12 col-lg-12 mt-4 mb-2 dv_msg">
<label for="remarks_view" style="font-weight: bold;">Comment Thread:</label>
   <hr>
<div class="container_remarks" >
<div id="remarks_view"></div>
</div>
</div>




</div>

</div>


</div>

</div>
</div> 
</div>

<div class="modal-footer">
<input type="hidden" name="operation" id="operation" value="Add" />
<input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id'];  ?>">

</div>
</form>
</div>
</div>
</div>
</div>







 

