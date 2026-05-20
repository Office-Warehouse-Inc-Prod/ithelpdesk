<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>I.T HARDWARE</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>  
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="assets/css/main.css" />
  <link rel="stylesheet" href="assets/css/datepicker.css" />
  <script src="assets/js/bootstrap-datepicker.js"></script>
  <script src="https://kit.fontawesome.com/426b4bab4c.js" crossorigin="anonymous"></script>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

  <!-- <link rel="preconnect" href="https://fonts.gstatic.com"> -->
<!-- <link href="https://fonts.googleapis.com/css2?family=Arvo&display=swap" rel="stylesheet"> -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arvo">

<?php 
include 'function.php';
$conn=new dbconfig(); ?>


  <style>

label {
  font-size: 12px;
}

form-control {
  border-style: solid;
}

  </style>
 </head>
 <body>
  <div class="col-md-12">
  <h1 class="animate__animated animate__fadeInUp" align="center">FIX ASSET MONITORING</h1>
   <br />
   <div class="table-responsive">
    <br />
    <div align="right">
     <button type="button" id="add_button" data-toggle="modal" data-target="#userModal" class="btn btn-info btn-lg">Add</button>
    </div>
    <br /><br />
    <table id="user_data" class="table table-bordered table-striped table-responsive content-table animate__animated animate__fadeIn">
     <thead>
      <tr>
       <!-- <th width="10%">Image</th> -->
       <th>TAG#</th>
       <th>DESCRIPTION</th>
       <th>BRAND</th>
       <th>PO#</th>
       <th>PO DATE</th>
       <th>S.I#</th>
       <th>S.I DATE</th>
       <th>SERIAL#</th>
       <th>DATE RECEIVED</th>
       <th>ISSUED TO</th>
       <th>ISSUED DATE</th>
       <th>BRANCH CODE / DEPT CODE</th>
       <th>NAV CODE</th>
       <th>UPDATE</th>
      </tr>
     </thead>
    </table>
    
   </div>
  </div>
 </body>
</html>

<div class="col-md-12">
<div id="userModal" class="modal fade">
 <div class="modal-dialog">
  <form method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">
    <div class="modal-header">
     <button type="button" class="close" data-dismiss="modal">&times;</button>
     <h4 class="modal-title">ADD ITEM</h4>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-7 col-md-4">
     <label>FIX ASSET TAG #</label>
     <input type="text" name="tag_id" id="tag_id" class="form-control"  autocomplete="off" />
   </div>
   <div class="col-7 col-md-4">
     <label>TAG DESCRIPTION:</label>
     <input type="text" name="tag_desc" id="tag_desc" class="form-control"  autocomplete="off" />
    

     <!-- <input type="text" name="components" id="components" class="form-control"  autocomplete="off" /> -->
    </div>

    <div class="col-7 col-md-4">
   <label>BRAND:</label>
     <input type="text" name="tag_brand" id="tag_brand" class="form-control"  autocomplete="off" />
   </div>

 </div> <!--end of row 1-->

 <br />

 <div class="row">
  <div class="col-7 col-md-8">
    <label>PO#:</label>
     <input type="text" name="po_num" id="po_num" class="form-control"   autocomplete="off" />
     </div>
  
     <div class="col-7 col-md-4">
      <label>P.O Date:</label>
        <div class="input-append date" id="date1" >
          <input class="form-control" id="po_date" name="po_date" type="date" value=""  autocomplete="off">
          <span class="add-on"><i class="icon-th"></i></span>
        </div>
      </div>
    </div>
<div class="row">
  
      <div class="col-7 col-md-8">
          <label>S.I #:</label>
           <input type="text" name="si_num" id="si_num" class="form-control"   autocomplete="off" />
     </div>

      <div class="col-7 col-md-4">
    <label>S.I Date:</label>
     <div class="input-append date" id="date2" >
          <input class="form-control" id="si_date" name="si_date" type="date" value=""  autocomplete="off">
          <span class="add-on"><i class="icon-th"></i></span>
        </div>
      </div>


</div>
    <br />
    <div class="row">
   

     <div class="col-7 col-md-8">
          <label>SERIAL#:</label>
           <input type="text" name="serial_num" id="serial_num" class="form-control"  style="text-transform:uppercase" autocomplete="off" />
     </div> 

     <div class="col-7 col-md-4">
    <label>DATE RECEIVED:</label>
     <div class="input-append date" id="date3" >
          <input class="form-control" id="date_received" name="date_received" type="date" value=""  autocomplete="off">
          <span class="add-on"><i class="icon-th"></i></span>
        </div>
      </div>


     </div>   
 <br />
         <div class="row">

        <div class="col-7 col-md-3">
          <label>ISSUED TO:</label>
           <input type="text" name="dimension" id="dimension" class="form-control"   autocomplete="off" />
           </div> 


         <div class="col-7 col-md-4">
        <label>DATE ISSUED:</label>
         <div class="input-append date" id="date3" >
              <input class="form-control" id="date_issued" name="date_issued" type="date" value=""  autocomplete="off">
              <span class="add-on"><i class="icon-th"></i></span>
            </div>
          </div>



          <div class="col-7 col-md-2">
          <label style="font-size: 12px;">BrachCode:</label>
           <input type="text" name="branchCd" id="branchCd" class="form-control"   autocomplete="off" />
           </div> 

         <div class="col-7 col-md-3">
          <label>NAV CODE:</label>
           <input type="text" name="navCd" id="navCd" class="form-control"   autocomplete="off" />
           </div> 

      </div>
     <br />       

    <div class="modal-footer">
     <input type="hidden" name="user_id" id="user_id" />
     <input type="hidden" name="operation" id="operation" />
     <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
   </div>
  </form>
 </div>
</div>


<script type="text/javascript" language="javascript" >
$(document).ready(function(){
 $('#add_button').click(function(){
  $('#user_form')[0].reset();
  $('.modal-title').text("Add Asset");
  $('#action').val("Add");
  $('#operation').val("Add");
  // $('#user_uploaded_image').html('');
 });


         // $(function () {
         //     $('#dop').datepicker()
         // });


//  $('.datepicker').datepicker({
//     format: 'mm/dd/yyyy'
//     // startDate: '-3d'
// });
 
 var dataTable = $('#user_data').DataTable({
  "processing":true,
  "serverSide":true,
  "order":[],
  "ajax":{
   url:"fetch.php",
   type:"POST"
  },
    "columnDefs": [
            {

                targets: [4,6,8,10],
                render: function ( data, type, row) {
                    if(type === 'display'){
                        if(data == '01/01/1970'){
                          data = ''
                        }
                        else if(data<0){
                          data = ''
                        }
                        else if(data == '0'){
                          data = 'Solve Immediately'
                        }
                }
                return data;
              }
            }
        ]

 });

 $(document).on('submit', '#user_form', function(event){
  event.preventDefault();
  var KEYTAG = $('#tag_id').val();
  var Components = $('#tag_desc').val();
  var Brand = $('#tag_brand').val();
  var SerialNum = $('#serial_num').val();
  // var extension = $('#user_image').val().split('.').pop().toLowerCase();
  // if(extension != '')
  // {
  //  if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
  //  {
  //   alert("Invalid Image File");
  //   $('#user_image').val('');
  //   return false;
  //  }
  // } 
  if(KEYTAG != '' && Components != '')
  {
   $.ajax({
    url:"insert.php",
    method:'POST',
    data:new FormData(this),
    contentType:false,
    processData:false,
    success:function(data)
    {
     alert(data);
     $('#user_form')[0].reset();
     $('#userModal').modal('hide');
     location.reload();
    }
   });
  }
  else
  {
   alert("Both Fields are Required");
  }
 });
 
 $(document).on('click', '.update', function(){
  var user_id = $(this).attr("id");
  console.log(user_id)
  $.ajax({
   url:"fetch_single.php",
   method:"POST",
   data:{user_id:user_id},
   dataType:"json",
   success:function(data)
   {
    $('#userModal').modal('show');
    $('#tag_id').val(data.tag_id);
    $('#tag_desc').val(data.tag_desc);
    $('#tag_brand').val(data.tag_brand);
    $('#po_num').val(data.po_num);
    $('#po_date').val(data.po_date);
    $('#si_num').val(data.si_num);
    $('#si_date').val(data.si_date);
    $('#serial_num').val(data.serial_num);
    $('#date_received').val(data.date_received);
    $('#dimension').val(data.dimension);
    $('#date_issued').val(data.date_issued);
    $('#branchCd').val(data.branchCd);
    $('#navCd').val(data.navCd);
    $('.modal-title').text("Edit");
    $('#user_id').val(user_id);
    // $('#user_uploaded_image').html(data.user_image);
    $('#action').val("Edit");
    $('#operation').val("Edit");
   }
  })
 });
 
 $(document).on('click', '.delete', function(){
  var user_id = $(this).attr("id");
  if(confirm("Are you sure you want to delete this?"))
  {
   $.ajax({
    url:"delete.php",
    method:"POST",
    data:{user_id:user_id},
    success:function(data)
    {
     alert(data);
     dataTable.ajax.reload();
    }
   });
  }
  else
  {
   return false; 
  }
 });
 
 
});


</script>