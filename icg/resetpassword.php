<?php
include 'defaultheader.php';
 ?>


 <div class="container" style="margin: 0 auto;">

<div class="card col-md-12">
  <h5 class="card-header text-center">Please update your password</h5>

  <div class="card-body">
     <form method="post" id="change_passform">
      <div class="justify-content-center">
        <div class="col-md-12 mb-2">
         <small class="">FOR NEW USER OR IF YOUR PASSWORD HAS BEEN RESET RECENTLY KINDLY CHANGE YOUR PASSWORD</small> 
        </div>
          <div class="row">
            
          <div class=" form-group col-md-12 mb-3 mx-auto">

            <div class="col-md-12 col-xs-12">
                          <!-- <label for="email">Current Password</label> -->
                <input type="password" class="form-control" name="curpass" id="curpass" aria-describedby="emailHelp" placeholder="Input the Password given by I.T Helpdesk">
            </div>

          </div>
          <div class=" form-group col-md-12 mb-3">
            <div class="col-md-12 col-xs-12">
                         <!-- <label for="password">New Password</label> -->
            <input type="password" class="form-control" name="newpass" id="newpass" placeholder="New Password" required minlength="8">
            </div>
 
          </div>
          <div class=" form-group col-md-12 mb-3">
            <div class="col-md-12 col-xs-12">
          <!-- <label for="confirm_password">Confirm Password</label> -->
            <input type="password" class="form-control" name="confrm_nwpass" id="confrm_nwpass" placeholder="Confirm Password" minlength="8">
            </div>

          </div>

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

$('.blink').each(function() {
    var elem = $(this);
    setInterval(function() {
        if (elem.css('visibility') == 'hidden') {
            elem.css('visibility', 'visible');
        } else {
            elem.css('visibility', 'hidden');
        }    
    }, 500);
});


});


        $('#btn_chngepass').on("click", function () {
        if ($('#newpass').val() != $('#confrm_nwpass').val()) {
          // alert("New password is not the same in confirm password!")
             Swal.fire('New password is not the same in confirm password!');
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