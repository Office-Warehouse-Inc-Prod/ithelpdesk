<?php
require_once '../condb.php'; 
if (!isset($_SESSION['login']) || $_SESSION['login'] != 'true') {
    header("Location: index.php");
    exit();
}

if (isset($_POST['mode'])) {
   if ($_POST['mode'] === 'fa_tbl') {
        header('Content-Type: application/json');
        try {
            $sql = "SELECT 
                        r.ticket_no, r.date_created, r.concern, r.service_desc, r.subject,
                        GROUP_CONCAT(i.files_name SEPARATOR '|') AS attachment_files,
                        r.sub_id, r.store,
                        COALESCE(d_nw.dept_desc, r.f_deptsel) AS f_deptsel, 
                        COALESCE(t_nw.it_desc, r.itsup) AS itsup,
                        IF(tr.ticket_no IS NOT NULL, 'On Process', r.status) AS status
                    FROM reports r
                    LEFT JOIN images i ON r.ticket_no = i.ticket_no
                    LEFT JOIN (
                        SELECT t1.ticket_no, t1.f_deptsel, t1.nw_sup
                        FROM tbl_reassigned t1
                        INNER JOIN (
                            SELECT ticket_no, MAX(date_rasigned) as max_date 
                            FROM tbl_reassigned 
                            GROUP BY ticket_no
                        ) t2 ON t1.ticket_no = t2.ticket_no AND t1.date_rasigned = t2.max_date
                    ) tr ON r.ticket_no = tr.ticket_no
                    LEFT JOIN tbl_dept d_nw ON tr.f_deptsel = d_nw.dept_id
                    LEFT JOIN it_tech t_nw ON tr.nw_sup = t_nw.itsup
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
        exit(); 
    }

    if ($_POST['mode'] === 'add_remarks_only') {
        header('Content-Type: application/json');
        
        $ticket_no = $_POST['ticket_no'] ?? '';
        $remarks = trim($_POST['remarks_adtech'] ?? '');
        $tech_id = $_SESSION['tech_id'] ?? '';
        $store = $_SESSION['str_num'] ?? '';
        $currentDate = date('Y-m-d H:i:s');

        if (empty($ticket_no) || empty($remarks)) {
            echo json_encode(["status" => "error", "message" => "Missing data."]);
            exit();
        }

        if (empty($tech_id)) {
            echo json_encode(["status" => "error", "message" => "Session expired or Tech ID missing. Please log in again."]);
            exit();
        }

        try {
            $conn->begin_transaction();
            
            $stmt1 = $conn->prepare("
                INSERT INTO fixed_asset_remarks (
                    ticket_no, remarks_note, remarks_by, date_remarks
                ) VALUES (?, ?, ?, ?)
            ");
            $stmt1->bind_param("ssss", $ticket_no, $remarks, $tech_id, $currentDate);
            $exec1 = $stmt1->execute();
            
            $notif_msg = "Admin Support added a remark on ticket no " . $ticket_no;
            
            $stmt2 = $conn->prepare("
                INSERT INTO tbl_notif (
                    ticket_no, store, itsup, notif_data, notif_val, notif_date
                ) VALUES (?, ?, ?, ?, '10', ?)
            ");
            $stmt2->bind_param("sssss", $ticket_no, $store, $tech_id, $notif_msg, $currentDate);
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
        header('Content-Type: application/json');
        $sql = "SELECT 
                    r.ticket_no, r.date_created, r.concern, r.service_desc, r.subject, 
                    GROUP_CONCAT(i.files_name SEPARATOR '|') AS attachment_files, 
                    r.sub_id, r.store,
                    COALESCE(d_nw.dept_desc, r.f_deptsel) AS f_deptsel, 
                    COALESCE(t_nw.it_desc, r.itsup) AS itsup,
                    IF(tr.ticket_no IS NOT NULL, 'On Process', r.status) AS status
                FROM reports r 
                LEFT JOIN images i ON r.ticket_no = i.ticket_no 
                LEFT JOIN (
                    SELECT t1.ticket_no, t1.f_deptsel, t1.nw_sup
                    FROM tbl_reassigned t1
                    INNER JOIN (
                        SELECT ticket_no, MAX(date_rasigned) as max_date 
                        FROM tbl_reassigned 
                        GROUP BY ticket_no
                    ) t2 ON t1.ticket_no = t2.ticket_no AND t1.date_rasigned = t2.max_date
                ) tr ON r.ticket_no = tr.ticket_no
                LEFT JOIN tbl_dept d_nw ON tr.f_deptsel = d_nw.dept_id
                LEFT JOIN it_tech t_nw ON tr.nw_sup = t_nw.itsup
                WHERE r.status = 'Assigned'
                GROUP BY r.ticket_no 
                ORDER BY r.date_created DESC";
        
        $result = $conn->query($sql);
        if ($result) {
            echo json_encode(['newrptdata' => $result->fetch_all(MYSQLI_ASSOC)]);
        } else {
            echo json_encode(['newrptdata' => []]);
        }
        exit();
    }

    if ($_POST['mode'] === 'fetch_remarks') {
        header('Content-Type: application/json');
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
        exit();
    }

    if ($_POST['mode'] === 'ticket_progress') {
        header('Content-Type: application/json');
        $ticket_no = trim($_POST['ticket_no'] ?? ''); 
        
        try {
            // Modified to LEFT JOIN it_tech based on close_by to get it_desc
            $stmt = $conn->prepare("SELECT r.status,
                                           r.deptsel, d.dept_desc AS assigned_dept, 
                                           r.itsup, t.it_desc AS assigned_tech, 
                                           r.close_by, tc.it_desc AS close_by_name,
                                           r.date_closed 
                                    FROM reports r 
                                    LEFT JOIN tbl_dept d ON r.deptsel = d.dept_id 
                                    LEFT JOIN it_tech t ON r.itsup = t.itsup 
                                    LEFT JOIN it_tech tc ON r.close_by = tc.itsup 
                                    WHERE r.ticket_no = ?");
            $stmt->bind_param("s", $ticket_no);
            $stmt->execute();
            $result = $stmt->get_result();
            $main_info = $result->fetch_assoc();
            
            if (!$main_info) {
                echo json_encode(['error' => "Could not find ticket exact match for: '" . $ticket_no . "'"]);
                exit();
            }
            
            // Transfer logic correctly fetching from and to data
            $stmt2 = $conn->prepare("SELECT tr.nw_sup, t_nw.it_desc AS nw_tech_desc, 
                                            tr.f_deptsel, d_nw.dept_desc AS nw_dept_desc, 
                                            tr.itsup AS prev_sup, t_prev.it_desc AS prev_tech_desc,
                                            tr.deptsel AS prev_deptsel, d_prev.dept_desc AS prev_dept_desc,
                                            tr.date_rasigned 
                                     FROM tbl_reassigned tr 
                                     LEFT JOIN it_tech t_nw ON tr.nw_sup = t_nw.itsup 
                                     LEFT JOIN tbl_dept d_nw ON tr.f_deptsel = d_nw.dept_id 
                                     LEFT JOIN it_tech t_prev ON tr.itsup = t_prev.itsup 
                                     LEFT JOIN tbl_dept d_prev ON tr.deptsel = d_prev.dept_id
                                     WHERE tr.ticket_no = ? 
                                       AND tr.itsup IS NOT NULL AND tr.itsup != '' 
                                       AND tr.nw_sup IS NOT NULL AND tr.nw_sup != '' 
                                       AND tr.date_rasigned IS NOT NULL AND tr.date_rasigned != '' 
                                     ORDER BY tr.date_rasigned ASC");
            $stmt2->bind_param("s", $ticket_no);
           $stmt2->execute();
            $transfers = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
            
            // If the ticket has a transfer history, override the current assignment 
            // to display the latest support, latest dept, and set status to 'On Process'
            if (!empty($transfers)) {
                $latest_transfer = end($transfers);
                
                if (strtolower($main_info['status']) === 'assigned' || empty($main_info['status'])) {
                     $main_info['status'] = 'On Process';
                }
                
                // Replace the handler info with the reassigned personnel
                $main_info['assigned_dept'] = $latest_transfer['nw_dept_desc'];
                $main_info['assigned_tech'] = $latest_transfer['nw_tech_desc'];
            }
            
           echo json_encode([
                'status' => $main_info['status'], 
                'assigned_dept' => $main_info['assigned_dept'] ?? '',
                'assigned_tech' => $main_info['assigned_tech'] ?? '',
                'close_by' => $main_info['close_by'] ?? '',
                'close_by_name' => $main_info['close_by_name'] ?? '',
                'date_closed' => $main_info['date_closed'] ?? '',
                'transfers' => $transfers
            ]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit();
    }
}
?>
<head>
  <link rel="stylesheet" href="adminpanel.css">
</head>
<style>
/* Custom Scrollbars */
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

/* Modal Layout & Headers */
#ticket_modal .modal-dialog { 
    max-width: 1200px; 
    margin: 1.25rem auto; 
}
#ticket_modal .modal-content { 
    border-radius: 16px; 
    border: none; 
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); 
    overflow: hidden; 
}
#ticket_modal .modal-header { 
    background: linear-gradient(135deg, #213456, #334c7a); 
    color: #fff; 
    padding: 16px 18px; 
    border-bottom: 4px solid #E1AD01; 
}
#ticket_modal_header { 
    font-weight: 700; 
    font-size: 18px; 
    margin: 0; 
}
#ticket_modal .modal-body { 
    padding: 20px; 
    background-color: #f8fafc; 
}

:root {
    --hz-complete: #16A34A;    
    --hz-active: #0084ff;       
    --hz-pending: #dc3545;    
    --hz-muted: #e2e8f0;
    --hz-muted-border: #cbd5e1;
}

.hz-timeline-container { 
    position: relative; 
    padding: 25px 10px; 
    margin-bottom: 20px; 
    background: #ffffff; 
    border-radius: 12px; 
    border: 1px solid #e2e8f0; 
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
}

.hz-progress-bar-bg { 
    position: absolute; 
    top: 45px; 
    left: 5%; 
    right: 5%; 
    height: 4px; 
    background: #e2e8f0; 
    z-index: 1; 
}

.hz-progress-bar-fill { 
    position: absolute; 
    top: 45px; 
    left: 5%; 
    height: 4px; 
    background: var(--hz-complete);
    z-index: 2; 
    transition: width 0.4s ease, background 0.4s ease; 
}

.hz-progress-bar-fill.active-fill { 
    background: var(--hz-active);
}
.hz-progress-bar-fill.pending { 
    background: var(--hz-pending);
}

.hz-steps { 
    display: flex; 
    justify-content: space-between; 
    position: relative; 
    z-index: 3; 
}

.hz-step { 
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    text-align: center;
}

.hz-icon { 
    width: 24px; 
    height: 24px; 
    border-radius: 50%; 
    background: var(--hz-muted); 
    border: 4px solid #ffffff; 
    box-shadow: 0 0 0 2px var(--hz-muted-border); 
    margin-bottom: 12px; 
    transition: background 0.4s ease, box-shadow 0.4s ease, transform 0.2s ease;
}

.hz-step.completed .hz-icon { 
    background: var(--hz-complete); 
    box-shadow: 0 0 0 2px var(--hz-complete); 
}
.hz-step.completed .hz-label { 
    color: var(--hz-complete); 
}
.hz-step.completed .hz-sub,
.hz-step.completed .hz-sub strong { 
    color: var(--hz-complete); 
}

.hz-step.current .hz-icon { 
    background: var(--hz-active); 
    box-shadow: 0 0 0 3px rgba(0, 132, 255, 0.25);
    border-color: #ffffff;
    animation: hzPulse 2s infinite;
}
.hz-step.current .hz-label { 
    color: var(--hz-active); 
    font-weight: 900;
}
.hz-step.current .hz-sub,
.hz-step.current .hz-sub strong { 
    color: var(--hz-active); 
}

@keyframes hzPulse {
    0% {
        box-shadow: 0 0 0 0 rgba(0, 132, 255, 0.5);
    }
    70% {
        box-shadow: 0 0 0 8px rgba(0, 132, 255, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(0, 132, 255, 0);
    }
}

.hz-step.pending .hz-icon {
    background: var(--hz-pending);
    box-shadow: 0 0 0 2px var(--hz-pending);
}
.hz-step.pending .hz-label { 
    color: var(--hz-pending); 
}
.hz-step.pending .hz-sub,
.hz-step.pending .hz-sub strong { 
    color: var(--hz-pending); 
}

.hz-label { 
    font-size: 11px; 
    font-weight: 800; 
    color: #334155; 
    text-transform: uppercase; 
    line-height: 1.2; 
    transition: color 0.4s ease;
}

.hz-sub { 
    font-size: 10px; 
    color: #64748b; 
    margin-top: 6px; 
    line-height: 1.3; 
    word-wrap: break-word; 
    max-width: 140px; 
    transition: color 0.4s ease;
}

.hz-sub strong {
    color: #213456;
    transition: color 0.4s ease;
}

.container_remarks { 
    display: flex; 
    flex-direction: column-reverse; 
    height: 380px; 
    overflow-y: auto; 
    background-color: #ffffff; 
    border: 1px solid #e2e8f0; 
    border-radius: 12px; 
    padding: 15px; 
    margin-top: 10px; 
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02); 
}

#remarks_view { 
    display: flex; 
    flex-direction: column; 
    width: 100%; 
    gap: 10px; 
}

.chat-bubble { 
    max-width: 80%; 
    padding: 10px 14px; 
    border-radius: 16px; 
    font-size: 0.85rem; 
    line-height: 1.4; 
    position: relative; 
    word-wrap: break-word; 
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08); 
}

.chat-left { 
    align-self: flex-start; 
    background: #f1f0f0; 
    color: #1e293b; 
    border-bottom-left-radius: 4px; 
}

.chat-right { 
    align-self: flex-end; 
    background: #0084ff; 
    color: #ffffff; 
    border-bottom-right-radius: 4px; 
}

.msg-meta { 
    display: flex; 
    justify-content: space-between; 
    gap: 15px; 
    font-size: 0.7rem; 
    margin-bottom: 4px; 
    opacity: 0.85; 
}

.chat-left .msg-meta-name { color: #213456; font-weight: 700; }
.chat-right .msg-meta-name { color: #ffffff; font-weight: 700; }
.chat-left .msg-time { color: #64748b; }
.chat-right .msg-time { color: rgba(255, 255, 255, 0.85); }

#ticket_modal label { 
    font-size: 11px; 
    font-weight: 900; 
    color: #213456; 
    letter-spacing: .08em; 
    text-transform: uppercase; 
    margin-bottom: 6px; 
    display: block; 
}

#ticket_modal .form-control { 
    background: #fff !important; 
    color: #333 !important; 
    border: 1px solid #e2e8f0 !important; 
    border-radius: 8px; 
    font-size: 13px; 
}

#ticket_modal .form-control:focus { 
    box-shadow: 0 0 0 3px rgba(33, 52, 86, 0.1); 
    border-color: #213456 !important; 
}

/* Vertical Asset Tracking Timeline */
.tracking-timeline { 
    list-style: none; 
    padding: 0; 
    margin: 0; 
    position: relative; 
}

.tracking-timeline::before { 
    content: ''; 
    position: absolute; 
    top: 5px; 
    bottom: 0; 
    left: 11px; 
    width: 2px; 
    border-left: 2px dotted #a3a3a3; 
    z-index: 1; 
}

.timeline-item { 
    position: relative; 
    padding-left: 35px; 
    padding-bottom: 20px; 
}

.timeline-icon { 
    position: absolute; 
    left: 4px; 
    top: 2px; 
    width: 16px; 
    height: 16px; 
    border-radius: 50%; 
    background-color: #e0e0e0; 
    border: 3px solid #ffffff; 
    z-index: 2; 
    box-shadow: 0 0 0 1px #ccc; 
}

.timeline-item.completed .timeline-icon { 
    background-color: #16A34A; 
    box-shadow: 0 0 0 2px #16A34A; 
}

.timeline-item.pending .timeline-icon { 
    background-color: #E1AD01; 
    box-shadow: 0 0 0 2px #E1AD01; 
}

.timeline-desc { 
    font-size: 12px; 
    font-weight: 700; 
    color: #333; 
    margin-bottom: 2px; 
    text-transform: uppercase; 
}

.timeline-date { 
    font-size: 11px; 
    color: #6c757d; 
    font-style: italic; 
}

@media (max-width: 991px) {
    #ticket_modal .modal-dialog { 
        max-width: 96%; 
        margin: .75rem auto; 
    }
    .container_remarks { 
        max-height: 300px; 
    }
    .hz-steps { 
        flex-direction: column; 
        align-items: flex-start; 
        padding-left: 10px; 
    }
    .hz-step { 
        width: 100% !important; 
        flex-direction: row; 
        text-align: left; 
        margin-bottom: 15px; 
    }
    .hz-icon { 
        margin-bottom: 0; 
        margin-right: 15px; 
    }
    .hz-progress-bar-bg, .hz-progress-bar-fill { 
        display: none; 
    }
    .hz-sub { 
        margin-top: 2px; 
        margin-left: 0; 
    }
}
</style>

<div class="modal fade" id="ticket_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
     <div class="modal-content">
       <div class="modal-header">
           <h5 class="modal-title font-weight-bold" id="createReportModalLabel">
               <i class="fa fa-ticket mr-2" aria-hidden="true"></i> Ticket Details
           </h5>
           <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8; outline: none; background: none; border: none;">
             <span aria-hidden="true" style="font-size: 28px;">&times;</span>
           </button>
       </div>
      
       <div class="modal-body">
         <form method="post" id="modal_form" enctype="multipart/form-data">
             
             <div class="row mb-2" id="hz_timeline_row">
                 <div class="col-12">
                     <h6 class="text-uppercase mb-2" style="color:#213456; font-weight: 800; font-size: 13px;">Standard Ticket Progress</h6>
                     <div class="hz-timeline-container">
                         <div class="hz-progress-bar-bg"></div>
                         <div class="hz-progress-bar-fill" id="hz_progress_fill"></div>
                         <div class="hz-steps" id="hz_steps_container">
                         </div>
                     </div>
                 </div>
             </div>

             <div class="row" id="modal_columns_row">
                 <!-- Ticket Info Column -->
                 <div class="col-md-6 border-right pt-2 pb-2" id="col_ticket_info">
                     <div class="form-row">
                         <div class="form-group col-md-6">
                             <label>TICKET#</label>
                             <input type="text" class="form-control" name="ModalTicket_no" id="ModalTicket_no" readonly required>
                         </div>
                         <div class="form-group col-md-6">
                             <label>DATE CREATED</label>
                             <input type="text" class="form-control" name="ModalDate_create" id="ModalDate_create" readonly required>
                         </div>
                         <div class="form-group col-md-6">
                             <label>STORE</label>
                             <input type="text" class="form-control" name="ModalStore" id="ModalStore" readonly>
                         </div>
                         <div class="form-group col-md-6">
                             <label>SUBJECT</label>
                             <input type="text" class="form-control" name="ModalSubject" id="ModalSubject" readonly>
                         </div>
                         <div class="form-group col-md-12">
                             <label>STATUS</label>
                             <input type="text" class="form-control" name="ModalStatus" id="ModalStatus" readonly>
                         </div>
                         <div class="form-group col-md-12">
                             <label><strong>Attachments</strong></label>
                             <div id="attached_files" class="form-control" style="min-height:90px; background:#f8f9fa; overflow:auto;"></div>
                         </div>
                         
                         <div class="col-12 d-flex justify-content-between align-items-center mb-2">
                             <div>
                                 <a href="#" id="rars" class="mr-3 font-weight-bold text-primary">RARS FORM</a>
                                 <a href="#" id="vwfile" class="font-weight-bold text-primary">VIEW ATTACHMENTS</a>
                             </div>
                         </div>
                     </div>
                 </div>

                 <div class="col-md-6 pt-2 pb-2" id="col_comment_thread" style="background: #fafbfc;">
                     <h6 class="text-uppercase mb-3" style="color:#213456; font-weight: 800; font-size: 13px;">Comment Thread</h6>
                     <div class="container_remarks">
                         <div id="remarks_view"></div>
                     </div>
                     <div class="d-flex align-items-start mt-3">
                         <textarea class="form-control" id="Modal_reply" name="Modal_reply" style="height: 60px; resize: none;" placeholder="Type a message..."></textarea>
                         <button type="submit" class="btn btn-primary ml-2 px-3 py-2" name="Modal_action" id="Modal_action" style="height: 60px; border-radius: 8px;">
                             <i class="fa fa-paper-plane" aria-hidden="true"></i>
                         </button>
                     </div>
                     <input type="hidden" name="Modal_uId" id="Modal_uId" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">
                     <input type="hidden" name="operation" id="operation" value="Addcomment">
                     <div id="alrtmsg" class="mt-2"></div>
                 </div>

                 <div class="col-md-6 border-right border-top pt-3 pb-2 mt-2" id="col_asset_progress" style="display: none; background: #ffffff;">
                     <h6 class="text-uppercase mb-3" style="color:#E1AD01; font-weight: 800; font-size: 13px;">Asset Request Progress</h6>
                     <div class="tracking-container" style="max-height: 380px; overflow-y: auto; padding-right: 5px;">
                         <ul class="tracking-timeline" id="trackingMap"></ul>
                     </div>
                 </div>

                 <div class="col-md-6 border-top pt-3 pb-2 mt-2" id="col_remarks_thread" style="display: none; background: #fafbfc;">
                     <h6 class="text-uppercase mb-3" style="color:#dc3545; font-weight: 800; font-size: 13px;">Fixed Asset Remarks Thread</h6>
                     <div class="chat-container mb-2" style="height: 290px; overflow-y: auto; background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0;">
                         <div id="remarks_thread_container"></div>
                     </div>
                     <div class="chat-input-area mt-3">
                         <textarea class="form-control" id="new_remark_input" rows="2" placeholder="Type a new remark..."></textarea>
                         <button type="button" class="btn btn-sm w-100 mt-2" id="btn_send_remark" style="background-color: #dc3545; color: #ffffff; font-weight: 700;">
                             <i class="fas fa-paper-plane"></i> Send Remark
                         </button>
                     </div>
                 </div>

             </div>
         </form>
       </div>
     </div>
  </div>
</div>

<script type="text/javascript">
   $('#ticket_modal').on('show.bs.modal', function () {
        let currentTicket = $('#ModalTicket_no').val();
        if (currentTicket) {
            currentTicket = currentTicket.trim(); 
            loadHzTicketProgress(currentTicket); 
            loadCommentThread(currentTicket);
            checkAssetRequestProgress(currentTicket);
            loadAttachments(currentTicket);
        }
    });

   function loadHzTicketProgress(ticket_no) {
        $('#hz_steps_container').html('<div class="w-100 text-center"><div class="spinner-border spinner-border-sm text-primary"></div></div>');
        
        $.ajax({
            url: window.location.href, 
            type: 'POST',
            data: { mode: 'ticket_progress', ticket_no: ticket_no },
            dataType: 'json',
            success: function(res) {
                if(res.error) {
                    console.error("Database Error:", res.error);
                    $('#hz_steps_container').html('<div class="text-danger text-center font-weight-bold" style="font-size: 12px;">Error: ' + res.error + '</div>');
                } else {
                    renderHzTimeline(res);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
                $('#hz_steps_container').html('<div class="text-danger text-center font-weight-bold" style="font-size: 12px;">Failed to fetch data. Please check the browser console (F12).</div>');
            }
        });
    }

 function renderHzTimeline(data) {
    console.log("Server Response:", data); 
    const dbStatus = (data.status || '').toLowerCase().trim();
    const fallbackStatus = $('#ModalStatus').val().toLowerCase().trim();
    const checkStatus = dbStatus || fallbackStatus;
    console.log("Parsed Status for Timeline:", checkStatus);

    let steps = [];
    let currentLevel = 1;

    // 1. New Report
    steps.push({ label: 'NEW REPORT', sub: '', level: currentLevel });
    currentLevel++;

    // 2. Original Assignment
    let origDept = data.assigned_dept;
    let origTech = data.assigned_tech;
    
    // If there are transfers, the original assignment was the source of the VERY FIRST transfer
    if (data.transfers && data.transfers.length > 0) {
        origDept = data.transfers[0].prev_dept_desc || origDept;
        origTech = data.transfers[0].prev_tech_desc || origTech;
    }

    steps.push({ 
        label: 'ASSIGNED', 
        sub: origDept ? `<strong>${origDept}</strong><br>${origTech}` : '', 
        level: currentLevel 
    });

    // 3. Dynamic Transfer Steps (Transfer 1, Transfer 2, etc.)
    if (data.transfers && data.transfers.length > 0) {
        data.transfers.forEach((t, index) => {
            currentLevel++;
            
            let transferSub = '';
            
            // Output "From:" ONLY for Transfer 1 (index 0)
            if (index === 0) {
                let fromDept = t.prev_dept_desc || origDept || '';
                let fromTech = t.prev_tech_desc || origTech || '';
                transferSub += `From: <strong>${fromDept}</strong><br>${fromTech}<br>`;
            }
            
            // "To:" is output for all transfers
            transferSub += `To: <strong>${t.nw_dept_desc || ''}</strong><br>${t.nw_tech_desc || ''}<br><small>${t.date_rasigned}</small>`;

            steps.push({ 
                label: `TRANSFER ${index + 1}`, 
                sub: transferSub, 
                level: currentLevel 
            });
        });
    }

    // 4. On Process (Current handler)
    currentLevel++;
    const onProcessLvl = currentLevel; 
    steps.push({ 
        label: 'ON PROCESS', 
        sub: data.assigned_dept ? `<strong>${data.assigned_dept}</strong><br>${data.assigned_tech}` : (data.assigned_tech ? `<strong>${data.assigned_tech}</strong>` : ''), 
        level: onProcessLvl 
    });

    // 5. Pending (Optional)
    const isPending = checkStatus.includes('pending');
    if (isPending) {
        currentLevel++;
        steps.push({ label: 'PENDING', sub: '', level: currentLevel, isPendingNode: true });
    }

    // 6. Closing & Closed
    currentLevel++;
    const subClosingLvl = currentLevel;
    steps.push({ label: 'SUBJECT FOR CLOSING', sub: '', level: subClosingLvl });

    currentLevel++;
    const closedLvl = currentLevel;
    
    // NEW LOGIC: Only display closed details if status actually matches a closed state
    let closedSubText = '';
    if (checkStatus.includes('close') || checkStatus === 'completed' || checkStatus.includes('resolve') || checkStatus.includes('done')) {
        let closerName = data.close_by_name ? data.close_by_name : (data.close_by ? data.close_by : '');
        if(closerName) {
            closedSubText = `<strong>${closerName}</strong><br><small>${data.date_closed || ''}</small>`;
        }
    }

    steps.push({ 
        label: 'CLOSED', 
        sub: closedSubText, 
        level: closedLvl 
    });

    // Determine Active Level indicator
    let activeLevel = 1;

    if (checkStatus.includes('sub') || checkStatus.includes('subject') || checkStatus.includes('for closing') || checkStatus.includes('validate')) {
        activeLevel = subClosingLvl; 
    } 
    else if (checkStatus.includes('close') || checkStatus.includes('resolve') || checkStatus === 'completed' || checkStatus.includes('done')) {
        activeLevel = closedLvl; 
    } 
    else if (isPending) {
        let pendingNode = steps.find(s => s.isPendingNode);
        activeLevel = pendingNode ? pendingNode.level : onProcessLvl; 
    } 
    else if (checkStatus === 'on process' || checkStatus === 'on-process' || checkStatus.includes('process') || checkStatus.includes('going') || checkStatus.includes('prog') || checkStatus.includes('work')) {
        activeLevel = onProcessLvl; 
    } 
    else if (checkStatus.includes('transfer')) {
        activeLevel = onProcessLvl - 1; // Highlights the most recent transfer step
    }
    else if (checkStatus.includes('assign') || checkStatus.includes('acknowledged') || checkStatus.includes('accept')) {
        activeLevel = 2; 
    } 
    else if (checkStatus.includes('new') || checkStatus.includes('open')) {
        activeLevel = 1; 
    }

    let html = '';
    let stepCount = steps.length;
    
    // UI Rendering
    steps.forEach((step) => {
        let statusClass = '';
        
        if (step.level < activeLevel) {
            statusClass = 'completed'; 
        } else if (step.level === activeLevel) {
            if (isPending) {
                statusClass = 'pending';
            } else if (activeLevel === closedLvl) {
                statusClass = 'completed'; 
            } else {
                statusClass = 'current';
            }
        }
        
        html += `
            <div class="hz-step ${statusClass}" style="width: ${100/stepCount}%">
                <div class="hz-icon"></div>
                <div class="hz-label">${step.label}</div>
                <div class="hz-sub">${step.sub}</div>
            </div>
        `;
    });
    
    $('#hz_steps_container').html(html);
    let fillWidth = 0;
    if (stepCount > 1) {
        fillWidth = ((Math.min(activeLevel, stepCount) - 1) / (stepCount - 1)) * 100;
    }
    $('#hz_progress_fill').css('width', fillWidth + '%');
    
    // Style active bar colors based on status
    if (isPending) {
        $('#hz_progress_fill').addClass('pending').removeClass('active-fill').css('background', 'var(--hz-pending)'); 
    } else if (activeLevel < closedLvl) {
        $('#hz_progress_fill').addClass('active-fill').removeClass('pending').css('background', 'var(--hz-active)');
    } else {
        $('#hz_progress_fill').removeClass('pending active-fill').css('background', 'var(--hz-complete)'); 
    }
}

    $('#modal_form').on('submit', function(e) {
        e.preventDefault();
        
        let ticket_no = $('#ModalTicket_no').val();
        let reply_msg = $('#Modal_reply').val().trim();
        
        if (!reply_msg) return;
        $.ajax({
            url: 'insert.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#Modal_reply').val('');
                loadCommentThread(ticket_no);
            },
            error: function() {
                $('#alrtmsg').html('<span class="text-danger">Failed to send message.</span>');
            }
        });
    });

    $('#btn_send_remark').off('click').on('click', function() {
        var remarks = $('#new_remark_input').val();
        var ticket_no = $('#ModalTicket_no').val();

        if (!remarks.trim()) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Warning', 'Please type a remark first.', 'warning');
            } else {
                alert('Please type a remark first.');
            }
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
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'success', title: 'Saved!', timer: 1000, showConfirmButton: false });
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', response.message, 'error');
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            },
            error: function(xhr) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error', 'Communication failed.', 'error');
                } else {
                    alert('Communication failed.');
                }
                console.error(xhr.responseText);
            }
        });
    });

    function timeAgo(dateParam) {
        if (!dateParam) return "";
        let date = new Date(dateParam.replace(/-/g, "/"));
        let now = new Date();
        let seconds = Math.floor((now - date) / 1000);
        
        let interval = Math.floor(seconds / 86400);
        if (interval >= 1) return interval + " day" + (interval === 1 ? "" : "s") + " ago";
        
        interval = Math.floor(seconds / 3600);
        if (interval >= 1) return interval + " hour" + (interval === 1 ? "" : "s") + " ago";
        
        interval = Math.floor(seconds / 60);
        if (interval >= 1) return interval + " minute" + (interval === 1 ? "" : "s") + " ago";
        
        return "just now";
    }

    function loadCommentThread(ticket_no,rowData) {
        const $remarksView = $('#remarks_view');
        const ticketValue = (ticket_no || '').toString().trim();

        if (!ticketValue) return;
        
        $remarksView.html('<div class="text-center text-muted mt-4 mb-4"><div class="spinner-border spinner-border-sm me-2 text-primary"></div>Loading conversation...</div>');

        var target = $('#trackingMap');
        target.html('<p class="text-muted" style="font-size: 12px; margin-top: 10px;">Loading timeline...</p>');

        var isTechnical = (rowData && rowData.is_technical !== undefined && rowData.is_technical !== null) 
            ? parseInt(rowData.is_technical) 
            : 1;
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
                    
                    response.forEach(function(comment) {
                        let sender = comment.userId || 'Unknown';
                        let isMe = false;
                        
                        if(currentUserIdStr !== "" && sender === currentUserIdStr) isMe = true;
                        if(currentUserNameStr !== "" && sender.includes(currentUserNameStr)) isMe = true;
                        
                        let bubbleClass = isMe ? 'chat-right' : 'chat-left';
                        let relativeTime = timeAgo(comment.comment_date);
                        
                        html += `
                            <div class="chat-bubble ${bubbleClass}">
                                <div class="msg-meta">
                                    <span class="msg-meta-name">${sender}</span>
                                    <span class="msg-time">${comment.comment_date}</span> 
                                </div>
                                <div style="white-space: pre-wrap;">${comment.comment_details}</div>
                                <div style="font-size: 0.65rem; text-align: right; margin-top: 4px; font-style: italic; opacity: 0.8;">
                                    ${relativeTime}
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="text-center text-muted mt-4" style="font-size:13px;"><i class="fas fa-comments mb-2" style="font-size:24px; opacity:0.5;"></i><br>No comments yet. Start the conversation!</div>';
                }
                
                $remarksView.html(html);
                
                const $container = $('.container_remarks');
                if ($container.length) {
                    $container.scrollTop($container[0].scrollHeight);
                }
            },
            error: function() {
                $remarksView.html('<div class="text-danger text-center mt-3">Error loading comments.</div>');
            }
        });
    }

   function checkAssetRequestProgress(ticket_no) {
    const ticketValue = (ticket_no || '').toString().trim();
    if (!ticketValue) {
        hideAssetTrackerLayout();
        return;
    }

    $.ajax({
        url: 'get_first_comment.php', 
        type: 'POST',
        dataType: 'json',
        data: { ticket_no: ticketValue },
        success: function(response) {
            if (response && response.has_asset_record === true) { 
                showAssetTrackerLayout(response, ticketValue);
            } else {
                hideAssetTrackerLayout();
            }
        },
        error: function() {
            hideAssetTrackerLayout();
        }
    });
}

    function showAssetTrackerLayout(response, ticket_no) {
        $('#col_asset_progress').show();
        $('#col_remarks_thread').show();

        const statusLevels = {
                'submitted': 1, 'noted': 2, 'validated': 3, 
                'verified': 4, 'printed': 5, 'approved': 6,  'rejected': 6, 'purchased': 7, 'completed': 8
            };

        let dbStatus = (response.status || "").toLowerCase().trim();
        let currentLevel = statusLevels[dbStatus] || 0; 
        
        let isTechnical = (response.is_technical !== undefined && response.is_technical !== null) 
            ? parseInt(response.is_technical) 
            : 1;

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
                { desc: "Validated by admin support", date: response.date_validated, reqLevel: isTechnical === 1 ? 3 : 3 },
                { desc: "For administrative verification", date: null, reqLevel: isTechnical === 1 ? 3 : 3 }, 
                { desc: "Verified by the administrator", date: response.date_verified, reqLevel: isTechnical === 1 ? 4 : 4 },
                { desc: "For printing request form", date: null, reqLevel: isTechnical === 1 ? 4 : 4 }, 
                { desc: "Printed", date: response.date_printed, reqLevel: isTechnical === 1 ? 5 : 5 },
                { desc: "For General Manager Approval", date: null, reqLevel: isTechnical === 1 ? 5 : 5 }
            );

            if (dbStatus === 'rejected') {
                trackSteps.push(
                    { desc: "Rejected by General Manager", date: response.date_rejected || response.date_updated, reqLevel: isTechnical === 1 ? 6 : 6, isRejected: true }
                );
            } else {
                trackSteps.push(
                    { desc: "Approved by General Manager", date: response.date_approved, reqLevel: isTechnical === 1 ? 6 : 6 },
                    { desc:  "Transferred to PD for Procurement", date: null, reqLevel: isTechnical === 1 ? 6 : 6 }, 
                    { desc:  "Asset Purchased", date: response.date_purchased,  reqLevel: isTechnical === 1 ? 7 : 7 }, 
                    { desc: "Asset Ready for Release", date: null, reqLevel: isTechnical === 1 ? 7 : 7 }, 
                    { desc: "Asset replaced / Completed", date: response.date_completed, reqLevel: isTechnical === 1 ? 8 : 8 }
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
        
        $('#trackingMap').html(timelineHtml);
        
        loadRemarks(ticket_no);
    }

    function hideAssetTrackerLayout() {
        $('#col_asset_progress').hide();
        $('#col_remarks_thread').hide();
        $('#trackingMap').html('');
        $('#remarks_thread_container').html('');
    }

    function loadRemarks(ticket_no) {
        $('#remarks_thread_container').html('<div class="text-center mt-4"><div class="spinner-border spinner-border-sm me-2 text-primary"></div>Loading conversation...</div>');
        
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
                            <div style="margin-bottom: 15px; display: flex; flex-direction: column; align-items: flex-start;">
                               <span style="font-size: 11px; color: #64748b; margin-bottom: 4px;"><strong>${rmk.user_fullname || 'System'}</strong> • ${rmk.date_remarks}</span>
                                <div style="background: #f1f5f9; color: #334155; padding: 10px 14px; border-radius: 12px; border-top-left-radius: 2px; font-size: 13px; max-width: 95%; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">${rmk.remarks_note}</div>
                            </div>
                        `;
                    });
                } else {
                    html = `<div class="text-center mt-4 text-muted" style="font-size: 12px; font-style: italic;">No remarks found.</div>`;
                }
                $('#remarks_thread_container').html(html);
                
                var chatDiv = document.getElementById("remarks_thread_container").parentElement;
                if(chatDiv) chatDiv.scrollTop = chatDiv.scrollHeight;
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
                $('#remarks_thread_container').html('<div class="text-danger text-center mt-3" style="font-size: 12px;">Failed to fetch remarks. Check console.</div>');
            }
        });
    }

    function loadAttachments(ticketNo) {
        if (!ticketNo) {
          $('#attached_files').html('<div class="text-muted">No attachments available.</div>');
          return;
        }

        $.ajax({
          type: 'POST',
          url: 'sesticket.php',
          data: {tktval: ticketNo},
          success: function(response) {
            $('#attached_files').html(response);
            $('#images').html(response); 
          }
        });
    }

    $('#vwfile').click(function (e) { 
        e.preventDefault();
        var val = $('#ModalTicket_no').val();
        loadAttachments(val);
        $('#ViewFile #ticketxxx').val(val);
        $('#ViewFile #file-input').val('');
        $('#save_file').attr('disabled', 'true');
        $('#ViewFile').modal('show');
        $('#ticket_modal').modal('hide');
    });

    $('#rars').click(function (e) { 
      e.preventDefault();
      var PDtkt = $('#ModalTicket_no').val();
      var pdfUrl = '/ithelpdesk/users/rarspdf.php?ticket=' + encodeURIComponent(PDtkt);
      window.open(pdfUrl, '_blank');
    });
</script>