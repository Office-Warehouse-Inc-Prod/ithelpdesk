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
        session_unset();
        session_destroy();
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

include 'tech_header.php';
$inactive = 180;
if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > $inactive)){
    session_unset();
    session_destroy();
    header("Location: techdashboard.php");
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
                      <textarea class="form-control" name="technical_workoutput" id="technical_workoutput" style="height: 100px;"></textarea>
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
        <input type="hidden" name="operation" id="operation" value="update_request">
        <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id']; ?>">
        <button type="submit" class="btn" style="background-color: #213456; color: #213456;">UPDATE REQUEST</button>
      </div>
    </div>
  </form>
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
                'verified': 4, 'printed': 5, 'approved': 6, 'completed': 7
            };

            let dbStatus = (response.status || "").toLowerCase().trim();
            let currentLevel = statusLevels[dbStatus] || 0; 

            const trackSteps = [
                { desc: "Request submitted by store/user", date: response.date_created, reqLevel: 0 },
                { desc: "Under assigned support evaluation", date: response.date_created, reqLevel: 0 },
                { desc: "Submitted to technical/dept head", date: response.date_submitted, reqLevel: 1 },
                { desc: "Approved and noted by technical/dept head", date: response.date_noted, reqLevel: 2 },
                { desc: "For admin support validation", date: null, reqLevel: 2 }, 
                { desc: "Validated by admin support", date: response.date_validated, reqLevel: 3 },
                { desc: "For administrative verification", date: null, reqLevel: 3 }, 
                { desc: "Verified by the administrator", date: response.date_verified, reqLevel: 4 },
                { desc: "For printing request form", date: null, reqLevel: 4 }, 
                { desc: "Printed", date: response.date_printed, reqLevel: 5 },
                { desc: "For General Manager Approval", date: null, reqLevel: 5 }, 
                { desc: "Approved by General Manager", date: response.date_approved, reqLevel: 6 },
                { desc: "Ready for asset replacement", date: null, reqLevel: 6 }, 
                { desc: "Asset replaced / Completed", date: response.date_completed, reqLevel: 7 }
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