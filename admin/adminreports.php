<?php
include 'admin.php';
include 'main_js.php';
include '../condb.php';
?>

<head>
  <link rel="stylesheet" href="adminpanel.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" />
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@linways/table-to-excel@1.0.4/dist/tableToExcel.min.js"></script>
</head>

<style>
/* Custom Scrollbar System Styling */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.05); border-radius: 10px; }
::-webkit-scrollbar-thumb { background: linear-gradient(135deg, #837031, #E1AD01); border-radius: 10px; }

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
  font-family: "Arial", "Helvetica", sans-serif;
  background-color: var(--bg-body);
  color: #3A3541DE;
  min-height: 100vh;
  margin: 0;
  padding: 0;
}

.navbar, header, .topbar, .navbar-default {
  background: var(--navy) !important;
  border-color: rgba(255,255,255,.10) !important;
}

/* Action Filters Compact Layout */
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

/* Print-Only Header Block Details */
#print-filter-badge-header {
  display: none;
}

/* Table Architecture Optimizations for One-Page Output */
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
.dept-badge-title { font-weight: 700; color: #1A202C; }

/* Department Modal Table elements */
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

  .action-bar-container, 
  .card-header .d-flex, 
  .btn, 
  #layoutSidenav_nav, 
  nav, 
  header, 
  .owi-navbar,
  .noExl,
  .no-print-header { 
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
    margin-top: -30px;
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

  #admin_report.admin-table th.active.text-center {
    background-color: #2b9827 !important;
    color: #fff !important;
    padding: 6px 4px !important;
    font-size: 11px !important;
  }

  #admin_report.admin-table th.compliance.text-center {
    background-color: #a29341 !important;
    color: #fff !important;
    padding: 6px 4px !important;
    font-size: 11px !important;
  }

  .admin-table td {
    padding: 6px 4px !important;
    font-size: 11px !important;
    border-bottom: 1px solid #0e0e0ea1 !important;
  }
  .table-responsive{
    margin-top: -600px;
  }

  .progress {
    border: 1px solid #999 !important;
    background-color: #ddd !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }
  #dept-table-footer {
    border: 2px solid #2d3c59;
    background-color: #f4e9d7 !important; /* !important ensures it overrides Bootstrap's .bg-light */
}

  * {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
    color-adjust: exact !important;
    box-shadow: none !important;
  }
}
</style>

<div class="container-fluid" id="printTicketReport">
  <div id="wrapper">
    <div id="layoutSidenav_content">
      <div class="container-fluid py-2">
        
        <form method="post" name="cof_form" id="cof_form" enctype="multipart/form-data">
          <input type="hidden" name="chcksbjcls" id="chcksbjcls" value="check">
        </form>

        <div id="print-filter-badge-header">
          <div class="header" style="font-size: 25px; display: flex; align-items: center; justify-content: center; text-align: center;">
            OFFICE WAREHOUSE INC
          </div>

          <div class="header" style="font-size: 15px; display: flex; align-items: center; justify-content: center; text-align: center;">
            169 E.Rodriguez Jr. Ave., Brgy. Bagumbayan, Quezon City
          </div>
          <div class="header" style="font-size: 25px; margin-top:10px; display: flex; align-items: center; justify-content: center; text-align: center;">
            HELPDESK - DEPARTMENT TICKET SUMMARY
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

        <div class="row">
          <div class="col-12">
            <div class="card card2 border-0 shadow-sm" style="border-radius: 12px; background: rgba(255, 255, 255, 0.95);">
              
              <div class="card-header d-flex flex-wrap justify-content-between align-items-center text-dark py-2 gap-2" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                <h5 class="m-0 font-weight-bold no-print-header" style="letter-spacing: 0.3px; font-size: 1.15rem;">
                  <i class="fa-solid fa-circle text-success me-2"></i> HELPDESK DEPARTMENT TICKET SUMMARY
                </h5>
                <div class="d-flex gap-2">
                  <button type="button" class="btn text-white d-flex align-items-center gap-2" style="border-radius: 6px; font-size: 13px; background-color: #12922a; padding: 4px 12px;" onclick="window.print()">
                    <i class="fa fa-print" aria-hidden="true"></i> Print
                  </button>
                  <button type="button" class="btn text-dark d-flex align-items-center gap-2" style="border-radius: 6px; font-size: 13px; background-color: #E1AD01; padding: 4px 12px;" onclick="exportTableToExcel()">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export
                  </button>
                </div>
              </div>
              
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table id="admin_report" class="table admin-table m-0">
                    <thead>  
                      <tr>
                        <th colspan="5" class="active text-center" style="background-color: #2b9827; color: #fff; font-weight: 700; text-transform: uppercase; font-size: 13px; padding: 6px;">
                          ACTIVE/RUNNING TICKET REPORTS
                        </th>
                        <th colspan="4" class="compliance text-center" style="background-color: #a29341; color: #fff; font-weight: 700; text-transform: uppercase; font-size: 13px; padding: 6px;">
                          COMPLIANCE TICKET REPORTS
                        </th>
                      </tr>
                      
                      <tr style="background-color: #213456; color: #ffffff;">
                        <th style="background-color: #213456; font-size: 12px; vertical-align: middle;">DEPARTMENT</th>
                        <th class="text-center fw-bold" style="background-color: #213456; font-size: 12px; vertical-align: middle;">ASSIGNED</th>
                        <th class="text-center fw-bold" style="background-color: #213456; font-size: 12px; vertical-align: middle;">ON PROCESS</th>
                        <th class="text-center" style="background-color: #213456; font-size: 12px; vertical-align: middle;">PENDING</th>
                        <th class="text-center" style="background-color: #213456; font-size: 12px; vertical-align: middle;">TOTAL (ACTIVE)</th>
                        <th class="text-center" style="background-color: #213456; font-size: 12px; vertical-align: middle;">COMPLIANCE RATE</th>
                        <th class="text-center" style="background-color: #213456; font-size: 12px; vertical-align: middle;">MET SLA</th>
                        <th class="text-center" style="background-color: #213456; font-size: 12px; vertical-align: middle;">NON-SLA</th>
                        <th class="text-center" style="background-color: #213456; font-size: 12px; vertical-align: middle;">SLA COMPLIANCE</th>
                      </tr>
                    </thead>
                    
                    <tbody id="dept-table-body">
                      <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                          <div class="spinner-border spinner-border-sm me-2 text-warning" role="status"></div> 
                          Loading department metrics...
                        </td>
                      </tr>
                    </tbody>
                    
                    <tfoot id="dept-table-footer" class="bg-light" style="border: 2px solid #2d3c59; background: #F4E9D7;">
                    </tfoot>
                  </table>

                 <div class="legend-container" style="font-size: 15px; margin-top: 10px; font-family: sans-serif;">
                    <div style="font-weight: bold; margin-bottom: 8px;">LEGEND:</div>
                    
                    <table style="border-collapse: collapse; border: none; width: 100%; max-width: 600px;">
                      <tr>
                        <td style="border: none; padding: 0 20px 0 0; vertical-align: top; width: 50%;">
                          <div style="font-weight: bold; margin-bottom: 5px;">COMPLIANCE RATE:</div>
                          <div style="display: flex; align-items: center; margin-bottom: 4px;">
                            <i class="fas fa-square" style="color:green; font-size:24px; margin-right: 8px;"></i> 75% - 100%
                          </div>
                          <div style="display: flex; align-items: center; margin-bottom: 4px;">
                            <i class="fas fa-square" style="color:yellow; font-size:24px; margin-right: 8px;"></i> 40% - 74%
                          </div>
                          <div style="display: flex; align-items: center;">
                            <i class="fas fa-square" style="color:red; font-size:24px; margin-right: 8px;"></i> 0% - 39%
                          </div>
                        </td>

                        <td style="border: none; padding: 0; vertical-align: top; width: 50%;">
                          <div style="font-weight: bold; margin-bottom: 5px;">SLA COMPLIANCE RATE:</div>
                          <div style="display: flex; align-items: center; margin-bottom: 4px;">
                            <i class="fas fa-square" style="color:green; font-size:24px; margin-right: 8px;"></i> 80% - 100%
                          </div>
                          <div style="display: flex; align-items: center; margin-bottom: 4px;">
                            <i class="fas fa-square" style="color:yellow; font-size:24px; margin-right: 8px;"></i> 50% - 79%
                          </div>
                          <div style="display: flex; align-items: center;">
                            <i class="fas fa-square" style="color:red; font-size:24px; margin-right: 8px;"></i> 0% - 49%
                          </div>
                        </td>
                      </tr>
                    </table>
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
        <h5 class="modal-title font-weight-bold" id="deptModalLabel" style="font-size:1.5rem;">DEPARTMENT OVERVIEW</h5>
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
            <div class="d-flex gap-2">
              <button class="btn btn-sm text-dark" style="border-radius:6px; background-color: #E1AD01;" onclick="exportDeptExcel()"><i class="fa fa-file-excel-o"></i> Excel</button>
              <button class="btn btn-sm text-white" style="border-radius:6px; background-color: #1ea871;" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table id="dept-table" class="table department-table m-0">
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

<script>
$(document).ready(function() {
    let globalTicketDetails = [];

    // Captures live select names dynamically before printing triggers
    window.onbeforeprint = function() {
        let yrText = $("#yearpicker option:selected").text();
        let moText = $("#monthpicker option:selected").text();
        $("#print-year-val").text(yrText);
        $("#print-month-val").text(moText);
    };

    $('.dashcard-clickable').on('click', function() {
        const filterValue = $(this).data('filter');
        if ($.fn.DataTable.isDataTable('#report_data')) {
            $('#report_data').DataTable().search(filterValue).draw();
        }
        $('html, body').animate({
            scrollTop: $("#report_data").offset().top - 100
        }, 600);
        $(this).fadeOut(100).fadeIn(100);
    });

    function loadDepartmentTable(selectedYear, selectedMonth) {
        let displayYear = selectedYear.includes(',') ? 'OVERALL' : selectedYear;
        let displayMonthText = selectedMonth ? " | Month: " + selectedMonth : "";
        $('#table-year-indicator').text('Period: ' + displayYear + displayMonthText);

        let currentPath = window.location.pathname;
        let dynamicDirectory = currentPath.substring(0, currentPath.lastIndexOf('/')) + '/';
        let targetedURL = window.location.origin + dynamicDirectory + 'fetch_department_table.php';
        
        $.ajax({
            url: targetedURL, 
            method: 'POST',
            data: { 
                yr: selectedYear,
                mo: selectedMonth 
            },
            dataType: 'json',
            success: function(response) {
                let html = '';
                let footerHtml = '';
                
                if(response && response.department_stats && response.department_stats.length > 0) {
                    globalTicketDetails = response.ticket_details || []; 
                    response.department_stats.forEach(function(row) {
                        let closedCount = parseInt(row.CLOSED) || 0;
                        let grandTotal = parseInt(row.GRAND_TOTAL) || 0;
                        let metSlaCount = parseInt(row.MET_SLA) || 0;
                        let activeTotal = (parseInt(row.ASSIGNED) || 0) + (parseInt(row.ON_PROCESS) || 0) + (parseInt(row.PENDING) || 0);
                        let compliancePercent = grandTotal > 0 ? Math.round((closedCount / grandTotal) * 100) : 0;
                        let metSLA = closedCount > 0 ? Math.round((metSlaCount / closedCount) * 100) : 0;

                        let barTheme = "bg-danger";
                        if (compliancePercent >= 75) { barTheme = "bg-success"; }
                        else if (compliancePercent >= 40) { barTheme = "bg-warning"; }

                        let metBarTheme = "bg-danger";
                        if (metSLA >= 80) { metBarTheme = "bg-success"; }
                        else if (metSLA >= 50) { metBarTheme = "bg-warning"; }

                        html += `
                            <tr class="dept-row" style="cursor: pointer;" data-dept="${row.DEPARTMENT}">
                                <td><span class="dept-badge-title" style="font-size:13px;">${row.DEPARTMENT}</span></td>
                                <td class="text-center fw-bold" style="font-size:13px;"><span class="stat-badge stat-assigned">${row.ASSIGNED}</span></td>
                                <td class="text-center fw-bold" style="font-size:13px;"><span class="stat-badge stat-onprocess">${row.ON_PROCESS}</span></td>
                                <td class="text-center fw-bold" style="font-size:13px;"><span class="stat-badge stat-pending">${row.PENDING}</span></td>
                                <td class="text-center fw-bold" style="font-size:13px;"><span class="stat-badge stat-total text-primary">${activeTotal}</span></td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center flex-column" style="padding: 0 4px;">
                                        <div class="d-flex justify-content-between w-100 mb-1 small fw-bold">
                                            <span class="text-dark" style="font-size:12px;">${closedCount}/${grandTotal}</span>
                                            <span class="text-dark" style="font-size:12px;">${compliancePercent}%</span>
                                        </div>
                                        <div class="progress w-100" style="height: 6px; border-radius: 4px; background-color: rgba(0,0,0,0.06);">
                                            <div class="progress-bar ${barTheme}" role="progressbar" 
                                                 style="width: ${compliancePercent}%; border-radius: 4px;" 
                                                 aria-valuenow="${compliancePercent}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-bold text-success" style="font-size:13px;">${metSlaCount}</td>
                                <td class="text-center fw-bold text-danger" style="font-size:13px;">${closedCount - metSlaCount}</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center flex-column" style="padding: 0 4px;">
                                        <div class="d-flex justify-content-between w-100 mb-1 small fw-bold">
                                            <span class="text-dark fw-bold" style="font-size:12px;">${metSlaCount}/${closedCount}</span>
                                            <span class="text-dark fw-bold" style="font-size:12px;">${metSLA}%</span>
                                        </div>
                                        <div class="progress w-100" style="height: 6px; border-radius: 4px; background-color: rgba(0,0,0,0.06);">
                                            <div class="progress-bar ${metBarTheme}" role="progressbar" 
                                                 style="width: ${metSLA}%; border-radius: 4px;" 
                                                 aria-valuenow="${metSLA}" aria-valuemin="0" aria-valuemax="100">
                                            </div>  
                                        </div>
                                    </div>
                                </td>
                            </tr>`;
                    });

                    if(response.global_totals) {
                        let gt = response.global_totals;
                        let totalAssigned = parseInt(gt.TOTAL_ASSIGNED) || 0;
                        let totalOnProcess = parseInt(gt.TOTAL_ON_PROCESS) || 0;
                        let totalPending = parseInt(gt.TOTAL_PENDING) || 0;
                        let totalClosed = parseInt(gt.TOTAL_CLOSED) || 0;
                        let totalGrand = parseInt(gt.OVERALL_GRAND_TOTAL) || 0;
                        let totalMetSLA = parseInt(gt.TOTAL_MET_SLA) || 0;
                        
                        let totalActiveSum = totalAssigned + totalOnProcess + totalPending;
                        let globalCompliancePercent = totalGrand > 0 ? Math.round((totalClosed / totalGrand) * 100) : 0;
                        let globalMetSLAPercent = totalClosed > 0 ? Math.round((totalMetSLA / totalClosed) * 100) : 0;

                        let globalBarTheme = "bg-danger";
                        if (globalCompliancePercent >= 75) { globalBarTheme = "bg-success"; }
                        else if (globalCompliancePercent >= 40) { globalBarTheme = "bg-warning"; }

                        let globalMetSLABarTheme = "bg-danger";
                        if (globalMetSLAPercent >= 80) { globalMetSLABarTheme = "bg-success"; }
                        else if (globalMetSLAPercent >= 50) { globalMetSLABarTheme = "bg-warning"; }

                        footerHtml = `
                            <tr style="background-color: #ecebe584; font-weight: bold; border-top: 2px solid #213456;">
                                <td class="text-dark fw-bold text-uppercase" style="font-size:13px;border-bottom: 1px solid #0e0e0ea1 !important;">TOTAL SUMMARY</td>
                                <td class="text-center" style="font-size:14px;">${totalAssigned}</td>
                                <td class="text-center" style="font-size:14px;">${totalOnProcess}</td>
                                <td class="text-center" style="font-size:14px;">${totalPending}</td>
                                <td class="text-center text-primary fw-bold" style="font-size:14px;">${totalActiveSum}</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center flex-column" style="padding: 0 4px;">
                                        <div class="d-flex justify-content-between w-100 mb-1 small fw-bold">
                                            <span class="text-dark" style="font-size:13px;">${totalClosed}/${totalGrand}</span>
                                            <span class="text-dark" style="font-size:13px;">${globalCompliancePercent}%</span>
                                        </div>
                                        <div class="progress w-100" style="height: 6px; border-radius: 4px; background-color: rgba(0,0,0,0.1);">
                                            <div class="progress-bar ${globalBarTheme}" role="progressbar" 
                                                 style="width: ${globalCompliancePercent}%; border-radius: 4px;" 
                                                 aria-valuenow="${globalCompliancePercent}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center text-success" style="font-size:14px;">${totalMetSLA}</td>
                                <td class="text-center fw-bold text-danger" style="font-size:14px;">${totalClosed - totalMetSLA}</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center flex-column" style="padding: 0 4px;">
                                        <div class="d-flex justify-content-between w-100 mb-1 small fw-bold">
                                            <span class="text-dark" style="font-size:13px;">${totalMetSLA}/${totalClosed}</span>
                                            <span class="text-dark" style="font-size:13px;">${globalMetSLAPercent}%</span>     
                                        </div>
                                        <div class="progress w-100" style="height: 6px; border-radius: 4px; background-color: rgba(0,0,0,0.1);">
                                            <div class="progress-bar ${globalMetSLABarTheme}" role="progressbar" 
                                                 style="width: ${globalMetSLAPercent}%; border-radius: 4px;" 
                                                 aria-valuenow="${globalMetSLAPercent}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </td>  
                            </tr>`;
                    }
                } else {
                    let errMsg = (response && response.error) ? response.error : "No data discovered for this period context selection.";
                    html = `<tr><td colspan="9" class="text-center py-4 text-muted fw-semibold">${errMsg}</td></tr>`;
                    footerHtml = '';
                }
                
                $('#dept-table-body').html(html);
                $('#dept-table-footer').html(footerHtml);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Exception Trace:", xhr.responseText, error);
                $('#dept-table-body').html('<tr><td colspan="9" class="text-center text-danger py-4 fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Communications fault encountered.</td></tr>');
                $('#dept-table-footer').html('');
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
        let rangeText = $('#yearpicker').val();
        $('#modal-dept-year').text("Period: " + rangeText);
    });
});


    var currentUrl = window.location.pathname.split("/").pop();
    if (currentUrl === "" || currentUrl === "index.php") {
      currentUrl = "adminreports.php";
    }

    $('.navbar-nav .nav-item').each(function () {
      var $this = $(this);
      var linkHref = $this.find('a').attr('href');
      $this.removeClass('active');

      if (linkHref === currentUrl) {
        $this.addClass('active');
      }

      if ($this.hasClass('dropdown')) {
        $this.find('.dropdown-item').each(function () {
          if ($(this).attr('href') === currentUrl) {
            $this.addClass('active');
          }
        });
      }
    });
</script>