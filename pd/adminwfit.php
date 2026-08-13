<?php
$inactive = 180;

if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > $inactive)){
    session_unset();
    // removed session_destroy() to avoid "headers already sent" warnings
    echo '<script>setTimeout(function(){ window.location.href = "adminpanel.php"; }, 180000);</script>';
    exit();
}

$_SESSION['start'] = time();
  
include 'admin.php';
include '../condb.php';

$con1 = new dbconfig();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mode']) && $_POST['mode'] === 'newrpt_tbl') {
    try {
        $sql = "SELECT 
                    r.ticket_no, 
                    r.date_created, 
                    r.concern, 
                    r.service_desc, 
                    r.subject,
                    GROUP_CONCAT(i.files_name SEPARATOR '|') AS attachment_files,
                    r.sub_id,
                    r.f_deptsel,
                    r.itsup,
                    r.store
                FROM reports r
                LEFT JOIN images i ON r.ticket_no = i.ticket_no
                WHERE r.status = 'Assigned' 
                GROUP BY r.ticket_no
                ORDER BY r.date_created DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['newrptdata' => $results]);
        
    } catch (Exception $e) {
        echo json_encode(['newrptdata' => [], 'error' => $e->getMessage()]);
    }
    
    exit; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/>
    <script src="../js/bootstrap-datetimepicker.min.js"></script>
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="styles.css" />
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

        #new_rep_table { 
          width:100% !important; 
          background-color: #ffffff; 
          border-collapse: separate; 
          border-spacing: 0; 
          border-radius: 8px; overflow: hidden; box-shadow: 0 10px 8px rgba(108, 108, 53, 0.4); border: 1px solid #e9ecef; }
        #new_rep_table thead th { background-color: #54699e; color: white; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; padding: 15px; border-bottom: 2px solid #dee2e6; }
        #new_rep_table tbody td { padding: 12px 15px; vertical-align: middle; color: #333; border-bottom: 1px solid #f1f1f1; }
        #new_rep_table tbody tr:hover { background-color: #213456 !important; color: #ffffff !important; cursor: pointer; transition: all 0.2s ease; }

        .table-responsive { border-radius: 8px; margin-top: 20px; overflow: visible !important; width: 100% !important; }
        .dataTables_wrapper { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius); box-shadow: var(--shadow); padding: 14px; }
        
        .dataTables_wrapper .pull-left { flex-direction: row; align-items: center; justify-content: flex-start; width: 100%; gap: 40px; margin-bottom: 20px; }
        .dataTables_filter { position: relative; display: inline-block; margin: 0 !important; }
        .dataTables_filter label { display: flex; align-items: center; margin-bottom: 0; }
        .dataTables_filter::before { content: "\f002"; font-family: "Font Awesome 5 Free"; font-weight: 900; position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #213456; z-index: 1; opacity: 0.6; }
        .dataTables_filter input { border: 2px solid #e0e0e0 !important; border-radius: 50px !important; padding: 8px 15px 8px 35px !important; width: 300px !important; background-color: #ffffff !important; transition: all 0.3s ease; outline: none !important; color: #213456; margin-left: 0 !important; }
        .dataTables_filter input:focus { border-color: #E1AD01 !important; box-shadow: 0 0 10px rgba(225, 173, 1, 0.2) !important; }

        /* Modal Settings */
        #newrpt_Modal .modal-dialog { max-width: 1100px; margin: 1.25rem auto; }
        #newrpt_Modal .modal-content { border: none; border-radius: 16px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); overflow: hidden; }
        #newrpt_Modal .modal-header { background-color: #213456; color: #fff; border-bottom: 4px solid #E1AD01; padding: 16px 18px !important; }
        #newrpt_Modal .modal-title { font-weight: 700; letter-spacing: 0.5px; display: flex; align-items: center; font-size: 18px; text-transform: uppercase; }
        #newrpt_Modal .modal-body { padding: 16px 18px; }
        #newrpt_Modal .modal-footer { border-top: 1px solid rgba(0,0,0,0.08); background: rgba(255,255,255,0.92); position: sticky; bottom: 0; z-index: 5; padding: 12px 14px; }

        label {
  font-size: 11px;
  font-weight: 900;
  color: #213456; 
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
        
        select.custom-select-placeholder.placeholder-active { color: red !important; }
        select.custom-select-placeholder.has-value { color: #212529 !important; }

        #msg_thread { padding: 1.5rem; height: 100%; display: flex; flex-direction: column; }
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
        .chat-bubble { max-width: 85%; padding: 10px 14px; border-radius: 18px; font-size: 0.9rem; line-height: 1.4; position: relative; margin-bottom: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); word-wrap: break-word; }
        .chat-left { align-self: flex-start; background: #ffffff; color: #1e293b; border-bottom-left-radius: 4px; border: 1px solid #e5e7eb; }
        .chat-right { align-self: flex-end; background: #1C0770; color: #ffffff; border-bottom-right-radius: 4px; }
        .msg-meta { display: flex; justify-content: space-between; gap: 15px; font-size: 0.7rem; margin-bottom: 4px; }
        .chat-left .msg-meta { color: #64748b; }
        .chat-right .msg-meta { color: rgba(255, 255, 255, 0.85); }
        .chat-left .msg-meta-name { color: #213456; font-weight: bold; }
        .chat-right .msg-meta-name { color: #ffffff; font-weight: bold; }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.1); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(135deg, #837031, #E1AD01); border-radius: 10px; }
        
        .btn-success { background-color: #1C0770 !important; border: none; padding: 0.6rem 2rem; font-weight: 600; border-radius: 8px; color: white; transition: transform 0.2s ease; }
        .btn-success:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(28, 7, 112, 0.2); color: white; }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="table-responsive">
        <table class="table table-hover" id="new_rep_table"></table>
    </div>
</div>

<script src="../js/coms.js"></script> 

<div class="col-12 col-lg-12 modal fade" id="newrpt_Modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 80%; width: 80%;">
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
                                    <input type="text" name="concern" id="concern" class="form-control form-control-sm" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Service Requested:</label>
                                    <input type="text" class="form-control form-control-sm" name="tos" id="tos" readonly>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>CONCERN DETAILS</label>
                                    <textarea name="message" id="message" class="form-control form-control-sm" rows="3" readonly></textarea>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Attachment:</label>
                                    <div id="attachments-container" class="d-flex flex-wrap gap-2 p-2 border rounded bg-light" style="min-height: 50px;"></div>
                                </div>

                                <div class="col-md-12"><hr></div>

                                <div class="form-group col-md-4">
                                    <label>VIA</label>
                                    <select class="form-control form-control-sm custom-select-placeholder placeholder-active" name="via" id="via" required onchange="handleDropdownChange(this)">
                                        <option value="" style="color:red;">&larr; VIA &rarr;</option>
                                        <?php
                                            $query = "select * from via_main";
                                            $run = $con1->prepare($query);
                                            $run->execute();
                                            $rs = $run->get_result();
                                            while ($res = $rs->fetch_assoc()) {
                                                echo "<option value='".$res['via_desc']."' style='color:#333;'>".$res['via_desc']."</option>";
                                            } 
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-8">
                                    <label>ASSIGNED SUPPORT</label>
                                    <input type="hidden" name="it_num" id="it_num" readonly>
                                    <select class="form-control form-control-sm custom-select-placeholder placeholder-active" name="itsup" id="itsup" required onchange="handleDropdownChange(this)">
                                        <option value="" style="color:red;">&larr; ASSIGN SUPPORT &larr;</option>  
                                        <?php
                                            $query="select * from it_tech WHERE itsup NOT IN ('4','8','12','14') AND deptsel = '19'";
                                            $run=$con1->prepare($query);
                                            $run->execute();
                                            $rs=$run->get_result();
                                            while ($res=$rs->fetch_assoc()) {
                                                echo "<option value='".$res['itsup']."' style='color:#333;'>".$res['it_desc']."</option>";
                                            } 
                                        ?>    
                                    </select> 
                                </div>
                                <div class="form-group col-md-6">
                                    <label>CATEGORY</label>
                                    <input type="hidden" name="cat_num" id="cat_num" readonly>
                                    <select class="form-control form-control-sm custom-select-placeholder placeholder-active" name="cat" id="cat" required onchange="handleDropdownChange(this)">
                                        <option value="" style="color:red;">&larr; CATEGORY &rarr;</option>  
                                        <?php
                                            $query="select * from categories WHERE deptsel = '19' AND (old_tag IS NULL OR old_tag <> 'Y') ORDER BY order_id ASC";
                                            $run=$con1->prepare($query);
                                            $run->execute();
                                            $rs=$run->get_result();
                                            while ($res=$rs->fetch_assoc()) {
                                                echo "<option value='".$res['cat_id']."' style='color:#333;'>".$res['cat_desc']."</option>";
                                            } 
                                        ?>
                                    </select> 
                                </div>
                                <div class="form-group col-md-6">
                                    <label>SUB CATEGORY</label>
                                    <input type="hidden" name="sub_num" id="sub_num" readonly>
                                    <select class="form-control form-control-sm custom-select-placeholder placeholder-active" name="sub" id="sub" required onchange="handleDropdownChange(this)"></select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>STATUS</label>
                                    <select class="form-control form-control-sm custom-select-placeholder placeholder-active" name="status" id="status" required onchange="handleDropdownChange(this)">
                                        <option value="" style="color:red;">&larr; STATUS &rarr;</option>
                                        <?php
                                            $query="select * from status WHERE it_module_tag = 'Y' AND stat_id <> '29'";
                                            $run=$con1->prepare($query);
                                            $run->execute();
                                            $rs=$run->get_result();
                                            while ($res=$rs->fetch_assoc()) {
                                                echo "<option value='".$res['stat_desc']."' style='color:#333;'>".$res['stat_desc']."</option>";
                                            } 
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label id="dateclabel" class="hidden">DATE CLOSED</label>
                                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                        <input type="text" name="date_closed" id="date_closed" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker2" autocomplete="off" />
                                        <div class="input-group-append" data-target="#date_closed" autocomplete="off" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label id="clby_label" class="hidden">CLOSED BY</label>
                                    <input type="hidden" name="close_by" id="close_by" value="<?php echo $_SESSION['tech_id'] ?? '';?>">
                                    <input type="text" class="form-control form-control-sm" name="cl_desc" id="cl_desc" readonly value="<?php echo ($_SESSION['fname'] ?? '').' '.($_SESSION['lstname'] ?? '');?>">
                                </div>
                                <div class="form-group col-md-12 mb-0">
                                    <label>Work Output:</label>
                                    <textarea name="remarks" id="remarks" rows="2" class="form-control form-control-sm custom-select-placeholder placeholder-active" placeholder="Your Workoutput" style="text-transform:uppercase" required onchange="handleDropdownChange(this)"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Auto Display Thread & Response -->
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="action" class="btn btn-success">Save</button>
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

    if (targetTicket) {
        setTimeout(function() {
            var foundRow = null;
            reptable.rows().every(function (rowIdx, tableLoop, rowLoop) {
                var rowData = this.data();
                if (rowData && rowData.ticket_no == targetTicket) {
                    foundRow = this.node();
                }
            });
            if (foundRow) {
                $(foundRow).find('button[name="update"]').trigger('click');
                $('html, body').animate({ scrollTop: $(foundRow).offset().top - 100 }, 800, function() {
                    $(foundRow).css('transition', 'background-color 0.5s ease');
                    $(foundRow).css('background-color', '#ffff99');
                    setTimeout(function() { $(foundRow).css('background-color', ''); }, 1200);
                });
            }
        }, 600);
    }

    var reptable;
    var user_id = "<?= $_SESSION['user_id'] ?? ''; ?>"; 

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
                {title:"TicketNo", data:"ticket_no","defaultContent": ""},
                {title:"Department/Store", data:"str_code","defaultContent": ""},
                {title:"Created By", data:"full_name","defaultContent": ""},
                {title:"Date Created", data:"date_created","defaultContent": ""},
                {title:"SUBJECT", data:"concern","defaultContent": ""},
                {title:"Types of Service", data:"service_desc","defaultContent": ""},
                {title:"CONCERN", data:"subject","defaultContent": ""},
                {title:"Update", data:null,"defaultContent": "<Button class='btn btn-danger btn-sm' name='update'><i class='fas fa-edit'></i></Button>"}
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

            $('#ticket_no').val(data['ticket_no']);
            $('#store').val(data['store']);
            $('#str_desc').val(data['str_code']);
            $('#crtd_by').val(data['full_name']);
            $('#date_createdx').val(data['date_created']);
            $('#concern').val(data['concern']);
            $('#tos').val(data['service_desc']);
            $('#message').val(data['subject']);
            $('#sub_num').val(data['sub_id']);

            $('#newrpt_Modal').modal('show');
            $('#action').val("Update");
            $('#operation').val("Save and Reply"); 

            var tid = data['ticket_no'];
            $('#tick_title').text("Ticket Number: " + tid);
            
            displayAttachmentsFromData(data);
            
            // Automatically Load Ticket Thread
            loadCommentThread(tid);
        });
    }

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
                    Swal.fire({ icon: 'error', title: 'Save failed', text: response.message || 'Please try again.' });
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
                let reversedResponse = response.slice().reverse();

                reversedResponse.forEach(function(comment, index) {
                    let sender = comment.userId || 'Unknown';
                    
                    let isMe = false;
                    if(currentUserIdStr !== "" && sender === currentUserIdStr) isMe = true;
                    if(currentUserNameStr !== "" && sender.includes(currentUserNameStr)) isMe = true;
                    
                    let bubbleClass = isMe ? 'chat-right' : 'chat-left';
                    let delay = index * 0.05; 
                    let replyTimeColor = isMe ? "color: #e2e8f0;" : "color: #64748b;";
                    
                    html += `
                        <div class="chat-bubble ${bubbleClass}" style="animation-delay: ${delay}s;">
                            <div class="msg-meta">
                                <span class="msg-meta-name">${sender}</span>
                                <span class="msg-time">${comment.comment_date}</span> 
                            </div>
                            <div style="white-space: pre-wrap;">${comment.comment_details}</div>
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
                        $container.animate({ scrollTop: $container.prop("scrollHeight") }, 600, 'swing');
                    }
                }, 200);
            });
        },
        error: function(xhr) {
            $remarksView.html('<div class="text-danger text-center mt-3">Error loading comments.</div>');
        }
    });
}

function displayAttachmentsFromData(data) {
    const container = document.getElementById('attachments-container');
    if (!container) return;
    
    container.innerHTML = '';
    const attachmentFiles = data.attachment_files;

    if (!attachmentFiles) {
        container.innerHTML = '<span class="text-muted">No attachments for this ticket.</span>';
        return;
    }

    const filePaths = attachmentFiles.split('|').filter(f => f.trim() !== '');
    if (filePaths.length === 0) {
        container.innerHTML = '<span class="text-muted">No attachments for this ticket.</span>';
        return;
    }

    filePaths.forEach(imagePath => {
        if (!imagePath.trim()) return;

        const imgElement = document.createElement('img');
        let fullPath = imagePath.trim();
        if (!fullPath.includes('users/image/')) fullPath = 'users/image/' + fullPath;
      
        imgElement.src = '../' + fullPath;
        imgElement.alt = "Ticket Attachment";
        imgElement.className = "img-thumbnail m-1";
        imgElement.style.maxHeight = "100px";
        imgElement.style.maxWidth = "100px";
        imgElement.style.objectFit = "cover";
        imgElement.style.cursor = "pointer";
        imgElement.style.transition = "transform 0.2s ease";
        imgElement.style.border = "2px solid #EAAA00";

        imgElement.onmouseover = () => imgElement.style.transform = "scale(1.08)";
        imgElement.onmouseout = () => imgElement.style.transform = "scale(1.0)";
        imgElement.onclick = () => window.open('../' + fullPath, '_blank');

        container.appendChild(imgElement);
    });
}

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
</body>
</html>