<?php
include '../condb.php';
$con1 = new dbconfig();
$conn = $con1->getConnection(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mode'])) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
   
    $_SESSION['start'] = time();
    if ($_POST['mode'] === 'fa_tbl') {
        try {
            $sql = "SELECT 
                        r.ticket_no, r.date_created, r.concern, r.service_desc, r.subject,
                        GROUP_CONCAT(i.files_name SEPARATOR '|') AS attachment_files,
                        r.sub_id, r.f_deptsel, r.itsup, r.store, r.is_technical
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
                GROUP_CONCAT(i.files_name SEPARATOR '|') AS attachment_files, r.sub_id, r.f_deptsel, r.itsup, r.store, r.is_technical 
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
  session_unset();
  // removed session_destroy() to avoid "headers already sent" warnings
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
     <link rel="stylesheet" href="fix_asset.css" />
</head>

 
<div class="container" style="max-width:1800px;">
  <div class="table-responsive-xl">
    <table class="table table-hover" id="fix_asset_table"></table>
  </div>
</div>


  <script src="../js/coms.js"></script> 
  <div id="msg_thread" style="display:none;"></div>
  <div id="remarks_view" style="display:none;"></div>
  <div id="ticket_title" style="display:none;"></div>
  <div id="msg_cnt" style="display:none;"></div>
<div class="modal fade" id="fa_Modal" tabindex="-1" aria-hidden="true">
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
            
                <h6 class="text-uppercase mb-3" style="color:#213456; font-weight: 800;">Request Details</h6> 
                
                <div class="row">

                  <div class="form-group col-md-5">
                     <label>Ticket No</label>
                      <input type="text" class="form-control" name="ticket_no" id="ticket_no">
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
                    <input type="text" class="form-control" name="description" id="description">
                  </div>

                  <div class="form-group col-md-5">
                    <label>Serial Number</label>
                    <input type="text" class="form-control" name="serial_number" id="serial_number">
                  </div>

                   <div class="form-group col-md-5">
                    <label>Asset Tag Number</label>
                    <input type="text" class="form-control" name="asset_tag_number" id="asset_tag_number">
                  </div>

                    <div class="form-group col-md-12">
                    <label>Purpose of Request</label>
                    <textarea class="form-control" name="purpose_of_request" id="purpose_of_request" style="height: 150px;" readonly></textarea>
                  </div>

                   

                   <div class="form-group col-md-12">
                    <label>Purpose of Request (Rephrase for Printing)</label>
                    <textarea class="form-control" name="revised_request"  id="revised_request" style="height: 150px;" maxlength="70" required></textarea>
                  </div>

                    
                  <div class="form-group col-md-5">
                      <label>Item Inspected/Recieved By</label>
                      <input type="text" class="form-control" id="it_desc" readonly>
                      <input type="hidden" name="item_received_by" id="item_received_by_hidden">
                  </div>

                  <div class="form-group col-md-5">
                      <label>Noted by</label>
                      <input type="text" class="form-control" id="noted_by_desc" readonly>
                      <input type="hidden" name="noted_by" id="noted_by_hidden">
                  </div>
                    
                  <input type="hidden" class="form-control" name="received_by" value="<?php echo $_SESSION['tech_id'] ?? ''; ?>" readonly>

                  <div class="form-group col-md-5">
                    <label>Date Inspected/Received</label>
                    <input type="text" class="form-control" name="date_received" id="date_received" required>
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

              

            <div class="col-md-3 pt-2 pb-2" style=" background: linear-gradient(to bottom, #ffffff, #d7dce4);border-radius: 0 8px 8px 0;">
                  <h6 class="text-uppercase mb-3" style="color:#E1AD01; font-weight: 800;">Asset Request Progress</h6>
                  <div class="tracking-container" style="max-height: 450px; overflow-y: auto; padding-right: 10px;">
                      <ul class="tracking-timeline" id="trackingMap">
                      </ul>
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

          <div class="modal-footer d-flex justify-content-between align-items-center">
            <input type="hidden" name="operation" id="operation" value="update_request">
             <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">
              
              <div class="form-group col-md-5 mb-0" id="signature_attachment_section">
               <label style="font-weight: bold;" id="label_attached_file">Attach E-Signature</label>
               <div class="d-flex align-items-center">
                   <input id="file-input" type="file" name="files[]" class="form-control-file" accept=".png, .jpg, .jpeg" required>
               </div>
               <div id="signature_preview_container" class="mt-2" style="display: none;">
                   <span style="font-size: 11px; color: #555; display: block; margin-bottom: 3px;">Current Signature Preview:</span>
                   <img id="signature_preview_img" src="" alt="Signature Preview" style="max-height: 50px; border: 1px solid #ccc; border-radius: 4px; padding: 2px; background: #fff;">
               </div>
            </div>
           
            <button type="submit" class="btn"><strong>APPROVE REQUEST</strong></button>
          </div>
        </div>
      </form>
    </div>
</div>


<div class="modal fade" id="remarks_Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 60%; width: 60%;">
      <form id="remarks_submit_form" action="insert.php" method="POST">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #dc3545; border-bottom: 4px solid #E1AD01;">
                    <h5 class="modal-title text-white">Add Ticket Remarks</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Target Ticket No</label>
                        <input type="text" class="form-control" name="ticket_no" id="remarks_ticket_no" readonly />
                    </div>
                    <div class="form-group">
                        <label>Remarks Description</label>
                        <textarea class="form-control" name="remarks_adtech" id="modal_textarea_remarks" style="height: 120px;" placeholder="Type your notes here..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="operation" value="add_remarks_only">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Notes</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 25px 40px; border-radius: 8px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div class="spinner"></div>
        <h4 style="margin-top: 15px; color: #333; font-family: sans-serif; font-weight: 700;">Saving Request...</h4>
        <p style="margin: 0; color: #666; font-size: 14px;">Please wait while the email is being sent.</p>
    </div>
</div>
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
    $.post('fetchdata/fetch_data.php', {mode: 'fa_tbl'}, function(data){
      admin_datatable(data);
    }, 'json');
  }
  getdata();

  function admin_datatable(t){
    const dataset = t.fadata;
    reptable = $("#fix_asset_table").DataTable({
      "dom": '<"pull-left"lf><"pull-right">tip',
      stateSave: true,
      "bDestroy": true,
      "responsive": true, 
      "lengthChange": false, 
      "autoWidth": false,
      language: {
        emptyTable: "No for validation fixed asset reports",
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
        {title:"Date Received", data:"date_received","defaultContent": ""},
        {title:"Status", data:"status","defaultContent": ""},
        {title:"Update", data:null,"defaultContent": "<Button class='btn btn-danger' name='update'><i class='fas fa-edit'></i></Button>"},
        {title:"Is Technical", data:"is_technical", visible: false, "defaultContent": "0"} 
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

    $('#fix_asset_table tbody').off('click', 'button[name="update"]').on('click', 'button[name="update"]', function (e) {
      e.stopPropagation();
      var data = reptable.row($(this).parents('tr')).data();
      if(!data) return;

      var loggedInName = "<?= isset($_SESSION['fname']) ? addslashes($_SESSION['fname'] . ' ' . $_SESSION['lstname']) : '' ?>";
      var loggedInId = "<?= isset($_SESSION['tech_id']) ? addslashes($_SESSION['tech_id']) : '' ?>";

      $('#ticket_no').val(data['ticket_no']);
      $('#str_name').val(data['str_name']);
      $('#full_name').val(data['full_name']);
      $('#ticket_created').val(data['ticket_created']);
      $('#item_code').val(data['item_code']);
      $('#description').val(data['description']);
      $('#serial_number').val(data['serial_number']);
      $('#asset_tag_number').val(data['asset_tag_number']); 
      $('#purpose_of_request').val(data['purpose_of_request']); 
      $('#technical_workoutput').val(data['technical_workoutput']);
       $('#revised_request').val(data['revised_request']); 
      $('#problem_reported').val(data['problem_reported']);
      $('#verification_findings').val(data['verification_findings']);
      $('#work_done').val(data['work_done']);
      $('#status_workoutput').val(data['status_workoutput']);
      $('#recommendation').val(data['recommendation']);
      $('#date_received').val(data['date_received']);
      $('#status').val(data['status']);

      var isTechnical = data['is_technical'] !== undefined && data['is_technical'] !== null ? parseInt(data['is_technical']) : 1;
      
      if (isTechnical === 1) {
         
          
          $('#technical_workoutput_section').hide();
          $('#additional_technical_fields').show();
      } else {
      
          
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

      $('#action').val("Update");
      $('#operation').val("update_request"); 

      var tid = $(this).parent().siblings(':first').html() || data['ticket_no'];
      $('#tick_title').text("Ticket Number: " + tid);
      
      if (typeof displayAttachmentsFromData === "function") displayAttachmentsFromData(data);
      if (typeof getinfo === "function") getinfo(tid, 'remarks', user_id);
      loadRemarks(data['ticket_no']);

      $.ajax({
        url: 'get_first_comment.php', 
        type: 'POST',
        dataType: 'json', 
        data: { ticket_no: data['ticket_no'] },
        success: function(response) {
            const statusLevels = {
                'submitted': 1, 'noted': 2, 'validated': 3, 
                'verified': 4, 'printed': 5, 'approved': 6, 'completed': 7
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
                { desc: "For General Manager Approval", date: null, reqLevel: isTechnical === 1 ? 5 : 4 }, 
                { desc: "Approved by General Manager", date: response.date_approved, reqLevel: isTechnical === 1 ? 6 : 5 },
                { desc:  "Transferred to PD for Procurement", date: null, reqLevel: isTechnical === 1 ? 6 : 5 }, 
                { desc: "Asset replaced / Completed", date: response.date_completed, reqLevel: isTechnical === 1 ? 7 : 6 }
            );

            let timelineHtml = '';
            
            trackSteps.forEach((step) => {
                let statusClass = (currentLevel >= step.reqLevel) ? "completed" : "";
                let dateDisplay = step.date ? `<div class="timeline-date">${step.date}</div>` : '';

                timelineHtml += `
                    <li class="timeline-item ${statusClass}">
                        <div class="timeline-icon"></div>
                        <div class="timeline-desc">${step.desc}</div>
                        ${dateDisplay}
                    </li>
                `;
            });

            $('#trackingMap').html(timelineHtml);
        },
        error: function() {
            $('#trackingMap').html('<p class="text-danger">Failed to load progress timeline.</p>');
        },
        complete: function() {
            $('#fa_Modal').modal('show');
        }
      });
    });
  }
  $(document).on('click', '#btn_open_remarks', function() {
      var ticketNo = $('#ticket_no').val();
      
      if(!ticketNo) {
          Swal.fire({
              icon: 'warning',
              title: 'Validation Error',
              text: 'No active ticket number was found to append remarks to.'
          });
          return;
      }
      
      $('#remarks_ticket_no').val(ticketNo);
      $('#modal_textarea_remarks').val($('#remarks_adtech').val());
      $('#remarks_Modal').modal('show');
  });

  $(document).on('submit', '#remarks_submit_form', function(event) {
      event.preventDefault();
      var formData = new FormData(this);
      $('#remarks_adtech').val($('#modal_textarea_remarks').val());

      $.ajax({
          url: "insert.php",
          method: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          dataType: 'json',
          success: function(response) {
              if (response.status === 'success' || response.status === true) {
                  Swal.fire({
                      icon: 'success',
                      title: 'Remarks added successfully',
                      showConfirmButton: false,
                      timer: 1500
                  }).then(function() {
                      $('#remarks_Modal').modal('hide');
                      $('#fa_Modal').modal('hide');
                      getdata();
                      location.reload();
                  });
              } else {
                  Swal.fire({
                      icon: 'error',
                      title: 'Execution Failed',
                      text: response.message || 'Please check backend logs.'
                  });
              }
          }
      });
  });

  $(document).on('submit', '#fa_form', function(event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var formData = new FormData(this);
        
    var $submitBtn = $(this).find('button[type="submit"]');
    $('#loadingOverlay').css('display', 'flex');
    
    $submitBtn.prop('disabled', true).html('<strong>SAVING...</strong>');

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
            $('#fa_Modal').modal('hide');
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
        Swal.fire({
          icon: 'error',
          title: 'Save failed',
          text: message
        });
      },
      complete: function() {
        $('#loadingOverlay').hide();
        $submitBtn.prop('disabled', false).html('<strong>APPROVE REQUEST</strong>');
      }
    });
  });
});

  $('#file-input').on('change', function(e) {
      const file = e.target.files[0];
      if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
              $('#signature_preview_img').attr('src', e.target.result);
              $('#signature_preview_container').show();
          }
          reader.readAsDataURL(file);
      }
  });

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
                                <span style="font-size: 11px; color: #64748b; margin-bottom: 4px;"><strong>${rmk.user_fullname || 'System'}</strong> • ${rmk.date_remarks}</span>
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

// Global Utilities
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
</script>