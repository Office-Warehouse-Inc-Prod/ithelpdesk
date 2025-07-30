
<!-- modal addnew button -->

<script type='text/javascript'>
$( document ).ready(function() {
if(/Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { $("#ovrall").hide(); }

var user_id = <?= $_SESSION['user_id']; ?>

$('#sales_dashboard').hide();




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


// Add event listeners for date changes *(correction: this should be inside the document ready block)*
$('#frompolDate, #topolDate').change(function() {
    _polledraph($('#frompolDate').val(), $('#topolDate').val());


});



$('.a_sales_monitoring').click(function (e) { 
  e.preventDefault();
//   alert("Sales Monitoring is not available yet.");
$('.ticket_container').fadeOut();
$('#sales_dashboard').fadeIn();

salesdata();

function salesdata(){
  $.post('fetchdata/fetch_data.php', { mode: 'zreading_data' }, function(data){
    console.log(data); // inspect this
    sales_datatable(data); // corrected function name
  }, 'json');
}

var tablex;

function sales_datatable(t){
  const datasetsale = t.rptdata;
  console.log(datasetsale); // inspect array content

  tablex = $("#zreading_tbl").DataTable({
    dom: '<"pull-left"lf><"pull-right">tip',
    pagingType: "full_numbers",
    bDestroy: true,
    responsive: true,
    lengthChange: false,
    autoWidth: false,
    language: {
      search: "_INPUT_",
      searchPlaceholder: "Search..."
    },
    pageLength: 50,
    data: datasetsale,
    order: [[ 0, "desc" ]],
    columns: [
      { title: "ZREADING DATE", data: "CREATED_DATE", defaultContent: "" },
    //   { title: "STORE_NO", data: "STORE_NO", defaultContent: "" },
      { title: "STORE CODE", data: "STORE_CODE", defaultContent: "" },
      { title: "YESTERDAY SALES", data: "ttl", defaultContent: "" }
    ]
  });
}




});//click close



});//document ready close









</script>
