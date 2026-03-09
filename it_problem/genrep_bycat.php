<?php

include_once 'admin.php';
include '../condb.php';
include 'sub_graph_modal.php';

 ?>

<link rel="stylesheet" href="../plugins/DataTables-1.10.25/media/css/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="../assets/Date-Time-Picker-Bootstrap-4/src/sass/bootstrap-datetimepicker-build.css" />
<script src="../assets/Date-Time-Picker-Bootstrap-4/src/js/bootstrap-datetimepicker.js"></script>
<link rel="stylesheet" href="styles.css" />
<script src="../plugins/DataTables-1.10.25/media/js/jquery.dataTables.min.js"></script>
<script src="../js/ellipsis.js"></script>
<script src="../table2excel/src/jquery.table2excel.js" type="text/javascript"></script>
<script src="../js/coms.js"></script> 
<style>
:root {
  --owi-blue: #213456;
  --owi-gold: #E1AD01;
  -glass-bg: rgba(255, 255, 255, 0.95);
}

/* --- Dashboard Cards --- */
.card2 {
  border: none;
  border-radius: 15px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease;
  background: var(--glass-bg);
  overflow: hidden;
  margin-bottom: 20px;
}

.card2:hover {
  transform: translateY(-5px);
}

.card2 .card-header {
  background-color: var(--owi-blue) !important;
  border-bottom: 3px solid var(--owi-gold);
  font-weight: 600;
  letter-spacing: 1px;
  text-transform: uppercase;
  font-size: 0.9rem;
  padding: 15px 20px;
}

/* --- Buttons --- */
.btn-modern {
  border-radius: 8px;
  padding: 10px 20px;
  font-weight: 600;
  transition: all 0.3s;
  border: none;
}

.btn-primary { background-color: var(--owi-blue); }
.btn-primary:hover { background-color: #162640; box-shadow: 0 4px 15px rgba(33, 52, 86, 0.4); }
    
.btn-success { background-color: #28a745; }
.btn-success:hover { box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4); }

/* --- Table Design --- */
#genrep_table {
  border-radius: 12px;
  overflow: hidden;
  background-color: #ffffff !important;
  color: #333 !important;
  box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}

#genrep_table thead {
  background-color: var(--owi-blue);
  color: white;
}

/* --- Modal & Form Refinement --- */
.modal-content {
  border: none;
  border-radius: 20px;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

.modal-header {
  background-color: #213456;
  color: #fff;
  border-top-left-radius: 15px;
  border-top-right-radius: 15px;
  border-bottom: 4px solid #E1AD01; /* Your Theme Gold */
}

.form-control {
  border-radius: 8px;
  border: 1px solid #E1AD01;
  padding: 10px 12px;
  transition: border-color 0.2s;
}

.form-control:focus {
  border-color: #E1AD01;
  box-shadow: 0 0 0 0.2rem rgba(225, 173, 1, 0.25);
}

label {
  font-weight: 700;
  font-size: 0.75rem;
  color: #213456;
  text-transform: uppercase;
  margin-bottom: 5px;
  display: block;
}

#btn_chngepass {
  background-color: #E1AD01;
  border: none;
  color: #213456;
  font-weight: 700;
  padding: 10px 40px;
  border-radius: 30px;
  transition: all 0.3s ease;
}

#btn_chngepass:hover {
  background-color: #213456;
  color: #E1AD01;
  transform: translateY(-2px);
}

/* --- Remarks/Chat Thread --- */
.container_remarks {
  background: #f8f9fa;
  border-radius: 10px;
  padding: 15px;
  border-left: 5px solid var(--owi-gold);
  max-height: 300px;
  overflow-y: auto;
}
</style>

<div class="container-fluid mt-3 buttons">
    <span class="mb-2" id="genrep_title"></span>
	<div class="row">

<div class="card card2 col-xs-12 col-md-6">
<h5 class="card-header text-white">Overall Status</h5>
<div class="card-body">
<div id="chartdiv5"></div>
</div>
</div>

<div class="card card2 col-xs-12 col-md-6">
<h5 class="card-header text-white">CATEGORIES</h5>
<div class="card-body">
<div id="chartdiv2" name="chartdiv2"></div>
</div>
</div>


<div class="col-md-2 mt-2 mb-3 ">
    <button id="export_excel" class="btn btn-success pull-right">Export <i class="fas fa-file-excel"></i></button>
</div>
<div class="col-md-2 mt-2 mb-3 ">
    <button type="button" id="btn_generate" class="btn btn-primary pull-right mr-2" data-toggle="modal" data-target="#genModal">Generate Again <i class="far fa-list-alt"></i> </button>
</div>

			<div class="col-md-12">
				<table class="table table-dark table-responsive table-condensed" id="genrep_table"></table>
			</div>
	</div>

</div>



<!-- Start of Add/Edit Modal -->

<div class="col-12 col-lg-12"> 
<div class="col-12 col-lg-12 modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg mw-75">
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
<div class="form-group col-12 col-md-12 col-lg-6">
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
<div class="form-group col-lg-12">
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
      ?>   
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
              ?>   
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
      $query="select * from status WHERE stat_id NOT IN  ('2','5','6')";
      $run=$conn->prepare($query);
      $run->execute();
      $rs=$run->get_result();
      while ($res=$rs->fetch_assoc()) {
      ?>
      <option><?=$res['stat_desc'] ?></option>
      <?php }?>
      ?>   
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
<input type="hidden" name="close_by" id="close_by" value="<?php echo $_SESSION['tech_id'];?>"> 
<input type="text" class="form-control form-control-sm" name="cl_desc" id="cl_desc" readonly="" value="<?php echo $_SESSION['fname']. '  ' . $_SESSION['lstname'];?>">
</div>

<div class="form-group col-lg-12">
<label>Work Output:</label>
<textarea name="remarks" id="remarks" class="form-control form-control-sm" placeholder="Your Workoutput" ></textarea>
</div>
<hr/>
<div class="form-group col-lg-12">
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

<div class="col-12 col-lg-12">
  
<div class="collapse" id="msg_thread">

<div class="row">
<div  class="col-12 col-lg-12 mb-3">
       <label style="font-weight: bold;">Add Comment:</label>
<textarea name="admsg" id="addmsg" class="form-control form-control-sm" placeholder="Reply to their message or give an updates regarding on this ticket..."></textarea> 
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

<div class="modal-footer">
<input type="hidden" name="operation" id="operation" />
<input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id'];  ?>">

</div>
</form>
</div>
</div>
</div>
</div>
</div>



<script type="text/javascript">

$(document).ready(function() {
  autostrt_modal();
$(".buttons").hide();
$(".div_alrt").hide();
 let start_date = "";
 let end_date = "";
 let slct_area = "";
 let tb_title = "";
 let apnd_date = "";


$("#search").click(function(event) {

 start_date = $('#start_date').val();
 end_date = $('#end_date').val();
 // slct_area = $('#slct_area').val();
 slct_cat = $('#slct_cat').val();
 slct_stat = $('#slct_stat').val();
                 console.log( $('#start_date').val())
                console.log( $('#end_date').val())
 let sdate = new Date(start_date);
 let edate = new Date(end_date);
 let st_date = ((sdate.getMonth() > 8) ? (sdate.getMonth() + 1) : ('0' + (sdate.getMonth() + 1))) + '/' + ((sdate.getDate() > 9) ? sdate.getDate() : ('0' + sdate.getDate())) + '/' + sdate.getFullYear();
 let en_date = ((edate.getMonth() > 8) ? (edate.getMonth() + 1) : ('0' + (edate.getMonth() + 1))) + '/' + ((edate.getDate() > 9) ? edate.getDate() : ('0' + edate.getDate())) + '/' + edate.getFullYear();

//  tb_title = "GENERATED REPORT FROM"+" "+st_date+" "+"to"+" "+en_date;
//  apnd_date = st_date+" "+"TO"+" "+en_date;

tb_title = "Overall Generated Report Filtered By Category";

   if (end_date < start_date ){
      $(".div_alrt").show();
      $('#div_alrtmsg').html('<i class="fa fa-exclamation-circle fa-lg" aria-hidden="true"></i> End date should not be greater than date created. </div>')
      hidealrtdiv();
      return false;
    }
    else{
      $.post('fetchdata/fetch_data.php',{mode:'admin_get_reports_bycat' , 'slct_cat':slct_cat , 'slct_stat':slct_stat ,  'start_date': start_date , 'end_date': end_date},function(data){


                getrepdata(data);
                console.log(data);
                _overallpie();
                _catpie();
                // genrep_store();
                $('#genModal').modal( 'hide' );
                $(".buttons").show();
                $("#genrep_title").html(tb_title);

        

                
      },'json')
      .fail(function(data){
      $(".div_alrt").show();
        $('#div_alrtmsg').html('<i class="fa fa-exclamation-circle fa-lg" aria-hidden="true"></i> NO RECORDS FOUND </div>');
        hidealrtdiv();
      });
    }

});

var table
function getrepdata(t){
const dataset=t.adminrptdata_bycat;
table =  $("#genrep_table").DataTable({

"dom":
'<"pull-left"lf><"pull-right">tip',
// "stateSave": true,
//"serverSide": true,
"pagingType": "full_numbers",
"bDestroy": true,
"responsive": true, "lengthChange": false, "autoWidth": false,
"bInfo": false,
"bFilter": false,
"paging": false,
"select": true,
"pageLength":10,
"data": dataset,
// "order": [[ 0, "Asc" ]],

"columns": [

{title:"Update", data:null,"defaultContent": "<Button class='btn btn-danger' name='update' id='dtbsecond'><i class='fas fa-edit'></i></Button>"},
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
{title:"WORKOUTPUT", data:"remarks","defaultContent": "",}


 

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


$('#genrep_table tbody').on( 'click', 'button', function () {

// alert('click');  
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
option.text = $(this).parent().siblings(':nth-of-type(10)').html();
sst.add(option);   

// console.log(user_id)
getinfo(tid, 'remarks');

gtsub_id();

$('.modal-title').text("Ticker Number: "+tid+"");
$('#action').val("Save");
$('#operation').val("Edit"); 
$('#userModal').modal({"show": true, "backdrop": 'static'});



} );

} // end of data table


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
    console.log(DateClosed)
    var today = new Date();
    DateCreated = new Date(DateCreated);
    DateClosed = new Date(DateClosed);
    if (DateCreated > today) {
      alert("Invalid date");
      return false;
    }
    else if (Status == 'OPEN'){
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
            
                // table.clear();
                // 
                // 
                // 
                $.post('fetchdata/fetch_data.php',{mode:'admin_get_reports_bycat' , 'slct_cat':slct_cat , 'slct_stat':slct_stat ,  'start_date': start_date , 'end_date': end_date},function(data){


                getrepdata(data);
                console.log(data);
                _overallpie();
                _catpie();
                // genrep_store();
                $('#genModal').modal( 'hide' );
                $(".buttons").show();
                $("#genrep_title").html(tb_title);

        

                
      },'json')
        },
      });
    } else {
      alert("All Fields are Required");
    }
  });





    function autostrt_modal(){
      setTimeout(function() {
      $('#genModal').modal({
      backdrop: 'static',
      keyboard: false
      });
      }, 1000);
    }

    function hidealrtdiv(){
              setTimeout(function () {
  
            // Closing the alert
            $('.alert').hide();
        }, 5000);
    }



    $("#export_excel").click(function(){

        $("#genrep_table").table2excel({

            // exclude CSS class
            exclude: ".noExl",
              name: "Generated Report",
              filename: tb_title,
              fileext: ".xlsx",
              exclude_img: true,
              exclude_links: true,
              exclude_inputs: true,
              preserveColors: true

    });
  });

  function _overallpie(){

 $.ajax({
    url:"fetchdata/fetch_data.php",
    method:'POST',
     data:{mode:'genrep_bycat_pie','slct_cat':slct_cat , 'start_date': start_date , 'end_date': end_date},

    success:function(data5)
    {

      var obj5 = JSON.parse(data5);
      // console.log(obj5)
       _plotovpie(obj5)
      
    }
   });

}

function _plotovpie(grphdata){

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv5", am4charts.PieChart);

// legend
chart.legend = new am4charts.Legend();
chart.legend.position = "bottom";
chart.legend.valign = "bottom";
chart.innerRadius = am4core.percent(40);
chart.legend.labels.template.text = "[bold {color}]{name}[/]";
// chart.legend.labels.template.text =
// series1.legendSettings.value = "{points}";
// Add data
chart.data = grphdata

// Add and configure Series
var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "points";
pieSeries.dataFields.category = "stat_name";
pieSeries.slices.template.stroke = am4core.color("#FFF"); //outline
pieSeries.slices.template.strokeWidth = 2;
pieSeries.slices.template.strokeOpacity = 1;
pieSeries.slices.template.tooltipPosition = "pointer";
pieSeries.labels.template.maxWidth = 130;
pieSeries.labels.template.wrap = true;
pieSeries.labels.template.fontSize = 12;
pieSeries.labels.template.text = "{type} {value.value} Reports | {value.percent.formatNumber('.##')}%";
pieSeries.slices.template.tooltipText = "{type} {value.value} Reports | {value.percent.formatNumber('.##')}%";


// This creates initial animation
pieSeries.hiddenState.properties.opacity = 1;
pieSeries.hiddenState.properties.endAngle = -90;
pieSeries.hiddenState.properties.startAngle = -90;

pieSeries.colors.list = [
  am4core.color("#27A243"),
  am4core.color("#D53343"),
  am4core.color("#F7BB07"),
  am4core.color("#169DB2"),
];

am4core.options.autoDispose = true;

}); // end am4core.ready()

 }

function _catpie(){
var selected;
var types = $.ajax({
    url:"fetchdata/fetch_data.php",
    method:'POST',
    data:{mode:'genrep_bycat_catpie','slct_cat':slct_cat , 'slct_stat':slct_stat, 'start_date': start_date , 'end_date': end_date},
    datatype:'JSON',
   
    success:function(data)
    {
      var objcat = JSON.parse(data);
      // console.log(objcat);
      grhp(objcat);
    }
   });
 } 



 function grhp(types){
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_material);
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv2", am4charts.PieChart);

// Set data
//legend 
// chart.legend = new am4charts.Legend();
// chart.legend.scrollable = true;

var selected;


// Add data
chart.data = generateChartData();

// Add and configure Series
var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "percent";
pieSeries.dataFields.category = "type";
pieSeries.slices.template.propertyFields.fill = "color";
pieSeries.slices.template.propertyFields.isActive = "pulled";
pieSeries.slices.template.strokeWidth = 0;
pieSeries.labels.template.maxWidth = 130;
pieSeries.labels.template.wrap = true;
pieSeries.labels.template.paddingTop = 0;
pieSeries.labels.template.paddingBottom = 0;
pieSeries.labels.template.fontSize = 10;
pieSeries.integersOnly = true;
pieSeries.labels.template.text = "{type}: {value.value} | {value.percent.formatNumber('.##')}%";
pieSeries.slices.template.tooltipText = "{type}: {value.value} | {value.percent.formatNumber('.##')}%";
pieSeries.slices.template.tooltipPosition = "pointer";





chart.exporting.menu = new am4core.ExportMenu();


function generateChartData() {
 let d = Array();
  var chartData = [];
  for (var i = 0; i < types.length; i++) {
    if (i == selected) {
      for (var x = 0; x < types[i].subs.length; x++) {
         // d= new Array('types'=>types[i].subs[x].type)
        chartData.push({
          type: types[i].subs[x].type,
          percent: types[i].subs[x].percent,
          color: types[i].color,
          pulled:true
        });

      }

      for (var y = 0; y < types[i].subs.length; y++) {
         // d= new Array('types'=>types[i].subs[x].type)
        d.push({
          type: types[i].subs[y].type,
          percent: types[i].subs[y].percent
        });

      }
newgrph(d)
   
      // chartData.push({
      //   type: types[i].type,
      //   percent: types[i].percent,
      //   color: types[i].color,
      //   id: i
      // });

    } else {
      chartData.push({
        type: types[i].type,
        percent: types[i].percent,
        color: types[i].color,
        id: i
      });
    }
  }
  return chartData;
}

pieSeries.slices.template.events.on("hit", function(event) {
  if (event.target.dataItem.dataContext.id != undefined) {
    selected = event.target.dataItem.dataContext.id;
  } else {
    selected = undefined;
  }
  chart.data = generateChartData();
});
am4core.options.autoDispose = true;


}); // end am4core.ready()

} // end am4core.ready()


 function newgrph(data){
// console.log(data)

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance

var chart = am4core.create("chartdiv9", am4charts.PieChart);

// legend
// chart.legend = new am4charts.Legend();
// chart.legend.scrollable = true;
chart.innerRadius = am4core.percent(40);
// chart.legend.labels.template.text = "[bold {color}]{name}[/]";
// series1.legendSettings.value = "{points}";
// Add data
chart.data = data;




// Add and configure Series
var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "percent";
pieSeries.dataFields.category = "types";
pieSeries.slices.template.stroke = am4core.color("#FFF"); //outline
pieSeries.slices.template.strokeWidth = 2;
pieSeries.slices.template.strokeOpacity = 1;
pieSeries.slices.template.tooltipPosition = "pointer";
pieSeries.labels.template.maxWidth = 130;
pieSeries.labels.template.wrap = true;
pieSeries.labels.template.fontSize = 10;

// pieSeries.alignLabels = false;
// pieSeries.labels.template.text = "{type}: {value}";
// pieSeries.slices.template.tooltipText = "{type}:{value}";
pieSeries.labels.template.text = "{type}: {value.value} | {value.percent.formatNumber('.##')}%";
pieSeries.slices.template.tooltipText = "{type}: {value.value} | {value.percent.formatNumber('.##')}%";


// This creates initial animation
pieSeries.hiddenState.properties.opacity = 1;
pieSeries.hiddenState.properties.endAngle = -90;
pieSeries.hiddenState.properties.startAngle = -90;


am4core.options.autoDispose = true;

}); // end am4core.ready()



$('#piegraphModal').modal({"show": true, "backdrop": 'static'});

 
}

function genrep_store(){
                          $.ajax({
                  url:"fetchdata/fetch_data.php",
                  method:'POST',
                   data:{mode:'genrep_strgrph','slct_area':slct_area , 'start_date': start_date , 'end_date': end_date},
                  success:function(fdata)
                  {
                    var objstore = JSON.parse(fdata);
                    // console.log(objstore)
                    plot_genrepstore(objstore);
                  }
                 });
}


function plot_genrepstore(strres){

am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("store_graph1", am4charts.XYChart);

// Add data
chart.data = strres

// Create axes

var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "str_code";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 30;

categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
  if (target.dataItem && target.dataItem.index & 2 == 2) {
    return dy + 25;
  }
  return dy;
});

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
// valueAxis.min = 0;
// valueAxis.max = 300;

// Create series
var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.valueY = "cnt_ttl";
series.dataFields.categoryX = "str_code";
series.name = "cnt_ttl";
series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
series.columns.template.fillOpacity = .8;

var columnTemplate = series.columns.template;
columnTemplate.strokeWidth = 2;
columnTemplate.strokeOpacity = 1;

}); // end am4core.ready()



}

});

</script>


<!-- Styles -->
<style>
#chartdiv5 {
/*   margin-top: 2px;
  margin-left: 18px;*/
  width: 100%;
  height: 300px;
}

#chartdiv2 {
  margin-top: 12px;
  margin-left: 12px;
  width: 100%;
  height: 300px; 
}

#chartdiv9 {
   margin-top: 2px;
  margin-left: 12px;
  width: 100%;
  height: 350px;
}

#store_graph1 {
   margin-top: 2px;
  margin-left: 12px;
  width: 100%;
  height: 350px;
}


</style>





