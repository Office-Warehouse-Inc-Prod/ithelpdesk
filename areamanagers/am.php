
<!-- modal addnew button -->

<script type='text/javascript'>
$( document ).ready(function() {
function timeAgo(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.round((now - date) / 1000);
    const minutes = Math.round(seconds / 60);
    const hours = Math.round(minutes / 60);
    const days = Math.round(hours / 24);

    if (seconds < 60) return "just now";
    else if (minutes < 60) return minutes + " minute" + (minutes > 1 ? "s" : "") + " ago";
    else if (hours < 24) return hours + " hour" + (hours > 1 ? "s" : "") + " ago";
    else if (days < 30) return days + " day" + (days > 1 ? "s" : "") + " ago";
    
    return date.toLocaleDateString(); 
}

 function loadCommentThread(ticket_no) {
        const $remarksView = $('#remarks_view');
        const ticketValue = (ticket_no || '').toString().trim();

        if (!ticketValue) return;
        
        $remarksView.fadeOut(150, function() {
            $remarksView.html('<div class="text-center text-muted mt-4 mb-4"><div class="spinner-border spinner-border-sm me-2 text-primary"></div>Loading conversation...</div>').fadeIn(150);
        });

        $.ajax({
            url: 'get_comments.php', 
            type: 'POST',
            dataType: 'json',
            data: { ticket_no: ticketValue },
            success: function(response) {
                let html = '';
                
                if (Array.isArray(response) && response.length > 0) {
                    var currentUserIdStr = "<?= $_SESSION['user_id'] ?? '' ?>";
                    var currentUserNameStr = "<?= $_SESSION['fname'] ?? '' ?>";
                    let reversedResponse = response.slice().reverse();

                    reversedResponse.forEach(function(comment, index) {
                        let sender = comment.userId || 'Unknown';
                        
                        let isMe = false;
                        if(currentUserIdStr !== "" && sender === currentUserIdStr) isMe = true;
                        if(currentUserNameStr !== "" && sender.includes(currentUserNameStr)) isMe = true;
                        
                        let bubbleClass = isMe ? 'chat-right' : 'chat-left';
                        let delay = index * 0.05; 
                        let relativeTime = timeAgo(comment.comment_date);
                        let replyTimeColor = isMe ? "color: #e2e8f0;" : "color: #64748b;";
                        
                        html += `
                            <div class="chat-bubble ${bubbleClass}" style="animation-delay: ${delay}s;">
                                <div class="msg-meta">
                                    <span class="msg-meta-name">${sender}</span>
                                    <span class="msg-time">${comment.comment_date}</span> 
                                </div>
                                <div style="white-space: pre-wrap;">${comment.comment_details}</div>
                                
                                <div class="reply-time" style="font-size: 0.65rem; text-align: right; margin-top: 6px; opacity: 0.85; font-style: italic; ${replyTimeColor}">
                                    Replied ${relativeTime}
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="text-center text-muted mt-3" style="font-size:13px;"><i class="fas fa-comments mb-2" style="font-size:24px; opacity:0.5;"></i><br>No comments yet. Start the conversation!</div>';
                }
                
               $remarksView.fadeOut(150, function() {
                    $remarksView.html(html).fadeIn(300);
                    $('.dv_msg, .container_remarks').slideDown(300); 

                    setTimeout(() => {
                        const $container = $('.container_remarks');
                        if ($container.length) {
                            $container.animate({ scrollTop: 0 }, 600, 'swing');
                        }
                    }, 200);
                });
            },
            error: function(xhr) {
                $remarksView.html('<div class="text-danger text-center mt-3">Error loading comments.</div>');
            }
        });
    }

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


/**
 * Getdata.
 */
function getdata(yr){
$.post('fetchdata/fetch_data.php',{yr:yr,mode:'dtb'},function(data){
admin_datatable(data);
},'json');
}


var table
/**
 * Admin datatable.
 */
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


  rowCallback: function (row, data, index) {
          $(row).removeClass('status-open status-open-msg status-closed status-subject-closing status-pending');
          
          $(row).find('td').css({'background-color': '', 'color': ''});

          if (data['status'] === "ON PROCESS") {
            $(row).find('td').css({
                'color': '#9b7807' 
            });

            if (data['msg_cnt'] === '1' || data['msg_cnt'] === '0') {
              $(row).addClass('status-open');
            }
          } else if (data['status'] === 'PENDING') {
            $(row).addClass('status-pending');
            $(row).find('td:eq(11)').html(' '); 
          } else if (data['status'] === 'CLOSED') {
            $(row).addClass('status-closed');
          } else if (data['status'] === 'SUBJECT FOR CLOSING') {
            $(row).addClass('status-subject-closing');
          }
        }
      });

$('#report_data tbody').on('click', 'button', function () {
var data = table.row($(this).parents('tr')).data();
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

open_ticket_modal(data);
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

$('.dv_msg').show();
$('#remarks_view').show();
$('#msg_thread').show();
loadCommentThread(data['ticket_no']);

gtsub_id();

$('.modal-title').text("Ticket Number: "+tid+"");
$('#action').val("Save");
$('#operation').val("Edit"); 
$('#userModal').modal({"show": true, "backdrop": 'static'});



} );

$('#card_totalval').on('click', function() {
    table.search('').columns().search('').draw();
    $.fn.dataTable.ext.search = []; 
    table.draw();
    $('#myInput').slideToggle();
    $('html, body').animate({ scrollTop: 1600 }, 1000);
});

$('#card_openval').on('click', function() {
    table.search('').columns().search('').draw();
    
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var status = data[6]; 
            return status !== "CLOSED" && 
                   status !== "SUBJECT FOR CLOSING";
        }
    );
    table.draw();
    $('#myInput').slideToggle();
    $('html, body').animate({ scrollTop: 1600 }, 1000);
});

$('#card_openwfaval').on('click', function () {
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var dateColumn = data[10] || ""; 
            var match = dateColumn.match(/(\d+)\s+days?/i); 
            var days = match ? parseInt(match[1]) : NaN; 
            if (isNaN(days)) {
                console.error("Invalid number of days:", dateColumn);
                return false; 
            }

            if (days >= 4) {
                return true; 
            }

            return false; 
        }
    );

    table.draw();
    $.fn.dataTable.ext.search.pop();

    $('#myInput').slideToggle();
    $('html, body').animate({ 
    scrollTop: $('#ticketTabsContent').closest('.card2').offset().top - 20 
}, 200);
});


$('#card_openval').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table.columns(6).search(val).draw();
$('#myInput').slideToggle();
    $('html, body').animate({ 
    scrollTop: $('#ticketTabsContent').closest('.card2').offset().top - 20 
}, 200);
} );


$('#card_totalval').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table.columns(6).search(val).draw();
$('#myInput').slideToggle();
    $('html, body').animate({ 
    scrollTop: $('#ticketTabsContent').closest('.card2').offset().top - 20 
}, 200);
} );


$('#card_openwfaval').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table.columns(6).search(val).draw();
$('#myInput').slideToggle();
    $('html, body').animate({ 
    scrollTop: $('#ticketTabsContent').closest('.card2').offset().top - 20 
}, 200);
} );


$('#card_closedval').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table.columns(6).search(val).draw();
$('#myInput').slideToggle();
    $('html, body').animate({ 
    scrollTop: $('#ticketTabsContent').closest('.card2').offset().top - 20 
}, 200);
} );

} // end of data table


$('#store_graph_modal').modal('hide'); 

// crd_btm();
// slct_isp();
// slct_sub();
// gtsub_id();
// admin_hideshowforms();  


get_card_data(yr)
/**
 * Get card data.
 */
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

function open_ticket_modal(data) {
      var tid = data['ticket_no'];
      $('#status').html(window.originalStatusOptions);

      if (data['status'] === 'ON PROCESS') {
          $('#status').html(
              '<option value="ON PROCESS" style="color: #333;">ON PROCESS</option>' +
              '<option value="PENDING" style="color: #333;">PENDING</option>' +
              '<option value="SUBJECT FOR CLOSING" style="color: #333;">SUBJECT FOR CLOSING</option>'
          );
      } else if (data['status'] === 'PENDING') {
          $('#status').html(
              '<option value="PENDING" style="color: #333;">PENDING</option>' +
              '<option value="SUBJECT FOR CLOSING" style="color: #333;">SUBJECT FOR CLOSING</option>'
          );
      }

      $('#subjct').attr('readonly', true);
      $('#ticket_no').val(data['ticket_no']);
      $('#str_num').val(data['store']);
      $('#store').val(data['store']);
      $('#date_createdx').val(data['date_created']);
      $('#subjct').val(data['subject']);
      $('#concern').val(data['concern']);
      $('#via').val(data['via']);
      $('#status').val(data['status']);
      $('#it_num').val(data['itsup']);
      
      console.log("Ticket: " + data['ticket_no'] + " | is_transfer raw value: ", data['is_transfer']);
      
      var isTransferValue = parseInt($.trim(data['is_transfer'])) === 1;

      $('#userModal #is_transfer').prop('checked', isTransferValue).trigger('change');
      if (data['itsup'] && $('#itsup option[value="' + data['itsup'] + '"]').length === 0) {
        $('<option>', {
          value: data['itsup'],
          text: data['it_desc'] ? data['it_desc'] : 'Support ID ' + data['itsup'],
          class: 'temp-option'
        }).appendTo('#itsup');
      }
      $('#itsup').val(data['itsup']);

      $('#cat_num').val(data['cat_id']);
      $('#close_by').val(data['close_by']);
      $('#cl_desc').val(data['clusers']);

      if (data['cat_id'] && $('#cat option[value="' + data['cat_id'] + '"]').length === 0) {
        $('<option>', {
          value: data['cat_id'],
          text: data['category'] ? data['category'] : 'Category ID ' + data['cat_id'],
          class: 'temp-option'
        }).appendTo('#cat');
      }
      $('#cat').val(data['cat_id']);

      $('#sub_num').val(data['sub_id']);
      if (data['sub_id'] && $('#sub option[value="' + data['sub_id'] + '"]').length === 0) {
        $('<option>', {
          value: data['sub_id'],
          text: data['sub_category'] ? data['sub_category'] : 'Sub Category ID ' + data['sub_id'],
          class: 'temp-option'
        }).appendTo('#sub');
      }
      $('#sub').val(data['sub_id']);
      $('#isp_num').val(data['isp_id']);
      $('#isp').val(data['isp_id']);
      $('#refNo').val(data['refNo']);
      $('#date_refNo').val(data['date_refNo']);
      $('#file-input').val("");
      
      if(typeof admin_hideshowforms === "function") admin_hideshowforms();
      
      $('#date_closed').val(data['date_closed']);
      $('#remarks').val(data['remarks']);
      
      $('#remarks_view').show();
      $('.dv_msg').show();
      $('.container_remarks').show();
      $('#msg_thread').slideDown(300);
      
      if(typeof unilayout_netshowmodalform === "function") unilayout_netshowmodalform();

      $('#itsup').off('change').on('change', function () {
        var itfrstsup = $('#it_num').val();
        var itchange = this.value;
        if (itfrstsup != itchange) {
          $('#remarks').attr("placeholder", "Reason for re-assign/ Workoutput");
          $('#remarks').val("");
        } else {
          $('#remarks').val(data['remarks']);
        }
      });

      if ($('#status').val() == 'CLOSED') {
        $(':input[type="submit"]').prop('disabled', true);
        $('#date_createdx, #date_refNo, #date_closed, #remarks').attr('readonly', true);
        $('#store, #via, #status, #itsup, #cat, #sub, #isp, #is_transfer').prop("disabled", true);
      } else {
        $(':input[type="submit"]').prop('disabled', false);
        $('#date_createdx, #date_refNo, #date_closed, #subjct, #remarks').attr('readonly', false);
        $('#store, #via, #status, #itsup, #cat, #sub, #isp, #is_transfer').prop("disabled", false);
      }

      if (typeof getinfo === "function") getinfo(tid, 'remarks', user_id);
      if (typeof gtsub_id === "function") gtsub_id();

      $('.modal-title').text("Ticket Number: " + tid);
      $('#action').val("Save and Reply");
      $('#operation').val("Save and Reply");
      $('#userModal').modal({ "show": true, "backdrop": 'static' });
      loadCommentThread(data['ticket_no']);

      $.ajax({
        type: 'POST',
        url: 'sesticket.php',
        data: { tktval: data['ticket_no'] },
        success: function (response) {
          $('#img').html(response);
        }
      });

      $('#addmsg').val("");
    }

$(function () {
$('#datetimepicker1, #datetimepicker2, #datetimepicker3').datetimepicker()
});




  $(document).on("submit", "#report_form", function (e) {
    e.preventDefault();
    var TicketNumber = $("#ticket_no").val();
    var Store = $("#store").val();
    var DateCreated = $("#date_created").val();
    var Concern = $("#subjct").val();
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
    // else if (DateClosed < DateCreated ){
    //   alert("Date closed should be greater than date created!");
    //   return false;
    // }
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
          // Check if the backend sent a JSON error
          try {
             var response = typeof data === 'string' ? JSON.parse(data) : data;
             if(response.status === 'error') {
                 Swal.fire({ icon: 'error', title: 'Database Error', text: response.message });
                 return; // Stop execution here so the modal stays open!
             }
          } catch(e) { } // If not JSON, continue normally
          
          Swal.fire({
              icon: 'success',
              title: 'Your work has been saved',
              showConfirmButton: false,
              timer: 1500
          });
          $("#userModal").modal("hide");
          getdata();
          get_card_data(yr);
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
endDate.setDate(endDate.getDate() - 1); // Set to yesterday
let startDate = new Date(endDate); // Copy the same date (yesterday)

$('#frompolDate').val(startDate.toISOString().split('T')[0]);
$('#topolDate').val(endDate.toISOString().split('T')[0]);

// Load initial data for yesterday
_polledraph($('#frompolDate').val(), $('#topolDate').val());


$('#frompolDate, #topolDate').change(function() {
    _polledraph($('#frompolDate').val(), $('#topolDate').val());


});



$('.a_sales_monitoring').click(function (e) { 
  e.preventDefault();
//   alert("Sales Monitoring is not available yet.");
$('.ticket_container').fadeOut();
$('#sales_dashboard').fadeIn();

salesdata();

/**
 * Salesdata.
 */
function salesdata(){
  $.post('fetchdata/fetch_data.php', { mode: 'zreading_data' }, function(data){
    console.log(data); // inspect this
    sales_datatable(data); // corrected function name
  }, 'json');
}

var tablex;

/**
 * Sales datatable.
 */
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
