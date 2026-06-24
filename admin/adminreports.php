<?php
include 'admin.php';
include 'main_js.php';
include '../condb.php';

date_default_timezone_set('Asia/Manila'); 
$ph_datetime = date('l, F d, Y - h:i A'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="adminpanel.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" />
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@linways/table-to-excel@1.0.4/dist/tableToExcel.min.js"></script>

<style>
::-webkit-scrollbar { 
  width: 6px; 
  height: 6px; 
}
::-webkit-scrollbar-track { 
  background: rgba(0, 0, 0, 0.05); 
  border-radius: 10px; 
}
::-webkit-scrollbar-thumb { 
  background: linear-gradient(135deg, #837031, #E1AD01); 
  border-radius: 10px; 
}

:root {
  --navy: #121C31;
  --navy2: #1a2a4a;
  --yellow: #EAAA00;
  --bg: #EEF2F7;
  --card: #ffffff;
  --line: #E5E7EB;
  --text: #111827;
  --muted: #6B7280;
  --shadow: 0 14px 32px rgba(17,24,39,.12);
  --radius: 12px;
  --bg-body: #F4F5FA;
}

body {
  background: linear-gradient(to bottom, #ffffff, #6d89b9);
  background-attachment: fixed; 
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


.action-bar-container {
  padding: 10px 15px !important;
  margin-bottom: 12px !important;
}
.input-group-text {
  background: var(--navy) !important;
  color: #fff !important;
  border: 1px solid rgba(0,0,0,.08) !important;
  font-weight: 700 !important;
  border-radius: 8px 0 0 8px !important;
  font-size: 13px;
}
select.form-control, .form-control, .form-select {
  background: #fff !important;
  color: var(--text) !important;
  border: 1px solid var(--line) !important;
  border-radius: 0 8px 8px 0 !important;
  font-size: 13px;
  padding: 4px 8px;
}

#print-filter-badge-header { 
  display: none; 
}
.card{ 
  padding: 25px;
 }
.overall-table {
   margin: 15px 0; 
   width: 100%; 
  }
.admin-table {
   width: 100%; 
   box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
   background-color: #fff; 
  }
.admin-table th { 
  background-color: #3d4e6d; 
  color: #ffffff !important; 
  font-weight: 700; 
  text-transform: uppercase; 
  font-size: 13px !important; 
  letter-spacing: 0.5px; 
  padding: 10px 8px !important; 
}
.admin-table td { 
  padding: 10px 8px !important; 
  vertical-align: middle; 
  color: #2D3748; 
  font-size: 13px !important; 
  border-bottom: 1px solid var(--line); 
}
.admin-table tbody tr:hover { 
  background-color: rgba(151, 178, 205, 0.2) !important; 
}

.department-table tbody tr:hover { 
  background-color: rgba(151, 178, 205, 0.2) !important; 
}
.dept-badge-title { 
  font-weight: 700; 
  color: #1A202C; 
}
.department-table { 
  width: 100%; 
}
.department-table th { 
  background-color: #213456; 
  color: #ffffff !important; 
  font-weight: 600; 
  font-size: 13px; 
  padding: 10px; 
}
.department-table td { 
  padding: 10px; 
  font-size: 13px; 
  border-bottom: 1px solid var(--line); 
}

@media print {
  @page { 
    size: landscape; 
    margin: 0.2in 0.3in; 
  }
  html, body { 
    background: #fff !important; 
    height: 100%; 
    max-height: 100vh; 
    overflow: hidden !important; 
    font-size: 11px !important; 
  }
  #print-filter-badge-header { 
    display: block !important; 
    margin-bottom: 5px !important; 
    border-bottom: 2px dashed #3d4e6d; 
    padding-bottom: 8px; 
    box-shadow: 0 0 10px rgba(0, 255, 255, 0.1); 
  }
  .action-bar-container, .card-header .d-flex, .btn, #layoutSidenav_nav, nav, header, .owi-navbar, .noExl, .no-print-header { 
    display: none !important;
  }
  .card-header { 
    padding: 0 !important; 
    border-bottom: none !important; 
  }
  .container-fluid, #wrapper, #layoutSidenav_content { 
    padding: 0 !important; 
    margin: 0 !important; 
    width: 100% !important; 
    max-width: 100% !important; 
    box-shadow: none !important; 
    background: transparent !important; 
  }
  .card { 
    border: none !important; 
    box-shadow: none !important; 
    background: transparent !important; 
    page-break-inside: avoid; 
    margin-top: -60px; 
    padding: -20px; 
    border-radius: 0; 
  }
  .table-responsive { 
    overflow: visible !important; 
    width: 100% !important; 
  }
  .admin-table { 
    width: 100% !important; 
    table-layout: auto !important; 
    page-break-inside: avoid; 
  }
  .admin-table th { 
    background-color: #213456 !important; 
    color: #fff !important; 
    padding: 6px 4px !important; 
    font-size: 11px !important; 
  }
  .admin-table td { 
    padding: 6px 4px !important; 
    font-size: 11px !important; 
    border-bottom: 1px solid #0e0e0ea1 !important; 
  }
  .progress { 
    border: 1px solid #999 !important; 
    background-color: #ddd !important; 
    -webkit-print-color-adjust: exact !important; 
    print-color-adjust: exact !important; 
  }
  #dept-table-footer, #dept-table-footer-non-escalated { 
    border: 2px solid #2d3c59; 
    background-color: #f4e9d7 !important; 
  }
  * { -webkit-print-color-adjust: exact !important; 
  print-color-adjust: exact !important; 
  color-adjust: exact !important; 
  box-shadow: none !important; 
}
}
</style>
</head>
<body>

<div class="container-fluid" id="printTicketReport">
  <div id="wrapper">
    <div id="layoutSidenav_content">
      <div class="container-fluid py-2">
        
        <form method="post" name="cof_form" id="cof_form" enctype="multipart/form-data">
          <input type="hidden" name="chcksbjcls" id="chcksbjcls" value="check">
        </form>

        <div id="print-filter-badge-header">
          <div class="header" style="font-size: 15px; display: flex; align-items: center; justify-content: center; text-align: center; margin-bottom:-15px;">OFFICE WAREHOUSE INC</div>
          <div class="header" style="font-size: 25px; margin-top:6px; display: flex; align-items: center; justify-content: center; text-align: center; margin-bottom: -5px;">HELPDESK - DEPARTMENT TICKET SUMMARY</div>
          <div class="datetime-container" style=" margin-top:1px; display: flex; align-items: center; justify-content: center; text-align: center; margin-bottom: 8px;">
            <p>As of: <strong><?php echo $ph_datetime; ?></strong></p>
          </div>
          <table style="width: 100%; margin-top:10px; font-family: sans-serif;padding:60px; box-shadow: 0 6px 18px rgba(0, 0, 0, 0.6);color: #121C31;">
            <tr>
              <td style="text-align: right; font-size: 13px; border:none; padding:0;">
                <strong>LOGS IN YEAR OF:</strong> <span id="print-year-val">--</span> &nbsp;|&nbsp; 
                <strong>FILTER BY MONTH:</strong> <span id="print-month-val">--</span>
              </td>
            </tr>
          </table>
        </div>

        <div class="action-bar-container d-flex flex-wrap gap-2 align-items-center p-2 mb-3 rounded bg-white shadow-sm" style="box-shadow: 0 4px 8px rgba(45, 60, 89, 0.15) !important;">
          <div class="d-flex flex-wrap gap-2 w-100">
            <div class="input-group" style="max-width: 380px;">
              <span class="input-group-text bg-light border-end-0" style="width: 150px; ">LOGS IN YEAR OF:</span>
              <select class="form-select border-start-0" name="yearpicker" id="yearpicker" required>
                <option value="2019,2020,2021,2022,2023,2024,2025,2026">OVERALL</option>
                <option value="2026" selected>2026</option>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
              </select>
            </div>
            <div class="input-group" style="max-width: 380px;">
              <span class="input-group-text bg-light border-end-0" style="width: 150px;">FILTER BY MONTH:</span>
              <select class="form-select border-start-0" name="monthpicker" id="monthpicker" required>
                <option value="01,02,03,04,05,06,07,08,09,10,11,12" selected>OVERALL</option>
                <option value="01">JANUARY</option>
                <option value="02">FEBRUARY</option>
                <option value="03">MARCH</option>
                <option value="04">APRIL</option>
                <option value="05">MAY</option>
                <option value="06">JUNE</option>
                <option value="07">JULY</option>
                <option value="08">AUGUST</option>
                <option value="09">SEPTEMBER</option>
                <option value="10">OCTOBER</option>
                <option value="11">NOVEMBER</option>
                <option value="12">DECEMBER</option>
              </select>
            </div>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-12">
            <div class="card card2 border-0 shadow-sm" style="border-radius: 12px; background: rgba(255, 255, 255, 0.95);">
              <div class="card-header d-flex flex-wrap justify-content-between align-items-center text-dark py-2 gap-2" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                <h5 class="m-0 font-weight-bold no-print-header" style="letter-spacing: 0.3px; font-size: 1.15rem;">
                  <i class="fa-solid fa-circle text-success me-2"></i> HELPDESK DEPARTMENT TICKET SUMMARY
                </h5>
                <div class="d-flex gap-2">
                  <button type="button" class="btn text-white d-flex align-items-center gap-2" style="border-radius: 6px; font-size: 13px; background-color: #1f7246; padding: 4px 12px;" id="btnExportExcel">
                    <i class="fa fa-file-excel" aria-hidden="true"></i> Excel
                  </button>
                  <button type="button" class="btn text-white d-flex align-items-center gap-2" style="border-radius: 6px; font-size: 13px; background-color: #12922a; padding: 4px 12px;" onclick="window.print()">
                    <i class="fa fa-print" aria-hidden="true"></i> Print
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table id="admin_report" class="table admin-table m-0">
                    <thead>  
                      <tr>
                        <th colspan="5" class="text-center" style="background-color: #2b9827; color: #fff; font-weight: 700; text-transform: uppercase; font-size: 13px; padding: 6px;">ACTIVE/RUNNING TICKET REPORTS</th>
                        <th colspan="4" class="text-center" style="background-color: #a29341; color: #fff; font-weight: 700; text-transform: uppercase; font-size: 13px; padding: 6px;">COMPLIANCE TICKET REPORTS</th>
                      </tr>
                      <tr style="background-color: #213456; color: #ffffff;">
                        <th style="font-size: 12px; vertical-align: middle;">DEPARTMENT</th>
                        <th class="text-center fw-bold" style="font-size: 12px; vertical-align: middle;">ASSIGNED</th>
                        <th class="text-center fw-bold" style="font-size: 12px; vertical-align: middle;">ON PROCESS</th>
                        <th class="text-center" style="font-size: 12px; vertical-align: middle;">PENDING</th>
                        <th class="text-center" style="font-size: 12px; vertical-align: middle;">TOTAL (ACTIVE)</th>
                        <th class="text-center" style="font-size: 12px; vertical-align: middle;">COMPLIANCE RATE</th>
                        <th class="text-center" style="font-size: 12px; vertical-align: middle;">MET SLA</th>
                        <th class="text-center" style="font-size: 12px; vertical-align: middle;">NON-SLA</th>
                        <th class="text-center" style="font-size: 12px; vertical-align: middle;">SLA COMPLIANCE</th>
                      </tr>
                    </thead>
                    <tbody id="dept-table-body">
                      <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                          <div class="spinner-border spinner-border-sm me-2 text-warning" role="status"></div> Loading department metrics...
                        </td>
                      </tr>
                    </tbody>
                    <tfoot id="dept-table-footer" class="bg-light" style="border: 2px solid #2d3c59; background: #F4E9D7;"></tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div> 

        <div class="row mb-4">
          <div class="col-12">
            <div class="card card2 border-0 shadow-sm" style="border-radius: 12px; background: rgba(255, 255, 255, 0.95);">
              <div class="card-header d-flex flex-wrap justify-content-between align-items-center text-dark py-2 gap-2" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                <h5 class="m-0 font-weight-bold no-print-header" style="letter-spacing: 0.3px; font-size: 1.15rem;">
                  <i class="fa-solid fa-circle text-success me-2"></i> ESCALATED REPORTS FOR NON-ACTION
                </h5>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table id="admin_report_escalated" class="table admin-table m-0">
                    <thead>  
                      <tr style="background-color: #213456; color: #ffffff;">
                        <th style="font-size: 12px; vertical-align: middle;">DEPARTMENT</th>
                        <th class="text-center fw-bold" style="font-size: 12px; vertical-align: middle;">TOTAL NUMBER OF NON-ESCALATED REPORTS</th>
                        <th class="text-center fw-bold" style="font-size: 12px; vertical-align: middle;">PERCENTAGE SHARE</th>
                      </tr>
                    </thead>
                    <tbody id="dept-table-body-non-escalated">
                      <tr>
                        <td colspan="3" class="text-center py-4 text-muted">
                          <div class="spinner-border spinner-border-sm me-2 text-warning" role="status"></div> Loading non-escalated metrics...
                        </td>
                      </tr>
                    </tbody>
                    <tfoot id="dept-table-footer-non-escalated" class="bg-light" style="border: 2px solid #2d3c59; background: #F4E9D7;"></tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div> 
        <div class="row">
          <div class="col-12">
            <div class="card card2 border-0 shadow-sm" style="border-radius: 12px; background: rgba(255, 255, 255, 0.95); margin-top: 20px;">
              <div class="card-header d-flex flex-wrap justify-content-between align-items-center text-dark py-2 gap-2" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                <h5 class="m-0 font-weight-bold no-print-header" style="letter-spacing: 0.3px; font-size: 1.15rem;">
                  <i class="fa-solid fa-circle text-success me-2"></i> Ticket Transfer Logs
                </h5>
                <div class="ms-auto no-print-header" style="width: 280px;">
                  <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" id="searchTransferLog" class="form-control border-start-0" placeholder="Search ticket, dept...">
                  </div>
                </div>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table id="transfer_logs_table" class="table admin-table m-0">
                    <thead>  
                      <tr style="background-color: #213456; color: #ffffff;">
                        <th style="font-size: 12px; vertical-align: middle;">TICKET NO</th>
                        <th class="text-center fw-bold" style="font-size: 12px; vertical-align: middle;">STORE</th>
                        <th class="text-center fw-bold" style="font-size: 12px; vertical-align: middle;">FROM DEPT</th>
                        <th class="text-center fw-bold" style="font-size: 12px; vertical-align: middle;">TO DEPT</th>
                         <th class="text-center fw-bold" style="font-size: 12px; vertical-align: middle;">ORIGINAL TECH</th>
                          <th class="text-center fw-bold" style="font-size: 12px; vertical-align: middle;">NEW TECH</th>
                          <th class="text-center fw-bold" style="font-size: 12px; vertical-align: middle;">REQUESTED DATE</th>
                          <th class="text-center fw-bold" style="font-size: 12px; vertical-align: middle;">APPROVAL DATE</th>
                           <th class="text-center fw-bold" style="font-size: 12px; vertical-align: middle;">TURNAROUND</th>
                      </tr>
                    </thead>
                    <tbody id="transfer-logs-table-body">
                      <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                          <div class="spinner-border spinner-border-sm me-2 text-warning" role="status"></div> Loading Transfer Logs...
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top bg-light no-print-header">
                  <span id="transfer-log-info" class="text-muted small">Showing 0 to 0 of 0 entries</span>
                  <div class="btn-group btn-group-sm">
                    <button type="button" id="btnPrevTransfer" class="btn btn-outline-secondary" disabled>Previous</button>
                    <button type="button" id="btnNextTransfer" class="btn btn-outline-secondary" disabled>Next</button>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div> 

        <div class="row">
          <div class="col-12">
            <div class="card card2 border-0 shadow-sm" style="border-radius: 12px; background: rgba(255, 255, 255, 0.95); margin-top: 20px;">
              <div class="card-header d-flex flex-wrap justify-content-between align-items-center text-dark py-2 gap-2" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                <h5 class="m-0 font-weight-bold no-print-header" style="letter-spacing: 0.3px; font-size: 1.15rem;">
                  <i class="fa-solid fa-circle text-success me-2"></i> User Activity Logs
                </h5>
                <div class="ms-auto no-print-header" style="width: 280px;">
                  <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" id="searchUserLog" class="form-control border-start-0" placeholder="Search user, dept, or date...">
                  </div>
                </div>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table id="user_activity" class="table admin-table m-0">
                    <thead>  
                      <tr style="background-color: #213456; color: #ffffff;">
                        <th style="font-size: 12px; vertical-align: middle;">HELPDESK USER</th>
                        <th class="text-center fw-bold" style="font-size: 12px; vertical-align: middle;">DEPARTMENT</th>
                        <th class="text-center fw-bold" style="font-size: 12px; vertical-align: middle;">LOG IN DATE</th>
                      </tr>
                    </thead>
                    <tbody id="user-activity-table-body">
                      <tr>
                        <td colspan="3" class="text-center py-4 text-muted">
                          <div class="spinner-border spinner-border-sm me-2 text-warning" role="status"></div> Loading User Activity Logs...
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top bg-light no-print-header">
                  <span id="user-log-info" class="text-muted small">Showing 0 to 0 of 0 entries</span>
                  <div class="btn-group btn-group-sm">
                    <button type="button" id="btnPrevLog" class="btn btn-outline-secondary" disabled>Previous</button>
                    <button type="button" id="btnNextLog" class="btn btn-outline-secondary" disabled>Next</button>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div> 

        

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deptDetailsModal" tabindex="-1" aria-labelledby="deptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 95%;">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; background: rgba(255, 255, 255, 0.95);">
      <div class="modal-header border-0 pb-0" style="background-color: #213456; color: #fff;">
        <h5 class="modal-title font-weight-bold" id="deptModalLabel" style="font-size:1.5rem;">TICKET DETAILS OVERVIEW</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-3">
        <div class="d-flex align-items-center mb-3 p-2 rounded" style="background: rgba(225, 173, 1, 0.1); border-left: 5px solid #e1ad01;">
          <i class="fas fa-chart-pie fa-lg text-warning me-3" style="margin-right: 10px;"></i>  
          <div class="w-100 d-flex justify-content-between align-items-center">
            <div>
              <h4 class="m-0 font-weight-bold text-dark" id="modal-dept-name">Department Name</h4> 
              <small class="text-muted" id="modal-dept-year">Year Metrics</small>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table department-table m-0">
            <thead>
              <tr>
                <th>TICKET NO</th>
                <th class="text-center">STR CODE</th>
                <th class="text-center">DATE CREATED</th>
                <th class="text-center">CONCERN</th>
                <th class="text-center">STATUS</th>
                <th class="text-center">CATEGORY</th>
                <th class="text-center">ASSIGNED DEPARTMENT</th>
                <th class="text-center">SUB CATEGORY</th>
                <th class="text-center">REMARKS</th>
                <th class="text-center">SUBJECT</th>
              </tr>
            </thead>
            <tbody id="modal-table-body"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="nonescaTicketDetails" tabindex="-1" aria-labelledby="nonEscaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 95%;">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; background: rgba(255, 255, 255, 0.95);">
      <div class="modal-header border-0 pb-0" style="background-color: #213456; color: #fff;">
        <h5 class="modal-title font-weight-bold" id="nonEscaModalLabel" style="font-size:1.5rem;">DEPARTMENT OVERVIEW (NON-ESCALATED)</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-3">
        <div class="d-flex align-items-center mb-3 p-2 rounded" style="background: rgba(225, 173, 1, 0.1); border-left: 5px solid #e1ad01;">
          <i class="fas fa-chart-pie fa-lg text-warning me-3" style="margin-right: 10px;"></i>  
          <div class="w-100 d-flex justify-content-between align-items-center">
            <div>
              <h4 class="m-0 font-weight-bold text-dark" id="modal-dept-name-non">Department Name</h4> 
              <small class="text-muted" id="modal-dept-year-non">Year Metrics</small>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table department-table m-0">
            <thead>
              <tr>
                <th>TICKET NO</th>
                <th class="text-center">STR CODE</th>
                <th class="text-center">DATE CREATED</th>
                <th class="text-center">DAYS UNRESOLVED</th>
                <th class="text-center">SLA DAYS</th>
                <th class="text-center">CONCERN</th>
                <th class="text-center">STATUS</th>
                <th class="text-center">CATEGORY</th>
                <th class="text-center">ASSIGNED DEPARTMENT</th>
                <th class="text-center">SUB CATEGORY</th>
                <th class="text-center">REMARKS</th>
                <th class="text-center">SUBJECT</th>
              </tr>
            </thead>
            <tbody id="modal-table-body-non-escalated"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    let globalTicketDetails = [];
    let globalTicketDetails2 = [];
    let globalUserLogs = [];
    let filteredLogsCache = [];
    let currentPageLog = 1;
    const rowsPerPageLog = 10;
    let globalTransferLogs = [];
    let filteredTransferLogsCache = [];
    let currentPageTransfer = 1;
    const rowsPerPageTransfer = 10;

    window.onbeforeprint = function() {
        $("#print-year-val").text($("#yearpicker option:selected").text());
        $("#print-month-val").text($("#monthpicker option:selected").text());
    };

    $('#btnExportExcel').on('click', function() {
        let clonedSummaryTable = document.getElementById('admin_report').cloneNode(true);
        let clonedNonEscaTable = document.getElementById('admin_report_escalated').cloneNode(true);
        let bars = clonedSummaryTable.querySelectorAll('.progress');
        bars.forEach(b => b.parentNode.removeChild(b));
        let book = TableToExcel.tableToBook(clonedSummaryTable, { sheet: { name: "Helpdesk Ticket Summary" } });
        TableToExcel.tableToSheet(book, clonedNonEscaTable, { sheet: { name: "Non-Escalated Reports" } });
        TableToExcel.save(book, "Helpdesk_Department_Summary_Reports.xlsx");
    });

    function renderUserActivityLogs() {
        let searchTerm = $('#searchUserLog').val().toLowerCase();
        let html3 = '';

        filteredLogsCache = globalUserLogs.filter(row => {
            let user = String(row.HELPDESK_USER || '').toLowerCase();
            let dept = String(row.DEPARTMENT || '').toLowerCase();
            let logDate = String(row.login_date || '').toLowerCase();
            return user.includes(searchTerm) || dept.includes(searchTerm) || logDate.includes(searchTerm);
        });

        let totalPages = Math.ceil(filteredLogsCache.length / rowsPerPageLog);
        if(totalPages === 0) totalPages = 1;
        if(currentPageLog > totalPages) currentPageLog = totalPages;
        if(currentPageLog < 1) currentPageLog = 1;

        let startIndex = (currentPageLog - 1) * rowsPerPageLog;
        let endIndex = startIndex + rowsPerPageLog;

        let logsToDisplay = filteredLogsCache.slice(startIndex, endIndex);

        if(logsToDisplay.length > 0) {
            logsToDisplay.forEach(function(row){
                html3 += `
                    <tr>
                        <td><span style="font-size:13px;">${row.HELPDESK_USER || 'N/A'}</span></td>
                        <td class="text-center fw-bold" style="font-size:13px;">${row.DEPARTMENT || 'N/A'}</td>
                        <td class="text-center" style="font-size:13px; color:black;">${row.login_date || 'N/A'}</td>
                    </tr>`;
            });
        } else {
            html3 = '<tr><td colspan="3" class="text-center py-4 text-muted">No matching records found.</td></tr>';
        }

        $('#user-activity-table-body').html(html3);

        let startCount = filteredLogsCache.length > 0 ? startIndex + 1 : 0;
        let endCount = Math.min(endIndex, filteredLogsCache.length);

        $('#user-log-info').text(`Showing ${startCount} to ${endCount} of ${filteredLogsCache.length} entries`);
        $('#btnPrevLog').prop('disabled', currentPageLog === 1);
        $('#btnNextLog').prop('disabled', currentPageLog === totalPages || filteredLogsCache.length === 0);
    }

    $('#searchUserLog').on('keyup', function() {
        currentPageLog = 1; 
        renderUserActivityLogs();
    });

    $('#btnPrevLog').on('click', function() {
        if (currentPageLog > 1) {
            currentPageLog--;
            renderUserActivityLogs();
        }
    });

    $('#btnNextLog').on('click', function() {
        let totalPages = Math.ceil(filteredLogsCache.length / rowsPerPageLog);
        if (currentPageLog < totalPages) {
            currentPageLog++;
            renderUserActivityLogs();
        }
    });

   function renderTransferLogs() {
        let searchTerm = $('#searchTransferLog').val().toLowerCase();
        let htmlTransfer = '';

        filteredTransferLogsCache = globalTransferLogs.filter(row => {
            let ticket = String(row.ticket_no || '').toLowerCase();
            let fromDept = String(row.from_department || '').toLowerCase();
            let toDept = String(row.to_department || '').toLowerCase();
            return ticket.includes(searchTerm) || fromDept.includes(searchTerm) || toDept.includes(searchTerm);
        });

        let totalPages = Math.ceil(filteredTransferLogsCache.length / rowsPerPageTransfer);
        if(totalPages === 0) totalPages = 1;
        if(currentPageTransfer > totalPages) currentPageTransfer = totalPages;
        if(currentPageTransfer < 1) currentPageTransfer = 1;

        let startIndex = (currentPageTransfer - 1) * rowsPerPageTransfer;
        let endIndex = startIndex + rowsPerPageTransfer;

        let logsToDisplay = filteredTransferLogsCache.slice(startIndex, endIndex);

        if(logsToDisplay.length > 0) {
            logsToDisplay.forEach(function(row){
              let turnaroundRaw = String(row.turnaround_time || '0h');
              let t_hours = parseInt(turnaroundRaw.split('h')[0]) || 0; 
              let turnaroundColor = t_hours >= 24 ? 'text-danger' : 'text-success';
              let turnaroundText = row.turnaround_time || 'N/A';
                
                htmlTransfer += `
                    <tr>
                        <td><strong>${row.ticket_no || 'N/A'}</strong></td>
                        <td class="text-center" style="font-size:13px;">${row.store_name || 'Unknown'}</td>
                                                <td class="text-center"><span class="badge" style="background-color: #a09e08; color: #fff; padding: 4px 8px;">${row.to_department || 'N/A'}</span></td>
                        <td class="text-center"><span class="badge bg-secondary text-white px-2 py-1">${row.from_department || 'N/A'}</span></td>

                        <td class="text-center" style="font-size:13px;">${row.original_support || 'N/A'}</td>
                        <td class="text-center" style="font-size:13px;">${row.new_support || 'N/A'}</td>
                        <td class="text-center" style="font-size:13px;">${row.request_date || 'N/A'}</td>
                        <td class="text-center" style="font-size:13px;">${row.approval_date || 'N/A'}</td>
                        <td class="text-center fw-bold ${turnaroundColor}" style="font-size:13px;">${turnaroundText}</td>
                    </tr>`;
            });
        } else {
            htmlTransfer = '<tr><td colspan="9" class="text-center py-4 text-muted">No matching records found.</td></tr>';
        }

        $('#transfer-logs-table-body').html(htmlTransfer);

        let startCount = filteredTransferLogsCache.length > 0 ? startIndex + 1 : 0;
        let endCount = Math.min(endIndex, filteredTransferLogsCache.length);

        $('#transfer-log-info').text(`Showing ${startCount} to ${endCount} of ${filteredTransferLogsCache.length} entries`);
        $('#btnPrevTransfer').prop('disabled', currentPageTransfer === 1);
        $('#btnNextTransfer').prop('disabled', currentPageTransfer === totalPages || filteredTransferLogsCache.length === 0);
    }

    $('#searchTransferLog').on('keyup', function() {
        currentPageTransfer = 1; 
        renderTransferLogs();
    });

    $('#btnPrevTransfer').on('click', function() {
        if (currentPageTransfer > 1) {
            currentPageTransfer--;
            renderTransferLogs();
        }
    });

    $('#btnNextTransfer').on('click', function() {
        let totalPages = Math.ceil(filteredTransferLogsCache.length / rowsPerPageTransfer);
        if (currentPageTransfer < totalPages) {
            currentPageTransfer++;
            renderTransferLogs();
        }
    });

    function loadDepartmentTable(selectedYear, selectedMonth) {
        let targetedURL = 'fetch_department_table.php'; 
        
        $.ajax({
            url: targetedURL, 
            method: 'POST',
            data: { yr: selectedYear, mo: selectedMonth },
            dataType: 'json',
            success: function(response) {
                let html1 = '';
                let footerHtml1 = '';
                
                if(response && response.department_stats && response.department_stats.length > 0) {
                    globalTicketDetails = response.ticket_details || []; 
                    response.department_stats.forEach(function(row) {
                        let closedCount = parseInt(row.CLOSED) || 0;
                        let grandTotal = parseInt(row.GRAND_TOTAL) || 0;
                        let metSlaCount = parseInt(row.MET_SLA) || 0;
                        let activeTotal = (parseInt(row.ASSIGNED) || 0) + (parseInt(row.ON_PROCESS) || 0) + (parseInt(row.PENDING) || 0);
                        let compliancePercent = grandTotal > 0 ? Math.round((closedCount / grandTotal) * 100) : 0;
                        let metSLA = closedCount > 0 ? Math.round((metSlaCount / closedCount) * 100) : 0;

                        let barTheme = compliancePercent >= 75 ? "bg-success" : (compliancePercent >= 40 ? "bg-warning" : "bg-danger");
                        let metBarTheme = metSLA >= 80 ? "bg-success" : (metSLA >= 50 ? "bg-warning" : "bg-danger");

                        html1 += `
                            <tr class="dept-row" style="cursor: pointer;" data-dept="${row.DEPARTMENT}">
                                <td><span class="dept-badge-title" style="font-size:13px;">${row.DEPARTMENT}</span></td>
                                <td class="text-center fw-bold" style="font-size:13px;">${row.ASSIGNED}</td>
                                <td class="text-center fw-bold" style="font-size:13px;">${row.ON_PROCESS}</td>
                                <td class="text-center fw-bold" style="font-size:13px;">${row.PENDING}</td>
                                <td class="text-center fw-bold" style="font-size:13px;"><span class="text-primary">${activeTotal}</span></td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center flex-column" style="padding: 0 4px;">
                                        <div class="d-flex justify-content-between w-100 mb-1 small fw-bold">
                                            <span style="font-size:12px;">${closedCount}/${grandTotal}</span>
                                            <span style="font-size:12px;">${compliancePercent}%</span>
                                        </div>
                                        <div class="progress w-100" style="height: 6px; background-color: rgba(0,0,0,0.06);">
                                            <div class="progress-bar ${barTheme}" style="width: ${compliancePercent}%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-bold text-success" style="font-size:13px;">${metSlaCount}</td>
                                <td class="text-center fw-bold text-danger" style="font-size:13px;">${closedCount - metSlaCount}</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center flex-column" style="padding: 0 4px;">
                                        <div class="d-flex justify-content-between w-100 mb-1 small fw-bold">
                                            <span style="font-size:12px;">${metSlaCount}/${closedCount}</span>
                                            <span style="font-size:12px;">${metSLA}%</span>
                                        </div>
                                        <div class="progress w-100" style="height: 6px; background-color: rgba(0,0,0,0.06);">
                                            <div class="progress-bar ${metBarTheme}" style="width: ${metSLA}%;"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>`;
                    });

                    if(response.global_totals) {
                        let gt = response.global_totals;
                        let totalActiveSum = (parseInt(gt.TOTAL_ASSIGNED)||0) + (parseInt(gt.TOTAL_ON_PROCESS)||0) + (parseInt(gt.TOTAL_PENDING)||0);
                        let globalCompliancePercent = (parseInt(gt.OVERALL_GRAND_TOTAL)||0) > 0 ? Math.round((parseInt(gt.TOTAL_CLOSED)/parseInt(gt.OVERALL_GRAND_TOTAL)) * 100) : 0;
                        let globalMetSLAPercent = (parseInt(gt.TOTAL_CLOSED)||0) > 0 ? Math.round((parseInt(gt.TOTAL_MET_SLA)/parseInt(gt.TOTAL_CLOSED)) * 100) : 0;

                        footerHtml1 = `
                            <tr style="font-weight: bold; background: #F4E9D7;">
                                <td class="text-dark fw-bold text-uppercase" style="font-size:13px;">TOTAL SUMMARY</td>
                                <td class="text-center">${gt.TOTAL_ASSIGNED}</td>
                                <td class="text-center">${gt.TOTAL_ON_PROCESS}</td>
                                <td class="text-center">${gt.TOTAL_PENDING}</td>
                                <td class="text-center text-primary fw-bold">${totalActiveSum}</td>
                                <td>${globalCompliancePercent}%</td>
                                <td class="text-center text-success">${gt.TOTAL_MET_SLA}</td>
                                <td class="text-center text-danger">${parseInt(gt.TOTAL_CLOSED) - parseInt(gt.TOTAL_MET_SLA)}</td>
                                <td>${globalMetSLAPercent}%</td>
                            </tr>`;
                    }
                    $('#dept-table-body').html(html1);
                    $('#dept-table-footer').html(footerHtml1);
                } else {
                    $('#dept-table-body').html('<tr><td colspan="9" class="text-center py-4 text-muted">No data discovered for this configuration context.</td></tr>');
                    $('#dept-table-footer').html('');
                }


                let html2 = '';
                let footerHtml2 = '';
                let nonEscalatedTotalCount = 0; 

                if(response && response.non_escalated && response.non_escalated.length > 0) {
                    globalTicketDetails2 = response.nonesca_ticket_details || []; 
                    response.non_escalated.forEach(function(row) {
                        let countVal = parseInt(row.ticket_count) || 0;
                        let percentVal = row.ticket_percentage || '0';
                        nonEscalatedTotalCount += countVal;

                        html2 += `
                            <tr class="dept-row-non-escalated" style="cursor: pointer;" data-dept="${row.DEPARTMENT}">
                                <td><span class="dept-badge-title" style="font-size:13px;">${row.DEPARTMENT}</span></td>
                                <td class="text-center fw-bold" style="font-size:13px;">${countVal}</td>
                                <td class="text-center fw-bold" style="font-size:13px; color:black;">${percentVal}%</td>
                            </tr>`;
                    });

                    footerHtml2 = `
                        <tr style="font-weight: bold; background: #F4E9D7;">
                            <td class="text-dark fw-bold text-uppercase" style="font-size:13px;">TOTAL SUMMARY</td>
                            <td class="text-center text-dark" style="font-size:14px;">${nonEscalatedTotalCount}</td>
                            <td class="text-center text-dark" style="font-size:14px;">100%</td>
                        </tr>`;
                    
                    $('#dept-table-body-non-escalated').html(html2);
                    $('#dept-table-footer-non-escalated').html(footerHtml2);
                } else {
                    $('#dept-table-body-non-escalated').html('<tr><td colspan="3" class="text-center py-4 text-muted">No non-escalated tags found.</td></tr>');
                    $('#dept-table-footer-non-escalated').html('');
                }

                // Initial Load for Transfer Logs
                if(response && response.transfer_logs && response.transfer_logs.length > 0) {
                    globalTransferLogs = response.transfer_logs;
                } else {
                    globalTransferLogs = [];
                }
                currentPageTransfer = 1;
                renderTransferLogs();

                if(response && response.user_login && response.user_login.length > 0) {
                    globalUserLogs = response.user_login;
                } else {
                    globalUserLogs = [];
                }
                currentPageLog = 1;
                renderUserActivityLogs();

            },
           error: function(xhr, status, error) {
    console.error("AJAX Exception Trace:", xhr.responseText, error);
    let errorMessage = "Communications fault encountered.";
    try {
        let serverResponse = JSON.parse(xhr.responseText);
        if (serverResponse.message) {
            errorMessage = "Backend Error: " + serverResponse.message;
        }
    } catch (e) {
        if (xhr.status === 404) {
            errorMessage = "Error 404: 'fetch_department_table.php' was not found. Check the file path!";
        } else if (xhr.responseText) {
            errorMessage = "Raw Server Error: " + xhr.responseText.substring(0, 150) + "...";
        }
    }

    $('#dept-table-body, #dept-table-body-non-escalated, #user-activity-table-body, #transfer-logs-table-body')
        .html(`<tr><td colspan="12" class="text-center text-danger py-4 fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> ${errorMessage}</td></tr>`);
}
        });
    }

    loadDepartmentTable($('#yearpicker').val(), $('#monthpicker').val());
    $('#yearpicker, #monthpicker').on('change', function() {
        loadDepartmentTable($('#yearpicker').val(), $('#monthpicker').val());
    });

    $(document).on('click', '.dept-row', function() {
        const targetedDept = $(this).data('dept');
        $('#modal-dept-name').text(targetedDept);
        $('#modal-dept-year').text("Period: " + $('#yearpicker').val());

        let filteredTickets = globalTicketDetails.filter(t => t.ASSIGNED_DEPARTMENT === targetedDept);
        let modalHtml = '';

        if(filteredTickets.length > 0) {
            filteredTickets.forEach(function(ticket) {
                modalHtml += `<tr>
                    <td>${ticket.TICKET_NO || ''}</td>
                    <td class="text-center">${ticket.STR_CODE || ''}</td>
                    <td class="text-center">${ticket.DATE_CREATED || ''}</td>
                    <td>${ticket.CONCERN || ''}</td>
                    <td class="text-center">${ticket.STATUS || ''}</td>
                    <td>${ticket.CATEGORY || ''}</td>
                    <td>${ticket.ASSIGNED_DEPARTMENT || ''}</td>
                    <td>${ticket.SUB_CATEGORY || ''}</td>
                    <td>${ticket.REMARKS || ''}</td>
                    <td>${ticket.SUBJECT || ''}</td>
                </tr>`;
            });
        } else {
            modalHtml = `<tr><td colspan="10" class="text-center py-3 text-muted">No specific ticket data found.</td></tr>`;
        }
        $('#modal-table-body').html(modalHtml);

        var myModal = new bootstrap.Modal(document.getElementById('deptDetailsModal'));
        myModal.show();
    });

    $(document).on('click', '.dept-row-non-escalated', function() {
        const targetedDept = $(this).data('dept');
        $('#modal-dept-name-non').text(targetedDept);
        $('#modal-dept-year-non').text("Period: " + $('#yearpicker').val());

        let filteredTickets2 = globalTicketDetails2.filter(t => t.ASSIGNED_DEPARTMENT === targetedDept);
        let modalHtml2 = '';

        if(filteredTickets2.length > 0) {
            filteredTickets2.forEach(function(ticket) {
                modalHtml2 += `<tr>
                    <td>${ticket.TICKET_NO || ''}</td>
                    <td class="text-center">${ticket.STR_CODE || ''}</td>
                    <td class="text-center">${ticket.DATE_CREATED || ''}</td>
                    <td class="text-center">${ticket.DAYS_UNRESOLVED || ''}</td>
                    <td class="text-center">${ticket.SLA_DAYS || ''}</td>
                    <td>${ticket.CONCERN || ''}</td>
                    <td class="text-center">${ticket.STATUS || ''}</td>
                    <td>${ticket.CATEGORY || ''}</td>
                    <td>${ticket.ASSIGNED_DEPARTMENT || ''}</td>
                    <td>${ticket.SUB_CATEGORY || ''}</td>
                    <td>${ticket.REMARKS || ''}</td>
                    <td>${ticket.SUBJECT || ''}</td>
                </tr>`;
            });
        } else {
            modalHtml2 = `<tr><td colspan="12" class="text-center py-3 text-muted">No specific ticket data found.</td></tr>`;
        }
        $('#modal-table-body-non-escalated').html(modalHtml2);

        var myModal2 = new bootstrap.Modal(document.getElementById('nonescaTicketDetails'));
        myModal2.show();
    });
});
</script>
</body>
</html>