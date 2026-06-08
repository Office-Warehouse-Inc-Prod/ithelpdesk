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
::-webkit-scrollbar { width: 8px; }
::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.1); border-radius: 10px; }
::-webkit-scrollbar-thumb { background: linear-gradient(135deg, #837031, #E1AD01); border-radius: 10px; }


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

body{
  background:
    linear-gradient(135deg, rgba(18,28,49,.18) 0%, rgba(18,28,49,.18) 12%, transparent 12%) ,
    linear-gradient(315deg, rgba(18,28,49,.18) 0%, rgba(18,28,49,.18) 12%, transparent 12%),
    var(--bg) !important;
  color: var(--text) !important;
}

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
:root {
  --primary-light: #F4F0FF;
  --bg-body: #F4F5FA;
  --sidebar-width: 260px;
  --topbar-height: 70px;
  --card-shadow: 0 4px 12px 0 rgba(58, 53, 65, 0.1);
}

body {
  font-family: "Arial", "Helvetica", sans-serif;
  background-color: var(--bg-body);
  color: #3A3541DE;
  overflow-x: hidden;
  background: linear-gradient(rgba(218, 219, 207, 0.3), rgba(113, 114, 136, 0.27)), url('images/bg_login.png'); 
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  background-repeat: no-repeat;
  min-height: 100vh;
}

/* Navbar Interface Configurations */
.owi-navbar { 
  background-color: #213456 !important; 
  box-shadow: 0 2px 10px 2px #66738e; 
  margin-bottom: 10px; 
}
.owi-navbar .nav-link, .owi-navbar .navbar-brand { 
  color: #fff !important; 
  font-weight: 600; 
  letter-spacing: .3px; 
}
.owi-navbar .nav-link i { 
  margin-right: 6px; 
}
.owi-navbar .nav-link:hover, .owi-navbar .navbar-brand:hover { 
  opacity: .92; 
}
.owi-navbar .dropdown-menu { 
  background-color: #ffffff; 
  border: none; 
  min-width: 220px; 
  padding: .35rem; 
  box-shadow: 0 12px 24px rgba(0,0,0,0.25); 
  border-radius: 12px; 
  border-top: 3px solid var(--primary-color) !important; 
  margin-top: 10px; 
}
.owi-navbar .dropdown-item { 
  color: black; 
  border-radius: 10px; 
  padding: .55rem .75rem;
  white-space: normal; 
}
.owi-navbar .dropdown-item i { 
  margin-right: 8px; 
}
.owi-navbar .dropdown-item:hover { 
  background-color: #54699e; 
  color: #fff; 
}
.owi-navbar .dropdown-divider { 
  border-top: 1px solid rgba(255,255,255,0.2); 
}
.notif-dropdown { 
  width: 360px; 
  max-width: 92vw; 
}

@media (max-width: 576px) { .notif-dropdown { width: 92vw; } }

.owi-navbar .badge-danger { 
  background-color: #ff4d4d; 
}
.owi-navbar .badge-info { 
  background-color: #28c7ff; 
  color: #002a4a; 
  font-weight: 700; 
}
.owi-navbar .navbar-toggler { 
  border-color: rgba(255,255,255,0.35); 
}
.owi-navbar .navbar-toggler-icon { 
  filter: brightness(0) invert(1); 
}

.owi-navbar .nav-item { 
  position: relative; 
  margin: 0 5px; 
  display: flex; 
  align-items: center; 
}
.owi-navbar .nav-link { 
  position: relative; 
  padding: 0.8rem 1rem !important; 
  color: rgba(255, 255, 255, 0.8) !important; 
  transition: all 0.3s ease; 
}
.owi-navbar .nav-link::after { 
  content: ''; 
  position: absolute; 
  width: 0; 
  height: 3px; 
  bottom: 5px; 
  left: 50%; 
  background-color: var(--primary-color); 
  transition: width 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), left 0.3s ease; 
  transform: translateX(-50%); 
  border-radius: 10px; }
.owi-navbar .nav-item:hover .nav-link { 
  color: #fff !important; 
}
.owi-navbar .nav-item:hover .nav-link::after { 
  width: 70%; 
}
.owi-navbar .nav-item.active .nav-link { 
  color: var(--primary-color) !important; 
  font-weight: 700; 
}
.owi-navbar .nav-item.active .nav-link::after { 
  width: 70%; 
  background-color: var(--primary-color); 
}

.navbar-brand { 
  display: flex; 
  align-items: center; 
  gap: 10px; 
  font-family: 'Orbitron', sans-serif; 
  font-size: 1.4rem; 
  letter-spacing: 1px; 
}
.navbar-brand img { 
  transition: transform 0.3s ease; 
}
.navbar-brand:hover img { 
  transform: rotate(-10deg) scale(1.1); 
}

.admin-table { 
  align-items: center;
  background-color: transparent; 
  margin: 40px; 
  padding-left: 90px;
  padding: 40px;
  margin-bottom: 40px;
  width:100%; 
  border-collapse: separate; 
  border-spacing: 0;  box-shadow: 0 10px 20px rgba(221, 221, 93, 0.4);
}
.admin-table th { 
  background-color: #213456; 
  color: #ffffff !important; 
  font-weight: 700; 
  text-transform: uppercase; 
  font-size: 1.78rem; 
  letter-spacing: 0.8px; 
  padding: 16px 24px; 
  border-bottom: 2px solid rgba(0, 0, 0, 0.06); 
}
.admin-table tbody tr { 
  transition: all 0.25s ease; 
  cursor: pointer; 
}
.admin-table tbody tr:hover { 
  
    background-color: #365d845f !important;
  border-bottom: 10px solid #213456 ;
  transform: translateY(-2px); 
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04); 
}
.admin-table td { 
  padding: 18px 24px; 
  vertical-align: middle; 
  color: #2D3748; 
  font-size: 1.95rem;
  border-bottom: 1px solid rgba(0, 0, 0, 0.04); 
}

.dept-badge-title { 
  font-weight: 600; 
  color: #1A202C; 
}

.department-table { 
  align-items: center;
  background-color: transparent; 
  margin: 40px; 
  padding-left: 90px;
  padding: 40px;
  margin-bottom: 40px;
  width:100%; 
  border-collapse: separate; 
  border-spacing: 0;  box-shadow: 0 10px 20px rgba(221, 221, 93, 0.4);
}
.department-table th { 
  background-color: #213456; 
  color: #ffffff !important; 
  font-weight: 700; 
  text-transform: uppercase; 
  font-size: 1.78rem; 
  letter-spacing: 0.8px; 
  padding: 16px 24px; 
  border-bottom: 2px solid rgba(0, 0, 0, 0.06); 
}
.department-table tbody tr { 
  transition: all 0.25s ease; 
  cursor: pointer; 
}
.department-table tbody tr:hover { 
  
    background-color: #365d845f !important;
  border-bottom-color: #213456 solid 3px;
  transform: translateY(-2px); 
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04); 
}
.department-table td { 
  padding: 18px 24px; 
  vertical-align: middle; 
  color: #2D3748; 
  font-size: 1.95rem;
  border-bottom: 1px solid rgba(0, 0, 0, 0.04); 
}
.stat-assigned {  
  color: black; 
}
.stat-onprocess { 
  color: black; 
}
.stat-pending { 
  color: black; }
.stat-total { 
  color: #ffffff; 
  }

  #deptDetailsModal{
    width: 100%;
  }

  
#deptDetailsModal .modal-header {
  background-color: #213456;
  color: #fff;
  border-bottom: 6px solid #E1AD01; /* Your Theme Gold */
}

#deptDetailsModal .modal-title {
  font-weight: 700;
  letter-spacing: 0.5px;
  display: flex;
  align-items: center;
}

#deptDetailsModal .input-group-text {
  background-color: #f8f9fa;
  border-right: none;
  color: #213456;
}

#deptDetailsModal .form-control {
  border-left: none;
  height: 45px;
  border-radius: 0 8px 8px 0;
}

#deptDetailsModal .form-control:focus {
  border-color: #ced4da;
  box-shadow: none;
}

#deptDetailsModal .input-group:focus-within {
  box-shadow: 0 0 0 0.2rem rgba(225, 173, 1, 0.25);
  border-radius: 8px;
}
</style>
<div class="container-fluid">
  <div id="wrapper">
    <div id="layoutSidenav_content">
      <div class="container-fluid py-4">
        
        <form method="post" name="cof_form" id="cof_form" enctype="multipart/form-data">
          <input type="hidden" name="chcksbjcls" id="chcksbjcls" value="check">
        </form>

        <div class="action-bar-container d-flex justify-content-between align-items-center p-3 mb-4 rounded bg-white shadow-sm" style="box-shadow: 0 5px 10px 2px #2d3c597f !important;">
          <div class="year-picker-group">
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0"  style="width: 170px;">
                <i class="fas fa-history me-2 text-muted"></i>LOGS IN YEAR OF:
              </span>
              <select class="form-select border-start-0" name="yearpicker" id="yearpicker" required style="min-width: 150px; border-radius: 0 12px 12px 0 !important;">
                <option value="2019,2020,2021,2022,2023,2024,2025,2026">OVERALL</option>
                <option value="2026" selected>2026</option>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
              </select>
            </div>

             <div class="input-group">
              <span class="input-group-text bg-light border-end-0" style="width: 170px; margin-top: 5px;">
                <i class="fa fa-calendar-check-o" text-muted >   </i>   IN MONTH OF:
              </span>
              <select class="form-select border-start-0" name="monthpicker" id="monthpicker" required style="min-width: 150px; margin-top: 5px; border-radius: 0 12px 12px 0 !important;">
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
            <div class="card card2 border-0 shadow-sm" style="border-radius: 16px; background: rgba(255, 255, 255, 0.85);   box-shadow: 0 10px 20px rgba(90, 90, 37, 0.44);backdrop-filter: blur(10px);">
              <div class="card-header d-flex justify-content-between align-items-center text-dark py-3" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                <h5 class="m-0 font-weight-bold" style="letter-spacing: 0.5px;">
                  <i class="fa-solid fa-circle" style="color: green;"></i> DEPARTMENT TICKET SUMMARY
                 

                </h5>
                    <button style ="align-itmes: right; border-radius:10px; background-color: #E1AD01; color: #213456; padding: 10px;"onclick="exportTableToExcel()"><i class="fa fa-file-excel-o" aria-hidden="true"></i>Export</button>
              </div>
              
              <div class="card-body p-0">
                
                <div class="table-responsive">
                  <table id="admin_report" class="table admin-table m-0">
                    <thead>
                      <tr class="noExl">
                        <th>DEPARTMENT</th>
                        <th class="text-center">ASSIGNED</th>
                        <th class="text-center">ON PROCESS</th>
                        <th class="text-center">PENDING</th>
                        <th class="text-center">TOTAL</th>
                        <th class="text-center" style="width: 200px;">COMPLIANCE RATE</th>
                      </tr>
                    </thead>
                    <tbody id="dept-table-body">
                      <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                          <div class="spinner-border spinner-border-sm me-2 text-warning" role="status"></div> Loading department metrics...
                        </td>
                      </tr>
                    </tbody>
                    <tfoot id="dept-table-footer" class="bg-light" style="border-top: 2px solid #2d3c59;">
                      
                    </tfoot>
                  </table>
                </div>
              </div>
               
             
               
            </div>
          </div>
        </div> 
        
        <div class="col-lg-12 Down mt-3" id="Down">
          <input type="hidden" id="myInput">
        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deptDetailsModal" tabindex="-1" aria-labelledby="deptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 95%;">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(15px);">
      <div class="modal-header border-0 pb-0" style="position: relative; font-size: 40px;">
        <h5 class="modal-title font-weight-bold" id="deptModalLabel">DEPARTMENT OVERVIEW</h5>
         <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <button type="button" class="btn-close m-2" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: 15px; top: 15px; background: none; border: 0; font-size: 1.2rem;"></button>
      </div>
      <div class="modal-body pt-3">
        <div class="d-flex align-items-center mb-4 p-3 rounded-3" style="background: rgba(225, 173, 1, 0.1); border-left: 5px solid #e1ad01;">
          <i class="fas fa-chart-pie fa-2x text-warning me-3" style="margin-right: 10px;"></i>   
          <div>
            <h4 class="m-0 font-weight-bold text-dark" id="modal-dept-name">  Department Name</h4> 
            <small class="text-muted" id="modal-dept-year">  Year Metrics</small>
            <button style ="align-itmes: right; border-radius:10px; background-color: #E1AD01; color: #213456; padding: 10px;"onclick="exportDeptExcel()"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</button>
          </div>
        </div>
        <div class="card-body p-0">
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
              <tbody id="modal-table-body">
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><script>
$(document).ready(function() {
    let globalTicketDetails = [];

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
                        let activeTotal = (parseInt(row.ASSIGNED) || 0) + (parseInt(row.ON_PROCESS) || 0) + (parseInt(row.PENDING) || 0);
                        let compliancePercent = grandTotal > 0 ? Math.round((closedCount / grandTotal) * 100) : 0;
                        
                        let barTheme = "bg-danger";
                        if (compliancePercent >= 75) { barTheme = "bg-success"; }
                        else if (compliancePercent >= 40) { barTheme = "bg-warning"; }

                        html += `
                            <tr class="dept-row" style="cursor: pointer;" data-dept="${row.DEPARTMENT}">
                                <td><span class="dept-badge-title fw-bold" style="color: #213456;">${row.DEPARTMENT}</span></td>
                                <td class="text-center"><span class="stat-badge stat-assigned">${row.ASSIGNED}</span></td>
                                <td class="text-center"><span class="stat-badge stat-onprocess">${row.ON_PROCESS}</span></td>
                                <td class="text-center"><span class="stat-badge stat-pending">${row.PENDING}</span></td>
                                <td class="text-center"><span class="stat-badge stat-total font-weight-bold text-primary">${activeTotal}</span></td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center flex-column" style="padding: 0 10px;">
                                        <div class="d-flex justify-content-between w-100 mb-1 small fw-bold">
                                            <span class="text-dark">${closedCount}/${grandTotal} Closed</span>
                                            <span class="text-dark">${compliancePercent}%</span>
                                        </div>
                                        <div class="progress w-100" style="height: 8px; border-radius: 4px; background-color: rgba(0,0,0,0.06);">
                                            <div class="progress-bar ${barTheme}" role="progressbar" 
                                                 style="width: ${compliancePercent}%; border-radius: 4px;" 
                                                 aria-valuenow="${compliancePercent}" aria-valuemin="0" aria-valuemax="100">
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
                        
                        let totalActiveSum = totalAssigned + totalOnProcess + totalPending;
                        let globalCompliancePercent = totalGrand > 0 ? Math.round((totalClosed / totalGrand) * 100) : 0;

                        let globalBarTheme = "bg-danger";
                        if (globalCompliancePercent >= 75) { globalBarTheme = "bg-success"; }
                        else if (globalCompliancePercent >= 40) { globalBarTheme = "bg-warning"; }

                        footerHtml = `
                            <tr style="background-color: #ecebe584; font-weight: bold; font-size: 2px; border-top: 2px solid #213456;">
                                <td class="text-dark fw-bold text-uppercase" style="letter-spacing: 0px;">TOTAL SUMMARY</td>
                                <td class="text-center text-info">${totalAssigned}</td>
                                <td class="text-center text-warning">${totalOnProcess}</td>
                                <td class="text-center text-danger">${totalPending}</td>
                                <td class="text-center text-primary fw-bold">${totalActiveSum}</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center flex-column" style="padding: 0 10px;">
                                        <div class="d-flex justify-content-between w-100 mb-1 small fw-bold">
                                            <span class="text-muted">${totalClosed}/${totalGrand} Closed</span>
                                            <span class="text-dark">${globalCompliancePercent}%</span>
                                        </div>
                                        <div class="progress w-100" style="height: 8px; border-radius: 4px; background-color: rgba(0,0,0,0.1);">
                                            <div class="progress-bar ${globalBarTheme}" role="progressbar" 
                                                 style="width: ${globalCompliancePercent}%; border-radius: 4px;" 
                                                 aria-valuenow="${globalCompliancePercent}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>`;
                    }

                } else {
                    let errMsg = (response && response.error) ? response.error : "No data discovered for this period context selection.";
                    html = `<tr><td colspan="6" class="text-center py-4 text-muted fw-semibold">${errMsg}</td></tr>`;
                    footerHtml = '';
                }
                
                $('#dept-table-body').html(html);
                $('#dept-table-footer').html(footerHtml);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Exception Trace:", xhr.responseText, error);
                $('#dept-table-body').html('<tr><td colspan="6" class="text-center text-danger py-4 fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Communications fault encountered.</td></tr>');
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
        let rangeText = $('#yearpicker option:selected').text() + ' ' + $('#monthpicker option:selected').text();
        $('#modal-dept-year').text('Logging Metrics Context: ' + rangeText);

        let filteredTickets = globalTicketDetails.filter(ticket => ticket['Assigned Department'] === targetedDept);
        let modalHtml = '';

        if(filteredTickets.length > 0) {
            filteredTickets.forEach(function(ticket) {
                modalHtml += `
                    <tr>
                        <td>${ticket.ticket_no || ''}</td>
                        <td class="text-center">${ticket.str_code || ''}</td>
                        <td class="text-center">${ticket.date_created || ''}</td>
                        <td>${ticket.concern || ''}</td>
                        <td class="text-center">${ticket.status || ''}</span></td>
                        <td>${ticket.category || ''}</td>
                        <td>${ticket['Assigned Department'] || ''}</td>
                        <td>${ticket.sub_category || ''}</td>
                        <td>${ticket.remarks || ''}</td>
                        <td>${ticket.subject || ''}</td>
                    </tr>`;
            });
        } else {
            modalHtml = `<tr><td colspan="11" class="text-center py-4 text-muted">No active issues pending for this department row.</td></tr>`;
        }

        $('#modal-table-body').html(modalHtml);

        if (typeof $.fn.modal === 'function') {
            $('#deptDetailsModal').modal('show');
        } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const systemDetailModal = new bootstrap.Modal(document.getElementById('deptDetailsModal'));
            systemDetailModal.show();
        } else {
            alert("Bootstrap layout core library engine not detected.");
        }
    });
});
 
function exportTableToExcel() {
    let table = document.getElementById("admin_report");
    let headers = table.querySelectorAll("thead th");
    headers.forEach(th => {
        th.setAttribute("data-fill-color", "4B5694"); 
        th.setAttribute("data-font-color", "F5F5F5"); 
        th.setAttribute("data-f-bold", "true");
        th.setAttribute("data-a-h", "center");        
        th.setAttribute("data-a-v", "middle");      
    });

    let bodyRows = table.querySelectorAll("tbody tr");
    bodyRows.forEach(row => {
        let cells = row.querySelectorAll("td");
        cells.forEach((td, index) => {
            td.setAttribute("data-a-v", "middle"); 
            if (td.classList.contains("text-center")) {
                td.setAttribute("data-a-h", "center");
            }
            if (index === 5) { 
                td.setAttribute("data-a-h", "center");
            }
        });
    });

    let footerCells = table.querySelectorAll("tfoot tr td");
    footerCells.forEach(td => {
        td.setAttribute("data-fill-color", "F4AE52"); 
        td.setAttribute("data-f-bold", "true");
        td.setAttribute("data-font-color", "213456"); 
        if (td.classList.contains("text-center")) {
            td.setAttribute("data-a-h", "center");
        }
    });
    const date = new Date();
    const today = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
    TableToExcel.convert(table, {
        name: `HELPDESK TICKET SUMMARY PER DEPARTMENT ${today}.xlsx`,
        sheet: { name: "Ticket Summary" }
    });
}

function exportDeptExcel() {
    let table = document.getElementById("dept-table");
    let headers = table.querySelectorAll("thead th");
    headers.forEach(th => {
        th.setAttribute("data-fill-color", "4B5694"); 
        th.setAttribute("data-font-color", "F5F5F5"); 
        th.setAttribute("data-f-bold", "true");
        th.setAttribute("data-a-h", "center");        
        th.setAttribute("data-a-v", "middle");      
    });

    let bodyRows = table.querySelectorAll("tbody tr");
    bodyRows.forEach(row => {
        let cells = row.querySelectorAll("td");
        cells.forEach((td, index) => {
            td.setAttribute("data-a-v", "middle"); 
            if (td.classList.contains("text-center")) {
                td.setAttribute("data-a-h", "center");
            }
            if (index === 5) { 
                td.setAttribute("data-a-h", "center");
            }
        });
    });

    let footerCells = table.querySelectorAll("tfoot tr td");
    footerCells.forEach(td => {
        td.setAttribute("data-fill-color", "F4AE52"); 
        td.setAttribute("data-f-bold", "true");
        td.setAttribute("data-font-color", "213456"); 
        if (td.classList.contains("text-center")) {
            td.setAttribute("data-a-h", "center");
        }
    });
    const date = new Date();
    const today = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
    TableToExcel.convert(table, {
        name: `DEPARTMENT ACTIVE TICKETS REPORT ${today}.xlsx`,
        sheet: { name: "Ticket Summary" }
    });
}
</script>