 
<!-- modal addnew button -->

<script type='text/javascript'>
$( document ).ready(function() {



  const toggle = document.getElementById('darkModeToggle');
  const body = document.body;

  // Load theme preference from localStorage
  // if (localStorage.getItem('theme') === 'dark') {
  //   body.classList.add('dark-mode');
  //   toggle.checked = true;
  // }

  // toggle.addEventListener('change', () => {
  //   if (toggle.checked) {
  //     body.classList.add('dark-mode');
  //     localStorage.setItem('theme', 'dark');
  //   } else {
  //     body.classList.remove('dark-mode');
  //     localStorage.setItem('theme', 'light');
  //   }
  // });
  

  



  verQTY();

  function verQTY(){

let  formxxx= document.getElementById('cof_form');
let formData = new FormData(formxxx); 
$.ajax({
      url: "insert.php",
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (data) {
      },
      error: function (error) {
     
}
    });



}


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
// console.log(data);
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
  order: [[2, "desc"]],

  columns: [
    {
      title: "",
      data: null,
      orderable: false,
      className: "text-center",
      defaultContent:
        "<button class='btn btn-outline-danger btn-sm rounded-circle' name='update' title='Edit'>" +
          "<i class='fas fa-edit'></i>" +
        "</button>"
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
    { title: "Store", data: "str_code", defaultContent: "" },
    { title: "Date Created", data: "date_created", defaultContent: "" },
    { title: "Subject", data: "subject", defaultContent: "" },
    { title: "Via", data: "via", defaultContent: "" },

    // ✅ Status pill badge (modern + readable)
    {
      title: "Status",
      data: "status",
      defaultContent: "",
      render: function (data, type, row) {
        if (type !== 'display') return data;

        const s = (data || "").toUpperCase();
        let cls = "badge bg-secondary";

        if (s === "ON PROCESS") cls = "badge bg-warning";
        else if (s === "CLOSED") cls = "badge bg-success";
        else if (s === "SUBJECT FOR CLOSING") cls = "badge bg-primary";
        else if (s === "PENDING") cls = "badge bg-danger";

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

    { title: "Category", data: "category", defaultContent: "" },
    { title: "Subcategory", data: "sub_category", defaultContent: "" },

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
        if (type !== 'display') return data;

        if (!data) return "";
        let d = String(data);

        // Normalize
        if (d === "0" || d === "0 Days Unresolved") return "";
        if (d === "1 Days Unresolved") return "1 Day Unresolved";
        if (d.includes("01/01/1970")) return "";

        // If numeric negative or zero
        const n = parseInt(d, 10);
        if (!isNaN(n) && n <= 0) return "";

        return d;
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
    $(row).removeClass('status-open status-closed status-subject-closing status-pending');

    const s = (data['status'] || "").toUpperCase();

    if (s === 'ON PROCESS') $(row).addClass('status-open');
    else if (s === 'PENDING') $(row).addClass('status-pending');
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
  // $('#remarks').val('');
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
  var sst = document.querySelector("#sub");  
  var option = document.createElement("option");
  option.value = 0;
  option.id = 'tmpsubid';
  option.selected = 'selected';
  option.text = $(this).find('td:eq(10)').html();
  sst.add(option);   

  getinfo(tid, 'remarks', user_id);
  
  gtsub_id();

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


// function crd_btm(){
//     console.log("bottom");
// }

$('#store_graph_modal').modal('hide'); 

// crd_btm();
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
$('#today_closed').html(a[0].t_res);


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


$('#subpie_clsbtn').click(function(event) {
event.preventDefault();
$('#chartdiv9').empty();

});

$('#substr_clsbtn').click(function(event) {
event.preventDefault();
$('#substr_clsbtn').empty();

});


$('#action').click(function () { 
        var files = $('#file-input')[0].files;
        var tktno = $('#ticket_no').val();
        var formData = new FormData();

        for (var i = 0; i < files.length; i++) {
            formData.append('files[]',files[i]);
            
        }
        formData.append('ticket_no',tktno);


        $.ajax({
            type: "POST",
            url: "insertimg.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                // alert(response);
            }
        });
        
    });

    //validition for file upload size
    var uploadField = document.getElementById("file-input");

    uploadField.onchange = function() {

      for (var i = 0; i < $("#file-input").get(0).files.length; ++i) {
                var file1=$("#file-input").get(0).files[i].name;

                if(file1){                        
                    var file_size=$("#file-input").get(0).files[i].size;
                    if(file_size<2097152){
                        var ext = file1.split('.').pop().toLowerCase();                            
                        if($.inArray(ext,['jpg','jpeg','gif','png', 'txt', 'pdf', 'docx', 'doc', 'xlsx', 'xls'])===-1){
                            alert("Invalid file extension");
                            this.value = "";
                            return false;
                        }

                    }else{
                        alert("File must not exceed 2MB");
                        this.value = "";
                        return false;
                    }                        
                }
            }
  
};
// end of validition for file upload size

let endDate = new Date();
endDate.setDate(endDate.getDate() - 1); // Set to yesterday
let startDate = new Date(endDate); // Copy the same date (yesterday)

$('#frompolDate').val(startDate.toISOString().split('T')[0]);
$('#topolDate').val(endDate.toISOString().split('T')[0]);

// Load initial data for yesterday
_polledraph($('#frompolDate').val(), $('#topolDate').val());


// Add event listeners for date changes
$('#frompolDate, #topolDate').change(function() {
  // alert("GOOD");
    _polledraph($('#frompolDate').val(), $('#topolDate').val());



});


});//document ready close



</script>