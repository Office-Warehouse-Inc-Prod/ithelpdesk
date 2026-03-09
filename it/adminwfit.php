<?php
include 'admin.php';
include '../condb.php';

$con1=new dbconfig();

 ?>
<head>
<link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/>
<script src="../js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="../css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="styles.css" />

<script src="../js/jquery.dataTables.min.js"></script>
<script src="../js/dataTables.select.min.js"></script>
<script src="../js/dataTables.responsive.min.js"></script>
<script src="../js/fnReloadAjax.js"></script>
</head>
<style>
  #new_rep_table {
    background-color: #ffffff;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 8px;
    overflow: hidden;
     box-shadow: 0 10px 8px rgba(108, 108, 53, 0.4);
    border: 1px solid #e9ecef;
  }

  #new_rep_table thead th {
    background-color: #54699e;
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    padding: 15px;
    border-bottom: 2px solid #dee2e6;
  }

  #new_rep_table tbody td {
    padding: 12px 15px;
    vertical-align: middle;
    color: #333;
    border-bottom: 1px solid #f1f1f1;
  }

  /* Hover Effect with requested color #213456 */
  #new_rep_table tbody tr:hover {
    background-color: #213456 !important;
    color: #ffffff !important;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  /* Responsive Table Wrapper */
  .table-responsive {
    border-radius: 8px;
    margin-top: 20px;
  }
  /* Change Password Modal Custom Styles */
  #newrpt_Modal .modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
  }

  #newrpt_Modal .modal-header {
    background-color: #213456;
    color: #fff;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    border-bottom: 4px solid #E1AD01; /* Your Theme Gold */
  }

  #newrpt_Modal .modal-title {
    font-weight: 700;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
  }

  #newrpt_Modal .input-group-text {
    background-color: #f8f9fa;
    border-right: none;
    color: #213456;
  }

  #newrpt_Modal .form-control {
    border-left: none;
    height: 45px;
    border-radius: 0 8px 8px 0;
  }

  #newrpt_Modal .form-control:focus {
    border-color: #ced4da;
    box-shadow: none;
  }

  #newrpt_Modal .input-group:focus-within {
    box-shadow: 0 0 0 0.2rem rgba(225, 173, 1, 0.25);
    border-radius: 8px;
  }

  #msgbtn {
    background-color: #E1AD01;
    border: none;
    color: #213456;
    font-weight: 700;
    padding: 10px 40px;
    border-radius: 30px;
    transition: all 0.3s ease;
  }

  #msgbtn:hover {
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

  /* 2. Modern Search Bar Styling (Now back on the left) */
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
</style>
 
<div class="container mt-4">
  <div class="table-responsive-xl">
    <table class="table table-hover" id="new_rep_table">
        </table>
  </div>
</div>

<!-- Start of Add/Edit Modal -->
<script src="../js/coms.js"></script> 
 <div class="modal fade" id="newrpt_Modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
  data-backdrop="static"
   data-keyboard="false">
  <div class="modal-dialog modal-lg">
  <form method="post" id="newrpt_form" enctype="multipart/form-data">
   <div class="modal-content">
    <div class="modal-header">
     <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
     <h4 class="modal-title" id="tick_title" value=""></h4>
    </div>
    <div class="modal-body">
      <!-- <form> --> 
      <div class="row">
        <div class="form-group col-md-4">
        <label>STORE</label>
        <input type="hidden" name="store" id="store" readonly="" value="">
        <input type="text" class="form-control form-control-sm" name="str_desc" id="str_desc" readonly="" value="">
      </div>
    
      <div class="form-group col-md-4">
        <label>Created By:</label>
        <input type="text" class="form-control form-control-sm" name="crtd_by" id="crtd_by" readonly="" >
      </div>
    
      <input type = "hidden" class="form-control form-control-sm" name = "ticket_no" id="ticket_no">
      <div class="form-group col-md-4">
        <label>DATE CREATED</label>
        <input type="text" class="form-control form-control-sm" name="date_createdx" id="date_createdx" readonly="" value="">
      </div>

      <div class="form-group col-md-12">
        <label>SUBJECT</label>
        <textarea name="concern" id="concern" class="form-control form-control-sm" placeholder="Input Concern" style="text-transform:uppercase" onkeyup="this.value = this.value;" readonly></textarea>
      </div>

      <div class="form-group col-md-4">
        <label>Service Requested:</label>
        <input type="text" class="form-control form-control-sm" name="tos" id="tos" readonly="" >
      </div>

      <div class="form-group col-md-12">
        <label>CONCERN</label>
        <textarea name="concern" id="message" class="form-control form-control-sm" placeholder="Input Concern" 
          style="text-transform:uppercase" onkeyup="this.value = this.value;" readonly></textarea>
      </div>

      <div class="form-group col-md-4">
        <label>VIA</label>
        <select class="form-control form-control-sm" name="via" id="via" required>
        <option value=""> &larr; VIA &rarr;</option>
        <?php
          $query = "select * from via_main";
          $run = $con1->prepare($query);
          $run->execute();
          $rs = $run->get_result();
          while ($res = $rs->fetch_assoc()) {
        ?>
        <option value="<?=$res['via_desc']?>"><?=$res['via_desc']?></option>
        <?php }?>
        </select>
      </div>

      <div class="form-group col-md-8">
        <label>ASSIGNED SUPPORT</label>
        <input type="hidden" name="it_num" id="it_num" readonly="">
        <select class="form-control form-control-sm" name="itsup" id="itsup">
        <option value="">Assign support...</option>  
          <?php
            $query="select * from it_tech WHERE itsup NOT IN ('4','7','8','12','14') AND deptsel = '1'";
            $run=$con1->prepare($query);
            $run->execute();
            $rs=$run->get_result();
            while ($res=$rs->fetch_assoc()) {
            $tchid = $res['itsup'];
            $tchdesc = $res['it_desc'];
          ?>

          <option value="<?php echo $tchid;?>"><?= $tchdesc; ?></option>
          <?php }?>    
        </select> 
      </div>
  
      <div class="form-group col-md-6">
        <label>CATEGORY</label>
        <input type="hidden" name="cat_num" id="cat_num" readonly="">
        <select class="form-control form-control-sm" name="cat" id="cat" required >
        <option value=""> &larr; CATEGORY &rarr;</option>  
        <?php
          $query="select * from category WHERE deptsel = '1'";
          $run=$con1->prepare($query);
          $run->execute();
          $rs=$run->get_result();
          while ($res=$rs->fetch_assoc()) {
          $supid = $res['id'];
          $suppdesc = $res['category_name'];
        ?>

        <option value="<?php echo $supid;?>"><?= $suppdesc; ?></option>
        <?php }?>
        </select> 
      </div>

      <div class="form-group col-md-6">
        <label>SUB CATEGORY</label>
        <input type="hidden" name="sub_num" id="sub_num" readonly="">
        <select class="form-control form-control-sm" name="sub" id="sub">
        </select>
      </div>

      <div class="form-group col-md-4 hide_isp">
        <label for="isp" id="lbl_isp">Service Provider</label>
        <input type="hidden" name="isp_num" id="isp_num" readonly="">
        <select class="form-control form-control-sm" name="isp" id="isp">
          <option value="">Select Network Provider</option>  
            <?php
              $query="select * from tbl_isp";
              $run=$con1->prepare($query);
              $run->execute();
              $rs=$run->get_result();
              while ($res=$rs->fetch_assoc()) {
              $ispid = $res['isp_id'];
              $ispdesc = $res['isp_shortDesc'];
            ?>

            <option value="<?php echo $ispid;?>"><?= $ispdesc; ?></option>
            <?php }?>
        </select> 
      </div>

      <div class="form-group col-md-4 hide_isp" >
        <label id="lbl_refNo" for="refNo">Reference No:</label>
        <input type="text" class="form-control form-control-sm" name="refNo" id="refNo">
      </div>

      <div class="form-group col-md-4 hide_isp">
        <label for="date_refNo" class="hidden" id="lbl_DtRefNo">Date of RefNo</label>
          <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
            <input type="text" name="date_refNo" id="date_refNo" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker3"/>
            <div class="input-group-append" data-target="#" data-toggle="datetimepicker">
            <!-- <input type="text" class="form-control form-control-sm" name="date_created" id="date_created" readonly="" value=""> -->
            <div class="input-group-text" id="ico_cal3"><i class="fa fa-calendar"></i></div>
          </div>
        </div>
      </div>

      <div class="form-group col-md-4 selected">
        <label>STATUS</label>
        <select class = "form-control form-control-sm" name= "status" id="status" required>
        <option value=""> &larr; Status &rarr;</option>
           <?php
              $query="select * from status WHERE it_module_tag = 'Y' AND stat_id <> '29'";
              $run=$con1->prepare($query);
              $run->execute();
              $rs=$run->get_result();
              while ($res=$rs->fetch_assoc()) {
            ?>
            <option><?=$res['stat_desc'] ?></option>
            <?php }?>
        </select>
      </div>
      <div class="form-group col-md-4">
        <label id="dateclabel" class="hidden">DATE CLOSED</label>
        <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
          <input type="text" name="date_closed" id="date_closed" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker2" autocomplete="off" />
          <div class="input-group-append" data-target="#date_closed" autocomplete="off" data-toggle="datetimepicker">
          <div class="input-group-text" id="ico_cal" name="ico_cal"><i class="fa fa-calendar"></i></div>
        </div>
      </div>
    </div>

    <div class="form-group col-md-4">
      <label id="clby_label" class="hidden">CLOSED BY</label>
      <input type="text" name="close_by" id="close_by" value="<?php echo $_SESSION['tech_id'];?>">
      <input type="text" class="form-control form-control-sm" name="cl_desc" id="cl_desc" readonly="" value="<?php echo $_SESSION['fname'].' '.$_SESSION['lstname'];?>">
    </div>

    <div class="form-group col-md-12">
      <label>Work Output: </label>
      <textarea name="remarks" id="remarks" class="form-control form-control-sm"placeholder="Your Workoutput"
      style="text-transform:uppercase"></textarea>
    </div>
    <hr/>

    <div class="form-group col-md-12">
      <p>
        <button class="btn btn-primary float-right mr-2" type="button" name="msgbtn" id="msgbtn" value="show">
          Show Message Thread
        </button>
      </p>
    </div>

    <div class="col-md-12 collapse" id="msg_thread">
      <div class="card card-body">
        <div class="row">
          <div class="col-md-12 dv_msg">
            <label style="font-weight: bold;">Add Message:</label>
            <textarea name="admsg" id="" class="form-control form-control-sm"placeholder="Reply to their message or give an updates regarding on this ticket..."></textarea>
          </div>
          <div class="col-md-12 mt-4 mb-2 dv_msg">
            <label for="remarks_view" style="font-weight: bold;">Ticket Thread:</label>
            <div class="container_remarks">
              <div id="remarks_view"><ul></ul></div>
            </div>
          </div>
        </div>
        <div clas="col-md-12">
          <input type="submit" name="action" id="action" class="btn btn-success" value="Add"/>
          <button type="button" name="btnClose" id="btnClose" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal-footer">
  <input type="hidden" name="operation" id="operation" />
  <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id'];  ?>">
</div>
</div>
</div>
</div>
</form>
</div>
  

<script type="text/javascript">
  $(document).ready(function(){


  

// for Status Open    
$("div.selected select").val("OPEN");


  // $('#action').hide();

var reptable;
var user_id = <?= $_SESSION['user_id']; ?>

function getdata(){
  $.post('fetchdata/fetch_data.php',{mode:'newrpt_tbl'},function(data){
    // console.log(data);
    admin_datatable(data);
  },'json');
}
getdata();

function admin_datatable(t){
const dataset=t.newrptdata;
     reptable =  $("#new_rep_table").DataTable({
           "dom":
          '<"pull-left"lf><"pull-right">tip',
           // ajax: t,
          stateSave: true,
          "bDestroy": true,
          "responsive": true, "lengthChange": false, "autoWidth": false,
          language: {
          emptyTable: "No unassinged reports",
          search: "_INPUT_",
          searchPlaceholder: "Search..."
          },
          pageLength:5,
          data: dataset,
           "order": [[ 0, "Desc" ]],

          columns: [
          {title:"TicketNo", data:"ticket_no","defaultContent": ""},
          {title:"Department/Store", data:"str_code","defaultContent": ""},
          {title:"Created By", data:"full_name","defaultContent": ""},
          {title:"Date Created", data:"date_created","defaultContent": ""},
          {title:"SUBJECT", data:"concern","defaultContent": ""},
          {title:"Types of Service", data:"service_desc","defaultContent": ""},
          {title:"CONCERN", data:"subject","defaultContent": ""},
          {title:"Update", data:null,"defaultContent": "<Button class='btn btn-danger' name='update'><i class='fas fa-edit'></i></Button>"}


          ],
              rowCallback: function(row, data, index){
    if(data['msg_cnt'] == '1'){
      $(row).find('td:eq(0)').css("font-weight", "bold");
      $(row).find('td:eq(1)').css("font-weight", "bold");
      $(row).find('td:eq(2)').css("font-weight", "bold");
      $(row).find('td:eq(3)').css("font-weight", "bold");
      $(row).find('td:eq(4)').css("font-weight", "bold");
      $(row).find('td:eq(5)').css("font-weight", "bold");
      $(row).find('td:eq(6)').css("font-weight", "bold");
      $(row).find('td:eq(7)').css("font-weight", "bold");
      $(row).find('td:eq(8)').css("font-weight", "bold");
      $(row).find('td:eq(9)').css("font-weight", "bold");
      $(row).find('td:eq(10)').css("font-weight", "bold");
      $(row).find('td:eq(11)').css("font-weight", "bold");
 
    }


  }



   }); //  end of datatable


   setInterval( function () {
    getdata();
   // admin_datatable();
}, 60000);
 $('#new_rep_table tbody').on( 'click', 'button', function () {
        var data = reptable.row( $(this).parents('tr') ).data();

                $('#ticket_no').val(data['ticket_no']);
                $('#store').val(data['store']);
                $('#str_desc').val(data['str_code']);
                $('#crtd_by').val(data['full_name']);
                $('#date_createdx').val(data['date_created']);
                $('#concern').val(data['concern']);
                $('#tos').val(data['service_desc']);
                $('#message').val(data['subject']);
                $('#sub_num').val(data['sub_id']);



  $('#newrpt_Modal').modal('show');
  $('#action').val("Update");
  $('#operation').val("Save and Reply"); 

var tid=$(this).parent().siblings(':first').html();
$('#tick_title').text("Ticker Number: "+tid+"");

getinfo(tid, 'remarks', user_id);
// console.log(tid)

// $('#itsup').change(function(e) { 
//     e.preventDefault();
//     let Dataxxx = $(this).val(); // Correct way to get value in jQuery
//     alert(Dataxxx);
//   });

        });

  $('#userModal').modal({"show": true, "backdrop": 'static'});

  }; //end of function 

  $('#ModalDate_close').datetimepicker();
  $('#date_refNo').datetimepicker();


  $('#cat').on('change', function() {
      var category_id = this.value;
      $.ajax({
        url: "get_subcat.php",
        type: "POST",
        data: {
          category_id: category_id
        },
        cache: false,
        success: function(dataResult){
          $("#sub").html(dataResult);
        }
      });
    
    
  });


$(function () {
          $('#datetimepicker2, #datetimepicker3').datetimepicker()
      });


slct_isp();
slct_sub();
gtsub_id();
admin_hideshowforms();


$(document).on('submit', '#newrpt_form', function(event)
 {
  event.preventDefault();
  event.stopImmediatePropagation();
   $.ajax({
    url:"insert.php",
    method:'POST',
    data:new FormData(this),
    contentType:false,
    processData:false,
    success:function(data)
    {
     alert(data);
     $('#newrpt_form')[0].reset();
     $('#newrpt_Modal').modal('hide');
     getdata();
     location.reload(); 
    }
   });
 });
}); // end of docu.ready

$(document).on('click', '#msgbtn', function(){

$('.dv_msg').show();
$('#remarks_view').show();


if($('#msgbtn').val() == 'show'){
$('#action').val("Save and Reply");
$('#operation').val("Save and Reply");
$('#msgbtn').val("hide");
$('#msg_thread').show('slow');
}
else if($('#msgbtn').val() == 'hide'){
$('#action').val("Save");
$('#operation').val("Save and Reply");
$('#msgbtn').val("show");
$('#msg_thread').hide('slow');
}

});



</script>
