<?php
include 'userheader.php';
 ?>


 <div class="container">

<div class="card col-md-12">
  <h5 class="card-header">Change Password</h5>
  <div class="card-body">
     <form method="post" id="change_passform">
      <div class="row">

          <div class="col-md-12 mb-3">
            <!-- <label for="email">Current Password</label> -->
            <input type="password" class="form-control" name="curpass" id="curpass" aria-describedby="emailHelp" placeholder="Current Password">
          </div>
          <div class="col-md-12 mb-3">
            <!-- <label for="password">New Password</label> -->
            <input type="password" class="form-control" name="newpass" id="newpass" placeholder="New Password" required>
          </div>
          <div class="col-md-12 mb-3">
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
  let oldPass= $('#usr_oldpas').val();
  
$(document).ready(function() {
  console.log(oldPass);
});

//   setTimeout(function() {
//     $('#changepass_modal').modal({
//             backdrop: 'static',
//             keyboard: false
//     });
// }, 1000);

  //   $('#btn_chngepass').on("click", function () {
  //     // alert("TEST");
  //     $.ajax({
  //       url: "../admin/insert.php",
  //       method: "POST",
  //       data: $('#change_passform').serialize(),
  //       success: function (data) {
  //         alert("Updated successfully");
  //         $("#change_passform")[0].reset();
  //         $("#changepass_modal").modal("hide");
            
  //       },
  //     });

  // });

        $('#btn_chngepass').on("click", function () {
        if ($('#newpass').val() != $('#confrm_nwpass').val()) {
          alert("New password is not the same in confirm password!")
          return false
        }
        else {
              $.ajax({
                url: "fetch.php",
                method: "POST",
                data: $('#change_passform').serialize(),
                success: function (data) {
                   Swal.fire({
                            icon: 'success',
                            title: 'Your work has been saved',
                            showConfirmButton: false,
                            timer: 1500
                  }).then(function(){
                     window.location.replace("userpanel.php");
                  });
                },

              });
        }



  });


</script>