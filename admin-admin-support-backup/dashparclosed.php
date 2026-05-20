 <?php

session_start();

if ($_SESSION['login']!='true'){
    header("Location: index.php");
    exit();
}
// include 'session.php';

// ======== db  =========
include '../dbconn.php';
include '../condb.php';
$conn=new dbconfig();
// ======== header =========
$datetime = new DateTime();
$timezone = new DateTimeZone('Asia/Manila');
$datetime->setTimezone($timezone);

$query="select * from vw6 where `status` = 'PARTIALLY CLOSED'";
          $run=$conn->prepare($query);
          $run->execute();
          $rs=$run->get_result();

?>
<!DOCTYPE HTML>
<html lang="en">
 <head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- ================== PLUG INS =============================== -->
<!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->

  <link rel="stylesheet" href="../css/4bootstrap.min.css" /> 
   <link rel="stylesheet" href="../css/jquery.dataTables.min.css" />
  <link rel="stylesheet" href="../css/buttons.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="../css/bootstrap-datetimepicker.min.css">

<script src="../js/helpdesk.js"></script> 

<script src="../js/jquery-3.5.1.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/4bootstrap.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.6.4/js/buttons.colVis.min.js"></script>
<script src="//cdn.datatables.net/fixedheader/3.1.7/js/dataTables.fixedHeader.min.js"></script>
<script src="//cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
<script src="//cdn.datatables.net/responsive/2.2.6/js/responsive.bootstrap.min.js"></script>

 
  <script src="../js/jquery.dataTables.min.js"></script>
  <script src="../js/moment.min.js"></script>
  <script src="../js/bootstrap-datetimepicker.min.js"></script>


  
<style type="text/css">
  
/* fallback */
@font-face {
  font-family: 'Material Icons';
  font-style: normal;
  font-weight: 400;
  src: url(../icon/flUhRq6tzZclQEJ-Vdg-IuiaDsNc.woff2) format('woff2');
}

.material-icons {
  font-family: 'Material Icons';
  font-weight: normal;
  font-style: normal;
  font-size: 24px;
  line-height: 1;
  letter-spacing: normal;
  text-transform: none;
  display: inline-block;
  white-space: nowrap;
  word-wrap: normal;
  direction: ltr;
  -webkit-font-feature-settings: 'liga';
  -webkit-font-smoothing: antialiased;
}


</style>



  <!-- bootsrap ver 3.3.7 -->



  <!--   ================================================== -->

  <!--   ==================END OF PLUG INS========================= -->

 </head>

 <body class="demo">

  <div id="demo-content">


    <div id="loader-wrapper">
      <div id="loader"></div>

      <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>

    </div>


  </div>



<!-- navbar -->




          <!-- table field -->


   <style>
table, td, th {
   border: 1px solid black;
  width: 250px;
  }
div.container {
    width: 95%;
}
.hidden{
  visibility:hidden;
        }
</style>

<div class="container-fluid">

  <div id="wrapper">

    <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">REPORTS FOR CLOSED</h1>
                        <ol class="breadcrumb mb-4">
                       <?php
                            
                                          $ret=mysqli_query($con," SELECT * FROM vw_pclose");
                                          $row=mysqli_fetch_array($ret);
                                          $t_pclose=$row['t_pclose'];
                                          
                                          ?>
                            <li class="breadcrumb-item active">As of <?php echo $datetime->format('m/d/Y g:i A');?> There are "<?php echo $t_pclose;?>" Need to be checked. </li>
                        </ol>
 
                        </div>

<div id="page-wrapper">      

    <div class="container-fluid">
      <style type="text/css">
        
    
        th { font-size: 12px; }
        td { font-size: 11px; }
      </style>
   <div class="table-responsive">
    <br />
    <div align="right">
    <button id="export_excel">Export to Excel</button>

   </div>
        <br />
   
    <br />
    <table id="report_data" class="display table-condensed">
     <thead class="bg-warning">
      <tr class="">
       <th>TICKET#</th>
       <th>STORE</th>
       <th>DATE CREATED</th>
       <th>CONCERN</th>
       <th>VIA</th>
       <th>STATUS</th>
       <!-- <th>ID</th> -->
       <th>I.T SUPPORT</th>
       <th>CATEGORY</th>
       <th>SUBCATEGORY</th>
       <th>DATE CLOSED</th>
       <th>CHECKED BY</th>
       <th>DAYS COMPLETED</th>
       <th>WORK OUTPUT</th>
       <th>UPDATE</th>
 <!--       <th>DELETE</th> -->

       <tbody>
         
         <?php
while ($row=$rs->fetch_assoc())
{
$empnum =$row['ticket_no'];
$clsby=$row['close_by'];
$name=$conn->getuserinfo($clsby);
?>
<tr class ="" style="text-align:center;">
<td align="center"><?php echo $row['ticket_no']; ?></td>
<td align="center"><?php echo $row['str_code']; ?></td>
<td align="center"><?php echo date('m/d/Y H:i',strtotime($row["date_created"])); ?></td>
<td align="center"><?php echo $row['concern']; ?></td>
<td align="center"><?php echo $row['via']; ?></td>
<td align="center"><?php echo $row['status']; ?></td>
<td align="center"><?php echo $row['it_desc']; ?></td>  
<td align="center"><?php echo $row['category']; ?></td>
<td align="center"><?php echo $row['sub_category']; ?></td>
<td align="center"><?php echo date('m/d/Y H:i',strtotime($row["date_closed"])); ?></td>
<td align="center"><?php echo $name['it_desc']; ?></td>
<td align="center"><?php echo $row['tdc']; ?></td>
<td align="center"><?php echo $row['remarks']; ?></td>
<td align="center"><?php echo '<button type="button" name="update" onclick=" id="'.$row["ticket_no"].'" class="btn btn-warning btn-xs update "><i class="material-icons">update</i></button>'; ?></td>


</tr>
<?php 
}?>

       </tbody>

      </tr>
     </thead>
    </table>
     </div>
   </div>
  </div>
</div>
</div>



 </body>
</html>
    <!-- End of table field -->


    <!-- Start of Modal -->
<!--  -->


  <div class="modal fade bd-example-modal-lg" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
  <form method="post" id="report_form" enctype="multipart/form-data">
   <div class="modal-content">
    <div class="modal-header">
     <button type="button" class="close" data-dismiss="modal">&times;</button>
     <h4 class="modal-title">ADD REPORT</h4>
    </div>


<div class="modal-body">
<form>
              <div class="container">


                   <div class="form-row">
    <div class="form-group col-md-8">
       <label>STORE</label>
      <input type="hidden" name="store" id="store" class="form-control" required>
     <select class="form-control" type="text" id="selectstore" ></select>

    </div>

    <input type = "hidden" class="form-control" name = "ticket_no" id="ticket_no" >


    <div class="form-group col-md-4">
      
     <label>DATE CREATED</label>
     <input type="text" name="date_created" id="date_created" class="form-control" 
     value="<?php echo $datetime->format('m/d/Y g:i A');?>" />
    </div>


    <div class="form-group col-md-12">
    <label>CONCERN</label>
     <textarea name="concern" id="concern" class="form-control" placeholder="Input Concern" 
     style="text-transform:uppercase" onkeyup="this.value = this.value.toUpperCase();"></textarea>
     </div>

    <div class="form-group col-md-4">
      <label>VIA</label>
     <select class="form-control" name="via" id="via" required>
     <option value=""> &larr; VIA &rarr;</option>
           <?php
                    $query="select * from via_main";
                    $run=$conn->prepare($query);
                    $run->execute();
                    $rs=$run->get_result();
                    while ($res=$rs->fetch_assoc()) {
                    ?>
                    <option><?=$res['via_desc'] ?></option>
                    <?php }?>
                    ?>   
    </select>
  </div>
    <div class="form-group col-md-8">
      <label>I.T SUPPORT</label>
    <input type="hidden" name="itsup" id="itsup" class="form-control" required="">
     <select class="form-control " id="select_tech" ></select>
   </div>
    <div class="form-group col-md-4">

     <label>CATEGORY</label>
     <input type="hidden" name="cat" id="cat" class="form-control" required>
     <select class="form-control " id="selectcategory" ></select>
   </div>
    <div class="form-group col-md-4">

    <label>SUB CATEGORY</label>
     <input type="hidden" name="sub" id="sub" class="form-control" required>
     <select class="form-control " id="select_subcategory" ></select>
    </div>
    <div class="form-group col-md-4">
        <label>STATUS</label>
    <select class = "form-control" name= "status" id="status" required>
    <option value=""> &larr; Status &rarr;</option>
           <?php
                    $query="select * from status";
                    $run=$conn->prepare($query);
                    $run->execute();
                    $rs=$run->get_result();
                    while ($res=$rs->fetch_assoc()) {
                    ?>
                    <option><?=$res['stat_desc'] ?></option>
                    <?php }?>
                    ?>   
    </select>
    <script type="text/javascript">
      hideshowforms();
    </script>
  </div>
    <div class="form-group col-md-4">
      <label id="dateclabel" class="hidden">DATE CLOSED</label>
     <input type ="text" name="date_closed" id="date_closed" class="form-control" placeholder="Inpute date">
     </div>

    <div class="form-group col-md-8">

     <label id="clby_label" class="hidden">CLOSED BY</label>
      <input type="hidden" name="close_by" id="close_by" class="form-control">
      <select class="form-control " id="select_cl" ></select>
    </div>

    <div class="form-group col-md-12">

     <label>REMARKS / WORK OUTPUT</label>
     <textarea name="remarks" id="remarks" class="form-control" placeholder="Remarks" required=""></textarea> 

</div>

  <div class="modal-footer">
     <!-- <input type="hidden" name="t_id" id="t_id" /> -->
     <input type="hidden" name="operation" id="operation" />
     <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
     <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
     <br />
     <br />
     <br />
     <br />

    </div>





                  </div>
        
        </form>



      </div>

    

<!-- modal addnew button -->

<script type='text/javascript'>
    $( document ).ready(function() {
      $('#date_created, #date_closed').datetimepicker({
            widgetPositioning:{
        horizontal: 'auto',
        vertical: 'bottom'
    }
    
      });
    // });


// $(document).ready(function(){


 $('#add_button').click(function(){
  $('#report_form')[0].reset();
  $('.modal-title').text("ADD REPORT");
  $('#action').val("Add");
  $('#operation').val("Add");
  $("#userModal").on('hidden.bs.modal', function(){
    // location.reload();


  });

    // 
  populate_branch();
  populate_tech();
  populatedrop(); // add
  select_cl();

 });
      // END MODAL


// data table

moment.updateLocale(moment.locale(), { invalidDate: "" }); //sets null value

var dataTable = $('#report_data').removeAttr('width').DataTable({

  "dom": 'Blfrtip',
        columnDefs: [
            {
                targets: 1,
                className: 'noVis'
            }
        ],
        buttons: [
            {
                extend: 'colvis',
                columns: ':not(.noVis)'
            }
        ],
  // "processing":true,
  "fixedHeader": true,
  "responsive": true,
  "select":true,
    "order":[0],
    "search": {
  },
  // "ajax":{
  //  url:"fetch.php",
  //  type:"POST"
  // },
      "columnDefs": [
    { "searchable": false, "targets": 3 }
  ],   
  "columnDefs":[
   {
    "targets":[0, 10, 11],
    "orderable":false,
    "sortable":true,
   },
  ],
   "columnDefs": [
            {

                targets: [9,11],
                "width": "2%",
                render: function ( data, type, row) {
                    if(type === 'display'){
                        if(data == '01/01/1970 01:00'){
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
        ],



    rowCallback: function(row, data, index){
    if(data[5].toUpperCase() == 'OPEN'){
      $(row).find('td:eq(0)').css('color', 'red');
      $(row).find('td:eq(1)').css('color', 'red');
      $(row).find('td:eq(2)').css('color', 'red');
      $(row).find('td:eq(3)').css('color', 'red');
      $(row).find('td:eq(4)').css('color', 'red');
      $(row).find('td:eq(5)').css('color', 'red');
      $(row).find('td:eq(6)').css('color', 'red');
      $(row).find('td:eq(7)').css('color', 'red');
      $(row).find('td:eq(8)').css('color', 'red');
      $(row).find('td:eq(9)').css('color', 'red');
    }
    else if (data[5].toUpperCase() == 'OPEN WITH FIX ASSET'){
      $(row).find('td:eq(0)').css('color', 'red');
      $(row).find('td:eq(1)').css('color', 'red');
      $(row).find('td:eq(2)').css('color', 'red');
      $(row).find('td:eq(3)').css('color', 'red');
      $(row).find('td:eq(4)').css('color', 'red');
      $(row).find('td:eq(5)').css('color', 'red');
      $(row).find('td:eq(6)').css('color', 'red');
      $(row).find('td:eq(7)').css('color', 'red');
      $(row).find('td:eq(8)').css('color', 'red');
      $(row).find('td:eq(9)').css('color', 'red');
    }
        else if (data[5].toUpperCase() == 'CLOSED'){
      $(row).find('td:eq(0)').css('color', 'green');
      $(row).find('td:eq(1)').css('color', 'green');
      $(row).find('td:eq(2)').css('color', 'green');
      $(row).find('td:eq(3)').css('color', 'green');
      $(row).find('td:eq(4)').css('color', 'green');
      $(row).find('td:eq(5)').css('color', 'green');
      $(row).find('td:eq(6)').css('color', 'green');
      $(row).find('td:eq(7)').css('color', 'green');
      $(row).find('td:eq(8)').css('color', 'green');
      $(row).find('td:eq(9)').css('color', 'green');
      $(row).find('td:eq(10)').css('color', 'green');
      $(row).find('td:eq(11)').css('color', 'green');
      $(row).find('td:eq(12)').css('color', 'green');
      $(row).find('td:eq(13)').css('color', 'green');
    }

  }, // raw close bracket
 }); // close bracket main

 // submit / add
 $(document).on('submit', '#report_form', function(event)
 {
 // alert("1");
  event.preventDefault();
  var TicketNumber = $('#ticket_no').val();
  var Store = $('#store').val();
  var DateCreated = $('#date_created').val();
  var Concern = $('#concern').val();
  var Status = $('#status').val();
  var Via = $('#via').val();
  var ItSupport = $('#itsup').val();
  var cat_id = $('#cat').val();
  var sub_id = $('#sub').val();
  var DateClosed = $('#date_closed').val();
  var CloseBy = $('#close_by').val();
  var remarks = $('#remarks').val();


  var today = new Date();
  DateCreated = new Date(DateCreated);
  if (DateCreated>today){
    alert('Invalid date');
    return false
  }

  if(Store != '' && DateCreated != '' && Concern != '' && Status != '' && Via != '' && ItSupport != '' && cat_id != '' &&sub_id != '')
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
     $('#report_form')[0].reset();
     $('#userModal').modal('hide');
     location.reload();
    }
   });
  }
  else
  {
   alert("All Fields are Required");
  }

 });

   function getinfo(tid,gettype){
    var Cid =tid;
    var gettype = gettype;
$.ajax({
    type:"GET",
      url:"info.php",
      data:{tid:Cid,gettype:gettype},
      datatype:'JSON',
    success:function(data)
    {

      var obj = JSON.parse(data);
       // console.log(obj[0].id);
      if (gettype == 'tech_info'){
 document.getElementById("itsup").value =obj[0].id;
 $("#userModal #select_tech").append('<option value ='+ obj[0].desc+'>' + obj[0].desc + '</option>');
   
    }
    else if (gettype == 'branch_info'){
      document.getElementById("store").value =obj[0].id;
      // console.log(obj[0].id);
 $("#userModal #selectstore").append('<option value ='+ obj[0].desc+'>' + obj[0].desc + '</option>');

    }
    else if (gettype == 'cat_info'){
      document.getElementById("cat").value =obj[0].id;
 $("#userModal #selectcategory").append('<option value ='+ obj[0].desc+'>' + obj[0].desc + '</option>');

    }
    else if (gettype == 'sub_info'){
      document.getElementById("sub").value =obj[0].id;
 $("#userModal #select_subcategory").append('<option value ='+ obj[0].desc+'>' + obj[0].desc + '</option>');

    }
    else if (gettype == 'cl_info'){
      document.getElementById("close_by").value =obj[0].id;
 $("#userModal #select_cl").append('<option value ='+ obj[0].desc+'>' + obj[0].desc + '</option>');

    }
    }
   });
  }



 // update/edit report's data

 $("#userModal #selectstore").change(function () {
  var str = "";
  $("#userModal #selectstore option:selected").each(function () {
  str += $(this).val();
  });
  $("#store").val(str);
  }).change();

$("#userModal #select_tech").change(function () {
  var str = "";
  $("#userModal #select_tech option:selected").each(function () {
  str += $(this).val();
  });
  $("#itsup").val(str);
  }).change();


   $("#userModal #selectcategory").change(function () {
  var str = "";
  $("#userModal #selectcategory option:selected").each(function () {
  str += $(this).val();
  });
  $("#cat").val(str);
 populatedropsub(str);
 
  }).change();



   $("#userModal #select_subcategory").change(function () {
  var str = "";
  $("#userModal #select_subcategory option:selected").each(function () {
  str += $(this).val();
  });
  $("#sub").val(str);
  }).change();


    $("#userModal #select_cl").change(function () {
  var str = "";
  $("#userModal #select_cl option:selected").each(function () {
  str += $(this).val();
  });
  $("#close_by").val(str);
  }).change();




 $(document).on('click', '.update', function(){

 var confirmedit= confirm('Are you sure you want to edit ticketno:'+ $(this).parent().siblings(':first').html());
 if (confirmedit){
   
var tid=$(this).parent().siblings(':first').html();

$('#ticket_no').val($(this).parent().siblings(':first').html());
getinfo(tid,'branch_info');
populate_branch('edit');
// $('#store').val($(this).parent().siblings(':nth-of-type(2)').html());

$('#date_created').val($(this).parent().siblings(':nth-of-type(3)').html());
$('#concern').val($(this).parent().siblings(':nth-of-type(4)').html());
$('#via').val($(this).parent().siblings(':nth-of-type(5)').html());
$('#status').val($(this).parent().siblings(':nth-of-type(6)').html());

getinfo(tid,'tech_info');
populate_tech('edit');
getinfo(tid,'cat_info');
populatedrop('edit');
getinfo(tid, 'sub_info');
populatedropsub('edit');
getinfo(tid, 'cl_info');
hideshowforms();

$('#date_closed').val($(this).parent().siblings(':nth-of-type(10)').html());
 select_cl('edit');
$('#remarks').val($(this).parent().siblings(':nth-of-type(13)').html());


 
 $('#action').val("Edit");
 $('#operation').val("Edit"); 
$('#userModal').modal('show');}
   $("#userModal").on('hidden.bs.modal', function(){
    location.reload();
  });

 });
 
// delete reports data

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
     location.reload();
    }
   });
  }
  else
  {
   return false; 
  }
 });

 
});//document ready close



</script>




<script type="text/javascript">
  
$(document).ready(function() {
  
  setTimeout(function(){
    $('body').addClass('loaded');
  }, 1000);
  
});

</script>

<script src="../table2excel/src/jquery.table2excel.js" type="text/javascript"></script>
<script>
    $("#export_excel").click(function(){
      alert('okay');
        $("#report_data").table2excel({

            // exclude CSS class
            exclude: ".noExl",
              name: "Pending Cases",
              filename: "Pending Cases" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
              fileext: ".xls",
              exclude_img: true,
              exclude_links: true,
              exclude_inputs: true 
    });
    });

</script>

