<?php

// ======== db  =========
include 'header.php';
include '../condb.php';
include 'am.php';
include 'chrtdashboard.php';
// include 'sub_graph_modal.php';

$conn=new dbconfig();

// ======== header =========
$datetime = new DateTime();
$timezone = new DateTimeZone('Asia/Manila');
$datetime->setTimezone($timezone);


?>
<head>
<link rel="stylesheet" href="../plugins/DataTables-1.10.25/media/css/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="../assets/Date-Time-Picker-Bootstrap-4/src/sass/bootstrap-datetimepicker-build.css" />
<script src="../assets/Date-Time-Picker-Bootstrap-4/src/js/bootstrap-datetimepicker.js"></script>
<link rel="stylesheet" href="styles.css" />
<script src="../plugins/DataTables-1.10.25/media/js/jquery.dataTables.min.js"></script>
<script src="../js/ellipsis.js"></script>
  <meta http-equiv='cache-control' content='no-cache'>
  <meta http-equiv='expires' content='0'>
  <meta http-equiv='pragma' content='no-cache'>
</head>


<div class="container-fluid mt-4 ticket_container">

<div id="wrapper">

<div id="layoutSidenav_content">

 
    </div>
  </div>

  <div class="row">

<div class="col-4 col-md-4 " >
            
           <div class="input-group mb-3">
             <div class="input-group-prepend">
               <span class="input-group-text" id="basic-addon1">SELECT YEAR:</span>
             </div>
                            <select class="form-control"  name="yearpicker" id="yearpicker" required>
                            <option value="2019,2020,2021,2022,2023,2024,2025,2026">OVERALL</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
			    <option value="2024">2024</option>
			    <option value="2025">2025</option>
                            <option value="2026" selected>2026</option>
                           </select>
            </div>

</div>
<input type="hidden" name="slct_area" id="slct_area" value="<?php echo $_SESSION['user_id'];?>">

           <div  class="col-2 col-md-2 ">
               <button class="btn btn-info btn-xs" id="flterbutton" style="display: inline-block;"><i class="fa fa-search" aria-hidden="true"></i></button>
           </div>

   </div>


<div class=" second card-deck align-items-center mb-3">


<div class="dashcard card text-white mb-4 bg-primary border-dark" style="width: 18rem; height: 9rem;">
<div class="card-body">

<div class="card-title"><span id="card_totalrep">TOTAL TICKETS:</span> <span class="" id="count_total" style="font-size: 18px;"></span></div>
                          
</div>                                  
<div class="card-footer d-flex align-items-center justify-content-between">

                     <a class="small text-white stretched-link" id="card_totalval" href="#bottom" value="" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                          
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-danger" style="width: 18rem; height: 9rem; ">
<div class="card-body">

<div class="card-title">OPEN TICKETS: <span class="" id="count_open" style="font-size: 18px;"></span></div>
</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                       
                          <a class="small text-white stretched-link" id="card_openval" href="#bottom" value="OPEN" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-info" style="width: 18rem; height: 9rem;">
<div class="card-body">

<div class="card-title" style="font-size: 15px;">OVER DUE OPEN TICKETS: <span class="" id="count_owfa" style="font-size: 18px;"></span></div>

</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                      
                           <a class="small text-white stretched-link" id="card_openwfaval" href="#bottom" value="OPEN WITH FIX ASSET" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                      </div>

</div>

<div class="dashcard card text-white mb-4 bg-success" style="width: 18rem; height: 9rem;">
<div class="card-body">

<div class="card-title">CLOSED TICKETS: <span class="" id="count_closed" style="font-size: 18px;"></span></div>
</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" id="card_closedval" href="#bottom" value="CLOSED" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                      </div>


</div>

</div>


<div class="row " id="ovrall">

<div class="card card2 col-12 col-md-12 col-lg-6">
<h5 class="card-header text-white">Store With Open Tickets</h5>
<div class="card-body">
<div id="chartdiv5"></div>
</div>
</div>


<div class="card card2 col-12 col-md-12 col-lg-6">
<h5 class="card-header text-white">Open Tickets Per Deparment</h5>
<div class="card-body">
<div id="chartdiv2" name="chartdiv2"></div>
</div>
</div>


<div class="card card2 col-12 col-lg-12">
<h5 class="card-header text-white">Number of Escalated Tickets Per Store</h5>
<div class="card-body">
<div class="col-xl-12 col-lg-12">
</div>

<div id="chart_area"></div>
</div>     
</div>

<div class="card card2 col-12 col-lg-12">
    <h5 class="card-header text-white">Non Compliant Stores on End of Day Process (7:AM CUT OFF)</h5>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">FROM</span>
                    <input type="date" id="frompolDate" class="form-control mr-3">
                    <span class="input-group-text">TO</span>
                    <input type="date" id="topolDate" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="col-xl-12 col-lg-12">
            <!-- Additional content can go here -->
        </div>

        <div id="chart_polled">
            <!-- Chart will be rendered here -->
        </div>
    </div>     
</div>


</div>


<div class="row">


<div class="card card2">
<h5 class="card-header text-white"></h5>
<div class="card-body">





<table id="report_data" class="table table-dark table-responsive table-condensed text-center"></table>

</div>
</div>
</div>
<input type="hidden" id="myInput">
</div> <!--end of container-->

<style>
  .glass-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    color: white;
    padding: 2rem;
  }

  .glass-card h1 {
    font-size: 2rem;
    margin-bottom: 1.5rem;
    font-weight: bold;
  }

  #zreading_tbl {
    background-color: transparent;
  }

  ::placeholder {
    color: #ccc;
  }
</style>

<div class="col-md-12 mb-4 mt-4" id="sales_dashboard">
  <div class="glass-card">
    <h1 class="text-dark" >Daily Sales Monitoring</h1>

    <table id="zreading_tbl" class="table table-dark table-bordered text-dark table-hover text-center">
       <thead class="thead-dark text-dark">
    </table>
  </div>
</div>

</div> <!--end of wrapper-->

<!-- Start of Add/Edit Modal -->

<div class="col-12 col-md-12"> 
<div class="col-12 col-md-12 modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
<form method="post" id="report_form" enctype="multipart/form-data">
<div class="modal-content">
<div class="modal-header">
<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
<h4 class="modal-title" id="userModal_header" value="Add Report"></h4>
<!-- <button type="button" id="prntForm" class="btn btn-info float-right" data-dismiss="modal"><i class="fas fa-print"></i></button> -->
</div>


<div class="modal-body">
<!-- <form> -->

<div class="row">
<div class="form-group col-12 col-md-6">
<label>STORE</label>
<input type="hidden" name="str_num" id="str_num" readonly="" value="">
<select class="form-control form-control-sm" name="store" id="store" required>
<option value="">Select Store...</option>  
     <?php
              $query="select * from tbl_branch";
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


<div class="form-group col-md-3">

<label>DATE CREATED</label>
<div class="input-group date" id="datetimepicker1" data-target-input="nearest">
<input type="text" name="date_created" id="date_created" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker1" value="<?php echo $datetime->format('m/d/Y g:i A');?>" />
<div class="input-group-append" data-target="#date_created" data-toggle="datetimepicker">
<div class="input-group-text"><i class="fa fa-calendar"></i></div>
</div>
</div>
</div>
<div class="form-group col-md-12">
<label>SUBJECT/CONCERN</label>
<textarea name="subjct" id="subjct" class="form-control form-control-sm" placeholder="Input Concern" 
style="text-transform:uppercase" onkeyup="this.value = this.value;"></textarea>
</div>

<div class="form-group col-md-3">
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
    
</select>
</div>
<div class="form-group col-md-3">
<label>I.T SUPPORT</label>
<input type="hidden" name="it_num" id="it_num" readonly="">
<select class="form-control form-control-sm" name="itsup" id="itsup" required>
<option value="">Assign support...</option>  
     <?php
              $query="select * from it_tech WHERE itsup NOT IN ('4','7','8',12)";
              $run=$conn->prepare($query);
              $run->execute();
              $rs=$run->get_result();
              while ($res=$rs->fetch_assoc()) {
                $tchid = $res['itsup'];
                $tchdesc = $res['it_desc'];
              ?>

              <option value="<?php echo $tchid;?>"><?= $tchdesc; ?></option>
              <?php }?>
             
  </select> 
</div>
<div class="form-group col-md-3">

<label>CATEGORY</label>
<input type="hidden" name="cat_num" id="cat_num" readonly="">
<select class="form-control form-control-sm" name="cat" id="cat" required >
<option value=""> &larr; CATEGORY &rarr;</option>  
     <?php
              $query="select * from category";
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
<div class="form-group col-md-3">

<label>SUB CATEGORY</label>
<input type="hidden" name="sub_num" id="sub_num" readonly="">


<select class="form-control form-control-sm" name="sub" id="sub">
</select>
</div>
<div class="form-group col-md-4 hide_isp">

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
<div class="form-group col-md-4 hide_isp">
<label id="lbl_refNo" for="refNo">Reference No:</label>
<input type="text" class="form-control form-control-sm" name="refNo" id="refNo">
</div>


<div class="form-group col-md-4 hide_isp">

<label for="date_refNo" class="hidden" id="lbl_DtRefNo">Date of RefNo</label>
<div class="input-group date" id="datetimepicker3" data-target-input="nearest">
<input type="text" name="date_refNo" id="date_refNo" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker3"/>
<div class="input-group-append" data-target="#date_created" data-toggle="datetimepicker">
<div class="input-group-text" id="ico_cal3"><i class="fa fa-calendar"></i></div>
</div>
</div>
</div>

<div class="form-group col-md-3">
<label>STATUS</label>
<select class = "form-control form-control-sm" name= "status" id="status" required>
<option value=""> &larr; Status &rarr;</option>
<?php
      $query="select * from status ";
      $run=$conn->prepare($query);
      $run->execute();
      $rs=$run->get_result();
      while ($res=$rs->fetch_assoc()) {
      ?>
      <option><?=$res['stat_desc'] ?></option>
      <?php }?>
    
</select>
</div>
<div class="form-group col-md-4 hide_cl">
<label id="dateclabel" class="hidden">DATE CLOSED</label>
<div class="input-group date" id="datetimepicker2" data-target-input="nearest">
<input type="text" name="date_closed" id="date_closed" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker2" autocomplete="off" />
<div class="input-group-append" data-target="#date_closed" autocomplete="off" data-toggle="datetimepicker">
<div class="input-group-text" id="ico_cal" name="ico_cal"><i class="fa fa-calendar"></i></div>
</div>
</div>
</div>

<div class="form-group col-md-3 hide_cl">

<label id="clby_label" class="hidden">CLOSED BY</label>
<input type="hidden" name="close_by" id="close_by" value=""> 
<input type="text" class="form-control form-control-sm" name="cl_desc" id="cl_desc" readonly="" value="">
</div>

<div class="form-group col-md-12">
<label>Work Output:</label>
<textarea name="remarks" id="remarks" class="form-control form-control-sm" placeholder="Your Workoutput" ></textarea>
</div>
<hr/>
<div class="form-group col-md-12">
<p>
<div class="row">
    
<div class=" col-6 col-md-8">
<input type="submit" name="action" id="action" class="btn btn-success" value="Add" />   
</div>
<div class=" col-6 col-md-4 ">
<button type="button" name="btnClose" id="btnClose" class="btn btn-danger float-right" data-dismiss="modal">Close</button>  
</div>
<div class="col-md-4 mt-2">
<button class="btn btn-info" type="button" name="msgbtn" id="msgbtn" value="show">
Show Message Thread
</button>   
</div>
</div>

</p>

<div class="col-12 col-md-12">
  
<div class="collapse" id="msg_thread">

<div class="row">
<div  class="col-12 col-md-12 mb-3">
       <label style="font-weight: bold;">Add Comment:</label>
<textarea name="admsg" id="addmsg" class="form-control form-control-sm" placeholder="Reply to their message or give an updates regarding on this ticket..."></textarea> 
</div>
<div class="col-12 col-md-12 mt-4 mb-2 dv_msg">
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

<div class="modal-footer">
<input type="hidden" name="operation" id="operation" />
<input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id'];  ?>">

</div>
</form>
</div>
</div>
</div>
</div>


