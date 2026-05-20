<?php
include 'admin.php';
include '../condb.php';
include 'rst_form.php';
$regcon=new dbconfig();
 ?>

<style>
.swal-btn{
  margin: 10px; /* add 10px margin between buttons */
}
</style>

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

<style>
.table{
  background-color: #ffffff;
  border-collapse: separate;
  border-spacing: 0;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 10px 8px rgba(108,108,53,0.4);
  border: 1px solid #e9ecef;
  margin-top:15px;
}

.table thead th{
  background-color: #54699e;
  color:white;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.85rem;
  letter-spacing: 0.5px;
  padding: 15px;
  border-bottom: 2px solid #dee2e6;
}

.table tbody td{
  padding:12px 15px;
  vertical-align: middle;
  color:#333;
  border-bottom: 1px solid #f1f1f1;
}

.table tbody tr:hover{
  background-color: #213456 !important;
  color:#ffffff !important;
  cursor:pointer;
  transition: all 0.2s ease;
}

.table-responsive{
  border-radius: 8px;
  margin-top:20px;
}
    
#usr_crt_modal .modal-content {
  border: none;
  border-radius: 15px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
}

#usr_crt_modal .modal-header {
  background-color: #213456;
  color: #fff;
  border-top-left-radius: 15px;
  border-top-right-radius: 15px;
  border-bottom: 4px solid #E1AD01; /* Your Theme Gold */
}

#usr_crt_modal .modal-title {
  font-weight: 700;
  letter-spacing: 0.5px;
  display: flex;
  align-items: center;
}

#usr_crt_modal .input-group-text {
  background-color: #f8f9fa;
  border-right: none;
  color: #213456;
}

#usr_crt_modal .form-control {
  border-left: none;
  height: 45px;
  border-radius: 0 8px 8px 0;
}

#usr_crt_modal .form-control:focus {
  border-color: #ced4da;
  box-shadow: none;
}

#usr_crt_modal .input-group:focus-within {
  box-shadow: 0 0 0 0.2rem rgba(225, 173, 1, 0.25);
  border-radius: 8px;
}

#btn_submit {
  background-color: #E1AD01;
  border: none;
  color: #213456;
  font-weight: 700;
  padding: 10px 40px;
  border-radius: 30px;
  transition: all 0.3s ease;
}

#btn_submit:hover {
  background-color: #213456;
  color: #E1AD01;
  transform: translateY(-2px);
}

.dataTables_wrapper .pull-left {
  flex-direction: row;      
  align-items: center;      
  justify-content: flex-start; /* Aligns both items to the left */
  width: 100%;              
  gap: 40px;                /* Keeps the gap between them */
  margin-bottom: 20px; 
}

.dataTables_filter {
  position: relative;
  display: inline-block;    
  margin: 0 !important;     
}

.dataTables_filter label {
  display: flex;
  align-items: center;
  margin-bottom: 0;         
}

/* Search Icon */
.dataTables_filter::before {
  content: "\f002"; 
  font-family: "Font Awesome 5 Free";
  font-weight: 900;
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #213456;
  z-index: 1;
  opacity: 0.6;
}

.dataTables_filter input {
  border: 2px solid #e0e0e0 !important;
  border-radius: 50px !important;
  padding: 8px 15px 8px 35px !important; 
  width: 300px !important;
  background-color: #ffffff !important;
  transition: all 0.3s ease;
  outline: none !important;
  color: #213456;
  margin-left: 0 !important; 
}

.dataTables_filter input:focus {
  border-color: #E1AD01 !important;
  box-shadow: 0 0 10px rgba(225, 173, 1, 0.2) !important;
}

#crtbtnact {
  background-color: #E1AD01 !important;
  border: none !important;
  border-radius: 50px !important;
  color: #213456 !important;
  font-weight: 700 !important;
  padding: 10px 25px !important;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-size: 13px;
  transition: all 0.3s ease;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  white-space: nowrap;      
  display: inline-flex;     
  align-items: center;
  gap: 8px;
  margin: 0;                
}

#crtbtnact:hover {
  background-color: #213456 !important;
  color: #ffffff !important;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(33, 52, 86, 0.3);
}

/* Table Header Styling */
#usermtc_table thead th {
  background-color: #213456 !important;
  color: #ffffff !important;
  border: none;
  padding: 15px;
  font-size: 13px;
}

</style>


<div class="container mt-3">
<div class="col-md-12">
     
<button type="button" id="crtbtnact" class="btn btn-primary mb-3" data-toggle="modal" data-target="#usr_crt_modal"><i class="fas fa-users-cog">&nbsp</i>Create User Account</button>

<div class="modal fade" id="usr_crt_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title" id="exampleModalLabel">Create User Account</h5>
           <!--  <small id="emailHelp" class="form-text text-muted">Your information is safe with us.</small> -->
        
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     <form method="post" name="reg_form" id="reg_form">
        <div class="modal-body">
          <div class="row">
          <input type="hidden" name="usrID" id="usrID">
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
          <input type="hidden" name="strslt_num" id="strslt_num" value="201" >

          <select class="form-control form-control-sm" name="select_dept" id="select_dept" >
          <option value=""> &larr; Select Department &rarr;</option>
            <?php
                     $query="select * from tbl_dept where dept_id != '11' ";
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
                <select class="form-control form-control-sm" name="slct_gender" id="slct_gender" require>
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

  <table id="usermtc_table" class="table table-hover table-responsive"></table>

</div>
<script type="text/javascript">
  $(document).ready(function(){
     $(".strcol").hide();

     $('#crtbtnact').click(function (e) { 

      e.preventDefault();

      $("#select_dept").show();
            $('#slct_gender').show();
            $('#slct_gender').prop('required',true);
      
     });

var reptable;
var user_id = <?= $_SESSION['user_id']; ?>

function getdata(){
  $.post('fetchdata/fetch_data.php',{mode:'usermtc_dtable'},function(data){
    admin_datatable(data);
    // console.log(data);
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
               {title:"Status", data:"usr_stat","defaultContent": ""},
              //  {title:"Update", data:null,"defaultContent": "<Button class='GetName btn btn-info mr-2' name='BtnVw' id='BtnVw'><i class='fas fa-eye'></i></Button>"}
	       {title:"Update", data:null,"width": "20%","defaultContent": "<Button class='GetName btn btn-info mr-2' name='BtnVw' id='BtnVw'><i class='fas fa-eye'></i></Button> <Button class=' GetPosition btn btn-success mr-2' name='BtnEdit' id='BtnEdit'><i class='fas fa-edit'></i></Button> <Button class=' GetPositions btn btn-danger' name='BtnDact' id='BtnDact'><i class='fas fa-window-close'></i></Button>"}
	       ],
	       "columnDefs": [
                {

targets: [6],
"width": "2%",
render: function ( data, type, row) {
    if(type === 'display'){
      if(data == 'A'){
          data = '<i class="fas fa-user-check" style="color : green; font-size: 25px;"></i>'
        }
        else if (data == 'D'){
          data = '<i class="fas fa-user-times" style="color : red; font-size: 25px;"></i>'

        }
}
return data;
}

}
               ]

   }); //  end of datatable

//  $('#usermtc_table tbody').on( 'click', '#resetusr', function () {
//          var data = reptable.row( $(this).parents('tr') ).data();
//         $('#restusr_id').val(data['user_id']);
//         $('#operation').val("4");
//     if( !confirm('Are you sure you want to reset'+' '+data['flName']+' '+'password?')) {
//               $('#restusr_id').val("");
//         $('#operation').val("");
//                     return false;
//    }
//    else{
//         // var data = reptable.row( $(this).parents('tr') ).data();
//         $.ajax({
//         url: "insert.php",
//         method: "POST",
//         data: $('#rst_form').serialize(),
//         success: function (data) {
//           $("#rst_form")[0].reset();
//             Swal.fire({
//              icon: 'success',
//              title: 'Password has been reset',
//              showConfirmButton: false,
//              timer: 1500
//           });
//           $("#rst_form").modal("hide");
//           setTimeout(function(){// wait for 5 secs(2)
//            location.reload(); // then reload the page.(3)
//       }, 2000);
          
//         }
//       });

    
//    }



//         });



$('#usermtc_table tbody').on('click', 'button', function () {
      
      var action = this.id;
            var data = reptable.row( $(this).parents('tr') ).data();
    const IDx = data.user_id;
            if (action=='BtnVw') {

            // var data = reptable.row( $(this).parents('tr') ).data();
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
      //     setTimeout(function(){// wait for 5 secs(2)
      //      location.reload(); // then reload the page.(3)
      // }, 2000);
          
        }
      });

    
   }

            }

     
         if(action == 'BtnEdit'){

            
          // alert( 'This is the Position: '+data[1]);
          // alert('beta phase');
            $("#usr_crt_modal").modal("show");

            $(".strcol").show();
            $("#select_dept").show();
            $('#slct_gender').hide();
            $('#slct_gender').removeAttr('required');
            $('#usr_crt_modal #operation').val("stredit");
            $('#usrID').val(data['user_id']);
            $('#fname').val(data['fname']);
            $('#lstname').val(data['lstname']);
            $('#strslt_num').val(data['dept_id']);
            $('#select_dept').val(data['dept_desc']);
            $('#select_strcd').val(data['str_num']);
          //  $('#slct_gender').val(data['gender_id']);
        
                //  $("#exampleModalLongTitle #menu_value").val();
                // $('#restusr_id').val(data['user_id']);
    
	 }

	           if (action == 'BtnDact') {
        //  alert(IDx);
        Swal.fire({
  title: "Do you want to update this user account status?",
  showDenyButton: true,
  showCancelButton: true,
  confirmButtonText: "Deactivate",
  denyButtonText: "Activate",
  buttonsStyling: false,
  customClass: {
    confirmButton: 'btn btn-danger swal-btn',
    denyButton: 'btn btn-success swal-btn',
    cancelButton: 'btn btn-secondary swal-btn'
  }
}).then((result) => {
  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
  Swal.fire({
    title: "Deactivated!",
    text: "",
    icon: "success",
    timer: 1000, // auto-close the modal after 1.5 seconds
    showConfirmButton: false // no button required
  });
  $.ajax({
    type: 'POST',
    url: 'insert.php', 
    data: { operation: 'Dactivate', IDx: IDx },
    success: function() {
      getdata();
    }
  });
} else if (result.isDenied){
  Swal.fire({
    title: "Activated!",
    text: "",
    icon: "success",
    timer: 1000, // auto-close the modal after 1.5 seconds
    showConfirmButton: false, // no button required
  });
  $.ajax({
    type: 'POST',
    url: 'insert.php', 
    data: { operation: 'Activate', IDx: IDx },
    success: function() {
      getdata();
    }
  });
}
});

         }
           
    
        });

  }; //end of function 

});

$('#select_dept').change(function() {
  if ($(this).val() == '10') {
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
let SelDept = $('#select_dept').val();
// select_dept

if (FName !== "" && LstName !== "") {
  $.ajax({
    url: "insert.php",
    method: "POST",
    data: $('#reg_form').serialize(),
    success: function (data) {
      console.log(data);

      $("#reg_form")[0].reset();
      $("#usr_crt_modal").modal("hide");

      Swal.fire({
        icon: 'success',
        title: 'Saved successfully',
        text: 'The record has been added to the system.',
        width: 360,
        showConfirmButton: false,
        timer: 1800,
        timerProgressBar: true
      });

      $("#userModal").modal("hide");
    }
  });
} else {
  Swal.fire({
    icon: 'warning',
    title: 'Incomplete information',
    text: 'Please complete all required fields.',
    width: 360,
    confirmButtonText: 'OK'
  });
  return false;
}


  });



</script>
