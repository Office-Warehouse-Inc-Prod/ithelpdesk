      <?php
      include 'admin.php';
      include '../condb.php';
      $con1 = new dbconfig();
      ?>

      <head>
      <link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/>
      <script src="../js/bootstrap-datetimepicker.min.js"></script>

      <link rel="stylesheet" href="../css/jquery.dataTables.min.css" />
      <!-- <link rel="stylesheet" href="styles.css" /> -->

      <script src="../js/jquery.dataTables.min.js"></script>
      <script src="../js/dataTables.select.min.js"></script>
      <script src="../js/dataTables.responsive.min.js"></script>
      <script src="../js/fnReloadAjax.js"></script>

        <!-- <style>


/* =========================
   Modern Helpdesk UI Skin
   Works with Bootstrap + DataTables
   ========================= */

:root{
  --bg0:#0b1220;
  --bg1:#0f172a;
  --card:#101a33cc;
  --card2:#0f1a33;
  --text:#e5e7eb;
  --muted:#9ca3af;
  --line:rgba(255,255,255,.08);
  --shadow: 0 20px 55px rgba(0,0,0,.45);
  --radius:18px;
  --radius-sm:14px;
  --focus: 0 0 0 .2rem rgba(59,130,246,.25);
}

html, body{
  height:100%;
}

body{
  background:
    radial-gradient(900px 600px at 15% 10%, rgba(56,189,248,.16), transparent 55%),
    radial-gradient(700px 500px at 85% 20%, rgba(168,85,247,.14), transparent 55%),
    radial-gradient(700px 500px at 50% 90%, rgba(34,197,94,.10), transparent 55%),
    linear-gradient(180deg, var(--bg0), var(--bg1));
  color: var(--text);
  font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
}

/* container spacing */
.container.mt-3{
  padding-top: 10px;
  padding-bottom: 24px;
}

/* ===== Card wrapper for table ===== */
#new_rep_table{
  width:100% !important;
}

.table-wrap{
  background: rgba(16, 26, 51, .55);
  border: 1px solid var(--line);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 14px;
  backdrop-filter: blur(10px);
}

/* If you can't add wrapper div, style DataTables container instead */
.dataTables_wrapper{
  background: rgba(16, 26, 51, .55);
  border: 1px solid var(--line);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 14px;
  backdrop-filter: blur(10px);
}

/* DataTables header controls */
.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label,
.dataTables_wrapper .dataTables_info{
  color: var(--muted) !important;
  font-weight: 500;
}

.dataTables_wrapper .dataTables_filter input,
.dataTables_wrapper .dataTables_length select{
  background: rgba(255,255,255,.06) !important;
  border: 1px solid var(--line) !important;
  border-radius: 12px !important;
  color: var(--text) !important;
  padding: 8px 10px !important;
  outline: none !important;
}

.dataTables_wrapper .dataTables_filter input:focus,
.dataTables_wrapper .dataTables_length select:focus{
  box-shadow: var(--focus) !important;
  border-color: rgba(59,130,246,.55) !important;
}

/* Pagination */
.dataTables_wrapper .dataTables_paginate .paginate_button{
  border-radius: 12px !important;
  border: 1px solid transparent !important;
  color: var(--text) !important;
  background: transparent !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover{
  border-color: var(--line) !important;
  background: rgba(255,255,255,.06) !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current{
  background: rgba(59,130,246,.20) !important;
  border-color: rgba(59,130,246,.35) !important;
}

/* ===== Table modern look ===== */
table.dataTable{
  border-collapse: separate !important;
  border-spacing: 0 10px !important; /* row gaps */
}

table.dataTable thead th{
  color: rgba(229,231,235,.9) !important;
  font-weight: 700;
  letter-spacing: .02em;
  border: none !important;
  background: transparent !important;
  padding: 14px 12px !important;
}

table.dataTable tbody tr{
  background: rgba(15, 26, 51, .70) !important;
  border: 1px solid var(--line);
  box-shadow: 0 8px 18px rgba(0,0,0,.25);
  border-radius: 14px;
  overflow: hidden;
}

table.dataTable tbody td{
  border-top: 1px solid transparent !important;
  border-bottom: 1px solid transparent !important;
  color: rgba(229,231,235,.92) !important;
  padding: 14px 12px !important;
}

table.dataTable tbody tr:hover{
  transform: translateY(-1px);
  transition: .15s ease;
  background: rgba(17, 32, 62, .78) !important;
}

/* Fix the rounded row corners */
table.dataTable tbody tr td:first-child{
  border-top-left-radius: 14px;
  border-bottom-left-radius: 14px;
}
table.dataTable tbody tr td:last-child{
  border-top-right-radius: 14px;
  border-bottom-right-radius: 14px;
}

/* ===== Modal modern glass ===== */
.modal-content{
  border: 1px solid var(--line) !important;
  border-radius: var(--radius) !important;
  background: rgba(12, 18, 35, .88) !important;
  box-shadow: var(--shadow);
  backdrop-filter: blur(12px);
}

.modal-header{
  border-bottom: 1px solid var(--line) !important;
  padding: 16px 18px !important;
}

.modal-title{
  font-size: 18px;
  font-weight: 800;
  letter-spacing: .02em;
  color: var(--text);
}

.modal-body{
  padding: 18px !important;
}

.modal-footer{
  border-top: 1px solid var(--line) !important;
  padding: 14px 18px !important;
}

label{
  font-size: 12px;
  font-weight: 700;
  color: rgba(229,231,235,.78);
  letter-spacing: .04em;
  text-transform: uppercase;
  margin-bottom: 6px;
}

/* Inputs / Select / Textarea */
.form-control,
.form-control-sm,
select.form-control,
textarea.form-control{
  background: rgba(255,255,255,.06) !important;
  border: 1px solid var(--line) !important;
  color: var(--text) !important;
  border-radius: 14px !important;
  padding: 10px 12px !important;
}

.form-control:focus,
.form-control-sm:focus,
select.form-control:focus,
textarea.form-control:focus{
  box-shadow: var(--focus) !important;
  border-color: rgba(59,130,246,.55) !important;
}

.form-control[readonly],
textarea[readonly]{
  opacity: .95;
}

/* Spacing in grid */
.form-group{
  margin-bottom: 14px !important;
}

/* ===== Buttons ===== */
.btn{
  border-radius: 14px !important;
  padding: 10px 14px !important;
  font-weight: 700 !important;
  letter-spacing: .02em;
  border: 1px solid transparent !important;
}

.btn-primary{
  background: rgba(59,130,246,.22) !important;
  border-color: rgba(59,130,246,.35) !important;
}
.btn-primary:hover{
  background: rgba(59,130,246,.32) !important;
}

.btn-success{
  background: rgba(34,197,94,.22) !important;
  border-color: rgba(34,197,94,.35) !important;
}
.btn-success:hover{
  background: rgba(34,197,94,.32) !important;
}

.btn-danger{
  background: rgba(239,68,68,.22) !important;
  border-color: rgba(239,68,68,.35) !important;
}
.btn-danger:hover{
  background: rgba(239,68,68,.32) !important;
}

/* Collapse thread card */
#msg_thread .card.card-body{
  background: rgba(255,255,255,.04) !important;
  border: 1px solid var(--line) !important;
  border-radius: var(--radius-sm) !important;
}

/* Thread container */
.container_remarks{
  background: rgba(255,255,255,.04);
  border: 1px solid var(--line);
  border-radius: var(--radius-sm);
  padding: 12px;
  max-height: 280px;
  overflow: auto;
}

#remarks_view ul{
  list-style: none;
  padding-left: 0;
  margin: 0;
}

#remarks_view li{
  padding: 10px 12px;
  border: 1px solid var(--line);
  background: rgba(15, 26, 51, .65);
  border-radius: 14px;
  margin-bottom: 10px;
}

hr{
  border-top: 1px solid var(--line) !important;
}


.priority-chip{
  padding:4px 10px;
  border-radius:999px;
  font-weight:700;
  font-size:11px;
  letter-spacing:.05em;
}

.p-critical{
  background: rgba(239,68,68,.18);
  color:#f87171;
  border:1px solid rgba(239,68,68,.35);
}

.p-high{
  background: rgba(251,146,60,.18);
  color:#fb923c;
  border:1px solid rgba(251,146,60,.35);
}

.p-medium{
  background: rgba(250,204,21,.18);
  color:#facc15;
  border:1px solid rgba(250,204,21,.35);
}

.p-low{
  background: rgba(34,197,94,.18);
  color:#4ade80;
  border:1px solid rgba(34,197,94,.35);
}



.select2-container--default .select2-selection--single {
    background-color: #1e293b;
    border: 1px solid #334155;
    color: #fff;
}

.select2-dropdown {
    background-color: #1e293b;
    color: #fff;
}

.select2-results__option {
    color: #fff;
}




          </style> -->
<style>

/* =========================
   OWI Helpdesk UI Skin (LIGHT)
   Navy #121C31 + Yellow #EAAA00
   Works with Bootstrap + DataTables + Select2
   ========================= */

:root{
  --navy:#121C31;
  --navy2:#1a2a4a;
  --yellow:#EAAA00;

  --bg:#EEF2F7;
  --card:#ffffff;
  --card2:#F8FAFF;
  --text:#111827;
  --muted:#6B7280;
  --line:#E5E7EB;

  --shadow: 0 14px 34px rgba(17,24,39,.10);
  --radius:18px;
  --radius-sm:14px;
  --focus: 0 0 0 .2rem rgba(234,170,0,.18);
}

html, body{ height:100%; }

body{
  background:
    linear-gradient(135deg, rgba(18,28,49,.16) 0%, rgba(18,28,49,.16) 12%, transparent 12%),
    linear-gradient(315deg, rgba(18,28,49,.14) 0%, rgba(18,28,49,.14) 12%, transparent 12%),
    var(--bg);
  color: var(--text);
  font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
}

/* container spacing */
.container.mt-3{ padding-top: 10px; padding-bottom: 24px; }



/* ===== Top navbar (if applicable) =====  */
.navbar, header, .topbar, .navbar-default{
  background: var(--navy) !important;
  border-color: rgba(255,255,255,.10) !important;
}
.navbar a, .navbar-brand, .navbar-nav > li > a,
.navbar i, .navbar .fa, .navbar .fas{
  color: #fff !important;
}
.navbar-nav > li.active > a,
.navbar-nav > li > a:hover{
  color: var(--yellow) !important;
}
.navbar-nav > li.active > a{
  border-bottom: 3px solid var(--yellow);
}
.table-wrap{
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 14px;
}

/* If you can't add wrapper div, style DataTables container instead */
.dataTables_wrapper{
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 14px;
}

/* DataTables header controls */
.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label,
.dataTables_wrapper .dataTables_info{
  color: var(--muted) !important;
  font-weight: 600;
}

/* Search + length */
.dataTables_wrapper .dataTables_filter input,
.dataTables_wrapper .dataTables_length select{
  background: #fff !important;
  border: 1px solid var(--line) !important;
  border-radius: 12px !important;
  color: var(--text) !important;
  padding: 8px 10px !important;
  outline: none !important;
}

.dataTables_wrapper .dataTables_filter input:focus,
.dataTables_wrapper .dataTables_length select:focus{
  box-shadow: var(--focus) !important;
  border-color: rgba(234,170,0,.45) !important;
}

/* Pagination */
.dataTables_wrapper .dataTables_paginate .paginate_button{
  border-radius: 12px !important;
  border: 1px solid transparent !important;
  color: var(--text) !important;
  background: transparent !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover{
  border-color: var(--line) !important;
  background: #F8FAFC !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current{
  background: rgba(234,170,0,.18) !important;
  border-color: rgba(234,170,0,.35) !important;
}

/* ===== Table modern look ===== */
table.dataTable{
  border-collapse: separate !important;
  border-spacing: 0 10px !important; /* row gaps */
}

table.dataTable thead th{
  color: white !important;
  font-weight: 900;
  letter-spacing: .04em;
  text-transform: uppercase;
  border: none !important;
  background: #4667a0!important;
  padding: 14px 12px !important;
}

/* “Floating rows” on light mode */
table.dataTable tbody tr{
  background: #ffffff !important;
  border: 1px solid var(--line) !important;
  box-shadow: 0 10px 22px rgba(17,24,39,.08);
  border-radius: 14px;
  overflow: hidden;
}

table.dataTable tbody td{
  border-top: 1px solid transparent !important;
  border-bottom: 1px solid transparent !important;
  color: rgba(17,24,39,.85) !important;
  padding: 14px 12px !important;
}

table.dataTable tbody tr:hover{
  transform: translateY(-1px);
  transition: .15s ease;
  background: #F8FAFF !important;
}

/* Fix the rounded row corners */
table.dataTable tbody tr td:first-child{
  border-top-left-radius: 14px;
  border-bottom-left-radius: 14px;
}
table.dataTable tbody tr td:last-child{
  border-top-right-radius: 14px;
  border-bottom-right-radius: 14px;
}

/* ===== Modal (clean light) ===== */
.modal-content{
  border: 1px solid var(--line) !important;
  background: #ffffff !important;
  box-shadow: 0 22px 60px rgba(17,24,39,.18);
}

.modal-header{
  border-bottom: 3px solid var(--yellow) !important;
  padding: 16px 18px !important;
  background: #213456 !important;
}

.modal-title{
  font-size: 16px;
  font-weight: 900;
  letter-spacing: .02em;
  color: white;
  text-transform: uppercase;
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
  color: rgba(17,24,39,.65);
  letter-spacing: .08em;
  text-transform: uppercase;
  margin-bottom: 6px;
}

/* Inputs / Select / Textarea */
.form-control,
.form-control-sm,
select.form-control,
textarea.form-control{
  background: #fff !important;
  border: 1px solid var(--line) !important;
  color: var(--text) !important;
  border-radius: 14px !important;
  padding: 10px 12px !important;
}

.form-control:focus,
.form-control-sm:focus,
select.form-control:focus,
textarea.form-control:focus{
  box-shadow: var(--focus) !important;
  border-color: rgba(234,170,0,.45) !important;
}

.form-control[readonly],
textarea[readonly]{ opacity: .95; }

/* Spacing in grid */
.form-group{ margin-bottom: 14px !important; }

/* ===== Buttons (OWI style) ===== */
.btn{
  border-radius: 14px !important;
  padding: 10px 14px !important;
  font-weight: 900 !important;
  letter-spacing: .02em;
  border: 1px solid transparent !important;
}

.btn-primary{
  background: white !important;
  border-color: var(--navy) !important;
  color: #213456 !important;
}
.btn-primary:hover{ background: #213456; color:white;}

.btn-success{
  background: rgba(22,163,74,.14) !important;
  border-color: rgba(22,163,74,.28) !important;
  color: #166534 !important;
}
.btn-success:hover{ background: rgba(22,163,74,.18) !important; }

.btn-danger{
  background: rgba(239,68,68,.14) !important;
  border-color: rgba(239,68,68,.28) !important;
  color: #991b1b !important;
}
.btn-danger:hover{ background: rgba(239,68,68,.18) !important; }

/* Collapse thread card */
#msg_thread .card.card-body{
  background: #213456 !important;
  border: 1px solid var(--line) !important;
  border-radius: var(--radius-sm) !important;
}

/* Thread container */
.container_remarks{
  background: #F8FAFF;
  border: 1px solid var(--line);
  border-radius: var(--radius-sm);
  padding: 12px;
  max-height: 280px;
    box-shadow: 0 20px 60px rgba(123, 128, 44, 0.605);
  overflow: auto;
}

#remarks_view ul{ list-style: none; padding-left: 0; margin: 0; }

#remarks_view li{
  padding: 10px 12px;
  border: 1px solid var(--line);
  background: #ffffff;
  border-radius: 14px;
  margin-bottom: 10px;
  box-shadow: 0 10px 18px rgba(17,24,39,.06);
}


hr{ border-top: 1px solid var(--line) !important; }

/* ===== Priority chips (same but readable on light bg) ===== */
.priority-chip{
  padding:4px 10px;
  border-radius:999px;
  font-weight:900;
  font-size:11px;
  letter-spacing:.05em;
}
.p-critical{ background: rgba(239,68,68,.14); color:#991b1b; border:1px solid rgba(239,68,68,.25); }
.p-high{     background: rgba(251,146,60,.14); color:#9a3412; border:1px solid rgba(251,146,60,.25); }
.p-medium{   background: rgba(234,170,0,.16); color:#7a5200; border:1px solid rgba(234,170,0,.30); }
.p-low{      background: rgba(34,197,94,.14); color:#166534; border:1px solid rgba(34,197,94,.25); }

/* ===== Select2 (light) ===== */
.select2-container--default .select2-selection--single{
  background-color: #ffffff !important;
  border: 1px solid var(--line) !important;
  border-radius: 14px !important;
  height: 42px !important;
  display: flex !important;
  align-items: center !important;
  padding: 4px 10px !important;
  color: var(--text) !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered{
  color: var(--text) !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow{
  height: 42px !important;
}

.select2-dropdown{
  background-color: #ffffff !important;
  color: var(--text) !important;
  border: 1px solid var(--line) !important;
  border-radius: 14px !important;
  box-shadow: 0 18px 40px rgba(17,24,39,.14);
}
.select2-results__option{ color: var(--text) !important; }
.select2-results__option--highlighted{
  background: rgba(234,170,0,.16) !important;
  color: var(--text) !important;
}
/* --- Buttons --- */
.btn {  
    background-color: white !important;
    border: 2px solid #213456;
      border-color: var(--gold-accent);
    font-weight: 700;
    color: #213456;
}

.btn:hover {
    background-color: #16243d !important;
    border-color: var(--gold-accent);
    color: white;
}

/* --- Buttons --- */
.btn-success {  
    background-color: white !important;
    border: 2px solid #213456;
    font-weight: 700;
    color: #213456;
}

.btn-success:hover {
    background-color: #16243d !important;
    border-color: var(--gold-accent);
    color:white;
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
</style>
      </head>

      <div class="container mt-3">
        <button onclick="location.reload();" class="btn btn-primary btn-sm">
    <i class="fas fa-sync-alt"></i> Reload
</button>
      <table class="table table-responsive table-condensed" id="new_rep_table"></table>
      </div>

      <!-- Start of Add/Edit Modal -->
      <script src="../js/coms.js"></script> 

      <div class="modal fade" id="newrpt_Modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
      data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg">
      <form method="post" id="newrpt_form" enctype="multipart/form-data">
      <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title" id="tick_title" value=""></h4>
      </div>

      <div class="modal-body">
      <div class="row">

      <div class="form-group col-md-4">
        
      <label>STORE</label>
      
      <input type="hidden" name="store" id="store" readonly value="">
      <input type="text" class="form-control form-control-sm" name="str_desc" id="str_desc" readonly value="">
      </div>

      <div class="form-group col-md-4">
      <label>Created By:</label>
      <input type="text" class="form-control form-control-sm" name="crtd_by" id="crtd_by" readonly>
      </div>

      <input type="hidden" class="form-control form-control-sm" name="ticket_no" id="ticket_no">

      <div class="form-group col-md-4">
      <label>DATE CREATED</label>
      <input type="text" class="form-control form-control-sm" name="date_createdx" id="date_createdx" readonly value="">
      <div></div>
      </div>

      <!-- ✅ FIX 1: SUBJECT must NOT use name="concern" (it was duplicated) -->
      <div class="form-group col-md-12">
      <label>SUBJECT</label>
      <textarea name="subject" id="concern" class="form-control form-control-sm"
      style="text-transform:uppercase" readonly></textarea>
      </div>

      <div class="form-group col-md-4">
      <label>Service Requested:</label>
      <input type="text" class="form-control form-control-sm" name="tos" id="tos" readonly>
      </div>

      <!-- ✅ FIX 2: CONCERN uses name="concern" (kept correct) -->
      <div class="form-group col-md-12">
      <label>CONCERN</label>
      <textarea name="concern" id="message" class="form-control form-control-sm"
      style="text-transform:uppercase" readonly></textarea>
      </div>

      <!-- <div class="form-group col-md-12"> -->



      <!-- </div> -->

      <!-- ✅ FIX 3: Assigned Support -> Assigned Department (itsup -> f_deptsel) -->
      <div class="form-group col-md-8">
      <label>ASSIGNED DEPARTMENT</label>

      <!-- old value for reassignment history -->
      <input type="hidden" name="old_dept" id="old_dept" readonly value="0">

      <!-- new field name expected by updated insert.php -->
      <select class="form-control form-control-sm" name="f_deptsel" id="f_deptsel" required>
      <option value="">Assign department...</option>
      <?php
<<<<<<< HEAD
      $query="SELECT * FROM tbl_dept WHERE dept_id <> '8' ";
=======
      $query="SELECT * FROM tbl_dept WHERE dept_id NOT IN ('7','8') ";
>>>>>>> 8bb0b92 (latest build with transfer feature on the works)
      $run=$con1->prepare($query);
      $run->execute();
      $rs=$run->get_result();
      while ($res=$rs->fetch_assoc()) {
      $dept_id = $res['dept_id'];
      $dept_desc = $res['dept_desc'];
      ?>
      <option value="<?php echo $dept_id; ?>"><?php echo $dept_desc; ?></option>
      <?php } ?>
      </select>
      </div>


      <input type="hidden" name="setStatus" id="setStatus" value="Assigned" required>

      <!-- <select id="setStatus" name="setStatus" class="form-control form-control-sm" required>
    <option value="">Select Status</option>
    <option value="Assigned">Assigned</option>
    <option value="Closed">Closed</option>
</select> -->

      <input type="hidden" name="contactNumber" id="contactNumber">
      <input type="hidden" name="dept_email" id="dept_email">







      <div class="form-group col-md-4">
      <label>PRIORITY LEVEL</label>
      <select class="form-control form-control-xl" name="priority_level" id="priority_level" required>
      <option value=""> &larr; PRIORITY &rarr;</option>
      <option value="4">LOW</option>
      <option value="3">NORMAL</option>
      <option value="2">HIGH</option>
      <option value="1">CRITICAL</option>
      </select>
      </div>


      <div class="form-group col-xl 4">
    <label for="sla_days">Service Level Agreement (SLA)</label>
    <select name="sla_days" id="sla_days" class="form-control" required>
        <option value="">Select SLA</option>
        <option value="2">24 – 48 hours</option>
        <option value="5">3 – 5 days</option>
        <option value="7">5 – 7 days</option>
        <option value="14">1 – 2 weeks</option>
        <option value="21">2 – 3 weeks</option>
        <option value="28">3 – 4 weeks</option>
    </select>
</div>



      <!-- <div class="form-group col-md-4"> -->
      <label id="clby_label" class="hidden">CLOSED BY</label>
      <input type="hidden" name="close_by" id="close_by" value="<?php echo $_SESSION['tech_id'];?>">
      <input type="hidden" class="form-control form-control-sm" name="cl_desc" id="cl_desc" readonly
      value="<?php echo $_SESSION['fname'].'  '.$_SESSION['lstname'];?>">
      <!-- </div> -->

      <div class="form-group col-md-12">
      <label>Work Output:</label>
      <textarea name="remarks" id="remarks" class="form-control form-control-sm"
      placeholder="Your Workoutput" style="text-transform:uppercase"></textarea>
      </div>

      <hr/>

      <div class="form-group col-md-12">
      <p>
      <button class="btn btn-primary float-right mr-2" type="button" name="msgbtn" id="msgbtn" value="show">
      Show Message Thread
      </button>
      </p>
      </div>

      <div class="col-md-12 collapse" id="msg_thread">
      <div class="card card-body">
      <div class="row">
      <div class="col-md-12 dv_msg">
      <label style="font-weight: bold; color:white;">Add Message:</label>

      <!-- keep same POST key admsg -->
      <textarea name="admsg" id="admsg" class="form-control form-control-sm"
        placeholder="Reply to their message or give updates regarding this ticket..."></textarea>
      </div>

      <div class="col-md-12 mt-4 mb-2 dv_msg">
      <label for="remarks_view" style="font-weight: bold;color:white;">Ticket Thread:</label>
      <div class="container_remarks">
      <div id="remarks_view"><ul></ul></div>
      </div>
      </div>
      </div>

      <div class="col-md-12">
      <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
      <button type="button" name="btnClose" id="btnClose" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
      </div>
      </div>
      </div>

      </div>
      </div>

      <div class="modal-footer">
      <input type="hidden" name="operation" id="operation" />
      <input type="hidden" name="u_id" id="u_id" value="<?php echo $_SESSION['user_id']; ?>">
      </div>

      </div>
      </form>
      </div>
      </div>

<?php include 'adminwfit_obj.php'; ?>
