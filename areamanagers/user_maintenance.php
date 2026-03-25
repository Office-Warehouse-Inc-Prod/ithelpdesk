<?php
include 'admin.php';
include '../condb.php';
include 'rst_form.php';
$regcon=new dbconfig();
 ?>

<head>
<link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/>
<script src="../js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="../css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="styles.css" />
<script src="../js/jquery.dataTables.min.js"></script>
<script src="../js/dataTables.select.min.js"></script>
<script src="../js/dataTables.responsive.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script src="../vendor/sweetalert/src/sweetalert2.js"></script> -->
 </head>


<div class="container mt-3">
<div class="col-md-12">
     
<button type="button" id="crtbtnact" class="btn btn-primary mb-3" data-toggle="modal" data-target="#usr_crt_modal"><i class="fas fa-users-cog">&nbsp</i>Create User Account</button>

<div class="modal fade" id="usr_crt_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title" id="exampleModalLabel">Create User Account</h5>
           <!--  <small id="emailHelp" class="form-text text-muted">Your information is safe with us.</small> -->
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     <form method="post" id="reg_form">
        <div class="modal-body">
          <div class="row">

           <div class="col-md-6 form-group">
            <!-- <label for="fname">First Name</label> -->
            <input type="text" class="form-control form-control-sm" name="fname" id="fname" aria-describedby="emailHelp" placeholder="Name" required>
          </div>
          <div class="col-md-6 form-group">
            <!-- <label for="lstname">Last Name</label> -->
            <input type="text" class="form-control form-control-sm" name="lstname" id="lstname" aria-describedby="emailHelp" placeholder="Last Name" required>
          </div>
          <div class="col-md-12 form-group">
          <!-- <label for="select_dept">Department</label> -->
          <input type="hidden" name="strslt_num" id="strslt_num" value="201" required="">

          <select class="form-control form-control-sm" name="select_dept" id="select_dept" >
          <option value=""> &larr; Select Department &rarr;</option>
            <?php
                     $query="select * from tbl_dept";
                     $run=$regcon->prepare($query);
                     $run->execute();
                     $rs=$run->get_result();
                     while ($res=$rs->fetch_assoc()) {
                         $deptid = $res['dept_id'];
                         $deptdesc = $res['dept_desc'];
                     ?>
                     <option value="<?php echo $deptid;?>"><?=$deptdesc; ?></option>
                     <?php }?>
                     ?>   
         </select>   
          </div>
          <div class="col-md-12 form-group strcol">
          <!-- <label for="select_dept">Branch</label> -->
           <select class="form-control form-control-sm" name="select_strcd" id="select_strcd">
           <option value="">Assign Branch</option>
                 <?php
                          $query="select * from tbl_branch";
                          $run=$regcon->prepare($query);
                          $run->execute();
                          $rs=$run->get_result();
                          while ($res=$rs->fetch_assoc()) {
                              $str_id = $res['str_num'];
                              $str_desc = $res['str_code'];
                              $str_fulldesc = $res['str_name'];
                          ?>
                          <option value="<?php echo $str_id;?>"><?=$str_desc." - ". $str_fulldesc; ?></option>
                          <?php }?>
                          ?>   
              </select>  
          </div>
          <div class="col-md-12 form-group">
                <select class="form-control form-control-sm" name="slct_gender" id="slct_gender" required="">
                  <option value="">Select User Gender</option>
                  <option value="1">Male</option>
                  <option value="2">Female</option>
                </select>
          </div>


          </div>

        </div>
        <div class="modal-footer border-top-0 d-flex justify-content-center">
          <input type="hidden" name="operation" id="operation" value="3" /> 
          <button id="btn_submit" class="btn btn-success">Submit</button>

        </div>
      </form>
    </div>
  </div>
</div>

</div>

  <table id="usermtc_table" class="table table-dark table-responsive table-condensed text-center"></table>

</div>
<script type="text/javascript">
  $(document).ready(function(){
     $(".strcol").hide();
var reptable;
var user_id = <?= $_SESSION['user_id']; ?>

function getdata(){
  $.post('fetchdata/fetch_data.php',{mode:'usermtc_dtable'},function(data){
    admin_datatable(data);
  },'json');
}
getdata();

function admin_datatable(t){
const dataset=t.usermtc_data;
     reptable =  $("#usermtc_table").DataTable({
           "dom":
          '<"pull-left"lf><"pull-right">tip',
          stateSave: true,
          "bDestroy": true,
          "responsive": true, "lengthChange": false, "autoWidth": false,
          language: {
          search: "_INPUT_",
          searchPlaceholder: "Search..."
          },
          pageLength:10,
          data: dataset,
           "order": [[ 0, "Desc" ]],

               columns: [


               {title:"USER ID", data:"user_id", "width": "5%","defaultContent": ""},
               {title:"Full Name", data:"flName","defaultContent": ""},
               {title:"Role", data:"role","width": "5%", "defaultContent": ""},
               {title:"Username", data:"username","defaultContent": ""},
               {title:"Department", data:"dept_desc","defaultContent": ""},
               {title:"Store Code", data:"str_code","defaultContent": ""},
               {title:"Reset Password", data:null,"width": "5%","defaultContent": "<Button id='resetusr' class='btn btn-warning'><i class='fas fa-edit'></i></Button>"}
 
               ]

   }); //  end of datatable

 $('#usermtc_table tbody').on( 'click', '#resetusr', function () {
         var data = reptable.row( $(this).parents('tr') ).data();
        $('#restusr_id').val(data['user_id']);
        $('#operation').val("4");
    if( !confirm('Are you sure you want to reset'+' '+data['flName']+' '+'password?')) {
              $('#restusr_id').val("");
        $('#operation').val("");
                    return false;
   }
   else{
        // var data = reptable.row( $(this).parents('tr') ).data();
        $.ajax({
        url: "insert.php",
        method: "POST",
        data: $('#rst_form').serialize(),
        success: function (data) {
          $("#rst_form")[0].reset();
            Swal.fire({
             icon: 'success',
             title: 'Password has been reset',
             showConfirmButton: false,
             timer: 1500
          });
          $("#rst_form").modal("hide");
          setTimeout(function(){// wait for 5 secs(2)
           location.reload(); // then reload the page.(3)
      }, 2000);
          
        }
      });

    
   }



        });


  }; //end of function 

});

$('#select_dept').change(function() {
  if ($(this).val() == 10) {
      $(".strcol").show();
       $('#strslt_num').val('');
      $('#select_strcd').prop({
        required: 'true'
      })
     
  }
  else if ($(this).val() == '11') {
         $(".strcol").hide();
    $('#strslt_num').val('202');
          $('#select_strcd').prop({required: 'false'})
  }

  else {
         $(".strcol").hide();
       $('#strslt_num').val('201');
       $('#select_strcd').prop({
        required: 'false'
      })
  }
});

$('#select_strcd').change(function() {
 let strVal = $(this).val();
 $('#strslt_num').val(strVal);

});


      $('#btn_submit').on("click", function () {



// validation
let FName = $('#fname').val();
let LstName = $('#lstname').val();
let Gender = $('#slct_gender').val();
let StrVal = $('strslt_num').val();

if (FName != "" && LstName != "" && Gender != "" && StrVal != "") {
      $.ajax({
        url: "insert.php",
        method: "POST",
        data: $('#reg_form').serialize(),
        success: function (data) {
          $("#reg_form")[0].reset();
          $("#usr_crt_modal").modal("hide");
          Swal.fire({
             icon: 'success',
             title: 'Your work has been saved',
             showConfirmButton: false,
             timer: 1500
          });
          $("#userModal").modal("hide");
          setTimeout(function(){// wait for 5 secs(2)
           location.reload(); // then reload the page.(3)
      }, 2000);
          
        },
      });
}
else{
           Swal.fire({
             icon: 'error',
             title: 'Please Complete',
             showConfirmButton: false,
             timer: 1500
          });
  return false;
}

  });



</script>