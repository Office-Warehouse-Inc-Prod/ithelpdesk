<?php
include 'switch_attach_modal.php';

?>

<style>


.uModaltxtarea{
  display: flex;
}
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

.comment-widgets .comment-row:hover {
    background: rgba(0, 0, 0, 0.02);
    cursor: pointer
}

.comment-widgets .comment-row {
    border-bottom: 1px solid rgba(120, 130, 140, 0.13);
    padding: 15px
}

.comment-text:hover {
    visibility: hidden
}

.comment-text:hover {
    visibility: visible
}

.label {
    padding: 3px 10px;
    line-height: 13px;
    color: #ffffff;
    font-weight: 400;
    border-radius: 4px;
    font-size: 75%
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
</style>
<!-- Modal -->
<div class="modal fade" id="ticket_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="ticket_modal" aria-hidden="true">
  <div class="modal-dialog modal-lg"  role="dialog">


    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ticket Details</h5>
        <button type="button" id="clsmodaltick" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
     
      <div class="card">
              <!-- <div class="card-header">
                <h3 class="card-title" ></h3>
              </div> -->
              <!-- /.card-header -->
                  <div class="card-body">
                  <form method="post" id="modal_form" enctype="multipart/form-data">




      <div class="form-row">

            <div class="input-group mb-3 col-md-4">
            <div class="input-group-prepend">
            <label class="input-group-text" for="ModalTicket_no"><strong> Ticket #</strong></label>
            </div>
            <input type="text" class="col form-control" name="ModalTicket_no" id="ModalTicket_no" required="" readonly="" style="background-color: #fff;">
            </div>
  
            <div class="input-group mb-3 col-md-4">
            <div class="input-group-prepend">
            <label class="input-group-text" for="ModalDate_create"><strong> Date Created</strong></label>
            </div>
            <input type="text" class="col form-control " name="ModalDate_create" id="ModalDate_create" required="" readonly="" style="background-color: #fff;">
            </div>

            <div class="input-group mb-3 col-md-4">
            <div class="input-group-prepend">
            <label class="input-group-text" for="ModalStore"><strong> Store</strong></label>
            </div>
            <input type="text" class="form-control " name="ModalStore" id="ModalStore" required="" readonly="" style="background-color: #fff;">
            </div>
            
            <div class="input-group mb-3 col-md-12">
            <div class="input-group-prepend">
            <label class="input-group-text" for="ModalSubject"><strong>Subject</strong></label>
            </div>
            <input type="text" class="form-control" name="ModalSubject" id="ModalSubject" required="" readonly="" style="background-color: #fff;">
            </div>


            <div class="input-group mb-3 col-md-12">
            <div class="input-group-prepend">
            <label class="input-group-text" for="ModalTOS"><strong> Types of Service</strong></label>
            </div>
            <input type="text" class="form-control " name="ModalTOS" id="ModalTOS" required="" readonly="" style="background-color: #fff;">
            </div>

            <div class="input-group mb-3 col-md-12">
            <div class="input-group-prepend">
            <label class="input-group-text" for="ModalTOS"><strong> Status</strong></label>
            </div>
            <input type="text" class="form-control " name="ModalStatus" id="ModalStatus" required="" readonly="" style="background-color: #fff;">
            </div>

<!--             <div class="input-group mb-3 col-md-12">
            <div class="input-group-prepend">
            <label class="input-group-text" for="ModalTOS"><strong> Concern</strong></label>
            </div>           
            <textarea name="Modal_concern" id="Modal_concern" class="form-control form-control-sm" readonly="" style="background-color: #fff;"></textarea>
            <input type="hidden" name="user_id" id="user_id" />
    
  </div> -->

  <div id="alrtmsg" class="mt-2 col-lg-12"></div>
                <div class=" col-lg-12">
            <label class="d-flex justify-content-between" for="Modal_reply"><strong> Comment</strong>
            <a href="#" name="vwfile" id="vwfile" class="text-decoration-none">View Attached File</a></label>
           
                  <textarea class="uModaltxtarea" id="Modal_reply" name="Modal_reply" minlength="5" maxlength="1000" placeholder="Leave a comment to follow up your report..."></textarea>
             
                    
                    
                    </div>

                  <div class="col-md-4">
                 
                   <input type="hidden" name="Modal_uId" id="Modal_uId" value="<?php echo $_SESSION['user_id'];?>" />
                     <input type="hidden" name="operation" id="operation" value="Addcomment" />
                    <input type="submit" class="mt-2 btn btn-outline-primary btn-sm" name="Modal_action" id="Modal_action"  value="Post comment" />
                <!--     <button type="button" class="btn btn-secondary btm-sm" data-dismiss="modal">Close</button> -->
                  
                 
                  
                  </div>



               
                  </div>
                  <div class="row mt-3">
                  <div class="col-md-12">


                  <div class="col-md-12" id="remarks_view"></div>

                  </div>
                  </div>
              <!-- /.card-body -->
            </div>










                
                 <!--  </div> -->
        <!-- </div> -->


<!-- 
               <fieldset>
                <legend><h6>Comment Thread</h6></legend> -->

<!-- alejo -->

 

<!-- alejo -->


   
               <!-- </fieldset> -->
          
                </div>
              </form>




          </div>
          </div>
          </div>
		  </div>
 <script type="text/javascript">

$('#vwfile').click(function (e) { 
    e.preventDefault();

var val = $('#ModalTicket_no').val();


    $.ajax({
      type: 'POST',
      url: 'sesticket.php',
      data: {tktval: val},
      success: function(response) {
        $('#images').html(response);
        $('#ViewFile #ticketxxx').val(val);
        $('#ViewFile #file-input').val('');
        $('#save_file').attr('disabled', 'true');
        $('#ViewFile').modal('show');
        $('#ticket_modal').modal('hide');



      }
    });
});



</script>

