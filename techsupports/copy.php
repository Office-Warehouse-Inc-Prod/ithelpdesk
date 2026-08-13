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
            echo json_encode([["remarks_note" => "Error loading remarks.", "user_fullname" => "System", "date_remarks" => ""]]);
        }
        exit;
    }
}

include 'tech_header.php';
$inactive = 180;
if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > $inactive)){
    session_unset();
    // removed session_destroy() to avoid "headers already sent" warnings
    echo '<script>setTimeout(function(){ window.location.href = "techdashboard.php"; }, 180000);</script>';
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
    
    <!-- Custom UI Enhancements -->
    <style>
        /* Form & Input Enhancements */
        .form-control[readonly] {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            color: #64748b;
        }
        .section-header {
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        /* Modern Timeline (Tracking Map) */
        .tracking-container {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .tracking-timeline {
            position: relative;
            list-style: none;
            padding-left: 1.5rem;
            margin: 0;
        }
        .tracking-timeline::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 5px;
            width: 2px;
            background: #cbd5e1;
            z-index: 1;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .timeline-item:last-child {
            margin-bottom: 0;
        }
        .timeline-icon {
            position: absolute;
            left: -1.5rem;
            top: 0.25rem;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #f8fafc;
            border: 3px solid #cbd5e1;
            z-index: 2;
            transition: all 0.3s ease;
        }
        .timeline-desc {
            font-weight: 600;
            color: #64748b;
            font-size: 0.9rem;
            padding-left: 0.75rem;
        }
        .timeline-date {
            font-size: 0.8rem;
            color: #94a3b8;
            padding-left: 0.75rem;
            margin-top: 0.2rem;
        }
        
        /* State Modifiers for Timeline */
        .timeline-item.completed .timeline-icon {
            background: #fff;
            border-color: #10b981; /* Emerald Green */
        }
        .timeline-item.completed .timeline-desc {
            color: #0f172a;
        }
        .timeline-item.rejected .timeline-icon {
            background: #fff;
            border-color: #ef4444; /* Red */
        }
        .timeline-item.rejected .timeline-desc {
            color: #ef4444;
        }

        /* Chat & Remarks Interface */
        .chat-container {
            height: 350px;
            overflow-y: auto;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.25rem;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }
        .chat-message {
            margin-bottom: 1rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .chat-meta {
            font-size: 0.75rem;
            color: #64748b;
            margin-bottom: 0.25rem;
            margin-left: 0.5rem;
        }
        .chat-bubble {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
            border-radius: 18px;
            border-top-left-radius: 4px;
            font-size: 0.9rem;
            color: #334155;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            max-width: 90%;
            word-wrap: break-word;
        }
        .chat-input-area textarea {
            border-radius: 8px;
            resize: none;
        }
        
        /* Modal Custom Scrollbar */
        .tracking-container::-webkit-scrollbar,
        .chat-container::-webkit-scrollbar {
            width: 6px;
        }
        .tracking-container::-webkit-scrollbar-track,
        .chat-container::-webkit-scrollbar-track {
            background: transparent;
        }
        .tracking-container::-webkit-scrollbar-thumb,
        .chat-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
</head>

<div class="container" style="max-width:1800px;">
    <div class="table-responsive-xl mt-4">
        <table class="table table-hover" id="new_rep_table"></table>
    </div>
</div>

<script src="../js/coms.js"></script> 
<div class="modal fade" id="newrpt_Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width: 95%;"> 
        <form id="newrpt_form" action="insert.php" method="POST">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold" style="color:#213456;">
                        <i class="fas fa-boxes mr-2"></i> Fixed Asset Information
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-0">
                    <div class="row m-0">
                        <!-- Left Column: Request Details -->
                        <div class="col-lg-7 p-4 border-right bg-white">
                            <h6 class="text-uppercase mb-4 section-header" style="color:#213456; font-weight: 800;">Request Details</h6>
                     
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class="text-muted small font-weight-bold">Ticket No</label>
                                    <input type="text" class="form-control" name="ticket_no" id="ticket_no" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="text-muted small font-weight-bold">Requesting Dept/Branch</label>
                                    <input type="text" class="form-control" name="requested_db" id="str_name" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="text-muted small font-weight-bold">Requesting Employee</label>
                                    <input type="text" class="form-control" name="requested_by" id="full_name" readonly>
                                </div>

                                <div class="form-group col-md-4">
                                    <label class="text-muted small font-weight-bold">Ticket Created</label>
                                    <input type="text" class="form-control" name="ticket_created" id="ticket_created" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="text-muted small font-weight-bold">Item Code</label>
                                    <input type="text" class="form-control" name="item_code" id="item_code">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="text-muted small font-weight-bold">Serial Number</label>
                                    <input type="text" class="form-control" name="serial_number" id="serial_number">
                                </div>
                                
                                <div class="form-group col-md-12">
                                    <label class="text-muted small font-weight-bold">Description</label>
                                    <input type="text" class="form-control" name="description" id="description" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="text-muted small font-weight-bold">Purpose of Request</label>
                                    <textarea class="form-control" name="purpose" id="purpose_of_request" style="height: 80px;" readonly></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="text-muted small font-weight-bold">Problem Reported</label>
                                    <textarea class="form-control" name="problem_reported" id="problem_reported" style="height: 80px;"></textarea>
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="text-muted small font-weight-bold">Verification/Findings</label>
                                    <textarea class="form-control" name="verification_findings" id="verification_findings" style="height: 80px;"></textarea>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="text-muted small font-weight-bold">Work Done/Tech Solutions</label>
                                    <textarea class="form-control" name="work_done" id="work_done" style="height: 80px;"></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="text-muted small font-weight-bold">Status/Work Output</label>
                                    <textarea class="form-control" name="status_workoutput" id="status_workoutput" style="height: 80px;"></textarea>
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="text-muted small font-weight-bold">Recommendations/Suggestions</label>
                                    <textarea class="form-control" name="recommendation" id="recommendation" style="height: 80px;"></textarea>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="text-muted small font-weight-bold">Item Received By</label>
                                    <input type="text" class="form-control" name="item_received_by" id="it_desc" readonly>
                                </div>
                                
                                <input type="hidden" name="received_by" value="<?php echo $_SESSION['tech_id']; ?>" readonly>

                                <div class="form-group col-md-6">
                                    <label class="text-muted small font-weight-bold">Date Received</label>
                                    <input type="text" class="form-control" name="date_received" id="date_received" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column: Progress & Remarks -->
                        <div class="col-lg-5 p-4 bg-light">
                            <h6 class="text-uppercase mb-4 section-header" style="color:#E1AD01; font-weight: 800;">Asset Request Progress</h6>
                            
                            <!-- Timeline UI -->
                            <div class="tracking-container">
                                <ul class="tracking-timeline" id="trackingMap">
                                    <!-- Dynamic Timeline injected via JS -->
                                </ul>
                            </div>
                            
                            <h6 class="text-uppercase mt-4 mb-3 section-header" style="color:#213456; font-weight: 800;">Remarks Thread</h6>
                            
                            <!-- Chat UI -->
                            <div id="remarks_thread_container" class="chat-container">
                                <!-- Dynamic Remarks injected via JS -->
                            </div>
                            
                            <div class="chat-input-area mt-3">
                                <div class="input-group">
                                    <textarea class="form-control" id="new_remark_input" rows="2" placeholder="Type a new remark..."></textarea>
                                    <div class="input-group-append">
                                        <button type="button" class="btn h-100 px-4" id="btn_send_remark" style="background-color: #E1AD01; color: #213456; border-radius: 0 8px 8px 0; font-weight: bold;">
                                            <i class="fas fa-paper-plane d-block mb-1"></i> Send
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0">
                    <input type="hidden" name="operation" id="operation" value="update_request">
                    <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn px-4 py-2" style="background-color: #213456; color: white; font-weight: bold;">
                        <i class="fas fa-save mr-1"></i> UPDATE REQUEST
                    </button>
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
                        setTimeout(function() { $(foundRow).css('background-color', ''); }, 1200);
                    });
                }
            }
        }, 600);
    }

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
                searchPlaceholder: "Search records..."
            },
            pageLength: 10,
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
                {title:"Status", data:"status","defaultContent": '<span class="badge badge-info">Pending</span>'},
                {title:"Action", data:null,"defaultContent": "<Button class='btn btn-sm text-white edit-btn' style='background-color:#E1AD01;' name='update'><i class='fas fa-edit'></i> Edit</Button>"}
            ],
            rowCallback: function(row, data, index){
                if(data['msg_cnt'] == '1'){
                    $(row).find('td').css("font-weight", "bold");
                }
            }
        });

        setInterval(function () { getdata(); }, 60000);
        
        $('#new_rep_table tbody').off('click', 'button').on('click', 'button', function () {
            var data = reptable.row($(this).parents('tr')).data();
            if(!data) return;
            
            // Populate Details
            $('#ticket_no').val(data['ticket_no']);
            $('#str_name').val(data['str_name']);
            $('#full_name').val(data['full_name']);
            $('#ticket_created').val(data['ticket_created']);
            $('#item_code').val(data['item_code']);
            $('#description').val(data['description']);
            $('#serial_number').val(data['serial_number']);
            $('#purpose_of_request').val(data['purpose_of_request']);
            $('#problem_reported').val(data['problem_reported']);
            $('#verification_findings').val(data['verification_findings']);
            $('#work_done').val(data['work_done']);
            $('#status_workoutput').val(data['status_workoutput']);
            $('#recommendation').val(data['recommendation']);
            $('#it_desc').val(data['it_desc']);
            $('#date_received').val(data['date_received']);
            $('#status').val(data['status']);

            $('#action').val("Update");
            $('#operation').val("update_request"); 

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
                        'verified': 4, 'printed': 5, 'approved': 6, 'rejected': 6, 'completed': 7
                    };

                    let dbStatus = (response.status || "").toLowerCase().trim();
                    let currentLevel = statusLevels[dbStatus] || 0; 

                    let trackSteps = [
                        { desc: "Request submitted by store/user", date: response.date_created, reqLevel: 0 },
                        { desc: "Under assigned support evaluation", date: response.date_created, reqLevel: 0 }
                    ];

                    // Assuming isTechnical is defined in tech_header or globally
                    let isTech = typeof isTechnical !== 'undefined' ? isTechnical : 1; 

                    if (isTech === 1) {
                        trackSteps.push(
                            { desc: "Submitted to technical/dept head", date: response.date_submitted, reqLevel: 1 },
                            { desc: "Approved and noted by technical/dept head", date: response.date_noted, reqLevel: 2 }
                        );
                    }

                    trackSteps.push(
                        { desc: "For admin support validation", date: null, reqLevel: isTech === 1 ? 2 : 1 }, 
                        { desc: "Validated by admin support", date: response.date_validated, reqLevel: isTech === 1 ? 3 : 2 },
                        { desc: "For administrative verification", date: null, reqLevel: isTech === 1 ? 3 : 2 }, 
                        { desc: "Verified by the administrator", date: response.date_verified, reqLevel: isTech === 1 ? 4 : 3 },
                        { desc: "For printing request form", date: null, reqLevel: isTech === 1 ? 4 : 3 }, 
                        { desc: "Printed", date: response.date_printed, reqLevel: isTech === 1 ? 5 : 4 },
                        { desc: "For General Manager Approval", date: null, reqLevel: isTech === 1 ? 5 : 4 }
                    );

                    if (dbStatus === 'rejected') {
                        trackSteps.push(
                            { desc: "Rejected by General Manager", date: response.date_rejected || response.date_updated, reqLevel: isTech === 1 ? 6 : 5, isRejected: true }
                        );
                    } else {
                        trackSteps.push(
                            { desc: "Approved by General Manager", date: response.date_approved, reqLevel: isTech === 1 ? 6 : 5 },
                            { desc: "Ready for Asset Replacement", date: null, reqLevel: isTech === 1 ? 6 : 5 }, 
                            { desc: "Asset replaced / Completed", date: response.date_completed, reqLevel: isTech === 1 ? 7 : 6 }
                        );
                    }

                    let timelineHtml = '';
                    trackSteps.forEach((step) => {
                        let statusClass = "";
                        if (step.isRejected && currentLevel >= step.reqLevel) {
                            statusClass = "rejected";
                        } else if (currentLevel >= step.reqLevel) {
                            statusClass = "completed";
                        }
                        
                        let dateDisplay = step.date ? `<div class="timeline-date"><i class="far fa-calendar-alt mr-1"></i>${step.date}</div>` : '';

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
                    $('#trackingMap').html('<li class="text-danger small">Failed to load progress timeline.</li>');
                }
            });
            $('#newrpt_Modal').modal('show');
        });
    }

    function loadRemarks(ticket_no) {
        $('#remarks_thread_container').html('<div class="text-center mt-5"><i class="fas fa-spinner fa-spin fa-2x" style="color:#cbd5e1;"></i></div>');
        
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
                                <div class="chat-meta">
                                    <strong>${rmk.user_fullname || 'System'}</strong> &bull; ${rmk.date_remarks}
                                </div>
                                <div class="chat-bubble shadow-sm">${rmk.remarks_note}</div>
                            </div>
                        `;
                    });
                } else {
                    html = `<div class="text-center mt-5 text-muted"><i class="far fa-comments fa-2x mb-2 d-block"></i> No remarks yet.</div>`;
                }
                $('#remarks_thread_container').html(html);
                var chatDiv = document.getElementById("remarks_thread_container");
                if(chatDiv) chatDiv.scrollTop = chatDiv.scrollHeight;
            },
            error: function(xhr) {
                $('#remarks_thread_container').html('<div class="text-danger text-center mt-3">Failed to fetch remarks.</div>');
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
                    const Toast = Swal.mixin({
                        toast: true, position: 'top-end', showConfirmButton: false, timer: 1500
                    });
                    Toast.fire({ icon: 'success', title: 'Remark sent' });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Communication failed.', 'error');
            }
        });
    });

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

    // Inactivity setup
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
}); 
</script>