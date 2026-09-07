<?php
$inactive = 180;
if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > $inactive)){
    session_unset();
    echo '<script>setTimeout(function(){ window.location.href = "adminpanel.php"; }, 180000);</script>';
    exit();
}
$_SESSION['start'] = time();
  
include 'admin.php';
include '../condb.php';
$con1 = new dbconfig();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transfer Reports</title>
    <link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/>
    <script src="../js/bootstrap-datetimepicker.min.js"></script>
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css" />
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.select.min.js"></script>
    <script src="../js/dataTables.responsive.min.js"></script>
    <script src="../js/fnReloadAjax.js"></script>
    <style>
        :root {
            --navy:#121C31;
            --navy2:#1a2a4a;
            --yellow:#EAAA00;
            --bg:#EEF2F7;
            --card:#ffffff;
            --card2:#F8FAFF;
            --text:#111827;
            --muted:#6B7280;
            --line:#E5E7EB;
            --shadow: 0 14px 34px rgba(17,24,39,.10);
            --radius:18px;
            --radius-sm:14px;
            --focus: 0 0 0 .2rem rgba(234,170,0,.18);
        }

        body {
            background: linear-gradient(to bottom, #ffffff, #99aac8);
            background-attachment: fixed; 
            margin: 0; 
            height: 100vh; 
        } 

        .table-responsive { 
            border-radius: 8px; 
            margin-top: 20px; 
            overflow: visible !important; 
            width: 100% !important; 
        }

        .dataTables_wrapper { 
            background: var(--card); 
            border: 1px solid var(--line); 
            border-radius: var(--radius); 
            box-shadow: var(--shadow); 
            padding: 14px; 
        }
        
        .dataTables_wrapper .pull-left { 
            flex-direction: row; 
            align-items: center; 
            justify-content: flex-start; 
            width: 100%; 
            gap: 40px; 
            margin-bottom: 20px; 
        }

        .dataTables_filter { 
        position: relative; 
        display: inline-block; 
        margin: 0 !important; 
        }
        .dataTables_filter label { 
        display: flex; 
        align-items: center; 
        margin-bottom: 0; 
        }
        .dataTables_filter::before { 
            content: "\f002"; 
            font-family: "Font Awesome 5 Free"; 
            font-weight: 900; 
            position: absolute; 
            left: 12px; top: 50%; 
            transform: translateY(-50%); 
            color: #213456; 
            z-index: 1; 
            opacity: 0.6; 
        }
        .dataTables_filter input { 
            border: 2px solid #e0e0e0 !important; 
            border-radius: 50px !important; 
            padding: 8px 15px 8px 35px !important; 
            width: 300px !important; 
            background-color: #ffffff !important; 
            transition: all 0.3s ease; 
            outline: none !important; 
            color: #213456; 
            margin-left: 0 !important; 
        }
        .dataTables_filter input:focus { 
            border-color: #E1AD01 !important; 
            box-shadow: 0 0 10px rgba(225, 173, 1, 0.2) !important; 
        }

        #new_rep_table { 
            width: 100% !important; 
            background-color: #ffffff;   
            border-collapse: collapse !important;
            border: none !important;
        }
        
        #new_rep_table th, #new_rep_table td {
            border-left: none !important;
            border-right: none !important;
            border-top: none !important;
            border-bottom: 1px solid #e5e7eb !important; 
            vertical-align: middle;
        }
        
        #new_rep_table thead th { 
            background-color: #4c6da5; 
            color: white; 
            font-weight: 600; 
            text-transform: uppercase; 
            font-size: 0.85rem; 
            letter-spacing: 0.5px; 
            padding: 15px; 
            border-bottom: 2px solid #213456 !important; 
        }

        #new_rep_table tbody tr:hover td { 
            background-color: #d2d9e6 !important;
            color: black !important; 
            cursor: pointer; 
            transition: background-color 0.2s ease; 
        }

        label {
            font-size: 11px;
            font-weight: 900;
            color: #213456; 
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        input.form-control, textarea.form-control {
            color: #6c757d !important;
            background-color: transparent !important; 
            border: black !important; 
            border-bottom: 1px solid #E1AD01 !important; 
            resize: none !important; 
            border-radius: 0px !important;
        }

        select.custom-select-placeholder.placeholder-active,
        textarea.form-control.custom-select-placeholder:placeholder-shown {
            color: red !important;
            border: 1px solid #ced4da !important;
            background-color: #fff !important;
            border-radius: 4px !important;
        }

        textarea.form-control.custom-select-placeholder::placeholder {
            color: red !important;
            opacity: 0.7;
        }

        select.custom-select-placeholder.has-value,
        textarea.form-control.custom-select-placeholder:not(:placeholder-shown) {
            color: #0a0a0a !important; 
            border-bottom: 1px solid #E1AD01 !important; 
            border-left: none !important;
            border-right: none !important;
            border-top: none !important;
            background-color: transparent !important;
            border-radius: 0px !important;
        }

        .form-control:focus, select.form-control:focus, textarea.form-control:focus {
            box-shadow: 0 10px 18px rgba(17,24,39,.06);
            border-color: rgba(114, 89, 21, 0.94) !important;
        }

        #msg_thread { 
            padding: 1.5rem; 
            height: 100%; 
            display: flex; 
            flex-direction: column; 
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
            flex-grow: 1;
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
        .chat-left .msg-meta-name, .chat-right .msg-meta-name { 
            font-weight: bold; 
        }
        .chat-left .msg-meta-name { 
            color: #213456; 
        }
        .chat-right .msg-meta-name { 
            color: #ffffff; 
        }

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
        
        .btn-success { 
            background-color: #1C0770 !important; 
            border: none; 
            padding: 0.6rem 2rem; 
            font-weight: 600; 
            border-radius: 8px; 
            color: white; 
            transition: transform 0.2s ease; 
        }
        .btn-success:hover { 
            transform: translateY(-1px); 
            box-shadow: 0 4px 12px rgba(28, 7, 112, 0.2); 
            color: white; 
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="mb-3">
        <button onclick="location.reload();" class="btn btn-primary btn-sm" style="background-color: #213456; color: white; border:none; padding: 8px 16px; border-radius: 8px;">
            <i class="fas fa-sync-alt"></i> Reload
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" id="new_rep_table"></table>
    </div>
</div>

<script src="../js/coms.js"></script> 

<!-- Split Modal UI -->
<div class="col-12 col-lg-12 modal fade" id="newrpt_Modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 85%; width: 85%;">
        <form method="post" id="newrpt_form" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tick_title"></h4>
                </div>
                <div class="modal-body p-0">
                    <div class="row m-0">
                        <!-- Left Side: Form Inputs -->
                        <div class="col-md-6 border-right p-4 bg-white">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>STORE</label>
                                    <input type="hidden" name="store" id="store" readonly>
                                    <input type="text" class="form-control form-control-sm" name="str_desc" id="str_desc" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Created By:</label>
                                    <input type="text" class="form-control form-control-sm" name="crtd_by" id="crtd_by" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>DATE CREATED</label>
                                    <input type="hidden" name="ticket_no" id="ticket_no">
                                    <input type="text" class="form-control form-control-sm" name="date_createdx" id="date_createdx" readonly>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>SUBJECT</label>
                                    <input type="text" name="subject" id="concern" class="form-control form-control-sm" style="text-transform:uppercase" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Service Requested:</label>
                                    <input type="text" class="form-control form-control-sm" name="tos" id="tos" readonly>
                                </div>
                                
                                <div class="form-group col-md-12">
                                    <label>CONCERN DETAILS</label>
                                    <textarea name="concern" id="message" class="form-control form-control-sm" rows="2" style="text-transform:uppercase" readonly></textarea>
                                </div>
                                
                                <input type="hidden" class="form-control form-control-sm" name="old_dept" id="old_dept" readonly>
                                <input type="hidden" class="form-control form-control-sm" name="deptsel" id="deptsel" readonly>
                                <input type="hidden" name="setStatus" id="setStatus" value="Assigned">
                                <input type="hidden" name="contactNumber" id="contactNumber">
                                <input type="hidden" name="dept_email" id="dept_email">

                                <div class="col-md-12"><hr></div>

                                <div class="form-group col-md-12">
                                    <label>ASSIGNED DEPARTMENT</label>
                                    <select class="form-control form-control-sm custom-select-placeholder placeholder-active" name="f_deptsel" id="f_deptsel" required onchange="handleDropdownChange(this)">
                                        <option value="">Assign department...</option>
                                        <?php
                                            $query="SELECT * FROM tbl_dept WHERE dept_id NOT IN ('4','5','7','8','9','10','12','14','17','18')";
                                            $run=$con1->prepare($query);
                                            $run->execute();
                                            $rs=$run->get_result();
                                            while ($res=$rs->fetch_assoc()) {
                                                echo "<option value='".$res['dept_id']."' style='color: #333;'>".$res['dept_desc']."</option>";
                                            } 
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>PRIORITY LEVEL</label>
                                    <select class="form-control form-control-sm custom-select-placeholder placeholder-active" name="priority_level" id="priority_level" required onchange="handleDropdownChange(this)">
                                        <option value=""> &larr; PRIORITY &rarr;</option>
                                        <option value="4" style="color:black;">LOW</option>
                                        <option value="3" style="color:black;">NORMAL</option>
                                        <option value="2" style="color:black;">HIGH</option>
                                        <option value="1" style="color:black;">CRITICAL</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="sla_days">Service Level Agreement (SLA)</label>
                                    <select name="sla_days" id="sla_days" class="form-control form-control-sm custom-select-placeholder placeholder-active" required onchange="handleDropdownChange(this)">
                                        <option value="">Select SLA</option>
                                        <option value="2" style="color:black;">24 – 48 hours</option>
                                        <option value="5" style="color:black;">3 – 5 days</option>
                                        <option value="7" style="color:black;">5 – 7 days</option>
                                        <option value="14" style="color:black;">1 – 2 weeks</option>
                                        <option value="21" style="color:black;">2 – 3 weeks</option>
                                        <option value="28" style="color:black;">3 – 4 weeks</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12 mb-0">
                                    <label>Work Output:</label>
                                    <textarea name="remarks" id="remarks" rows="2" class="form-control form-control-sm custom-select-placeholder placeholder-active" placeholder="Your Workoutput" style="text-transform:uppercase" required onchange="handleDropdownChange(this)"></textarea>
                                </div>

                                <input type="hidden" name="close_by" id="close_by" value="<?php echo $_SESSION['tech_id'] ?? '';?>">
                                <input type="hidden" class="form-control form-control-sm" name="cl_desc" id="cl_desc" readonly value="<?php echo ($_SESSION['fname'] ?? '').' '.($_SESSION['lstname'] ?? '');?>">
                            </div>
                        </div>

                        <!-- Right Side: Thread UI -->
                        <div class="col-md-6 p-4" style="background: linear-gradient(to bottom, #f8f9fa, #d7dce4);">
                            <div class="d-flex flex-column h-100" id="msg_thread">
                                <label style="font-weight: bold; color: #213456;">Ticket Thread:</label>
                                <div class="container_remarks mb-3">
                                    <div id="remarks_view"></div>
                                </div>
                                <div class="mt-auto">
                                    <label style="font-weight: bold; color: #213456;">Add Message:</label>
                                    <textarea name="admsg" class="form-control" rows="3" placeholder="Reply to their message or give an update regarding this ticket..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

               <div class="modal-footer d-flex justify-content-end">
                    <input type="hidden" name="operation" id="operation" />
                    <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">
                    <input type="hidden" name="is_transfer" value="1" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="action" class="btn btn-success">Save & Reply</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    var reptable;
    const user_id = "<?= $_SESSION['user_id'] ?? '' ?>";
    
    function getdata(){
        $.post('fetchdata/fetch_data.php', {mode: 'trans_tbl'}, function(data){
            admin_datatable(data);
        }, 'json').fail(function(xhr) {
            console.error("Failed to load table data:", xhr.responseText);
        });
    }
    getdata();

    function admin_datatable(t){
        const dataset = t.transdata || []; 
        reptable = $("#new_rep_table").DataTable({
            "dom": '<"pull-left"lf><"pull-right">tip',
            "stateSave": false,
            "bDestroy": true,
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "language": {
                "emptyTable": "No transferred reports pending",
                "search": "_INPUT_",
                "searchPlaceholder": "Search Tickets..."
            },
            "pageLength": 8,
            "data": dataset,
            "order": [[ 4, "desc" ]], 
            "columns": [
                {title:"TicketNo", data:"ticket_no", defaultContent: ""},
                {title:"Selected Department", data:"dept_desc", defaultContent: ""},
                {title:"Department/Store", data:"str_code", defaultContent: ""},
                {title:"Created By", data:"full_name", defaultContent: ""},
                {
                    title:"Date Created", 
                    data:"date_created", 
                    defaultContent: "",
                    render: function(data, type, row) {
                        if (type === 'sort' || type === 'type') {
                            if (!data) return '';
                            let parts = data.split(" ");
                            if (parts.length > 1) {
                                let date = parts[0].split("/");
                                let time = parts[1];
                                if(date.length === 3) {
                                    return `${date[2]}-${date[0]}-${date[1]} ${time}`;
                                }
                            }
                        }
                        return data; 
                    }
                },
                {title:"SUBJECT", data:"concern", defaultContent: ""},
                {title:"Types of Service", data:"service_desc", defaultContent: ""},
                {title:"CONCERN", data:"subject", defaultContent: ""},
                {title:"Action", data:null, defaultContent: "<Button class='btn btn-primary btn-sm' name='update' style='background:#213456; color:white; border:none;'><i class='fas fa-edit'></i> Open</Button>"}
            ],
            "rowCallback": function(row, data, index){
                if(data['msg_cnt'] == '1'){
                    $(row).find('td').css("font-weight", "bold");
                }
            }
        });

        // Search Binding
        $('#new_rep_table_filter input').off().on('keyup', function () {
            let value = $.fn.dataTable.util.escapeRegex($(this).val());
            reptable.column(2).search('^' + value + '$', true, false).draw();
        });

        setInterval(function () { getdata(); }, 60000);

        $('#new_rep_table tbody').off('click', 'button').on('click', 'button', function () {
            var data = reptable.row($(this).parents('tr')).data();
            if(!data) return;

            $('#ticket_no').val(data['ticket_no']);
            $('#store').val(data['store']);
            $('#str_desc').val(data['str_code']);
            $('#crtd_by').val(data['full_name']);
            $('#date_createdx').val(data['date_created']);
            $('#concern').val(data['concern']);
            $('#tos').val(data['service_desc']);
            $('#message').val(data['subject']);
            $('#sub_num').val(data['sub_id'] || '');
            
            let currentDept = data['itsup'] || data['f_deptsel'] || '0';
            $('#old_dept').val(currentDept); 
            $('#f_deptsel').val(currentDept !== '0' ? currentDept : '').trigger('change');

            $('#newrpt_Modal').modal('show');
            $('#action').val("Update");
            $('#operation').val("New_Report");

            var tid = data['ticket_no'];
            $('#tick_title').text("Ticket Number: " + tid);
            
            loadCommentThread(tid);
            loadDeptsel(currentDept);
        });
    }

    $(document).on('change', '#f_deptsel', function () {
        let dept_id = $(this).val();
        if (dept_id !== '') {
            $.ajax({
                url: 'fetchdata/get_contact.php',
                method: 'POST',
                data: { dept_id: dept_id },
                success: function (response) {
                    $('#contactNumber').val(response);
                },
                error: function () {
                    $('#contactNumber').val('');
                }
            });
        } else {
            $('#contactNumber').val('');
        }
    });

    $(document).on('submit', '#newrpt_form', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        var formData = new FormData(this);

        let $btn = $('#action');
        let originalText = $btn.text();
        $btn.text('Saving...').prop('disabled', true);

        $.ajax({
            url: "insert.php", 
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            success: function(response) {
                $btn.text(originalText).prop('disabled', false);

                let isSuccess = false;
                if(typeof response === 'object' && (response.status === 'success' || response.status === true)) {
                    isSuccess = true;
                } else if(typeof response === 'string' && response.toLowerCase().includes('success')) {
                    isSuccess = true;
                }

                if (isSuccess) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved successfully',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        $('#newrpt_form')[0].reset();
                        $('#newrpt_Modal').modal('hide');
                        getdata();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Save failed', text: response.message || response || 'Please try again.' });
                }
            },
            error: function(xhr, status, error) {
                $btn.text(originalText).prop('disabled', false);
                if (xhr.status === 200 && xhr.responseText && xhr.responseText.toLowerCase().includes("success")) {
                     Swal.fire({
                        icon: 'success',
                        title: 'Saved successfully',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        $('#newrpt_form')[0].reset();
                        $('#newrpt_Modal').modal('hide');
                        getdata();
                    });
                    return;
                }

                console.error("Server Error Details:", xhr.responseText);
                let errorMsg = xhr.responseText ? xhr.responseText : 'Failed to process request.';
                if (errorMsg.includes('Fatal error') || errorMsg.includes('Uncaught Error')) {
                    errorMsg = "A PHP Fatal Error occurred. Check your browser console or PHP error logs.";
                }

                Swal.fire({ icon: 'error', title: 'System Error', text: errorMsg });
            }
        });
    });
});

function loadDeptsel(itsup_value) {
    if (itsup_value && itsup_value !== "0") {
        $('#deptsel').val("Fetching..."); 
        $.ajax({
            url: "fetch_deptsel.php", 
            method: "POST",
            data: { itsup: itsup_value },
            success: function(response) {
                $('#deptsel').val(response.trim()); 
            },
            error: function() {
                $('#deptsel').val("Error fetching data");
            }
        });
    } else {
        $('#deptsel').val("No Department Assigned");
    }
}

function timeAgo(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString.replace(/-/g, '/'));
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    const months = Math.floor(days / 30);
    const years = Math.floor(days / 365);

    if (seconds < 60) return 'replied just now';
    if (minutes < 60) return `replied ${minutes} minute${minutes > 1 ? 's' : ''} ago`;
    if (hours < 24) return `replied ${hours} hour${hours > 1 ? 's' : ''} ago`;
    if (days < 30) return `replied ${days} day${days > 1 ? 's' : ''} ago`;
    if (months < 12) return `replied ${months} month${months > 1 ? 's' : ''} ago`;
    return `replied ${years} year${years > 1 ? 's' : ''} ago`;
}

function loadCommentThread(ticket_no) {
    const $remarksView = $('#remarks_view');
    const ticketValue = (ticket_no || '').toString().trim();

    if (!ticketValue) return;
    
    $remarksView.fadeOut(150, function() {
        $remarksView.html('<div class="text-center text-muted mt-4 mb-4"><div class="spinner-border spinner-border-sm me-2 text-primary"></div>Loading conversation...</div>').fadeIn(150);
    });

    $.ajax({
        url: 'get_comments.php', 
        type: 'POST',
        dataType: 'json',
        data: { ticket_no: ticketValue },
        success: function(response) {
            let html = '';
            if (Array.isArray(response) && response.length > 0) {
                var currentUserIdStr = "<?= $_SESSION['user_id'] ?? '' ?>";
                var currentUserNameStr = "<?= $_SESSION['fname'] ?? '' ?>";
                
                let sortedResponse = response.slice().sort((a, b) => {
                    return new Date(b.comment_date.replace(/-/g, '/')) - new Date(a.comment_date.replace(/-/g, '/'));
                });

                sortedResponse.forEach(function(comment, index) {
                    let sender = comment.userId || 'Unknown';
                    let isMe = false;
                    if(currentUserIdStr !== "" && sender === currentUserIdStr) isMe = true;
                    if(currentUserNameStr !== "" && sender.includes(currentUserNameStr)) isMe = true;
                    
                    let bubbleClass = isMe ? 'chat-right' : 'chat-left';
                    let delay = index * 0.05; 
                    let timeAgoStr = timeAgo(comment.comment_date);
                    
                    html += `
                        <div class="chat-bubble ${bubbleClass}" style="animation-delay: ${delay}s;">
                            <div class="msg-meta">
                                <span class="msg-meta-name">${sender}</span>
                                <span class="msg-time">${comment.comment_date}</span> 
                            </div>
                            <div style="white-space: pre-wrap; padding-bottom: 5px;">${comment.comment_details}</div>
                            <div style="font-size: 0.65rem; opacity: 0.7; text-align: ${isMe ? 'right' : 'left'}; margin-top: 4px; border-top: 1px solid rgba(128,128,128,0.2); padding-top: 4px;">
                                <i class="far fa-clock"></i> ${timeAgoStr}
                            </div>
                        </div>
                    `;
                });
            } else {
                html = '<div class="text-center text-muted mt-3" style="font-size:13px;"><i class="fas fa-comments mb-2" style="font-size:24px; opacity:0.5;"></i><br>No comments yet. Start the conversation!</div>';
            }
            
            $remarksView.fadeOut(150, function() {
                $remarksView.html(html).fadeIn(300);
                setTimeout(() => {
                    const $container = $('.container_remarks');
                    if ($container.length) {
                     
                        $container.animate({ scrollTop: 0 }, 600, 'swing');
                    }
                }, 200);
            });
        },
        error: function(xhr) {
            console.error("Comments Error:", xhr.responseText);
            $remarksView.html('<div class="text-danger text-center mt-3">Error loading comments.</div>');
        }
    });
}

function handleDropdownChange(selectElement) {
    if (selectElement.value === "") {
        selectElement.classList.add("placeholder-active");
        selectElement.classList.remove("has-value");
    } else {
        selectElement.classList.remove("placeholder-active");
        selectElement.classList.add("has-value");
    }
}

// Activity Watcher
let inactivityTime = function(){
    let time;
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.onscroll = resetTimer;
    document.onclick = resetTimer;

    function logout(){ window.location.href = 'adminpanel.php'; }
    function resetTimer(){ clearTimeout(time); time = setTimeout(logout, 180000); }
};
inactivityTime();
</script>
</body>
</html>