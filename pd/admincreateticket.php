<?php
include 'admin.php';
include '../condb.php';
include 'main_js2.php';
include 'chrtdashboard.php';
include 'sub_graph_modal.php';
?>

<head>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Orbitron:wght@600;800&display=swap" rel="stylesheet">
</head>

<style>
  :root {
    --primary: #E1AD01;
    --primary-hover: #c99a00;
    --dark-blue: #1e2d4a;
    --light-bg: #f8fafc;
    --border-color: #e2e8f0;
    --text-main: #1e293b;
    --text-muted: #64748b;
    --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    --font-sans: 'Inter', sans-serif;
  }

  body {
    font-family: var(--font-sans);
     background: linear-gradient(to bottom, #ffffff, #99aac8);
    background-attachment: fixed;
    margin: 0;
    color: var(--text-main);
    min-height: 100vh;
  }


  .modern-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.6);
    border-radius: 16px;
    box-shadow: var(--card-shadow);
    padding: 24px;
    transition: all 0.3s ease;
  }

  .section-title {
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
  }

  .form-group label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #213456;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
    display: block;
  }

  .modern-input {
    width: 100%;
    height: 44px;
    padding: 10px 14px;
    background: #ffffff !important;
    border: 1px solid var(--border-color) !important;
    border-radius: 8px !important;
    color: var(--text-main) !important;
    font-size: 0.9rem;
    transition: all 0.2s ease;
  }

  .modern-input:focus {
    outline: none !important;
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 3px rgba(225, 173, 1, 0.15) !important;
  }

  textarea.modern-input {
    height: 120px !important;
    resize: none !important;
  }

  /* Elegant Scrollbars */
  ::-webkit-scrollbar {
    width: 6px;
    height: 6px;
  }
  ::-webkit-scrollbar-track {
    background: transparent;
  }
  ::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
  }
  ::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }

  .table-responsive-xl {
    border-radius: 12px;
    overflow: hidden;
  }

  table.dataTable {
    border-collapse: collapse !important;
    margin: 0 !important;
    width: 100% !important;
  }

  table.dataTable thead th {
     background: linear-gradient(135deg, #213456, #334c7a);
    font-weight: 600 !important;
    font-size: 0.8rem !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 14px 16px !important;
    border: none !important;
  }

  table.dataTable tbody tr {
    background: #ffffff !important;
    transition: background 0.2s ease;
  }

  table.dataTable tbody tr:hover {
    background: #6c89ba !important;
  }

  table.dataTable tbody td {
    padding: 14px 16px !important;
    font-size: 0.85rem !important;
    color: var(--text-main) !important;
    border-bottom: 1px solid var(--border-color) !important;
  }

  .dataTables_wrapper .dataTables_filter input {
    border: 1px solid var(--border-color);
    border-radius: 6px;
    padding: 5px 10px;
    margin-left: 8px;
    outline: none;
  }
  .dataTables_wrapper .dataTables_filter input:focus {
    border-color: var(--primary);
  }

  .modal-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    justify-content: center;
    align-items: center;
    z-index: 1050;
  }

  .modal-overlay .modal-content {
    background: #ffffff;
    padding: 30px;
    border-radius: 16px;
    width: 90%;
    max-width: 700px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    border: none;
  }

.container_remarks {
    display: flex; 
    flex-direction: column;
    max-height: 480px;
    overflow-y: auto;
    background-color: #f0f2f5 !important;
    border: 1px solid #dee2e6;
    border-radius: 12px;
    padding: 15px;
    margin-top: 10px;
    display: none; /* jQuery will override this safely */
}

.dv_msg {
    display: block;
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
    color: rgba(255, 255, 255, 0.85);
}

.chat-left .msg-meta-name {
    color: #213456;
    font-weight: bold;
}

.chat-right .msg-meta-name {
    color: #ffffff;
    font-weight: bold;
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
    color: #213456;
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
             background: linear-gradient(to bottom, #ffffff, #99aac8);
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
            background-color: #28a745;
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
  
</style>

<div class="container-fluid py-4">
  <div class="row">
    
    <div class="col-xl-5 col-lg-6 mb-4">
      <div class="modern-card">
        <form method="post" id="create_report_form" enctype="multipart/form-data">
          <h6 class="section-title" style="color: var(--dark-blue);">
            <i class="fas fa-ticket-alt" style="color: var(--primary);"></i> Ticket Details
          </h6>
          
          <div class="row">
            <div class="form-group col-md-6 mb-3">
              <label><i class="fas fa-store mr-1"></i> Store / Branch</label>
              <select class="modern-input" name="store" id="create_store" required style="pointer-events: none; background-color: #f1f5f9 !important;">
                <?php
                $query = "SELECT * FROM tbl_branch";
                $run = $conn->prepare($query);
                $run->execute();
                $rs = $run->get_result();
                while ($res = $rs->fetch_assoc()) {
                  $brcnhid = $res['str_num'];
                  $brnchcd = $res['str_code'] . ' | ' . $res['str_name'];
                  $selected = ($brcnhid == '201') ? 'selected' : '';
                  echo "<option value='{$brcnhid}' {$selected}>{$brnchcd}</option>";
                } 
                ?>
              </select>
            </div>

            <div class="form-group col-md-6 mb-3">
              <label><i class="fas fa-building mr-1"></i> Department</label>
              <select class="modern-input" name="deptsel" id="create_deptsel" required>
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

            <div class="form-group col-md-6 mb-3">
              <label><i class="fas fa-envelope mr-1"></i> Subject (Category)</label>
              <select class="modern-input" id="create_subject" name="subject" required>
                <option value="" selected disabled>---Select Category---</option>
              </select>
            </div>

            <div class="form-group col-md-6 mb-3" id="create_sub_group" style="display: none;">
              <label><i class="fas fa-tag mr-1"></i> Subcategory</label>
              <select class="modern-input" id="create_sub" name="sub">
                <option value="" selected disabled>---Select Subcategory---</option>
              </select>
            </div>

            <div class="form-group col-12 mb-3">
              <label><i class="fas fa-comment-alt mr-1"></i> Concern (Message)</label>
              <textarea class="modern-input" id="create_concern" name="concern" minlength="10" maxlength="1000" placeholder="Describe the concern or request in detail..." required></textarea>
            </div>

            <div class="form-group col-12 mb-4">
              <label><i class="fas fa-paperclip mr-1"></i> Attach Files (Optional)</label>
              <input id="create_file-input" type="file" name="file[]" multiple class="form-control-file d-block mb-1">
              <small class="text-muted d-block">Max file size: 2MB. Allowed types: Images, PDF, Docs, Excel.</small>
            </div>
          </div>

          <div class="d-flex align-items-center justify-content-between pt-3 border-top">
            <div>
              <span class="badge badge-info p-2 font-weight-bold text-uppercase" style="border-radius: 6px; background-color: #0ea5e9;">
                Ticket: <span id="create_ticket_lbl">---</span>
              </span>
              <input type="hidden" name="ticket_no" id="create_ticket_no">
            </div>
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-light px-3 font-weight-bold" data-dismiss="modal" style="border-radius: 8px;">Cancel</button>
              <button type="submit" name="action" id="create_action" class="btn text-white px-4 font-weight-bold" style="border-radius: 8px; background: var(--dark-blue);">
                <i class="fas fa-save mr-2"></i>Save Ticket
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="col-xl-7 col-lg-6 mb-4">
      <div class="modern-card h-100 d-flex flex-column">
        <h6 class="section-title" style="color: var(--primary);">
          <i class="fas fa-list-ul"></i> Created Tickets Queue
        </h6>
        <div class="table-responsive-xl flex-grow-1">
          <table class="table table-hover" id="create_dept_table"></table>
        </div>
      </div>
    </div>

  </div>
</div>


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
                    <label>STORE/BRANCH</label>
                    <input type="text" id="str_name" name="str_name" class="form-control form-control-sm" readonly value="">
                  </div>

                  <div class="form-group col-12 col-md-6">
                    <label>DEPARTMENT</label>
                    <input type="text" id="deptsel" name="deptsel" class="form-control form-control-sm" readonly value="">
                  </div>

                  <input type="hidden" class="form-control form-control-sm" name="ticket_no" id="ticket_no">
                  <input type="hidden" name="store" id="store">
                  <input type="hidden" name="itsup" id="itsup">
                  <input type="hidden" name="it_num" id="it_num">
                  <input type="hidden" name="cat" id="cat">
                  <input type="hidden" name="sub" id="sub">
                  <input type="hidden" name="sub_num" id="sub_num">
                  <input type="hidden" name="via" id="via">
                  <input type="hidden" name="date_created" id="date_created">
                  <input type="hidden" name="date_createdx" id="date_createdx">
                  <input type="hidden" name="refNo" id="refNo">
                  <input type="hidden" name="date_refNo" id="date_refNo">
                  <input type="hidden" name="concern" id="concern">

                  <div class="form-group col-12 col-md-6">
                    <label>SUBJECT/CONCERN</label>
                    <input type="text" id="subjct" name="subjct" class="form-control form-control-sm" placeholder="Input Concern"
                      style="text-transform:uppercase" onkeyup="this.value = this.value;">
                  </div>
                   <div class="form-group col-12 col-md-6">
                    <label>SUB CATEGORY</label>
                    <input type="text" id="sub_cat" name="sub_cat" class="form-control form-control-sm" readonly>
                  </div>

                  <div class="form-group col-12 col-md-6">
                    <label>ASSIGN SUPPORT</label>
                    <input type="text" id="it_desc" name="it_desc" class="form-control form-control-sm" readonly>
                  </div>

              

                  

                  <div class="form-group col-12 col-md-6">
                    <label>STATUS</label>
                    <input type="text" id="status" name="status" class="form-control form-control-sm" readonly>
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
                      placeholder="Reply to their message or give updates regarding this ticket..."
                      required></textarea>
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
