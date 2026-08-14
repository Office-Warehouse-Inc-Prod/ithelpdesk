<?php
include 'admin.php';
?>
<style>
  body {
    background: linear-gradient(to bottom, #ffffff, #99aac8);
    background-attachment: fixed; 
    margin: 0; 
    overflow-x: hidden;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    min-height: 100vh;
  } 

  #changepass_modal .modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
  }

  #changepass_modal .modal-header {
    background-color: #213456;
    color: #fff;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    border-bottom: 4px solid #E1AD01;
  }

  #changepass_modal .modal-title {
    font-weight: 700;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
  }

  #changepass_modal .input-group-text {
    background-color: #f8f9fa;
    border-right: none;
    color: #213456;
  }

  #changepass_modal .form-control {
    border-left: none;
    height: 45px;
    border-radius: 0 8px 8px 0;
  }

  #changepass_modal .form-control:focus {
    border-color: #ced4da;
    box-shadow: none;
  }

  #changepass_modal .input-group:focus-within {
    box-shadow: 0 0 0 0.2rem rgba(225, 173, 1, 0.25);
    border-radius: 8px;
  }

  #btn_chngepass {
    background-color: #E1AD01;
    border: none;
    color: #213456;
    font-weight: 700;
    padding: 10px 40px;
    border-radius: 30px;
    transition: all 0.3s ease;
  }

  #btn_chngepass:hover:not(:disabled) {
    background-color: #213456;
    color: #E1AD01;
    transform: translateY(-2px);
  }
</style>

<div class="modal fade" id="changepass_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-lock mr-2 text-warning"></i> SECURITY UPDATE
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form method="post" id="change_passform">
        <div class="modal-body px-4 py-4">
          <p class="text-muted small mb-4">Please enter your current password to authorize changes to your account.</p>
          
          <div class="form-group mb-3">
            <label class="small font-weight-bold">CURRENT PASSWORD</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-key"></i></span>
              </div>
              <input type="password" class="form-control" name="curpass" id="curpass" placeholder="••••••••" required>
            </div>
          </div>

          <hr class="my-4">

          <div class="form-group mb-3">
            <label class="small font-weight-bold">NEW PASSWORD</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
              </div>
              <input type="password" class="form-control" name="newpass" id="newpass" placeholder="New Password" required>
            </div>
          </div>

          <div class="form-group">
            <label class="small font-weight-bold">CONFIRM NEW PASSWORD</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
              </div>
              <input type="password" class="form-control" name="confrm_nwpass" id="confrm_nwpass" placeholder="Confirm Password" required>
            </div>
          </div>
        </div>

        <div class="modal-footer border-top-0 pb-4">
          <input type="hidden" name="operation" id="operation" value="changepass" /> 
          <button type="button" id="btn_chngepass" class="btn shadow-sm">
            UPDATE PASSWORD <i class="fas fa-arrow-right ml-2"></i>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    setTimeout(function() {
        $('#changepass_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }, 1000);

    $('#btn_chngepass').on("click", function (e) {
        e.preventDefault();

        const curPass = $('#curpass').val().trim();
        const newPass = $('#newpass').val().trim();
        const confPass = $('#confrm_nwpass').val().trim();

        if(curPass === "") {
            Swal.fire({ icon: 'warning', title: 'Required', text: 'Please enter your current password.' });
            return false;
        }

        if(newPass === "") {
            Swal.fire({ icon: 'warning', title: 'Required', text: 'Please enter a new password.' });
            return false;
        }

        if(newPass !== confPass) {
            Swal.fire({ icon: 'error', title: 'Mismatch', text: 'New passwords do not match!' });
            return false;
        }

        $.ajax({
            url: "insert.php",
            method: "POST",
            data: $('#change_passform').serialize(),
            dataType: "json", 
            beforeSend: function() {
                $('#btn_chngepass').html('<i class="fas fa-spinner fa-spin"></i> Processing...').attr('disabled', true);
            },
            success: function (response) {
                $('#btn_chngepass').html('UPDATE PASSWORD <i class="fas fa-arrow-right ml-2"></i>').attr('disabled', false);

                if (response.status === 'success') {
                    $("#change_passform")[0].reset();
                    $("#changepass_modal").modal("hide");

                    Swal.fire({
                        icon: 'success',
                        title: 'Password Updated!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2500
                    }).then(() => {
                        window.location.replace("../logout.php"); 
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: response.message
                    });
                    
                    $('#curpass').val('');
                }
            },
            error: function() {
                $('#btn_chngepass').html('UPDATE PASSWORD <i class="fas fa-arrow-right ml-2"></i>').attr('disabled', false);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'An error occurred while communicating with the server.'
                });
            }
        });
    });
});
</script>