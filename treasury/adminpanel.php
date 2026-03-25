<?php

// ======== db  =========
include 'admin.php';
include '../condb.php';
include 'main_js.php';
include 'chrtdashboard.php';
include 'sub_graph_modal.php';
// include 'testcalendar.php';

// $conn=new dbconfig();

?>
<head>
  <link rel="stylesheet" href="adminpanel.css">
</head>
<style>

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

<!-- =========================
     DASHBOARD MAIN WRAPPER
     ========================= -->
<div class="container-fluid">
  <div id="wrapper">
    <div id="layoutSidenav_content">
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
              <select class="form-control" name="yearpicker" id="yearpicker"required>
                <option value="2019,2020,2021,2022,2023,2024,2025,2026">OVERALL</option>
                <option value="2026" selected>2026</OPTION>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
              </select>
            </div>
          </div>
          <div class="d-flex align-items-center gap-3">
            <form action="testcalendar.php" method="POST" class="m-0">
              <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id'];?>">
              <button type="submit" id="showCalendarBtn" class="btn">
                <i class="fas fa-calendar-alt me-2"></i>CALENDAR
              </button>
            </form>
            <div class="form-check form-switch float-right m-3">
              <input class="form-check-input" style="margin-left:-50px;" type="checkbox" id="darkModeToggle">
              <label class="form-check-label text-dark" for="darkModeToggle">Dark Mode</label>
          </div>
        </div>
      </div>
    </div>

    <!-- KPI CARDS (replaces card-deck properly) -->
    <div class="main-container">  
      <main class="p-4">
        <div class="row g-4">
          <div class="row g-4 mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6">
              <div class="card h-100 dashcard-clickable" data-filter="" style="border-radius: 15px; cursor:pointer;">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class=" bg-opacity-10 p-3 rounded-circle " style="color: #576A8F;">
                      <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                    <h2 class="fw-black mb-1" id="count_total" style="font-size:2.2rem; letter-spacing: -1px; ">0</h2>
                  </div>
                  <div>
                    <p class=" fw-bold text-uppercase mb-0" style="font-size: 0.75rem; color: #576A8F;letter-spacing: 1px;">Total Reports</p>
                    <hr class="mt-2 mb-3" style="border-top: 2px solid #576A8F; opacity: 1; width: 100%;"/>
                    <div class="d-flex justify-content-between align-items-center">
                      <a href="#report_data" class="text-decoration-none small text-muted stretched-link">Click here for more info</a>
                      <i class="fas fa-chevron-right small text-muted"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
              <div class="card  h-100 dashcard-clickable" data-filter="ON PROCESS" style="border-radius: 15px; cursor: pointer;">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class=" bg-opacity-10 p-3 rounded-circle" style="color: #E5BA41;">
                      <i class="fas fa-spinner fa-2x"></i>
                  </div>
                  <h2 class ="fw-black mb-1" id="count_open" style="font-size: 2.2rem; letter-spacing: -1px;">0</h2>
                </div>
                <div>
                  <p class="text-warning fw-bold text-uppercase mb-0" style="font-size: 0.75rem;color: #E5BA41; letter-spacing: 1px;">On Process</p>
                  <hr class="mt-2 mb-3" style="border-top: 2px solid #E5BA41;; opacity:1; width:100%;"/>
                  <div class="d-flex justify-content-between align-items-center">
                    <a href="#report_data" class="text-decoration-none small text-muted stretched-link">Click here for more info</a>
                    <i class="fas fa-chevron-right small text-muted"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card h-100 dashcard-clickable" data-filter="ATTENDED WITH FIX ASSET" style="border-radius: 15px; cursor:pointer;">
              <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger" style="color: #D25353;">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                  </div>
                  <h2 class="fw-black mb-1" id="count_owfa" style="font-size:2.2rem; letter-spacing: -1px;">0</h2>
                </div>
                <div>
                  <p class="text-danger fw-bold text-uppercase mb-0" style="font-size:0.75rem; color: #D25353;letter-spacing:1px;">Over Sla / Pending</p>
                  <hr class="mt-2 mb-3" style="border-top: 2px solid #D25353; opacity:1; width:100%;"/>
                  <div class="d-flex justify-content-between align-items-center">
                    <a href="#report_data" class="text-decoration-none small text-muted stretched-link">Click here for more info</a>
                    <i class="fas fa-chevron-right small text-muted"></i>
                  </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
          <div class="card h-100 dashcard-clickable" data-filter="CLOSED" style="border-radius: 15px; cursor:pointer;">
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
                <p class="text-success fw-bold text-uppercase mb-0" style="font-size:0.75rem; color: #94A378; letter-spacing: 1px;">Closed Reports</p>
                <hr class="mt-2 mb-3" style="border-top: 2px solid #94A378; opacity:1; width:100%;"/>
                <div class="d-flex justify-content-between align-items-center">
                  <a href="#report_data" class="text-decoration-none small text-muted stretched-link">View History</a>
                  <i class="fas fa-chevron-right small text-muted"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
        <!-- CHARTS -->
        <div class="row" id="ovrall">

          <div class="col-12 col-lg-6 mb-3">
            <div class="card card2 h-100">
              <h5 class="card-header" style="background-color: #95a2b9b4; color:black;">Overall Status</h5>
              <div class="card-body">
                <div id="chartdiv5"></div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6 mb-3">
            <div class="card card2 h-100">
              <h5 class="card-header text-black"  style="background-color: #95a2b9b4; color:black;">Treasury Support Logs</h5>
              <div class="card-body">
                <div id="chartdiv8"></div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6 mb-3">
            <div class="card card2 h-100">
              <h5 class="card-header text-black"  style="background-color: #95a2b9b4; color:black;">Recently enrolled reports.</h5>
              <div class="card-body">
                <div id="chartdiv1"></div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6 mb-3">
            <div class="card card2 h-100">
              <h5 class="card-header text-black"  style="background-color: #95a2b9b4; color:black;">CATEGORIES</h5>
              <div class="card-body">
                <div id="chartdiv2" name="chartdiv2"></div>
              </div>
            </div>
          </div>

          <div class="col-12 mb-3">
            <div class="card card2">
              <h5 class="card-header text-black"  style="background-color: #95a2b9b4; color:black;">Number of Escalated Reports Per Area</h5>
              <div class="card-body">
                <div id="chart_area"></div>
              </div>
            </div>
          </div>

          <div class="col-12 mb-3">
            <div class="card card2">
              <h5 class="card-header text-black"  style="background-color: #95a2b9b4; color:black;">Non Compliant Stores on End of Day Process (7:AM CUT OFF)</h5>
              <div class="card-body">

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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" />

        <!-- TABLES -->
        <div class="row">

          <div class="col-12 mb-3">
            <div class="card card2">
              <h5 class="card-header text-black"  style="background-color: #95a2b9b4; color:black;">TICKETS</h5>
              <div class="card-body">

                <div class="row">
                  <!-- old code with overflow -->
                  <!-- <div class="col-12 mb-3">  
                     <div class="table-responsive" id="proTeamScroll" style="max-height:450px; width:100%;overflow-y:auto;">
                    <table id="report_data" class="table table-hover">

                    </div>
                  </div> -->

                                    <div class="col-12 mb-3">
                     <div class="table-responsive" id="proTeamScroll" style="">
                    <table id="report_data" class="table table-hover">

                    </div>
                  </div>

                  <div class="col-12">
                     <div class="table-responsive" id="proTeamScroll" style="max-height:450px; width:100%;overflow-y:auto;">
                      <table id="network_tb" class="table table-hover">

                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

        </div><!-- /.row -->

        <div class="col-lg-12 Down" id="Down">
          <input type="hidden" id="myInput">
        </div>

      </div><!-- /.container-fluid -->
    </div><!-- /#layoutSidenav_content -->
  </div><!-- /#wrapper -->
</div><!-- /.container-fluid -->


<!-- =========================
Start of Add/Edit Modal
========================= -->
<div class="col-12 col-lg-12 modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                      $query="select * from tbl_branch ";
                      $run=$conn->prepare($query);
                      $run->execute();
                      $rs=$run->get_result();
                      while ($res=$rs->fetch_assoc()) {
                        $brcnhid = $res['str_num'];
                        $brnchcd = $res['str_code'].' | '.$res['str_name'];
                    ?>
                      <option value="<?php echo $brcnhid; ?>"><?= $brnchcd; ?></option>
                    <?php } ?>
                  </select>
                </div>

                <input type="hidden" class="form-control form-control-sm" name="ticket_no" id="ticket_no">

                <div class="form-group col-12">
                  <label>SUBJECT/CONCERN</label>
                  <textarea name="subjct" id="subjct" class="form-control form-control-sm"
                    placeholder="Input Concern" style="text-transform:uppercase"
                    onkeyup="this.value = this.value;"></textarea>
                </div>

                <div class="form-group col-12 col-md-4">
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
                      <option><?= $res['via_desc'] ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="form-group col-12 col-md-8">
                  <label>TREASURY SUPPORT</label>
                  <input type="hidden" name="it_num" id="it_num" readonly>
                  <select class="form-control form-control-sm" name="itsup" id="itsup" required>
                    <option value="">Assign support...</option>
                    <?php
                      $query="select * from it_tech WHERE deptsel = '15' AND itsup NOT IN ('4','7','8','12','14')";
                      $run=$conn->prepare($query);
                      $run->execute();
                      $rs=$run->get_result();
                      while ($res=$rs->fetch_assoc()) {
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
                          $query="select * from categories WHERE deptsel = '15'";
                      $run=$conn->prepare($query);
                      $run->execute();
                      $rs=$run->get_result();
                      while ($res=$rs->fetch_assoc()) {
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

                <div class="form-group col-12 col-md-4 hide_isp">
                  <label for="isp" id="lbl_isp">Service Provider</label>
                  <input type="hidden" name="isp_num" id="isp_num" readonly>
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
                      class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker3"/>
                    <div class="input-group-append" data-target="#date_created" data-toggle="datetimepicker">
                      <input type="hidden" class="form-control form-control-sm" name="date_createdx" id="date_createdx">
                      <div class="input-group-text" id="ico_cal3"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>

                <div class="form-group col-12 col-md-4">
                  <label>STATUS</label>
                  <select class="form-control form-control-sm" name="status" id="status" required>
                    <option value=""> &larr; Status &rarr;</option>
                    <?php
                      $query="select * from status  WHERE it_module_tag = 'Y'";
                      $run=$conn->prepare($query);
                      $run->execute();
                      $rs=$run->get_result();
                      while ($res=$rs->fetch_assoc()) {
                    ?>
                      <option><?= $res['stat_desc'] ?></option>
                    <?php } ?>
                    <option value="CLOSED" readonly>CLOSED</option>
                  </select>
                </div>

                <div class="form-group col-12 col-md-4 hide_cl">
                  <label id="dateclabel" class="hidden">DATE CLOSED</label>
                  <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                    <input type="text" name="date_closed" id="date_closed"
                      class="form-control form-control-sm datetimepicker-input"
                      data-target="#datetimepicker2" autocomplete="off"/>
                    <div class="input-group-append" data-target="#date_closed" autocomplete="off" data-toggle="datetimepicker">
                      <div class="input-group-text" id="ico_cal" name="ico_cal"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>

                <div class="form-group col-12 col-md-4 hide_cl">
                  <label id="clby_label" class="hidden">CLOSED BY</label>
                  <input type="hidden" name="close_by" id="close_by" value="<?php echo $_SESSION['tech_id']; ?>">
                  <input type="text" class="form-control form-control-sm" name="cl_desc" id="cl_desc" readonly
                    value="<?php echo $_SESSION['fname']. '  ' . $_SESSION['lstname']; ?>">
                </div>

                <div class="form-group col-12">
                  <label>Work Output:</label>
                  <textarea name="remarks" id="remarks" class="form-control form-control-sm" placeholder="Your Workoutput"></textarea>
                </div>

                <div class="col-12">
                  <label style="font-weight: bold;">Attached File:</label>
                  <p><input id="file-input" type="file" name="file" Multiple></p>
                </div>

                <div class="col-12"><hr/></div>

                <div class="col-12 d-flex justify-content-between align-items-center">
                  <input type="submit" name="action" id="action" class="btn btn-success" value="Add">
                  <button type="button" name="btnClose" id="btnClose" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

                <div class="col-12"><hr/></div>

                <div class="card" id="img" name="img"></div>

              </div><!-- /.row -->

            </div><!-- /.left -->

            <!-- RIGHT SIDE -->
            <div class="col-12 col-lg-6">

              <div id="msg_thread">

                <div class="col-12 mb-3 px-0">
                  <label style="font-weight: bold; color:white;">Add Comment:</label>
                  <textarea name="admsg" id="addmsg" class="form-control form-control-sm"
                    placeholder="Reply to their message or give an updates regarding on this ticket..." required></textarea>
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

<script>
  $(document).ready(function() {
    // KPI Card Click Functionality
    $('.dashcard-clickable').on('click', function() {
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
});
</script>


