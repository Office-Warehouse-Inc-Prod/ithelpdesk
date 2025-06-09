<?php

// ======== db  =========
include 'admin.php';
include '../condb.php';
include 'chrtdashboard.php';
include 'sub_graph_modal.php';

// $conn=new dbconfig();




?>


<style>

  
  </style>


<div class="container-fluid">

<div id="wrapper">

<div id="layoutSidenav_content">
<div class="container-fluid">



    <div class="col-md-4 mt-4 d-inline-flex p-2">
      <label class="sr-only" for="inlineFormInputGroup">Start Date</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text">LOGS IN YEAR OF:</div>
        </div>
                 <select class="form-contro"  name="yearpicker" id="yearpicker" required>
                 <option value="2019,2020,2021,2022,2023,2024,2025" >OVERALL</option>
                 <option value="2025" selected >2025</option>
                 <option value="2024" >2024</option>
                 <option value="2023" >2023</option>
                 <option value="2022" >2022</option>
                 <option value="2021" >2021</option>
                 <option value="2020">2020</option>
                 <option value="2019">2019</option>
  



                </select>
      </div>
    </div>

        </div>
 
    </div>
  </div>

<div class="card-deck align-items-center mb-3">


<div class="dashcard card text-white mb-4 bg-primary border-dark" style="width: 18rem; height: 9rem;">
<div class="card-body">

<div class="card-title">TOTAL REPORTS: <span class="float-right" id="count_total" style="font-size: 32px;"></span></div>
                          
</div>                                  
<div class="card-footer d-flex align-items-center justify-content-between">
<a class=" text-white stretched-link" id="card_totalval" href="#bottom" value="" ><span class="small text-white">Click here for more info.</span></a>
                          <div class="go-arrow">  </div>
                          
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-danger" style="width: 18rem; height: 9rem; ">
<div class="card-body">

<div class="card-title">OPEN REPORTS: <span class="float-right" id="count_open" style="font-size: 32px;"></span></div>
<!-- <div class="row">
  <div class="col-md-3">
  <h6>Hardware</h6>

  </div>
  <div class="col-md-3">
  <h6>Software</h6>

  </div>
  <div class="col-md-3">
  <h6>Network</h6>

  </div>
  <div class="col-md-3">
  <h6>Others</h6>

  </div>
</div> -->
</div>
<div class="card-footer d-flex align-items-center justify-content-between">

                       <a class=" text-white stretched-link" id="card_openval" href="#bottom" value="OPEN" ><span class="small text-white">Click here for more info.</span></a>
                   

                          <div class="go-arrow">  </div>
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-warning" style="width: 18rem; height: 9rem;">
<div class="card-body">

<div class="card-title" style="font-size: 15px;">ATTENDED WITH FIX ASSET:<span class="float-right" id="count_owfa" style="font-size: 32px;"></span></div>

</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                      
                           <a class="text-white stretched-link" id="card_openwfaval" href="#bottom" value="ATTENDED WITH FIX ASSET" ><span class="small text-white">Click here for more info.</span></a>
                          <div class="go-arrow">  </div>
                      </div>

</div>

<div class="dashcard card text-white mb-4 bg-success" style="width: 18rem; height: 9rem;">
<div class="card-body">

<div class="card-title">CLOSED REPORTS <span class="float-right" id="count_closed" style="font-size: 32px;"></span></div>
</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="text-white stretched-link" id="card_closedval" href="#bottom" value="CLOSED" ><span class="small text-white">Click here for more info.</span></a>
                          <div class="go-arrow">  </div>
                      </div>


</div>

</div>



<div class="row " id="ovrall">

<div class="card card2 col-12 col-md-12 col-lg-6">
<h5 class="card-header text-white">Overall Status</h5>
<div class="card-body">
<div id="chartdiv5"></div>
</div>
</div>

<div class="card card2 col-12 col-md-12 col-lg-6">
<h5 class="card-header text-white">Support Logs</h5>
<div class="card-body">
<div id="chartdiv8"></div>
</div>
</div>





<div class="card card2 col-12 col-md-12 col-lg-6">
<h5 class="card-header text-white">Recently enrolled reports.</h5>
<div class="card-body">
<div id="chartdiv1"></div>
</div>
</div>


<div class="card card2 col-12 col-md-12 col-lg-6">
<h5 class="card-header text-white">CATEGORIES</h5>
<div class="card-body">
<div id="chartdiv2" name="chartdiv2"></div>
</div>
</div>

<div class="card card2 col-12 col-lg-12">
<h5 class="card-header text-white">Number of Escalated Reports Per Area</h5>
<div class="card-body">
<div class="col-xl-12 col-lg-12">
</div>

<div id="chart_area"></div>
</div>     
</div>

</div>
<div class="row">


<div class="card card2">
<h5 class="card-header text-white">TICKETS</h5>
<div class="card-body">
<div class="row col-md-12 mb-3">
  
<div class="col-md-12">
<table id="report_data" class="table  table-striped table-responsive table-condensed text-center borderless"></table>

</div>
<!-- <button type="button" id="add_button" class=" second btn btn-xs btn-success" data-toggle="modal" data-target="#userModal"><i class="fas fa-plus"></i></button> -->
</div>

<div class="col-md-12">
<table id="network_tb" class="table  table-striped table-responsive table-condensed text-center borderless"></table>

</div>




</div>
</div>



</div>

<div class="col-lg-12">
  <input type="hidden" id="myInput">
</div>
</div> <!--end of container-->




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
<label id="suplabel" >SUPPORT</label>
<input type="hidden" name="it_num" id="it_num" readonly="">
<input type="hidden" name="it_numres" id="it_numres" readonly="">
<select class="form-control form-control-sm" name="itsup" id="itsup" required>
<option value="">Assign support...</option>  
     <?php
              $query="select * from it_tech WHERE deptsel = '2' AND itsup NOT IN ('4','7','8',12)";
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
<div class="form-group col-4 col-md-4 col-lg-4">

<label>CATEGORY</label>
<input type="hidden" name="cat_num" id="cat_num" readonly="">
<select class="form-control form-control-sm" name="cat" id="cat" required >
<option value=""> &larr; CATEGORY &rarr;</option>  
     <?php
              $query="select * from category WHERE deptsel = '2'";
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
<select class = "form-control form-control-sm" name= "status" id="status" required>
<option value=""> &larr; Status &rarr;</option>
<?php
      $query="select * from status WHERE mktg_module_tag = 'Y' ";
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
<input type="hidden" name="operation" id="operation" value="Add" />
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

  

//for debug purposes enable here
// console.log($('#date_created').val())


if(/Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { $("#ovrall").hide(); }

var user_id = <?= $_SESSION['user_id']; ?>;
var supvalres = $('#itsup').val();
console.log(user_id)

// create arry with non admin users


if (user_id != '239') {
  // alert('working');
}
else{
  // alert('not working');
  // console.log(supvalres)
  
  $('#it_numres').show();
  $('#itsup').attr('readonly', 'readonly');
  $('#status').attr('readonly', 'readonly');
  // $('#suplabel').html('TEST');
}


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
$('#myInput').on( 'input', function () {
    table.search( this.value ).draw();
} );


function getdata(yr){
$.post('fetchdata/fetch_data.php',{yr:yr, mode:'dtb'},function(data){
console.log(data);
admin_datatable(data);
},'json');
}
getdata();

var table
function admin_datatable(t){
const dataset=t.rptdata;
table =  $("#report_data").DataTable({

"dom":
'B<"pull-left"lf><"pull-right">tip',
// stateSave: true,
"buttons": [
       
                    {
                        text: '<i class="fas fa-plus"></i>',
                        attr:  {
                                    title: 'Add Report',
                                    id: 'add_button',
                                    class: 'second btn btn-danger',

                                    },
                                    action: function ( e, dt, node, config ) {
                                    $('#remarks_view').empty();
                                    $('#userModal').modal({"show": true, "backdrop": 'static'});
                                    $('.modal-title').text("ADD REPORT");
                                    $('#action').val("Add");
                                    $('#operation').val("Add");
                                    $('#report_form').trigger('reset');
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
                                    admin_hideshowforms();
                                    unilayout_netshowmodalform();
                                    // $('#msgbtn').hide();
                                    // $('#msg_thread').hide();
                                    $('#addmsg').removeAttr('required');



                     }

                },  
                {       
                        extend:'excelHtml5',
                        text:'<i class="fas fa-file-excel"></i>',
                        attr:{
                                 title:'Export to Excel',
                                 class: 'btn btn-success'

                        }
                       



                },
            
    ],
"pagingType": "full_numbers",
"bDestroy": true,
"responsive": true, "lengthChange": false, "autoWidth": false,
"language": {
"search": "_INPUT_",
"searchPlaceholder": "Search..."
},
"pageLength":10,
"data": dataset,
"order": [[ 2, "Desc" ]],

"columns": [

{title:"Update", data:null,"defaultContent": "<Button class='btn btn-danger' name='update' id='dtbsecond'><i class='fas fa-edit'></i></Button>"},
{title:".", data:"msg_cnt","defaultContent": ""},
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

// columnDefs: [ {
//             targets: -1,
//             data: null,
//             defaultContent: "<div style='text-align:center'><a class='btn btn-default'><i class='fa fa-search'></i></a> <a class='btn btn-default'><i class='fa fa-pencil'></i></a> <a class='btn btn-default'><i class='fa fa-times'></i></a></div>"
//         },
//         {
//             targets: 4,
//             orderable: false
//         } ]

"columnDefs": [
{ 

  targets: [7,11,12],
  "width": "2%",
  render: function ( data, type, row) {
      if(type === 'display'){
         if(data == '1 Days Unresolved'){
            data = '1 Day Unresolved'
          }
         else if(data == '01/01/1970 01:00'){
            data = ''
          }
         else if(data == '01/01/1970 08:00'){
            data = ''
          }
          else if(data<0){
            data =   ''
          }
          else if(data == 0){
            data = ''
          }
          else if(data == '0 Days Unresolved'){
            data = ''
          }
          // else if(data == '1'){
          //   data = '<i class="fas fa-envelope fa-lg bg-warning"></i>'
          // }
  }
  return data;
}


},
{

  targets: [1],
  "width": "2%",
  render: function ( data, type, row) {
      if(type === 'display'){
        if(data == '1'){
            data = '<i class="fas fa-envelope fa-lg bg-warning"></i>'
          }
          else if (data == '0'){
            data = ''

          }
  }
  return data;
}

}
],

rowCallback: function(row, data, index){
if(data['status'] == 'OPEN' && data['msg_cnt'] == '1'){
  // console.log (data['msg_cnt'])
$(row).find('td:eq(1)').css('color', 'red');
// .addClass('fas fa-envelope');
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
$(row).find('td:eq(13)').css('color', 'red');
}
if(data['status'] == 'OPEN' && data['msg_cnt'] == '0'){
  // console.log (data['msg_cnt'])
$(row).find('td:eq(1)').css('color', 'red');
// .addClass('fas fa-envelope');
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
$(row).find('td:eq(13)').css('color', 'red');
}
else if (data['status'] == 'ATTENDED WITH FIX ASSET'){
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
$(row).find('td:eq(11)').html(' ');
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
var tid=$(this).parent().siblings('td:eq(1)').html(); 
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
$('#it_numres').val(data['itsup']);
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
getinfo(tid, 'remarks', user_id);

gtsub_id();

$('.modal-title').text("Ticket Number: "+tid+"");
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
.columns( 7 )
.search(val)
.draw();
} );


$('#card_openval').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 7 )
.search(val)
.draw();
} );

$('#card_openwfaval').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 7 )
.search(val)
.draw();
} );

$('#card_closedval').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 7 )
.search(val)
.draw();
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
getdata(yr)
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
getdata(yr);
get_card_data(this.value);
_techgraph(yr);
_overallpie(yr);
_dbline(yr); 
_catpie(yr);
_areagraph(yr);
// bargrph_tech_res(yr);
// itsupdata(yr);
// _storegraph(yr);

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
// location.reload();
return false;
});

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
    var addmsgx = $("#addmsg").val();
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
          // alert(addmsgx);
          // $("#report_form")[0].reset();
          Swal.fire({
             icon: 'success',
             title: 'Your work has been saved',
             showConfirmButton: false,
             timer: 1500
          });
          $("#userModal").modal("hide");
                  getdata(yr);
                  get_card_data(yr);
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

$(document).on('click', '#dtbsecond', function(){

// alert("working");
$('#msgbtn').show();
$('msg_thread').show();
$('.dv_msg').show();
$('#remarks_view').show();
$('#addmsg').val("");


})


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
$('report_form')[0].reset();
$('.dv_msg').hide();
$('#remarks_view').hide();
$('#tmpsubid').remove();
$('#addmsg').val('');

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

</script>

