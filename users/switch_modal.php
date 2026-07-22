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
  max-width: 1800px; /* desktop width */
  margin: 1.25rem auto;
}

#ticket_modal .modal-content{
  border-radius: 16px;
  border: 1px solid rgba(0,0,0,0.08);
  overflow: hidden;
}

/* Header with hierarchy */
#ticket_modal .modal-header{
 background: linear-gradient(135deg, #213456, #334c7a);
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
  background: linear-gradient(135deg, #213456, #334c7a);
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

#ticket_modal .modal-dialog{
  max-width: 1100px; 
  margin: 1.25rem auto;
}

#ticket_modal .modal-content{
  border-radius: 16px;
  border: none;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
  overflow: hidden;
}

#ticket_modal .modal-header{
    background-color: linear-gradient(135deg, #213456, #334c7a);
    color: #fff;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    border-bottom: 4px solid #E1AD01; 
}

#ticket_modal_header{
  font-weight: 700;
  font-size: 18px;
  margin: 0;
}

#ticket_modal .modal-body{
  padding: 16px 18px;
}

#ticket_modal .modal-title {
    font-weight: 700;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
}

#ticket_modal .input-group-text {
    background-color: white;
    border-right: none;
    color: linear-gradient(135deg, #213456, #334c7a);
}

#ticket_modal .form-control {
    border-left: none;
    height: 45px;
    border-radius: 0 8px 8px 0;
}

#ticket_modal .form-control:focus {
    border-color: linear-gradient(135deg, #213456, #334c7a);
    box-shadow: none;
}

#ticket_modal .input-group:focus-within {
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
    color: #ffffff; 
}

.chat-left .msg-meta-name {
    color: #213456;
    font-weight: bold;
}

.chat-right .msg-meta-name {
    color: #ffffff;
    font-weight: bold;
}


.chat-right .msg-time {
    color: #ffffff !important; 
}

.chat-left .msg-time {
    color: #64748b !important;
}
.chat-left .msg-meta {
    color: #64748b;
}

.chat-right .msg-meta {
    color: rgba(255, 255, 255, 0.85);
}

.chat-left .msg-meta-name {
    color: linear-gradient(135deg, #213456, #334c7a);
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

#ticket_modal .modal-footer{
  border-top: 1px solid rgba(0,0,0,0.08);
  background: rgba(255,255,255,0.92);
  position: sticky;
  bottom: 0;
  z-index: 5;
  padding: 12px 14px;
}

@media (max-width: 991px){
  #ticket_modal .modal-dialog{
    max-width: 96%;
    margin: .75rem auto;
  }

  .container_remarks{
    max-height: 350px;
  }

  #action, #btnClose{
    width: 100%;
  }
}

</style>
<div class="modal fade" id="ticket_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
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
               <div class="row">
              <div class="form-group col-md-6">
                <label>TICKET#</label>
                <input type="text" class="form-control" name="ModalTicket_no" id="ModalTicket_no" readonly  required onchange="handleDropdownChange(this)">
              </div>

              <div class="form-group col-md-6">
                <label>DATE CREATED</label>
                <input type="text" class="form-control" name="ModalDate_create" id="ModalDate_create" readonly  required onchange="handleDropdownChange(this)">
              </div>

              <div class="form-group col-md-6">
                <label>STORE</label>
                <input type="text" class="form-control" name="ModalStore" id="ModalStore" readonly onchange="handleDropdownChange(this)">
              </div>

              <div class="form-group col-md-6">
                <label>SUBJECT</label>
                <input type="text" class="form-control" name="ModalSubject" id="ModalSubject" readonly onchange="handleDropdownChange(this)">
              </div>

              <div class="form-group col-md-6">
                <label>STATUS</label>
                <input type="text" class="form-control" name="ModalStatus" id="ModalStatus" readonly onchange="handleDropdownChange(this)">
              </div>

               <div class="form-group col-md-12">
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
                    <label for="remarks_view" style="font-weight: bold; color:linear-gradient(135deg, #213456, #334c7a);">Comment Thread:</label>
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
                        let relativeTime = timeAgo(comment.comment_date);
                        let replyTimeColor = isMe ? "color: #e2e8f0;" : "color: #64748b;";
                        
                        html += `
                            <div class="chat-bubble ${bubbleClass}" style="animation-delay: ${delay}s;">
                                <div class="msg-meta">
                                    <span class="msg-meta-name">${sender}</span>
                                    <span class="msg-time">${comment.comment_date}</span> 
                                </div>
                                <div style="white-space: pre-wrap;">${comment.comment_details}</div>
                                
                                <div class="reply-time" style="font-size: 0.65rem; text-align: right; margin-top: 6px; opacity: 0.85; font-style: italic; ${replyTimeColor}">
                                    Replied ${relativeTime}
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="text-center text-muted mt-3" style="font-size:13px;"><i class="fas fa-comments mb-2" style="font-size:24px; opacity:0.5;"></i><br>No comments yet. Start the conversation!</div>';
                }
                
               $remarksView.fadeOut(150, function() {
                    $remarksView.html(html).fadeIn(300);
                    $('.dv_msg, .container_remarks').slideDown(300); 

                    setTimeout(() => {
                        const $container = $('.container_remarks');
                        if ($container.length) {
                            $container.animate({ scrollTop: 0 }, 600, 'swing');
                        }
                    }, 200);
                });
            },
            error: function(xhr) {
                $remarksView.html('<div class="text-danger text-center mt-3">Error loading comments.</div>');
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
  
  // Since get-pdf.php is in the users folder
  var pdfUrl = '/ithelpdesk/users/rarspdf.php?ticket=' + encodeURIComponent(PDtkt);
  
  window.open(pdfUrl, '_blank');
   
   $('#ticket_modal').on('shown.bs.modal', function () {
        const ticketNo = $('#ticket_no').val();
        if (ticketNo) {
            $('.dv_msg').show();
            $('.container_remarks').show();
            loadCommentThread(ticketNo);
        }
    });

    $(document).on('hidden.bs.modal', '#ticket_modal', function () {
      $('.temp-option').remove();
    });
  
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

