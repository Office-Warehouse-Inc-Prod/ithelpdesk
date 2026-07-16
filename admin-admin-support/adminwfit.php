<?php
 $inactive = 180;

 if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > $inactive)){
  session_unset();
  session_destroy();
  header("Location: adminpanel.php");
  exit();
 }

 $_SESSION['start'] = time ();
  
include 'admin.php';
include '../condb.php';

$con1 = new dbconfig();

 
      
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mode']) && $_POST['mode'] === 'newrpt_tbl') {
  

    try {
        $sql = "SELECT 
                    r.ticket_no, 
                    r.date_created, 
                    r.concern, 
                    r.service_desc, 
                    r.subject,
                    GROUP_CONCAT(i.files_name SEPARATOR '|') AS attachment_files,
                    r.sub_id,
                    r.f_deptsel,
                    r.itsup,
                    r.store
                FROM reports r
                LEFT JOIN images i ON r.ticket_no = i.ticket_no
                WHERE r.status = 'Assigned' 
                GROUP BY r.ticket_no
                ORDER BY r.date_created DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['newrptdata' => $results]);
        
    } catch (Exception $e) {
        echo json_encode(['newrptdata' => [], 'error' => $e->getMessage()]);
    }
    
    exit; 
}
?>
<head>
    <link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/>
    <script src="../js/bootstrap-datetimepicker.min.js"></script>
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="styles.css" />

    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.select.min.js"></script>
    <script src="../js/dataTables.responsive.min.js"></script>
    <script src="../js/fnReloadAjax.js"></script>
</head>
<style>

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

  #new_rep_table {
    background-color: #ffffff;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 10px 8px rgba(108, 108, 53, 0.4);
    border: 1px solid #e9ecef;
  }

  #new_rep_table thead th {
    background-color: #54699e;
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    padding: 15px;
    border-bottom: 2px solid #dee2e6;
  }

  #new_rep_table tbody td {
    padding: 12px 15px;
    vertical-align: middle;
    color: #333;
    border-bottom: 1px solid #f1f1f1;
  }

  /* Hover Effect with requested color #213456 */
  #new_rep_table tbody tr:hover {
    background-color: #213456 !important;
    color: #ffffff !important;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  /* Responsive Table Wrapper */
  .table-responsive {
    border-radius: 8px;
    margin-top: 20px;
  }
  #newrpt_Modal .modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
  }

  #newrpt_Modal .modal-header {
    background-color: #213456;
    color: #fff;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    border-bottom: 4px solid #E1AD01; 
  }

  #newrpt_Modal .modal-title {
    font-weight: 700;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
  }

  #newrpt_Modal .input-group-text {
    background-color: #f8f9fa;
    border-right: none;
    color: #213456;
  }

  #newrpt_Modal .form-control {
    border-left: none;
    height: 45px;
    border-radius: 0 8px 8px 0;
  }

  #newrpt_Modal .form-control:focus {
    border-color: #ced4da;
    box-shadow: none;
  }

  #newrpt_Modal .input-group:focus-within {
    box-shadow: 0 0 0 0.2rem rgba(225, 173, 1, 0.25);
    border-radius: 8px;
  }

  #msgbtn {
    background-color: #E1AD01;
    border: none;
    color: #213456;
    font-weight: 700;
    padding: 10px 40px;
    border-radius: 30px;
    transition: all 0.3s ease;
  }

  #msgbtn:hover {
    background-color: #213456;
    color: #E1AD01;
    transform: translateY(-2px);
  }

  .dataTables_wrapper .pull-left {
    flex-direction: row;      
    align-items: center;      
    justify-content: flex-start; 
    width: 100%;              
    gap: 40px;                
    margin-bottom: 20px; 
  }

  .dataTables_filter {
    position: relative;
    display: inline-block;    
    margin: 0 !important;     
  }

  .dataTables_filter label {
    display: flex;
    align-items: center;
    margin-bottom: 0;          
  }

  /* Search Icon */
  .dataTables_filter::before {
    content: "\f002"; 
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #213456;
    z-index: 1;
    opacity: 0.6;
  }

  .dataTables_filter input {
    border: 2px solid #e0e0e0 !important;
    border-radius: 50px !important;
    padding: 8px 15px 8px 35px !important; 
    width: 300px !important;
    background-color: #ffffff !important;
    transition: all 0.3s ease;
    outline: none !important;
    color: #213456;
    margin-left: 0 !important; 
  }

  .dataTables_filter input:focus {
    border-color: #E1AD01 !important;
    box-shadow: 0 0 10px rgba(225, 173, 1, 0.2) !important;
  }

  :root {
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

  /* container spacing */
  .container.mt-3 { padding-top: 10px; padding-bottom: 24px; }

 #new_rep_table { width:100% !important; }

.table-wrap {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 14px;
}

/* If you can't add wrapper div, style DataTables container instead */
.dataTables_wrapper {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 14px;
}

/* DataTables header controls */
.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label,
.dataTables_wrapper .dataTables_info {
  color: var(--muted) !important;
  font-weight: 600;
}

.dataTables_wrapper .dataTables_filter input:focus,
.dataTables_wrapper .dataTables_length select:focus {
  box-shadow: var(--focus) !important;
  border-color: rgba(234,170,0,.45) !important;
}

/* Pagination */
.dataTables_wrapper .dataTables_paginate .paginate_button {
  border-radius: 12px !important;
  border: 1px solid transparent !important;
  color: var(--text) !important;
  background: transparent !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
  border-color: var(--line) !important;
  background: #F8FAFC !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
  background: rgba(234,170,0,.18) !important;
  border-color: rgba(234,170,0,.35) !important;
}

/* ===== Table modern look ===== */
table.dataTable {
  border-collapse: collapse !important; /* Changed to collapse to allow clean continuous borders */
  width: 100% !important;
}

table.dataTable thead th {
  color: white !important;
  font-weight: 900;
  letter-spacing: .04em;
  text-transform: uppercase;
  border: none !important;
  border-bottom: 2px solid #213456 !important; /* Solid line under the header */
  background: #5273ad !important;
  padding: 14px 12px !important;
}

/* Solid rows with continuous borders */
table.dataTable tbody tr {
  background: #ffffff !important;
  box-shadow: 0 10px 22px rgba(17,24,39,.08);
}

table.dataTable tbody td {
  border-top: none !important;
  border-bottom: 1px solid #213456 !important; /* Continuous horizontal line between table entries */
  color: rgba(17,24,39,.85) !important;
  padding: 14px 12px !important;
}

table.dataTable tbody tr:hover {
  transition: .15s ease;
  background: #F8FAFF !important;
}


  /* ===== Modal (clean light) ===== */
  .modal-content {
    border: 1px solid var(--line) !important;
    border-radius: var(--radius) !important;
    background: #ffffff !important;
    box-shadow: 0 22px 60px rgba(17,24,39,.18);
  }

  .modal-header {
    border-bottom: 3px solid var(--yellow) !important;
    padding: 16px 18px !important;
    background: #213456 !important;
    color: white;
  }

  .modal-title {
    font-size: 16px;
    font-weight: 900;
    letter-spacing: .02em;
    color: white;
    text-transform: uppercase;
  }

  .modal-body { padding: 18px !important; }
  .modal-footer {
    border-top: 1px solid var(--line) !important;
    padding: 14px 18px !important;
  }
label {
  font-size: 11px;
  font-weight: 900;
  color: #213456;
  letter-spacing: .08em;
  text-transform: uppercase;
  margin-bottom: 6px;
}

input.form-control,
textarea.form-control {
  color: #6c757d !important;
  background-color: transparent !important; 
  border: none !important; 
  border-bottom: 1px solid #213456 !important; 
  border-radius: 0px !important; 
  resize: none !important; 
}

select.custom-select-placeholder.placeholder-active,
textarea.form-control.custom-select-placeholder:placeholder-shown {
  color: red !important;
  border: 1px solid #ced4da !important;
  border-radius: .2rem !important;
  background-color: #fff !important;
}

textarea.form-control.custom-select-placeholder::placeholder {
  color: red !important;
  opacity: 0.7;
}

select.custom-select-placeholder.has-value,
textarea.form-control.custom-select-placeholder:not(:placeholder-shown) {
  color: #212529 !important; 
  border: none !important; 
  border-bottom: 1px solid #213456 !important; 
  border-radius: 0px !important;
  background-color: transparent !important;
}

  /* Inputs / Select / Textarea */
  .form-control,
  .form-control-sm,
  select.form-control,
  textarea.form-control {
    background: #fff !important;
    border: 1px solid var(--line) !important;
    color: var(--text) !important;
    border-radius: 14px !important;
    padding: 10px 12px !important;
  }

  .form-control:focus,
  .form-control-sm:focus,
  select.form-control:focus,
  textarea.form-control:focus {
    box-shadow: var(--focus) !important;
    border-color: rgba(234,170,0,.45) !important;
  }

  .form-control[readonly],
  textarea[readonly] { opacity: .95; }

  /* Spacing in grid */
  .form-group { margin-bottom: 14px !important; }

  /* ===== Buttons (OWI style) ===== */
  .btn-danger {
    background: rgba(239,68,68,.14) !important;
    border-color: rgba(239,68,68,.28) !important;
    color: #991b1b !important;
  }
  .btn-danger:hover { background: rgba(239,68,68,.18) !important; }

  /* Collapse thread card */
  #msg_thread .card.card-body {
    background: #213456 !important;
    border: 1px solid var(--line) !important;
    border-radius: var(--radius-sm) !important;
  }

  /* Thread container */
  .container_remarks {
    background: #F8FAFF;
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    padding: 12px;
    max-height: 280px;
    box-shadow: 0 20px 60px rgba(123, 128, 44, 0.605);
    overflow: auto;
  }

  #remarks_view ul { list-style: none; padding-left: 0; margin: 0; }

  #remarks_view li {
    padding: 10px 12px;
    border: 1px solid var(--line);
    background: #ffffff;
    border-radius: 14px;
    margin-bottom: 10px;
    box-shadow: 0 10px 18px rgba(17,24,39,.06);
  }

  hr { border-top: 1px solid var(--line) !important; }

  /* ===== Priority chips (same but readable on light bg) ===== */
  .priority-chip {
    padding:4px 10px;
    border-radius:999px;
    font-weight:900;
    font-size:11px;
    letter-spacing:.05em;
  }
  .p-critical { background: rgba(239,68,68,.14); color:#991b1b; border:1px solid rgba(239,68,68,.25); }
  .p-high {     background: rgba(251,146,60,.14); color:#9a3412; border:1px solid rgba(251,146,60,.25); }
  .p-medium {   background: rgba(234,170,0,.16); color:#7a5200; border:1px solid rgba(234,170,0,.30); }
  .p-low {      background: rgba(34,197,94,.14); color:#166534; border:1px solid rgba(34,197,94,.25); }

  /* ===== Select2 (light) ===== */
  .select2-container--default .select2-selection--single {
    background-color: #ffffff !important;
    border: 1px solid var(--line) !important;
    border-radius: 14px !important;
    height: 42px !important;
    display: flex !important;
    align-items: center !important;
    padding: 4px 10px !important;
    color: var(--text) !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: var(--text) !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 42px !important;
  }

  .select2-dropdown {
    background-color: #ffffff !important;
    color: var(--text) !important;
    border: 1px solid var(--line) !important;
    border-radius: 14px !important;
    box-shadow: 0 18px 40px rgba(17,24,39,.14);
  }
  .select2-results__option { color: var(--text) !important; }
  .select2-results__option--highlighted {
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
    background-color: #7a5200 !important;
    border: 2px solid #213456;
    font-weight: 700;
    color: white;
  }

  .btn-success:hover {
    background-color: #16243d !important;
    border-color: yellow;
    color: white;
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
 
<div class="container mt-4">
  <div class="table-responsive-xl">
    <table class="table table-hover" id="new_rep_table"></table>
  </div>
</div>

<script src="../js/coms.js"></script> 
<div class="modal fade" id="newrpt_Modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <form method="post" id="newrpt_form" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="tick_title"></h4>
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
            </div>

            <div class="form-group col-md-4">
              <label>SUBJECT</label>
              <input type="text" name="concern" id="concern" class="form-control form-control-sm" placeholder="Input Concern" style="text-transform:uppercase" readonly></input>
            </div>

            <div class="form-group col-md-4">
              <label>Service Requested:</label>
              <input type="text" class="form-control form-control-sm" name="tos" id="tos" readonly>
            </div>

            <div class="form-group col-md-12">
              <label>CONCERN</label>
              <textarea name="concern" id="message" class="form-control form-control-sm" placeholder="Input Concern" style="text-transform:uppercase" readonly></textarea>
            </div>

             <div class="form-group col-md-12">
              <label>Attachment:</label>
             
              <div id="attachments-container" class="d-flex flex-wrap gap-2 p-2 border rounded bg-light" style="min-height: 50px;">
                <span class="text-muted">No attachments for this ticket.</span>
              </div>
            </div>

             <hr style="border:2px solid #333; width: 100%; ">

            <div class="form-group col-md-4">
              <label>VIA</label>
               <select class="form-control form-control-sm custom-select-placeholder placeholder-active" name="via" id="via" required onchange="handleDropdownChange(this)">
                <option value=""style="color:red;"> &larr; VIA &rarr;</option>
                <?php
                  $query = "select * from via_main";
                  $run = $con1->prepare($query);
                  $run->execute();
                  $rs = $run->get_result();
                  while ($res = $rs->fetch_assoc()) {
                ?>
                <option value="<?=$res['via_desc']?>"style="color: #333;"><?=$res['via_desc']?></option>
                <?php } ?>
              </select>
            </div>

            <div class="form-group col-md-8">
              <label>ASSIGNED SUPPORT</label>
              <input type="hidden" name="it_num" id="it_num" readonly>
              <select class="form-control form-control-sm custom-select-placeholder placeholder-active" name="itsup" id="itsup"required onchange="handleDropdownChange(this)">
                <option value=""style="color:red;"> &larr;ASSIGN SUPPORT&larr;</option>  
                <?php
                  $query="select * from it_tech WHERE itsup NOT IN ('4','7','8','12','14') AND deptsel = '2'";
                  $run=$con1->prepare($query);
                  $run->execute();
                  $rs=$run->get_result();
                  while ($res=$rs->fetch_assoc()) {
                    $tchid = $res['itsup'];
                    $tchdesc = $res['it_desc'];
                ?>
                <option value="<?php echo $tchid;?>"style="color: #333;"><?= $tchdesc; ?></option>
                <?php } ?>    
              </select> 
            </div>
        
            <div class="form-group col-md-6">
              <label>CATEGORY</label>
              <input type="hidden" name="cat_num" id="cat_num" readonly>
              <select class="form-control form-control-sm custom-select-placeholder placeholder-active" name="cat" id="cat" required onchange="handleDropdownChange(this)">
                <option value=""style="color:red;"> &larr; CATEGORY &rarr;</option>  
                <?php
                  $query="select * from categories WHERE deptsel = '2' AND (old_tag IS NULL OR old_tag <> 'Y') ORDER BY order_id ASC";
                  $run=$con1->prepare($query);
                  $run->execute();
                  $rs=$run->get_result();
                  while ($res=$rs->fetch_assoc()) {
                    $supid = $res['cat_id'];
                    $suppdesc = $res['cat_desc'];
                ?>
                <option value="<?php echo $supid;?>" style="color: #333;"><?= $suppdesc; ?></option>
                <?php } ?>
              </select> 
            </div>

            <div class="form-group col-md-6">
              <label>SUB CATEGORY</label>
              <input type="hidden" name="sub_num" id="sub_num" readonly>
              <select class="form-control form-control-sm custom-select-placeholder placeholder-active" name="sub" id="sub" required onchange="handleDropdownChange(this)"></select>
            </div>

            <div class="form-group col-md-4 hide_isp">
              <label for="isp" id="lbl_isp">Service Provider</label>
              <input type="hidden" name="isp_num" id="isp_num" readonly>
              <select class="form-control form-control-sm" name="isp" id="isp">
                <option value="">Select Network Provider</option>  
                <?php
                  $query="select * from tbl_isp";
                  $run=$con1->prepare($query);
                  $run->execute();
                  $rs=$run->get_result();
                  while ($res=$rs->fetch_assoc()) {
                    $ispid = $res['isp_id'];
                    $ispdesc = $res['isp_shortDesc'];
                ?>
                <option value="<?php echo $ispid;?>"style="color: #333;"><?= $ispdesc; ?></option>
                <?php } ?>
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
                <div class="input-group-append" data-target="#" data-toggle="datetimepicker">
                  <div class="input-group-text" id="ico_cal3"><i class="fa fa-calendar"></i></div>
                </div>
              </div>
            </div>

            <div class="form-group col-md-4">
              <label>STATUS</label>
              <select class="form-control form-control-sm custom-select-placeholder placeholder-active" name="status" id="status" required onchange="handleDropdownChange(this)">
                <option value=""style="color:red;"> &larr; STATUS &rarr;</option>
                <?php
                  $query="select * from status WHERE adminsup_module_tag = 'Y' AND stat_id <> '29'";
                  $run=$con1->prepare($query);
                  $run->execute();
                  $rs=$run->get_result();
                  while ($res=$rs->fetch_assoc()) {
                ?>
                    <option value="<?=$res['stat_desc'] ?>" style="color: #333;"><?=$res['stat_desc'] ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group col-md-4">
              <label id="dateclabel" class="hidden">DATE CLOSED</label>
              <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                <input type="text" name="date_closed" id="date_closed" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker2" autocomplete="off" />
                <div class="input-group-append" data-target="#date_closed" autocomplete="off" data-toggle="datetimepicker">
                  <div class="input-group-text" id="ico_cal" name="ico_cal"><i class="fa fa-calendar"></i></div>
                </div>
              </div>
            </div>

            <div class="form-group col-md-4">
              <label id="clby_label" class="hidden">CLOSED BY</label>
              <input type="hidden" name="close_by" id="close_by" value="<?php echo $_SESSION['tech_id'];?>">
              <input type="text" class="form-control form-control-sm" name="cl_desc" id="cl_desc" readonly value="<?php echo $_SESSION['fname'].' '.$_SESSION['lstname'];?>">
            </div>

            <div class="form-group col-md-12">
              <label>Work Output: </label>
              <textarea name="remarks" id="remarks" class="form-control form-control-sm custom-select-placeholder placeholder-active" placeholder="Your Workoutput" style="text-transform:uppercase" required onchange="handleDropdownChange(this)"></textarea>
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
                    <textarea name="admsg" required class="form-control form-control-sm" placeholder="Reply to their message or give an updates regarding on this ticket..."></textarea>
                  </div>
                  <div class="col-md-12 mt-4 mb-2 dv_msg">
                    <label for="remarks_view" style="font-weight: bold; color:white;">Ticket Thread:</label>
                    <div class="container_remarks">
                      <div id="remarks_view"><ul></ul></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 mt-2">
                  <input type="submit" name="action" id="action" class="btn btn-success" value="Add"/>
                  <button type="button" name="btnClose" id="btnClose" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <input type="hidden" name="operation" id="operation" />
          <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id']; ?>">
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){

  function getUrlParam(param) {
    var urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
  }

  var targetTicket = getUrlParam('ticket_no');

  if (targetTicket) {
    setTimeout(function() {
      var foundRow = null;

      reptable.rows().every(function (rowIdx, tableLoop, rowLoop) {
        var rowData = this.data();
        if (rowData && rowData.ticket_no == targetTicket) {
          foundRow = this.node();
        }
      });

      if (foundRow) {
        $(foundRow).find('button[name="update"]').trigger('click');

        $('html, body').animate({
          scrollTop: $(foundRow).offset().top - 100
        }, 800, function() {
          // Highlight Animation sequence on the target row
          $(foundRow).css('transition', 'background-color 0.5s ease');
          $(foundRow).css('background-color', '#ffff99'); // Fixed structural string mistake here

          setTimeout(function() {
            $(foundRow).css('background-color', ''); 
          }, 1200);
        });
      }
    }, 600);
  }


  $("div.selected select").val("OPEN");

  var reptable;
  var user_id = <?= $_SESSION['user_id']; ?>; 

  function getdata(){
    $.post('fetchdata/fetch_data.php',{mode:'newrpt_tbl'},function(data){
      admin_datatable(data);
    },'json');
  }
  getdata();

  function admin_datatable(t){
    const dataset = t.newrptdata;
    reptable = $("#new_rep_table").DataTable({
      "dom": '<"pull-left"lf><"pull-right">tip',
      stateSave: true,
      "bDestroy": true,
      "responsive": true, 
      "lengthChange": false, 
      "autoWidth": false,
      language: {
        emptyTable: "No unassigned reports",
        search: "_INPUT_",
        searchPlaceholder: "Search..."
      },
      pageLength: 5,
      data: dataset,
      "order": [[ 0, "Desc" ]],
      columns: [
        {title:"TicketNo", data:"ticket_no","defaultContent": ""},
        {title:"Department/Store", data:"str_code","defaultContent": ""},
        {title:"Created By", data:"full_name","defaultContent": ""},
        {title:"Date Created", data:"date_created","defaultContent": ""},
        {title:"SUBJECT", data:"concern","defaultContent": ""},
        {title:"Types of Service", data:"service_desc","defaultContent": ""},
        {title:"CONCERN", data:"subject","defaultContent": ""},
        {title:"Update", data:null,"defaultContent": "<Button class='btn btn-danger' name='update'><i class='fas fa-edit'></i></Button>"}
      ],
      rowCallback: function(row, data, index){
        if(data['msg_cnt'] == '1'){
          $(row).find('td').css("font-weight", "bold");
        }
      }
    });

    setInterval(function () {
      getdata();
    }, 60000);
$('#new_rep_table tbody').off('click', 'button').on('click', 'button', function () {
      var data = reptable.row($(this).parents('tr')).data();
       if(!data) return;

      $('#ticket_no').val(data['ticket_no']);
      $('#store').val(data['store']);
      $('#str_desc').val(data['str_code']);
      $('#crtd_by').val(data['full_name']);
      $('#date_createdx').val(data['date_created']);
      $('#concern').val(data['concern']);
      $('#tos').val(data['service_desc']);
      $('#message').val(data['subject']);
      $('#sub_num').val(data['sub_id']);

      $('#newrpt_Modal').modal('show');
      $('#action').val("Update");
      $('#operation').val("Save and Reply"); 

      var tid = $(this).parent().siblings(':first').html();
      $('#tick_title').text("Ticker Number: "+tid);
      

        displayAttachmentsFromData(data);

      getinfo(tid, 'remarks', user_id);
    });
  }

  $('#ModalDate_close').datetimepicker();
  $('#date_refNo').datetimepicker();

  $('#cat').on('change', function() {
    var category_id = this.value;
    $.ajax({
      url: "get_subcat.php",
      type: "POST",
      data: { category_id: category_id },
      cache: false,
      success: function(dataResult){
        $("#sub").html(dataResult);
      }
    });
  });

  $(function () {
    $('#datetimepicker2, #datetimepicker3').datetimepicker();
  });

  if (typeof slct_isp === "function") slct_isp();
  if (typeof slct_sub === "function") slct_sub();
  if (typeof gtsub_id === "function") gtsub_id();
  if (typeof admin_hideshowforms === "function") admin_hideshowforms();

  $(document).on('submit', '#newrpt_form', function(event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var formData = new FormData(this);

    $.ajax({
      url: "insert.php",
      method: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'json',
      cache: false,
      success: function(response) {
        if (typeof response !== 'object') {
          try {
            response = JSON.parse(response);
          } catch (e) {
            response = { status: 'error', message: String(response) };
          }
        }

        if (response.status === 'success' || response.status === true) {
          Swal.fire({
            icon: 'success',
            title: response.message || 'Saved successfully',
            showConfirmButton: false,
            timer: 1500
          }).then(function() {
            $('#newrpt_form')[0].reset();
            $('#newrpt_Modal').modal('hide');
            getdata();
            location.reload();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Save failed',
            text: response.message || 'Please try again.'
          });
        }
      },
      error: function(xhr, status, error) {
        var message = 'Please try again.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          message = xhr.responseJSON.message;
        } else if (xhr.responseText) {
          message = xhr.responseText.trim();
        }
        Swal.fire({
          icon: 'error',
          title: 'Save failed',
          text: message
        });
      }
    });
  });
});

$(document).on('click', '#msgbtn', function(){
  $('.dv_msg').show();
  $('#remarks_view').show();

  if($('#msgbtn').val() == 'show'){
    $('#action').val("Save and Reply");
    $('#operation').val("Save and Reply");
    $('#msgbtn').val("hide");
    $('#msg_thread').show('slow');
  } else if($('#msgbtn').val() == 'hide'){
    $('#action').val("Save");
    $('#operation').val("Save and Reply");
    $('#msgbtn').val("show");
    $('#msg_thread').hide('slow');
  }
});


function displayAttachmentsFromData(data) {
    const container = document.getElementById('attachments-container');
    if (!container) {
        console.warn('⚠️ attachments-container element not found in modal');
        return;
    }
    
    container.innerHTML = '';

    const attachmentFiles = data.attachment_files;

    if (!attachmentFiles) {
        container.innerHTML = '<span class="text-muted">No attachments for this ticket.</span>';
        return;
    }

    const filePaths = attachmentFiles.split('|').filter(f => f.trim() !== '');

    if (filePaths.length === 0) {
        container.innerHTML = '<span class="text-muted">No attachments for this ticket.</span>';
        return;
    }

    filePaths.forEach(imagePath => {
        if (!imagePath.trim()) return;

        const imgElement = document.createElement('img');
        let fullPath = imagePath.trim();
        
        if (!fullPath.includes('users/image/')) {
            fullPath = 'users/image/' + fullPath;
        }
      
        imgElement.src = '../' + fullPath;
        imgElement.alt = "Ticket Attachment";
        
        imgElement.className = "img-thumbnail m-1";
        imgElement.style.maxHeight = "100px";
        imgElement.style.maxWidth = "100px";
        imgElement.style.objectFit = "cover";
        imgElement.style.cursor = "pointer";
        imgElement.style.transition = "transform 0.2s ease";
        imgElement.style.border = "2px solid #EAAA00";

        imgElement.onmouseover = () => imgElement.style.transform = "scale(1.08)";
        imgElement.onmouseout = () => imgElement.style.transform = "scale(1.0)";
        
        imgElement.onclick = () => window.open('../' + fullPath, '_blank');

        container.appendChild(imgElement);
    });
}

let inactivityTime = function(){
  let time;

  window.onload = resetTimer;
  document.onmousemove = resetTimer;
  document.onkeypress = resetTimer;
  document.onscroll = resetTimer;
  document.onclick = resetTimer;

  function logout(){
    window.location.href = 'adminpanel.php';
  }

  function resetTimer(){
    clearTimeout(time);
    time = setTimeout(logout, 180000)
  }
};

inactivityTime();


function handleDropdownChange(selectElement) {
  if (selectElement.value === "") {
    selectElement.classList.add("placeholder-active");
    selectElement.classList.remove("has-value");
  } else {
    selectElement.classList.remove("placeholder-active");
    selectElement.classList.add("has-value");
  }
}
</script>
