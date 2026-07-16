<?php
include '../condb.php';
$con1 = new dbconfig();
$conn = $con1->getConnection(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mode'])) {
    
    if ($_POST['mode'] === 'add_remarks_only') {
        $ticket_no = $_POST['ticket_no'] ?? '';
        $remarks = $_POST['remarks_adtech'] ?? '';
        $tech_id = $_SESSION['tech_id'] ?? 'Unknown';
        $store = $_SESSION['str_num'] ?? '';
        $currentDate = date('Y-m-d H:i:s');

        if (empty($ticket_no) || empty($remarks)) {
            echo json_encode(["status" => "error", "message" => "Missing data."]);
            exit;
        }

        $stmt1 = $conn->prepare("INSERT INTO fixed_asset_remarks (ticket_no, remarks_note, remarks_by, date_remarks) VALUES (?, ?, ?, ?)");
        $stmt1->bind_param("ssss", $ticket_no, $remarks, $tech_id, $currentDate);
        
        $notif_msg = "Technical Head added a remark on ticket no " . $ticket_no;
        $stmt2 = $conn->prepare("INSERT INTO tbl_notif (ticket_no, store, itsup, notif_data, notif_val, notif_date) VALUES (?, ?, ?, ?, '10', ?)");
        $stmt2->bind_param("sssss", $ticket_no, $store, $tech_id, $notif_msg, $currentDate);

        if ($stmt1->execute() && $stmt2->execute()) {
            echo json_encode(["status" => "success", "message" => "Remarks saved successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
        }
        exit;
    }

    if ($_POST['mode'] === 'newrpt_tbl') {
        $sql = "SELECT r.ticket_no, r.date_created, r.concern, r.service_desc, r.subject, 
                GROUP_CONCAT(i.files_name SEPARATOR '|') AS attachment_files, r.sub_id, r.f_deptsel, r.itsup, r.store 
                FROM reports r LEFT JOIN images i ON r.ticket_no = i.ticket_no 
                WHERE r.status = 'Assigned' GROUP BY r.ticket_no ORDER BY r.date_created DESC";
        $result = $conn->query($sql);
        echo json_encode(['newrptdata' => $result->fetch_all(MYSQLI_ASSOC)]);
        exit;
    }

    if ($_POST['mode'] === 'fetch_remarks') {
        $stmt = $conn->prepare("SELECT far.remarks_note, it.it_desc, far.date_remarks FROM fixed_asset_remarks far 
LEFT JOIN 
it_tech it ON far.remarks_by = it.itsup WHERE ticket_no = ? ORDER BY date_remarks ASC");
        $stmt->bind_param("s", $_POST['ticket_no']);
        $stmt->execute();
        echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
        exit;
    }
}

$inactive = 180;
if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > $inactive)){
    session_unset();
    session_destroy();
    header("Location: techdashboard.php");
    exit();
}
$_SESSION['start'] = time();

include 'tech_header.php';
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
  :root {
    --navy: #121C31;
    --navy2: #1a2a4a;
    --navy-header: #213456; 
    --yellow: #EAAA00;
    --gold-accent: #E1AD01; 
    --bg: #EEF2F7;
    --card: #ffffff;
    --card2: #F8FAFF;
    --text: #111827;
    --muted: #6B7280;
    --line: #E5E7EB;
    --shadow: 0 14px 34px rgba(17, 24, 39, 0.10);
    --radius: 18px;
    --radius-sm: 14px;
    --focus: 0 0 0 .2rem rgba(234, 170, 0, 0.18);
  }

  body {
    background: linear-gradient(to bottom, #ffffff, #99aac8);
    background-attachment: fixed;
    margin: 0;
    height: 100vh;
  }

  .container.mt-3 {
    padding-top: 10px;
    padding-bottom: 24px;
  }

  hr {
    border-top: 1px solid var(--line) !important;
  }

  label {
    font-size: 11px;
    font-weight: 900;
    color: var(--navy-header);
    letter-spacing: .08em;
    text-transform: uppercase;
    margin-bottom: 6px;
  }

  ::-webkit-scrollbar {
    width: 8px;
  }
  ::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 10px;
  }
  ::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #837031, var(--gold-accent));
    border-radius: 10px;
  }
  ::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #837031, var(--gold-accent));
  }

  #new_rep_table { width:100% !important; }

  .table-wrap {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 14px;
  }

  .dataTables_wrapper {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 14px;
  }

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
  table.dataTable {
    border-collapse: collapse !important; 
    border: none !important;
    width: 100% !important;
  }

  table.dataTable thead th {
    color: white !important;
    font-weight: 900;
    letter-spacing: .04em;
    text-transform: uppercase;
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
    border-bottom: 2px solid #213456 !important; 
    background: #5273ad !important;
    padding: 14px 12px !important;
  }

  table.dataTable tbody tr {
    background: #ffffff !important;
    box-shadow: 0 10px 22px rgba(17,24,39,.08);
    border: none !important; 
  }

  table.dataTable tbody td {
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
    border-bottom: 1px solid #E5E7EB !important; 
    color: rgba(17,24,39,.85) !important;
    padding: 14px 12px !important;
  }

  table.dataTable.no-footer {
    border-bottom: none !important;
  }
  table.dataTable tbody tr:hover {
    transition: .15s ease;
    background: #F8FAFF !important;
  }

  .form-group {
    margin-bottom: 14px !important;
  }

  .form-control,
  .form-control-sm,
  select.form-control,
  textarea.form-control {
    background-color: var(--card) !important;
    border: 1px solid var(--line) !important;
    color: var(--text) !important;
    border-radius: var(--radius-sm) !important;
    padding: 10px 12px !important;
    resize: none !important;
    transition: all 0.3s ease;
  }

  .form-control:focus,
  .form-control-sm:focus,
  select.form-control:focus,
  textarea.form-control:focus,
  .dataTables_wrapper .dataTables_filter input:focus,
  .dataTables_wrapper .dataTables_length select:focus {
    border-color: var(--gold-accent) !important;
    box-shadow: var(--focus) !important;
    outline: none;
  }

  .form-control[readonly],
  textarea[readonly] {
    opacity: 0.95;
  }
  .input-group-text {
    background-color: #f8f9fa;
    border-right: none;
    color: var(--navy-header);
  }

  .input-group:focus-within {
    box-shadow: var(--focus);
    border-radius: 8px;
  }

  .input-group .form-control {
    border-left: none;
    border-radius: 0 8px 8px 0 !important;
    height: 45px;
  }

  select.custom-select-placeholder.placeholder-active,
  textarea.form-control.custom-select-placeholder:placeholder-shown {
    color: red !important;
    border-color: #ced4da !important;
  }

  textarea.form-control.custom-select-placeholder::placeholder {
    color: red !important;
    opacity: 0.7;
  }

  select.custom-select-placeholder.has-value,
  textarea.form-control.custom-select-placeholder:not(:placeholder-shown) {
    color: var(--text) !important;
  }

  .modal-content {
    border: none !important;
    border-radius: 15px !important;
    background: var(--card) !important;
    box-shadow: 0 22px 60px rgba(17, 24, 39, 0.18);
  }

  .modal-header {
    background-color: var(--navy-header) !important;
    color: #fff;
    border-top-left-radius: 15px !important;
    border-top-right-radius: 15px !important;
    border-bottom: 4px solid var(--gold-accent) !important;
    padding: 16px 18px !important;
  }

  .modal-title {
    font-size: 16px;
    font-weight: 900;
    letter-spacing: 0.5px;
    color: white;
    text-transform: uppercase;
    display: flex;
    align-items: center;
  }

  .modal-body {
    padding: 18px !important;
  }

  .modal-footer {
    border-top: 1px solid var(--line) !important;
    padding: 14px 18px !important;
  }
  .btn {
    background-color: var(--card) !important;
    border: 2px solid var(--navy-header);
    color: var(--navy-header);
    font-weight: 700;
    transition: all 0.3s ease;
  }

  .btn:hover {
    background-color: #16243d !important;
    border-color: var(--gold-accent);
    color: white !important;
  }

  .btn-success {
    background-color: #7a5200 !important;
    border: 2px solid var(--navy-header) !important;
    color: white !important;
  }

  .btn-success:hover {
    background-color: #16243d !important;
    border-color: var(--yellow) !important;
  }

  .btn-danger {
    background: rgba(239, 68, 68, 0.14) !important;
    border-color: rgba(239, 68, 68, 0.28) !important;
    color: #991b1b !important;
  }

  .btn-danger:hover {
    background: rgba(239, 68, 68, 0.18) !important;
  }

  #msgbtn {
    background-color: var(--gold-accent);
    border: none;
    color: var(--navy-header);
    font-weight: 700;
    padding: 10px 40px;
    border-radius: 30px;
    transition: all 0.3s ease;
  }

  #msgbtn:hover {
    background-color: var(--navy-header);
    color: var(--gold-accent);
    transform: translateY(-2px);
  }

  #msg_thread .card.card-body {
    background: var(--navy-header) !important;
    border: 1px solid var(--line) !important;
    border-radius: var(--radius-sm) !important;
  }

  .container_remarks {
    background: var(--card2);
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    padding: 12px;
    max-height: 280px;
    box-shadow: 0 20px 60px rgba(123, 128, 44, 0.605);
    overflow: auto;
  }

  #remarks_view ul {
    list-style: none;
    padding-left: 0;
    margin: 0;
  }

  #remarks_view li {
    padding: 10px 12px;
    border: 1px solid var(--line);
    background: var(--card);
    border-radius: var(--radius-sm);
    margin-bottom: 10px;
    box-shadow: 0 10px 18px rgba(17, 24, 39, 0.06);
  }

  .priority-chip {
    padding: 4px 10px;
    border-radius: 999px;
    font-weight: 900;
    font-size: 11px;
    letter-spacing: .05em;
  }
  .p-critical { background: rgba(239, 68, 68, 0.14); color: #991b1b; border: 1px solid rgba(239, 68, 68, 0.25); }
  .p-high { background: rgba(251, 146, 60, 0.14); color: #9a3412; border: 1px solid rgba(251, 146, 60, 0.25); }
  .p-medium { background: rgba(234, 170, 0, 0.16); color: #7a5200; border: 1px solid rgba(234, 170, 0, 0.30); }
  .p-low { background: rgba(34, 197, 94, 0.14); color: #166534; border: 1px solid rgba(34, 197, 94, 0.25); }

  .select2-container--default .select2-selection--single {
    background-color: var(--card) !important;
    border: 1px solid var(--line) !important;
    border-radius: var(--radius-sm) !important;
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
    background-color: var(--card) !important;
    color: var(--text) !important;
    border: 1px solid var(--line) !important;
    border-radius: var(--radius-sm) !important;
    box-shadow: 0 18px 40px rgba(17, 24, 39, 0.14);
  }

  .select2-results__option {
    color: var(--text) !important;
  }

  .select2-results__option--highlighted {
    background: rgba(234, 170, 0, 0.16) !important;
    color: var(--text) !important;
  }

  .tracking-timeline {
    list-style: none;
    padding-left: 20px;
    position: relative;
  }

  .tracking-timeline::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 25px;
    width: 2px;
    background: var(--line);
  }

  .timeline-item {
    position: relative;
    padding-left: 25px;
    padding-bottom: 15px;
  }

  .timeline-icon {
    position: absolute;
    left: -4px;
    top: 2px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background-color: var(--card);
    border: 2px solid var(--line);
    z-index: 1;
  }

  .timeline-item.completed .timeline-icon {
    background-color: var(--gold-accent);
    border-color: var(--gold-accent);
    box-shadow: 0 0 0 3px rgba(225, 173, 1, 0.2);
  }

  .timeline-item.completed .timeline-desc {
    color: var(--navy-header) !important;
  }
  </style>
  
  <div class="container" style="max-width:1800px;">
    <div class="table-responsive-xl">
      <table class="table table-hover" id="new_rep_table"></table>
    </div>
  </div>

  <script src="../js/coms.js"></script> 
  <div class="modal fade" id="newrpt_Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 95%; width: 95%;"> 
        <form id="newrpt_form" action="insert.php" method="POST">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Fixed Asset Information</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="row">

          <div class="col-md-5 border-right pt-2 pb-2">
              <h6 class="text-uppercase mb-3" style="color:#213456; font-weight: 800;">Request Details</h6>
         
              <div class="row">
                  <div class="form-group col-md-6">
                      <label>Ticket No</label>
                      <input type="text" class="form-control" name="ticket_no" id="ticket_no" readonly>
                  </div>
                  
                  <div class="form-group col-md-6">
                      <label>Requesting Dept/Branch</label>
                      <input type="text" class="form-control" name="requested_db" id="str_name" readonly>
                  </div>
                  
                  <div class="form-group col-md-6">
                      <label>Requesting Employee</label>
                      <input type="text" class="form-control" name="requested_by" id="full_name" readonly>
                  </div>

                  <div class="form-group col-md-6">
                      <label>Ticket Created</label>
                      <input type="text" class="form-control" name="ticket_created" id="ticket_created" readonly>
                  </div>

                  <div class="form-group col-md-6">
                   <label>Item Code </label>

                      <input type="text" class="form-control" name="item_code" id="item_code" >
                  </div>

                  <div class="form-group col-md-6">
                      <label>Description </label>
                      <input type="text" class="form-control" name="description" id="description" >
                  </div>

                  <div class="form-group col-md-12">
                      <label>Serial Number </label>
                      <input type="text" class="form-control" name="serial_number" id="serial_number" required>
                  </div>

                  <div class="form-group col-md-12">
                      <label>Purpose of Request</label>
                      <textarea class="form-control" name="purpose" id="purpose_of_request" style="height: 100px;" readonly></textarea>
                  </div>

                   <div class="form-group col-md-12">
                      <label>Technical Workoutput</label>
                      <textarea class="form-control" name="purpose" id="technical_workoutput" style="height: 100px;"></textarea>
                  </div>

                  <div class="form-group col-md-6">
                      <label>Item Received By</label>
                      <input type="text" class="form-control" name="item_received_by" id="it_desc" readonly>
                  </div>
                  
                  <input type="hidden" class="form-control" name="received_by" value="<?php echo $_SESSION['tech_id']; ?>" readonly>

                  <div class="form-group col-md-6">
                      <label>Date Received</label>
                      <input type="text" class="form-control" name="date_received" id="date_received" required>
                  </div>
              </div>
          </div>
          
    
          <div class="col-md-4 border-right pt-2 pb-2" style="background: linear-gradient(to bottom, #ffffff, #f0f3f7);">
              <h6 class="text-uppercase mb-3" style="color:#E1AD01; font-weight: 800;">Asset Request Progress</h6>
              <div class="tracking-container" style="max-height: 850px; overflow-y: auto; padding-right: 10px;">
                  <ul class="tracking-timeline" id="trackingMap">
            
                  </ul>
              </div>
          </div>

          <div class="col-md-3 pt-2 pb-2" style="background: #f8f9fa; border-radius: 0 8px 8px 0;">
              <h6 class="text-uppercase mb-3" style="color:#213456; font-weight: 800;">Remarks Thread</h6>
              
              <div id="remarks_thread_container" class="chat-container">
               
              </div>
              
             <!-- <div class="chat-input-area mt-3">
                  <textarea class="form-control" id="new_remark_input" rows="2" placeholder="Type a new remark..."></textarea>
                  <button type="button" class="btn btn-sm w-100 mt-2" id="btn_send_remark" style="background-color: #E1AD01; color: #213456; font-weight: 700;">
                      <i class="fas fa-paper-plane"></i> Send Remark
                  </button>
              </div>-->
          </div>

        </div>
      </div>

      <div class="modal-footer">
        <input type="hidden" name="operation" id="operation" value="update_request">
        <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id']; ?>">
        <button type="submit" class="btn" style="background-color: #213456; color: #213456;">UPDATE REQUEST</button>
      </div>
    </div>
  </form>
    </div>
  </div>

  <style>
    .placeholder-style { color: #6c757d; font-style: italic; }
    #dataModal .modal-content { border: none; border-radius: 15px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); }
    #dataModal .modal-header { background-color: #213456; color: #fff; border-top-left-radius: 15px; border-top-right-radius: 15px; border-bottom: 4px solid #E1AD01; }
    #dataModal .modal-title { font-weight: 700; letter-spacing: 0.5px; display: flex; align-items: center; }
    #dataModal .input-group-text { background-color: #494949; border-right: none; color: #213456; }
    #dataModal .form-control { border-left: none; height: 45px; }
    #dataModal .form-control:focus { border-color: #213456; box-shadow: none; }
    #dataModal .input-group:focus-within { box-shadow: 0 0 0 0.2rem rgba(225, 173, 1, 0.25); border-radius: 8px; }
    #btn_chngepass { background-color: #E1AD01; border: none; color: #213456; font-weight: 700; padding: 10px 40px; border-radius: 30px; transition: all 0.3s ease; }
    #btn_chngepass:hover { background-color: #213456; color: #E1AD01; transform: translateY(-2px); }
    .toggle-password { cursor: pointer; position: absolute; right: 15px; top: 13px; z-index: 10; color: #6c757d; }
    .tracking-timeline { list-style: none; padding: 0; margin: 0; position: relative; }
    .tracking-timeline::before { content: ''; position: absolute; top: 5px; bottom: 0; left: 11px; width: 2px; border-left: 2px dotted #a3a3a3; z-index: 1; }
    .timeline-item { position: relative; padding-left: 35px; padding-bottom: 20px; }
    .timeline-icon { position: absolute; left: 4px; top: 2px; width: 16px; height: 16px; border-radius: 50%; background-color: #e0e0e0; border: 3px solid #ffffff; z-index: 2; box-shadow: 0 0 0 1px #ccc; transition: all 0.3s ease; }
    .timeline-item.completed .timeline-icon { background-color: #16A34A; box-shadow: 0 0 0 2px #16A34A; }
    .timeline-item.pending .timeline-icon { background-color: #E1AD01; box-shadow: 0 0 0 2px #E1AD01; }
    .timeline-desc { font-size: 12px; font-weight: 700; color: #333; margin-bottom: 2px; text-transform: uppercase; }
    .timeline-date { font-size: 11px; color: #6c757d; font-style: italic; }

    .chat-container {
        height: 400px;
        overflow-y: auto;
        background: #ffffff;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        scrollbar-width: thin;
    }
    .chat-container::-webkit-scrollbar { width: 6px; }
    .chat-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    .chat-message { margin-bottom: 15px; display: flex; flex-direction: column; align-items: flex-start; }
    .chat-meta { font-size: 11px; color: #64748b; margin-bottom: 4px; padding-left: 2px; }
    .chat-meta strong { color: #0f172a; font-weight: 700; }
    
    .chat-bubble {
        background: #f1f5f9;
        color: #334155;
        padding: 10px 14px;
        border-radius: 12px;
        border-top-left-radius: 2px; 
        font-size: 13px;
        line-height: 1.4;
        max-width: 95%;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
  </style>

  <script type="text/javascript">
  $(document).ready(function(){

    function getUrlParam(param) {
      var urlParams = new URLSearchParams(window.location.search);
      return urlParams.get(param);
    }

    var targetTicket = getUrlParam('ticket_no');
    var reptable;
    var user_id = <?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>; 

    if (targetTicket) {
      setTimeout(function() {
        var foundRow = null;
        if (reptable) {
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
                $(foundRow).css('transition', 'background-color 0.5s ease');
                $(foundRow).css('background-color', '#ffff99'); 

                setTimeout(function() {
                  $(foundRow).css('background-color', ''); 
                }, 1200);
              });
            }
        }
      }, 600);
    }

    $("div.selected select").val("OPEN");

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
          {title:"Ticket No", data:"ticket_no","defaultContent": ""},
          {title:"Dept/Branch", data:"str_name","defaultContent": ""},
          {title:"Employee", data:"full_name","defaultContent": ""},
          {title:"Ticket Created", data:"ticket_created","defaultContent": ""},
          {title:"Item Code", data:"item_code","defaultContent": ""},
          {title:"Description", data:"description","defaultContent": ""},
          {title:"Serial Number", data:"serial_number","defaultContent": ""},
          {title:"Received by", data:"it_desc","defaultContent": ""},
          {title:"Date Received", data:"date_received","defaultContent": ""},
          {title:"Status", data:"status","defaultContent": ""},
          {title:"Update", data:null,"defaultContent": "<Button class='btn btn-danger edit-btn' name='update'><i class='fas fa-edit'></i></Button>"}
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
          $('#str_name').val(data['str_name']);
          $('#full_name').val(data['full_name']);
          $('#ticket_created').val(data['ticket_created']);
          $('#item_code').val(data['item_code']);
          $('#description').val(data['description']);
          $('#serial_number').val(data['serial_number']);
          $('#purpose_of_request').val(data['purpose_of_request']);
            $('#technical_workoutput').val(data['technical_workoutput']);
          $('#it_desc').val(data['it_desc']);
          $('#date_received').val(data['date_received']);
          $('#status').val(data['status']);

          $('#action').val("Update");
          $('#operation').val("update_request"); 

          var tid = $(this).parent().siblings(':first').html() || data['ticket_no'];
          $('#tick_title').text("Ticket Number: " + tid);
            
          if (typeof displayAttachmentsFromData === "function") {
              displayAttachmentsFromData(data);
          }
    
          
          loadRemarks(data['ticket_no']);

          $.ajax({
              url: 'get_first_comment.php', 
              type: 'POST',
              dataType: 'json', 
              data: { ticket_no: data['ticket_no'] },
              success: function(response) {
                  const statusLevels = {
                'submitted': 1, 'noted': 2, 'validated': 3, 
                'verified': 4,  'recorded': 5, 'printed': 6, 'approved': 7, 'completed': 8
            };

            let dbStatus = (response.status || "").toLowerCase().trim();
            let currentLevel = statusLevels[dbStatus] || 0; 

            const trackSteps = [
                { desc: "Request submitted by store/user", date: response.date_created, reqLevel: 0 },
                { desc: "Under technical evaluation", date: response.date_created, reqLevel: 0 },
                { desc: "Submitted to technical head", date: response.date_submitted, reqLevel: 1 },
                { desc: "Approved and noted by technical head", date: response.date_noted, reqLevel: 2 },
                { desc: "For admin support validation", date: null, reqLevel: 2 }, 
                { desc: "Validated by admin support", date: response.date_validated, reqLevel: 3 },
                { desc: "For administrative verification", date: null, reqLevel: 3 }, 
                { desc: "Verified by the administrator", date: response.date_verified, reqLevel: 4 },
                { desc: "For recording", date: null, reqLevel: 4 }, 
                { desc: "Recorded", date: response.date_recorded, reqLevel: 5 },
                { desc: "For printing request form", date: null, reqLevel: 5 }, 
                { desc: "Printed", date: response.date_printed, reqLevel: 6 },
                { desc: "For General Manager Approval", date: null, reqLevel: 6 }, 
                { desc: "Approved by General Manager", date: response.date_approved, reqLevel: 7 },
                { desc: "Ready for asset replacement", date: null, reqLevel: 7 }, 
                { desc: "Asset replaced / Completed", date: response.date_completed, reqLevel: 8 }
            ];

                  let timelineHtml = '';
                  
                  trackSteps.forEach((step) => {
                      let statusClass = (currentLevel >= step.reqLevel) ? "completed" : "";
                      let dateDisplay = step.date ? `<div class="timeline-date" style="font-size: 11px; color: #6B7280;">${step.date}</div>` : '';

                      timelineHtml += `
                          <li class="timeline-item ${statusClass}">
                              <div class="timeline-icon"></div>
                              <div class="timeline-desc" style="font-size: 13px; font-weight: 600; color: #213456;">${step.desc}</div>
                              ${dateDisplay}
                          </li>
                      `;
                  });

                  $('#trackingMap').html(timelineHtml);
              },
              error: function() {
                  $('#trackingMap').html('<p class="text-danger">Failed to load progress timeline.</p>');
              }
          });
          $('#newrpt_Modal').modal('show');
      });
    }

  function loadRemarks(ticket_no) {
      $('#remarks_thread_container').html('<div class="text-center mt-4"><i class="fas fa-spinner fa-spin fa-2x" style="color:#cbd5e1;"></i></div>');
      
      $.ajax({
          url: window.location.href,
          type: 'POST',
          data: { mode: 'fetch_remarks', ticket_no: ticket_no },
          dataType: 'json',
          success: function(response) {
              let html = '';
              if (Array.isArray(response) && response.length > 0) {
                  response.forEach(function(rmk) {
                      html += `
                          <div class="chat-message">
                              <span class="chat-meta"><strong>${rmk.it_desc}</strong> • ${rmk.date_remarks}</span>
                              <div class="chat-bubble">${rmk.remarks_note}</div>
                          </div>
                      `;
                  });
              } else {
                  html = `<div class="text-center mt-4 text-muted" style="font-size: 12px; font-style: italic;">No remarks found.</div>`;
              }
              $('#remarks_thread_container').html(html);
              var chatDiv = document.getElementById("remarks_thread_container");
              if(chatDiv) chatDiv.scrollTop = chatDiv.scrollHeight;
          },
          error: function(xhr) {
              console.error("Remarks Fetch Error:", xhr.responseText);
              $('#remarks_thread_container').html('<div class="text-danger text-center mt-3" style="font-size: 12px;">Failed to fetch remarks.</div>');
          }
      });
  }

  $('#btn_send_remark').off('click').on('click', function() {
      var remarks = $('#new_remark_input').val();
      var ticket_no = $('#ticket_no').val();

      if (!remarks.trim()) {
          Swal.fire('Warning', 'Please type a remark first.', 'warning');
          return;
      }

      $.ajax({
          url: window.location.href,
          type: 'POST',
          data: { 
              mode: 'add_remarks_only',
              ticket_no: ticket_no, 
              remarks_adtech: remarks 
          },
          dataType: 'json',
          success: function(response) {
              if (response.status === 'success') {
                  $('#new_remark_input').val('');
                  loadRemarks(ticket_no); 
                  Swal.fire({ icon: 'success', title: 'Saved!', timer: 1000, showConfirmButton: false });
              } else {
                  Swal.fire('Error', response.message, 'error');
              }
          },
          error: function(xhr) {
              Swal.fire('Error', 'Communication failed.', 'error');
              console.error(xhr.responseText);
          }
      });
  });

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
            try { response = JSON.parse(response); } 
            catch (e) { response = { status: 'error', message: String(response) }; }
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
          Swal.fire({ icon: 'error', title: 'Save failed', text: message });
        }
      });
    });

  }); 

  let inactivityTime = function(){
    let time;

    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.onscroll = resetTimer;
    document.onclick = resetTimer;

    function logout(){ window.location.href = 'techdashboard.php'; }

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