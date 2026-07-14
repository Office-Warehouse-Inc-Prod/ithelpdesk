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
   OWI HELP DESK THEME (like screenshot)
   Navy + Yellow accents • light cards
   ========================= */

:root{
  --navy:#121C31;
  --navy2:#1a2a4a;
  --yellow:#EAAA00;
  --bg:#EEF2F7;
  --card:#ffffff;
  --line:#E5E7EB;
  --text:#111827;
  --muted:#6B7280;
  --shadow: 0 14px 32px rgba(17,24,39,.12);
  --radius:16px;
}
body {
  background: linear-gradient(to bottom, #ffffff, #6d89b9);
  margin: 0; 
  height: 100vh; 
 
} 
.navbar, header, .topbar, .navbar-default{
    background-color: #213456 !important;
    margin-bottom: 40px;
}
.navbar a, .navbar-brand, .navbar-nav > li > a,
.navbar i, .navbar .fa, .navbar .fas{
  color: #fff !important;
  font-weight: 600;
}
.navbar-nav > li.active > a,
.navbar-nav > li > a:hover{
  color: var(--yellow) !important;
}
.navbar-nav > li.active > a{
  border-bottom: 3px solid var(--yellow);
}

/* ===== Top controls strip (year picker / calendar / toggle) ===== */
.form-check.form-switch{
  background: #fff !important;
  border: 1px solid var(--line) !important;
  border-radius: 999px !important;
  box-shadow: 0 10px 22px rgba(17,24,39,.08) !important;
}
.form-check-label{ color: var(--muted) !important; font-weight: 700; }

.input-group-text{
  background: var(--navy) !important;
  color: #fff !important;
  border: 1px solid rgba(0,0,0,.08) !important;
  font-weight: 800 !important;
  border-radius: 12px 0 0 12px !important;
}
select.form-control, .form-control{
  background: #fff !important;
  color: var(--text) !important;
  border: 1px solid var(--line) !important;
  border-radius: 0 12px 12px 0 !important;
}
select.form-control:focus, .form-control:focus{
  border-color: rgba(234,170,0,.55) !important;
  box-shadow: 0 0 0 .2rem rgba(234,170,0,.18) !important;
}

/* Calendar button like screenshot */
#showCalendarBtn{
  background: var(--yellow) !important;
  color: #111827 !important;
  border: none !important;
  border-radius: 12px !important;
  font-weight: 900 !important;
  letter-spacing: .02em;
  box-shadow: 0 10px 22px rgba(17,24,39,.12);
}
#showCalendarBtn:hover{
  filter: brightness(.98);
  transform: translateY(-1px);
}

/* ===== Summary cards (top 4 cards) ===== */
.card-deck{ gap: 16px; }

.dashcard{
  background: var(--card) !important;
  border: 1px solid var(--line) !important;
  border-radius: 8px !important;
  overflow: hidden;
  position: relative;
  width: 80px;
}


.dashcard.bg-assigned { border-top: 4px solid #8CC0EB !important;  }
.dashcard.bg-assigned:hover { box-shadow: 0 22px 40px -5px rgba(140, 192, 235, 0.3); border-color: #2577ba; }

.dashcard.bg-onprocess { border-top: 4px solid #E9B63B !important;  }
.dashcard.bg-onprocess:hover { box-shadow: 0 22px 40px -5px rgba(233, 182, 59, 0.3); border-color: #E9B63B; }

.dashcard.bg-pending { border-top: 4px solid #AF3E3E !important; }
.dashcard.bg-pending:hover { box-shadow: 0 22px 40px -5px rgba(175, 62, 62, 0.3); border-color: #AF3E3E; }

.dashcard.bg-nonesca { border-top: 4px solid #8E7DBE !important;}
.dashcard.bg-nonesca:hover { box-shadow: 0 22px 40px -5px rgba(142, 125, 190, 0.3); border-color: #260d6d; }

.dashcard.bg-subforclosing { border-top: 4px solid #EDA35A !important; }
.dashcard.bg-subforclosing:hover { box-shadow: 0 22px 40px -5px rgba(237, 163, 90, 0.3); border-color: #EDA35A; }

.dashcard.bg-closed { border-top: 4px solid #5D866C !important;  }
.dashcard.bg-closed:hover { box-shadow:0 22px 40px -5px rgba(93, 134, 108, 0.3); border-color: #5D866C; }


.dashcard, .dashcard *{ color: var(--text) !important; }

.dashcard .card-body{
}
.dashcard .card-title{
    display: block;
  text-align: center;
  font-size: 12px !important;
  font-weight: 900 !important;
  letter-spacing: .08em;
  text-transform: uppercase;
  color: black !important;
}
.dashcard .card-title span{
  display: block;
  text-align: center;
  font-size: 32px !important;
  font-weight: 900 !important;
  color: var(--navy) !important;
  padding-bottom: -40px;
  
}

.dashcard .card-footer{
  
  margin-top: -20px;
  background: #fff !important;
  border-top: 1px solid var(--line) !important;
  padding: 0px 18px !important;
}
.dashcard .card-footer:hover{
  color: #E1AD01;
  
  margin-top: -20px;
  background: #fff !important;
  border-top: 1px solid var(--line) !important;
  padding: 0px 18px !important;
}
.dashcard .card-footer a{
    display: block;
  text-align: center;
  color: var(--muted) !important;
  font-weight: 700 !important;
  text-decoration: none !important;
}
.dashcard .card-footer a:hover{
  color: #E1AD01 !important;
  text-decoration: underline !important;
}

.card2{
  background: #fff !important;
  border: 1px solid var(--line) !important;
  border-radius: var(--radius) !important;
  box-shadow: var(--shadow) !important;
  overflow: hidden;
}

/* Navy header bar + yellow underline like screenshot */
.card2 .card-header{
  background: var(--navy) !important;
  color: var(--yellow) !important;
  font-weight: 900 !important;
  text-transform: none;
  letter-spacing: .02em;
  border-bottom: 3px solid var(--yellow) !important;
  padding: 14px 18px !important;
}
.card2 .card-body{ padding: 18px !important; }

/* ===== DataTables / tables (clean light) ===== */
#report_data, #network_tb{
  background: #fff !important;
  color: var(--text) !important;
}

#report_data thead th, #network_tb thead th{
  background: #F8FAFC !important;
  color: #334155 !important;
  font-weight: 900 !important;
  border-bottom: 1px solid var(--line) !important;
  white-space: nowrap;
}

#report_data tbody td, #network_tb tbody td{
  color: #334155 !important;
  border-color: var(--line) !important;
}

#report_data tbody tr:hover, #network_tb tbody tr:hover{
  background: #F8FAFC !important;
}

/* DataTables controls */
.dataTables_wrapper .dataTables_filter input,
.dataTables_wrapper .dataTables_length select{
  background: #fff !important;
  border: 1px solid var(--line) !important;
  border-radius: 10px !important;
  color: var(--text) !important;
}
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate{
  color: var(--muted) !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current{
  background: rgba(234,170,0,.18) !important;
  border-color: rgba(234,170,0,.35) !important;
}

/* ===== Action circle buttons keep modern look ===== */
.action-btn-group{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  white-space:nowrap;
}
.btn-circle{
  width:34px; height:34px;
  border-radius:50%;
  display:flex; align-items:center; justify-content:center;
  border:none;
  color:#fff !important;
  transition: .15s ease;
  box-shadow: 0 10px 18px rgba(17,24,39,.12);
}
.btn-circle:hover{ transform: translateY(-1px); }
.btn-edit{ background:#2563eb !important; }
.btn-viber{ background:#7360F2 !important; }
.btn-email{ background:#20c997 !important; }
.btn-disabled{ background:#94a3b8 !important; cursor:not-allowed; }

/* ===== Optional: status text only (if your JS colors rows) ===== */
.status-open td{ color:#b91c1c !important; }
.status-fixed td{ color:#c2410c !important; }
.status-closed td{ color:#15803d !important; }
.status-subject-closing td{ color:#6d28d9 !important; }

  
.bg-orange{
    background-color: orange;
    color:#fff;
}


/* -------- status closed all row ---- */
#report_data tbody tr.status-closed {
    background-color: #c8e6c9 !important;
    color: #237227!important;
}

/* Change Password Modal Custom Styles */
#userModal .modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
}

#userModal .modal-header {
    background-color: #213456;
    color: #fff;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    border-bottom: 4px solid #E1AD01; /* Your Theme Gold */
}

#userModal .modal-title {
    font-weight: 700;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
}

#userModal .input-group-text {
    background-color: white;
    border-right: none;
    color: #213456;
}

#userModal .form-control {
    border-left: none;
    height: 45px;
    border-radius: 0 8px 8px 0;
}

#userModal .form-control:focus {
    border-color: #213456;
    box-shadow: none;
}

#userModal .input-group:focus-within {
    box-shadow: 0 0 0 0.2rem rgba(225, 173, 1, 0.25);
    border-radius: 8px;
}

/* --- Right Side: Message Thread Panel --- */
#msg_thread {
    padding: 1rem 1.5rem;
    background-color: #f8fafc;
    height: 100%;
}

/* Comment Input Area */
#addmsg {
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 1rem;
    background: #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

/* Container for Remarks (The Thread) */
.container_remarks {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    height: 450px;
    overflow-y: auto;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Individual Message Bubbles (to be used in your JS output) */
.chat-bubble {
    max-width: 85%;
    padding: 0.8rem 1rem;
    border-radius: 15px;
    font-size: 0.9rem;
    line-height: 1.5;
    position: relative;
}

/* System/Received messages */
.chat-left {
    align-self: flex-start;
    background: #f1f5f9;
    color: #334155;
    border-bottom-left-radius: 2px;
}

/* User/Sent messages */
.chat-right {
    align-self: flex-end;
    background: #1C0770;
    color: #ffffff;
    border-bottom-right-radius: 2px;
}

.msg-meta {
    font-size: 0.7rem;
    color: #94a3b8;
    margin-bottom: 4px;
    display: block;
}

/* --- Action Buttons --- */
.btn-success {
    background-color: #1C0770 !important;
    border: none;
    padding: 0.6rem 2rem;
    font-weight: 600;
    border-radius: 8px;
    transition: transform 0.2s ease;
}

.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(28, 7, 112, 0.2);
}

.btn-danger {
    background-color: #fff;
    border: 1px solid #e2e8f0;
    color: #e53e3e;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    border-radius: 8px;
}

.btn-danger:hover {
    background-color: #fff5f5;
    color: #c53030;
}

/* --- Global Theme Vars --- */
:root {
    --navy-primary: #213456;
    --gold-accent: #E1AD01;
    --chat-bg: #f4f7f9;
}

/* --- Left Side: Refined Input Panel --- */
.m_col {
    background: #ffffff;
    padding: 2rem !important;
    border-right: 1px solid #edf2f7;
}

.m_col label {
    color: var(--navy-primary);
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 0.05em;
}

.m_col .form-control:focus {
    border-color: var(--gold-accent);
    box-shadow: 0 0 0 3px rgba(225, 173, 1, 0.15);
}

/* --- Right Side: Premium Messenger Thread --- */
#msg_thread {
    background-color: #213456;

}

.container_remarks {
    background: var(--chat-bg);
    height: 500px;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(123, 128, 44, 0.605);
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
    border: none;
}



/* Received (Support/Other Users) - Navy Style */
.msg-received {
    align-self: flex-start;
    background-color: var(--navy-primary);
    color: #ffffff;
    border-radius: 20px 20px 20px 5px;
}

/* Sent (You) - Gold Style */
.msg-sent {
    align-self: flex-end;
    background-color: var(--gold-accent);
    color: #ffffff;
    border-radius: 20px 20px 5px 20px;
}

/* Bubble Meta Info */
.msg-info {
    font-size: 0.7rem;
    margin-bottom: 4px;
    font-weight: 600;
    display: block;
}

.msg-received .msg-info { color: rgba(255,255,255,0.7); }
.msg-sent .msg-info { color: #ffffff; text-align: right; }

/* --- Chat Input Footer --- */
.chat-input-box {
    background: #ffffff;
    padding: 20px;
    border-top: 1px solid #e2e8f0;
    border-radius: 0 0 12px 0;
}

#addmsg {
    border-radius: 25px;
    padding: 12px 20px;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

#addmsg:focus {
    background-color: #ffffff;
    border-color: var(--gold-accent);
    box-shadow: 0 4px 12px rgba(225, 173, 1, 0.1);
    outline: none;
}
.card2 {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    background: #ffffff;
}

/* --- Header using your Navy/Gold Theme --- */
.card2 .card-header {
    background-color: #213456 !important; /* Your Navy */
    color: #E1AD01 !important; /* Your Gold */
    font-weight: 700;
    letter-spacing: 1px;
    padding: 1.2rem 1.5rem;
    border-bottom: 2px solid #E1AD01;
    display: flex;
    align-items: center;
}
/* Custom Scrollbar for the table container */
#proTeamScroll::-webkit-scrollbar {
    width: 6px;
}
#proTeamScroll::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 10px;
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

.overall-status-header {
    background-color: #1f375c !important;
    color: #ffc400 !important;
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 14px 18px;
    border-bottom: 3px solid #ffc400;
}

.overall-title {
    font-size: 20px;
    font-weight: 700;
    letter-spacing: 2px;
    white-space: nowrap;
    line-height: 1.1;
}

.overall-filter {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
}

.dept-label {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    letter-spacing: 2px;
    color: #ffc400;
    white-space: nowrap;
}

.dept-select {
    height: 38px;
    font-size: 15px;
    border-radius: 4px;
    border: 1px solid #b5a13a;
    box-shadow: none;
    flex: 1;
    min-width: 220px;
}

#chartdiv1 {
    width: 100%;
    height: 400px;
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

    
        </div>
      </div>
    </div>
  </div>


<div class="card-deck align-items-center mb-3">
  <div class="dashcard card text-white bg-assigned mb-2" style="width: 18rem; height: 9rem; ">
    <div class="card-body">
      <div class="card-title" style="color:#8CC0EB">ASSIGNED<br> <span class="float-center" id="count_assigned"></span></div>
    </div>
    <div class="card-footer d-flex align-items-center justify-content-between">
      <a class=" text-white stretched-link" id="card_assigned" href="#bottom" value="ASSIGNED" ><span class="small text-white">Click here for more info.</span></a>
      <div class="go-arrow"></div>
    </div>
  </div>

  <div class="dashcard card text-white mb-2 bg-onprocess border-dark" style="width: 20rem; height: 9rem;">
    <div class="card-body">
      <div class="card-title">ON PROCESS<br> <span class="float-center" id="count_onprocess"></span></div>         
    </div>                                  
    <div class="card-footer d-flex align-items-center justify-content-between">
      <a class=" text-white stretched-link" id="card_onprocess" href="#bottom" value="ON PROCESS" ><span class="small text-white">Click here for more info.</span></a>
    <div class="go-arrow">  </div>
    </div>
  </div>

  <div class="dashcard card text-white bg-pending mb-2" style="width: 20rem; height: 9rem;">
    <div class="card-body">
      <div class="card-title" style="font-size: 15px;">Pending<br> <span class="float-center" id="count_pending"></span></div>
    </div>
    <div class="card-footer d-flex align-items-center justify-content-between">
      <a class="text-white stretched-link" id="card_pending" href="#bottom" value="PENDING" ><span class="small text-white">Click here for more info.</span></a>
      <div class="go-arrow">  </div>
    </div>
  </div>
  
  <div class="dashcard card text-white bg-nonesca mb-2" style="width: 20rem; height: 9rem;">
    <div class="card-body">
      <div class="card-title" style="font-size: 15px;">ESCALATED <br><span class="float-center" id="count_nonesca"></span></div>
    </div>
    <div class="card-footer d-flex align-items-center justify-content-between">
      <a class="text-white stretched-link" id="card_nonesca" href="#bottom" value="ESCALATED" ><span class="small text-white">Click here for more info.</span></a>
      <div class="go-arrow">  </div>
    </div>
  </div>
  
  <div class="dashcard card text-white bg-subforclosing mb-2" style="width: 20rem; height: 9rem;">
    <div class="card-body">
      <div class="card-title">SUBJECT FOR CLOSING <br><span class="float-center" id="count_subforclosing"></span></div>
    </div>
    <div class="card-footer d-flex align-items-center justify-content-between">
      <a class="text-white stretched-link" id="card_subforclosing" href="#bottom" value="SUBJECT FOR CLOSING" ><span class="small text-white">Click here for more info.</span></a>
      <div class="go-arrow">  </div>
    </div>
  </div>

  <div class="dashcard card text-white bg-closed mb-2" style="width: 20rem; height: 9rem;">
    <div class="card-body">
      <div class="card-title" style="font-size: 15px;">CLOSED<br> <span class="float-center" id="count_closed"></span></div>
    </div>
    <div class="card-footer d-flex align-items-center justify-content-between">
      <a class="text-white stretched-link" id="card_closed" href="#bottom" value="CLOSED" ><span class="small text-white">Click here for more info.</span></a>
      <div class="go-arrow">  </div>
    </div>
  </div>
</div>



        <div class="row" id="ovrall">

<div class="col-12 col-lg-6 mb-3">
    <div class="card card2 h-100">

        <div class="card-header overall-status-header">
            <div class="overall-title">
                Overall Status
            </div>

            <div class="overall-filter">
                <label for="dept_id" class="dept-label">Department</label>
                <select name="dept_id" id="dept_id" class="form-control dept-select">
                    <option value="1,2,3,6,11,13,15,16" selected>All</option>
                    <option value="1">I.T DEPT</option>
                    <option value="2">ADMIN DEPARTMENT</option>
                    <option value="3">MARKETING DEPARTMENT</option>
                    <!-- <option value="4">MERCHANDISING DEPARTMENT</option>
                    <option value="5">MERCHANDISING DEPARTMENT - OFFICE FURNITURE</option> -->
                    <option value="6">VISUAL</option>
                    <!-- <option value="7">HUMAN RESOURCES</option> -->
                    <!-- <option value="8">VISUAL</option> -->
                    <!-- <option value="9">LOGISTIC DEPARTMENT</option> -->
                    <!-- <option value="10">STORE OPERATION</option> -->
                    <option value="11">HUMAN RESOURCES</option>
                    <!-- <option value="12">INVENTORY CONTROL GROUP</option> -->
                    <option value="13">ACCOUNTS PAYABLE</option>
                    <!-- <option value="14">SALES ACCOUNTING</option> -->
                    <option value="15">TREASURY</option>
                    <option value="16">ACCOUNTS RECEIVABLE</option>
                    <!-- <option value="17">MANAGEMENT SYSTEM SERVICES</option> -->
                </select>
            </div>
        </div>

        <div class="card-body">
            <div id="chartdiv1"></div>
        </div>
 
    </div>
</div>
<div class="col-12 col-lg-6 mb-3">
    <div class="card card2 h-100">
        <div class="card-header" style="background-color:#1f375c; color:#ffc400; font-weight:700;">
            Category Breakdown
            <span id="selected_status_title" style="color:white; font-size:14px;"></span>
        </div>

        <div class="card-body">
            <div id="chartdiv_category" style="width:100%; height:400px;"></div>
        </div>
    </div>
</div>

                      <div class="col-12 col-lg-12 col-md-12 mb-3">
            <div class="card card2 h-100">
              <h5 class="card-header" style="background-color: #95a2b9b4; color:black;">Tickets Per Area</h5>
              <div class="card-body">
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
<!-- <button type="button" id="prntForm" class="btn btn-info float-center" data-dismiss="modal"><i class="fas fa-print"></i></button> -->
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

<div class="form-group col-6 col-md-6 col-lg-6">
    <label>ASSIGNED DEPT</label>
    <select class="form-control form-control-sm" name="f_deptsel" id="f_deptsel" required>
        <option value="">Select Department...</option>  
        <?php
        $query = "SELECT * FROM tbl_dept WHERE dept_id <> '8'";
        $run = $conn->prepare($query);
        $run->execute();
        $rs = $run->get_result();
        while ($res = $rs->fetch_assoc()) {
            $brcnhid = $res['dept_id'];
            $brnchcd = $res['dept_desc'];
        ?>
            <option value="<?php echo $brcnhid; ?>"><?php echo $brnchcd; ?></option>
        <?php } ?>
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
<button type="button" name="btnClose" id="btnClose" class="btn btn-danger float-center" data-dismiss="modal">Close</button>  
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

       <label style="font-weight: bold; color:white;">Add Comment:</label>
<textarea name="admsg" id="addmsg" class="form-control form-control-sm" placeholder="Reply to their message or give an updates regarding on this ticket..." required></textarea> 
</div>
<div class="col-12 col-lg-12 mt-4 mb-2 dv_msg">
<label for="remarks_view" style="font-weight: bold; color: white;">Comment Thread:</label>
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


  <!-- =========================
Start of Create Department Report Modal
========================= -->
  <div class="modal fade" id="createReportModal" tabindex="-1" role="dialog" aria-labelledby="createReportModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form method="post" id="create_report_form" enctype="multipart/form-data">
        <div class="modal-content"
          style="border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);">
          <div class="modal-header"
            style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; border-top-left-radius: 15px; border-top-right-radius: 15px;">
            <h5 class="modal-title font-weight-bold" id="createReportModalLabel"><i
                class="fas fa-plus-circle mr-2 text-warning"></i> Create Ticket to IT Department</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
              style="opacity: 0.8; outline: none; background: none; border: none;">
              <span aria-hidden="true" style="font-size: 28px;">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="padding: 30px; background-color: #fcfcfc;">
            <div class="row">
              <!-- Left Side: Dropdowns -->
              <div class="col-12 col-lg-6">
                <div class="form-group mb-4">
                  <!--<label class="font-weight-bold text-secondary small"><i class="fas fa-store mr-1 text-primary" hidden></i>
                    STORE/BRANCH</label>-->
                  <select class="form-control" name="store" id="create_store" hidden required
                    style="border-radius: 8px; height: 45px; pointer-events: none; background-color: #e9ecef;">
                    <?php
                    $query = "select * from tbl_branch ";
                    $run = $conn->prepare($query);
                    $run->execute();
                    $rs = $run->get_result();
                    while ($res = $rs->fetch_assoc()) {
                      $brcnhid = $res['str_num'];
                      $brnchcd = $res['str_code'] . ' | ' . $res['str_name'];
                      $selected = ($brcnhid == '201') ? 'selected' : '';
                      ?>
                      <option value="<?php echo $brcnhid; ?>" <?= $selected; ?>><?= $brnchcd; ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="form-group mb-4">
                  <!--<label class="font-weight-bold text-secondary small hidden"><i class="fas fa-building mr-1 text-primary"></i>
                    ATTENTION TO (DEPARTMENT)</label>-->
                  <select class="form-control" name="deptsel" id="create_deptsel" hidden required
                    style="border-radius: 8px; height: 45px;">
                    <option value="" selected disabled>Select Here</option>
                    <option value="1">IT</option>
                    <option value="2">ADMIN</option>
                    <option value="3">MARKETING</option>
                    <option value="6">VISUAL</option>
                    <option value="11">H.R</option>
                    <option value="13">ACCOUNTS PAYABLE</option>
                    <option value="16">ACCOUNT RECEIVABLE</option>
                  </select>
                </div>

                <div class="form-group mb-4">
                  <!--<label class="font-weight-bold text-secondary small"><i class="fas fa-envelope mr-1 text-primary"></i>
                    SUBJECT (CATEGORY)</label>-->
                  <select class="form-control" id="create_subject" name="subject" required  hidden
                    style="width: 100%; pointer-events: none; background-color: #e9ecef;">
                    <option value="50" selected>SYSTEM</option>
                  </select>
                </div>

                <div class="form-group mb-4" id="create_sub_group" style="display: none;">
                  <!--<label class="font-weight-bold text-secondary small"><i class="fas fa-tag mr-1 text-primary"></i>
                    SUBCATEGORY</label>-->
                  <select class="form-control" id="create_sub" name="sub" hidden
                  style="width: 100%; pointer-events: none; background-color: #e9ecef; ">
                  <option value="238" selected>HELPDESK</option>
                </select>
                </div>
              </div>

              <!-- Right Side: Concern and Upload -->
              <div class="col-12 col-lg-12">
                <div class="form-group mb-4">
                  <label class="font-weight-bold text-secondary small"><i
                      class="fas fa-comment-alt mr-1 text-primary"></i> CONCERN (MESSAGE)</label>
                  <textarea class="form-control" id="create_concern" name="concern" minlength="10" maxlength="1000"
                    rows="5" placeholder="Describe the concern or request in detail..." required
                    style="border-radius: 8px; resize: none; height: 140px;"></textarea>
                </div>

                <div class="form-group mb-4">
                  <label class="font-weight-bold text-secondary small"><i
                      class="fas fa-paperclip mr-1 text-primary"></i> ATTACH FILES (OPTIONAL)</label>
                  <div class="custom-file">
                    <input id="create_file-input" type="file" name="file" multiple class="form-control-file">
                    <small class="form-text text-muted">Max file size: 2MB. Allowed types: Images, PDF, Docs,
                      Excel.</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer"
            style="background-color: #f1f3f6; border-bottom-left-radius: 15px; border-bottom-right-radius: 15px; border-top: 1px solid #e3e6ec; padding: 15px 30px;">
            <div class="mr-auto d-flex align-items-center">
              <span class="badge badge-info p-2 font-weight-bold text-uppercase"
                style="font-size: 13px; border-radius: 6px; letter-spacing: 0.5px; background-color: #17a2b8; color: white;">
                Generated Ticket: <span id="create_ticket_lbl">---</span>
              </span>
              <input type="hidden" name="ticket_no" id="create_ticket_no">
            </div>
            <button type="button" class="btn btn-light font-weight-bold px-4" data-dismiss="modal"
              style="border-radius: 8px; height: 40px;">Cancel</button>
            <button type="submit" name="action" id="create_action" class="btn btn-primary font-weight-bold px-4"
              style="border-radius: 8px; height: 40px; background-color: #1e3c72; border: none;"><i
                class="fas fa-save mr-2"></i>Save Ticket</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  

  <script>
$(document).ready(function () {
    // KPI Card Click Functionality
    $('.dashcard-clickable').on('click', function () {
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

    // Handle 'CREATE REPORT' Navbar Link Click
    $(document).on('click', '#navCreateReport', function (e) {
        if (window.location.pathname.endsWith('adminpanel.php') || window.location.pathname.endsWith('/admin/')) {
            e.preventDefault();
            $('#createReportModal').modal({ backdrop: 'static', keyboard: false });
        }
    });

    // Handle query param create=true on load
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('create') === 'true') {
        $('#createReportModal').modal({ backdrop: 'static', keyboard: false });
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    // Reset and Auto-populate Form when Modal Opens
    $('#createReportModal').on('show.bs.modal', function () {
        $('#create_report_form').trigger('reset');
        $('#create_store').val('201');
        $('#create_deptsel').val('1');
        $('#create_subject').val('50');
        $('#create_sub').val('238');
        $.post('../users/fetch.php', { operation: 'search_tkt', iN: '1' }, function (data) {
            if (data && data[0]) {
                let next_tktno = data[0].ticket_no;
                let deptabr = data[0].dept;
                $('#create_ticket_no').val(deptabr + '' + next_tktno);
                $('#create_ticket_lbl').html(deptabr + '' + next_tktno);
            }
        }, 'json');
        $('#create_sub_group').show();
    });

    // Populate dynamic categories
    $("#create_deptsel").on("change", function () {
        $('#create_subject').val(null).trigger('change');
        $('#create_sub').val(null).trigger('change');
        $('#create_sub_group').hide();
        let val = $(this).val();

        $("#create_subject").select2({
            dropdownParent: $('#createReportModal'),
            width: '100%',
            minimumResultsForSearch: Infinity,
            ajax: {
                url: "../users/select.php",
                type: "get",
                dataType: 'json',
                delay: 250,
                data: function (params) { return { type: 'category_id', val: val }; },
                processResults: function (response) { return { results: response }; },
                cache: true
            }
        });

        $.post('../users/fetch.php', { operation: 'search_tkt', iN: val }, function (data) {
            if (data && data[0]) {
                let next_tktno = data[0].ticket_no;
                let deptabr = data[0].dept;
                $('#create_ticket_no').val(deptabr + '' + next_tktno);
                $('#create_ticket_lbl').html(deptabr + '' + next_tktno);
            }
        }, 'json');
    });

    $("#create_subject").on("change", function () {
        let category_id = $(this).val();
        if (!category_id) { $('#create_sub_group').hide(); return; }
        $.ajax({
            url: "get_subcat.php",
            type: "POST",
            data: { category_id: category_id },
            cache: false,
            success: function(dataResult) {
                $("#create_sub").html(dataResult);
                $('#create_sub_group').show();
            }
        });
    });

    // File validation
    $('#create_file-input').on('change', function () {
        for (var i = 0; i < this.files.length; ++i) {
            var file = this.files[i];
            if (file.size > 2097152) {
                Swal.fire({ icon: 'error', title: 'File Too Large', text: 'File "' + file.name + '" must not exceed 2MB.' });
                this.value = ""; return false;
            }
            var ext = file.name.split('.').pop().toLowerCase();
            var validExtensions = ['jpg', 'jpeg', 'gif', 'png', 'txt', 'pdf', 'docx', 'doc', 'xlsx', 'xls'];
            if ($.inArray(ext, validExtensions) === -1) {
                Swal.fire({ icon: 'error', title: 'Invalid File Type', text: 'File "' + file.name + '" has an invalid extension.' });
                this.value = ""; return false;
            }
        }
    });

    // AJAX Submission
    $('#create_report_form').on('submit', function (e) {
        e.preventDefault();
        var form = this;
        var formData = new FormData(form);

        $.ajax({
            url: "api_create_dept_report.php",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#create_action').prop('disabled', true);
                if (typeof $.LoadingOverlay !== "undefined") {
                    $.LoadingOverlay("show", { image: "", background: "rgba(0, 0, 0, 0.45)" });
                }
            },
            success: function (response) {
                if (typeof $.LoadingOverlay !== "undefined") { $.LoadingOverlay("hide"); }
                $('#create_action').prop('disabled', false);

                if (response.Response) {
                    var files = $('#create_file-input')[0].files;
                    if (files.length > 0) {
                        var fileData = new FormData();
                        for (var i = 0; i < files.length; i++) { fileData.append('files[]', files[i]); }
                        fileData.append('ticket_no', response.ticket_no);
                        $.ajax({ type: "POST", url: "insertimg.php", data: fileData, processData: false, contentType: false });
                    }
                    Swal.fire({ icon: 'success', title: 'Success!', text: 'Report successfully submitted.', timer: 2000, showConfirmButton: false })
                    .then(function () {
                        $('#createReportModal').modal('hide');
                        if (typeof getdata === 'function') getdata($("#yearpicker").val());
                        if (typeof get_card_data === 'function') get_card_data($("#yearpicker").val());
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Submission Failed', html: response.m });
                }
            },
            error: function (xhr, status, error) {
                if (typeof $.LoadingOverlay !== "undefined") { $.LoadingOverlay("hide"); }
                $('#create_action').prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Server Error: ' + error });
            }
        });
    });
});
</script>
<?php
include 'chrtdashboard.php';
?>
 

