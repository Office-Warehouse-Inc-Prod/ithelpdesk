<?php
include 'switch_attach_modal.php';

?>
<head>
  <link rel="stylesheet" href="adminpanel.css">
</head>
<style>


.card-no-border .card {
    border: 0px;
    border-radius: 4px;
    -webkit-box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.05);
    box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.05)
}

.card-body {
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 1.25rem
}


.round img {
    border-radius: 100%
}

.label-info {
    background-color: #1976d2
}

.label-success {
    background-color: green
}

.label-danger {
    background-color: #ef5350
}

.action-icons a {
    padding-left: 7px;
    vertical-align: middle;
    color: #99abb4
}

.action-icons a:hover {
    color: #1976d2
}

.mt-100 {
    margin-top: 100px
}

.mb-100 {
    margin-bottom: 100px
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
::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(135deg, #837031, #E1AD01);
}

#ticket_modal .modal-dialog{
  max-width: 1100px; /* desktop width */
  margin: 1.25rem auto;
}

#ticket_modal .modal-content{
  border-radius: 16px;
  border: 1px solid rgba(0,0,0,0.08);
  overflow: hidden;
}

/* Header with hierarchy */
#ticket_modal .modal-header{
  background: linear-gradient(180deg, rgba(79,70,229,0.08), rgba(255,255,255,0));
  border-bottom: 1px solid rgba(0,0,0,0.08);
  padding: 16px 18px;
}

#ticket_modal_header{
  font-weight: 700;
  font-size: 18px;
  margin: 0;
}

/* Body spacing */
#ticket_modal .modal-body{
  padding: 16px 18px;
}

/* Section cards inside modal */
.modal-section{
  background: rgba(255,255,255,0.75);
  border: 1px solid rgba(0,0,0,0.08);
  border-radius: 14px;
  padding: 14px;
  box-shadow: 0 8px 22px rgba(0,0,0,0.04);
}

/* Section title */
.modal-section-title{
  font-size: 13px;
  font-weight: 800;
  letter-spacing: .6px;
  text-transform: uppercase;
  color: rgba(0,0,0,0.55);
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 10px;
}

.modal-section-title:before{
  content: "";
  width: 10px;
  height: 10px;
  border-radius: 3px;
  background: rgba(79,70,229,0.55);
}

/* Make inputs feel “premium” */
#ticket_modal .form-control,
#ticket_modal select,
#ticket_modal textarea{
  border-radius: 10px;
  border: 1px solid rgba(0,0,0,0.12);
  background: white;
}

#ticket_modal label{
  font-weight: 700;
  font-size: 12px;
  letter-spacing: .4px;
  text-transform: uppercase;
  color: #213456;
}

/* Comment thread container: scrollable, not endless */
.container_remarks{
  max-height: 480px;
  overflow: auto;
  padding-right: 6px;
}

/* Thread message card look (works with your existing markup) */
#remarks_view .msg-item{
  border: 1px solid rgba(0,0,0,0.08);
  background: rgba(255,255,255,0.90);
  border-radius: 14px;
  padding: 12px 12px;
  margin-bottom: 10px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.04);
}

#remarks_view .msg-head{
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 6px;
}

#remarks_view .msg-name{
  font-weight: 800;
  font-size: 14px;
}

#remarks_view .msg-meta{
  font-size: 12px;
  color: rgba(0,0,0,0.55);
}

#remarks_view .msg-body{
  font-size: 13px;
  color: rgba(0,0,0,0.80);
  line-height: 1.35;
  white-space: pre-wrap;
}

/* Sticky footer actions (great on mobile) */
#ticket_modal .modal-footer{
  border-top: 1px solid rgba(0,0,0,0.08);
  background: rgba(255,255,255,0.92);
  position: sticky;
  bottom: 0;
  z-index: 5;
  padding: 12px 14px;
}


label {
  font-size: 11px;
  font-weight: 900;
  color: #e1ad01; 
  letter-spacing: .08em;
  text-transform: uppercase;
  margin-bottom: 6px;
}

input.form-control,
textarea.form-control {
  color: #6c757d !important;
  background-color: #fcfcfc; !important; 
  border: black !important; 
  border-bottom: 1px solid #E1AD01 !important; 
  resize: none !important; 
  margin-bottom: -10px;
}

select.custom-select-placeholder.placeholder-active,
textarea.form-control.custom-select-placeholder:placeholder-shown {
  color: red !important;
  background-color: #fcfcfc; !important;
}

textarea.form-control.custom-select-placeholder::placeholder {
  color: red !important;
  opacity: 0.7;
}

select.custom-select-placeholder.has-value,
textarea.form-control.custom-select-placeholder:not(:placeholder-shown) {
  color: #0a0a0a !important; 
  border-bottom: 1px solid #E1AD01 !important; 
  background-color: #fcfcfc; !important;
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

</style>
<div class="modal fade" id="ticket_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content"
          style="border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);">
       <div class="modal-header"
            style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; border-top-left-radius: 15px; border-top-right-radius: 15px;">
            <h5 class="modal-title font-weight-bold" id="createReportModalLabel"><i class="fa fa-ticket" aria-hidden="true"></i>  Ticket Details</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
              style="opacity: 0.8; outline: none; background: none; border: none;">
              <span aria-hidden="true" style="font-size: 28px;">&times;</span>
            </button>
          </div>
      
    <div class="modal-body" style="padding: 30px; background-color: #fcfcfc;">
        <form method="post" id="modal_form" enctype="multipart/form-data">
          <div class="row">
            
            <div class="col-12 col-lg-6">
              <div class="form-group mb-4">
                <label>TICKET#</label>
                <input type="text" class="form-control" name="ModalTicket_no" id="ModalTicket_no" readonly  required onchange="handleDropdownChange(this)">
              </div>

              <div class="form-group mb-12">
                <label>DATE CREATED</label>
                <input type="text" class="form-control" name="ModalDate_create" id="ModalDate_create" readonly  required onchange="handleDropdownChange(this)">
              </div>

              <div class="form-group mb-4">
                <label>STORE</label>
                <input type="text" class="form-control" name="ModalStore" id="ModalStore" readonly onchange="handleDropdownChange(this)">
              </div>

              <div class="form-group mb-4">
                <label>SUBJECT</label>
                <input type="text" class="form-control" name="ModalSubject" id="ModalSubject" readonly onchange="handleDropdownChange(this)">
              </div>

              <div class="form-group mb-4">
                <label>STATUS</label>
                <input type="text" class="form-control" name="ModalStatus" id="ModalStatus" readonly onchange="handleDropdownChange(this)">
              </div>

              <div class="form-group">
                <label><strong>Attachments</strong></label>
                <div id="attached_files" class="form-control" style="min-height:100px; background:#f8f9fa; overflow:auto;"></div>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-2">
               
                <div>
                  <a href="#" id="rars" class="mr-2">RARS FORM</a>
                  <a href="#" id="vwfile">VIEW ATTACHMENTS</a>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-6">
              

              <div id="msg_thread">
    <div class="d-flex align-items-start">
        <textarea class="form-control" id="Modal_reply" name="Modal_reply" style="height: 150px; resize: vertical;" placeholder="Leave a comment..."></textarea>
        
        <button type="submit" class="btn btn-primary ms-2" name="Modal_action" id="Modal_action">
            <i class="fa fa-paper-plane" aria-hidden="true"></i>
        </button>
    </div>

    <input type="hidden" name="Modal_uId" id="Modal_uId" value="<?php echo $_SESSION['user_id'];?>">
    <input type="hidden" name="operation" id="operation" value="Addcomment">
    
    <div id="alrtmsg" class="mt-2"></div>
    
    <div class="col-12 mt-4 mb-2 dv_msg px-0">
        <label for="remarks_view" style="font-weight: bold; color:white;">Comment Thread:</label>
        <hr>
        <div class="container_remarks">
            <div id="remarks_view"></div>
        </div>
    </div>
</div>
            </div>

          </div></form>
      </div></div>
  </div>
</div>
       




                 <!--  </div> -->
        <!-- </div> -->


<!-- 
               <fieldset>
                <legend><h6>Comment Thread</h6></legend> -->

<!-- alejo -->

 

<!-- alejo -->


   
               <!-- </fieldset> -->
          
              </form>




          </div>
          </div>
          </div>
		  </div>
 <script type="text/javascript">

/**
 * Load attachments.
 */
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
  
  // Since get-pdf.php is in the users folder
  var pdfUrl = '/ithelpdesk/users/rarspdf.php?ticket=' + encodeURIComponent(PDtkt);
  
  window.open(pdfUrl, '_blank');
});


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

