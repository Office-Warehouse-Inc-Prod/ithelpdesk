<?php

include 'header.php';

 ?>

<div class="modal fade" id="changepass_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     <form method="post" id="change_passform">
        <div class="modal-body">
          <div class="form-group">
            <!-- <label for="email">Current Password</label> -->
            <input type="password" class="form-control" name="curpass" id="curpass" aria-describedby="emailHelp" placeholder="Current Password">
          </div>
          <div class="form-group">
            <!-- <label for="password">New Password</label> -->
            <input type="password" class="form-control" name="newpass" id="newpass" placeholder="New Password" required>
          </div>
          <div class="form-group">
            <!-- <label for="confirm_password">Confirm Password</label> -->
            <input type="password" class="form-control" name="confrm_nwpass" id="confrm_nwpass" placeholder="Confirm Password">
          </div>
        </div>
        <div class="modal-footer border-top-0 d-flex justify-content-center">
          <input type="hidden" name="operation" id="operation" value="changepass" /> 
          <button id="btn_chngepass" class="btn btn-success">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
  setTimeout(function() {
    $('#changepass_modal').modal({
            backdrop: 'static',
            keyboard: false
    });
}, 1000);

    $('#btn_chngepass').on("click", function () {
      $.ajax({
        url: "insert.php",
        method: "POST",
        data: $('#change_passform').serialize(),
        success: function (data) {
          alert(data);
          $("#change_passform")[0].reset();
          $("#changepass_modal").modal("hide");
          location.replace("dashboard.php");

        }
      });

  });

</script>