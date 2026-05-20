<?php
include 'admin.php';
include '../condb.php';
include 'chrtdashboard.php';
include 'sub_graph_modal.php';


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

 <div class="row">

<div class="card card2 col-12 col-md-12 col-lg-6">
<h5 class="card-header text-black">Overall Status</h5>
<div class="card-body">
<div id="chartdivnet2"></div>
</div>
</div>


<div class="card card2 col-12 col-md-12 col-lg-6">
<h5 class="card-header text-black">CATEGORIES</h5>
<div class="card-body">
<div id="chartdivnet" name="chartdivnet"></div>
</div>
</div>

<div class="card card2 col-12 col-lg-12">
<h5 class="card-header text-black">Number of Escalated Reports Per Store</h5>
<div class="card-body">
<div class="col-xl-12 col-lg-12">
</div>

<div id="net_area"></div>
</div>     
</div>


</div>

<!-- <div class="card card2">
<h5 class="card-header text-black">TICKETS</h5>
<div class="card-body">
<div class="row col-md-12 mb-3">
  
<div class="col-md-12">
<table id="report_datanet" class="table  table-striped table-responsive table-condensed text-center borderless"></table>

</div>
<button type="button" id="add_button" class=" second btn btn-xs btn-success" data-toggle="modal" data-target="#userModal"><i class="fas fa-plus"></i></button>
</div>






</div>
</div> -->

<script type="text/javascript">

function getdatanet(){
$.post('fetchdata/fetch_data.php',{mode:'dtbnet'},function(data){
// console.log(data);
admin_datatablenet(data);
},'json');
}
getdatanet();


var table
function admin_datatablenet(t){
const dataset=t.rptdatanet;
table =  $("#report_datanet").DataTable({

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
                                    $('#img').empty();
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

rowCallback: function(row, data, index) {
  // Remove previous styling classes
  $(row).removeClass('status-open status-open-msg status-closed status-subject-closing status-fixed');

  if (data['status'] === 'OPEN') {
    if (data['msg_cnt'] === '1' || data['msg_cnt'] === '0') {
      $(row).addClass('status-open');
    }
  } else if (data['status'] === 'ATTENDED WITH FIX ASSET') {
    $(row).addClass('status-fixed');
    $(row).find('td:eq(11)').html(' '); // Clear cell 11 if needed
  } else if (data['status'] === 'CLOSED') {
    $(row).addClass('status-closed');
  } else if (data['status'] === 'SUBJECT FOR CLOSING') {
    $(row).addClass('status-subject-closing');
  }
}


});

$('#report_datanet tbody').on('dblclick', 'tr', function () {

  // Simulate the original `button` click by using this `tr` as parent
  var data = table.row($(this)).data();
  if (!data) return;

  $('#subjct').attr('readonly', true);
  var tid = $(this).find('td:eq(2)').html(); 
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
  $('#close_by').val(data['close_by']);
  $('#cl_desc').val(data['clusers']); // added 5/3/2024
  $('#cat').val(data['cat_id']);
  $('#sub_num').val(data['sub_id']);
  $('#sub').val(data['sub_category']);
  $('#isp_num').val(data['isp_id']);
  $('#isp').val(data['isp_id']);
  $('#refNo').val(data['refNo']);
  $('#date_refNo').val(data['date_refNo']);
  $('#file-input').val("");
  admin_hideshowforms();
  $('#date_closed').val(data['date_closed']);
  $('#remarks').val(data['remarks']);
  unilayout_netshowmodalform();

  $('#itsup').off('change').on('change', function () {
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
    $('#store').prop("disabled", true);
    $('#via').prop("disabled", true);
    $('#status').prop("disabled", true);
    $('#itsup').prop("disabled", true);
    $('#cat').prop("disabled", true);
    $('#sub').prop("disabled", true);
    $('#isp').prop("disabled", true);
    $('#remarks').attr('readonly', true);
  } else {
    $(':input[type="submit"]').prop('disabled', false); 
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
    $('#remarks').attr('readonly', false);
  }

  // ✅ Retained block as requested
  var sst = document.querySelector("#sub");  
  var option = document.createElement("option");
  option.value = 0;
  option.id = 'tmpsubid';
  option.selected = 'selected';
  option.text = $(this).find('td:eq(11)').html();
  sst.add(option);   

  getinfo(tid, 'remarks', user_id);
  
  gtsub_id();

  $('.modal-title').text("Ticket Number: " + tid);
  $('#action').val("Save and Reply");
  $('#operation').val("Save and Reply"); 
  $('#userModal').modal({ "show": true, "backdrop": 'static' });

});





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


$('.clcktxt').click(function () { 
  var val =  $(this).attr("value");
// alert(val);
table.columns(7).search(val).draw();
$('#network_tb').slideToggle();
    $('html, body').animate({
        scrollTop: 1600
    }, 1000);
});

} // end of data table


</script>
