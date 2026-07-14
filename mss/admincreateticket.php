<?php
include 'admin.php';
include '../condb.php';
include 'main_js.php';
include 'chrtdashboard.php';
include 'sub_graph_modal.php';
?>

<head>
  <link rel="stylesheet" href="adminpanel.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Orbitron:wght@600;800&display=swap" rel="stylesheet">
</head>

<style>
  :root {
    --primary: #E1AD01;
    --primary-hover: #c99a00;
    --dark-blue: #1e2d4a;
    --light-bg: #f8fafc;
    --border-color: #e2e8f0;
    --text-main: #1e293b;
    --text-muted: #64748b;
    --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    --font-sans: 'Inter', sans-serif;
  }

  body {
    font-family: var(--font-sans);
     background: linear-gradient(to bottom, #ffffff, #99aac8);
    background-attachment: fixed;
    margin: 0;
    color: var(--text-main);
    min-height: 100vh;
  }


  .modern-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.6);
    border-radius: 16px;
    box-shadow: var(--card-shadow);
    padding: 24px;
    transition: all 0.3s ease;
    margin-top: 60px;
  }

  .section-title {
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
  }

  .form-group label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #213456;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
    display: block;
  }

  .modern-input {
    width: 100%;
    height: 44px;
    padding: 10px 14px;
    background: #ffffff !important;
    border: 1px solid var(--border-color) !important;
    border-radius: 8px !important;
    color: var(--text-main) !important;
    font-size: 0.9rem;
    transition: all 0.2s ease;
  }

  .modern-input:focus {
    outline: none !important;
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 3px rgba(225, 173, 1, 0.15) !important;
  }

  textarea.modern-input {
    height: 120px !important;
    resize: none !important;
  }

  /* Elegant Scrollbars */
  ::-webkit-scrollbar {
    width: 6px;
    height: 6px;
  }
  ::-webkit-scrollbar-track {
    background: transparent;
  }
  ::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
  }
  ::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }

  .table-responsive-xl {
    border-radius: 12px;
    overflow: hidden;
    background: #ffffff;
    border: 1px solid var(--border-color);
  }

  table.dataTable {
    border-collapse: collapse !important;
    margin: 0 !important;
    width: 100% !important;
  }

  table.dataTable thead th {
    background: #213456 !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    font-size: 0.8rem !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 14px 16px !important;
    border: none !important;
  }

  table.dataTable tbody tr {
    background: #ffffff !important;
    transition: background 0.2s ease;
  }

  table.dataTable tbody tr:hover {
    background: #6c89ba !important;
  }

  table.dataTable tbody td {
    padding: 14px 16px !important;
    font-size: 0.85rem !important;
    color: var(--text-main) !important;
    border-bottom: 1px solid var(--border-color) !important;
  }

  .dataTables_wrapper .dataTables_filter input {
    border: 1px solid var(--border-color);
    border-radius: 6px;
    padding: 5px 10px;
    margin-left: 8px;
    outline: none;
  }
  .dataTables_wrapper .dataTables_filter input:focus {
    border-color: var(--primary);
  }

  .modal-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    justify-content: center;
    align-items: center;
    z-index: 1050;
  }

  .modal-overlay .modal-content {
    background: #ffffff;
    padding: 30px;
    border-radius: 16px;
    width: 90%;
    max-width: 700px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    border: none;
  }

  
</style>

<div class="container-fluid py-4">
  <div class="row">
    
    <div class="col-xl-5 col-lg-6 mb-4">
      <div class="modern-card">
        <form method="post" id="create_report_form" enctype="multipart/form-data">
          <h6 class="section-title" style="color: var(--dark-blue);">
            <i class="fas fa-ticket-alt" style="color: var(--primary);"></i> Ticket Details
          </h6>
          
          <div class="row">
            <div class="form-group col-md-6 mb-3">
              <label><i class="fas fa-store mr-1"></i> Store / Branch</label>
              <select class="modern-input" name="store" id="create_store" required style="pointer-events: none; background-color: #f1f5f9 !important;">
                <?php
                $query = "SELECT * FROM tbl_branch";
                $run = $conn->prepare($query);
                $run->execute();
                $rs = $run->get_result();
                while ($res = $rs->fetch_assoc()) {
                  $brcnhid = $res['str_num'];
                  $brnchcd = $res['str_code'] . ' | ' . $res['str_name'];
                  $selected = ($brcnhid == '201') ? 'selected' : '';
                  echo "<option value='{$brcnhid}' {$selected}>{$brnchcd}</option>";
                } 
                ?>
              </select>
            </div>

            <div class="form-group col-md-6 mb-3">
              <label><i class="fas fa-building mr-1"></i> Department</label>
              <select class="modern-input" name="deptsel" id="create_deptsel" required>
                <option value="" selected disabled>Select Here</option>
                <option value="1">IT</option>
                <option value="2">ADMIN</option>
                <option value="3">MARKETING</option>
                <option value="6">VISUAL</option>
                <option value="11">H.R</option>
                <option value="13">ACCOUNTS PAYABLE</option>
                <option value="15">TREASURY</option>
                <option value="16">ACCOUNT RECEIVABLE</option>
              </select>
            </div>

            <div class="form-group col-md-6 mb-3">
              <label><i class="fas fa-envelope mr-1"></i> Subject (Category)</label>
              <select class="modern-input" id="create_subject" name="subject" required>
                <option value="" selected disabled>---Select Category---</option>
              </select>
            </div>

            <div class="form-group col-md-6 mb-3" id="create_sub_group" style="display: none;">
              <label><i class="fas fa-tag mr-1"></i> Subcategory</label>
              <select class="modern-input" id="create_sub" name="sub">
                <option value="" selected disabled>---Select Subcategory---</option>
              </select>
            </div>

            <div class="form-group col-12 mb-3">
              <label><i class="fas fa-comment-alt mr-1"></i> Concern (Message)</label>
              <textarea class="modern-input" id="create_concern" name="concern" minlength="10" maxlength="1000" placeholder="Describe the concern or request in detail..." required></textarea>
            </div>

            <div class="form-group col-12 mb-4">
              <label><i class="fas fa-paperclip mr-1"></i> Attach Files (Optional)</label>
              <input id="create_file-input" type="file" name="file" multiple class="form-control-file d-block mb-1">
              <small class="text-muted d-block">Max file size: 2MB. Allowed types: Images, PDF, Docs, Excel.</small>
            </div>
          </div>

          <div class="d-flex align-items-center justify-content-between pt-3 border-top">
            <div>
              <span class="badge badge-info p-2 font-weight-bold text-uppercase" style="border-radius: 6px; background-color: #0ea5e9;">
                Ticket: <span id="create_ticket_lbl">---</span>
              </span>
              <input type="hidden" name="ticket_no" id="create_ticket_no">
            </div>
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-light px-3 font-weight-bold" data-dismiss="modal" style="border-radius: 8px;">Cancel</button>
              <button type="submit" name="action" id="create_action" class="btn text-white px-4 font-weight-bold" style="border-radius: 8px; background: var(--dark-blue);">
                <i class="fas fa-save mr-2"></i>Save Ticket
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="col-xl-7 col-lg-6 mb-4">
      <div class="modern-card h-100 d-flex flex-column">
        <h6 class="section-title" style="color: var(--primary);">
          <i class="fas fa-list-ul"></i> Created Tickets Queue
        </h6>
        <div class="table-responsive-xl flex-grow-1">
          <table class="table table-hover" id="create_dept_table"></table>
        </div>
      </div>
    </div>

  </div>
</div>


  <!-- =========================
Start of Add/Edit Modal
========================= -->
  <div class="col-12 col-lg-12 modal fade" id="userModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 100%;">
      <form method="post" id="report_form" enctype="multipart/form-data">
        <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title" id="userModal_header" value="Add Report"></h4>
          </div>

          <div class="modal-body">
            <div class="row">

              <!-- LEFT SIDE -->
              <div class="m_col col-12 col-lg-6">

                <div class="row">

                  <div class="form-group col-12 col-md-6">
                    <label>STORE</label>
                    <input type="hidden" name="str_num" id="str_num" readonly value="">
                    <select class="form-control form-control-sm" name="store" id="store" required>
                      <option value="">Select Store...</option>
                      <?php
                      $query = "select * from tbl_branch ";
                      $run = $conn->prepare($query);
                      $run->execute();
                      $rs = $run->get_result();
                      while ($res = $rs->fetch_assoc()) {
                        $brcnhid = $res['str_num'];
                        $brnchcd = $res['str_code'] . ' | ' . $res['str_name'];
                        ?>
                        <option value="<?php echo $brcnhid; ?>"><?= $brnchcd; ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <input type="hidden" class="form-control form-control-sm" name="ticket_no" id="ticket_no">




                  <div class="form-group col-4">
                    <label>SUBJECT/CONCERN</label>
                    <input type="text" name="subjct" id="subjct" class="form-control form-control-sm" placeholder="Input Concern"
                      style="text-transform:uppercase" onkeyup="this.value = this.value;"></input>
                  </div>

                  <div class="form-group col-12 col-md-4">
                    <label>VIA</label>
                    <select class="form-control form-control-sm" name="via" id="via" required>
                      <option value=""> &larr; VIA &rarr;</option>
                      <?php
                      $query = "select * from via_main";
                      $run = $conn->prepare($query);
                      $run->execute();
                      $rs = $run->get_result();
                      while ($res = $rs->fetch_assoc()) {
                        ?>
                        <option><?= $res['via_desc'] ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="form-group col-12 col-md-8">
                    <label>I.T SUPPORT</label>
                    <input type="hidden" name="it_num" id="it_num" readonly>
                    <select class="form-control form-control-sm" name="itsup" id="itsup" required>
                      <option value="">Assign support...</option>
                      <?php
                      $query = "select * from it_tech WHERE deptsel = '1' AND itsup NOT IN ('4','8','12','14')";
                      $run = $conn->prepare($query);
                      $run->execute();
                      $rs = $run->get_result();
                      while ($res = $rs->fetch_assoc()) {
                        $tchid = $res['itsup'];
                        $tchdesc = $res['it_desc'];
                        ?>
                        <option value="<?php echo $tchid; ?>"><?= $tchdesc; ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="form-group col-12 col-md-4">
                    <label>CATEGORY</label>
                    <input type="hidden" name="cat_num" id="cat_num" readonly>
                    <select class="form-control form-control-sm" name="cat" id="cat" required>
                      <option value=""> &larr; CATEGORY &rarr;</option>
                      <?php
                      // $query="select * from category WHERE deptsel = '1'";
                      $query = "select * from categories WHERE deptsel = '1' AND old_tag IS NULL";
                      $run = $conn->prepare($query);
                      $run->execute();
                      $rs = $run->get_result();
                      while ($res = $rs->fetch_assoc()) {
                        $supid = $res['cat_id'];
                        $suppdesc = $res['cat_desc'];
                        ?>
                        <option value="<?php echo $supid; ?>"><?= $suppdesc; ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="form-group col-12 col-md-4">
                    <label>SUB CATEGORY</label>
                    <input type="hidden" name="sub_num" id="sub_num" readonly>
                    <select class="form-control form-control-sm" name="sub" id="sub"></select>
                  </div>
                  <div class="form-group col-12 col-md-4">
                    <label style="font-weight: bold;">TRANSFER REQUEST</label>
                    <div>
                      <input type="checkbox" name="is_transfer" id="is_transfer" value="1">
                      <label for="is_transfer"> Mark as Transfer Request</label>
                    </div>
                  </div>
                  <div class="form-group col-12 col-md-4 hide_isp">
                    <label for="isp" id="lbl_isp">Service Provider</label>
                    <input type="hidden" name="isp_num" id="isp_num" readonly>
                    <select class="form-control form-control-sm" name="isp" id="isp">
                      <option value="">Select Network Provider</option>
                      <?php
                      $query = "select * from tbl_isp";
                      $run = $conn->prepare($query);
                      $run->execute();
                      $rs = $run->get_result();
                      while ($res = $rs->fetch_assoc()) {
                        $ispid = $res['isp_id'];
                        $ispdesc = $res['isp_shortDesc'];
                        ?>
                        <option value="<?php echo $ispid; ?>"><?= $ispdesc; ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="form-group col-12 col-md-4 hide_isp">
                    <label id="lbl_refNo" for="refNo">Reference No:</label>
                    <input type="text" class="form-control form-control-sm" name="refNo" id="refNo">
                  </div>

                  <div class="form-group col-12 col-md-4 hide_isp">
                    <label for="date_refNo" class="text" id="lbl_DtRefNo">Date of RefNo</label>
                    <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                      <input type="text" name="date_refNo" id="date_refNo"
                        class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker3" />
                      <div class="input-group-append" data-target="#date_created" data-toggle="datetimepicker">
                        <input type="hidden" class="form-control form-control-sm" name="date_createdx"
                          id="date_createdx">
                        <div class="input-group-text" id="ico_cal3"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group col-12 col-md-4">
                    <label>STATUS</label>
                    <select class="form-control form-control-sm" name="status" id="status" required>
                      <option value=""> &larr; Status &rarr;</option>
                      <?php
                      $query = "select * from status  WHERE it_module_tag = 'Y'";
                      $run = $conn->prepare($query);
                      $run->execute();
                      $rs = $run->get_result();
                      while ($res = $rs->fetch_assoc()) {
                        ?>
                                   <option value="<?=$res['stat_desc'] ?>" style="color: #333;"><?=$res['stat_desc'] ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="form-group col-12 col-md-4 hide_cl">
                    <label id="dateclabel" class="hidden">DATE CLOSED</label>
                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                      <input type="text" name="date_closed" id="date_closed"
                        class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker2"
                        autocomplete="off" />
                      <div class="input-group-append" data-target="#date_closed" autocomplete="off"
                        data-toggle="datetimepicker">
                        <div class="input-group-text" id="ico_cal" name="ico_cal"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group col-12 col-md-4 hide_cl">
                    <label id="clby_label" class="hidden">CLOSED BY</label>
                    <input type="hidden" name="close_by" id="close_by" value="<?php echo $_SESSION['tech_id']; ?>">
                    <input type="text" class="form-control form-control-sm" name="cl_desc" id="cl_desc" readonly
                      value="<?php echo $_SESSION['fname'] . '  ' . $_SESSION['lstname']; ?>">
                  </div>

                  <div class="form-group col-12">
                    <label>Work Output:</label>
                    <textarea name="remarks" id="remarks" class="form-control form-control-sm"
                      placeholder="Your Workoutput"></textarea>
                  </div>

                  <div class="col-12">
                    <label style="font-weight: bold;">Attached File:</label>
                    <p><input id="file-input" type="file" name="file" Multiple></p>
                  </div>

                  <div class="col-12">
                    <hr />
                  </div>

                  <div class="col-12 d-flex justify-content-between align-items-center">
                    <input type="submit" name="action" id="action" class="btn btn-success" value="Add">
                    <button type="button" name="btnClose" id="btnClose" class="btn btn-danger"
                      data-dismiss="modal">Close</button>
                  </div>

                  <div class="col-12">
                    <hr />
                  </div>

                  <div class="card" id="img" name="img"></div>

                </div><!-- /.row -->

              </div><!-- /.left -->

              <!-- RIGHT SIDE -->
              <div class="col-12 col-lg-6">

                <div id="msg_thread">

                  <div class="col-12 mb-3 px-0">
                    <label style="font-weight: bold; color:white;">Add Comment:</label>
                    <textarea name="admsg" id="addmsg" class="form-control form-control-sm"
                      placeholder="Reply to their message or give an updates regarding on this ticket..."
                      required></textarea>
                  </div>

                  <div class="col-12 mt-4 mb-2 dv_msg px-0">
                    <label for="remarks_view" style="font-weight: bold; color:white;">Comment Thread:</label>
                    <hr>
                    <div class="container_remarks">
                      <div id="remarks_view"></div>
                    </div>
                  </div>

                </div><!-- /#msg_thread -->

              </div><!-- /.right -->

            </div><!-- /.row -->
          </div><!-- /.modal-body -->

          <div class="modal-footer">
            <input type="hidden" name="operation" id="operation" value="Add">
            <input type="hidden" name="u_id" id="u_id" value="<?php echo $_SESSION['user_id']; ?>">
          </div>

        </div><!-- /.modal-content -->
      </form>
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->


  <script>
    $(document).ready(function () {
      $('.dashcard-clickable').on('click', function () {
        const filterValue = $(this).data('filter');

        if ($.fn.DataTable.isDataTable('#report_data')) {
          const table = $('#report_data').DataTable();
          table.search(filterValue).draw();
        }

        $('html, body').animate({
          scrollTop: $("#report_data").offset().top - 100
        }, 600);

        $(this).fadeOut(100).fadeIn(100);
      });

      function resetCreateTicketForm() {
        $('#create_report_form').trigger('reset');
        $('#create_store').val('201');
        $('#create_subject').empty().append('<option value="" selected disabled>---Select Category---</option>');
        $('#create_sub').empty().append('<option value="" selected disabled>---Select Subcategory---</option>');
        $('#create_sub_group').hide();
        $('#create_ticket_lbl').text('---');
        $('#create_ticket_no').val('');
      }

      resetCreateTicketForm();
      $("#create_deptsel").on("change", function () {
        $('#create_subject').empty().append('<option value="" selected disabled>Loading categories...</option>');
        $('#create_sub').empty().append('<option value="" selected disabled>---Select Subcategory---</option>');
        $('#create_sub_group').hide();
        let val = $(this).val();

        $.ajax({
          url: "../users/select.php",
          type: "GET",
          dataType: 'json',
          cache: true,
          data: {
            type: 'category_id',
            val: val
          },
          success: function (response) {
            $('#create_subject').empty().append('<option value="" selected disabled>---Select Category---</option>');
            if (Array.isArray(response) && response.length > 0) {
              response.forEach(function (item) {
                if (item && item.id !== undefined && item.text !== undefined) {
                  $('#create_subject').append($('<option>', { value: item.id, text: item.text }));
                }
              });
            } else {
              $('#create_subject').append($('<option>', { value: '', text: 'No categories available' }));
            }
          },
          error: function () {
            $('#create_subject').empty().append('<option value="" selected disabled>Error loading categories</option>');
          }
        });

        $.ajax({
          url: '../users/fetch.php',
          method: 'POST',
          dataType: 'json',
          data: { operation: 'search_tkt', iN: val },
          success: function (data) {
            if (data && data[0]) {
              let next_tktno = data[0].ticket_no;
              let deptabr = data[0].dept;
              let ticketNo = deptabr + '' + next_tktno;
              $('#create_ticket_no').val(ticketNo);
              $('#create_ticket_lbl').text(ticketNo);
            } else {
              $('#create_ticket_lbl').text('Ticket generation failed');
            }
          },
          error: function () {
            $('#create_ticket_lbl').text('Ticket generation failed');
          }
        });
      });

      $("#create_subject").on("change", function () {
        let category_id = $(this).val();
        if (!category_id) {
          $('#create_sub_group').hide();
          return;
        }

        $.ajax({
          url: "get_subcat.php",
          type: "POST",
          data: { category_id: category_id },
          cache: false,
          success: function (dataResult) {
            $("#create_sub").html(dataResult);
            $('#create_sub_group').show();
          },
          error: function () {
            $("#create_sub").html('<option value="">Error loading subcategories</option>');
            $('#create_sub_group').show();
          }
        });
      });

      $('#create_file-input').on('change', function () {
        for (var i = 0; i < this.files.length; ++i) {
          var file = this.files[i];
          if (file.size > 2097152) { // 2MB
            Swal.fire({
              icon: 'error',
              title: 'File Too Large',
              text: 'File "' + file.name + '" must not exceed 2MB.'
            });
            this.value = "";
            return false;
          }
          var ext = file.name.split('.').pop().toLowerCase();
          var validExtensions = ['jpg', 'jpeg', 'gif', 'png', 'txt', 'pdf', 'docx', 'doc', 'xlsx', 'xls'];
          if ($.inArray(ext, validExtensions) === -1) {
            Swal.fire({
              icon: 'error',
              title: 'Invalid File Type',
              text: 'File "' + file.name + '" has an invalid extension.'
            });
            this.value = "";
            return false;
          }
        }
      });

      $('#create_report_form').on('submit', function (e) {
        e.preventDefault();

        var form = this;
        var formData = new FormData(form);

        $.ajax({
          url: "api_create_dept_report.php",
          method: "POST",
          data: formData,
          contentType: false,
          processData: false,
          beforeSend: function () {
            $('#create_action').prop('disabled', true);
            $.LoadingOverlay("show", {
              image: "",
              background: "rgba(0, 0, 0, 0.45)"
            });
          },
          success: function (response) {
            $.LoadingOverlay("hide");
            $('#create_action').prop('disabled', false);

            if (response.Response) {
              var files = $('#create_file-input')[0].files;
              if (files.length > 0) {
                var fileData = new FormData();
                for (var i = 0; i < files.length; i++) {
                  fileData.append('files[]', files[i]);
                }
                fileData.append('ticket_no', response.ticket_no);

                $.ajax({
                  type: "POST",
                  url: "insertimg.php",
                  data: fileData,
                  processData: false,
                  contentType: false
                });
              }

              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Report successfully submitted to OWI HELPDESK.',
                timer: 2000,
                showConfirmButton: false
              }).then(function () {
                resetCreateTicketForm();
                getdata();
                if (typeof getdata === 'function') {
                  const currentYr = $("#yearpicker").val();
                  getdata(currentYr);
                }
                if (typeof get_card_data === 'function') {
                  const currentYr = $("#yearpicker").val();
                  get_card_data(currentYr);
                }
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                html: response.m
              });
            }
          },
          error: function () {
            $.LoadingOverlay("hide");
            $('#create_action').prop('disabled', false);
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'An unexpected error occurred while saving the report.'
            });
          }
        });
      });
    });



  </script>

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
  }


  $("div.selected select").val("OPEN");

  var reptable;
  var user_id = <?= $_SESSION['user_id']; ?>; 

  function getdata(){
    $.post('fetchdata/fetch_data.php',{mode:'dept_tbl'},function(data){
      admin_datatable(data);
    },'json');
  }
  getdata();
function admin_datatable(t){
  const dataset = t.deptdata;
  
  // If the table instance already exists, clear it and add the new dataset row vectors smoothly
  if ($.fn.DataTable.isDataTable('#create_dept_table')) {
    var table = $('#create_dept_table').DataTable();
    table.clear().rows.add(dataset).draw(false); // "false" preserves current page/pagination state
    return;
  }

  // Initial table render configuration setup if it doesn't exist yet
  reptable = $("#create_dept_table").DataTable({
    "dom": '<"pull-left"lf><"pull-right">tip',
    stateSave: true,
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
      {title:"SUBJECT", data:"subject","defaultContent": ""},
      {title:"Types of Service", data:"service_desc","defaultContent": ""},
      {title:"Concern", data:"concern","defaultContent": ""},
      {title:"Date Created", data:"date_created","defaultContent": ""},
      {title:"Status", data:"status","defaultContent": ""},
      {title:"Update", data:null,"defaultContent": "<Button class='btn btn-danger' name='update'><i class='fas fa-edit'></i></Button>"}
    ],
    rowCallback: function(row, data, index){
      if(data['msg_cnt'] == '1'){
        $(row).find('td').css("font-weight", "bold");
      }
    }
  });

  // Event listener attachment layout configuration
  $('#create_dept_table tbody').off('click', 'button').on('click', 'button', function () {
    var data = reptable.row($(this).parents('tr')).data();
    if (!data) return;
    
    // Data assignment logic blocks to userModal elements...
    $('#ticket_no').val(data['ticket_no']);
    $('#store').val(data['store']);
    $('#subjct').val(data['subject']);
    if (data['via']) $('#via').val(data['via']);
    if (data['itsup']) $('#itsup').val(data['itsup']);
    if (data['status']) $('#status').val(data['status']);
    if (data['cat_id']) $('#cat').val(data['cat_id']).trigger('change');
    $('#remarks').val(data['concern']); 
    $('#action').val("Update");
    $('#operation').val("Save and Reply"); 

    var tid = data['ticket_no'];
    $('#userModal_header').text("Ticket Number: " + tid);
    $('#userModal').modal('show');
    
    displayAttachmentsFromData(data);
    getinfo(tid, 'remarks', user_id);
  });
}

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
          try {
            response = JSON.parse(response);
          } catch (e) {
            response = { status: 'error', message: String(response) };
          }
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
        Swal.fire({
          icon: 'error',
          title: 'Save failed',
          text: message
        });
      }
    });
  });
});

$(document).on('click', '#msgbtn', function(){
  $('.dv_msg').show();
  $('#remarks_view').show();

  if($('#msgbtn').val() == 'show'){
    $('#action').val("Save and Reply");
    $('#operation').val("Save and Reply");
    $('#msgbtn').val("hide");
    $('#msg_thread').show('slow');
  } else if($('#msgbtn').val() == 'hide'){
    $('#action').val("Save");
    $('#operation').val("Save and Reply");
    $('#msgbtn').val("show");
    $('#msg_thread').hide('slow');
  }
});


function displayAttachmentsFromData(data) {
    const container = document.getElementById('attachments-container');
    if (!container) {
        console.warn('⚠️ attachments-container element not found in modal');
        return;
    }
    
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
        
        if (!fullPath.includes('users/image/')) {
            fullPath = 'users/image/' + fullPath;
        }
      
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
