<?php
// ======== Database & Includes =========
include 'header.php';
include '../condb.php';
include 'am.php';
include 'chrtdashboard.php';

$conn = new dbconfig();

// ======== Timezone Settings =========
$datetime = new DateTime();
$timezone = new DateTimeZone('Asia/Manila');
$datetime->setTimezone($timezone);
?>

<!-- Dependencies -->
<link rel="stylesheet" href="../plugins/DataTables-1.10.25/media/css/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="../assets/Date-Time-Picker-Bootstrap-4/src/sass/bootstrap-datetimepicker-build.css" />
<link rel="stylesheet" href="styles.css" />
<link rel="stylesheet" href="dashboard.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script src="../plugins/DataTables-1.10.25/media/js/jquery.dataTables.min.js"></script>
<script src="../assets/Date-Time-Picker-Bootstrap-4/src/js/bootstrap-datetimepicker.js"></script>
<script src="../js/ellipsis.js"></script>

<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>

<style>
    /* Scrollbar */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.1); border-radius: 10px; }
    ::-webkit-scrollbar-thumb { background: linear-gradient(135deg, #837031, #E1AD01); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: linear-gradient(135deg, #837031, #E1AD01); }

    /* Global Body */
    body {
        background: linear-gradient(to bottom, #ffffff, #99aac8);
        background-attachment: fixed; 
        margin: 0; 
        overflow-x: hidden;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
    }

    /* Tables */
    .table-responsive {
        overflow: visible !important;
        width: 100% !important;
        margin-top: -600px;
    }
    .admin-table {
        width: 100% !important;
        table-layout: auto !important;
        page-break-inside: avoid;
    }
    .admin-table th {
        background-color: #213456 !important;
        color: #ffffff !important;
        padding: 6px 4px !important;
        font-size: 11px !important;
    }
    #admin_report.admin-table th.active.text-center {
        background-color: #2b9827 !important;
        color: #ffffff !important;
        padding: 6px 4px !important;
        font-size: 11px !important;
    }
    #admin_report.admin-table th.compliance.text-center {
        background-color: #a29341 !important;
        color: #ffffff !important;
        padding: 6px 4px !important;
        font-size: 11px !important;
    }
    .admin-table td {
        padding: 6px 4px !important;
        font-size: 11px !important;
        border-bottom: 1px solid #0e0e0ea1 !important;
    }
    #dept-table-footer {
        border: 2px solid #2d3c59;
        background-color: #f4e9d7 !important; 
    }

    /* Print & Progress */
    .progress {
        border: 1px solid #999 !important;
        background-color: #ddd !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
        box-shadow: none !important;
    }

    /* Forms & Inputs */
    label {
        font-size: 11px;
        font-weight: 900;
        color: #e1ad01; 
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 6px;
    }
    .form-control, .form-control-sm, input.form-control, select.form-control, textarea.form-control {
        background: #fff !important;
        color: black !important;
        border: none !important;
        border-bottom: 1px solid #E1AD01 !important; 
        resize: none !important;
    }
    .form-control:focus, input.form-control:focus, select.form-control:focus, textarea.form-control:focus {
        box-shadow: 0 10px 18px rgba(17,24,39,.06);
        border-color: 2px solid rgba(114, 89, 21, 0.94) !important;
    }

    /* Remarks / Chat Section */
    .container_remarks {
        display: flex !important;
        flex-direction: column;
        max-height: 480px;
        overflow-y: auto;
        background-color: #f0f2f5 !important;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 15px;
        margin-top: 10px;
    }
    .dv_msg { display: block !important; }
    #remarks_view { display: flex; flex-direction: column; width: 100%; }
    
    #msg_thread {
        padding: 1rem 1.5rem;
        background: linear-gradient(to bottom, #ffffff, #99aac8);
        height: 100%;
    }
    #addmsg {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 1rem;
        background: #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    /* Chat Bubbles */
    .chat-bubble {
        max-width: 85%;
        padding: 10px 14px;
        border-radius: 18px;
        font-size: 0.9rem;
        line-height: 1.4;
        position: relative;
        margin-bottom: 12px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        word-wrap: break-word;
    }
    .chat-left {
        align-self: flex-start;
        background: #ffffff;
        color: #1e293b;
        border-bottom-left-radius: 4px;
        border: 1px solid #e5e7eb;
    }
    .chat-right {
        align-self: flex-end;
        background: #1C0770;
        color: #ffffff;
        border-bottom-right-radius: 4px;
    }
    .msg-meta {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        font-size: 0.7rem;
        margin-bottom: 4px;
    }
    .chat-left .msg-meta { color: #64748b; }
    .chat-right .msg-meta { color: rgba(255, 255, 255, 0.85); }
    .chat-left .msg-meta-name { color: #213456; font-weight: bold; }
    .chat-right .msg-meta-name { color: #ffffff; font-weight: bold; }

    /* Modals */
    #userModal .modal-dialog {
        max-width: 1100px; 
        margin: 1.25rem auto;
    }
    #userModal .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }
    #userModal .modal-header {
        background-color: #213456;
        color: #fff;
        border-bottom: 4px solid #E1AD01; 
    }
    #userModal_header {
        font-weight: 700;
        font-size: 18px;
        margin: 0;
    }
    #userModal .modal-body { padding: 16px 18px; }
    #userModal .modal-footer {
        border-top: 1px solid rgba(0,0,0,0.08);
        background: rgba(255,255,255,0.92);
        position: sticky;
        bottom: 0;
        z-index: 5;
        padding: 12px 14px;
    }

    /* Modal Overlay & Custom Overrides */
    .modal-overlay {
        display: none; 
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    .modal-overlay .modal-content {
        background: linear-gradient(to bottom, #ffffff, #b0b9c8);
        padding: 25px;
        border-radius: 8px;
        width: 70%;
        max-width: 90%;
        margin-top: 30px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .modal-overlay .close-btn {
        display: block;
        margin-left: auto;
        border-radius: 12px;
        padding: 10px;
        width: 30%;
        color: white;
        background-color: #213456;
        margin-top: 15px;
    }
    .modal-overlay .close-btn:hover { background-color: #E1AD01; color: white; }

    /* Buttons */
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

    /* Misc Utilities */
    .m_col { background: #ffffff; padding: 2rem !important; border-right: 1px solid #edf2f7; }
    .year-picker-group { flex: 1; min-width: 300px; }
    
    @media (max-width: 991px) {
        #userModal .modal-dialog { max-width: 96%; margin: .75rem auto; }
        .container_remarks { max-height: 350px; }
        #action, #btnClose { width: 100%; }
    }
</style>

<div class="container-fluid mt-4 ticket_container">
    <div id="wrapper">
        <div id="layoutSidenav_content">
            <!-- Header/Filter Section -->
            <div class="row">
                <div class="col-4 col-md-4">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">SELECT YEAR:</span>
                        </div>
                        <select class="form-control" name="yearpicker" id="yearpicker" required>
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

                <div class="col-2 col-md-2">
                    <button class="btn btn-info btn-xs" id="flterbutton" style="display: inline-block;">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <!-- Dashboard Metric Cards -->
            <div class="container-fluid mt-4">
                <div class="row g-4 mb-4">
                    <!-- Total Reports -->
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="card h-100 dashcard-clickable" data-filter="" style="margin-top:15px; border-radius: 15px; cursor:pointer;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="bg-opacity-10 p-3 rounded-circle" style="color: #576A8F;">
                                        <i class="fas fa-file-alt fa-2x" style="font-size: 3rem;"></i>
                                    </div>
                                    <h2 class="fw-black mb-1" id="count_total" style="font-size:2.2rem; letter-spacing: -1px;">0</h2>
                                </div>
                                <div>
                                    <p class="fw-bold text-uppercase mb-0" style="font-size: 0.75rem; color: #576A8F; letter-spacing: 1px;">Total Reports</p>
                                    <hr class="mt-2 mb-3" style="border-top: 2px solid #576A8F; opacity: 1; width: 100%;" />
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a class="second small text-info stretched-link" id="card_totalval" href="#bottom">Click here for more info.</a>
                                        <i class="fas fa-chevron-right small text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- On Process -->
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="card h-100 dashcard-clickable" data-filter="ON PROCESS" style="border-radius: 15px; margin-top:15px; cursor: pointer;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="bg-opacity-10 p-3 rounded-circle" style="color: #E5BA41;">
                                        <i class="fas fa-spinner fa-2x" style="font-size: 3rem;"></i>
                                    </div>
                                    <h2 class="fw-black mb-1" id="count_open" style="font-size: 2.2rem; letter-spacing: -1px;">0</h2>
                                </div>
                                <div>
                                    <p class="text-warning fw-bold text-uppercase mb-0" style="font-size: 0.75rem; color: #E5BA41; letter-spacing: 1px;">On Process</p>
                                    <hr class="mt-2 mb-3" style="border-top: 2px solid #E5BA41; opacity:1; width:100%;" />
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a class="small text-warning stretched-link" id="card_openval" href="#bottom" value="ON PROCESS">Click here for more info.</a>
                                        <i class="fas fa-chevron-right small text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending / Over SLA -->
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="card h-100 dashcard-clickable" data-filter="PENDING" style="border-radius: 15px; margin-top:15px; cursor:pointer;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="bg-opacity-10 p-1 rounded-circle text-danger" style="color: #D25353;">
                                        <i class="bi bi-exclamation-triangle-fill fs-1" style="font-size: 3rem;"></i>
                                    </div>
                                    <h2 class="fw-black mb-1" id="count_owfa" style="font-size:2.2rem; letter-spacing: -1px;">0</h2>
                                </div>
                                <div>
                                    <p class="text-danger fw-bold text-uppercase mb-0" style="font-size:0.75rem; color: #D25353; letter-spacing:1px;">Over Sla / Pending</p>
                                    <hr class="mt-2 mb-3" style="border-top: 2px solid #D25353; opacity:1; width:100%;" />
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a class="small text-danger stretched-link" id="card_openwfaval" href="#bottom" value="PENDING">Click here for more info.</a>
                                        <i class="fas fa-chevron-right small text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Closed Reports -->
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="card h-100 dashcard-clickable" data-filter="CLOSED" style="border-radius: 15px; margin-top:15px; cursor:pointer;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="bg-opacity-10 p-1 rounded-circle text-success" style="color: #94A378;">
                                        <i class="bi bi-check-all" style="font-size: 3rem;"></i>
                                    </div>
                                    <h2 class="fw-black mb-1" id="count_closed" style="font-size:2.2rem; letter-spacing: -1px;">0</h2>
                                </div>
                                <div>
                                    <p class="text-success fw-bold text-uppercase mb-0" style="font-size:0.75rem; color: #94A378; letter-spacing: 1px;">Closed Reports</p>
                                    <hr class="mt-2 mb-3" style="border-top: 2px solid #94A378; opacity:1; width:100%;" />
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a class="small text-success stretched-link" id="card_closedval" href="#bottom" value="CLOSED">Click here for more info.</a>
                                        <i class="fas fa-chevron-right small text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row" id="ovrall" >
                    <div class="col-12 col-lg-6 mb-3">
                        <div class="card card2 h-100" style="background: #ffffff;" >
                            <h5 class="card-header" >Store With Open Tickets</h5>
                            <div class="card-body" style="background: #ffffff;">
                                <div id="chartdiv5" ></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <div class="card card2 h-100">
                            <h5 class="card-header text-black" style="background: linear-gradient(135deg, #213456, #334c7a); color:black;">Open Tickets Per Deparment</h5>
                            <div class="card-body" style="background: #ffffff;">
                                <div id="chartdiv2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="card card2">
                            <h5 class="card-header text-black" style="background: linear-gradient(135deg, #213456, #334c7a); color:black;">Number of Escalated Reports Per Area</h5>
                            <div class="card-body" style="background: #ffffff;">
                                <div id="chart_area"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="card card2">
                            <h5 class="card-header text-black" style="background: linear-gradient(135deg, #213456, #334c7a); color:black;">Non Compliant Stores on End of Day Process (7:AM CUT OFF)</h5>
                            <div class="card-body" style="background: #ffffff;">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-8 col-lg-6">
                                        <div class="input-group">
                                            <span class="input-group-text">FROM</span>
                                            <input type="date" id="frompolDate" class="form-control">
                                            <span class="input-group-text">TO</span>
                                            <input type="date" id="topolDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div id="chart_polled"></div>
                            </div>
                        </div>
                    </div>
                </div><!-- /#ovrall -->

            


<!-- TABLES -->
<div class="row justify-content-center">
  <div class="col-12 mb-3">
    <div class="card card2" style="background: #ffffff; border-radius:12px;">
      <div class="card-header p-0" style="background: linear-gradient(135deg, #213456, #334c7a); border-bottom: none;"> 
        <ul class="nav nav-tabs card-header-tabs" id="ticketTabs" role="tablist">
         
        </ul>
      </div>

      <div class="card-body" style="background: #ffffff;">
        <div class="tab-content" id="ticketTabsContent">
          <div class="tab-pane fade show active" id="tickets" role="tabpanel" aria-labelledby="tickets-tab">
            <div class="table-responsive" style="max-height:450px; width:100%; overflow-y:auto;">
              <table id="report_data" class="table table-hover mb-0" style="min-width: 100%;">
                <tbody>
                </tbody>
              </table>
            </div>
          </div>

          <div class="tab-pane fade" id="transferred" role="tabpanel" aria-labelledby="transferred-tab">
            <div class="table-responsive" style="max-height:450px; width:100%; overflow-y:auto;">
              <table id="transferred_data" class="table table-hover mb-0" style="min-width: 100%;">
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12">
    <input type="hidden" id="myInput">
  </div>
</div>
                
                <input type="hidden" id="myInput">
                
                <!-- Sales Monitoring Section -->
                <div class="col-md-12 mb-4 mt-4" id="sales_dashboard">
                    <div class="glass-card">
                        <h1 class="text-dark">Daily Sales Monitoring</h1>
                        <table id="zreading_tbl" class="table table-dark table-bordered text-dark table-hover text-center">
                            <thead class="thead-dark text-dark"></thead>
                        </table>
                    </div>
                </div>

            </div> <!--end of inner container-->
        </div> <!-- end of layoutSidenav_content -->
    </div> <!--end of wrapper-->
</div> <!--end of main container-->


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

                  <div class="form-group col-12 col-md-4">
                    <label>ASSIGN SUPPORT</label>
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
                  <!--<div class="form-group col-12 col-md-4">
                    <label style="font-weight: bold;">TRANSFER REQUEST</label>
                    <div>
                      <input type="checkbox" name="is_transfer" id="is_transfer" value="1">
                      <label for="is_transfer"> Mark as Transfer Request</label>
                    </div>
                  </div>-->
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
                    <label style="font-weight: bold; color:#213456;">Add Comment:</label>
                    <textarea name="admsg" id="addmsg" class="form-control form-control-sm"
                      placeholder="Reply to their message or give updates regarding this ticket..."></textarea>
                  </div>

                  <div class="col-12 mt-4 mb-2 dv_msg px-0">
                    <label for="remarks_view" style="font-weight: bold; color:#213456;">Comment Thread:</label>
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