<?php


include '../condb.php';
include 'tech_header.php';

$conn=new dbconfig();

$datetime = new DateTime();
$timezone = new DateTimeZone('Asia/Manila');
$datetime->setTimezone($timezone);



?>

<style>
 #showCalendarBtn {
    background-color: #213456;
    color: white;
    border-radius: 8px;
    padding: 8px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    white-space: nowrap;
}

#showCalendarBtn:hover {
    background-color: var(--owi-gold, #E1AD01);
    color: #213456;
}


#report_data thead th{
    background: #F3F4F6 !important;  
    color: #374151 !important;      
    font-weight: 700;
}

#report_data tbody tr{
    background: #F9FAFB !important; 
}

#report_data tbody tr:nth-child(even){
    background: #F3F4F6 !important;  
}

#report_data tbody td{
    color: #4B5563 !important;    
    border-color: #E5E7EB !important;
}

#report_data tbody tr:hover{
    background: #E5E7EB !important;  
}

.dataTables_wrapper{
    background: #F9FAFB;
    padding: 15px;
    border-radius: 12px;
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



label {
  font-size: 11px;
  font-weight: 900;
  color: #a37f0a; 
  letter-spacing: .08em;
  text-transform: uppercase;
  margin-bottom: 6px;
}

input.form-control,
textarea.form-control {
  color: #6c757d !important;
  background-color: transparent !important; 
  border: black !important; 
  border-bottom: 1px solid #E1AD01 !important; 
  resize: none !important; 
}

select.custom-select-placeholder.placeholder-active,
textarea.form-control.custom-select-placeholder:placeholder-shown {
  color: red !important;
  border: 1px solid #ced4da !important;
  background-color: #fff !important;
}

textarea.form-control.custom-select-placeholder::placeholder {
  color: red !important;
  opacity: 0.7;
}

select.custom-select-placeholder.has-value,
textarea.form-control.custom-select-placeholder:not(:placeholder-shown) {
  color: #0a0a0a !important; 
  border-bottom: 1px solid #E1AD01 !important; 
  background-color: transparent !important;
}

.form-control,
.form-control-sm,
input.form-control,
select.form-control,
textarea.form-control {
  background: #fff !important;
  color: black !important;
  border-bottom: 1px solid #213456 !important; 
  border-radius: 8px;
}

.form-control:focus,
.form-control-sm:focus,
input.form-control:focus,
select.form-control:focus,
textarea.form-control:focus {
  box-shadow: 0 10px 18px rgba(17,24,39,.06);
  border-color: 2px solid rgba(114, 89, 21, 0.94) !important;
}

.btn{
  background-color: #E1AD01;
  color: white;
  border-radius: 8px;
}

.btn:hover{
    background-color: #213456;
  color: #E1AD01;
  border-radius: 8px;
}

.container_remarks {
  max-height: 320px;
  overflow-y: auto;
  padding-right: 6px;
}

#remarks_view {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.comment-item {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  border: 1px solid #e5e7eb;
  border-left: 4px solid #E1AD01;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.comment-item .fw-bold {
  color: #213456;
  margin-bottom: 2px;
}

.comment-item .small {
  color: #6b7280;
}

</style>

<head>
<link rel="stylesheet" href="../plugins/DataTables-1.10.25/media/css/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="../assets/Date-Time-Picker-Bootstrap-4/src/sass/bootstrap-datetimepicker-build.css" />
<script src="../assets/Date-Time-Picker-Bootstrap-4/src/js/bootstrap-datetimepicker.js"></script>


<script src="../plugins/DataTables-1.10.25/media/js/jquery.dataTables.min.js"></script>
<script src="../js/ellipsis.js"></script>
  <link rel="stylesheet" href="adminpanel.css">
</head>


<div class="container-fluid">
        <!-- Hidden Form -->
        <form method="post" name="cof_form" id="cof_form" enctype="multipart/form-data">
          <div class="row">
            <input type="hidden" name="chcksbjcls" id="chcksbjcls" value="check">
          </div>
        </form>
        <div class="action-bar-container" style="box-shadow: 0 5px 10px 2px #2d3c597f; margin-bottom: -20px;">
          <div class="year-picker-group">
            <div class="input-group">
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="fas fa-history me-2"></i>LOGS IN YEAR OF:
                </span>
              </div>
              <select class="form-control" name="yearpicker" id="yearpicker" required>
                <option value="2019,2020,2021,2022,2023,2024,2025,2026">OVERALL</option>
                <option value="2026" selected>2026</OPTION>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
              </select>
            </div>
          </div>
         
        </div>
      </div>

<div class="container-fluid mt-4">



 <div class="row g-4 mb-4" >
              <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card h-100 dashcard-clickable" data-filter="" style="margin-top:15px;border-radius: 15px; cursor:pointer;">
                  <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                      <div class=" bg-opacity-10 p-3 rounded-circle " style="color: #576A8F;">
                        <i class="fas fa-file-alt fa-2x"></i>
                      </div>
                      <h2 class="fw-black mb-1" id="count_total" style="font-size:2.2rem; letter-spacing: -1px; ">0</h2>
                    </div>
                    <div>
                      <p class=" fw-bold text-uppercase mb-0"
                        style="font-size: 0.75rem; color: #576A8F;letter-spacing: 1px;">Total Reports</p>
                      <hr class="mt-2 mb-3" style="border-top: 2px solid #576A8F; opacity: 1; width: 100%;" />
                      <div class="d-flex justify-content-between align-items-center">
                           <a class=" second small text-info stretched-link" id="card_totalval" href="#bottom" value="" >Click here for more info.</a>
                        <i class="fas fa-chevron-right small text-muted"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card  h-100 dashcard-clickable" data-filter="ON PROCESS"
                  style="border-radius: 15px; margin-top:15px;cursor: pointer;">
                  <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                      <div class=" bg-opacity-10 p-3 rounded-circle" style="color: #E5BA41;">
                        <i class="fas fa-spinner fa-2x"></i>
                      </div>
                      <h2 class="fw-black mb-1" id="count_open" style="font-size: 2.2rem; letter-spacing: -1px;">0</h2>
                    </div>
                    <div>
                      <p class="text-warning fw-bold text-uppercase mb-0"
                        style="font-size: 0.75rem;color: #E5BA41; letter-spacing: 1px;">On Process</p>
                      <hr class="mt-2 mb-3" style="border-top: 2px solid #E5BA41;; opacity:1; width:100%;" />
                      <div class="d-flex justify-content-between align-items-center">
                          <a class="small text-warning stretched-link" id="card_openval" href="#bottom" value="ON PROCESS" >Click here for more info.</a>
                        <i class="fas fa-chevron-right small text-muted"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card h-100 dashcard-clickable" data-filter="PENDING"
                  style="border-radius: 15px; margin-top:15px;cursor:pointer;">
                  <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                      <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger" style="color: #D25353;">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                      </div>
                      <h2 class="fw-black mb-1" id="count_owfa" style="font-size:2.2rem; letter-spacing: -1px;">0</h2>
                    </div>
                    <div>
                      <p class="text-danger fw-bold text-uppercase mb-0"
                        style="font-size:0.75rem; color: #D25353;letter-spacing:1px;">Over Sla / Pending</p>
                      <hr class="mt-2 mb-3" style="border-top: 2px solid #D25353; opacity:1; width:100%;" />
                      <div class="d-flex justify-content-between align-items-center">
                          <a class="small text-danger stretched-link" id="card_openwfaval" href="#bottom" value="PENDING" >Click here for more info.</a>
                        <i class="fas fa-chevron-right small text-muted"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card h-100 dashcard-clickable" data-filter="CLOSED"
                  style="border-radius: 15px; margin-top:15px;cursor:pointer;">
                  <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                      <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success" style="color: #94A378;">
                        <i class="fas fa-check-double fa-2x"></i>
                      </div>
                      <h2 class="fw-black mb-1" id="count_closed" style="font-size:2.2rem; letter-spacing: -1px;">0</h2>
                    </div>
                    <div class="mb-2">

                    </div>
                    <div>
                      <p class="text-success fw-bold text-uppercase mb-0"
                        style="font-size:0.75rem; color: #94A378; letter-spacing: 1px;">Closed Reports</p>
                      <hr class="mt-2 mb-3" style="border-top: 2px solid #94A378; opacity:1; width:100%;" />
                      <div class="d-flex justify-content-between align-items-center">
                        <a class="small text-success stretched-link" id="card_closedval" href="#bottom" value="CLOSED" >Click here for more info.</a>
                        <i class="fas fa-chevron-right small text-muted"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
  


<!--<div class="card-deck mb-3 align-items-center">


<div class="dashcard card text-white mb-4 bg-primary border-dark" style="width: 20rem; height: 9rem;">
<div class="card-body">

<div class="card-title">THIS YEAR TOTAL REPORTS: <span class="" id="count_total" style="font-size: 18px;"></span></div>
                          
</div>                                  
<div class="card-footer d-flex align-items-center justify-content-between">

                     <a class=" second small text-white stretched-link" id="card_totalval" href="#bottom" value="" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                          
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-warning" style="width: 20rem; height: 9rem; ">
<div class="card-body">

<div class="card-title">ASSIGNED / ON PROCESS: <span class="" id="count_open" style="font-size: 18px;"></span></div>
</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                       
                          <a class="small text-white stretched-link" id="card_openval" href="#bottom" value="ON PROCESS" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-danger" style="width: 20rem; height: 9rem;">
<div class="card-body">

<div class="card-title" style="font-size: 15px;">PENDING: <span class="" id="count_owfa" style="font-size: 18px;"></span></div>

</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                      
                           <a class="small text-white stretched-link" id="card_openwfaval" href="#bottom" value="ATTENDED WITH FIX ASSET" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                      </div>

</div>

<div class="dashcard card text-white mb-4 bg-success" style="width: 20rem; height: 9rem;">
<div class="card-body">

<div class="card-title">CLOSED REPORTS <span class="" id="count_closed" style="font-size: 18px;"></span></div>
</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" id="card_closedval" href="#bottom" value="CLOSED" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                      </div>


</div>
-->

</div>



<div class="col-12 mb-3">
            
                  <h5 class="card-header text-black" style="background-color: #95a2b9b4; color:black; "></h5>
                  <div class="card-body">

                    <div class="row col-md-4 mb-3">
<button type="button" id="add_button" class="second btn btn-xs btn-danger" data-toggle="modal" data-target="#userModal">Add Report</button>
</div>

<table id="report_data" class="table table-dark table-responsive table-sm" style="width: auto;"></table>

<div class="col-md-12">
  <input type="hidden" id="myInput">
</div>

                  </div>
           
              </div>

<!--end of container-->



<!-- Start of Add/Edit Modal -->

 <div class="col-12 col-lg-12 modal fade" id="userModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 100%;">
      <form method="post" id="report_form" enctype="multipart/form-data">
        <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title" id="userModal_header" value="Add Report"></h4>
          </div>

          <div class="modal-body">
            <div class="row">

              <!-- LEFT SIDE -->
              <div class="m_col col-12 col-lg-6">

                <div class="row">

                  <div class="form-group col-12 col-md-6">
                    <label>STORE</label>
                    <input type="hidden" name="str_num" id="str_num" readonly value="">
                    <select class="form-control form-control-sm" name="store" id="store" required>
                      <option value="">Select Store...</option>
                      <?php
                      $query = "select * from tbl_branch ";
                      $run = $conn->prepare($query);
                      $run->execute();
                      $rs = $run->get_result();
                      while ($res = $rs->fetch_assoc()) {
                        $brcnhid = $res['str_num'];
                        $brnchcd = $res['str_code'] . ' | ' . $res['str_name'];
                        ?>
                        <option value="<?php echo $brcnhid; ?>"><?= $brnchcd; ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <input type="hidden" class="form-control form-control-sm" name="ticket_no" id="ticket_no">




                  <div class="form-group col-12 col-md-4">
                    <label>DATE CREATED</label>
                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                      <input type="text" name="date_created" id="date_created"
                        class="form-control form-control-sm datetimepicker-input"
                        data-target="#datetimepicker1"
                        value="<?php echo $datetime->format('m/d/Y g:i A'); ?>" />
                      <div class="input-group-append" data-target="#date_created" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group col-4">
                    <label>SUBJECT/CONCERN</label>
                    <input type="text" name="subjct" id="subjct" class="form-control form-control-sm" placeholder="Input Concern"
                      style="text-transform:uppercase" onkeyup="this.value = this.value;"></input>
                  </div>

                  <div class="form-group col-12 col-md-4">
                    <label>VIA</label>
                    <select class="form-control form-control-sm" name="via" id="via" required>
                      <option value=""> &larr; VIA &rarr;</option>
                      <?php
                      $query = "select * from via_main";
                      $run = $conn->prepare($query);
                      $run->execute();
                      $rs = $run->get_result();
                      while ($res = $rs->fetch_assoc()) {
                        ?>
                        <option><?= $res['via_desc'] ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="form-group col-12 col-md-8">
                    <label>I.T SUPPORT</label>
                    <input type="hidden" name="it_num" id="it_num" readonly>
                    <select class="form-control form-control-sm" name="itsup" id="itsup" required>
                      <option value="">Assign support...</option>
                      <?php
                      $query = "select * from it_tech WHERE deptsel = '1' AND itsup NOT IN ('4','8','12','14')";
                      $run = $conn->prepare($query);
                      $run->execute();
                      $rs = $run->get_result();
                      while ($res = $rs->fetch_assoc()) {
                        $tchid = $res['itsup'];
                        $tchdesc = $res['it_desc'];
                        ?>
                        <option value="<?php echo $tchid; ?>"><?= $tchdesc; ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="form-group col-12 col-md-4">
                    <label>CATEGORY</label>
                    <input type="hidden" name="cat_num" id="cat_num" readonly>
                    <select class="form-control form-control-sm" name="cat" id="cat" required>
                      <option value=""> &larr; CATEGORY &rarr;</option>
                      <?php
                      // $query="select * from category WHERE deptsel = '1'";
                      $query = "select * from categories WHERE deptsel = '1' AND old_tag IS NULL";
                      $run = $conn->prepare($query);
                      $run->execute();
                      $rs = $run->get_result();
                      while ($res = $rs->fetch_assoc()) {
                        $supid = $res['cat_id'];
                        $suppdesc = $res['cat_desc'];
                        ?>
                        <option value="<?php echo $supid; ?>"><?= $suppdesc; ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="form-group col-12 col-md-4">
                    <label>SUB CATEGORY</label>
                    <input type="hidden" name="sub_num" id="sub_num" readonly>
                    <select class="form-control form-control-sm" name="sub" id="sub"></select>
                  </div>
                  <div class="form-group col-12 col-md-4">
                    <label style="font-weight: bold;">TRANSFER REQUEST</label>
                    <div>
                      <input type="checkbox" name="is_transfer" id="is_transfer" value="1">
                      <label for="is_transfer"> Mark as Transfer Request</label>
                    </div>
                  </div>
                  <div class="form-group col-12 col-md-4 hide_isp">
                    <label for="isp" id="lbl_isp">Service Provider</label>
                    <input type="hidden" name="isp_num" id="isp_num" readonly>
                    <select class="form-control form-control-sm" name="isp" id="isp">
                      <option value="">Select Network Provider</option>
                      <?php
                      $query = "select * from tbl_isp";
                      $run = $conn->prepare($query);
                      $run->execute();
                      $rs = $run->get_result();
                      while ($res = $rs->fetch_assoc()) {
                        $ispid = $res['isp_id'];
                        $ispdesc = $res['isp_shortDesc'];
                        ?>
                        <option value="<?php echo $ispid; ?>"><?= $ispdesc; ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="form-group col-12 col-md-4 hide_isp">
                    <label id="lbl_refNo" for="refNo">Reference No:</label>
                    <input type="text" class="form-control form-control-sm" name="refNo" id="refNo">
                  </div>

                  <div class="form-group col-12 col-md-4 hide_isp">
                    <label for="date_refNo" class="text" id="lbl_DtRefNo">Date of RefNo</label>
                    <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                      <input type="text" name="date_refNo" id="date_refNo"
                        class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker3" />
                      <div class="input-group-append" data-target="#date_created" data-toggle="datetimepicker">
                        <input type="hidden" class="form-control form-control-sm" name="date_createdx"
                          id="date_createdx">
                        <div class="input-group-text" id="ico_cal3"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group col-12 col-md-4">
                    <label>STATUS</label>
                    <select class="form-control form-control-sm" name="status" id="status" required>
                      <option value=""> &larr; Status &rarr;</option>
                      <?php
                      $query = "select * from status  WHERE it_module_tag = 'Y'";
                      $run = $conn->prepare($query);
                      $run->execute();
                      $rs = $run->get_result();
                      while ($res = $rs->fetch_assoc()) {
                        ?>
                                   <option value="<?=$res['stat_desc'] ?>" style="color: #333;"><?=$res['stat_desc'] ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="form-group col-12 col-md-4 hide_cl">
                    <label id="dateclabel" class="hidden">DATE CLOSED</label>
                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                      <input type="text" name="date_closed" id="date_closed"
                        class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker2"
                        autocomplete="off" />
                      <div class="input-group-append" data-target="#date_closed" autocomplete="off"
                        data-toggle="datetimepicker">
                        <div class="input-group-text" id="ico_cal" name="ico_cal"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group col-12 col-md-4 hide_cl">
                    <label id="clby_label" class="hidden">CLOSED BY</label>
                    <input type="hidden" name="close_by" id="close_by" value="<?php echo $_SESSION['tech_id']; ?>">
                    <input type="text" class="form-control form-control-sm" name="cl_desc" id="cl_desc" readonly
                      value="<?php echo $_SESSION['fname'] . '  ' . $_SESSION['lstname']; ?>">
                  </div>

                  <div class="form-group col-12">
                    <label>Work Output:</label>
                    <textarea name="remarks" id="remarks" class="form-control form-control-sm"
                      placeholder="Your Workoutput"></textarea>
                  </div>

                  <div class="col-12">
                    <label style="font-weight: bold;">Attached File:</label>
                    <p><input id="file-input" type="file" name="file" Multiple></p>
                  </div>

                  <div class="col-12">
                    <hr />
                  </div>

                  <div class="col-12 d-flex justify-content-between align-items-center">
                    <input type="submit" name="action" id="action" class="btn btn-success" value="Add">
                    <button type="button" name="btnClose" id="btnClose" class="btn btn-danger"
                      data-dismiss="modal">Close</button>
                  </div>

                  <div class="col-12">
                    <hr />
                  </div>

                  <div class="card" id="img" name="img"></div>

                </div><!-- /.row -->

              </div><!-- /.left -->

              <!-- RIGHT SIDE -->
              <div class="col-12 col-lg-6">

                <div id="msg_thread">

                  <div class="col-12 mb-3 px-0">
                    <label style="font-weight: bold; color:white;">Add Comment:</label>
                    <textarea name="admsg" id="addmsg" class="form-control form-control-sm"
                      placeholder="Reply to their message or give an updates regarding on this ticket..."
                      required></textarea>
                  </div>

                  <div class="col-12 mt-4 mb-2 dv_msg px-0">
                    <label for="remarks_view" style="font-weight: bold; color:white;">Comment Thread:</label>
                    <hr>
                    <div class="container_remarks">
                      <div id="remarks_view"></div>
                    </div>
                  </div>

                </div><!-- /#msg_thread -->

              </div><!-- /.right -->

            </div><!-- /.row -->
          </div><!-- /.modal-body -->

          <div class="modal-footer">
            <input type="hidden" name="operation" id="operation" value="Add">
            <input type="hidden" name="u_id" id="u_id" value="<?php echo $_SESSION['user_id']; ?>">
          </div>

        </div><!-- /.modal-content -->
      </form>
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->



<!-- modal addnew button -->

<script type='text/javascript'>
$( document ).ready(function() {


var user_id = "<?= $_SESSION['user_id'] ?? '' ?>";

function loadSubCategories(categoryId, selectedValue) {
    if (!categoryId) {
        $('#sub').html('<option value="">Select SubCategory</option>');
        $('#sub_num').val('');
        return;
    }

    $.ajax({
        url: 'get_subcat.php',
        type: 'POST',
        data: { category_id: categoryId },
        cache: false,
        success: function(dataResult) {
            $('#sub').html(dataResult);
            if (selectedValue) {
                $('#sub').val(selectedValue);
                $('#sub_num').val(selectedValue);
            } else {
                $('#sub_num').val('');
            }
        }
    });
}

function admin_hideshowforms() {
    const isTransfer = $('#is_transfer').is(':checked');
    const status = ($('#status').val() || '').toUpperCase();

    if (isTransfer) {
        $('.hide_isp').show();
    } else {
        $('.hide_isp').hide();
        $('#isp').val('');
        $('#isp_num').val('');
        $('#refNo').val('');
        $('#date_refNo').val('');
    }

    if (status === 'CLOSED') {
        $('.hide_cl').show();
    } else {
        $('.hide_cl').hide();
        $('#date_closed').val('');
        $('#close_by').val('');
        $('#cl_desc').val('');
    }
}

function slct_isp() {
    $('#is_transfer').off('change').on('change', function () {
        admin_hideshowforms();
    });
    $('#status').off('change').on('change', function () {
        admin_hideshowforms();
    });
}

function slct_sub() {
    $('#cat').off('change').on('change', function () {
        $('#cat_num').val($(this).val());
        loadSubCategories($(this).val(), '');
    });
}

function gtsub_id() {
    $('#sub').off('change').on('change', function () {
        $('#sub_num').val($(this).val());
    });
}

function syncHiddenFields() {
    $('#it_num').val($('#itsup').val() || '');
    $('#cat_num').val($('#cat').val() || '');
    $('#sub_num').val($('#sub').val() || '');
    $('#isp_num').val($('#isp').val() || '');
}

function loadCommentThread(ticket_no) {
    const $remarksView = $('#remarks_view');
    const $threadPanel = $('#msg_thread');
    const ticketValue = (ticket_no || '').toString().trim();

    $remarksView.empty().html('<div class="text-muted">Loading comments...</div>');
    $('.dv_msg').show();
    $threadPanel.show();

    if (!ticketValue) {
        $remarksView.html('<div class="text-muted">No ticket selected.</div>');
        return;
    }

    $.ajax({
        url: 'get_first_comment.php',
        type: 'POST',
        dataType: 'json',
        data: { ticket_no: ticketValue, thread: 1 },
        success: function(response) {
            let html = '';
            let comments = [];

            if (Array.isArray(response)) {
                comments = response;
            } else if (response && typeof response === 'object') {
                if (Array.isArray(response.thread)) {
                    comments = response.thread;
                } else if (Array.isArray(response.comments)) {
                    comments = response.comments;
                }
            }

            if (comments.length > 0) {
                comments.forEach(function(comment) {
                    const userName = (comment.user || comment.name || 'System').toString().trim();
                    const commentDate = (comment.date || comment.comment_date || '').toString().trim();
                    const commentText = (comment.comment || comment.comment_details || '').toString().trim();

                    html += `
                        <div class="comment-item p-3">
                            <div class="fw-bold">${userName}</div>
                            <div class="small mb-1">${commentDate}</div>
                            <div style="white-space: pre-wrap;">${commentText}</div>
                        </div>
                    `;
                });
            } else {
                html = '<div class="text-muted">No comments yet.</div>';
            }

            $remarksView.html(html);
            $threadPanel.show();
        },
        error: function(xhr) {
            console.error('Comment thread load failed', xhr.responseText || xhr.statusText);
            $remarksView.html('<div class="text-danger">Unable to load comment thread.</div>');
            $threadPanel.show();
        }
    });
}

/**
 * Getdata.
 */
function getdata(){
    $.post('fetchdata/fetch_data.php',{mode:'dtb'},function(data){
        admin_datatable(data);
    },'json');
}
getdata();

var table
/**
 * Admin datatable.
 */
function admin_datatable(t){
const dataset=t.rptdata;
table =  $("#report_data").DataTable({

"dom":
'<"pull-left"lf><"pull-right">tip',
// stateSave: true,
"pagingType": "full_numbers",
"bDestroy": true,
"responsive": true, "lengthChange": false, "autoWidth": false,
language: {
search: "_INPUT_",
searchPlaceholder: "Search..."
},
pageLength:10,
data: dataset,
"order": [[ 1, "Desc" ]],

columns: [
    { 
        title: "ACTION", 
        data: null, 
        render: function(data, type, row) {
            return `
             <div style="display: flex; gap: 5px;">
                <button type="button" class='btn btn-danger btn-sm edit-btn' name='update'><i class='fas fa-edit'></i>EDIT</button>
                <button type="button" class='btn btn-primary btn-sm print-btn' data-id='${row.ticket_no}'>FIXED ASSET</button>
             </div>
            `;
        }
    },
    // ... rest of your columns
{title:"TicketNo", data:"ticket_no","defaultContent": ""},
{title:"Date Created", data:"date_created","defaultContent": ""},
{title:"  Store", data:"str_code","defaultContent": ""},
{title:"Subject", data:"subject","defaultContent": ""},
// {title:"Concern", data:"concern","defaultContent": ""},
{title:"Via", data:"via","defaultContent": ""},
{title:"STATUS", data:"status","defaultContent": ""},
// {title:"Assigned Support", data:"it_desc","defaultContent": ""},
{title:"CATEGORY", data:"category","defaultContent": ""},
{title:"SUBCATEGORY", data:"sub_category","defaultContent": ""},
{title:"DATE CLOSED", data:"date_closed","defaultContent": ""},
{title:"DAYS COMPLETION", data:"tdc","defaultContent": ""}




],
"columnDefs": [
{ 

  targets: [9,10],
  "width": "2%",
  render: function ( data, type, row) {
      if(type === 'display'){
          if(data == '1 Days Unresolved'){
            data = '1 Day Unresolved'
          }
         else if(data == '01/01/1970 08:00'){
            data = 'UNRESOLVED'
          }
          else if(data<0){
            data =   ''
          }
          else if(data == 0){
            data = 'Solve Immediately'
          }
  }
  return data;
}
}
],


rowCallback: function (row, data) {

  $(row).find('td:eq(6)').attr('style', '');

  const status = (data['status'] || '').toUpperCase();
  const $statusTd = $(row).find('td:eq(6)'); 

  if (status === 'ON PROCESS') {
    $statusTd.attr('style', 'color:#F97316 !important; font-weight:800;');
  } else if (status === 'SUBJECT FOR CLOSING') {
    $statusTd.attr('style', 'color:#7C3AED !important; font-weight:800;');
    
  }
  else if (status === 'PENDING') {
    $statusTd.attr('style', 'color:#CC313F !important; font-weight:800;');
  } else if (status === 'CLOSED') {
    $statusTd.attr('style', 'color:#16A34A !important; font-weight:800;');
  } else {
    // default
    $statusTd.attr('style', 'color:#374151 !important; font-weight:700;');
  }
}

});
$('#report_data tbody').on('click', '.edit-btn', function(e) {
        e.stopPropagation();
        $('#dataModal').modal('hide');

        var data = table.row($(this).parents('tr')).data();
        var tid = $(this).parent().siblings(':first').html();

        $('#ticket_no').val(data['ticket_no']);
        $('#str_num').val(data['store']);
        $('#store').val(data['store']);
        $('#date_created').val(data['date_created']);
        $('#subjct').val(data['subject']).prop('readonly', true);
        $('#via').val(data['via']);
        $('#status').val(data['status']);
        $('#it_num').val(data['itsup']);
        $('#itsup').val(data['itsup']);
        $('#cat_num').val(data['cat_id']);
        $('#cat').val(data['cat_id']);
        loadSubCategories(data['cat_id'], data['sub_id']);
        $('#isp_num').val(data['isp_id']);
        $('#isp').val(data['isp_id']);
        $('#refNo').val(data['refNo']);
        $('#date_refNo').val(data['date_refNo']);
        
        admin_hideshowforms();
        $('#date_closed').val(data['date_closed']);
        $('#remarks').val(data['remarks']);
        loadCommentThread(data['ticket_no']);

        // Manage status/input state
        var isClosed = ($('#status').val() == 'CLOSED');
        $(':input[type="submit"]').prop('disabled', isClosed);
        $('#date_created, #date_refNo, #date_closed, #remarks').prop('readonly', isClosed);
        $('#via, #status, #itsup, #cat, #sub, #isp').prop("disabled", isClosed);

        $('.modal-title').text("Ticket Number: " + tid);
        $('#action').val("Save and Reply");
        $('#operation').val("Save and Reply");
        $('#userModal').modal({ "show": true, "backdrop": 'static' });
    });

    $('#userModal').on('shown.bs.modal', function () {
        const ticketNo = $('#ticket_no').val();
        if (ticketNo) {
            loadCommentThread(ticketNo);
        }
    });


// table
// .search( '' )
// .columns().search( '' )
// .draw();

$('#card_totalval').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 6 )
.search(val)
.draw();
} );


$('#card_openval').on('click', function () {
// var val =  $(this).attr("value");
var val =  $(this).attr("value");
// alert(val);
table
.columns( 6 )
.search(val)
.draw();
} );

$('#card_openwfaval').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 6 )
.search(val)
.draw();
} );

$('#card_closedval').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 6 )
.search(val)
.draw();
} );


$('#myInput').on( 'input', function () {
    table.search( this.value ).draw();
} );

} // end of data table


$('#store_graph_modal').modal('hide'); 

//crd_btm();
slct_isp();
// slct_itsup();
slct_sub();
gtsub_id();
admin_hideshowforms();  

$('#itsup').on('change', function () {
    syncHiddenFields();
});

$('#isp').on('change', function () {
    syncHiddenFields();
});

const yr =$("#yearpicker").val();
get_card_data(yr)
/**
 * Get card data.
 */
function get_card_data(y){
    $.ajax({
        url: 'fetchdata/fetch_data.php',
        type: 'POST',
        dataType: 'json', // Forces jQuery to handle the JSON automatically
        data: { yr: y, mode: 'yearch' },
        success: function(card_data) {
            console.log('Card data response:', card_data);
            var records = [];
            if (Array.isArray(card_data)) {
                records = card_data;
            } else if (card_data && typeof card_data === 'object') {
                records = Object.values(card_data);
            }

            if (records.length > 0 && records[0]) {
                $('#count_total').html(records[0].total_res || 0);
                $('#count_open').html(records[0].open_res || 0);
                $('#count_owfa').html(records[0].owfa_res || 0);
                $('#count_closed').html(records[0].cls_res || 0);
            } else {
                $('#count_total').html(0);
                $('#count_open').html(0);
                $('#count_owfa').html(0);
                $('#count_closed').html(0);
            }
        },
        error: function(xhr, status, error) {
            console.error("Card Data Error: ", status, error, xhr.responseText);
        }
    });
}

$(function () {
$('#datetimepicker1, #datetimepicker2, #datetimepicker3').datetimepicker()
});

$("#yearpicker").on('change',function(){
const yr =$("#yearpicker").val()
// reports_total(this.value);
get_card_data(this.value);
_techgraph(yr);
_overallpie(yr);
_dbline(yr); 
_catpie(yr);
_areagraph(yr);

});

$('#cat').on('change', function() {
var category_id = this.value;
$.ajax({
url: "get_subcat.php",
type: "POST",
data: {
category_id: category_id
},
cache: false,
success: function(dataResult){
$("#sub").html(dataResult);
}
}); 
});   


$('#add_button').click(function(){
$('#remarks_view').empty();
$('#report_form').trigger('reset');
$('.modal-title').text("ADD REPORT");
$('#subjct').attr('readonly', false);
$('#action').val("Add");
$('#operation').val("Add");
$('#date_created').val('<?= $datetime->format('m/d/Y g:i A'); ?>').attr('readonly', false);
$('#date_refNo').attr('readonly', false);
$('#date_closed').attr('readonly', false);
$('#store').prop("disabled", false);
$('#via').prop("disabled", false);
$('#status').prop("disabled", false);
$('#itsup').prop("disabled", false);
$('#cat').prop("disabled", false);
$('#sub').prop("disabled", false);
$('#isp').prop("disabled", false);
$(':input[type="submit"]').prop('disabled', false); 
$('#remarks').attr('readonly', false);
$('#msgbtn').hide();
$('#sub').html('<option value="">Select SubCategory</option>');
$('#sub_num').val('');
$('#it_num').val('');
$('#cat_num').val('');
$('#isp_num').val('');
$('#addmsg').val('');
syncHiddenFields();
admin_hideshowforms();
$("#userModal").on('hidden.bs.modal', function(){


});
$('#userModal').modal({backdrop: 'static', keyboard: false}) 
$("#userModal").off('hidden.bs.modal').on('hidden.bs.modal', function(){
    location.reload();
});

});

// _insert_data();


$(document).on('click', '#dtbsecond', function(){

  var val = jQuery('#ticket_no').val();


  $.ajax({
      type: 'POST',
      url: 'sesticket.php',
      data: {tktval: val},
      success: function(response) {
        $('#img').html(response);
      }
    });

});




$(document).on('click', '#msgbtn', function(){

$('.dv_msg').show();
$('#remarks_view').show();


if($('#msgbtn').val() == 'show'){
$('#action').val("Save and Reply");
$('#operation').val("Save and Reply");
$('#msgbtn').val("hide");
$('#container_remarks').show('slow');
}
else if($('#msgbtn').val() == 'hide'){
$('#action').val("Save");
$('#operation').val("Save and Reply");
$('#msgbtn').val("show");
$('#container_remarks').hide('slow');
}



});

$('#btnClose').click(function(){
// alert("working");
$('report_form').trigger('reset');
$('.dv_msg').hide();
$('#remarks_view').hide();
$('#tmpsubid').remove();
});


$('#subpie_clsbtn').click(function(event) {
event.preventDefault();
$('#chartdiv9').empty();

});

$('#substr_clsbtn').click(function(event) {
event.preventDefault();
$('#substr_clsbtn').empty();

});



});//document ready close

$(document).on("submit", "#report_form", function (e) {
    // alert("1");
    e.preventDefault();
    syncHiddenFields();

    let $submitBtn = $(this).find(':input[type="submit"]');
    $submitBtn.prop('disabled', true);
    var TicketNumber = $("#ticket_no").val();
    var Store = $("#store").val();
    var DateCreated = $("#date_created").val();
    var Concern = $("#subjct").val();
    var Status = $("#status").val();
    var Via = $("#via").val();
    var ItSupport = $("#itsup").val();
    var cat_id = $("#cat").val();
    var sub_id = $("#sub").val();
    var DateClosed = $("#date_closed").val();
    var CloseBy = $("#close_by").val();
    var remarks = $("#remarks").val();

    var today = new Date();
    DateCreated = new Date(DateCreated);
    DateClosed = new Date(DateClosed);
    if (DateCreated > today) {
      alert("Invalid date");
      $submitBtn.prop('disabled', false); // Re-enable on error
      return false;
    }
    else if (DateClosed > today ){
      alert("Invalid Closed_Date");
      $submitBtn.prop('disabled', false); // Re-enable on error
      return false;
    }

    if (Store != "" && DateCreated != "" && Concern != "" && Status != "" && Via != "" && ItSupport != "" && cat_id != "" && sub_id != "") {
      if (!$('#it_num').val()) { $('#it_num').val(ItSupport); }
      if (!$('#cat_num').val()) { $('#cat_num').val(cat_id); }
      if (!$('#sub_num').val()) { $('#sub_num').val(sub_id); }
      
      $.ajax({
        url: "insert.php",
        method: "POST",
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function (data) {
          const ticketNo = $('#ticket_no').val();
          Swal.fire({
             icon: 'success',
             title: 'Your work has been saved',
             showConfirmButton: false,
             timer: 1500
          });

          if (ticketNo) {
              $('#addmsg').val('');
              loadCommentThread(ticketNo);
          } else {
              $('#userModal').modal('hide');
          }
        },
        complete: function() {
            $submitBtn.prop('disabled', false); 
        }
      });
    } else {
      alert("All Fields are Required");
      $submitBtn.prop('disabled', false); // Re-enable on error
    }
});


// $('#action').click(function(event) {
//   alert("Updated Successfully")
//   location.reload();
// });


// function _insert_data() {
//   $(document).on("submit", "#report_form", function (e) {
//     // alert("1");
//     e.preventDefault();
//     var TicketNumber = $("#ticket_no").val();
//     var Store = $("#store").val();
//     var DateCreated = $("#date_created").val();
//     var Concern = $("#concern").val();
//     var Status = $("#status").val();
//     var Via = $("#via").val();
//     var ItSupport = $("#itsup").val();
//     var cat_id = $("#cat").val();
//     var sub_id = $("#sub").val();
//     var DateClosed = $("#date_closed").val();
//     var CloseBy = $("#close_by").val();
//     var remarks = $("#remarks").val();

//     var today = new Date();
//     DateCreated = new Date(DateCreated);
//     DateClosed = new Date(DateClosed);
//     if (DateCreated > today) {
//       alert("Invalid date");
//       return false;
//     }
//     // else if (Status == 'ON PROCESS')
//     // {
//     //   if (DateClosed < DateCreated ){
//     //   alert("Date closed should be greater than date created!");
//     //   return false;
//     // }
//     // }

//     else if (DateClosed > today ){
//       alert("Invalid Closed_Date");
//       return false;
//     }

//     if (
//       Store != "" &&
//       DateCreated != "" &&
//       Concern != "" &&
//       Status != "" &&
//       Via != "" &&
//       ItSupport != "" &&
//       cat_id != "" &&
//       sub_id != ""
//     ) {
//       $.ajax({
//         url: "insert.php",
//         method: "POST",
//         data: new FormData(this),
//         contentType: false,
//         processData: false,
//         success: function (data) {
//           // alert(data);
//           // $("#report_form")[0].reset();
//           Swal.fire({
//              icon: 'success',
//              title: 'Your work has been saved',
//              showConfirmButton: false,
//              timer: 1500
//           });
//           $("#userModal").modal("hide");
//       //     setTimeout(function(){// wait for 5 secs(2)
//       //      location.reload(); // then reload the page.(3)
//       // }, 2000); 
//         },
//       });
//     } else {
//       alert("All Fields are Required");
//     }
//      clearconsole();
//   });
// }

</script>

<div class="modal fade" id="dataModal" tabindex="-1" role="dialog" aria-labelledby="dataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 80%; width: 80%;">
        <form id="pdfForm" action="insert.php" method="POST">
            <input type="hidden" name="operation" value="submit_request">
            <input type="hidden" name="requesting_dept" id="requesting_dept">
            <input type="hidden" name="requesting_employee" id="requesting_employee">
            <input type="hidden" name="received_by" value="<?php echo $_SESSION['tech_id'] ?? ''; ?>">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dataModalLabel">Fixed Asset Information & Tracking</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7 border-right pt-2 pb-2">
                            <h6 class="text-uppercase mb-3" style="color:#213456; font-weight: 800;">Request Details</h6>
                            <div class="row">

                             <div class="form-group col-md-6">
            <label>Ticket No</label>
        <input type="text" class="form-control" name="ticket_no" id="modal_ticket_no">
         </div>
                                <div class="form-group col-md-6">
                                    <label>Requesting Dept/Branch:</label>
                                    <textarea class="form-control" name="requested_db_name" rows="2" readonly></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Requesting Employee</label>
                                    <textarea class="form-control" name="requested_by_name" rows="2" readonly></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Ticket Created</label>
                                    <textarea class="form-control" name="date_created" rows="2" readonly></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Item Code</label>
                                    <textarea class="form-control" name="item_code" rows="2" ></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description" rows="2" ></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Serial Number</label>
                                    <input type="text" class="form-control" name="serial_number" required placeholder="Type the serial number here...">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Purpose of Request</label>
                                    <textarea class="form-control" name="purpose_of_request" style="height: 100px;" readonly></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Item Received By</label>
                                    <input type="text" class="form-control" name="received_name" value="<?php echo ($_SESSION['fname'] ?? '') . ' ' . ($_SESSION['lstname'] ?? ''); ?>" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Date Received</label>
                                    <input type="date" class="form-control" name="date_received" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 pt-2 pb-2" style=" background: linear-gradient(to bottom, #ffffff, #bbc2cf); border-radius: 8px;">
                            <h6 class="text-uppercase mb-3" style="color:#E1AD01; font-weight: 800;">Asset Request Progress</h6>
                            <div class="tracking-container" style="max-height: 650px; overflow-y: auto; padding-right: 10px;">
                                <ul class="tracking-timeline" id="trackingMap"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <strong>SUBMIT REQUEST</strong>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
  .placeholder-style {
  color: #6c757d; 
  font-style: italic;
}
  #dataModal .modal-content {
  border: none;
  border-radius: 15px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
}

#dataModal .modal-header {
  background-color: #213456;;
  color: #fff;
  border-top-left-radius: 15px;
  border-top-right-radius: 15px;
  border-bottom: 4px solid #E1AD01;
}

#dataModal .modal-title {
  font-weight: 700;
  letter-spacing: 0.5px;
  display: flex;
  align-items: center;
}

#dataModal .input-group-text {
  background-color: #494949;
  border-right: none;
  color: #213456;
}

#dataModal .form-control {
  border-left: none;
  height: 45px;
  
}

#dataModal .form-control:focus {
  border-color: #213456;
  box-shadow: none;
}

#dataModal .input-group:focus-within {
  box-shadow: 0 0 0 0.2rem rgba(225, 173, 1, 0.25);
  border-radius: 8px;
}

#btn_chngepass {
  background-color: #E1AD01;
  border: none;
  color: #213456;
  font-weight: 700;
  padding: 10px 40px;
  border-radius: 30px;
  transition: all 0.3s ease;
}

#btn_chngepass:hover {
  background-color: #213456;
  color: #E1AD01;
  transform: translateY(-2px);
}


.toggle-password {
  cursor: pointer;
  position: absolute;
  right: 15px;
  top: 13px;
  z-index: 10;
  color: #6c757d;
}

.tracking-timeline {
    list-style: none;
    padding: 0;
    margin: 0;
    position: relative;
}

.tracking-timeline::before {
    content: '';
    position: absolute;
    top: 5px;
    bottom: 0;
    left: 11px; 
    width: 2px;
    border-left: 2px dotted #a3a3a3;
    z-index: 1;
}

.timeline-item {
    position: relative;
    padding-left: 35px;
    padding-bottom: 20px;
}

.timeline-icon {
    position: absolute;
    left: 4px;
    top: 2px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background-color: #e0e0e0;
    border: 3px solid #ffffff;
    z-index: 2;
    box-shadow: 0 0 0 1px #ccc;
    transition: all 0.3s ease;
}

.timeline-item.completed .timeline-icon {
    background-color: #16A34A; 
    box-shadow: 0 0 0 2px #16A34A;
}

.timeline-item.pending .timeline-icon {
    background-color: #E1AD01;
    box-shadow: 0 0 0 2px #E1AD01;
}

.timeline-desc {
    font-size: 12px;
    font-weight: 700;
    color: #333;
    margin-bottom: 2px;
    text-transform: uppercase;
}

.timeline-date {
    font-size: 11px;
    color: #6c757d;
    font-style: italic;
}
  </style>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>$(document).ready(function () {
    $("#pdfForm").on("submit", function (e) {
        e.preventDefault();

        $.ajax({
            url: "insert.php",
            type: "POST",
            data: $(this).serialize(),
            beforeSend: function () {
                Swal.fire({
                    title: "Saving...",
                    text: "Please wait.",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function (response) {
                Swal.close();
                response = $.trim(response);

                if (response === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Success!",
                        text: "Asset request submitted successfully.",
                        confirmButtonColor: "#213456"
                    }).then(() => {
                        $("#dataModal").modal("hide");
                        $('#pdfForm').trigger('reset');

                        if ($("#report_data").length && $.fn.DataTable.isDataTable("#report_data")) {
    try {
        getdata(); // Call your custom refresh function instead of ajax.reload()
    } catch (err) {
        console.error("Failed to reload data: ", err);
    }
}
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Submission Failed",
                        html: "<b>Server returned:</b><br>" + response,
                        confirmButtonColor: "#d33"
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    html: xhr.responseText 
                });
            }
        });
    });
});

$(document).on('click', '.print-btn', function(e) {
    e.preventDefault();
    e.stopPropagation();
    console.log('FIXED ASSET click', $(this).data('id'));

    let ticket_no = $(this).data('id');
    if (!ticket_no) {
        console.error('Missing ticket_no for fixed asset modal');
        return;
    }

    $('#userModal').modal('hide');

    $.ajax({
        url: 'get_first_comment.php', 
        type: 'POST',
        dataType: 'json', 
        data: { ticket_no: ticket_no },
        success: function(response) {
            if (!response || typeof response !== 'object') {
                console.error('Invalid response for fixed asset', response);
                return;
            }

            $('#pdfForm').trigger('reset');
            $('#modal_ticket_no').val(ticket_no);

            $('textarea[name="purpose_of_request"]').val(response.purpose || '');
            $('textarea[name="item_code"]').val(response.cat_desc || ''); 
            $('textarea[name="description"]').val(response.sub_cat || '');      
            $('input[name="serial_number"]').val(response.serial_number || '');
            $('textarea[name="date_created"]').val(response.date_created || '');     
            $('textarea[name="requested_by_name"]').val(response.requested_by_name || '');    
            $('textarea[name="requested_db_name"]').val(response.requested_db_name || '');    
            $('input[name="requesting_dept"]').val(response.requesting_dept || '');    
            $('input[name="requesting_employee"]').val(response.requesting_employee || '');    

            const statusLevels = {
                'submitted': 1, 'noted': 2, 'validated': 3, 'printed': 4,
                'recorded': 5, 'verified': 6, 'approved': 7, 'completed': 8
            };

            let dbStatus = (response.status || "").toLowerCase().trim();
            let currentLevel = statusLevels[dbStatus] || 0; 

            const trackSteps = [
                { desc: "Request submitted by store/user", date: response.date_created, reqLevel: 0 },
                { desc: "Under technical evaluation", date: response.date_created, reqLevel: 0 },
                { desc: "Submitted to technical head", date: response.date_submitted, reqLevel: 1 },
                { desc: "Approved and noted by technical head", date: response.date_noted, reqLevel: 2 },
                { desc: "For admin support validation", date: null, reqLevel: 2 }, 
                { desc: "Validated by admin support", date: response.date_validated, reqLevel: 3 },
                { desc: "For printing request form", date: null, reqLevel: 3 }, 
                { desc: "Printed", date: response.date_printed, reqLevel: 4 },
                { desc: "For recording", date: null, reqLevel: 4 }, 
                { desc: "Recorded", date: response.date_recorded, reqLevel: 5 },
                { desc: "For administrative verification", date: null, reqLevel: 5 }, 
                { desc: "Verified by the administrator", date: response.date_verified, reqLevel: 6 },
                { desc: "For AGM approval", date: null, reqLevel: 6 }, 
                { desc: "Approved by AGM", date: response.date_approved, reqLevel: 7 },
                { desc: "Ready for asset replacement", date: null, reqLevel: 7 }, 
                { desc: "Asset replaced / Completed", date: response.date_completed, reqLevel: 8 }
            ];

            let timelineHtml = '';
            trackSteps.forEach((step) => {
                let statusClass = (currentLevel >= step.reqLevel) ? "completed" : "";
                let dateDisplay = step.date ? `<div class="timeline-date">${step.date}</div>` : '';

                timelineHtml += `
                    <li class="timeline-item ${statusClass}">
                        <div class="timeline-icon"></div>
                        <div class="timeline-desc">${step.desc}</div>
                        ${dateDisplay}
                    </li>
                `;
            });

            $('#trackingMap').html(timelineHtml);
            
            $('#dataModal').modal('show');
        },
        error: function(xhr, status, error) {
            console.error('Error fetching asset details:', status, error, xhr.responseText);
            alert('Error fetching asset details. Check console log records.');
        }
    });
});
</script>