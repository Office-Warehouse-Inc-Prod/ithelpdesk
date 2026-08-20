<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mode'])) {
    include('db.php');
    header('Content-Type: application/json');
    if ($_POST['mode'] === 'fetch_remarks') {
        try {
            $ticket_no = $_POST['ticket_no'] ?? '';
            $stmt = $connection->prepare("SELECT far.remarks_note, 
                                               CONCAT(u.fname, ' ', u.lstname) AS user_fullname, 
                                               far.date_remarks 
                                        FROM fixed_asset_remarks far 
                                        LEFT JOIN users u ON far.remarks_by = u.id 
                                        WHERE far.ticket_no = ? 
                                        ORDER BY far.date_remarks ASC");
                                        
            $stmt->execute([$ticket_no]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            
        } catch (Exception $e) {
            echo json_encode([["remarks_note" => "Error loading remarks.", "user_fullname" => "System", "date_remarks" => ""]]);
        }
        exit(); 
    }

    if ($_POST['mode'] === 'add_remarks_only') {
        try {
            $ticket_no = $_POST['ticket_no'] ?? '';
            $remarks = trim($_POST['remarks_adtech'] ?? '');
            $user_id = $_SESSION['user_id'] ?? $_SESSION['tech_id'] ?? '';
            $store = $_SESSION['str_num'] ?? '';
            $currentDate = date('Y-m-d H:i:s');

            if (empty($ticket_no) || empty($remarks)) {
                echo json_encode(["status" => "error", "message" => "Missing data."]);
                exit();
            }

            if (empty($user_id)) {
                echo json_encode(["status" => "error", "message" => "Session expired or User ID missing. Please log in again."]);
                exit();
            }

            $connection->beginTransaction();
            $stmt1 = $connection->prepare("INSERT INTO fixed_asset_remarks (ticket_no, remarks_note, remarks_by, date_remarks) VALUES (?, ?, ?, ?)");
            $exec1 = $stmt1->execute([$ticket_no, $remarks, $user_id, $currentDate]);
            $notif_msg = "Store/User added a remark on ticket no " . $ticket_no;
            $stmt2 = $connection->prepare("INSERT INTO tbl_notif (ticket_no, store, itsup, notif_data, notif_val, notif_date) VALUES (?, ?, ?, ?, '10', ?)");
            $exec2 = $stmt2->execute([$ticket_no, $store, $user_id, $notif_msg, $currentDate]);
            
            if ($exec1 && $exec2) {
                $connection->commit();
                echo json_encode(["status" => "success", "message" => "Remarks saved successfully."]);
            } else {
                $connection->rollBack();
                echo json_encode(["status" => "error", "message" => "SQL Error: Saving remarks failed."]);
            }

        } catch (Exception $e) {
            if ($connection->inTransaction()) $connection->rollBack();
            echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        }
        exit(); 
    }
}
include 'userheader.php';
include 'switch_modal.php';

if (!isset($_SESSION['login']) || $_SESSION['login'] != 'true') {
  header("Location: index.php");
  exit();
}

require_once '../condb.php';
$con1 = new dbconfig();
?>
<style>
  :root {
    --primary-color: #E1AD01;
    --primary-light: #F4F0FF;
    --bg-body: #F4F5FA;
    --sidebar-width: 260px;
    --topbar-height: 70px;
    --card-shadow: 0 4px 12px 0 rgba(58, 53, 65, 0.1);
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

  .navbar,
  header,
  .topbar,
  .navbar-default {
    padding-top: 6px !important;
    padding-bottom: 6px !important;
    min-height: 60px !important;
  }

  .navbar-brand {
    font-size: 18px !important;
    font-weight: 700;
  }

  .navbar img {
    max-height: 42px !important;
  }

  .container-fluid.mt-4 {
    padding-left: 16px;
    padding-right: 16px;
  }

  @media (min-width: 992px) {
    .sticky-form {
      position: sticky;
      top: 16px;
      align-self: flex-start;
    }
  }

  label {
    color: var(--text);
    font-weight: 800 !important;
    margin-bottom: 6px;
  }

  small,
  .text-muted {
    color: var(--muted) !important;
  }

  /* inputs */
  .form-control,
  .textarea,
  .custom-select,
  select.form-control {
    background: #fff !important;
    color: var(--text) !important;
    border: 1px solid #21345658 !important;
    padding: 10px  !important;
    height: auto !important;
    transition: .15s ease;
    margin: 2px;
  }

  .form-control:focus,
  select.form-control:focus,
  textarea:focus {
    border-color: rgba(37, 99, 235, .55) !important;
    box-shadow: 0 0 0 .25rem rgba(37, 99, 235, .18) !important;
    outline: none !important;
  }

  .form-control::placeholder {
    color: #9aa3b2;
  }

  .cttxtarea {
    width: 100%;
    min-height: 140px;
    resize: vertical;
    background: #fff;
    color: var(--text);
    border: 1px solid #21345658 !important;
    border-radius: 14px;
    padding: 12px;
    line-height: 1.35;
  }

  #file-input {
    width: 100%;
    padding: 10px;
    background: #fff;
    border: 1px dashed #cfd6e6;
    border-radius: 14px;
    color: var(--muted);
  }

  .btn {
    border-radius: 12px !important;
    font-weight: 800;
    padding: 10px 14px;
  }

  .btn-primary {
    background: var(--primary) !important;
    border-color: var(--primary) !important;
    box-shadow: 0 10px 18px rgba(37, 99, 235, .18);
  }

  

  .btn-success {
    background: var(--success) !important;
    border-color: var(--success) !important;
    box-shadow: 0 10px 18px rgba(22, 163, 74, .16);
  }

  .soft-divider {
    height: 1px;
    background: var(--border);
    margin: 12px 0 14px;
  }

  .card-body {
      padding: 14px !important;
    }
  /* mobile */
  @media (max-width: 767.98px) {
    .container-fluid.mt-4 {
      margin-top: 12px !important;
    }

    .card-body {
      padding: 14px !important;
    }

    #addmsg {
      width: 100%;
    }

    #stat_picker {
      width: 100%;
      margin-top: -20px;
    }

    .w-100-mobile {
      width: 100% !important;
    }
  }

  .section-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    color: #E1AD01;
  }

  .section-title small {
    color: var(--muted);
    font-weight: 700;
  }

  .container-fluid.mt-4 {
    padding-left: 16px;
    padding-right: 16px;
  }

  .container-fluid.mt-4 .row {
    margin-left: -8px;
    margin-right: -8px;
  }

  .container-fluid.mt-4 .row>[class*="col-"] {
    padding-left: 8px;
    padding-right: 8px;
  }

  @media (min-width: 992px) {
    .sticky-form {
      position: static;
    }

    .sticky-form .card {
      position: sticky;
      top: 16px;
    }
  }

  #dvtables,
  #itmcard {
    width: 100% !important;
    max-width: 100%;
  }

  .ticket-controls {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
  }

  .ticket-controls .left-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .ticket-controls .right-actions {
    margin-left: auto;
    min-width: 220px;
  }

  @media (max-width: 767.98px) {
    .ticket-controls {
      flex-direction: column;
      align-items: stretch;
    }

    .ticket-controls .right-actions {
      min-width: 100%;
    }

    #addmsg {
      width: 100%;
    }

    #stat_picker {
      width: 100%;
    }
  }

  .navbar,
  header,
  .topbar,
  .navbar-default {
    background: linear-gradient(135deg, #213456, #334c7a);
    border-color: rgba(255, 255, 255, .12) !important;
  }

  .navbar .navbar-brand,
  .navbar .navbar-brand span,
  .navbar a,
  .navbar-nav>li>a {
    color: #ffffff !important;
  }

  .navbar a:hover,
  .navbar-nav>li>a:hover,
  .navbar a:focus,
  .navbar-nav>li>a:focus {
    color: #E5E7EB !important;
    opacity: .95;
  }

  .navbar .dropdown-menu {
   background: linear-gradient(135deg, #213456, #334c7a);
    border: 1px solid rgba(255, 255, 255, .12) !important;
  }

  .navbar .dropdown-menu a {
    color: #ffffff !important;
  }

  .navbar .dropdown-menu a:hover {
    background: rgba(255, 255, 255, .08) !important;
  }

  .navbar i,
  .navbar .fa,
  .navbar .fas {
    color: #ffffff !important;
  }
  .card .card-header {
    color: white;
    border-bottom-color: #213456;
    line-height: 30px;
    font-size: 15px;

    width: 100%;

    margin-bottom: 40px;
    background: linear-gradient(135deg, #213456, #334c7a);
    border-bottom: 1px solid rgba(0, 0, 0, .125);
  }

  .card-header:first-child {
    border-radius: calc(.25rem -1px)calc(.25rem -1px)00;
  }



 .card {
   transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: scale(1.02); 
  box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.card:hover .card-header,
.card:hover a {
  color: #E1AD01 !important;
}



.follow-up-btn {
    background: linear-gradient(135deg, #E1AD01, #d4a300) !important;
    color: #213456 !important;
    border: none !important;
    padding: 8px 16px !important;
    font-weight: 800 !important;
    font-size: 12px !important;
    border-radius: 6px !important;
    text-transform: uppercase;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(225, 173, 1, 0.2);
    transition: transform 0.2s, box-shadow 0.2s;
}

.follow-up-btn:hover {
  color: #E1AD01;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(225, 173, 1, 0.3);
}

#reports_table tbody tr:nth-child(even) {
    background-color: #fdfdfd;
}

.flex-row-center {
    display: flex;
    align-items: center;
    gap: 6px;
}

.card-body {
    background-color: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

#items_table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
}

#items_table thead th {
   background: linear-gradient(135deg, #213456, #334c7a);
    color: #E1AD01;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    padding: 15px 12px;
    border-bottom: 3px solid #E1AD01;
}

#items_table tbody tr {
    transition: background-color 0.2s ease;
}

#items_table tbody tr:hover {
    background-color: #f8f9fa;
}

#items_table td {
    padding: 12px;
    color: #333;
    border-bottom: 1px solid #e9ecef;
}

#items_table tbody tr td[colspan] {
    color: #6c757d;
    font-style: italic;
    padding: 30px;
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
</style>


<div class="container-fluid mt-4" id="helpdesk_row">
  <div class="row">

    <!-- LEFT: CREATE TICKET -->
    <div class="col-12 col-lg-4 col-xl-3 p-2 sticky-form">
      <div class="card w-100">
        <div class="card-header">
          <div class="section-title">
            <span><i class="fa-regular fa-square-plus"></i><strong>CREATE TICKET</strong></span>
            <small>Unified Helpdesk</small>
          </div>
        </div>

        <div class="card-body">
          <form method="post" id="report_form" enctype="multipart/form-data">
            <div class="row">

              <div class="form-group col-12">



                <input type="hidden" name="ticket_no" id="ticket_no">
                <input type="hidden" name="rars_no" id="rars_no">
                <input type="hidden" name="sesstr_num" id="sesstr_num" value="<?php echo $_SESSION['str_num']; ?>">
                <input type="hidden" name="str_code" id="str_code" value="<?php echo $_SESSION['str_code']; ?>">
                <input type="hidden" name="str_adrs" id="str_adrs" value="<?php echo $_SESSION['str_adrs']; ?>">
                <input type="hidden" name="str_contact" id="str_contact"
                  value="<?php echo $_SESSION['str_contact']; ?>">
         
                <input type="hidden" name="select_tos" id="select_tos" value="GENERAL"> 
                <input type="hidden" name="fix_asset_completed" id="fix_asset_completed" value="0">
                <input type="hidden" name="is_fix_asset" id="is_fix_asset" value="0">
                <input type="hidden" name="fa_item_code" id="fa_item_code" value="">

                <label><i class="fa fa-user-circle-o"></i>  Attention To:</label>
                <select class="form-control" id="deptsel" name="deptsel" required>
                   <option value="" selected disabled>---Select Department---</option>
                      <option value="1">IT</option>
                      <option value="2">ADMIN</option>
                      <option value="3">MARKETING</option>
                      <!-- <option value="4">MERCHANDISING</option> -->
                      <option value="6">VISUAL</option>
                      <option value="11">H.R</option>
                      <!-- <option value="12">ICG</option> -->
                      <option value="13">ACCOUNTS PAYABLE</option>
                      <!-- <option value="14">SALES ACCOUNTING</option> -->
                      <option value="15">TREASURY</option>
                      <option value="16">ACCOUNT RECEIVABLE</option>
                </select>


                    

                <label><i class="fas fa-envelope"></i> Subject</label>
                <select class="form-control" id="subject" name="subject" required>
                  <option value='' selected disabled>---Select Category---</option>
                </select>

                <div class="mt-2" id="subcategory_container" style="display:none;">
                  <label><i class="fas fa-list-ul"></i> Subcategory</label>
                  <select class="form-control selectpicker" name="subcategory" id="subcategory">
                    <option value='' selected disabled>---Select Subcategory---</option>
                  </select>
                </div>
                
                <div id="inline_fixed_asset_fields" style="display:none; background: #fff3cd; padding: 10px; border-radius: 8px; margin-top: 10px; margin-bottom: 10px; border: 1px solid #ffeeba;">
                    <h6 style="color: #856404; font-weight: bold; margin-bottom: 10px;"><i class="fas fa-info-circle"></i> Fixed Asset Details</h6>
                    
                    <div class="form-group" id="inline_fa_description_container">
                        <label>Description <span class="text-danger">*</span></label>
                        <select class="form-control selectpicker" id="inline_fa_description_sel" name="inline_fa_description_sel">
                            <option value="" selected disabled>Select Item</option>
                            <option value="CABINET">CABINET</option>
                            <option value="MOBILE PED">MOBILE PED</option>
                            <option value="LADDER">LADDER</option>
                            <option value="PUSH CART">PUSH CART</option>
                            <option value="LAMINATOR">LAMINATOR</option>
                            <option value="OTHER">OTHER</option>
                        </select>
                        <input type="text" class="form-control mt-2" id="inline_fa_description_txt" name="inline_fa_description_txt" placeholder="Enter item description" style="display:none;">
                    </div>

                    <div class="form-group" id="inline_fa_serial_container">
                        <label>Serial Number</label>
                        <input type="text" class="form-control" id="inline_fa_serial_number" name="inline_fa_serial_number" placeholder="Enter serial number (Optional)">
                    </div>
                </div>

                <div class="mt-2">
                  <select class="form-control selectpicker" name="subjectimp" id="subjectimp"
                    style="font-size: 12px; text-transform: uppercase;">
                    <option selected disabled>Select Subject</option>
                    <option value="OSS">OSS</option>
                    <option value="FURNITURE">FURNITURE</option>
                    <option value="TECHNOLOGY">TECHNOLOGY</option>
                  </select>
                </div>

                <div class="soft-divider"></div>

                <!-- ITEMS -->
                <label name="Qitem" id="Qitem">Quantity of Items</label>
                <select class="form-control selectpicker" name="QItems" id="QItems" style="font-size:12px;">
                  <option selected disabled>Select Here</option>
                  <option value="SINGLE">SINGLE ITEM</option>
                  <option value="MULTIPLE">MULTIPLE ITEM</option>
                </select>

                <div class="mt-3">
                  <label name="AluN" id="AluN">ALU</label>
                  <input type="text" name="Alu" id="Alu" class="form-control" placeholder="Enter ALU">
                </div>

                <div class="mt-3">
                  <label name="DescN" id="DescN">Description</label>
                  <input type="text" name="Desc" id="Desc" class="form-control" readonly
                    placeholder="Auto-filled description">
                </div>

                <div class="mt-3">
                  <label name="SerialLbl" id="SerialLbl">Serial No</label>
                  <input type="text" name="SerialNo" id="SerialNo" class="form-control" placeholder="Optional">
                </div>

                <div class="mt-3">
                  <label name="DefectLbl" id="DefectLbl">Nature of Defect</label>
                  <input type="text" name="Defect" id="Defect" class="form-control" placeholder="Describe the issue">
                </div>

                <div class="mt-3">
                  <label name="SupplierLbl" id="SupplierLbl">Supplier</label>
                  <input type="text" name="Supplier" id="Supplier" class="form-control" placeholder="Optional">
                </div>

                <div class="mt-3 text-right">
                  <input type="button" name="Additem" id="Additem" class="btn btn-success" value="Add Item" />
                </div>

                <div class="mt-3">
                  <label name="TypesUnit" id="TypesUnit">Classification</label>
                  <select class="form-control selectpicker" name="TypesOfUnit" id="TypesOfUnit" style="font-size:12px;">
                    <option selected disabled>Select Here</option>
                    <option value="1">Store Unit</option>
                    <option value="2">Costumer Stock</option>
                  </select>
                </div>

                <div class="soft-divider"></div>

                <input type="hidden" id="status" name="status" value="NEW REPORT">

                <div id="concern_container">
                  <label style="font-weight: bold;" id="titleconcern">Concern</label>
                  <p class="mb-2">
                    <textarea class="cttxtarea" id="concern" name="concern" minlength="10" maxlength="1000" row="2"
                      placeholder="Input your message here"></textarea>
                  </p>
                </div>

                <label style="font-weight: bold;" id="label_attached_file">Attached File</label>
                <p class="mb-3">
                  <input id="file-input" type="file" name="files[]" multiple>
                </p>

                <div class="row">
                  <div class="col-12">
                    <button type="button" id="btnFixAsset" class="btn btn-warning w-100 w-100-mobile mb-2" style="display:none; font-weight:bold; background-color: #E1AD01; border-color: #E1AD01;">
                        <i class="fas fa-edit"></i> Submit Fixed Asset Form
                    </button>

                    <input type="submit" name="action" id="action" class="btn btn-primary w-100 w-100-mobile"
                      value="Save Ticket" />
                  </div>
                </div>

              </div>
            </div>

            <input type="hidden" name="uId" id="uId" value="<?php echo $_SESSION['user_id']; ?>" />
            <input type="hidden" name="operation" id="operation" value="Add" />
          </form>

        </div>
      </div>
    </div>

    <!-- RIGHT: TICKETS + ITEMS TABLES -->
    <div class="col-12 col-lg-8 col-xl-9 p-2">

      <!-- CREATED TICKETS -->
      <div class="card" id="dvtables" style="width:auto;">
        <div class="card-header">
          <div class="section-title">
            <span ><strong>TICKETS CREATED</strong></span>
            <small>Track and follow up</small>
          </div>
        </div>

        <div class="card-body">

          <div class="ticket-controls">
            <div class="left-actions">
              <input type="hidden" class="form-control form-control-sm" name="slctdtick" id="slctdtick">
            </div>

            <div class="right-actions">
              <select class="form-control" name="stat_picker" id="stat_picker">
                <option value="OPEN">OPEN</option>
                <option value="CLOSED">CLOSED</option>
                <option value="ALL">ALL</option>
              </select>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-12" id="msg"></div>
          </div>

          <div class="mt-3 dt-wrap">
            <table id="reports_table" class="table hover table-bordered table-condensed text-center"></table>
          </div>

          <form method="post" id="stat_form" enctype="multipart/form-data">
            <input type="hidden" name="nticknum" id="nticknum">
            <input type="hidden" name="statOps" id="statOps">
          </form>
        </div>
      </div>


      <!-- ITEMS TABLE -->
      <div class="card mt-3" id="itmcard" style="width:100%;">
        <div class="card-header">
          <div class="section-title">
            <span>Items</span>
            <small>Ticket item list</small>
          </div>
        </div>

        <div class="card-body p-0">
    <div class="dt-wrap">
        <div class="table-responsive">
            <table id="items_table" class="table-hover text-center mb-0">
                <tbody>
                    <tr>
                        <td colspan="6" class="text-muted">
                            <i class="fas fa-box-open" style="color: #E1AD01; margin-right: 8px;"></i>
                            No items found in this ticket.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
      </div>


    </div>

  </div>
</div>

<div class="col-md-12">

  <!-- MERCH DR CARD (shows only when deptsel == 4) -->
  <div id="merchDrCard" class="card shadow-sm mt-3" style="display:none;border:1px solid #e5e7eb;border-radius:10px;">

    <form id="merch_ticket_form" method="post">

      <input type="hidden" name="uId" id="uId" value="<?php echo $_SESSION['user_id']; ?>">

      <div class="card shadow-sm mt-3">
        <div class="card-body">

          <h5 class="mb-3">Merchandising Defective Item Ticket</h5>

          <div class="mb-2">
            <label class="mb-1">Attention To</label>
            <input type="text" class="form-control" value="MERCHANDISING" readonly>
          </div>

          <div class="mb-2">
            <label class="mb-1">Subject</label>
            <input type="text" class="form-control" name="subject" id="subject" required
              style="text-transform:uppercase;">
          </div>

          <div class="mb-3">
            <label class="mb-1">Concern</label>
            <textarea class="form-control" name="concern" id="concern" rows="3" required></textarea>
          </div>

          <hr>

          <!-- Item inputs -->
          <div class="row">
            <div class="col-md-3 mb-2">
              <label class="mb-1">ALU</label>
              <input type="text" class="form-control" id="m_alu">
            </div>

            <div class="col-md-5 mb-2">
              <label class="mb-1">Description</label>
              <input type="text" class="form-control" id="m_desc">
            </div>

            <div class="col-md-4 mb-2">
              <label class="mb-1">Serial #</label>
              <input type="text" class="form-control" id="m_serial" placeholder="Required">
            </div>

            <div class="col-md-5 mb-2">
              <label class="mb-1">Nature of Defect</label>
              <input type="text" class="form-control" id="m_defect">
            </div>

            <div class="col-md-3 mb-2">
              <label class="mb-1">Vendor</label>
              <input type="text" class="form-control" id="m_vendor">
            </div>

            <div class="col-md-2 mb-2">
              <label class="mb-1">Qty</label>
              <input type="number" class="form-control" id="m_qty" min="1" value="1">
            </div>

            <div class="col-md-2 mb-2">
              <label class="mb-1">Classification</label>
              <select class="form-control" id="m_classification">
                <option value="" selected disabled>Select</option>
                <option value="STORE_UNIT">Store Unit</option>
                <option value="CUSTOMER_UNIT">Customer Unit</option>
              </select>
            </div>

            <div class="col-12 text-right mt-1">
              <button type="button" class="btn btn-success" id="m_addItem">
                <i class="fas fa-plus"></i> Add Item
              </button>
            </div>
          </div>

          <div class="table-responsive mt-3">
            <table class="table table-bordered table-hover mb-0" id="merchItemsTable" style="font-size:13px;">
              
            </table>
          </div>

          <input type="hidden" name="items_json" id="items_json" value="[]">

          <div class="mt-3">
            <button type="submit" class="btn btn-primary w-100" id="btnSubmitMerch">
              Submit Ticket
            </button>
          </div>

        </div>
      </div>
    </form>

  </div>

</div>


<div class="modal fade" id="ticket_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
     <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); overflow: hidden;">
       <div class="modal-header" style="background: linear-gradient(135deg, #213456, #334c7a); color: #fff; padding: 16px 18px; border-bottom: 4px solid #E1AD01;">
            <h5 class="modal-title font-weight-bold" id="createReportModalLabel">
                <i class="fa fa-ticket mr-2" aria-hidden="true"></i> Ticket Details
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8; outline: none; background: none; border: none;">
             <span aria-hidden="true" style="font-size: 28px;">&times;</span>
            </button>
       </div>
      
       <div class="modal-body" style="padding: 20px; background-color: #f8fafc;">
         <form method="post" id="modal_form" enctype="multipart/form-data">
             <div class="row" id="modal_columns_row">
                 
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

                 <div class="col-md-8 border-right pt-2 pb-2" id="col_comment_thread" style="background: #fafbfc;">
                     <h6 class="text-uppercase mb-3" style="color:#213456; font-weight: 800; font-size: 13px;">Comment Thread</h6>
                     
                     <div class="container_remarks" style="display: flex; flex-direction: column-reverse; height: 380px; overflow-y: auto; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; margin-top: 10px;">
                         <div id="remarks_view" style="display: flex; flex-direction: column; width: 100%; gap: 10px;"></div>
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

                 <div class="col-md-2 pt-2 pb-2" id="col_asset_progress" style="display: none;">
                     <h6 class="text-uppercase mb-3" style="color:#E1AD01; font-weight: 800; font-size: 13px;">Asset Request Progress</h6>
                     <div class="tracking-container" style="max-height: 480px; overflow-y: auto; padding-right: 5px;">
                         <ul class="tracking-timeline" id="trackingMap" style="list-style: none; padding: 0; margin: 0; position: relative;"></ul>
                     </div>
                 </div>

             </div>
         </form>
       </div>
     </div>
  </div>
</div>

<script type="text/javascript">
    function validateSubmitButton() {
        var isFixAsset = $('#is_fix_asset').val() == '1';
        var concernLength = $('#concern').val().trim().length;
        var subcatText = ($('#subcategory option:selected').text() || '').toUpperCase();
        var isValid = true;

        if (isFixAsset) {
            if ($('#inline_fixed_asset_fields').is(':visible') && $('#inline_fa_description_container').is(':visible')) {
                var descSel = $('#inline_fa_description_sel').val();
                var descTxt = $('#inline_fa_description_txt').val();
                if (!descSel || (descSel === 'OTHER' && !descTxt)) {
                    isValid = false;
                }
            }
            if (concernLength < 10) {
                isValid = false;
            }
            if (subcatText.includes('REPLACEMENT') && $('#file-input').get(0).files.length === 0) {
                isValid = false;
            }
        } else {
            if (concernLength < 10) {
                isValid = false;
            }
        }

        $('#action').prop('disabled', !isValid);
    }

    function checkFixAssetCondition() {
        var dept = $('#deptsel').val();
        var subj = ($('#subject').val() || '').toUpperCase();
        var subcatText = ($('#subcategory option:selected').text() || '').toUpperCase();
        
        var isFixAsset = (dept == '2' && (subj.includes('FIX ASSET') || subj.includes('FIXED ASSET')));
        var isReplacement = subcatText.includes('REPLACEMENT');
        var isNew = subcatText.includes('NEW');
        var isTransfer = subcatText.includes('TRANSFER');

        if (isFixAsset) {
            $('#is_fix_asset').val('1');
            $('#btnFixAsset').hide(); 

            if (isReplacement || isNew || isTransfer) {
                $('#inline_fixed_asset_fields').slideDown();

                if (isReplacement) {
                    $('#inline_fa_serial_container').show();
                    $('#inline_fa_description_container').show();
                    $('#concern_container').show();
                    $('#concern').prop('required', true);
                    $('#file-input').prop('required', true);
                    $('#label_attached_file').html('Attached File (Required)');
                } else if (isNew) {
                    $('#inline_fa_serial_container').hide();
                    $('#inline_fa_description_container').show();
                    $('#concern_container').show();
                    $('#concern').prop('required', true);
                    $('#file-input').prop('required', false);
                    $('#label_attached_file').html('Attached File (Not Required)');
                } else if (isTransfer) {
                    $('#inline_fa_serial_container').hide();
                    $('#inline_fa_description_container').hide(); 
                    $('#concern_container').show();
                    $('#concern').prop('required', true);
                    $('#file-input').prop('required', false);
                    $('#label_attached_file').html('Attached File (Not Required)');
                }

                validateSubmitButton();
            } else {
                $('#inline_fixed_asset_fields').hide();
                validateSubmitButton();
            }
        } else {
            $('#is_fix_asset').val('0');
            $('#inline_fixed_asset_fields').hide();
            $('#concern_container').show();
            $('#concern').prop('required', true);
            $('#file-input').prop('required', false);
            $('#label_attached_file').html('Attached File');
            validateSubmitButton();
        }
    }

    $(document).ready(function() {
        $('#inline_fa_description_sel').change(function() {
            if ($(this).val() == 'OTHER') {
                $('#inline_fa_description_txt').show().prop('required', true);
            } else {
                $('#inline_fa_description_txt').hide().prop('required', false).val('');
            }
            validateSubmitButton();
        });

        $('#deptsel').on('change', function() {
            if(typeof checkFixAssetCondition === "function") {
                checkFixAssetCondition();
            }
        });
        
        $('#subject').on('change select2:select', function() {
            var selectedSubj = $(this).val() || "";
            var dept = $('#deptsel').val();
            
            if (dept == '2') {
                if (selectedSubj && (selectedSubj.toUpperCase().includes('FIX ASSET') || selectedSubj.toUpperCase().includes('FIXED ASSET'))) {
                    $('#subcategory_container').fadeIn();
                    $.ajax({
                        url: "select.php",
                        type: "get",
                        dataType: 'json',
                        data: { type: 'sub_category', cat_id: 37 },
                        success: function(response) {
                            var $subcat = $('#subcategory');
                            $subcat.empty();
                            $subcat.append('<option value="" selected disabled>---Select Subcategory---</option>');
                            if(response && response.length > 0) {
                                $.each(response, function(index, item) {
                                    $subcat.append('<option value="' + item.id + '">' + item.text + '</option>');
                                });
                            }
                        }
                    });
                } else {
                    $('#subcategory_container').fadeOut();
                    $('#subcategory').val('');
                }
            } else {
                $('#subcategory_container').fadeOut();
                $('#subcategory').val('');
            }
            
            if(typeof checkFixAssetCondition === "function") {
                checkFixAssetCondition();
            }
        });

        $('#subcategory').on('change', function() {
            if(typeof checkFixAssetCondition === "function") {
                checkFixAssetCondition();
            }
        });
        
        $('#file-input').on('change', function() {
             validateSubmitButton();
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

function valtxt(){
    if($('#subject').val() == null || $('#subject').val().trim()==""){
        $('#subject').addClass('border-danger');
        setTimeout(() => { $('#subject').removeClass('border-danger'); }, 5000);
        return false;
    }else if ($('#select_tos').val() == null || $('#select_tos').val().trim()==""){
        $('#select_tos').addClass('border-danger');
        setTimeout(() => { $('#select_tos').removeClass('border-danger'); }, 5000);
        return false;
    }
    
    let isFixAsset = $('#is_fix_asset').val() == '1';
    let subcatText = ($('#subcategory option:selected').text() || '').toUpperCase();

    if ($('#concern').val().trim()==""){
        $('#concern').addClass('border-danger');
        setTimeout(() => { $('#concern').removeClass('border-danger'); }, 5000);
        return false;
    }

    if (isFixAsset) {
        if ($('#inline_fixed_asset_fields').is(':visible') && $('#inline_fa_description_container').is(':visible')) {
            var descSel = $('#inline_fa_description_sel').val();
            var descTxt = $('#inline_fa_description_txt').val();
            if (!descSel || (descSel === 'OTHER' && !descTxt)) {
                alert("Please complete Fixed Asset Description.");
                return false;
            }
        }
        if (subcatText.includes('REPLACEMENT') && $('#file-input').get(0).files.length === 0) {
            alert("An attached file is required for Replacement Fixed Asset requests.");
            return false;
        }
    }

    return true;
}

const validationLength = 1000;
const concern = document.getElementById('concern');
const action = document.getElementById('action');

concern.addEventListener('input', function() {
  const inputValue = concern.value;
  const inputLength = inputValue.length;

  if (inputLength > validationLength) {
    concern.value = inputValue.substr(0, validationLength);
  }
  
  validateSubmitButton();
});

</script>


<?php include 'userpanel_obj.php'; ?>