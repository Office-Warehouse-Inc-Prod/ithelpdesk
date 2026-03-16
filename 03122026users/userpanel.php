<?php 
include 'userheader.php';
include 'switch_modal.php';

if ($_SESSION['login']!='true'){
  header("Location: index.php");
  exit();
}

include '../condb.php';
$con1=new dbconfig(); 
?>

<style>
  :root{
    --bg:#f4f6fb;
    --card:#ffffff;
    --card2:#fbfcff;
    --border:#e6eaf2;
    --text:#1f2a37;
    --muted:#6b7280;
    --primary:#2563eb;
    --primary2:#1d4ed8;
    --success:#16a34a;
    --radius:16px;
    --shadow: 0 10px 24px rgba(16, 24, 40, .08);
  }

body{
  background:
    linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.85)),
    url('../images/bg_login.png');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  color: var(--text);
}

  /* ===== Reduce Header Height ===== */
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

  .container-fluid.mt-4{ padding-left:16px; padding-right:16px; }

  /* cards */
  .card{
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
  }
  .card-header{
    background: linear-gradient(180deg, var(--card2), var(--card));
    border-bottom: 1px solid var(--border);
    color: var(--text);
    font-weight: 800 !important;
    padding: 14px 16px !important;
  }
  .card-body{ padding: 16px !important; }

  /* sticky form on desktop */
  @media (min-width: 992px){
    .sticky-form{
      position: sticky;
      top: 16px;
      align-self: flex-start;
    }
  }

  /* text + labels */
  label{
    color: var(--text);
    font-weight: 800 !important;
    margin-bottom: 6px;
  }
  small, .text-muted{ color: var(--muted) !important; }

  /* inputs */
  .form-control, .custom-select, select.form-control{
    background: #fff !important;
    color: var(--text) !important;
    border: 1px solid var(--border) !important;
    border-radius: 12px !important;
    padding: 10px 12px !important;
    height: auto !important;
    transition: .15s ease;
  }
  .form-control:focus, select.form-control:focus, textarea:focus{
    border-color: rgba(37,99,235,.55) !important;
    box-shadow: 0 0 0 .25rem rgba(37,99,235,.18) !important;
    outline: none !important;
  }
  .form-control::placeholder{ color: #9aa3b2; }

  /* textarea */
  .cttxtarea{
    width: 100%;
    min-height: 140px;
    resize: vertical;
    background: #fff;
    color: var(--text);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 12px;
    line-height: 1.35;
  }

  /* file input */
  #file-input{
    width: 100%;
    padding: 10px;
    background: #fff;
    border: 1px dashed #cfd6e6;
    border-radius: 14px;
    color: var(--muted);
  }

  /* buttons */
  .btn{
    border-radius: 12px !important;
    font-weight: 800;
    padding: 10px 14px;
  }
  .btn-primary{
    background: var(--primary) !important;
    border-color: var(--primary) !important;
    box-shadow: 0 10px 18px rgba(37,99,235,.18);
  }
  .btn-primary:hover{ background: var(--primary2) !important; border-color: var(--primary2) !important; }
  .btn-success{
    background: var(--success) !important;
    border-color: var(--success) !important;
    box-shadow: 0 10px 18px rgba(22,163,74,.16);
  }

  /* section divider */
  .soft-divider{
    height: 1px;
    background: var(--border);
    margin: 12px 0 14px;
  }

  /* tables */
  table.table{
    color: var(--text);
    border-color: var(--border) !important;
    margin-bottom: 0;
    background: #fff;
  }
  .table thead th{
    background: #f6f8fd;
    color: #374151;
    border-color: var(--border) !important;
    font-weight: 900;
    white-space: nowrap;
  }
  .table td, .table th{ border-color: var(--border) !important; vertical-align: middle !important; }
  .table.hover tbody tr:hover{ background: #f8fbff !important; }

  /* datatables */
  .dataTables_wrapper .dataTables_filter input,
  .dataTables_wrapper .dataTables_length select{
    background: #fff !important;
    border: 1px solid var(--border) !important;
    color: var(--text) !important;
    border-radius: 10px !important;
  }
  .dataTables_wrapper .dataTables_info,
  .dataTables_wrapper .dataTables_paginate{
    color: var(--muted) !important;
  }
  .page-link{
    background: #fff !important;
    border: 1px solid var(--border) !important;
    color: var(--text) !important;
  }
  .page-item.active .page-link{
    background: var(--primary) !important;
    border-color: var(--primary) !important;
    color: #fff !important;
  }

  /* mobile */
  @media (max-width: 767.98px){
    .container-fluid.mt-4{ margin-top: 12px !important; }
    .card-body{ padding: 14px !important; }
    #addmsg{ width: 100%; }
    #stat_picker{ width: 100%; margin-top: 8px; }
    .w-100-mobile{ width: 100% !important; }
  }

  /* tiny polish */
  .section-title{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
  }
  .section-title small{
    color: var(--muted);
    font-weight: 700;
  }

  /* ... KEEP YOUR EXISTING CSS ABOVE ... */

  /* Make the row breathe */
  .container-fluid.mt-4 { padding-left:16px; padding-right:16px; }
  .container-fluid.mt-4 .row { margin-left:-8px; margin-right:-8px; }
  .container-fluid.mt-4 .row > [class*="col-"] { padding-left:8px; padding-right:8px; }

  /* IMPORTANT: sticky should be on the card, not the whole column */
  @media (min-width: 992px){
    .sticky-form{ position: static; } /* override your current sticky-form */
    .sticky-form .card{ position: sticky; top: 16px; }
  }

  /* Right cards should never look squeezed */
  #dvtables, #itmcard{
    width: 100% !important;
    max-width: 100%;
  }

  /* Make DataTables area stable */
  .dt-wrap{
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 14px;
  }
  table.dataTable{ width: 100% !important; }
  #reports_table, #items_table{ width: 100% !important; table-layout: auto; }

  /* Header controls: better alignment, no squeezing */
  .ticket-controls{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    flex-wrap:wrap;
  }
  .ticket-controls .left-actions{
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
  }
  .ticket-controls .right-actions{
    margin-left:auto;
    min-width: 220px;
  }

  /* Mobile: full width controls */
  @media (max-width: 767.98px){
    .ticket-controls{ flex-direction:column; align-items:stretch; }
    .ticket-controls .right-actions{ min-width: 100%; }
    #addmsg{ width:100%; }
    #stat_picker{ width:100%; }
  }

  /* ===== Header / Navbar color override ===== */
.navbar,
header,
.topbar,
.navbar-default{
  background: #121C31 !important;
  border-color: rgba(255,255,255,.12) !important;
}

/* Brand + links */
.navbar .navbar-brand,
.navbar .navbar-brand span,
.navbar a,
.navbar-nav > li > a{
  color: #ffffff !important;
}

/* Hover/focus */
.navbar a:hover,
.navbar-nav > li > a:hover,
.navbar a:focus,
.navbar-nav > li > a:focus{
  color: #E5E7EB !important;
  opacity: .95;
}

/* Dropdown + caret (if any) */
.navbar .dropdown-menu{
  background: #121C31 !important;
  border: 1px solid rgba(255,255,255,.12) !important;
}
.navbar .dropdown-menu a{
  color: #ffffff !important;
}
.navbar .dropdown-menu a:hover{
  background: rgba(255,255,255,.08) !important;
}

/* Icons */
.navbar i, .navbar .fa, .navbar .fas{
  color: #ffffff !important;
}



</style>


<div class="container-fluid mt-4" id="helpdesk_row">
  <div class="row">

    <!-- LEFT: CREATE TICKET -->
    <div class="col-12 col-lg-4 col-xl-3 p-2 sticky-form">
      <div class="card w-100">
        <div class="card-header">
          <div class="section-title">
            <span>Create Ticket</span>
            <small>Unified Helpdesk</small>
          </div>
        </div>

        <div class="card-body">
          <form method="post" id="report_form" enctype="multipart/form-data">
            <div class="row">

              <div class="form-group col-12">

              

                <input type="hidden" name="ticket_no" id="ticket_no">
                <input type="hidden" name="rars_no" id="rars_no">
                <input type="hidden" name="sesstr_num" id="sesstr_num" value="<?php echo $_SESSION['str_num'];?>">
                <input type="hidden" name="str_code" id="str_code" value="<?php echo $_SESSION['str_code'];?>">
                <input type="hidden" name="str_adrs" id="str_adrs" value="<?php echo $_SESSION['str_adrs'];?>">
                <input type="hidden" name="str_contact" id="str_contact" value="<?php echo $_SESSION['str_contact'];?>">
<!-- <input type="hidden" name="deptsel" id="deptsel" value="2">       1=IT default (change if needed) -->
<input type="hidden" name="select_tos" id="select_tos" value="GENERAL"> <!-- default TOS -->


<div class="col-md-12 d-inline-flex p-2">
<!-- <label class="" style="font-weight: bold;">SELECT DEPARTMENT:</label> -->

                            <div class="input-group mb-2">
                            <div class="input-group-prepend">
                            <div class="input-group-text">Attention To:</div>
                            </div>
                            <select class="form-control"  name="deptsel" id="deptsel" required>
                            <option value="" selected disabled >Select Here</option>
                            <option value="1" >IT</option>
                            <option value="2" >ADMIN</option>
                            <option value="3">MARKETING</option>
                            <!-- <option value="4">MERCHANDISING</option> -->
                            <option value="6">VISUAL</option>




                            </select>
                            </div>
                            </div>

                <!-- SUBJECT -->
                <label><i class="fas fa-envelope"></i> Subject</label>
                <input type="text" class="form-control" name="subject" id="subject"
                       style="font-size: 12px; text-transform: uppercase;"
                       minlength="5" maxlength="35" autocomplete="off"
                       placeholder="Type subject (optional if selecting below)">
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
                  <input type="text" name="Desc" id="Desc" class="form-control" readonly placeholder="Auto-filled description">
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

                <!-- CONCERN -->
                <label style="font-weight: bold;" id="titleconcern">Concern</label>
                <p class="mb-2">
                  <textarea class="cttxtarea" id="concern" name="concern" minlength="10" maxlength="1000"
                            row="2" placeholder="Input your message here"></textarea>
                </p>

                <!-- FILE -->
                <label style="font-weight: bold;">Attached File</label>
                <p class="mb-3">
                  <input id="file-input" type="file" name="file" Multiple>
                </p>

                <!-- SUBMIT -->
                <div class="row">
                  <div class="col-12">
                    <input type="submit" name="action" id="action"
                           class="btn btn-primary w-100 w-100-mobile"
                           value="Save Ticket"/>
                  </div>
                </div>

              </div>
            </div>

            <input type="hidden" name="uId" id="uId" value="<?php echo $_SESSION['user_id'];?>" />
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
      <span>Created Tickets</span>
      <small>Track and follow up</small>
    </div>
  </div>

  <div class="card-body">

    <div class="ticket-controls">
      <div class="left-actions">
        <button type="button" id="addmsg" class="btn btn-primary">
          Follow up report <i class="fa fa-comment" aria-hidden="true"></i>
        </button>

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
      <table id="items_table" class="table hover table-bordered table-condensed text-center mb-0">
        <tbody>
          <tr>
            <td colspan="X">No data available</td>
          </tr>
        </tbody>
      </table>
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

      <!-- Items table -->
      <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover mb-0" id="merchItemsTable" style="font-size:13px;">
          <thead class="thead-light">
            <tr>
              <th style="width:55px;">#</th>
              <th>ALU</th>
              <th>Description</th>
              <th>Serial #</th>
              <th>Nature of Defect</th>
              <th>Vendor</th>
              <th style="width:90px;">Qty</th>
              <th style="width:160px;">Classification</th>
              <th style="width:90px;">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
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

<?php include 'userpanel_obj.php'; ?>
