<?php include 'defaultheader.php'; ?>

<style>
  /* Custom Theme Styles */
  body {
    background-color: #f8f9fa; /* Light background to make the card pop */
  }

  .text-navy {
    color: #213456 !important;
  }

  .card-modern {
    border: none;
    border-radius: 16px;
    box-shadow: 0 12px 35px rgba(33, 52, 86, 0.1);
    overflow: hidden;
    background-color: #ffffff;
  }

  .card-header-theme {
    background: linear-gradient(135deg, #FFDB58 0%, #D4AF37 100%);
    color: #213456;
    border-bottom: none;
    padding: 1.5rem 1rem;
    font-weight: 700;
    letter-spacing: 0.5px;
  }

  .btn-theme {
    background: linear-gradient(135deg, #FFDB58 0%, #D4AF37 100%);
    color: #213456;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    padding: 10px 30px;
    transition: all 0.3s ease;
  }

  .btn-theme:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(212, 175, 55, 0.4);
    color: #213456;
  }

  .form-control {
    border-radius: 8px;
    padding: 0.75rem 1rem;
    border: 1px solid #e0e4e8;
    color: #213456;
  }

  .form-control:focus {
    border-color: #D4AF37;
    box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.2);
  }

  .pulse-text {
    color: #213456;
    font-weight: 600;
    font-size: 0.85rem;
    animation: pulse-opacity 2s infinite;
  }

  @keyframes pulse-opacity {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
  }

  /* Center the container vertically and horizontally */
  .update-pass-container {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>

<div class="container update-pass-container">
  <div class="col-md-6 col-lg-5 col-sm-10 mx-auto">
    <div class="card card-modern">
      <h5 class="card-header card-header-theme text-center m-0">
        <i class="fas fa-lock me-2"></i> Update Your Password
      </h5>

      <div class="card-body p-4">
        <form method="post" id="change_passform">
          <div class="text-center mb-4">
            <div class="alert alert-light border pulse-text rounded-3" role="alert">
              <i class="fas fa-info-circle me-1"></i> For new users or if your password was reset, kindly change it below.
            </div>
          </div>

          <div class="form-group mb-3">
            <label class="text-navy fw-bold small mb-1" for="curpass">Current Password</label>
            <input type="password" class="form-control" name="curpass" id="curpass" placeholder="Password given by I.T Helpdesk" required>
          </div>

          <div class="form-group mb-3">
            <label class="text-navy fw-bold small mb-1" for="newpass">New Password</label>
            <input type="password" class="form-control" name="newpass" id="newpass" placeholder="Enter new password" required minlength="8">
          </div>

          <div class="form-group mb-4">
            <label class="text-navy fw-bold small mb-1" for="confrm_nwpass">Confirm New Password</label>
            <input type="password" class="form-control" name="confrm_nwpass" id="confrm_nwpass" placeholder="Re-type new password" required minlength="8">
          </div>

          <div class="d-grid mt-4">
            <input type="hidden" name="operation" id="operation" value="changepass" /> 
            <button type="submit" id="btn_chngepass" class="btn btn-theme">Save Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    let oldPass = $('#usr_oldpas').val();

    // Replaced the old JS blink logic with the pure CSS .pulse-text class above.
    // It is much lighter on the browser and smoother.

    $('#btn_chngepass').on("click", function (e) {
        // Prevent default form submission to stop page reloads
        e.preventDefault(); 

        let newPass = $('#newpass').val();
        let confirmPass = $('#confrm_nwpass').val();

        // Basic validation
        if (!newPass || !confirmPass || !$('#curpass').val()) {
            Swal.fire({
                icon: 'warning',
                title: 'Required Fields',
                text: 'Please fill in all password fields.',
                confirmButtonColor: '#213456'
            });
            return false;
        }

        if (newPass !== confirmPass) {
            Swal.fire({
                icon: 'error',
                title: 'Mismatch',
                text: 'New password does not match the confirm password!',
                confirmButtonColor: '#213456'
            });
            return false;
        }
        
        // AJAX Request
        $.ajax({
            url: "fetch.php",
            method: "POST",
            data: $('#change_passform').serialize(),
            success: function (data) {
                Swal.fire({
                    icon: 'success',
                    title: 'Password Updated!',
                    text: 'Your new password has been saved.',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                }).then(function(){
                    window.location.replace("userpanel.php");
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong. Please try again.',
                    confirmButtonColor: '#213456'
                });
            }
        });
    });
});
</script>