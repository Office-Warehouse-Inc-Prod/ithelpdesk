<?php
include '../condb.php';
$con1 = new dbconfig();
$conn = $con1->getConnection(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mode'])) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $inactive = 180;
    if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > $inactive)){
        
        echo json_encode(["status" => "error", "message" => "Session expired. Please log in again."]);
        exit();
    }
    $_SESSION['start'] = time();
    if ($_POST['mode'] === 'fa_tbl') {
        try {
            $sql = "SELECT 
                        r.ticket_no, r.date_created, r.concern, r.service_desc, r.subject,
                        GROUP_CONCAT(i.files_name SEPARATOR '|') AS attachment_files,
                        r.sub_id, r.f_deptsel, r.itsup, r.store
                    FROM reports r
                    LEFT JOIN images i ON r.ticket_no = i.ticket_no
                    WHERE r.status = 'Assigned' 
                    GROUP BY r.ticket_no
                    ORDER BY r.date_created DESC";
                    
            $result = $conn->query($sql);
            
            if ($result) {
                echo json_encode(['fadata' => $result->fetch_all(MYSQLI_ASSOC)]);
            } else {
                echo json_encode(['fadata' => [], 'error' => $conn->error]);
            }
        } catch (Exception $e) {
            echo json_encode(['fadata' => [], 'error' => $e->getMessage()]);
        }
        exit; 
    }

    if ($_POST['mode'] === 'add_remarks_only') {
        $ticket_no = $_POST['ticket_no'] ?? '';
        $remarks = trim($_POST['remarks_adtech'] ?? '');
        $user_id = $_SESSION['user_id'] ?? '';
        
        $store = $_SESSION['str_num'] ?? '';

        date_default_timezone_set('Asia/Manila');
        $currentDate = date('Y-m-d H:i:s');

        if (empty($ticket_no) || empty($remarks)) {
            echo json_encode(["status" => "error", "message" => "Missing data."]);
            exit;
        }
        if (empty($user_id)) {
            echo json_encode(["status" => "error", "message" => "Session expired or User ID missing. Please log in again."]);
            exit;
        }

        try {
            $conn->begin_transaction();
            
            $stmt1 = $conn->prepare("
                INSERT INTO fixed_asset_remarks (
                    ticket_no, remarks_note, remarks_by, date_remarks
                ) VALUES (?, ?, ?, ?)
            ");
            $stmt1->bind_param("ssss", $ticket_no, $remarks, $user_id, $currentDate);
            $exec1 = $stmt1->execute();
            
            $notif_msg = "Technical Head added a remark on ticket no " . $ticket_no;
            $stmt2 = $conn->prepare("
                INSERT INTO tbl_notif (
                    ticket_no, store, itsup, notif_data, notif_val, notif_date
                ) VALUES (?, ?, ?, ?, '10', ?)
            ");
            $stmt2->bind_param("sssss", $ticket_no, $store, $user_id, $notif_msg, $currentDate);
            $exec2 = $stmt2->execute();
            
            if ($exec1 && $exec2) {
                $conn->commit();
                echo json_encode(["status" => "success", "message" => "Remarks saved successfully."]);
            } else {
                $conn->rollback();
                echo json_encode(["status" => "error", "message" => "SQL Error: Saving remarks failed."]);
            }

        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        }
        
        exit();
    }

    if ($_POST['mode'] === 'newrpt_tbl') {
        $sql = "SELECT r.ticket_no, r.date_created, r.concern, r.service_desc, r.subject, 
                GROUP_CONCAT(i.files_name SEPARATOR '|') AS attachment_files, r.sub_id, r.f_deptsel, r.itsup, r.store 
                FROM reports r LEFT JOIN images i ON r.ticket_no = i.ticket_no 
                WHERE r.status = 'Assigned' GROUP BY r.ticket_no ORDER BY r.date_created DESC";
        
        $result = $conn->query($sql);
        if ($result) {
            echo json_encode(['newrptdata' => $result->fetch_all(MYSQLI_ASSOC)]);
        } else {
            echo json_encode(['newrptdata' => []]);
        }
        exit;
    }
    if ($_POST['mode'] === 'fetch_remarks') {
        try {
            $stmt = $conn->prepare("SELECT far.remarks_note, 
                                                 CONCAT(u.fname, ' ', u.lstname) AS user_fullname, 
                                                 far.date_remarks 
                                          FROM fixed_asset_remarks far 
                                          LEFT JOIN users u ON far.remarks_by = u.id 
                                          WHERE far.ticket_no = ? 
                                          ORDER BY far.date_remarks ASC");
            $stmt->bind_param("s", $_POST['ticket_no']);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        } catch (Exception $e) {
            echo json_encode([["remarks_note" => "Error loading remarks.", "it_desc" => "System", "date_remarks" => ""]]);
        }
        exit;
    }
}

include 'admin.php';
$inactive = 180;
if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > $inactive)){
    // replace server-side redirect with client-side 3-minute redirect
    echo '<script>setTimeout(function(){ window.location.href = "adminpanel.php"; }, 180000);</script>';
    exit();
}
$_SESSION['start'] = time();
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
      <link rel="stylesheet" href="fix_asset_reports.css" />
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<div class="container" style="max-width:1800px;">
    <div class="row mb-12 align-items-end">
        <div class="row mb-3">
    <div class="col-md-3">
        <label>Year</label>
        <select id="filter_year" class="form-control filter-trigger">
            <option value="">All Years</option>
            <option value="2026">2026</option>
            </select>
    </div>
    <div class="col-md-3">
        <label>Month</label>
        <select id="filter_month" class="form-control filter-trigger">
            <option value="">All Months</option>
            <option value="01">January</option>
            <option value="02">February</option>
            <option value="03">March</option>
            <option value="04">April</option>
            <option value="05">May</option>
            <option value="06">June</option>
            <option value="07">July</option>
            <option value="08">August</option>
            <option value="09">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
            </select>
    </div>
    <div class="col-md-3">
        <label>Status</label>
        <select id="filter_status" class="form-control filter-trigger">
            <option value="">All Statuses</option>
            <option value="APPROVED">APPROVED</option>
            <option value="PURCHASED">PURCHASED</option>
        </select>
    </div>
</div>
    </div>
  
    <div id="metrics_summary_div" class="mb-3" style="margin-top:30px;"></div>

    <div class="table-responsive-xl table-wrap">
        <table class="table table-hover" id="fa_reports_table" style="width:100%;"></table>
    </div>
</div>
    
<script src="../js/coms.js"></script> 

<div class="modal fade" id="fa_reports_Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 80%; width: 80%;">
      <form id="fa_form" action="insert.php" method="POST">
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
                 <h6 class="text-uppercase mb-3" style="color:#E1AD01; font-weight: 800;">Request Details</h6>
                <div class="row">
                  <div class="form-group col-md-5">
                     <label>Ticket No</label>
                      <input type="text" class="form-control" name="ticket_no" id="ticket_no"></input>
                  </div>
                  <div class="form-group col-md-5">
                    <label>Requesting Dept/Branch</label>
                    <input type="text" class="form-control" name="requested_db" id="str_name" readonly>
                  </div>
                  <div class="form-group col-md-5">
                    <label>Requesting Employee</label>
                    <input type="text" class="form-control" name="requested_by" id="full_name" readonly>
                  </div>
                  <div class="form-group col-md-5">
                    <label>Ticket Created</label>
                    <input type="text" class="form-control" name="ticket_created" id="ticket_created" readonly>
                  </div>
                  <div class="form-group col-md-5">
                    <label>Item Code</label>
                    <input type="text" class="form-control" name="item_code" id="item_code" readonly>
                  </div>
                  <div class="form-group col-md-5">
                    <label>Description</label>
                    <input type="text" class="form-control" name="description" id="description" readonly>
                  </div>
                  <div class="form-group col-md-5">
                    <label>Serial Number</label>
                    <input type="text" class="form-control" name="serial_number" id="serial_number" >
                  </div>

                   <div class="form-group col-md-5">
                    <label>Asset Tag Number</label>
                    <input type="text" class="form-control" name="asset_tag_number" id="asset_tag_number" >
                  </div>

               


                  <div class="form-group col-md-12">
                    <label>Purpose of Request (From Store/Dept User)</label>
                    <textarea class="form-control" name="purpose_of_request" id="purpose_of_request" style="height: 150px;" readonly></textarea>
                  </div>

                   <div class="form-group col-md-12">
                    <label>Purpose of Request (Rephrase for Printing)</label>
                    <textarea class="form-control" name="revised_request" id="revised_request"  style="height: 150px;" maxlength="70"></textarea>
                  </div>
                   
                  <div class="form-group col-md-5">
                    <label>Item Inspected/Received By</label>
                    <input type="text" class="form-control" name="item_received_by" id="it_desc" readonly>
                  </div>
                  <input type="hidden" class="form-control" name="received_by" value="<?php echo $_SESSION['tech_id'] ?? ''; ?>" readonly>
                  <div class="form-group col-md-5">
                    <label>Date Inspected/Received</label>
                    <input type="text" class="form-control" name="date_received" id="date_received" >
                  </div>
                  <div class="form-group col-md-5">
                    <label>Noted By</label>
                    <input type="text" class="form-control" name="noted_by_desc" id="noted_by_desc" readonly>
                  </div>
                  <div class="form-group col-md-5">
                     <label>Status</label>
                    <select class="form-control" name="status" id="status">
                        <option value="">UPDATE STATUS</option>
                        <option value="PRINTED">PRINTED</option>
                        <option value="VERIFIED">VERIFIED</option>
                        <option value="APPROVED">APPROVED</option>
                        <option value="REJECTED">REJECTED</option>
                        <option value="COMPLETED">COMPLETED</option>
                    </select>
                  </div>

                  <div class="form-group col-md-4 date-input-container" id="datePrintedGroup" style="display: none;">
                        <label>Date Printed</label>
                        <input type="datetime-local" class="form-control status-date-input" name="date_printed" id="date_printed" disabled>
                  </div>

                    <div class="form-group col-md-4 date-input-container" id="dateVerifiedGroup" style="display: none;">
                        <label>Date Verified</label>
                        <input type="datetime-local" class="form-control status-date-input" name="date_verified" id="date_verified" disabled>
                    </div>

                    <div class="form-group col-md-4 date-input-container" id="dateApprovedGroup" style="display: none;">
                        <label>Date Approved</label>
                        <input type="datetime-local" class="form-control status-date-input" name="date_approved" id="date_approved" disabled>
                    </div>

                     <div class="form-group col-md-4 date-input-container" id="dateRejectedGroup" style="display: none;">
                        <label>Date Rejected</label>
                        <input type="datetime-local" class="form-control status-date-input" name="date_rejected" id="date_rejected" disabled>
                    </div>

                    <div class="form-group col-md-4 date-input-container" id="dateCompletedGroup" style="display: none;">
                        <label>Date Completed</label>
                        <input type="datetime-local" class="form-control status-date-input" name="date_completed" id="date_completed" disabled>
                    </div>
                </div>
              </div>


                <div class="col-md-4 pt-2 pb-2" style="border-radius: 0 8px 8px 0;">
                 <div class="form-group col-md-12" id="technical_workoutput_section">
                    <label>Workoutput (Under Assigned Support Evaluation)</label>
                    <textarea class="form-control" name="technical_workoutput" id="technical_workoutput" style="height: 350px;"></textarea>
                  </div>
                  
                  <div id="additional_technical_fields">
                      <label>Problem Reported:</label>
                      <div class="form-group col-md-12">
                        <textarea class="form-control" name="problem_reported" id="problem_reported" style="height: 120px;" required readonly> </textarea>
                      </div>
                       <label>Verification/Findings: </label>
                      <div class="form-group col-md-12">
                        <textarea class="form-control" name="verification_findings" id="verification_findings" style="height: 120px;" required readonly></textarea>
                      </div>
                       <label>Work Done/Technical Solutions Provided:</label>
                      <div class="form-group col-md-12">
                        <textarea class="form-control" name="work_done" id="work_done" style="height: 120px;" required readonly></textarea>
                      </div>
                       <label>Status/Work Output:</label>
                      <div class="form-group col-md-12">
                        <textarea class="form-control" name="status_workoutput" id="status_workoutput" style="height: 120px;" required readonly></textarea>
                      </div>
                       <label>Recommendations/Suggestions:</label>
                      <div class="form-group col-md-12">
                        <textarea class="form-control" name="recommendation" id="recommendation" style="height: 120px;" required readonly></textarea>
                      </div>
                  </div>

              </div>

              <div class="col-md-3 border-right pt-2 pb-2" style="background: linear-gradient(to bottom, #ffffff, #f0f3f7);">
            <h6 class="text-uppercase mb-3" style="color:#E1AD01; font-weight: 800;">Asset Request Progress</h6>
                  <div class="tracking-container" style="max-height: 500px; overflow-y: auto; padding-right: 10px;">
                      <ul class="tracking-timeline" id="trackingMap"></ul>
                  </div>
                   <h6 class="text-uppercase mb-3" style="color:#213456; font-weight: 800;">Remarks Thread</h6>
                
                <div id="remarks_thread_container" class="chat-container">
                
                </div>
                
                <div class="chat-input-area mt-3">
                    <textarea class="form-control" id="new_remark_input" rows="2" placeholder="Type a new remark..."></textarea>
                    <button type="button" class="btn btn-sm w-100 mt-2" id="btn_send_remark" style="background-color: #E1AD01; color: #213456; font-weight: 700;">
                        <i class="fas fa-paper-plane"></i> Send Remark
                    </button>
                </div>
          </div>

               
            </div>
          </div>

          <div class="modal-footer">
            <input type="hidden" name="operation" id="operation" value="save_request">
            <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">
              <button type="submit" class="btn"><strong>SAVE FIXED ASSET</strong></button>
          </div>
        </div>
      </form>
    </div>
</div>
<div class="modal fade" id="dataModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="pdfForm" action="print_form.php" method="POST" style="width: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-file-pdf mr-2"></i> PDF Generation Confirmation
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

           

                <div class="modal-body text-center py-4">
                    <div class="confirmation-text mb-4">
                        <p class="lead mb-1">Do you want to generate a report for this Fixed Asset form?</p>
                        <span class="text-muted">Review the Ticket Number below before proceeding.</span>
                    </div>

                    <div class="row justify-content-center">
                        <div class="form-group col-md-8 text-left">
                            <label for="modal_ticket_no" class="font-weight-bold text-secondary">Ticket No</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                                </div>
                                <input type="text" class="form-control" name="ticket_no" id="modal_ticket_no" readonly>
                            </div>
                        </div>
                             <div class="progress-container">
                    <div id="loadingBar" class="progress-bar-fill"></div>
                </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary px-4 py-2 rounded-pill" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary-custom">
                        <span class="btn-text">Generate PDF</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
  window.user_id = <?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
  var reptable;
  
  $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
      if (settings.nTable.id !== 'fa_reports_table') return true;
      
      let filterYear = $('#filter_year').val();
      let filterMonth = $('#filter_month').val();
      let dateStr = data[3] || ''; 
      
      if (!filterYear && !filterMonth) return true;
      
      let rowDate = new Date(dateStr);
      if (isNaN(rowDate.getTime())) return true;
      
      let rowYear = rowDate.getFullYear().toString();
      let rowMonth = (rowDate.getMonth() + 1).toString().padStart(2, '0');
      
      if (filterYear && rowYear !== filterYear) return false;
      if (filterMonth && rowMonth !== filterMonth) return false;
      
      return true;
  });

  $('.filter-trigger').change(function() {
      if (reptable) {
          reptable.draw(); 
          if($(this).attr('id') === 'filter_status') {
              applyStatusFilter();
          }
      }
      refreshData();
  });

  function getUrlParam(param) {
      var urlParams = new URLSearchParams(window.location.search);
      return urlParams.get(param);
  }
  var targetTicket = getUrlParam('ticket_no');

 function refreshData() {
      let month = $('#filter_month').val();
      let year = $('#filter_year').val();
      let status = $('#filter_status').val();

      $.post('fetchdata/fetch_data.php', {
          mode: 'fa_reports_tbl',
          month: month,
          year: year,
          status: status 
      }, function(response) {
          if ($.fn.DataTable.isDataTable('#fa_reports_table')) {
              reptable.clear().rows.add(response.table_data || []).draw(false);
              applyStatusFilter(); 
          } else {
              admin_datatable(response);
          }
          
          if(response.metrics) {
              updateMetricsUI(response.metrics);
          }
      }, 'json');
  }

  refreshData();
  setInterval(function () {
      refreshData();
  }, 15000);

  function getFAData(month = '', year = '') {
      $.post('fetchdata/fetch_data.php', {
          mode: 'fa_reports_tbl', 
          month: month, 
          year: year
      }, function(response) {
          admin_datatable(response);
          if(response.metrics) {
              updateMetricsUI(response.metrics);
          }
      }, 'json');
  }

  function updateMetricsUI(metrics) {
      let html = '<div class="row d-flex justify-content-start w-100">';
      
      metrics.forEach(function(item) {
          let statName = item.status ? item.status.toUpperCase() : 'UNKNOWN';
           html += `
    <div class="col-md-2 col-sm-4 mb-2">
        <div class="card p-3 text-center shadow-sm" style="border-radius: 12px; border: 1px solid #e9ecef; background-color: white;">
            <h6 class="mb-1 text-truncate" style="color: #54699e; font-weight: 800; font-size: 0.75rem;">${statName}</h6>
            <h3 class="mb-0 count-${(item.status || 'unknown').toLowerCase()}" style="color: #E1AD01; font-weight: 900;">${item.count || 0}</h3>
        </div>
    </div>`;
      });
      html += '</div>';
      $('#metrics_summary_div').html(html);
  }

  function applyStatusFilter() {
      if (reptable) {
          let statusVal = $('#filter_status').val();
          reptable.column(10).search(statusVal ? '^' + statusVal + '$' : '', true, false).draw();
      }
  }

  function admin_datatable(response){
    const dataset = response.table_data || [];
    
    reptable = $("#fa_reports_table").DataTable({
      "dom": '<"pull-left"lf><"pull-right">tip',
      stateSave: true,
      "bDestroy": true,
      "responsive": true, 
      "lengthChange": false, 
      "autoWidth": false,
      language: {
        emptyTable: "No fixed asset reports",
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
        {title:"Ticket Date", data:"ticket_created","defaultContent": ""},
        {title:"Description", data:"description","defaultContent": ""},
        {title:"Serial", data:"serial_number","defaultContent": ""},
        {title:"Received by", data:"it_desc","defaultContent": ""},
        {title:"Noted by", data:"noted_by_desc","defaultContent": ""},
        {title:"Date Received", data:"date_received","defaultContent": ""},
        {title:"Status", data:"status","defaultContent": ""},
       {
          title: "Action", 
          data: null, 
          render: function(data, type, row){
            let buttons = `<button type='button' class='btn btn-primary' onclick='openViewModal(this)'><i class='fas fa-eye'></i> View</button>`;
            let currentStatus = (data.status || '').toUpperCase();
            let allowedPrintStatuses = ['VERIFIED', 'APPROVED', 'COMPLETED', 'PRINTED'];
            
            if (allowedPrintStatuses.includes(currentStatus)) {
                buttons += `<button type='button' class='btn btn-success print-btn' data-id='${data.ticket_no}'><i class='fas fa-file-pdf'></i> Print</button>`;
            }

            return `<div style="display: flex; gap: 5px;">${buttons}</div>`;
          }
        }
      ],
      rowCallback: function(row, data, index){
        if(data['msg_cnt'] == '1'){
          $(row).find('td').css("font-weight", "bold");
        }
      },
      initComplete: function() {
        applyStatusFilter();

        if (targetTicket) {
          setTimeout(function() {
            var foundRow = null;
            reptable.rows().every(function () {
              var rowData = this.data();
              if (rowData && rowData.ticket_no == targetTicket) {
                foundRow = this.node();
              }
            });

            if (foundRow) {
              $(foundRow).find('.btn-primary').trigger('click');
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
          }, 600);
          targetTicket = null;
        }
      }
    });
  }

  $(document).on('submit', '#fa_form', function(event) {
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
            $('#fa_form')[0].reset();
            $('#fa_reports_Modal').modal('hide');
           refreshData();
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

function openViewModal(btn) {
    var tr = $(btn).closest('tr');
    var data = $('#fa_reports_table').DataTable().row(tr).data();
    if (!data) return;

    $('#ticket_no').val(data.ticket_no || '');
    $('#str_name').val(data.str_name || '');
    $('#full_name').val(data.full_name || '');
    $('#ticket_created').val(data.ticket_created || '');
    $('#item_code').val(data.item_code || '');
    $('#description').val(data.description || '');
    $('#serial_number').val(data.serial_number || '');
    $('#asset_tag_number').val(data.asset_tag_number || '');
    $('#purpose_of_request').val(data.purpose_of_request || '');
    $('#technical_workoutput').val(data.technical_workoutput || '');
    $('#problem_reported').val(data.problem_reported || '');
    $('#verification_findings').val(data.verification_findings || '');
    $('#work_done').val(data.work_done || '');
    $('#status_workoutput').val(data.status_workoutput || '');
    $('#recommendation').val(data.recommendation || '');
    $('#revised_request').val(data.revised_request || '');
    $('#it_desc').val(data.it_desc || '');
    $('#noted_by_desc').val(data.noted_by_desc || '');
    $('#date_received').val(data.date_received || '');
    $('#status').val(data.status || '');

       var isTechnical = data['is_technical'] !== undefined && data['is_technical'] !== null ? parseInt(data['is_technical']) : 1;
       if (isTechnical === 1) {
          $('#signature_attachment_section').hide();
          $('#file-input').prop('required', false);
          
          $('#technical_workoutput_section').hide();
          $('#additional_technical_fields').show();
      } else {
          $('#signature_attachment_section').show();
          $('#file-input').prop('required', true);
          
          $('#technical_workoutput_section').show();
          $('#additional_technical_fields').hide();
      }
      if (data['it_desc'] && data['it_desc'].trim() !== "") {
          $('#it_desc').val(data['it_desc']);
          $('#item_received_by_hidden').val(""); 
      } else {
          $('#it_desc').val(loggedInName);
          $('#item_received_by_hidden').val(loggedInId);
      }

      if (data['noted_by_desc'] && data['noted_by_desc'].trim() !== "") {
          $('#noted_by_desc').val(data['noted_by_desc']);
          $('#noted_by_hidden').val("");
      } else {
          $('#noted_by_desc').val(loggedInName);
          $('#noted_by_hidden').val(loggedInId);
      }


    $('#operation').val("save_request");
    
    $('#fa_reports_Modal').modal('show');

    if (typeof getinfo === "function") getinfo(data.ticket_no, 'remarks', window.user_id || '');
    loadRemarks(data.ticket_no);
    loadTimeline(data.ticket_no, data);
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
                      let userName = rmk.it_desc ? rmk.it_desc : 'System';
                      html += `
                          <div class="chat-message">
                                <span style="font-size: 11px; color: #64748b; margin-bottom: 4px;"><strong>${rmk.user_fullname || 'System'}</strong> • ${rmk.date_remarks}</span>
                              <div class="chat-bubble">${rmk.remarks_note}</div>
                          </div>
                      `;
                  });
              } else {
                  html = `<div class="text-center mt-4 text-muted" style="font-size: 12px; font-style: italic;">No remarks found. Start the conversation!</div>`;
              }
              
              $('#remarks_thread_container').html(html);
            
              var chatDiv = document.getElementById("remarks_thread_container");
              if (chatDiv) chatDiv.scrollTop = chatDiv.scrollHeight;
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

      var $btn = $(this);
      $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');

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
                  Swal.fire({ icon: 'success', title: 'Sent!', timer: 1000, showConfirmButton: false });
              } else {
                  Swal.fire('Error', response.message, 'error');
              }
          },
          error: function(xhr) {
              Swal.fire('Error', 'Communication failed.', 'error');
              console.error(xhr.responseText);
          },
          complete: function() {
              $btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Send Remark');
          }
      });
  });
function loadTimeline(ticket_no, rowData) {
    var target = $('#trackingMap');
    target.html('<p class="text-muted" style="font-size: 12px; margin-top: 10px;">Loading timeline...</p>');

    var isTechnical = (rowData && rowData.is_technical !== undefined && rowData.is_technical !== null) 
        ? parseInt(rowData.is_technical) 
        : 1;

    $.ajax({
        url: 'get_first_comment.php',
        type: 'POST',
        dataType: 'json',
        data: { ticket_no: ticket_no },
        success: function(response) {
            const statusLevels = {
                'submitted': 1, 'noted': 2, 'validated': 3, 
                'verified': 4, 'printed': 5, 'approved': 6, 'rejected': 6, 'completed': 7
            };

            let dbStatus = (response.status || "").toLowerCase().trim();
            let currentLevel = statusLevels[dbStatus] || 0; 

            let trackSteps = [
                { desc: "Request submitted by store/user", date: response.date_created, reqLevel: 0 },
                { desc: "Under assigned support evaluation", date: response.date_created, reqLevel: 0 }
            ];

            if (isTechnical === 1) {
                trackSteps.push(
                    { desc: "Submitted to technical/dept head", date: response.date_submitted, reqLevel: 1 },
                    { desc: "Approved and noted by technical/dept head", date: response.date_noted, reqLevel: 2 }
                );
            }

            trackSteps.push(
                { desc: "For admin support validation", date: null, reqLevel: isTechnical === 1 ? 2 : 1 }, 
                { desc: "Validated by admin support", date: response.date_validated, reqLevel: isTechnical === 1 ? 3 : 2 },
                { desc: "For administrative verification", date: null, reqLevel: isTechnical === 1 ? 3 : 2 }, 
                { desc: "Verified by the administrator", date: response.date_verified, reqLevel: isTechnical === 1 ? 4 : 3 },
                { desc: "For printing request form", date: null, reqLevel: isTechnical === 1 ? 4 : 3 }, 
                { desc: "Printed", date: response.date_printed, reqLevel: isTechnical === 1 ? 5 : 4 },
                { desc: "For General Manager Approval", date: null, reqLevel: isTechnical === 1 ? 5 : 4 }
            );

            if (dbStatus === 'rejected') {
                trackSteps.push(
                    { desc: "Rejected by General Manager", date: response.date_rejected || response.date_updated, reqLevel: isTechnical === 1 ? 6 : 5, isRejected: true }
                );
            } else {
                trackSteps.push(
                    { desc: "Approved by General Manager", date: response.date_approved, reqLevel: isTechnical === 1 ? 6 : 5 },
                    { desc:  "Transferred to PD for Procurement", date: null, reqLevel: isTechnical === 1 ? 6 : 5 }, 
                    { desc: "Asset replaced / Completed", date: response.date_completed, reqLevel: isTechnical === 1 ? 7 : 6 }
                );
            }

            let timelineHtml = '';
            trackSteps.forEach((step) => {
                let statusClass = (currentLevel >= step.reqLevel) ? "completed" : "";
                
                let dateDisplay = step.date ? `<div class="timeline-date">${step.date}</div>` : '';

                let iconStyle = step.isRejected ? 'style="background-color: #dc3545; border-color: #dc3545;"' : '';
                let textStyle = step.isRejected ? 'style="color: #dc3545; font-weight: bold;"' : '';

                timelineHtml += `
                    <li class="timeline-item ${statusClass}">
                        <div class="timeline-icon" ${iconStyle}></div>
                        <div class="timeline-desc" ${textStyle}>${step.desc}</div>
                        ${dateDisplay}
                    </li>
                `;
            });

            target.html(timelineHtml);
        },
        error: function() {
            target.html('<li class="text-danger">Failed to load timeline.</li>');
        }
    });
}

document.getElementById('status').addEventListener('change', function() {
    const status = this.value;
    
    document.querySelectorAll('.date-input-container').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.status-date-input').forEach(el => {
        el.disabled = true;
        el.value = ''; 
    });

    if (status) {
        let targetInputId = 'date_' + status.toLowerCase();
        let targetGroup = document.getElementById('date' + status.charAt(0).toUpperCase() + status.slice(1).toLowerCase() + 'Group');
        let targetInput = document.getElementById(targetInputId);

        if (targetInput && targetGroup) {
            targetGroup.style.display = 'block';
            targetInput.disabled = false;

            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            
            targetInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
        }
    }
});

document.getElementById('pdfForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const loadingBar = document.getElementById('loadingBar');
    const submitBtn = document.getElementById('btnSubmit');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Generating...';

    setTimeout(() => {
        loadingBar.style.width = '100%';
    }, 50);

    setTimeout(() => {
        form.submit();
    }, 1050); 
});

$('#dataModal').on('hidden.bs.modal', function () {
    document.getElementById('loadingBar').style.width = '0%';
    const submitBtn = document.getElementById('btnSubmit');
    submitBtn.disabled = false;
    submitBtn.innerHTML = 'Generate PDF';
});

$(document).on('click', '.print-btn', function() {
    let ticket_no = $(this).data('id');
    
    $('#pdfForm')[0].reset();
    
    $('#modal_ticket_no').val(ticket_no);
    
    $('#dataModal').modal('show');
});
</script>


