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

$query="select * from vw_netpanel WHERE
vw_netpanel.sub_id IN ('15', '28','34','35')";
          $run=$conn->prepare($query);
          $run->execute();
          $rs=$run->get_result(); 

?>
<!DOCTYPE HTML>
<!-- <html lang="en" xmlns="http://www.w3.org/1999/xhtml"> -->
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- ================== PLUG INS =============================== -->
   <!-- bootsrap ver 4 -->
<script src="../js/jquery-3.5.1.js"></script>
<link rel="stylesheet" type="text/css" href="../css/main.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arvo" />
<link rel="stylesheet" href="../css/4bootstrap.min.css" />
<script src="../js/moment.min.js"></script>
<link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
<link rel="stylesheet" href="../css/jquery.dataTables.min.css" />  
<link rel="stylesheet" type="text/css" href="../css/responsive.dataTables.min.css" />
<script src="../js/bootstrap-datetimepicker.min.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/4bootstrap.min.js"></script>
<script src="../js/jquery.dataTables.min.js"></script>
<script src="../js/ellipsis.js"></script>
<script src="../js/dataTables.responsive.min.js"></script>
<script src="../js/helpdesk.js"></script> 
<script src="../js/jquery.timeago.js"></script>


<!-- amcharts -->

<style type="text/css">
  .tdremarks{
  font-size: 10px;
    max-width: 0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

/* Important part */
.modal-dialog{
    overflow-y: initial !important
}
.modal-body{
    height: 80vh;
    overflow-y: auto;
}
#remark_view{
    height:200px;
    width:200px;
    position: relative;
    overflow: auto; 
}
</style>

  <!--   ================================================== -->

  <!--   ==================END OF PLUG INS========================= -->

 </head>

 <body>
<?php include '../includes/header.php' ?>
<br><br><br>
<div class="container-fluid">

<div class="row">
  

<div class="card2 col-md-12">
  <h5 class="card-header">NETWORK</h5>
  <div class="card-body">

<div class=" col-xl-12 col-md-12">

    <button type="button" id="add_button" style="float: right;" class=" second btn btn-xl btn-success" data-toggle="modal" data-target="#userModal"> <i class="medium material-icons" style="float: right;">ADD REPORT add</i></button>
</div>
    </div>
<br />
  <div class="col-xl-12 col-md-12">

    <table id="net_data" class="table table-striped table-bordered table-responsive table-condensed">
     <thead class="" id="table_main">
      <tr>
       <th class="text-white">#</th>
       <th class="text-white">STORE</th>
       <th class="text-white">DATE CREATED</th>
       <th class="text-white">CONCERN</th>
       <th class="text-white">VIA</th>
       <th class="text-white">STATUS</th>
       <!-- <th>ID</th> -->
       <th class="text-white">I.T SUPPORT</th>
       <th class="text-white">CATEGORY</th>
       <th class="text-white">SUBCATEGORY</th>
       <th class="text-white">Service Provider</th>
       <th class="text-white">RefNo:</th>
       <th class="text-white">DATE CLOSED</th>
       <!-- <th class="text-white">CHECKED BY</th> -->
       <th class="text-white">DAYS COMPLETED</th>
       <th class="crmks text-white" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">WORK OUTPUT</th>
       <th class="text-white">UPDATE</th>
       <th class="text-white">DELETE</th>

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
<td align="center"><?php echo $row['isp_shortDesc']; ?></td>
<td align="center"><?php echo $row['refNo']; ?></td>
<td align="center"><?php echo date('m/d/Y H:i',strtotime($row["date_closed"])); ?></td>
<!-- <td align="center"><?php echo $name['it_desc']; ?></td> -->
<td align="center"><?php echo $row['tdc']; ?></td>
<td class="tdremarks" align="center"><?php echo $row['remarks']; ?></td>
<td align="center"><?php echo '<button type="button" name="network_update" onclick=" id="'.$row["ticket_no"].'" class="btn-xs1 btn network_update"><i class="material-icons">update</i></button>'; ?></td>
<td align="center"><?php echo '<button type="button" name="delete" id="'.$row["ticket_no"].'" class="btn btn-danger btn-xs delete"><i class="material-icons">delete</i></button>'; ?></td>


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
</clear>


 </body>


</html>
    <!-- End of table field -->


    <!-- Start of Modal -->

<div class="col-md-6">



<div class="container-fluid">

  <div class="modal fade bd-example-modal-lg" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
  <form method="post" id="net_form" enctype="multipart/form-data">
   <div class="modal-content">
    <div class="modal-header">
     <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
     <h4 class="modal-title">ADD REPORT</h4>
    </div>


<div class="modal-body">
<!-- <form> -->
  <div class="form-row">
    <div class="form-group col-md-8">
       <label>STORE</label>
          <select class="form-control" name="store" id="store" data-show-subtext="true" data-live-search="true" required >
             <option value="">Select Store...</option>  
                   <?php
                            $query="select * from tbl_branch";
                            $run=$conn->prepare($query);
                            $run->execute();
                            $rs=$run->get_result();
                            while ($res=$rs->fetch_assoc()) {
                              $brcnhid = $res['str_num'];
                              $brnchcd = $res['str_code'].' | '.$res['str_name'];
                              $brnchdesc = "";
                            ?>

                            <option value="<?php echo $brcnhid;?>"><?= $brnchcd; ?></option>
                            <?php }?>
                            ?>   
                </select> 

    </div>
    
    <input type = "hidden" class="form-control" name = "ticket_no" id="ticket_no">


    <div class="form-group col-md-4">
      
     <label>DATE CREATED</label>
  <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
          <input type="text" name="date_created" id="date_created" class="form-control datetimepicker-input" data-target="#datetimepicker1" value="<?php echo $datetime->format('m/d/Y g:i A');?>" />
          <div class="input-group-append" data-target="#date_created" data-toggle="datetimepicker">
            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
    </div>
  </div>
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
          <select class="form-control" name="itsup" id="itsup" required>
             <option value="">Assign support...</option>  
                   <?php
                            $query="select * from it_tech WHERE itsup NOT IN ('1','4','7','8','12')";
                            $run=$conn->prepare($query);
                            $run->execute();
                            $rs=$run->get_result();
                            while ($res=$rs->fetch_assoc()) {
                              $tchid = $res['itsup'];
                              $tchdesc = $res['it_desc'];
                            ?>

                            <option value="<?php echo $tchid;?>"><?= $tchdesc; ?></option>
                            <?php }?>
                            ?>   
                </select> 
   </div>
    <div class="form-group col-md-4">

     <label>CATEGORY</label>
     <input type="hidden" name="catx" id="catx" value="3">
     <input type="text" class="form-control" name="cat_desc" id="cat_desc" readonly="" value="NETWORK" style="background-color: #fff;">
   </div>
    <div class="form-group col-md-4">

    <label>SUB CATEGORY</label>
              <select class="form-control" name="sub" id="sub">
                <option selected="">Select Subcategory...</option>
                <option value="15">VPN</option>
                <option value="28">Dial-tone</option>
                <option value="34">DSL-Local</option>
                <option value="35">DSL-VPN</option>
              </select>
    </div>
        <div class="form-group col-md-4">
      <label>Select Service Provider</label>
          <select class="form-control" name="isp" id="isp" required>
             <option value="">Select Here...</option>  
                   <?php
                            $query="SELECT * FROM tbl_isp";
                            $run=$conn->prepare($query);
                            $run->execute();
                            $rs=$run->get_result();
                            while ($res=$rs->fetch_assoc()) {
                              $ispID = $res['isp_id'];
                              $ispdesc = $res['isp_shortDesc'];
                            ?>

                            <option value="<?php echo $ispID;?>"><?= $ispdesc; ?></option>
                            <?php }?>
                            ?>   
                </select> 
   </div>
   <div class="form-group col-md-4">
     <label for="refNo">Reference#</label>
     <input type="text" class="form-control" name="refNo" id="refNo" placeholder="Reference# here...">
   </div>
    <div class="form-group col-md-4">
        <label>STATUS</label>
    <select class = "form-control" name= "status" id="status" required>
    <option value=""> &larr; Status &rarr;</option>
           <?php
                    $query="select * from status WHERE stat_id <> '2'";
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
       <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
          <input type="text" name="date_closed" id="date_closed" class="form-control datetimepicker-input" data-target="#datetimepicker2" autocomplete="off" />
          <div class="input-group-append" data-target="#date_closed" autocomplete="off" data-toggle="datetimepicker">
            <div class="input-group-text" id="ico_cal" name="ico_cal"><i class="fa fa-calendar"></i></div>
    </div>
  </div>
     </div>

    <div class="form-group col-md-8">

     <label id="clby_label" class="hidden">CLOSED BY</label>
          <select class="form-control" name="close_by" id="close_by">
             <option value="">Assign support...</option>  
                   <?php
                            $query="select * from it_tech WHERE itsup IN ('1','3','6','10','11','12')";
                            $run=$conn->prepare($query);
                            $run->execute();
                            $rs=$run->get_result();
                            while ($res=$rs->fetch_assoc()) {
                              $tchid = $res['itsup'];
                              $tchdesc = $res['it_desc'];
                            ?>

                            <option value="<?php echo $tchid;?>"><?= $tchdesc; ?></option>
                            <?php }?>
                            ?>   
                </select> 
    </div>

    <div class="form-group col-md-12">
    <label for="remarks_view">Remarks History:</label>
    <div class="container_remarks" >
    <div id="remarks_view"><ul></ul></div>
    </div>

<br/>
     <label>Add Remarks:</label>
     <textarea name="remarks" id="remarks" class="form-control" placeholder="Add remarks" required=""></textarea> 



</div>

  <div class="modal-footer">
     <input type="hidden" name="operation" id="operation" />
     <input type="submit" name="action" id="action" class="btn btn-success float-right" value="Add" />
     <button type="button" name="btnClose" id="btnClose" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
     <br />
     <br />
     <br />
     <br />

    </div>

  </div>
</div>
</form>

</div>

<!-- modal addnew button -->

<script type='text/javascript'>
    $( document ).ready(function() {
 $('#add_button').click(function(){
  $('#net_form')[0].reset();
  $('.modal-title').text("ADD REPORT");
  $('#action').val("Add");
  $('#operation').val("Add");
  $("#userModal").on('hidden.bs.modal', function(){
    // location.reload();


  });


$('#date_closed').hide();
$('#close_by').hide();
$('#ico_cal').hide();


    $('#date_created, #date_closed').datetimepicker({
        widgetPositioning:{
    horizontal: 'auto',
    vertical: 'bottom'
      }

  });

 });
      // END MODAL


// data table

moment.updateLocale(moment.locale(), { invalidDate: "" }); //sets null value X

var dataTable = $('#net_data').removeAttr('width').DataTable({

  "fixedHeader": true,
  "responsive": true,
  "select":true,
    "order":[0],
    "search": {
  },
  "columnDefs":[
   {
    "targets":[0, 10, 11],
    "orderable":false,
    "sortable":true,
   },
  ],
   "columnDefs": [
            {

                targets: [11,12],
                "width": "2%",
                render: function ( data, type, row) {
                    if(type === 'display'){
                        if(data == '01/01/1970 08:00'){
                          data = ''
                        }
                       else if(data == '01/01/1970 01:00'){
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

  },

  //   "columnDefs":[
  //     "targets": 12,
  //     "render": $.fn.dataTable.render.ellipsis( 17, true )
  // ], 



 }); // close bracket main

 // submit / add
 $(document).on('submit', '#net_form', function(event)
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
    url:"network/net_insert.php",
    method:'POST',
    data:new FormData(this),
    contentType:false,
    processData:false,
    cache: false,
    success:function(data)
    {
     alert(data);
     $('#net_form')[0].reset();
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
    // $('#select_tech').empty();
      var obj = JSON.parse(data);
       // console.log(obj[0].id);
if (gettype == 'remarks'){
          // document.getElementById("name").innerHTML = obj[0].desc ;
      //console.log(obj);
    // var  rem =["aa","bb"];
    $('#remarks_view').empty();
          for (var I = 0; I <= obj.length; I++)
      { 
           var dv="";
           var str = "Created on :";
            // $("#conReply").load("# conReply");
        dv += "<div class='accordion' id='accordionExample'>";
        dv += "<div class='card2'>";
        dv += "<div class='card-header' id='headingOne'>";
        dv +=  "<h6 class='mb-0'>";
        dv +=   "<button class='list-group-item list-group-item-action collapsed hdr' id='mdrb' type='button' data-toggle='collapse' data-target='#collapseOne"+ I +"' aria-expanded='false' aria-controls='collapseOne"+ I +"'>"+'“Remarks by:'+" "+obj[I].tech+  
        "</button>";

        dv += "</h6>";
        dv += "</div>";
        dv += "</div>";
        dv += "</div>";
        dv += "<div id='collapseOne"+ I +"' class='collapse show' aria-labelledby='headingOne' data-parent='#accordionExample'>"
        dv += "<div class='card-body'>"+ obj[I].desc + "</div>";
        dv += "<div class='card-body'>"+ str  +"&nbsp &nbsp &nbsp &nbsp ";
        dv +=  obj[I].dt +"</div>";
        dv += "</div>";
        dv += "</div>";
      document.getElementById("remarks_view").innerHTML += dv; 
       $('#remarks_view').append('&nbsp &nbsp &nbsp <time class="timeago" datetime="'+obj[I].dt+'"></time>')
       $("time.timeago").timeago();
  }

    }

    }
   });
  }

 // update/edit report's data

 $(document).on('click', '.network_update', function(){

    $('#cat').on('change', function() {
      var category_id = this.value;
      $.ajax({
        url: "admin/get_subcat.php",
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

 var confirmedit= confirm('Are you sure you want to edit ticketno:'+ $(this).parent().siblings(':first').html());
 if (confirmedit){
   // $('#cat').empty();
var tid=$(this).parent().siblings(':first').html();

$('#ticket_no').val($(this).parent().siblings(':first').html());
// $('#store').val($(this).parent().siblings(':nth-of-type(2)').html());
$('#date_created').val($(this).parent().siblings(':nth-of-type(3)').html());
$('#concern').val($(this).parent().siblings(':nth-of-type(4)').html());
$('#via').val($(this).parent().siblings(':nth-of-type(5)').html());
$('#status').val($(this).parent().siblings(':nth-of-type(6)').html());
$('#cat_desc').val($(this).parent().siblings(':nth-of-type(8)').html());
hideshowforms();
$('#date_closed').val($(this).parent().siblings(':nth-of-type(12)').html());
$('#refNo').val($(this).parent().siblings(':nth-of-type(11)').html());

 var rsb1 = document.querySelector("#store");  //supportselect
          var option = document.createElement("option");
          option.value=0;
          option.id='tmpbrnpid';
          option.selected='selected';
          option.text = $(this).parent().siblings(':nth-of-type(2)').html();
          rsb1.add(option);

 var rsn1 = document.querySelector("#itsup");  //supportselect
          var option = document.createElement("option");
          option.value=0;
          option.id='tmpsuppid';
          option.selected='selected';
          option.text = $(this).parent().siblings(':nth-of-type(7)').html();
          rsn1.add(option);

 


 var sst1 = document.querySelector("#sub");  
          var option = document.createElement("option");
          option.value=0;
          option.id='tmpsubid';
          option.selected='selected';
          option.text = $(this).parent().siblings(':nth-of-type(9)').html();
          sst1.add(option);   


 var sct = document.querySelector("#isp");  
          var option = document.createElement("option");
          option.value=0;
          option.id='tmpispid';
          option.selected='selected';
          option.text = $(this).parent().siblings(':nth-of-type(10)').html();
          sct.add(option);

  // var sclt1 = document.querySelector("#close_by");  
  //         var option = document.createElement("option");
  //         option.value=0;
  //         option.id='tmpclby';
  //         option.selected='selected';
  //         option.text = $(this).parent().siblings(':nth-of-type(11)').html();
  //         sclt1.add(option);      


  getinfo(tid, 'remarks');


 $('#action').val("Edit");
 $('#operation').val("Edit"); 
$('#userModal').modal({"show": true, "backdrop": 'static'});}
   $("#userModal").on('hidden.bs.modal', function(){
    // location.reload();
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

  $("#btnClose").click(function(){
    $('#tmpbrnpid, #tmpsuppid, #tmpcatid, #tmpsubid').remove();
  });

 
});//document ready close



</script>




<script type="text/javascript">


  $('.count').each(function () {
    $(this).prop('Counter',0).animate({
        Counter: $(this).text()
    }, {
        duration: 4000,
        easing: 'swing',
        step: function (now) {
            $(this).text(Math.ceil(now));
        }
    });
});

</script>




