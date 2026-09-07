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
    background-color: linear-gradient(135deg, #213456, #334c7a) !important;
    color: #ffffff!important;
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
.modal-overlay .close-btn:hover{
   display: block;
  margin-left: auto;
  border-radius: 12px;
  width: 30%;

  background-color: #E1AD01;
  color: white;
}

.modal-overlay .close-btn {
  display: block;
  margin-left: auto;
  border-radius: 12px;
  padding:10px;
  width: 30%;
  color: white;
  background-color: linear-gradient(135deg, #213456, #334c7a);
}
 .modal-overlay .month-row[data-month="6"] {
  background: linear-gradient(135deg, #213456, #334c7a);
  outline: 2px solid red;
  outline-offset: -2px; 
}


label {
  font-size: 11px;
  font-weight: 900;
  color: #e1ad01; 
  letter-spacing: .08em;
  text-transform: uppercase;
  margin-bottom: 6px;
}

input.form-control,
textarea.form-control {
  color: #6c757d !important;
  background-color: transparent !important; 
  border: black !important; 
  border-bottom: 1px solid #E1AD01 !important; 
  resize: none !important; 
}

select.custom-select-placeholder.placeholder-active,
textarea.form-control.custom-select-placeholder:placeholder-shown {
  color: red !important;
  border: 1px solid #ced4da !important;
  background-color: #fff !important;
}

textarea.form-control.custom-select-placeholder::placeholder {
  color: red !important;
  opacity: 0.7;
}

select.custom-select-placeholder.has-value,
textarea.form-control.custom-select-placeholder:not(:placeholder-shown) {
  color: #0a0a0a !important; 
  border-bottom: 1px solid #E1AD01 !important; 
  background-color: transparent !important;
}

.form-control,
.form-control-sm,
input.form-control,
select.form-control,
textarea.form-control {
  background: #fff !important;
  color: black !important;
  border-bottom: 1px solid #E1AD01 !important; 
}

.form-control:focus,
.form-control-sm:focus,
input.form-control:focus,
select.form-control:focus,
textarea.form-control:focus {
  box-shadow: 0 10px 18px rgba(17,24,39,.06);
  border-color: 2px solid rgba(114, 89, 21, 0.94) !important;
}


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

.dv_msg {
    display: block !important;
}

#remarks_view {
    display: flex;
    flex-direction: column;
    width: 100%;
}

#userModal .modal-dialog{
  max-width: 1100px; 
  margin: 1.25rem auto;
}

#userModal .modal-content{
  border-radius: 16px;
  border: none;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
  overflow: hidden;
}

#userModal .modal-header{
    background-color: #213456;
    color: #fff;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    border-bottom: 4px solid #E1AD01; 
}

#userModal_header{
  font-weight: 700;
  font-size: 18px;
  margin: 0;
}

#userModal .modal-body{
  padding: 16px 18px;
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
    color: linear-gradient(135deg, #213456, #334c7a);
}

#userModal .form-control {
    border-left: none;
    height: 45px;
    border-radius: 0 8px 8px 0;
}

#userModal .form-control:focus {
    border-color: linear-gradient(135deg, #213456, #334c7a);
    box-shadow: none;
}

#userModal .input-group:focus-within {
    box-shadow: 0 0 0 0.2rem rgba(225, 173, 1, 0.25);
    border-radius: 8px;
}

.m_col {
    background: #ffffff;
    padding: 2rem !important;
    border-right: 1px solid #edf2f7;
}

.m_col label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
    display: block;
}

.m_col .form-control {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.6rem 0.75rem;
    transition: all 0.2s ease;
    background-color: #f8fafc;
}

.m_col .form-control:focus {
    background-color: #fff;
    border-color: #1C0770;
    box-shadow: 0 0 0 3px rgba(28, 7, 112, 0.1);
    outline: none;
}

.m_col textarea {
    min-height: 80px;
}

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

.chat-left .msg-meta {
    color: #64748b;
}

.chat-right .msg-meta {
    color: #ffffff; 
}

.chat-left .msg-meta-name {
    color: #213456;
    font-weight: bold;
}

.chat-right .msg-meta-name {
    color: #ffffff;
    font-weight: bold;
}


.chat-right .msg-time {
    color: #ffffff !important; 
}

.chat-left .msg-time {
    color: #64748b !important;
}
.chat-left .msg-meta {
    color: #64748b;
}

.chat-right .msg-meta {
    color: rgba(255, 255, 255, 0.85);
}

.chat-left .msg-meta-name {
    color: linear-gradient(135deg, #213456, #334c7a);
    font-weight: bold;
}

.chat-right .msg-meta-name {
    color: #ffffff;
    font-weight: bold;
}
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
            margin-top:30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
       .modal-overlay .modal-content h3 {
            margin-top: 0;
            color: #333;
        }
       .modal-overlay .close-btn {
            background-color: #213456;
            margin-top: 15px;
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
.modal-overlay .close-btn:hover{
   display: block;
  margin-left: auto;
  border-radius: 12px;
  width: 30%;

  background-color: #E1AD01;
  color: white;
}

.modal-overlay .close-btn {
  display: block;
  margin-left: auto;
  border-radius: 12px;
  padding:10px;
  width: 30%;
  color: white;
  background-color: #213456;
}
 .modal-overlay .month-row[data-month="6"] {
  background: #213456;
  outline: 2px solid red;
  outline-offset: -2px; 
}

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

#userModal .modal-footer{
  border-top: 1px solid rgba(0,0,0,0.08);
  background: rgba(255,255,255,0.92);
  position: sticky;
  bottom: 0;
  z-index: 5;
  padding: 12px 14px;
}

@media (max-width: 991px){
  #userModal .modal-dialog{
    max-width: 96%;
    margin: .75rem auto;
  }

  .container_remarks{
    max-height: 350px;
  }

  #action, #btnClose{
    width: 100%;
  }
}


.year-picker-group {
    flex: 1;
    min-width: 300px; 
}
#showCalendarBtn {
    background-color: #213456;
    color: white;
    border-radius: 8px;
    padding: 8px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    white-space: nowrap;
}

#showCalendarBtn:hover {
    background-color: var(--owi-gold, #E1AD01);
    color: linear-gradient(135deg, #213456, #334c7a);
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

  

  #admin_report.admin-table th.active.text-center {
    background-color: #2b9827 !important;
    color: #213456 !important;
    padding: 6px 4px !important;
    font-size: 11px !important;
  }

  #admin_report.admin-table th.compliance.text-center {
    background-color: #a29341 !important;
    color: #213456 !important;
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

</style>
        
<div id="welcomeModal" class="modal-overlay" style="display: none; ">
    <div class="modal-content">
        <h3>Logistics Department HelpDesk Efficiency & Performance Report</h3>
        <p>As of the Year -  2026</p>
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
                          <tr class ="month-row" data-month="7" style="background: #213456; border-outline: 2px solid red;">
                        <td class="fw-bold" >JULY</td>
                        <td class="text-center active-total">-</td>
                        <td class="compliance-rate">-</td>
                        <td class="text-center met-sla">-</td>
                        <td class="text-center non-sla">-</td>
                        <td class="sla-compliance">-</td>
                      </tr>
                          <tr class ="month-row" data-month="8" style="background: #213456; border-outline: 2px solid red;">
                        <td class="fw-bold" >AUGUST</td>
                        <td class="text-center active-total">-</td>
                        <td class="compliance-rate">-</td>
                        <td class="text-center met-sla">-</td>
                        <td class="text-center non-sla">-</td>
                        <td class="sla-compliance">-</td>
                      </tr>
                          <tr class ="month-row" data-month="9" style="background: #213456; border-outline: 2px solid red;">
                        <td class="fw-bold" >SEPTEMBER</td>
                        <td class="text-center active-total">-</td>
                        <td class="compliance-rate">-</td>
                        <td class="text-center met-sla">-</td>
                        <td class="text-center non-sla">-</td>
                        <td class="sla-compliance">-</td>
                      </tr>
                          <tr class ="month-row" data-month="10" style="background: #213456; border-outline: 2px solid red;">
                        <td class="fw-bold" >OCTOBER</td>
                        <td class="text-center active-total">-</td>
                        <td class="compliance-rate">-</td>
                        <td class="text-center met-sla">-</td>
                        <td class="text-center non-sla">-</td>
                        <td class="sla-compliance">-</td>
                      </tr>
                          <tr class ="month-row" data-month="11" style="background: #213456; border-outline: 2px solid red;">
                        <td class="fw-bold" >NOVEMBER</td>
                        <td class="text-center active-total">-</td>
                        <td class="compliance-rate">-</td>
                        <td class="text-center met-sla">-</td>
                        <td class="text-center non-sla">-</td>
                        <td class="sla-compliance">-</td>
                      </tr>
                      
                    </tbody>
                  <tfoot id="dept-table-footer"></tfoot>
                  </table>

        <button id="closeModalBtn" class="close-btn">Proceed to Dashboard</button>
    </div>
</div>
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
        <div class="action-bar-container" style="box-shadow: 0 5px 10px 2px #2d3c597f; color: #213456; margin-bottom: -20px;">
          <div class="year-picker-group">
            <div class="input-group">
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="fas fa-history me-2"></i>LOGS IN YEAR OF:
                </span>
              </div>
              <select class="form-control" name="yearpicker" id="yearpicker" required>
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
              <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id']; ?>">
           
            </form>
            <div class="form-check form-switch float-right m-3">
              <input class="form-check-input" style="margin-left:-50px;" type="checkbox" id="darkModeToggle">
              <label class="form-check-label text-dark" for="darkModeToggle">Dark Mode</label>
            </div>
          </div>
        </div>
      </div>

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
                      <p class=" fw-bold text-uppercase mb-0"
                        style="font-size: 0.75rem; color: #576A8F;letter-spacing: 1px;">Total Reports</p>
                      <hr class="mt-2 mb-3" style="border-top: 2px solid #576A8F; opacity: 1; width: 100%;" />
                      <div class="d-flex justify-content-between align-items-center">
                        <a href="#report_data" class="text-decoration-none small text-muted stretched-link">Click here
                          for more info</a>
                        <i class="fas fa-chevron-right small text-muted"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card  h-100 dashcard-clickable" data-filter="ON PROCESS"
                  style="border-radius: 15px; cursor: pointer;">
                  <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                      <div class=" bg-opacity-10 p-3 rounded-circle" style="color: #E5BA41;">
                        <i class="fas fa-spinner fa-2x"></i>
                      </div>
                      <h2 class="fw-black mb-1" id="count_open" style="font-size: 2.2rem; letter-spacing: -1px;">0</h2>
                    </div>
                    <div>
                      <p class="text-warning fw-bold text-uppercase mb-0"
                        style="font-size: 0.75rem;color: #E5BA41; letter-spacing: 1px;">On Process</p>
                      <hr class="mt-2 mb-3" style="border-top: 2px solid #E5BA41;; opacity:1; width:100%;" />
                      <div class="d-flex justify-content-between align-items-center">
                        <a href="#report_data" class="text-decoration-none small text-muted stretched-link">Click here
                          for more info</a>
                        <i class="fas fa-chevron-right small text-muted"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card h-100 dashcard-clickable" data-filter="PENDING"
                  style="border-radius: 15px; cursor:pointer;">
                  <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                      <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger" style="color: #D25353;">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                      </div>
                      <h2 class="fw-black mb-1" id="count_owfa" style="font-size:2.2rem; letter-spacing: -1px;">0</h2>
                    </div>
                    <div>
                      <p class="text-danger fw-bold text-uppercase mb-0"
                        style="font-size:0.75rem; color: #D25353;letter-spacing:1px;">Over Sla / Pending</p>
                      <hr class="mt-2 mb-3" style="border-top: 2px solid #D25353; opacity:1; width:100%;" />
                      <div class="d-flex justify-content-between align-items-center">
                        <a href="#report_data" class="text-decoration-none small text-muted stretched-link">Click here
                          for more info</a>
                        <i class="fas fa-chevron-right small text-muted"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card h-100 dashcard-clickable" data-filter="CLOSED"
                  style="border-radius: 15px; cursor:pointer;">
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
                      <p class="text-success fw-bold text-uppercase mb-0"
                        style="font-size:0.75rem; color: #94A378; letter-spacing: 1px;">Closed Reports</p>
                      <hr class="mt-2 mb-3" style="border-top: 2px solid #94A378; opacity:1; width:100%;" />
                      <div class="d-flex justify-content-between align-items-center">
                        <a href="#report_data" class="text-decoration-none small text-muted stretched-link">View
                          History</a>
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
                  <h5 class="card-header" style="background: linear-gradient(135deg, #213456, #334c7a); color:black;">Overall Status</h5>
                  <div class="card-body">
                    <div id="chartdiv5"></div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-lg-6 mb-3">
                <div class="card card2 h-100">
                  <h5 class="card-header text-black" style="background: linear-gradient(135deg, #213456, #334c7a); color:black;">LD Support Logs
                  </h5>
                  <div class="card-body">
                    <div id="chartdiv8"></div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-lg-6 mb-3">
                <div class="card card2 h-100">
                  <h5 class="card-header text-black" style="background: linear-gradient(135deg, #213456, #334c7a); color:black;">Recently enrolled
                    reports.</h5>
                  <div class="card-body">
                    <div id="chartdiv1"></div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-lg-6 mb-3">
                <div class="card card2 h-100">
                  <h5 class="card-header text-black" style="background: linear-gradient(135deg, #213456, #334c7a); color:black;">CATEGORIES</h5>
                  <div class="card-body">
                    <div id="chartdiv2" name="chartdiv2"></div>
                  </div>
                </div>
              </div>

              <div class="col-12 mb-3">
                <div class="card card2">
                  <h5 class="card-header text-black" style="background: linear-gradient(135deg, #213456, #334c7a); color:black;">Number of
                    Escalated Reports Per Area</h5>
                  <div class="card-body">
                    <div id="chart_area"></div>
                  </div>
                </div>
              </div>

              <div class="col-12 mb-3">
                <div class="card card2">
                  <h5 class="card-header text-black" style="background: linear-gradient(135deg, #213456, #334c7a); color:black;">Non Compliant
                    Stores on End of Day Process (7:AM CUT OFF)</h5>
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
              
        <div class="card-header" style="background: linear-gradient(135deg, #213456, #334c7a); border-bottom: none; padding-bottom: 0;">
          <ul class="nav nav-tabs card-header-tabs" id="ticketTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="tickets-tab" data-toggle="tab" data-target="#tickets" type="button" role="tab" aria-controls="tickets" aria-selected="true" style="font-weight: bold; border: 1px solid #ffffff;">
                TICKETS
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="transferred-tab" data-toggle="tab" data-target="#transferred" type="button" role="tab" aria-controls="transferred" aria-selected="false" style="color: white; font-weight: bold; border: 1px solid #ffffff;">
                TRANSFERRED TICKETS
              </button>
            </li>
          </ul>
        </div>

      <div class="card-body">
        <div class="tab-content" id="ticketTabsContent">
          <div class="tab-pane fade show active" id="tickets" role="tabpanel" aria-labelledby="tickets-tab">
            <div class="table-responsive" style="max-height:450px; width:100%; overflow-y:auto;">
              <table id="report_data" class="table table-hover">
                
                <tbody>
                </tbody>
              </table>
            </div>
          </div>

          <div class="tab-pane fade" id="transferred" role="tabpanel" aria-labelledby="transferred-tab">
            <div class="table-responsive" style="max-height:450px; width:100%; overflow-y:auto;">
              <table id="transferred_data" class="table table-hover">
                
                <tbody>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
      
    </div>
  </div>
</div>

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

                  <div class="form-group col-12 col-md-8">
                    <label>I.T SUPPORT</label>
                    <input type="hidden" name="it_num" id="it_num" readonly>
                    <select class="form-control form-control-sm" name="itsup" id="itsup" required>
                      <option value="">Assign support...</option>
                      <?php
                      $query = "select * from it_tech WHERE deptsel = '9' AND itsup NOT IN ('4','8','12','14')";
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
                      $query = "select * from categories WHERE deptsel = '9' AND old_tag IS NULL";
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
                  <div class="form-group col-12 col-md-4">
                    <label style="font-weight: bold;">TRANSFER REQUEST</label>
                    <div>
                      <input type="checkbox" name="is_transfer" id="is_transfer" value="1">
                      <label for="is_transfer"> Mark as Transfer Request</label>
                    </div>
                  </div>
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

                  <div class="form-group col-12 col-md-4 hide_cl" style="display: none;">
                    <label id="dateclabel">DATE CLOSED</label>
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

                  <div class="form-group col-12 col-md-4 hide_cl" style="display: none;">
                    <label id="clby_label">CLOSED BY</label>
                    <input type="hidden" name="close_by" id="close_by" value="<?php echo htmlspecialchars($_SESSION['tech_id'] ?? ''); ?>">
                    <input type="text" class="form-control form-control-sm" name="cl_desc" id="cl_desc" readonly
                      value="<?php echo htmlspecialchars(trim(($_SESSION['fname'] ?? '') . '  ' . ($_SESSION['lstname'] ?? ''))); ?>">
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
              <!-- RIGHT SIDE -->
            <div class="col-12 col-lg-6">

                <div id="msg_thread">

                  <div class="col-12 mb-3 px-0">
                    <label style="font-weight: bold; color:linear-gradient(135deg, #213456, #334c7a);">Add Comment:</label>
                    <textarea name="admsg" id="addmsg" class="form-control form-control-sm"
                      placeholder="Reply to their message or give updates regarding this ticket..."
                      required></textarea>
                  </div>

                  <div class="col-12 mt-4 mb-2 dv_msg px-0">
                    <label for="remarks_view" style="font-weight: bold; color:linear-gradient(135deg, #213456, #334c7a);">Comment Thread:</label>
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
                class="fas fa-plus-circle mr-2 text-warning"></i> Create Department Report</h5>
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
                  <label class="font-weight-bold text-secondary small"><i class="fas fa-store mr-1 text-primary"></i>
                    STORE/BRANCH</label>
                  <select class="form-control" name="store" id="create_store" required
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
                  <label class="font-weight-bold text-secondary small"><i class="fas fa-building mr-1 text-primary"></i>
                    ATTENTION TO (DEPARTMENT)</label>
                  <select class="form-control" name="deptsel" id="create_deptsel" required
                    style="border-radius: 8px; height: 45px;">
                    <option value="" selected disabled>Select Here</option>
                    <option value="1">IT</option>
                    <option value="2">ADMIN</option>
                    <option value="3">MARKETING</option>
                    <option value="6">VISUAL</option>
                    <option value="11">H.R</option>
                    <option value="13">ACCOUNTS PAYABLE</option>
                    <option value="15">TREASURY</option>
                    <option value="16">ACCOUNT RECEIVABLE</option>
                  </select>
                </div>

                <div class="form-group mb-4">
                  <label class="font-weight-bold text-secondary small"><i class="fas fa-envelope mr-1 text-primary"></i>
                    SUBJECT (CATEGORY)</label>
                  <select class="form-control" id="create_subject" name="subject" required style="width: 100%;">
                    <option value="" selected disabled>---Select Category---</option>
                  </select>
                </div>

                <div class="form-group mb-4" id="create_sub_group" style="display: none;">
                  <label class="font-weight-bold text-secondary small"><i class="fas fa-tag mr-1 text-primary"></i>
                    SUBCATEGORY</label>
                  <select class="form-control" id="create_sub" name="sub" style="width: 100%;">
                    <option value="" selected disabled>---Select Subcategory---</option>
                  </select>
                </div>
              </div>

              <!-- Right Side: Concern and Upload -->
              <div class="col-12 col-lg-6">
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
      
      // Initialize active dashcard filter variable globally
      window.currentDashcardFilter = '';
      
      // KPI Card Click Functionality
      $('.dashcard-clickable').on('click', function () {
        const filterValue = $(this).data('filter') || '';
        window.currentDashcardFilter = filterValue; // Record clicked dashcard
        
        const statusRegex = filterValue ? '^' + $.fn.dataTable.util.escapeRegex(filterValue) + '$' : '';

        if ($.fn.DataTable.isDataTable('#report_data')) {
          const reportTable = $('#report_data').DataTable();
          reportTable.search('').column(6).search(filterValue ? statusRegex : '', true, false).draw();
        }
        
        if ($.fn.DataTable.isDataTable('#transferred_data')) {
          const transferTable = $('#transferred_data').DataTable();
          transferTable.search('').column(6).search(filterValue ? statusRegex : '', true, false).draw();
        }

        $('#report_data_filter_disabled').val(filterValue);
        $('#transferred_data_filter_disabled').val(filterValue);
        
        $('#report_data_free_search').val(filterValue);
        $('#transferred_data_free_search').val(filterValue);

              
        $('#report_data_free_search2').val(filterValue);
        $('#transferred_data_free_search2').val(filterValue);

        $('html, body').animate({
          scrollTop: $("#report_data").offset().top - 100
        }, 600);

        $(this).fadeOut(100).fadeIn(100);
      });

      $(document).on('click', '#navCreateReport', function (e) {
        if (window.location.pathname.endsWith('adminpanel.php') || window.location.pathname.endsWith('/it/')) {
          e.preventDefault();
          $('#createReportModal').modal({ backdrop: 'static', keyboard: false });
        }
      });

      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.get('create') === 'true') {
        $('#createReportModal').modal({ backdrop: 'static', keyboard: false });
        window.history.replaceState({}, document.title, window.location.pathname);
      }

      $('#createReportModal').on('show.bs.modal', function () {
        $('#create_report_form').trigger('reset');
        $('#create_store').val('201'); 
        $('#create_subject').val(null).trigger('change');
        $('#create_sub').val(null).trigger('change');
        $('#create_sub_group').hide();
        $('#create_ticket_lbl').text('---');
        $('#create_ticket_no').val('');
      });

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
    
    // Handle the status change to show/hide CLOSED BY and DATE CLOSED properly
    $('#status').on('change', function() {
        var stat = $(this).val();
        if (stat === 'CLOSED' || stat === 'SUBJECT FOR CLOSING') {
            $('.hide_cl').slideDown(200);
        } else {
            $('.hide_cl').slideUp(200);
        }
    });
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
                4: "APRIL", 5: "MAY", 6: "JUNE" , 7: "JULY" , 8: "AUGUST" , 9: "SEPTEMBER" , 10: "OCTOBER" , 11: "NOVEMBER" , 12: "DECEMBER"
            };

            if (response && response.department_stats) {
                globalTicketDetails = response.ticket_details || []; 
                
                let statsByMonth = {};
                response.department_stats.forEach(function(row) {
                    statsByMonth[parseInt(row.MONTH_NUM)] = row;
                });

                for (let m = 1; m <= 9; m++) {
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
                        <tr style="background-color: #ecebe584; font-weight: bold; border-top: 2px solid linear-gradient(135deg, #213456, #334c7a);">
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
$(document).ready(function() {
    $('button[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var targetTab = $(e.target).attr("id"); 

        if (targetTab === 'transferred-tab') {
            if ($.fn.DataTable.isDataTable('#transferred_data')) {
                $('#transferred_data').DataTable().columns.adjust().draw();
            }
        } else if (targetTab === 'tickets-tab') {
            if ($.fn.DataTable.isDataTable('#report_data')) {
                $('#report_data').DataTable().columns.adjust().draw();
            }
        }
        $('#ticketTabs .nav-link').css('color', 'white');
        $('#ticketTabs .nav-link.active').css('color', '#495057'); 
    });
    
});
</script>