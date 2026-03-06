 
<!-- modal addnew button -->

<script type='text/javascript'>
$( document ).ready(function() {




  




//for debug purposes enable here
// console.log($('#date_created').val());


if(/Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { $("#ovrall").hide(); }

var user_id = <?= $_SESSION['user_id']; ?>

let val = '';
$('#card_totalval').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");

});
$('#card_openval').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
// console.log(val)
});

$('#card_openwfaval').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});
$('#card_closedval').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});
// $('.clcktxt').click(function(e) {
// e.preventDefault();
// val =  $(this).attr("value");
// });
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
table = $("#report_data").DataTable({
  dom:
    "<'dt-top d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2'"+
      "<'dt-left d-flex align-items-center gap-2'l<f>>" +
      "<'dt-right d-flex align-items-center gap-2'B>" +
    ">" +
    "<'dt-table'rt>" +
    "<'dt-bottom d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2'ip>",

  buttons: [

    {
      extend: 'excelHtml5',
      text: '<i class="fas fa-file-excel"></i> <span class="d-none d-md-inline">Export</span>',
      attr: {
        title: 'Export to Excel',
        class: 'btn btn-success btn-sm rounded-pill px-3 shadow-sm'
      }
    }
  ],

  pagingType: "simple_numbers",
  bDestroy: true,
  responsive: {
    details: {
      type: 'inline',
      target: 'tr'
    }
  },
  lengthChange: false,
  autoWidth: false,

  language: {
    search: "",
    searchPlaceholder: "Search tickets…",
    zeroRecords: "No matching tickets found",
    info: "Showing _START_ to _END_ of _TOTAL_ tickets",
    infoEmpty: "No tickets to show"
  },

  pageLength: 10,
  data: dataset,
  order: [[5, "desc"]],

  columns: [
{
    title: "Actions",
    data: null,
    orderable:false,
    width:"130px", // ⭐ VERY IMPORTANT
    className:"text-center",
render:function(data,type,row){

    // ✏️ EDIT
    let editBtn = `
        <button class='btn btn-circle btn-edit'
                name='update'
                title='Edit Ticket'>
            <i class='fas fa-pen'></i>
        </button>
    `;

    // 📞 VIBER
    let viberBtn = row.contactNumber
        ? `
  <a href="#"
     class="btn btn-circle btn-viber viber-call"
     data-ticket_no="${row.ticket_no}"
     data-dept_id="${row.f_deptsel}"
     data-number="${row.contactNumber}"
     title="Call via Viber">
      <i class="fab fa-viber"></i>
  </a>
          `
        : `
            <button class="btn btn-circle btn-disabled"
                    disabled
                    title="No Contact Number">
                <i class="fab fa-viber"></i>
            </button>
          `;

    // ✉️ EMAIL
    let emailBtn = row.dept_email
        ? `
            <a href="mailto:${row.dept_email}?subject=Helpdesk Ticket ${row.ticket_no}"
               class="btn btn-circle btn-email"
               title="Send Email">
                <i class="fas fa-envelope"></i>
            </a>
          `
        : `
            <button class="btn btn-circle btn-disabled"
                    disabled
                    title="No Email">
                <i class="fas fa-envelope"></i>
            </button>
          `;

    return `
        <div class="action-btn-group">
            ${editBtn}
            ${viberBtn}
            ${emailBtn}
        </div>
    `;
}
},

    {
      title: "",
      data: "msg_cnt",
      className: "text-center",
      defaultContent: "",
      render: function (data, type, row) {
        if (type === 'display') {
          return (String(data) === '1')
            ? "<span title='New message'><i class='fas fa-envelope'></i></span>"
            : "";
        }
        return data;
      }
    },
    { title: "Ticket No", data: "ticket_no", defaultContent: "" },
    { title: "Priority Level", data: "priority_desc", defaultContent: "",
      render: function (data, type, row) {
        if (type !== 'display') return data;
        const s = (data || "").toUpperCase();
        let cls = "badge bg-secondary";

        if (s === "CRITICAL") cls = "badge bg-danger";
        else if (s === "HIGH") cls = "badge bg-warning text-dark";
        else if (s === "MEDIUM") cls = "badge bg-warning text-dark";
        else if (s === "LOW") cls = "badge bg-info text-dark";

        return `<span class="${cls} px-2 py-1">${data}</span>`;
      }
    },
    { title: "Store", data: "str_code", defaultContent: "" },
    // { title: "Date Created", data: "date_created", defaultContent: "" },
      {
  title:"Date Created",
  data:"date_created",
  defaultContent:"",
  render: function(data, type, row){

      if(type === 'sort' || type === 'type'){
          // Convert MM/DD/YYYY HH:MM:SS to YYYY-MM-DD HH:MM:SS
          let parts = data.split(" ");
          let date = parts[0].split("/");
          let time = parts[1];

          return date[2] + "-" + date[0] + "-" + date[1] + " " + time;
      }

      return data; // display normally
  }
},
    
    { title: "Subject", data: "subject", defaultContent: "" },
    // { title: "Via", data: "via", defaultContent: "" },

    // ✅ Status pill badge (modern + readable)
    {
      title: "Status",
      data: "status",
      defaultContent: "",
      render: function (data, type, row) {
        if (type !== 'display') return data;

        const s = (data || "").toUpperCase();
        let cls = "badge bg-secondary";

        if (s === "ASSIGNED") cls = "badge bg-warning text-dark";
        else if (s === "CLOSED") cls = "badge bg-success";
        else if (s === "SUBJECT FOR CLOSING") cls = "badge bg-primary";
        else if (s === "ON PROCESS") cls = "badge bg-info";
        else if (s === "ATTENDED WITH FIX ASSET") cls = "badge bg-info text-dark";

        return `<span class="${cls} px-2 py-1">${data}</span>`;
      }
    },

    // ✅ Assigned Department (new) with fallback to old it_desc
    {
      title: "Assigned Dept",
      data: null,
      defaultContent: "",
      render: function (data, type, row) {
        const dept = row.dept_desc || row.it_desc || "";
        return dept;
      }
    },
    { title: "Dept Personnel", data: "category", defaultContent: "" },

    // { title: "Category", data: "category", defaultContent: "" },
    // { title: "Subcategory", data: "sub_category", defaultContent: "" },

    // Date Closed clean
    {
      title: "Date Closed",
      data: "date_closed",
      defaultContent: "",
      render: function (data, type, row) {
        if (type !== 'display') return data;

        if (!data) return "";
        if (data === "01/01/1970 01:00" || data === "01/01/1970 08:00") return "";
        return data;
      }
    },

    // Days Completion clean + human-friendly
{
  title: "Days",
  data: "tdc",
  defaultContent: "",
  render: function (data, type, row) {
    // keep sorting numeric
    if (type !== 'display') return data;

    if (data === null || data === undefined || data === "") return "";

    const n = parseInt(data, 10);
    if (isNaN(n) || n < 0) return "";

    const isOpen = (row.status || '').toUpperCase() !== 'CLOSED';
    const dayWord = (n === 1) ? "Day" : "Days";

    return isOpen ? `${n} ${dayWord} Unresolved` : `${n} ${dayWord}`;
  }
},


    {
      title: "Work Output",
      data: "remarks",
      defaultContent: "",
      render: function (data, type, row) {
        if (type !== 'display') return data;
        if (!data) return "";
        // compact view
        const txt = String(data);
        return txt.length > 60 ? (txt.slice(0, 60) + "…") : txt;
      }
    }
  ],

  rowCallback: function (row, data) {
    // reset classes
    $(row).removeClass('status-open status-closed status-subject-closing status-fixed');

    const s = (data['status'] || "").toUpperCase();

    if (s === 'ASSIGNED') $(row).addClass('status-open');
    else if (s === 'ATTENDED WITH FIX ASSET') $(row).addClass('status-fixed');
    else if (s === 'CLOSED') $(row).addClass('status-closed');
    else if (s === 'SUBJECT FOR CLOSING') $(row).addClass('status-subject-closing');
  }
});


$('#report_data tbody').on('dblclick', 'tr', function () {

  // Simulate the original `button` click by using this `tr` as parent
  var data = table.row($(this)).data();
  if (!data) return;
  // console.log(data);
  $('#subjct').attr('readonly', true);
  var tid = $(this).find('td:eq(2)').html(); 
  $('#ticket_no').val(data['ticket_no']);
  $('#str_num').val(data['store']);
  $('#store').val(data['store']);
  $('#date_createdx').val(data['date_created']);
  $('#subjct').val(data['subject']);
  $('#concern').val(data['concern']);
  // $('#via').val(data['via']);
  $('#status').val(data['status']);
  // console.log(data['priority_desc']);
  $('#priority_desc').val(data['priority_desc']);
  $('#close_by').val(data['close_by']);

  admin_hideshowforms();
  $('#date_closed').val(data['date_closed']);
  $('#remarks').val(data['remarks']);
  // $('#remarks').val('');
  // unilayout_netshowmodalform();



  if($('#status').val() == 'CLOSED') {
    $(':input[type="submit"]').prop('disabled', true); 
    $('#date_createdx').attr('readonly', true);
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
    $('#date_createdx').attr('readonly', false);
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
  // var sst = document.querySelector("#sub");  
  var option = document.createElement("option");
  option.value = 0;
  option.id = 'tmpsubid';
  option.selected = 'selected';
  option.text = $(this).find('td:eq(10)').html();
  // sst.add(option);   

  getinfo(tid, 'remarks', user_id);
  
  // gtsub_id();

  $('.modal-title').text("Ticket Number: " + tid);
  $('#action').val("Save and Reply");
  $('#operation').val("Save and Reply"); 
  $('#userModal').modal({ "show": true, "backdrop": 'static' });

  var valtick = $('#ticket_no').val();

  $.ajax({
      type: 'POST',
      url: 'sesticket.php',
      data: {tktval: valtick},
      success: function(response) {
        $('#img').html(response);
      }
    });

$('#msgbtn').show();
$('msg_thread').show();
$('.dv_msg').show();
$('#remarks_view').show();
$('#addmsg').val("");

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
// var val =  $(this).attr("value");
var val =  ('ASSIGNED');
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




$('#store_graph_modal').modal('hide'); 

// crd_btm();
// slct_isp();
// slct_itsup();
// slct_sub();
// gtsub_id();
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
$('#count_total').html(a[0].owfa_res);
$('#count_open').html(a[0].open_res);
$('#count_owfa').html(a[0].t_pending);
$('#count_closed').html(a[0].cls_res);
$('#today_closed').html(a[0].t_res);


});
}

$(function () {
$('#datetimepicker1, #datetimepicker2, #datetimepicker3').datetimepicker()
});

$("#yearpicker").on('change',function(){
const yr =$("#yearpicker").val()
console.log(yr);
// reports_total(this.value);
getdata(yr);
// get_card_data(this.value);
// _techgraph(yr);
// _overallpie(yr);
// _dbline(yr); 
// _catpie(yr);
// _areagraph(yr);
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
    else if (Status == 'ASSIGNED'){
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
    //  clearconsole();
  });

// $(document).on('click', '#dtbsecond', function(){

// // // Store clicked page
// // var currentPage = $(this).attr("#userModal");
// // // Build filepath
// // var currentPagePath = "./it/" + currentPage + ".php";

// // // Find the div I want to change the include for and update it
// // $(".testtkt").load(currentPagePath);



// // $('#msgbtn').show();
// // $('msg_thread').show();
// // $('.dv_msg').show();
// // $('#remarks_view').show();
// // $('#addmsg').val("");

// // alert(valtick);

//   // var valtick = $('#ticket_no').val();




// });


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




let activeCall = null; // {call_id, start_ms}

function formatDuration(ms){
  const totalSec = Math.floor(ms / 1000);
  const m = String(Math.floor(totalSec / 60)).padStart(2,'0');
  const s = String(totalSec % 60).padStart(2,'0');
  return `${m}:${s}`;
}

function showEndCallSwal(){
  if(!activeCall) return;

  let timerInterval = null;

  Swal.fire({
    title: 'End Call',
    html: `
      <div style="font-size:14px; margin-bottom:8px;">
        <b>Duration:</b> <span id="callDuration">00:00</span>
      </div>

      <select id="callStatus" class="swal2-select">
        <option value="ANSWERED">Answered</option>
        <option value="NO_ANSWER">No Answer</option>
        <option value="BUSY">Busy</option>
        <option value="FAILED">Failed</option>
        <option value="VOICEMAIL">Voicemail</option>
      </select>
    `,
    showCancelButton: true,
    confirmButtonText: 'Hang Up & Save',
    cancelButtonText: 'Not yet',
    allowOutsideClick: false,
    didOpen: () => {
      const durEl = document.getElementById('callDuration');
      timerInterval = setInterval(() => {
        durEl.textContent = formatDuration(Date.now() - activeCall.start_ms);
      }, 500);
    },
    willClose: () => {
      if(timerInterval) clearInterval(timerInterval);
    },
    preConfirm: () => {
      const status = document.getElementById('callStatus').value;
      return status;
    }
  }).then((result) => {
    if(result.isConfirmed){
      const status = result.value;

      $.ajax({
        url: 'fetchdata/update_call.php',
        type: 'POST',
        data: {
          call_id: activeCall.call_id,
          call_status: status
        },
        success: function(){
          Swal.fire('Saved', 'Call log updated.', 'success');
          activeCall = null;
        },
        error: function(xhr){
          Swal.fire('Error', xhr.responseText || 'Failed to update call log.', 'error');
        }
      });
    }
  });
}

// 1) log + open viber
$(document).on('click', '.viber-call', function(e){
  e.preventDefault();

  const ticket_no = $(this).data('ticket_no');
  const dept_id   = $(this).data('dept_id');
  const number    = $(this).data('number');

  $.ajax({
    url: 'fetchdata/log_call.php',
    type: 'POST',
    dataType: 'json',
    data: { ticket_no, dept_id },
    success: function(res){
      // store active call
      activeCall = {
        call_id: res.call_id,
        start_ms: Date.parse(res.call_startdate) || Date.now()
      };

      // open viber
      window.location.href = `viber://chat?number=%2B${number}`;
    }
  });
});

// 2) when user returns to browser tab/window, prompt to end call
window.addEventListener('focus', function(){
  // if there is an active call, ask to end it
  if(activeCall){
    showEndCallSwal();
  }
});



});//document ready close



</script>