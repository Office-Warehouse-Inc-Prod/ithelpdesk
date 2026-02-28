<?php

// ======== db  =========
include 'admin.php';
include '../condb.php';
include 'adminpanel_obj.php';
// include 'chrtdashboard.php';
include 'sub_graph_modal.php';
// include 'testcalendar.php';

// $conn=new dbconfig();




?>
<style>
/* =========================
   MODERN DASHBOARD UI (Drop-in)
   Keeps your existing classes & IDs
   ========================= */

:root{
  --bg0:#0b1220;
  --bg1:#0f172a;
  --card: rgba(16, 26, 51, .58);
  --cardSolid:#0f1a33;
  --text:#e5e7eb;
  --muted:#9ca3af;
  --line:rgba(255,255,255,.10);
  --shadow: 0 20px 55px rgba(0,0,0,.45);
  --radius:18px;
  --radius-sm:14px;
  --focus: 0 0 0 .2rem rgba(59,130,246,.25);
}

html, body { height:100%; }

body{
  font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
  background:
    radial-gradient(900px 600px at 15% 10%, rgba(56,189,248,.16), transparent 55%),
    radial-gradient(700px 500px at 85% 20%, rgba(168,85,247,.14), transparent 55%),
    radial-gradient(700px 500px at 50% 90%, rgba(34,197,94,.10), transparent 55%),
    linear-gradient(180deg, var(--bg0), var(--bg1));
  color: var(--text);
}

/* ===== Dark mode toggle (works with your checkbox logic) ===== */
body.dark-mode{
  background:
    radial-gradient(900px 600px at 15% 10%, rgba(56,189,248,.14), transparent 55%),
    radial-gradient(700px 500px at 85% 20%, rgba(168,85,247,.12), transparent 55%),
    radial-gradient(700px 500px at 50% 90%, rgba(34,197,94,.08), transparent 55%),
    linear-gradient(180deg, #050814, #0b1022);
  color: var(--text);
}

::selection { background: rgba(59,130,246,.35); color: #fff; }

/* ===== Top controls ===== */
.form-check.form-switch{
  background: rgba(16, 26, 51, .45);
  border: 1px solid var(--line);
  border-radius: 999px;
  padding: 10px 14px;
  backdrop-filter: blur(10px);
  box-shadow: 0 10px 30px rgba(0,0,0,.25);
}

.form-check-label{
  color: var(--muted) !important;
  font-weight: 700;
  letter-spacing: .02em;
}

.form-check-input{
  cursor:pointer;
}
.form-check-input:checked{
  background-color: rgba(59,130,246,.9) !important;
  border-color: rgba(59,130,246,.9) !important;
}

/* ===== Input groups / yearpicker ===== */
.input-group-text{
  background: rgba(255,255,255,.06) !important;
  border: 1px solid var(--line) !important;
  color: rgba(229,231,235,.85) !important;
  font-weight: 800;
  letter-spacing: .02em;
  border-radius: 14px 0 0 14px !important;
}

select.form-control,
.form-control{
  background: rgba(255,255,255,.06) !important;
  border: 1px solid var(--line) !important;
  color: var(--text) !important;
  border-radius: 0 14px 14px 0 !important;
  padding: 10px 12px !important;
}

select.form-control:focus,
.form-control:focus{
  border-color: rgba(59,130,246,.55) !important;
  box-shadow: var(--focus) !important;
}

/* Calendar button */
#showCalendarBtn{
  border-radius: 14px !important;
  padding: 10px 16px !important;
  font-weight: 800;
  letter-spacing: .02em;
  border: 1px solid rgba(59,130,246,.35) !important;
  background: rgba(59,130,246,.22) !important;
  transition: .15s ease;
}
#showCalendarBtn:hover{
  transform: translateY(-1px);
  background: rgba(59,130,246,.30) !important;
}

/* ===== Card deck / dashcards ===== */
.card-deck{
  gap: 14px;
}

.dashcard{
  border-radius: var(--radius) !important;
  border: 1px solid var(--line) !important;
  box-shadow: 0 18px 45px rgba(0,0,0,.35);
  overflow: hidden;
  transform: translateY(0);
  transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
}

.dashcard:hover{
  transform: translateY(-4px);
  box-shadow: 0 26px 70px rgba(0,0,0,.45);
  filter: brightness(1.03);
}

/* remove old neon pulse behavior */
@keyframes pulse-glow{ 0%{ } 50%{ } 100%{ } }
.dashcard:hover{ animation: none !important; }

.dashcard .card-body{
  padding: 16px 16px 8px 16px;
}
.dashcard .card-title{
  font-size: 14px;
  font-weight: 900;
  letter-spacing: .04em;
  text-transform: uppercase;
  margin-bottom: 6px;
  text-shadow: none !important;
}
.dashcard .card-title span{
  font-size: 34px !important;
  font-weight: 900;
}

/* footers */
.dashcard .card-footer{
  background: rgba(255,255,255,.06) !important;
  border-top: 1px solid var(--line) !important;
}

/* Keep your bootstrap bg-* colors but make them less harsh */
.bg-primary{ background: linear-gradient(135deg, rgba(59,130,246,.80), rgba(59,130,246,.35)) !important; }
.bg-danger{  background: linear-gradient(135deg, rgba(239,68,68,.78), rgba(239,68,68,.32)) !important; }
.bg-warning{ background: linear-gradient(135deg, rgba(245,158,11,.78), rgba(245,158,11,.32)) !important; }
.bg-success{ background: linear-gradient(135deg, rgba(34,197,94,.78), rgba(34,197,94,.32)) !important; }

/* ===== Main cards (card2) ===== */
.card2{
  width: 100%;
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  backdrop-filter: blur(10px);
}

.card2 .card-header{
  background: transparent !important;
  border-bottom: 1px solid var(--line) !important;
  font-size: 14px !important;
  font-weight: 900 !important;
  text-transform: uppercase;
  letter-spacing: .08em;
  color: rgba(229,231,235,.9) !important;
}

.card2 .card-body{
  padding: 16px;
}

/* ===== Tables (your #report_data + network_tb) ===== */
#report_data,
#network_tb{
  width:100% !important;
  border-collapse: separate !important;
  border-spacing: 0 10px !important;
  background: transparent !important;
  color: var(--text) !important;
}

#report_data thead tr,
#network_tb thead tr{
  background: transparent !important;
}

#report_data thead th,
#network_tb thead th{
  border: none !important;
  color: rgba(229,231,235,.85) !important;
  font-weight: 900 !important;
  letter-spacing: .06em;
  text-transform: uppercase;
  font-size: 12px;
  padding: 14px 12px !important;
  text-align: center;
}

#report_data tbody tr,
#network_tb tbody tr{
  background: rgba(15, 26, 51, .70) !important;
  border: 1px solid var(--line);
  border-radius: 14px;
  box-shadow: 0 10px 25px rgba(0,0,0,.25);
  transition: transform .15s ease, background .15s ease;
}

#report_data tbody tr:hover,
#network_tb tbody tr:hover{
  transform: translateY(-1px);
  background: rgba(17, 32, 62, .78) !important;
}

#report_data td,
#network_tb td{
  border: none !important;
  padding: 14px 12px !important;
  font-size: 13px;
  color: rgba(229,231,235,.92) !important;
  text-align: center;
}

#report_data tbody tr td:first-child,
#network_tb tbody tr td:first-child{
  border-top-left-radius: 14px;
  border-bottom-left-radius: 14px;
}
#report_data tbody tr td:last-child,
#network_tb tbody tr td:last-child{
  border-top-right-radius: 14px;
  border-bottom-right-radius: 14px;
}

/* Keep your status row classes but make them subtle */
.status-open td{
  color: #fca5a5 !important;
}
.status-fixed td{
  color: #fdba74 !important;
}
.status-closed td{
  color: #86efac !important;
}
.status-subject-closing td{
  color: #d8b4fe !important;
}

/* ===== Buttons ===== */
.btn{
  border-radius: 14px !important;
  padding: 10px 14px !important;
  font-weight: 800 !important;
  letter-spacing: .02em;
  border: 1px solid transparent !important;
  transition: .15s ease;
}
.btn:hover{ transform: translateY(-1px); }

.btn-success{
  background: rgba(34,197,94,.22) !important;
  border-color: rgba(34,197,94,.35) !important;
}
.btn-danger{
  background: rgba(239,68,68,.22) !important;
  border-color: rgba(239,68,68,.35) !important;
}
.btn-primary{
  background: rgba(59,130,246,.22) !important;
  border-color: rgba(59,130,246,.35) !important;
}

/* ===== Modal modern glass ===== */
.modal-content{
  border: 1px solid var(--line) !important;
  border-radius: var(--radius) !important;
  background: rgba(12, 18, 35, .90) !important;
  box-shadow: var(--shadow);
  backdrop-filter: blur(12px);
}

.modal-header{
  border-bottom: 1px solid var(--line) !important;
  padding: 16px 18px !important;
}
.modal-title{
  font-size: 16px;
  font-weight: 900;
  letter-spacing: .04em;
  text-transform: uppercase;
  color: rgba(229,231,235,.92);
}
.modal-body{ padding: 18px !important; }
.modal-footer{
  border-top: 1px solid var(--line) !important;
  padding: 14px 18px !important;
}

/* Labels */
label{
  font-size: 11px;
  font-weight: 900;
  letter-spacing: .08em;
  text-transform: uppercase;
  color: rgba(229,231,235,.70);
}

/* Textareas */
textarea.form-control{
  border-radius: 14px !important;
}

/* Thread / remarks container */
.container_remarks{
  background: rgba(255,255,255,.04);
  border: 1px solid var(--line);
  border-radius: var(--radius-sm);
  padding: 12px;
  max-height: 320px;
  overflow: auto;
}

/* Nice scrollbar */
.container_remarks::-webkit-scrollbar{ width: 8px; height:8px; }
.container_remarks::-webkit-scrollbar-thumb{
  background: rgba(255,255,255,.16);
  border-radius: 99px;
}

/* HR */
hr{
  border-top: 1px solid var(--line) !important;
}

select.form-control {
    background-color: #1e293b !important;
    color: #ffffff !important;
    border: 1px solid #334155;
}

select.form-control option {
    background-color: #1e293b;
    color: #ffffff;
}


/* ACTION COLUMN */
.action-btn-group{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    white-space:nowrap;
}

/* PERFECT CIRCLE BUTTONS */
.btn-circle{
    width:34px;
    height:34px;
    padding:0;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    border:none;
    color:#fff;
    transition:all .18s ease;
}

/* HOVER EFFECT (very SaaS feel) */
.btn-circle:hover{
    transform:translateY(-2px);
    box-shadow:0 6px 14px rgba(0,0,0,.18);
}

/* COLORS */
.btn-edit{
    background:#0d6efd;
}

.btn-viber{
    background:#7360F2;
}

.btn-email{
    background:#20c997;
}

.btn-disabled{
    background:#6c757d;
    cursor:not-allowed;
}



</style>





<div class="container-fluid">
  <div id="wrapper">
    <div id="layoutSidenav_content">
      <div class="container-fluid">
      

        <form method="post" name="cof_form" id="cof_form" enctype="multipart/form-data">
          <div class="row">
            <input type="hidden" name="chcksbjcls" id="chcksbjcls" value="check">
          </div>
        </form>

        <!-- Combined Year Picker and Calendar Button -->
        <div class="d-flex align-items-center mb-3">
          <!-- Year Picker -->
          <div class="mr-3">
            <label class="sr-only" for="inlineFormInputGroup">Start Date</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">LOGS IN YEAR OF:</div>
              </div>
              <select class="form-control" name="yearpicker" id="yearpicker" required>
                <option value="2019,2020,2021,2022,2023,2024,2025,2026">OVERALL</option>
		<option value="2026" selected>2026</option>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
                <option value="2020">2020</option>
                <option value="2019">2019</option>
              </select>
            </div>
          </div>

    <form action="testcalendar.php" method="POST" style="display: inline;">
    <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id']; ?>">
    <button type="submit" id="showCalendarBtn" class="btn btn-primary">Show Calendar</button>
</form>
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

<div class="dashcard card text-white mb-4 bg-warning" style="width: 18rem; height: 9rem; ">
<div class="card-body">

<div class="card-title">ASSIGNED REPORTS: <span class="float-right" id="count_open" style="font-size: 32px;"></span></div>

</div>
<div class="card-footer d-flex align-items-center justify-content-between">

                       <a class=" text-white stretched-link" id="card_openval" href="#bottom" value="OPEN" ><span class="small text-white">Click here for more info.</span></a>
                   

                          <div class="go-arrow">  </div>
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-danger" style="width: 18rem; height: 9rem;">
<div class="card-body">

<div class="card-title" style="font-size: 15px;">Pending Reports: <span class="float-right" id="count_owfa" style="font-size: 32px;"></span></div>

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
                        <a class="text-white stretched-link" id="card_closedval" href="#bottom" value="CLOSED" ><span class="small text-white">Click here for more info.</span> </a><span class="small text-white">CLOSED REPORT HISTORY</span>
                          <div class="go-arrow">  </div>
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


<div class="form-group col-12 col-md-12 col-lg-12">
<label>SUBJECT/CONCERN</label>
<textarea name="subjct" id="subjct" class="form-control form-control-sm" placeholder="Input Concern" 
style="text-transform:uppercase" onkeyup="this.value = this.value;"></textarea>
</div>

<div class="form-group col-4 col-md-4 col-lg-4">
<label>STATUS</label>
<select class = "form-control form-control-sm" name= "status" id="status" required>
<option value=""> &larr; Status &rarr;</option>
<?php
      $query="select * from status  WHERE admin_module_tag = 'Y'";
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



<!-- <a href="viber://chat?number=%2B639358926389"
   class="btn btn-purple">
   Call via Viber
</a> -->




<div class="form-group col-12 col-md-8 col-lg-8">
<label>PRIORITY LEVEL</label>
<input type="hidden" name="priority_level" id="priority_level" value="">
<input type="text" name="priority_desc" id="priority_desc" class="form-control form-control-sm" placeholder="" readonly
style="text-transform:uppercase">
</div>

<hr>

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
<input type="hidden" name="u_id" id="u_id" value="<?php echo $_SESSION['user_id'];  ?>">

</div>
</form>
</div>
</div>
</div>
</div>







 

