<?php



// ======== db  =========
include '../condb.php';
include 'tech_header.php';

$conn=new dbconfig();

// ======== header =========
$datetime = new DateTime();
$timezone = new DateTimeZone('Asia/Manila');
$datetime->setTimezone($timezone);


?>

<style>
  #showCalendarBtn{
    display: inline-block;
    padding: 10px 20px;
    background: #4f46e5;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    font-family: inherit;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}
#showCalendarBtn:hover {
    background: #4338ca;
}

/* ===== Soft Light DataTable Background ===== */

#report_data thead th{
    background: #F3F4F6 !important;  /* soft grey header */
    color: #374151 !important;       /* dark gray text */
    font-weight: 700;
}

#report_data tbody tr{
    background: #F9FAFB !important;  /* very soft white */
}

#report_data tbody tr:nth-child(even){
    background: #F3F4F6 !important;  /* subtle zebra */
}

#report_data tbody td{
    color: #4B5563 !important;       /* soft dark gray text */
    border-color: #E5E7EB !important;
}

#report_data tbody tr:hover{
    background: #E5E7EB !important;  /* soft hover */
}

/* DataTables wrapper */
.dataTables_wrapper{
    background: #F9FAFB;
    padding: 15px;
    border-radius: 12px;
}
</style>

<head>
<link rel="stylesheet" href="../plugins/DataTables-1.10.25/media/css/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="../assets/Date-Time-Picker-Bootstrap-4/src/sass/bootstrap-datetimepicker-build.css" />
<script src="../assets/Date-Time-Picker-Bootstrap-4/src/js/bootstrap-datetimepicker.js"></script>
<link rel="stylesheet" href="styles.css" />

<script src="../plugins/DataTables-1.10.25/media/js/jquery.dataTables.min.js"></script>
<script src="../js/ellipsis.js"></script>
</head>



<div class="container-fluid mt-4">

<div id="wrapper mb-3">
    <input type="hidden" id="yearpicker" name="yearpicker" value="<?php echo date("Y"); ?>">

<!--         <div class="row align-items-center">

                 <div class="col-md-2 mt-4" >
                 
                 <select class=""  name="yearpicker" id="yearpicker" required>
                 <option value="'2019','2020','2021'">OVERALL</option>
                 <option value="2019">2019</option>
                 <option value="2020">2020</option>
                 <option value="2021" selected>2021</option>
                </select>
                 <p style="display: inline-block;">REPORTS:</p>
                </div> -->
            
        </div>

 
  


<div class="card-deck mb-3 align-items-center">


<div class="dashcard card text-white mb-4 bg-primary border-dark" style="width: 20rem; height: 9rem;">
<div class="card-body">

<div class="card-title">THIS YEAR TOTAL REPORTS: <span class="" id="count_total" style="font-size: 18px;"></span></div>
                          
</div>                                  
<div class="card-footer d-flex align-items-center justify-content-between">

                     <a class=" second small text-white stretched-link" id="card_totalval" href="#bottom" value="" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                          
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-warning" style="width: 20rem; height: 9rem; ">
<div class="card-body">

<div class="card-title">ASSIGNED / ON PROCESS: <span class="" id="count_open" style="font-size: 18px;"></span></div>
</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                       
                          <a class="small text-white stretched-link" id="card_openval" href="#bottom" value="ON PROCESS" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-danger" style="width: 20rem; height: 9rem;">
<div class="card-body">

<div class="card-title" style="font-size: 15px;">PENDING: <span class="" id="count_owfa" style="font-size: 18px;"></span></div>

</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                      
                           <a class="small text-white stretched-link" id="card_openwfaval" href="#bottom" value="ATTENDED WITH FIX ASSET" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                      </div>

</div>

<div class="dashcard card text-white mb-4 bg-success" style="width: 20rem; height: 9rem;">
<div class="card-body">

<div class="card-title">CLOSED REPORTS <span class="" id="count_closed" style="font-size: 18px;"></span></div>
</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" id="card_closedval" href="#bottom" value="CLOSED" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                      </div>


</div>

</div>

<div class="row">


<div class="card2 col-md-12">
<h5 class="card-header text-white"></h5>
<div class="card-body">
<div class="row col-md-4 mb-3">
<button type="button" id="add_button" class="second btn btn-xs btn-danger" data-toggle="modal" data-target="#userModal">Add Report</button>
</div>

<table id="report_data" class="table table-dark table-responsive table-sm" style="width: auto;"></table>

<div class="col-md-12">
  <input type="hidden" id="myInput">
</div>

</div>
</div>
</div>
</div> 
<!--end of container-->



<!-- Start of Add/Edit Modal -->

<div class="col-12 col-lg-12 modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" style="max-width: 100%;">
<form method="post" id="report_form" enctype="multipart/form-data">
<div class="modal-content" >
<div class="modal-header">
<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
<h4 class="modal-title" id="userModal_header" value="Add Report"></h4>
<!-- <button type="button" id="prntForm" class="btn btn-info float-right" data-dismiss="modal"><i class="fas fa-print"></i></button> -->
</div>


<div class="modal-body">
 <div class="row">
<!-- <form> -->
<div class= "m_col col-6 col-md-6 col-lg-6">
<div class="row">
<div class="form-group col-6 col-md-6 col-lg-6">
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


<div class="form-group col-6 col-md-6 col-lg-6">

<label>DATE CREATED</label>
<div class="input-group date" id="datetimepicker1" data-target-input="nearest">
<input type="text" name="date_created" id="date_created" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker1" value="<?php echo $datetime->format('m/d/Y g:i A');?>" />
<div class="input-group-append" data-target="#date_created" data-toggle="datetimepicker">
<div class="input-group-text"><i class="fa fa-calendar"></i></div>
</div>
</div>
</div>

<div class="form-group col-12 col-md-12 col-lg-12">
<label>SUBJECT/CONCERN</label>
<textarea name="subjct" id="subjct" class="form-control form-control-sm" placeholder="Input Concern" 
style="text-transform:uppercase" onkeyup="this.value = this.value;"></textarea>
</div>

<div class="form-group col-3 col-md-3 col-lg-3">
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
      ?>   
</select>
</div>
<div class="form-group col-9 col-md-9 col-lg-9">
<label>I.T SUPPORT</label>
<input type="hidden" name="it_num" id="it_num" value="<?php echo $_SESSION['tech_id'];?>" readonly="">
<input type="text" class="form-control form-control-sm" name="itsup" id="itsup" value="<?php echo $_SESSION['fname']." ". $_SESSION['lstname'];?>" readonly> 
</div>
<div class="form-group col-4 col-md-4 col-lg-4">

<label>CATEGORY</label>
<input type="hidden" name="cat_num" id="cat_num" readonly="">
<select class="form-control form-control-sm" name="cat" id="cat" required >
<option value=""> &larr; CATEGORY &rarr;</option>  
     <?php
              $query="select * from category WHERE deptsel = '1'";
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
<div class="form-group col-4 col-md-4 col-lg-4">

<label>SUB CATEGORY</label>
<input type="hidden" name="sub_num" id="sub_num" readonly="">


<select class="form-control form-control-sm" name="sub" id="sub">
</select>
</div>
<div class="form-group col-4 col-md-4 col-lg-4 hide_isp">

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
<div class="form-group col-4 col-md-4 col-lg-4 hide_isp">
<label id="lbl_refNo" for="refNo">Reference No:</label>
<input type="text" class="form-control form-control-sm" name="refNo" id="refNo">
</div>


<div class="form-group col-4 col-md-4 col-lg-4 hide_isp">

<label for="date_refNo" class="hidden" id="lbl_DtRefNo">Date of RefNo</label>
<div class="input-group date" id="datetimepicker3" data-target-input="nearest">
<input type="text" name="date_refNo" id="date_refNo" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker3"/>
<div class="input-group-append" data-target="#date_created" data-toggle="datetimepicker">
<div class="input-group-text" id="ico_cal3"><i class="fa fa-calendar"></i></div>
</div>
</div>
</div>

<div class="form-group col-4 col-md-4 col-lg-4">
<label>STATUS</label>
<input type="text" class="form-control form-control-sm" name="status" id="status" value="ON PROCESS" readonly="">
</div>
<div class="form-group col-4 col-md-4 col-lg-4 hide_cl">
<label id="dateclabel" class="hidden">DATE CLOSED</label>
<div class="input-group date" id="datetimepicker2" data-target-input="nearest">
<input type="text" name="date_closed" id="date_closed" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker2" autocomplete="off" />
<div class="input-group-append" data-target="#date_closed" autocomplete="off" data-toggle="datetimepicker">
<div class="input-group-text" id="ico_cal" name="ico_cal"><i class="fa fa-calendar"></i></div>
</div>
</div>
</div>

<div class="form-group col-4 col-md-4 col-lg-4 hide_cl">

<label id="clby_label" class="hidden">CLOSED BY</label>
<input type="hidden" name="close_by" id="close_by" value="<?php echo $_SESSION['tech_id'];?>"> 
<input type="text" class="form-control form-control-sm" name="cl_desc" id="cl_desc" readonly="" value="<?php echo $_SESSION['fname']. '  ' . $_SESSION['lstname'];?>">
</div>

<div class="form-group col-lg-12">
<label>Work Output:</label>
<textarea name="remarks" id="remarks" class="form-control form-control-sm" placeholder="Your Workoutput" ></textarea>
</div>
</div>
<hr/>

<div class="card" id="img" name="img">


</div>

<div class="form-group col-lg-12">
<p>
<div class="row">
    
<div class=" col-6 col-md-8">
<input type="submit" name="action" id="action" class="btn btn-success" value="Add" />   
</div>
<div class=" col-6 col-md-4 ">
<button type="button" name="btnClose" id="btnClose" class="btn btn-danger float-right" data-dismiss="modal">Close</button>  
</div>
</div>


</p>
</div>

</div>

</p>




<div class="col-6 col-md-6 col-lg-6">

  
<div class="" id="msg_thread">

<div  class="col-12 col-lg-12 mb-3">

       <label style="font-weight: bold;">Add Comment:</label>
<textarea name="admsg" id="addmsg" class="form-control form-control-sm" placeholder="Reply to their message or give an updates regarding on this ticket..." required></textarea> 
</div>
<div class="col-12 col-lg-12 mt-4 mb-2 dv_msg">
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


var user_id = <?= $_SESSION['user_id']; ?>

let val = '';
$('#card_totalval').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");

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


function getdata(){
$.post('fetchdata/fetch_data.php',{mode:'dtb'},function(data){
// console.log(data);
admin_datatable(data);
},'json');
}
getdata();

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

{title:"Update", data:null,"defaultContent": "<Button class='btn btn-danger' name='update' id='dtbsecond'><i class='fas fa-edit'></i></Button>"},
{title:"TicketNo", data:"ticket_no","defaultContent": ""},
{title:"Date Created", data:"date_created","defaultContent": ""},
{title:"  Store", data:"str_code","defaultContent": ""},
{title:"Subject", data:"subject","defaultContent": ""},
// {title:"Concern", data:"concern","defaultContent": ""},
{title:"Via", data:"via","defaultContent": ""},
{title:"STATUS", data:"status","defaultContent": ""},
// {title:"Assigned Support", data:"it_desc","defaultContent": ""},
{title:"CATEGORY", data:"category","defaultContent": ""},
{title:"SUBCATEGORY", data:"sub_category","defaultContent": ""},
{title:"DATE CLOSED", data:"date_closed","defaultContent": ""},
{title:"DAYS COMPLETION", data:"tdc","defaultContent": ""},
{title:"WORKOUTPUT", data:"remarks","defaultContent": ""}




],
"columnDefs": [
{ 

  targets: [9,10],
  "width": "2%",
  render: function ( data, type, row) {
      if(type === 'display'){
          if(data == '1 Days Unresolved'){
            data = '1 Day Unresolved'
          }
         else if(data == '01/01/1970 08:00'){
            data = 'UNRESOLVED'
          }
          else if(data<0){
            data =   ''
          }
          else if(data == 0){
            data = 'Solve Immediately'
          }
  }
  return data;
}
}
],


rowCallback: function (row, data) {

  // clear any previous inline styles set by callback
  $(row).find('td:eq(6)').attr('style', '');

  const status = (data['status'] || '').toUpperCase();
  const $statusTd = $(row).find('td:eq(6)'); // STATUS column

  if (status === 'ON PROCESS') {
    $statusTd.attr('style', 'color:#F97316 !important; font-weight:800;');
  } else if (status === 'SUBJECT FOR CLOSING') {
    $statusTd.attr('style', 'color:#7C3AED !important; font-weight:800;');
    
  }
  else if (status === 'PENDING') {
    $statusTd.attr('style', 'color:#CC313F !important; font-weight:800;');
  } else if (status === 'CLOSED') {
    $statusTd.attr('style', 'color:#16A34A !important; font-weight:800;');
  } else {
    // default
    $statusTd.attr('style', 'color:#374151 !important; font-weight:700;');
  }
}

});


$('#report_data tbody').on( 'click', 'button', function () {

var data = table.row( $(this).parents('tr') ).data();
// alert( data['ticket_no'] +"'s salary is: "+ data[ 'store' ] );
// alert("click");
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
$('#itsup').val(data['it_desc']);
$('#cat_num').val(data['cat_id']);
$('#cat').val(data['cat_id']);
$('#sub_num').val(data['sub_id']);
$('#sub').val(data['sub_id']);
$('#isp_num').val(data['isp_id']);
$('#isp').val(data['isp_id']);
$('#refNo').val(data['refNo']);
$('#date_refNo').val(data['date_refNo']);
admin_hideshowforms();
$('#date_closed').val(data['date_closed']);
$('#remarks').val(data['remarks']);




if($('#status').val() == 'CLOSED') {
$(':input[type="submit"]').prop('disabled', true); 
$('#date_created').attr('readonly', true);
$('#date_refNo').attr('readonly', true);
$('#date_closed').attr('readonly', true);
// $('#admsg').attr('readonly', true);
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
$('#date_created').attr('readonly', false);
$('#date_refNo').attr('readonly', false);
$('#date_closed').attr('readonly', false);
// $('#admsg').attr('readonly', false);
$('#store').prop("disabled", false);
$('#via').prop("disabled", false);
$('#status').prop("disabled", false);
$('#itsup').prop("disabled", false);
$('#cat').prop("disabled", false);
$('#sub').prop("disabled", false);
$('#isp').prop("disabled", false);
$('#remarks').attr('readonly', false);


}

var sst = document.querySelector("#sub");  
var option = document.createElement("option");
option.value=0;
option.id='tmpsubid';
option.selected='selected';
option.text = $(this).parent().siblings(':nth-of-type(9)').html();
sst.add(option);   

// console.log(user_id)
getinfo(tid, 'remarks', user_id);

gtsub_id();

$('.modal-title').text("Ticker Number: "+tid+"");
$('#action').val("Save and Reply");
$('#operation').val("Save and Reply"); 
$('#userModal').modal({"show": true, "backdrop": 'static'});



} );

// table
// .search( '' )
// .columns().search( '' )
// .draw();

$('#card_totalval').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 6 )
.search(val)
.draw();
} );


$('#card_openval').on('click', function () {
// var val =  $(this).attr("value");
var val =  'ON PROCESS';
// alert(val);
table
.columns( 6 )
.search(val)
.draw();
} );

$('#card_openwfaval').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 6 )
.search(val)
.draw();
} );

$('#card_closedval').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 6 )
.search(val)
.draw();
} );


$('#myInput').on( 'input', function () {
    table.search( this.value ).draw();
} );

} // end of data table


$('#store_graph_modal').modal('hide'); 

crd_btm();
slct_isp();
// slct_itsup();
slct_sub();
gtsub_id();
admin_hideshowforms();  

const yr =$("#yearpicker").val();
get_card_data(yr)
function get_card_data(y){
$.post('fetchdata/fetch_data.php',{yr:y,mode:'yearch'}, function(data) {
/*optional stuff to do after success */
// console.log(data)
let card_data = jQuery.parseJSON(data); 
const a = card_data;
// console.log(a)
$('#count_total').html(a[0].total_res);
$('#count_open').html(a[0].open_res);
$('#count_owfa').html(a[0].owfa_res);
$('#count_closed').html(a[0].cls_res);


});
}

$(function () {
$('#datetimepicker1, #datetimepicker2, #datetimepicker3').datetimepicker()
});

$("#yearpicker").on('change',function(){
const yr =$("#yearpicker").val()
// reports_total(this.value);
get_card_data(this.value);
_techgraph(yr);
_overallpie(yr);
_dbline(yr); 
_catpie(yr);
_areagraph(yr);

});

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


$('#add_button').click(function(){
$('#remarks_view').empty();
$('#report_form').trigger('reset');
$('.modal-title').text("ADD REPORT");
$('#subjct').attr('readonly', false);
$('#action').val("Add");
$('#operation').val("Add");
$('#date_created').attr('readonly', false);
$('#date_refNo').attr('readonly', false);
$('#date_closed').attr('readonly', false);
$('#store').prop("disabled", false);
$('#via').prop("disabled", false);
$('#status').prop("disabled", false);
$('#itsup').prop("disabled", false);
$('#cat').prop("disabled", false);
$('#sub').prop("disabled", false);
$('#isp').prop("disabled", false);
$(':input[type="submit"]').prop('disabled', false); 
$('#remarks').attr('readonly', false);
$('#msgbtn').hide();
$("#userModal").on('hidden.bs.modal', function(){


});
$('#userModal').modal({backdrop: 'static', keyboard: false}) 
$("#userModal").on('hidden.bs.modal', function(){
location.reload();
});

});

_insert_data();


$(document).on('click', '#dtbsecond', function(){

  var val = jQuery('#ticket_no').val();


  $.ajax({
      type: 'POST',
      url: 'sesticket.php',
      data: {tktval: val},
      success: function(response) {
        $('#img').html(response);
      }
    });

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
$('#operation').val("Save and Reply");
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

});//document ready close




// $('#action').click(function(event) {
//   alert("Updated Successfully")
//   location.reload();
// });


function _insert_data() {
  $(document).on("submit", "#report_form", function (e) {
    // alert("1");
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
    else if (Status == 'ON PROCESS')
    {
      if (DateClosed < DateCreated ){
      alert("Date closed should be greater than date created!");
      return false;
    }
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
      //     setTimeout(function(){// wait for 5 secs(2)
      //      location.reload(); // then reload the page.(3)
      // }, 2000); 
        },
      });
    } else {
      alert("All Fields are Required");
    }
     clearconsole();
  });
}

</script>

