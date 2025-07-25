<?php

// ======== db  =========
include 'header.php';
include '../condb.php';
include 'chrtdashboard.php';
// include 'sub_graph_modal.php';

$conn=new dbconfig();

// ======== header =========
$datetime = new DateTime();
$timezone = new DateTimeZone('Asia/Manila');
$datetime->setTimezone($timezone);


?>
<head>
<link rel="stylesheet" href="../plugins/DataTables-1.10.25/media/css/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="../assets/Date-Time-Picker-Bootstrap-4/src/sass/bootstrap-datetimepicker-build.css" />
<script src="../assets/Date-Time-Picker-Bootstrap-4/src/js/bootstrap-datetimepicker.js"></script>
<link rel="stylesheet" href="styles.css" />
<script src="../plugins/DataTables-1.10.25/media/js/jquery.dataTables.min.js"></script>
<script src="../js/ellipsis.js"></script>
  <meta http-equiv='cache-control' content='no-cache'>
  <meta http-equiv='expires' content='0'>
  <meta http-equiv='pragma' content='no-cache'>
</head>


<div class="container-fluid mt-4">

<div id="wrapper">

<div id="layoutSidenav_content">

 
    </div>
  </div>

  <div class="row">

<div class="col-4 col-md-4 " >
            
           <div class="input-group mb-3">
             <div class="input-group-prepend">
               <span class="input-group-text" id="basic-addon1">SELECT YEAR:</span>
             </div>
                            <select class="form-control"  name="yearpicker" id="yearpicker" required>
                            <option value="2019,2020,2021,2022,2023,2024,2025">OVERALL</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025" selected>2025</option>
                           </select>
            </div>

</div>
<input type="hidden" name="slct_area" id="slct_area" value="<?php echo $_SESSION['user_id'];?>">

           <div  class="col-2 col-md-2 ">
               <button class="btn btn-info btn-xs" id="flterbutton" style="display: inline-block;"><i class="fa fa-search" aria-hidden="true"></i></button>
           </div>

   </div>


<div class=" second card-deck align-items-center mb-3">


<div class="dashcard card text-white mb-4 bg-primary border-dark" style="width: 18rem; height: 9rem;">
<div class="card-body">

<div class="card-title"><span id="card_totalrep">TOTAL TICKETS:</span> <span class="" id="count_total" style="font-size: 18px;"></span></div>
                          
</div>                                  
<div class="card-footer d-flex align-items-center justify-content-between">

                     <a class="small text-white stretched-link" id="card_totalval" href="#bottom" value="" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                          
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-danger" style="width: 18rem; height: 9rem; ">
<div class="card-body">

<div class="card-title">OPEN TICKETS: <span class="" id="count_open" style="font-size: 18px;"></span></div>
</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                       
                          <a class="small text-white stretched-link" id="card_openval" href="#bottom" value="OPEN" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-info" style="width: 18rem; height: 9rem;">
<div class="card-body">

<div class="card-title" style="font-size: 15px;">OVER DUE OPEN TICKETS: <span class="" id="count_owfa" style="font-size: 18px;"></span></div>

</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                      
                           <a class="small text-white stretched-link" id="card_openwfaval" href="#bottom" value="OPEN WITH FIX ASSET" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                      </div>

</div>

<div class="dashcard card text-white mb-4 bg-success" style="width: 18rem; height: 9rem;">
<div class="card-body">

<div class="card-title">CLOSED TICKETS: <span class="" id="count_closed" style="font-size: 18px;"></span></div>
</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" id="card_closedval" href="#bottom" value="CLOSED" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                      </div>


</div>

</div>


<div class="row " id="ovrall">

<div class="card card2 col-12 col-md-12 col-lg-6">
<h5 class="card-header text-white">Store With Open Tickets</h5>
<div class="card-body">
<div id="chartdiv5"></div>
</div>
</div>


<div class="card card2 col-12 col-md-12 col-lg-6">
<h5 class="card-header text-white">Open Tickets Per Deparment</h5>
<div class="card-body">
<div id="chartdiv2" name="chartdiv2"></div>
</div>
</div>


<div class="card card2 col-12 col-lg-12">
<h5 class="card-header text-white">Number of Escalated Tickets Per Store</h5>
<div class="card-body">
<div class="col-xl-12 col-lg-12">
</div>

<div id="chart_area"></div>
</div>     
</div>

<div class="card card2 col-12 col-lg-12">
    <h5 class="card-header text-white">Non Compliant Stores on End of Day Process (7:AM CUT OFF)</h5>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">FROM</span>
                    <input type="date" id="frompolDate" class="form-control mr-3">
                    <span class="input-group-text">TO</span>
                    <input type="date" id="topolDate" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="col-xl-12 col-lg-12">
            <!-- Additional content can go here -->
        </div>

        <div id="chart_polled">
            <!-- Chart will be rendered here -->
        </div>
    </div>     
</div>


</div>


<div class="row">


<div class="card card2">
<h5 class="card-header text-white"></h5>
<div class="card-body">





<table id="report_data" class="table table-dark table-responsive table-condensed text-center"></table>

</div>
</div>
</div>
<input type="hidden" id="myInput">
</div> <!--end of container-->




<!-- Start of Add/Edit Modal -->

<div class="col-12 col-md-12"> 
<div class="col-12 col-md-12 modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
<form method="post" id="report_form" enctype="multipart/form-data">
<div class="modal-content">
<div class="modal-header">
<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
<h4 class="modal-title" id="userModal_header" value="Add Report"></h4>
<!-- <button type="button" id="prntForm" class="btn btn-info float-right" data-dismiss="modal"><i class="fas fa-print"></i></button> -->
</div>


<div class="modal-body">
<!-- <form> -->

<div class="row">
<div class="form-group col-12 col-md-6">
<label>STORE</label>
<input type="hidden" name="str_num" id="str_num" readonly="" value="">
<select class="form-control form-control-sm" name="store" id="store" required>
<option value="">Select Store...</option>  
     <?php
              $query="select * from tbl_branch";
              $run=$conn->prepare($query);
              $run->execute();
              $rs=$run->get_result();
              while ($res=$rs->fetch_assoc()) {
                $brcnhid = $res['str_num'];
                $brnchcd = $res['str_code'].' | '.$res['str_name'];
              ?>

              <option value="<?php echo $brcnhid;?>"><?= $brnchcd; ?></option>
              <?php }?>
              ?>   
  </select> 
</div>

<input type = "hidden" class="form-control form-control-sm" name = "ticket_no" id="ticket_no">


<div class="form-group col-md-3">

<label>DATE CREATED</label>
<div class="input-group date" id="datetimepicker1" data-target-input="nearest">
<input type="text" name="date_created" id="date_created" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker1" value="<?php echo $datetime->format('m/d/Y g:i A');?>" />
<div class="input-group-append" data-target="#date_created" data-toggle="datetimepicker">
<div class="input-group-text"><i class="fa fa-calendar"></i></div>
</div>
</div>
</div>
<div class="form-group col-md-12">
<label>SUBJECT/CONCERN</label>
<textarea name="subjct" id="subjct" class="form-control form-control-sm" placeholder="Input Concern" 
style="text-transform:uppercase" onkeyup="this.value = this.value;"></textarea>
</div>

<div class="form-group col-md-3">
<label>VIA</label>
<select class="form-control form-control-sm" name="via" id="via" required>
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
    
</select>
</div>
<div class="form-group col-md-3">
<label>I.T SUPPORT</label>
<input type="hidden" name="it_num" id="it_num" readonly="">
<select class="form-control form-control-sm" name="itsup" id="itsup" required>
<option value="">Assign support...</option>  
     <?php
              $query="select * from it_tech WHERE itsup NOT IN ('4','7','8',12)";
              $run=$conn->prepare($query);
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
<div class="form-group col-md-3">

<label>CATEGORY</label>
<input type="hidden" name="cat_num" id="cat_num" readonly="">
<select class="form-control form-control-sm" name="cat" id="cat" required >
<option value=""> &larr; CATEGORY &rarr;</option>  
     <?php
              $query="select * from category";
              $run=$conn->prepare($query);
              $run->execute();
              $rs=$run->get_result();
              while ($res=$rs->fetch_assoc()) {
                $supid = $res['id'];
                $suppdesc = $res['category_name'];
              ?>

              <option value="<?php echo $supid;?>"><?= $suppdesc; ?></option>
              <?php }?>
              ?>   
</select> 
</div>
<div class="form-group col-md-3">

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
              $run=$conn->prepare($query);
              $run->execute();
              $rs=$run->get_result();
              while ($res=$rs->fetch_assoc()) {
                $ispid = $res['isp_id'];
                $ispdesc = $res['isp_shortDesc'];
              ?>

              <option value="<?php echo $ispid;?>"><?= $ispdesc; ?></option>
              <?php }?>
              ?>   
</select> 
</div>
<div class="form-group col-md-4 hide_isp">
<label id="lbl_refNo" for="refNo">Reference No:</label>
<input type="text" class="form-control form-control-sm" name="refNo" id="refNo">
</div>


<div class="form-group col-md-4 hide_isp">

<label for="date_refNo" class="hidden" id="lbl_DtRefNo">Date of RefNo</label>
<div class="input-group date" id="datetimepicker3" data-target-input="nearest">
<input type="text" name="date_refNo" id="date_refNo" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker3"/>
<div class="input-group-append" data-target="#date_created" data-toggle="datetimepicker">
<div class="input-group-text" id="ico_cal3"><i class="fa fa-calendar"></i></div>
</div>
</div>
</div>

<div class="form-group col-md-3">
<label>STATUS</label>
<select class = "form-control form-control-sm" name= "status" id="status" required>
<option value=""> &larr; Status &rarr;</option>
<?php
      $query="select * from status ";
      $run=$conn->prepare($query);
      $run->execute();
      $rs=$run->get_result();
      while ($res=$rs->fetch_assoc()) {
      ?>
      <option><?=$res['stat_desc'] ?></option>
      <?php }?>
    
</select>
</div>
<div class="form-group col-md-4 hide_cl">
<label id="dateclabel" class="hidden">DATE CLOSED</label>
<div class="input-group date" id="datetimepicker2" data-target-input="nearest">
<input type="text" name="date_closed" id="date_closed" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker2" autocomplete="off" />
<div class="input-group-append" data-target="#date_closed" autocomplete="off" data-toggle="datetimepicker">
<div class="input-group-text" id="ico_cal" name="ico_cal"><i class="fa fa-calendar"></i></div>
</div>
</div>
</div>

<div class="form-group col-md-3 hide_cl">

<label id="clby_label" class="hidden">CLOSED BY</label>
<input type="hidden" name="close_by" id="close_by" value=""> 
<input type="text" class="form-control form-control-sm" name="cl_desc" id="cl_desc" readonly="" value="">
</div>

<div class="form-group col-md-12">
<label>Work Output:</label>
<textarea name="remarks" id="remarks" class="form-control form-control-sm" placeholder="Your Workoutput" ></textarea>
</div>
<hr/>
<div class="form-group col-md-12">
<p>
<div class="row">
    
<div class=" col-6 col-md-8">
<input type="submit" name="action" id="action" class="btn btn-success" value="Add" />   
</div>
<div class=" col-6 col-md-4 ">
<button type="button" name="btnClose" id="btnClose" class="btn btn-danger float-right" data-dismiss="modal">Close</button>  
</div>
<div class="col-md-4 mt-2">
<button class="btn btn-info" type="button" name="msgbtn" id="msgbtn" value="show">
Show Message Thread
</button>   
</div>
</div>

</p>

<div class="col-12 col-md-12">
  
<div class="collapse" id="msg_thread">

<div class="row">
<div  class="col-12 col-md-12 mb-3">
       <label style="font-weight: bold;">Add Comment:</label>
<textarea name="admsg" id="addmsg" class="form-control form-control-sm" placeholder="Reply to their message or give an updates regarding on this ticket..."></textarea> 
</div>
<div class="col-12 col-md-12 mt-4 mb-2 dv_msg">
<label for="remarks_view" style="font-weight: bold;">Comment Thread:</label>
   <hr>
<div class="container_remarks" >
<div id="remarks_view"></div>
</div>
</div>

</div>


</div>

</div>


</div>

</div>

<div class="modal-footer">
<input type="hidden" name="operation" id="operation" />
<input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id'];  ?>">

</div>
</form>
</div>
</div>
</div>
</div>


<!-- modal addnew button -->

<script type='text/javascript'>
$( document ).ready(function() {
if(/Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { $("#ovrall").hide(); }

var user_id = <?= $_SESSION['user_id']; ?>

let val = '';
$('#card_totalval').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");

// alert(val);


});
$('#card_openval').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});

$('#card_openwfaval').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});
$('#card_closedval').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});

$('#myInput').on( 'input', function () {
    table.search( this.value ).draw();
} );

const yr =$("#yearpicker").val();
// const areaVal = $("#slct_area").val();

_areagraph(yr);
_overallpie(yr);
_catpie(yr);
getdata(yr);


function getdata(yr){
$.post('fetchdata/fetch_data.php',{yr:yr,mode:'dtb'},function(data){
admin_datatable(data);
},'json');
}


var table
function admin_datatable(t){
const dataset=t.rptdata;
table =  $("#report_data").DataTable({

"dom":
'<"pull-left"lf><"pull-right">tip',
// stateSave: true,
"pagingType": "full_numbers",
"bDestroy": true,
"responsive": true, "lengthChange": false, "autoWidth": false,
language: {
search: "_INPUT_",
searchPlaceholder: "Search..."
},
pageLength:10,
data: dataset,
"order": [[ 1, "Desc" ]],

columns: [

{title:"Update", data:null,"defaultContent": "<Button class='btn btn-danger' name='update'><i class='fas fa-edit'></i></Button>"},
{title:"TicketNo", data:"ticket_no","defaultContent": ""},
{title:"  Store", data:"str_code","defaultContent": ""},
{title:"Date Created", data:"date_created","defaultContent": ""},
{title:"Subject", data:"subject","defaultContent": ""},
// {title:"Concern", data:"concern","defaultContent": ""},
{title:"Via", data:"via","defaultContent": ""},
{title:"STATUS", data:"status","defaultContent": ""},
{title:"Assigned Support", data:"it_desc","defaultContent": ""},
{title:"CATEGORY", data:"category","defaultContent": ""},
{title:"SUBCATEGORY", data:"sub_category","defaultContent": ""},
{title:"DATE CLOSED", data:"date_closed","defaultContent": ""},
{title:"DAYS COMPLETION", data:"tdc","defaultContent": ""},
{title:"WORKOUTPUT", data:"remarks","defaultContent": ""}




],
"columnDefs": [
{ 

  targets: [10,11],
  "width": "2%",
  render: function ( data, type, row) {
      if(type === 'display'){
          if(data == '1 Days Unresolved'){
            data = '1 Day Unresolved'
          }
         else if(data == '01/01/1970 01:00'){
            data = 'ATTENDED'
          }
         else if(data == '01/01/1970 08:00'){
            data = 'ATTENDED'
          }
          else if(data<0){
            data =   ''
          }
          else if(data == 0){
            data = 'Solve Immediately'
          }
          else if(data == '0 Days Unresolved'){
            data = ''
          }
  }
  return data;
}
}
],


rowCallback: function(row, data, index){
if(data['status'] == 'OPEN'){
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
$(row).find('td:eq(10)').css('color', 'red');
$(row).find('td:eq(11)').css('color', 'white');
$(row).find('td:eq(12)').css('color', 'red');
}
else if (data['status'] == 'OPEN WITH FIX ASSET'){
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
$(row).find('td:eq(10)').css('color', 'red');
$(row).find('td:eq(11)').css('color', 'red');
$(row).find('td:eq(12)').css('color', 'red');
}
else if (data['status'] == 'CLOSED'){
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

});

$('#report_data tbody').on( 'click', 'button', function () {
var data = table.row( $(this).parents('tr') ).data();
$('#subjct').attr('readonly', true);
var tid=$(this).parent().siblings(':first').html();
$('#ticket_no').val(data['ticket_no']);
$('#str_num').val(data['store']);
$('#store').val(data['store']);
$('#date_created').val(data['date_created']);
$('#subjct').val(data['subject']);
$('#concern').val(data['concern']);
$('#via').val(data['via']);
$('#status').val(data['status']);
$('#it_num').val(data['itsup']);
$('#itsup').val(data['itsup']);
$('#cat_num').val(data['cat_id']);
$('#cat').val(data['cat_id']);
$('#sub_num').val(data['sub_id']);
$('#sub').val(data['sub_category']);
$('#isp_num').val(data['isp_id']);
$('#isp').val(data['isp_id']);
$('#refNo').val(data['refNo']);
$('#date_refNo').val(data['date_refNo']);
admin_hideshowforms();
$('#date_closed').val(data['date_closed']);
$('#remarks').val(data['remarks']);
$('#close_by').val(data['close_by']);
$('#cl_desc').val(data['clusers']);


unilayout_netshowmodalform();




$('#itsup').change(function(event) {

var itfrstsup = $('#it_num').val();
var itchange = this.value;
if (itfrstsup != itchange ) {
  $('#remarks').attr("placeholder", "Reason for re-assign/ Workoutput");
  $('#remarks').val("");
} else {
  $('#remarks').val(data['remarks']);
}
});


if($('#status').val() == 'CLOSED') {
$(':input[type="submit"]').prop('disabled', true); 
$('#date_created').attr('readonly', true);
$('#date_refNo').attr('readonly', true);
$('#date_closed').attr('readonly', true);
// $('#admsg').attr('readonly', true);
$('#store').prop("disabled", true);
$('#via').prop("disabled", true);
$('#status').prop("disabled", true);
$('#itsup').prop("disabled", true);
$('#cat').prop("disabled", true);
$('#sub').prop("disabled", true);
$('#isp').prop("disabled", true);
$('#remarks').attr('readonly', true);


} 
else{

$(':input[type="submit"]').prop('disabled', false); 
$('#date_created').attr('readonly', true);
$('#date_refNo').attr('readonly', true);
$('#date_closed').attr('readonly', true);
// $('#admsg').attr('readonly', true);
$('#store').prop("disabled", true);
$('#via').prop("disabled", true);
$('#status').prop("disabled", true);
$('#itsup').prop("disabled", true);
$('#cat').prop("disabled", true);
$('#sub').prop("disabled", true);
$('#isp').prop("disabled", true);
$('#remarks').attr('readonly', true);


}   


var sst = document.querySelector("#sub");  
var option = document.createElement("option");
option.value=0;
option.id='tmpsubid';
option.selected='selected';
option.text = $(this).parent().siblings(':nth-of-type(10)').html();
sst.add(option);   

// console.log(user_id)
getinfo(tid, 'remarks', user_id);

gtsub_id();

$('.modal-title').text("Ticker Number: "+tid+"");
$('#action').val("Save");
$('#operation').val("Edit"); 
$('#userModal').modal({"show": true, "backdrop": 'static'});



} );

$('#card_totalval').on('click', function() {
    // 1. Clear all existing filters (same as your openval approach)
    table.search('').columns().search('').draw();
    
    // 2. Remove any custom filters (like your status filter)
    // This is the key difference - we remove instead of adding filters
    $.fn.dataTable.ext.search = []; // Clear ALL custom filters
    
    // 3. Redraw the table completely unfiltered
    table.draw();
    
    // 4. Your existing UI code
    $('#myInput').slideToggle();
    $('html, body').animate({ scrollTop: 1600 }, 1000);
});

$('#card_openval').on('click', function() {
    // Clear existing filters
    table.search('').columns().search('').draw();
    
    // Apply status filter (column 6)
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var status = data[6]; // Status column
            return status !== "CLOSED" && 
                   status !== "SUBJECT FOR CLOSING";
        }
    );
    table.draw();
    
    // Animation code
    $('#myInput').slideToggle();
    $('html, body').animate({ scrollTop: 1600 }, 1000);
});

$('#card_openwfaval').on('click', function () {
    // Use DataTables search with a custom function to compare dates
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var dateColumn = data[10] || ""; // Use data for column 10, default to empty string

            // Extract the number of days using a regular expression
            var match = dateColumn.match(/(\d+)\s+days?/i); // Matches "1 day", "5 days", etc. (case-insensitive)

            var days = match ? parseInt(match[1]) : NaN; // Extract the number from the match

            // Check if days is a valid number
            if (isNaN(days)) {
                // Handle the case where the number of days is invalid (e.g., log an error, skip the row)
                console.error("Invalid number of days:", dateColumn);
                return false; // Skip this row
            }

            // Compare the number of days to 3
            if (days >= 4) {
                return true; // Include the row if it's r days or more
            }

            return false; // Exclude the row if it's more than 4 days
        }
    );

    table.draw();
    $.fn.dataTable.ext.search.pop();

    $('#myInput').slideToggle();
    $('html, body').animate({
        scrollTop: 1600
    }, 1000);
});


$('#card_closedval').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table.columns(6).search(val).draw();
$('#myInput').slideToggle();
    $('html, body').animate({
        scrollTop: 1600
    }, 1000);
} );

} // end of data table


$('#store_graph_modal').modal('hide'); 

// crd_btm();
// slct_isp();
// slct_sub();
// gtsub_id();
// admin_hideshowforms();  


get_card_data(yr)
function get_card_data(y){

$.post('fetchdata/fetch_data.php',{yr:y,mode:'yearch'}, function(data) {
let card_data = jQuery.parseJSON(data); 
const a = card_data;
// console.log(a)
$('#count_total').html(a[0].total_res);
$('#count_open').html(a[0].open_res);
$('#count_owfa').html(a[0].owfa_res);
$('#count_closed').html(a[0].cls_res);
});
}

$("#flterbutton").click(function() {
     const yr =$("#yearpicker").val();
    //  const area =$("#slct_area").val();
    //  console.log(yr)
     get_card_data(yr);
     getdata(yr);
  _overallpie(yr);
  _catpie(yr);
_areagraph(yr);

  
      

     // console.log(getdata(yrs))

});



$(function () {
$('#datetimepicker1, #datetimepicker2, #datetimepicker3').datetimepicker()
});




  $(document).on("submit", "#report_form", function (e) {
    e.preventDefault();
    var TicketNumber = $("#ticket_no").val();
    var Store = $("#store").val();
    var DateCreated = $("#date_created").val();
    var Concern = $("#concern").val();
    var Status = $("#status").val();
    var Via = $("#via").val();
    var ItSupport = $("#itsup").val();
    var cat_id = $("#cat").val();
    var sub_id = $("#sub").val();
    var DateClosed = $("#date_closed").val();
    var CloseBy = $("#close_by").val();
    var remarks = $("#remarks").val();

    var today = new Date();
    DateCreated = new Date(DateCreated);
    DateClosed = new Date(DateClosed);
    if (DateCreated > today) {
      alert("Invalid date");
      return false;
    }
    else if (DateClosed < DateCreated ){
      alert("Date closed should be greater than date created!");
      return false;
    }
    else if (DateClosed > today ){
      alert("Invalid Closed_Date");
      return false;
    }

    if (
      Store != "" &&
      DateCreated != "" &&
      Concern != "" &&
      Status != "" &&
      Via != "" &&
      ItSupport != "" &&
      cat_id != "" &&
      sub_id != ""
    ) {
      $.ajax({
        url: "insert.php",
        method: "POST",
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function (data) {
          // alert(data);
          // $("#report_form")[0].reset();
          Swal.fire({
             icon: 'success',
             title: 'Your work has been saved',
             showConfirmButton: false,
             timer: 1500
          });
          $("#userModal").modal("hide");
                  getdata();
                  get_card_data(yr);
      //     setTimeout(function(){// wait for 5 secs(2)
      //      location.reload(); // then reload the page.(3)
      // }, 2000); 
        },
      });
    } else {
      alert("All Fields are Required");
    }
  });




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
$('#operation').val("Edit");
$('#msgbtn').val("show");
$('#msg_thread').hide('slow');
}



});

$('#btnClose').click(function(){
// alert("working");
$('report_form').trigger('reset');
$('.dv_msg').hide();
$('#remarks_view').hide();
$('#tmpsubid').remove();
});


$('#subpie_clsbtn').click(function(event) {
event.preventDefault();
$('#chartdiv9').empty();

});

$('#substr_clsbtn').click(function(event) {
event.preventDefault();
$('#substr_clsbtn').empty();

});


    let endDate = new Date();
    let startDate = new Date();
    startDate.setDate(endDate.getDate() - 7);
    
    $('#frompolDate').val(startDate.toISOString().split('T')[0]);
    $('#topolDate').val(endDate.toISOString().split('T')[0]);
    
    // Load initial data
    _polledraph($('#frompolDate').val(), $('#topolDate').val());
});

// Add event listeners for date changes
$('#frompolDate, #topolDate').change(function() {
    _polledraph($('#frompolDate').val(), $('#topolDate').val());



});//document ready close

</script>

