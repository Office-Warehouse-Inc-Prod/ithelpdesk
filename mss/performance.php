<?php
include 'admin.php';
include 'main_js.php';
include '../condb.php';


  date_default_timezone_set('Asia/Manila'); 
  
  $ph_datetime = date('l, F d, Y - h:i A'); 
?>

<head>
  <link rel="stylesheet" href="adminpanel.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" />
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@linways/table-to-excel@1.0.4/dist/tableToExcel.min.js"></script>
</head>

<style>
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.05); border-radius: 10px; }
::-webkit-scrollbar-thumb { background: linear-gradient(135deg, #837031, #E1AD01); border-radius: 10px; }

 :root {
    --primary-color: #E1AD01;
    --primary-light: #F4F0FF;
    --bg-body: #F4F5FA;
    --sidebar-width: 260px;
    --topbar-height: 70px;
    --card-shadow: 0 4px 12px 0 rgba(58, 53, 65, 0.1);
  }


body {
  background: linear-gradient(to bottom, #ffffff, #99aac8);
  background-attachment: fixed; 
  margin: 0; 
  height: 100vh; 
} 
  .owi-navbar {
    background-color: #213456 !important;
    box-shadow: 0 2px 10px 2px #66738e;
    margin-bottom: 10px;
  }

  .owi-navbar .nav-link,
  .owi-navbar .navbar-brand {
    color: #fff !important;
    font-weight: 600;
    letter-spacing: .3px;
  }

  .owi-navbar .nav-link i {
    margin-right: 6px;
  }

  .owi-navbar .nav-link:hover,
  .owi-navbar .navbar-brand:hover {
    opacity: .92;
  }

  .owi-navbar .dropdown-menu {
    background-color: #ffffff;
    border: none;
    min-width: 220px;
    padding: .35rem;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
    border-radius: 12px;
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
    border-top: 1px solid rgba(255, 255, 255, 0.2);
  }

  .notif-dropdown {
    width: 360px;
    max-width: 92vw;
  }

  @media (max-width: 576px) {
    .notif-dropdown {
      width: 92vw;
    }
  }

  .owi-navbar .badge-danger {
    background-color: #ff4d4d;
  }

  .owi-navbar .badge-info {
    background-color: #28c7ff;
    color: #002a4a;
    font-weight: 700;
  }

  .owi-navbar .navbar-toggler {
    border-color: rgba(255, 255, 255, 0.35);
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
    border-radius: 10px;
  }

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

  .owi-navbar .dropdown-menu {
    border-top: 3px solid var(--primary-color) !important;
    border-radius: 0 0 8px 8px !important;
    margin-top: 0;
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

  .owi-navbar .dropdown-menu {
    border-top: 3px solid var(--primary-color);
    margin-top: 10px;
  }

  /* Smooth flash styling for highlighting rows */
  .highlight-row {
    animation: flashYellow 2.5s ease-in-out;
  }

  @keyframes flashYellow {
    0% { background-color: #ffff99; }
    100% { background-color: transparent; }
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
.card{
  padding: 5px;
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
    margin-top: -60px;
    padding: -20px;
    border-radius: 0;
    width:79%;
  }

  .table-responsive {
    overflow: visible !important;
    width: 100% !important;
  }

  .admin-table {
    width: 80% !important;
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
    background-color: #f4e9d7 !important; 
}

  * {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
    color-adjust: exact !important;
    box-shadow: none !important;
  }
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
    background-color: #f4e9d7 !important; 
}

  * {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
    color-adjust: exact !important;
    box-shadow: none !important;
  }
}
.card2{
    margin-left:10%;
    margin-right:10%;
    padding:10px;
}
.modal-overlay .close-btn{
  border-radius: 12px;
  margin-top: 40px;
  width: 30%;
  background-color: #156436;
  color: white;
}

.modal-overlay  .close-btn:hover{
  border-radius: 12px;
  width: 30%;
  background-color: #E1AD01;
  color: black;
}
.month-row[data-month="6"] {
  background: #213456;
  outline: 2px solid red;
  outline-offset: -2px; 
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
          <div class="header" style="font-size: 25px; margin-top:6px; display: flex; align-items: center; justify-content: center; text-align: center;">
            IT HELPDESK TICKET SUMMARY
          </div>
          <div class="datetime-container" style=" margin-top:1px; display: flex; align-items: center; justify-content: center; text-align: center;">
            <p>As of: <strong><?php echo $ph_datetime; ?></strong></p>
          </div>

          <!--<table style="width: 100%; margin-top:10px; font-family: sans-serif;padding:60px; box-shadow: 0 6px 18px rgba(0, 0, 0, 0.6);color: #121C31;">
            <tr>
               
              <td style="text-align: right; font-size: 13px; border:none; padding:0;">
                <strong>LOGS IN YEAR OF:</strong> <span id="print-year-val">--</span> &nbsp;|&nbsp; 
                <strong>FILTER BY MONTH:</strong> <span id="print-month-val">--</span>
              </td>
            </tr>
          </table>-->
        </div>

       

        </div>

        <div class="row">
          <div class="col-12">
            <div class="card card2 border-0 shadow-sm" style="border-radius: 12px; background: rgba(255, 255, 255, 0.95);">
              
              <div class="card-header d-flex flex-wrap justify-content-between align-items-center text-dark py-2 gap-2" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                <h5 class="m-0 font-weight-bold no-print-header" style="letter-spacing: 0.3px; font-size: 1.15rem;">
                  <i class="fa-solid fa-circle text-success me-2"></i>MSSD HELPDESK DEPARTMENT TICKET SUMMARY
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
                       
                        <th colspan="6" class="compliance text-center" style="background-color: #a29341; color: #fff; font-weight: 700; text-transform: uppercase; font-size: 13px; padding: 6px;">
                          COMPLIANCE TICKET REPORTS
                        </th>
                      </tr>
                      
                      <tr style="background-color: #213456; color: #ffffff;">
                         <th style="background-color: #213456; font-size: 12px; vertical-align: middle;">MONTH</th>
                        <th class="text-center" style="background-color: #213456; font-size: 12px; vertical-align: middle;">ACTIVE TICKETS</th>
                        <th class="text-center" style="background-color: #213456; font-size: 12px; vertical-align: middle;">COMPLIANCE RATE</th>
                        <th class="text-center" style="background-color: #213456; font-size: 12px; vertical-align: middle;">MET SLA</th>
                        <th class="text-center" style="background-color: #213456; font-size: 12px; vertical-align: middle;">NON-SLA</th>
                        <th class="text-center" style="background-color: #213456; font-size: 12px; vertical-align: middle;">SLA COMPLIANCE</th>
                      </tr>
                    </thead>
                    
                    <tbody id="dept-table-body">
                      <tr class ="month-row" data-month="1">
                        <td class="fw-bold">JANUARY</td>
                        <td class="text-center active-total">-</td>
                        <td class="compliance-rate">-</td>
                        <td class="text-center met-sla">-</td>
                        <td class="text-center non-sla">-</td>
                        <td class="sla-compliance">-</td>
                      </tr>
                      <tr class ="month-row" data-month="2">
                        <td class="fw-bold">FEBRUARY</td>
                        <td class="text-center active-total">-</td>
                        <td class="compliance-rate">-</td>
                        <td class="text-center met-sla">-</td>
                        <td class="text-center non-sla">-</td>
                        <td class="sla-compliance">-</td>
                      </tr>
                      <tr class ="month-row" data-month="3">
                        <td class="fw-bold">MARCH</td>
                        <td class="text-center active-total">-</td>
                        <td class="compliance-rate">-</td>
                        <td class="text-center met-sla">-</td>
                        <td class="text-center non-sla">-</td>
                        <td class="sla-compliance">-</td>
                      </tr>
                      <tr class ="month-row" data-month="4">
                        <td class="fw-bold">APRIL</td>
                        <td class="text-center active-total">-</td>
                        <td class="compliance-rate">-</td>
                        <td class="text-center met-sla">-</td>
                        <td class="text-center non-sla">-</td>
                        <td class="sla-compliance">-</td>
                      </tr>
                      <tr class ="month-row" data-month="5">
                        <td class="fw-bold">MAY</td>
                        <td class="text-center active-total">-</td>
                        <td class="compliance-rate">-</td>
                        <td class="text-center met-sla">-</td>
                        <td class="text-center non-sla">-</td>
                        <td class="sla-compliance">-</td>
                      </tr>
                      <tr class ="month-row" data-month="6" style="background: #213456; border-outline: 2px solid red;">
                        <td class="fw-bold" >JUNE</td>
                        <td class="text-center active-total">-</td>
                        <td class="compliance-rate">-</td>
                        <td class="text-center met-sla">-</td>
                        <td class="text-center non-sla">-</td>
                        <td class="sla-compliance">-</td>
                      </tr>
                      
                    </tbody>
                  <tfoot id="dept-table-footer"></tfoot>
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
        // If we are already on adminpanel.php, open the modal directly
        if (window.location.pathname.endsWith('adminpanel.php') || window.location.pathname.endsWith('/it/')) {
          e.preventDefault();
          $('#createReportModal').modal({ backdrop: 'static', keyboard: false });
        }
      });

      // Handle query param create=true on load
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.get('create') === 'true') {
        $('#createReportModal').modal({ backdrop: 'static', keyboard: false });
        // Clean up url parameters without reloading
        window.history.replaceState({}, document.title, window.location.pathname);
      }

      // Reset Form when Modal Closes or Opens
      $('#createReportModal').on('show.bs.modal', function () {
        $('#create_report_form').trigger('reset');
        $('#create_store').val('201'); // Auto-select CEN | CENTRAL OFFICE - LIBIS
        $('#create_subject').val(null).trigger('change');
        $('#create_sub').val(null).trigger('change');
        $('#create_sub_group').hide();
        $('#create_ticket_lbl').text('---');
        $('#create_ticket_no').val('');
      });

      // Populate dynamic categories and fetch ticket numbers when Attention To Department changes
      $("#create_deptsel").on("change", function () {
        $('#create_subject').val(null).trigger('change');
        $('#create_sub').val(null).trigger('change');
        $('#create_sub_group').hide();
        let val = $(this).val();

        $("#create_subject").select2({
          dropdownParent: $('#createReportModal'),
          width: '100%',
          minimumResultsForSearch: Infinity, // Disable search box
          ajax: {
            url: "../users/select.php",
            type: "get",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                type: 'category_id',
                val: val
              };
            },
            processResults: function (response) {
              return {
                results: response
              };
            },
            cache: true
          }
        });

        // Dynamic Ticket Number Generation Fetch
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
        if (!category_id) {
          $('#create_sub_group').hide();
          return;
        }
        
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

      // Validate uploaded file size and extensions
      $('#create_file-input').on('change', function () {
        for (var i = 0; i < this.files.length; ++i) {
          var file = this.files[i];
          if (file.size > 2097152) { // 2MB
            Swal.fire({
              icon: 'error',
              title: 'File Too Large',
              text: 'File "' + file.name + '" must not exceed 2MB.'
            });
            this.value = "";
            return false;
          }
          var ext = file.name.split('.').pop().toLowerCase();
          var validExtensions = ['jpg', 'jpeg', 'gif', 'png', 'txt', 'pdf', 'docx', 'doc', 'xlsx', 'xls'];
          if ($.inArray(ext, validExtensions) === -1) {
            Swal.fire({
              icon: 'error',
              title: 'Invalid File Type',
              text: 'File "' + file.name + '" has an invalid extension.'
            });
            this.value = "";
            return false;
          }
        }
      });

      // Handle AJAX Submission of Department Ticket
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
            $.LoadingOverlay("show", {
              image: "",
              background: "rgba(0, 0, 0, 0.45)"
            });
          },
          success: function (response) {
            $.LoadingOverlay("hide");
            $('#create_action').prop('disabled', false);

            if (response.Response) {
              // Upload files if selected
              var files = $('#create_file-input')[0].files;
              if (files.length > 0) {
                var fileData = new FormData();
                for (var i = 0; i < files.length; i++) {
                  fileData.append('files[]', files[i]);
                }
                fileData.append('ticket_no', response.ticket_no);

                $.ajax({
                  type: "POST",
                  url: "insertimg.php",
                  data: fileData,
                  processData: false,
                  contentType: false
                });
              }

              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Report successfully submitted to OWI HELPDESK.',
                timer: 2000,
                showConfirmButton: false
              }).then(function () {
                $('#createReportModal').modal('hide');
                if (typeof getdata === 'function') {
                  const currentYr = $("#yearpicker").val();
                  getdata(currentYr);
                }
                if (typeof get_card_data === 'function') {
                  const currentYr = $("#yearpicker").val();
                  get_card_data(currentYr);
                }
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                html: response.m
              });
            }
          },
          error: function () {
            $.LoadingOverlay("hide");
            $('#create_action').prop('disabled', false);
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'An unexpected error occurred while saving the report.'
            });
          }
        });
      });
    });



  </script>

  
<script src="https://jquery.com"></script>
<script>
$(document).ready(function() {
    const $modal = $('#welcomeModal');
    const $closeBtn = $('#closeModalBtn');

    const shouldShowModal = localStorage.getItem('showWelcomeModal') === 'true';
    if (shouldShowModal) {
        $modal.css('display', 'flex'); 
        localStorage.removeItem('showWelcomeModal');
    }
    $closeBtn.on('click', function() {
        $modal.css('display', 'none');
    });
    loadDepartmentTable();
});

function loadDepartmentTable() {

    let currentPath = window.location.pathname;
    let dynamicDirectory = currentPath.substring(0, currentPath.lastIndexOf('/')) + '/';
    let targetedURL = window.location.origin + dynamicDirectory + 'fetch_department_table.php';
    
    $('#dept-table-body').html('<tr><td colspan="6" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm me-2 text-warning"></div>Loading Performance Metrics...</td></tr>');

    $.ajax({
        url: targetedURL, 
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            let html = '';
            let footerHtml = '';
        
            const monthNames = {
                1: "JANUARY", 2: "FEBRUARY", 3: "MARCH", 
                4: "APRIL", 5: "MAY", 6: "JUNE"
            };

            if (response && response.department_stats) {
                globalTicketDetails = response.ticket_details || []; 
                
                let statsByMonth = {};
                response.department_stats.forEach(function(row) {
                    statsByMonth[parseInt(row.MONTH_NUM)] = row;
                });

                for (let m = 1; m <= 6; m++) {
                    let row = statsByMonth[m];
                    let monthName = monthNames[m];

                    let closedCount = row ? (parseInt(row.CLOSED) || 0) : 0;
                    let grandTotal = row ? (parseInt(row.GRAND_TOTAL) || 0) : 0;
                    let metSlaCount = row ? (parseInt(row.MET_SLA) || 0) : 0;
                    let activeTotal = row ? ((parseInt(row.ASSIGNED) || 0) + (parseInt(row.ON_PROCESS) || 0) + (parseInt(row.PENDING) || 0)) : 0;
                    
                    let compliancePercent = grandTotal > 0 ? Math.round((closedCount / grandTotal) * 100) : 0;
                    let metSLA = closedCount > 0 ? Math.round((metSlaCount / closedCount) * 100) : 0;

                    let barTheme = compliancePercent >= 75 ? "bg-success" : (compliancePercent >= 40 ? "bg-warning" : "bg-danger");
                    let metBarTheme = metSLA >= 80 ? "bg-success" : (metSLA >= 50 ? "bg-warning" : "bg-danger");

                    html += `
                        <tr class="dept-row" style="cursor: pointer;" data-month="${m}">
                            <td class="fw-bold" style="font-size:13px; vertical-align: middle;">${monthName}</td>
                            <td class="text-center fw-bold" style="font-size:13px; vertical-align: middle;">
                                <span class="stat-badge stat-total text-primary">${activeTotal}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center flex-column" style="padding: 0 4px;">
                                    <div class="d-flex justify-content-between w-100 mb-1 small fw-bold">
                                        <span class="text-dark" style="font-size:12px;">${closedCount}/${grandTotal}</span>
                                        <span class="text-dark" style="font-size:12px;">${compliancePercent}%</span>
                                    </div>
                                    <div class="progress w-100" style="height: 6px; border-radius: 4px; background-color: rgba(0,0,0,0.06);">
                                        <div class="progress-bar ${barTheme}" role="progressbar" style="width: ${compliancePercent}%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center fw-bold text-success" style="font-size:13px; vertical-align: middle;">${metSlaCount}</td>
                            <td class="text-center fw-bold text-danger" style="font-size:13px; vertical-align: middle;">${closedCount - metSlaCount}</td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center flex-column" style="padding: 0 4px;">
                                    <div class="d-flex justify-content-between w-100 mb-1 small fw-bold">
                                        <span class="text-dark fw-bold" style="font-size:12px;">${metSlaCount}/${closedCount}</span>
                                        <span class="text-dark fw-bold" style="font-size:12px;">${metSLA}%</span>
                                    </div>
                                    <div class="progress w-100" style="height: 6px; border-radius: 4px; background-color: rgba(0,0,0,0.06);">
                                        <div class="progress-bar ${metBarTheme}" role="progressbar" style="width: ${metSLA}%;"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>`;
                }

                if (response.global_totals) {
                    let gt = response.global_totals;
                    let totalActiveSum = (parseInt(gt.TOTAL_ASSIGNED) || 0) + (parseInt(gt.TOTAL_ON_PROCESS) || 0) + (parseInt(gt.TOTAL_PENDING) || 0);
                    let globalCompliancePercent = (parseInt(gt.OVERALL_GRAND_TOTAL) || 0) > 0 ? Math.round((parseInt(gt.TOTAL_CLOSED) / parseInt(gt.OVERALL_GRAND_TOTAL)) * 100) : 0;
                    let globalMetSLAPercent = (parseInt(gt.TOTAL_CLOSED) || 0) > 0 ? Math.round((parseInt(gt.TOTAL_MET_SLA) / parseInt(gt.TOTAL_CLOSED)) * 100) : 0;

                    let globalBarTheme = globalCompliancePercent >= 75 ? "bg-success" : (globalCompliancePercent >= 40 ? "bg-warning" : "bg-danger");
                    let globalMetSLABarTheme = globalMetSLAPercent >= 80 ? "bg-success" : (globalMetSLAPercent >= 50 ? "bg-warning" : "bg-danger");

                    footerHtml = `
                        <tr style="background-color: #ecebe584; font-weight: bold; border-top: 2px solid #213456;">
                            <td class="text-dark fw-bold text-uppercase" style="font-size:13px;">TOTAL SUMMARY</td>
                            <td class="text-center text-primary fw-bold" style="font-size:14px;">${totalActiveSum}</td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center flex-column" style="padding: 0 4px;">
                                    <div class="d-flex justify-content-between w-100 mb-1 small fw-bold">
                                        <span class="text-dark" style="font-size:13px;">${gt.TOTAL_CLOSED || 0}/${gt.OVERALL_GRAND_TOTAL || 0}</span>
                                        <span class="text-dark" style="font-size:13px;">${globalCompliancePercent}%</span>
                                    </div>
                                    <div class="progress w-100" style="height: 6px; background-color: rgba(0,0,0,0.1);">
                                        <div class="progress-bar ${globalBarTheme}" role="progressbar" style="width: ${globalCompliancePercent}%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center text-success" style="font-size:14px;">${gt.TOTAL_MET_SLA || 0}</td>
                            <td class="text-center fw-bold text-danger" style="font-size:14px;">${(parseInt(gt.TOTAL_CLOSED) || 0) - (parseInt(gt.TOTAL_MET_SLA) || 0)}</td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center flex-column" style="padding: 0 4px;">
                                    <div class="d-flex justify-content-between w-100 mb-1 small fw-bold">
                                        <span class="text-dark" style="font-size:13px;">${gt.TOTAL_MET_SLA || 0}/${gt.TOTAL_CLOSED || 0}</span>
                                        <span class="text-dark" style="font-size:13px;">${globalMetSLAPercent}%</span>     
                                    </div>
                                    <div class="progress w-100" style="height: 6px; background-color: rgba(0,0,0,0.1);">
                                        <div class="progress-bar ${globalMetSLABarTheme}" role="progressbar" style="width: ${globalMetSLAPercent}%;"></div>
                                    </div>
                                </div>
                            </td>  
                        </tr>`;
                }
            } else {
                html = '<tr><td colspan="6" class="text-center py-4 text-muted">No database response detected.</td></tr>';
            }

            $('#dept-table-body').html(html);
            $('#dept-table-footer').html(footerHtml);
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error Context:", xhr.responseText);
            $('#dept-table-body').html('<tr><td colspan="6" class="text-center text-danger py-4 fw-bold">Communications fault with fetch_department_table.php</td></tr>');
        }
    });
}
</script>