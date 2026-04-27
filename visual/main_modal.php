
<!-- Start of Add/Edit Modal -->

<style>


/* Modal Content */
.modal-content {
  background-color: #fff;  /* clean white background */
  border-radius: 8px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.15);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #333;
  padding: 1.5rem;
}

/* Modal Header */
.modal-header {
  border-bottom: 1px solid #dee2e6;
  padding-bottom: 1rem;
}

.modal-header .modal-title {
  font-weight: 700;
  font-size: 1.5rem;
  color: #222;
}

/* Form Labels */
label {
  font-weight: 600;
  color: #555;
  font-size: 0.9rem;
}

/* Form Inputs & Selects */
.form-control-sm {
  border: 1px solid #ced4da;
  border-radius: 4px;
  font-size: 0.95rem;
  padding: 0.375rem 0.75rem;
  transition: border-color 0.2s ease;
  color: #333;
}

.form-control-sm:focus {
  border-color: #007bff;
  box-shadow: 0 0 6px rgba(0,123,255,0.25);
  outline: none;
}

/* Textareas */
textarea.form-control-sm {
  resize: vertical;
  min-height: 70px;
  font-size: 0.95rem;
}

/* Buttons */
.btn-success {
  background-color: #007bff;
  border: none;
  font-weight: 600;
  padding: 0.5rem 1.25rem;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.btn-success:hover {
  background-color: #0056b3;
}

.btn-danger {
  border-radius: 5px;
}

/* Comment Thread */
#msg_thread {
  margin-top: 2rem;
  border-top: 1px solid #dee2e6;
  padding-top: 1.5rem;
}

#msg_thread label {
  font-weight: 700;
  margin-bottom: 0.5rem;
  display: block;
  color: #444;
}

/* Comment input */
#admsg {
  border: 1px solid #ced4da;
  border-radius: 4px;
  padding: 0.5rem;
  font-size: 0.95rem;
  min-height: 80px;
  resize: vertical;
}

/* Comment Thread Container */
.container_remarks {
  max-height: 250px;
  overflow-y: auto;
  background: #f8f9fa;
  border-radius: 6px;
  padding: 1rem;
  border: 1px solid #dee2e6;
}

/* Individual comment */
#remarks_view .comment {
  background-color: #e9ecef;
  border-radius: 10px;
  padding: 0.75rem 1rem;
  margin-bottom: 0.75rem;
  font-size: 0.9rem;
  color: #212529;
  box-shadow: 1px 1px 3px rgba(0,0,0,0.05);
}

/* Modal Footer */
.modal-footer {
  border-top: 1px solid #dee2e6;
  padding-top: 1rem;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}


</style>

<div class="col-12 col-lg-12 modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" style="max-width: 100%;">
<form method="post" id="report_form" enctype="multipart/form-data">
<div class="modal-content" >
<div class="modal-header">
<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
<h4 class="modal-title" id="userModal_header" value="Add Report"></h4>
<!-- <button type="button" id="prntForm" class="btn btn-info float-right" data-dismiss="modal"><i class="fas fa-print"></i></button> -->
</div>


<div class="modal-body">
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


<div class="col-xl-12 d-inline-flex p-2">
<!-- <label class="" style="font-weight: bold;">SELECT DEPARTMENT:</label> -->

                            <div class="input-group xl-2">
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
                            <option value="11">H.R</option>
                            <!-- <option value="12">ICG</option> -->
                            <option value="13">ACCOUNTS PAYABLE</option>
                            <!-- <option value="14">SALES ACCOUNTING</option> -->
                            <option value="15">TREASURY</option>
                            <option value="16">ACCOUNT RECEIVABLE</option>
                            </select>
                            </div>
                            </div>

                <!-- SUBJECT -->
                <label><i class="fas fa-envelope"></i> Subject</label>
                <select class="form-control" id="subject" name="subject" required>
                    <option value='' selected disabled>---Select Category---</option>
                  </select>
                <!-- <input type="text" class="form-control" name="subject" id="subject"
                       style="font-size: 12px; text-transform: uppercase;"
                       minlength="5" maxlength="35" autocomplete="off"
                       placeholder="Type subject (optional if selecting below)"> -->
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
</div>
</div>
</div>