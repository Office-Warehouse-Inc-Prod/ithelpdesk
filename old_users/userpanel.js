$(document).ready(function(){
  $('#dvtables').show();
  $('#reports_table').show();

  // let operationVAL =  $('#sop').val('minus');
  


$('#DescN').hide();
$('#Desc').hide();
$('#AluN').hide();
$('#Alu').hide();
$('#SerialNo').hide();
$('#SerialLbl').hide();
$('#TypesUnit').hide();
$('#TypesOfUnit').hide();
$('#subjectimp').hide();


$("#deptsel").change(function (e) { 
  
  var iN = $("#deptsel").val();  
  
    
if (iN == "4") {
  $('#DescN').fadeIn();
  $('#Desc').fadeIn();
  $('#Alu').fadeIn();
  $('#AluN').fadeIn();
  $('#SerialNo').fadeIn();
  $('#SerialLbl').fadeIn();
  $('#TypesUnit').fadeIn();
  $('#TypesOfUnit').fadeIn();
}
else{
  $('#DescN').hide();
  $('#Alu').hide();
  $('#Desc').hide();
  $('#AluN').hide();
  $('#SerialNo').hide();
  $('#SerialLbl').hide();
  $('#TypesUnit').hide();
  $('#TypesOfUnit').hide();
}


});



$("#select_tos").change(function (e) { 
  // e.preventDefault();
  var Tos = $("#select_tos").val();

  if (Tos == "REPAIR IMPORT") {
    
    $('#subjectimp').fadeIn();
    $('#subject').hide();
    
  }
  else {
    $('#subjectimp').hide();
    $('#subject').fadeIn();

  }
});

$("#subjectimp").change(function (e) {

  var sbjct = $('#subjectimp').val();
  $('#subject').val(sbjct);

});

$(function(){
    var validation_el = $('<div>')
        validation_el.addClass('validation-err alert alert-danger my-2')
        validation_el.hide()
    var timeout;
    $('input[name="Alu"]').on('input',function(){
        var Alu = $(this).val()
            $(this).removeClass("border-danger border-success")
            $(this).siblings(".validation-err").remove();
        var err_el = validation_el.clone()

        clearTimeout(timeout); // Clear the previous timeout

        timeout = setTimeout(() => { // Set a new timeout
            if(Alu == '')
            return false;

            $.ajax({
                url:"alu_validate.php",
                method:'POST',
                data:{Alu:Alu},
                dataType:'json',
                error:err=>{
                    console.error(err)
                    alert("An error occured while validating the data")
                },
                success:function(resp){
                    if(Object.keys(resp).length > 0 && resp.field_name == 'Alu'){
                        err_el.text(resp.msg)
                        $('input[name="Alu"]').addClass('border-danger')
                        $('input[name="Alu"]').after(err_el)
                        err_el.show('slideDown')
                        $('#action').attr('disabled', 'true')
                    }else{
                        $('input[name="Alu"]').addClass('border-success')
                        $('#action').attr('disabled',false)
                    }
                }
            })
        }, 1000); // The validation will be triggered after 500 milliseconds (0.5 seconds)
    })
})


$('#Alu').keyup(function (e) { 


let alu = this.value;


getdesc(alu);

function getdesc(){
$.post('fetch.php',{alu:alu, operation:'search_desc'},function(data){
  // console.log(data);



let desc = jQuery.parseJSON(data); 
const aludesc = desc;
$('#Desc').val(aludesc[0].Desc1);
  




// console.log(desc);

});
}



});



  

 validationLength = 255;

$('#concern').on('keyup keydown change', function () {
    if($(this).val().length > validationLength){

        val=$(this).val().substr(0,$(this).val().length-1);
        $(this).val(val);
    };
});

function hide(){
var earrings = document.getElementById('logistic');
logistic.style.visibility = 'hidden';
}

var uid = <?= $_SESSION['user_id'] ?>;

const customElement = $("<div>", {
"class" : "spinner-grow spinner-grow-lg",
"text"  : ""
});


$("#deptsel").change(function (e) { 
e.preventDefault();

let deptval = this.value;
_get_tos(deptval);
//  console.log(deptval);

switch (deptval) {
  case '1':
  $('#dvtables').show();
  $('#reports_table').show();
  // $('#sop').val('Add');
  
    break;
  case '2':

  // $('#sop').val('admin_add');
    break;
    case '3':
  // $('#sop').val('mktg_add');
    break;
    case '4':
  // $('#sop').val('ld_add');
    break;


  default:
    break;
}

// console.log(operationVAL)

});




function _get_tos(deptval){
  $.post('fetch.php',{deptval:deptval, operation:'get_tos'},function(data){
    
    var sel = $("#select_tos");
    sel.empty();
    sel.append('<option selected disabled value="0">Select TOS Here...</option>');
    for (var i=0; i<data.length; i++) {
    //   sel.append('<option value="' + data[i].str_no + '">' + data[i].str_name + '</option>');
      sel.append('<option value="' + data[i].service_desc + '">' + data[i].service_desc + '</option>');
    }

  },'json');
}






$("#stat_picker").change(function(){


getdata(this.value)
})

$('#action').val("Save ticket");
$('#sop').val("Add");
$("#subject").alphanum({
allow      : "!€$£/-",
disallow   : "",
allowUpper : true
// allowSpace : false
});
$("#concern").alphanum({
allow      : "!?€$£/-/,.",
disallow   : "",
allowUpper : true
// allowSpace : false
});
$("#Modal_reply").alphanum({
allow      : "!?€$£/-/,.",
disallow   : "",
allowUpper : true
// allowSpace : false
});

function getdata(fltr=null){
$.post('fetch.php',{operation:'',filter:fltr},function(data){
tbl( data)
},'json');
}
getdata();

var table;
function tbl(t){


const dataset=t.rptdata;
table =  $("#reports_table").DataTable({

"dom":
'<"pull-left"lf><"pull-right">tip',

stateSave: true,
"bDestroy": true,
"responsive": true, "lengthChange": false, "autoWidth": false,
language: {
search: "_INPUT_",
searchPlaceholder: "Search..."
},
pageLength: 5,
data: dataset,
  "order": [[ 0, "Desc" ]],
columns: [
{title:"Date Created",data: "Dt_Created"},
{ title:"Ticket Number",data: "TicketNum" },
{ title:"ASSIGNED TO:",data: "deptsel_val" },
{ title:"SUBJECT",data: "Concern" },
{ title:"Types of Service",data: "Tos" },
{ title:"STATUS",data: "Status" },
{ title:"ASSIGNED SUPPORT",data: "AsgnSup" }

],

// "columnDefs": [
//        { 

//            targets: [2],
//            render: function ( data, type, row) {
//                if(type === 'display'){
//                    if(data['brncd_dptdesc']==data.substr(0,3)+"| STORE OPERATION"){
//                     return data.substr(0,3)
//                    }
//                    else {
//                    return data
//                    }
//            }
//            return data;
//          }
//        }
//    ],
rowCallback: function(row, data, index){
// console.log(data['NewRpt'])
if(data['Status'].toUpperCase() == 'NEW REPORT' && data['NewRpt'] =='1'){

$(row).find('td:eq(0)').css("font-weight", "bold");
$(row).find('td:eq(1)').css("font-weight", "bold");
$(row).find('td:eq(2)').css("font-weight", "bold");
$(row).find('td:eq(3)').css("font-weight", "bold");
$(row).find('td:eq(4)').css("font-weight", "bold");
$(row).find('td:eq(5)').css("font-weight", "bold");
$(row).find('td:eq(6)').css("font-weight", "bold");
$(row).find('td:eq(7)').css("font-weight", "bold");
$(row).find('td:eq(8)').css("font-weight", "bold");

}

else if (data['Status'].toUpperCase() == 'OPEN' && data['NewRpt'] =='0' ){
$(row).find('td:eq(0)').css("color", "red")
.addClass('fas fa-envelope');
$(row).find('td:eq(1)').css({"font-weight": "bold", "color": "red"});
$(row).find('td:eq(2)').css({"font-weight": "bold", "color": "red"});
$(row).find('td:eq(3)').css({"font-weight": "bold", "color": "red"});
$(row).find('td:eq(4)').css({"font-weight": "bold", "color": "red"});
$(row).find('td:eq(5)').css({"font-weight": "bold", "color": "red"});
$(row).find('td:eq(6)').css({"font-weight": "bold", "color": "red"});
$(row).find('td:eq(7)').css({"font-weight": "bold", "color": "red"});
$(row).find('td:eq(8)').css({"font-weight": "bold", "color": "red"});
}
else if (data['Status'].toUpperCase() == 'OPEN' && data['NewRpt'] =='1' ){
$(row).find('td:eq(0)').css({"font-weight": "bold", "color": "red"});
$(row).find('td:eq(1)').css({"font-weight": "bold", "color": "red"});
$(row).find('td:eq(2)').css({"font-weight": "bold", "color": "red"});
$(row).find('td:eq(3)').css({"font-weight": "bold", "color": "red"});
$(row).find('td:eq(4)').css({"font-weight": "bold", "color": "red"});
$(row).find('td:eq(5)').css({"font-weight": "bold", "color": "red"});
$(row).find('td:eq(6)').css({"font-weight": "bold", "color": "red"});
$(row).find('td:eq(7)').css({"font-weight": "bold", "color": "red"});
$(row).find('td:eq(8)').css({"font-weight": "bold", "color": "red"});
}
else if (data['Status'].toUpperCase() == 'SCHEDULE FOR PULL OUT' ){
$(row).find('td:eq(0)').css({"font-weight": "bold", "color": "#1597BB"});
$(row).find('td:eq(1)').css({"font-weight": "bold", "color": "#1597BB"});
$(row).find('td:eq(2)').css({"font-weight": "bold", "color": "#1597BB"});
$(row).find('td:eq(3)').css({"font-weight": "bold", "color": "#1597BB"});
$(row).find('td:eq(4)').css({"font-weight": "bold", "color": "#1597BB"});
$(row).find('td:eq(5)').css({"font-weight": "bold", "color": "#1597BB"});
$(row).find('td:eq(6)').css({"font-weight": "bold", "color": "#1597BB"});
$(row).find('td:eq(7)').css({"font-weight": "bold", "color": "#1597BB"});
$(row).find('td:eq(8)').css({"font-weight": "bold", "color": "#1597BB"});
}
else if (data['Status'].toUpperCase() == 'DIRECT PULL OUT' ){
$(row).find('td:eq(0)').css({"font-weight": "bold", "color": "#31363F"});
$(row).find('td:eq(1)').css({"font-weight": "bold", "color": "#31363F"});
$(row).find('td:eq(2)').css({"font-weight": "bold", "color": "#31363F"});
$(row).find('td:eq(3)').css({"font-weight": "bold", "color": "#31363F"});
$(row).find('td:eq(4)').css({"font-weight": "bold", "color": "#31363F"});
$(row).find('td:eq(5)').css({"font-weight": "bold", "color": "#31363F"});
$(row).find('td:eq(6)').css({"font-weight": "bold", "color": "#31363F"});
$(row).find('td:eq(7)').css({"font-weight": "bold", "color": "#31363F"});
$(row).find('td:eq(8)').css({"font-weight": "bold", "color": "#31363F"});
}
else if (data['Status'].toUpperCase() == 'READY TO PICK UP' ){
$(row).find('td:eq(0)').css({"font-weight": "bold", "color": "#78A083"});
$(row).find('td:eq(1)').css({"font-weight": "bold", "color": "#78A083"});
$(row).find('td:eq(2)').css({"font-weight": "bold", "color": "#78A083"});
$(row).find('td:eq(3)').css({"font-weight": "bold", "color": "#78A083"});
$(row).find('td:eq(4)').css({"font-weight": "bold", "color": "#78A083"});
$(row).find('td:eq(5)').css({"font-weight": "bold", "color": "#78A083"});
$(row).find('td:eq(6)').css({"font-weight": "bold", "color": "#78A083"});
$(row).find('td:eq(7)').css({"font-weight": "bold", "color": "#78A083"});
$(row).find('td:eq(8)').css({"font-weight": "bold", "color": "#78A083"});
}
else if (data['Status'].toUpperCase() == 'ALREADY PICK UP' ){
$(row).find('td:eq(0)').css({"font-weight": "bold", "color": "#F6B17A"});
$(row).find('td:eq(1)').css({"font-weight": "bold", "color": "#F6B17A"});
$(row).find('td:eq(2)').css({"font-weight": "bold", "color": "#F6B17A"});
$(row).find('td:eq(3)').css({"font-weight": "bold", "color": "#F6B17A"});
$(row).find('td:eq(4)').css({"font-weight": "bold", "color": "#F6B17A"});
$(row).find('td:eq(5)').css({"font-weight": "bold", "color": "#F6B17A"});
$(row).find('td:eq(6)').css({"font-weight": "bold", "color": "#F6B17A"});
$(row).find('td:eq(7)').css({"font-weight": "bold", "color": "#F6B17A"});
$(row).find('td:eq(8)').css({"font-weight": "bold", "color": "#F6B17A"});
}
else if (data['Status'].toUpperCase() == 'REPAIRED SAME UNIT AND SERIAL' ){
$(row).find('td:eq(0)').css({"font-weight": "bold", "color": "#662549"});
$(row).find('td:eq(1)').css({"font-weight": "bold", "color": "#662549"});
$(row).find('td:eq(2)').css({"font-weight": "bold", "color": "#662549"});
$(row).find('td:eq(3)').css({"font-weight": "bold", "color": "#662549"});
$(row).find('td:eq(4)').css({"font-weight": "bold", "color": "#662549"});
$(row).find('td:eq(5)').css({"font-weight": "bold", "color": "#662549"});
$(row).find('td:eq(6)').css({"font-weight": "bold", "color": "#662549"});
$(row).find('td:eq(7)').css({"font-weight": "bold", "color": "#662549"});
$(row).find('td:eq(8)').css({"font-weight": "bold", "color": "#662549"});
}
else if (data['Status'].toUpperCase() == 'REPAIRED SAME UNIT DIFFERENT SERIAL' ){
$(row).find('td:eq(0)').css({"font-weight": "bold", "color": "#AE445A"});
$(row).find('td:eq(1)').css({"font-weight": "bold", "color": "#AE445A"});
$(row).find('td:eq(2)').css({"font-weight": "bold", "color": "#AE445A"});
$(row).find('td:eq(3)').css({"font-weight": "bold", "color": "#AE445A"});
$(row).find('td:eq(4)').css({"font-weight": "bold", "color": "#AE445A"});
$(row).find('td:eq(5)').css({"font-weight": "bold", "color": "#AE445A"});
$(row).find('td:eq(6)').css({"font-weight": "bold", "color": "#AE445A"});
$(row).find('td:eq(7)').css({"font-weight": "bold", "color": "#AE445A"});
$(row).find('td:eq(8)').css({"font-weight": "bold", "color": "#AE445A"});
}
else if (data['Status'].toUpperCase() == 'REPLACE' ){
$(row).find('td:eq(0)').css({"font-weight": "bold", "color": "#451952"});
$(row).find('td:eq(1)').css({"font-weight": "bold", "color": "#451952"});
$(row).find('td:eq(2)').css({"font-weight": "bold", "color": "#451952"});
$(row).find('td:eq(3)').css({"font-weight": "bold", "color": "#451952"});
$(row).find('td:eq(4)').css({"font-weight": "bold", "color": "#451952"});
$(row).find('td:eq(5)').css({"font-weight": "bold", "color": "#451952"});
$(row).find('td:eq(6)').css({"font-weight": "bold", "color": "#451952"});
$(row).find('td:eq(7)').css({"font-weight": "bold", "color": "#451952"});
$(row).find('td:eq(8)').css({"font-weight": "bold", "color": "#451952"});
}
else if (data['Status'].toUpperCase() == 'RTV' ){
$(row).find('td:eq(0)').css({"font-weight": "bold", "color": "#F39F5A"});
$(row).find('td:eq(1)').css({"font-weight": "bold", "color": "#F39F5A"});
$(row).find('td:eq(2)').css({"font-weight": "bold", "color": "#F39F5A"});
$(row).find('td:eq(3)').css({"font-weight": "bold", "color": "#F39F5A"});
$(row).find('td:eq(4)').css({"font-weight": "bold", "color": "#F39F5A"});
$(row).find('td:eq(5)').css({"font-weight": "bold", "color": "#F39F5A"});
$(row).find('td:eq(6)').css({"font-weight": "bold", "color": "#F39F5A"});
$(row).find('td:eq(7)').css({"font-weight": "bold", "color": "#F39F5A"});
$(row).find('td:eq(8)').css({"font-weight": "bold", "color": "#F39F5A"});
}
else if (data['Status'].toUpperCase() == 'RETURN TO STORE' ){
$(row).find('td:eq(0)').css({"font-weight": "bold", "color": "#890188"});
$(row).find('td:eq(1)').css({"font-weight": "bold", "color": "#890188"});
$(row).find('td:eq(2)').css({"font-weight": "bold", "color": "#890188"});
$(row).find('td:eq(3)').css({"font-weight": "bold", "color": "#890188"});
$(row).find('td:eq(4)').css({"font-weight": "bold", "color": "#890188"});
$(row).find('td:eq(5)').css({"font-weight": "bold", "color": "#890188"});
$(row).find('td:eq(6)').css({"font-weight": "bold", "color": "#890188"});
$(row).find('td:eq(7)').css({"font-weight": "bold", "color": "#890188"});
$(row).find('td:eq(8)').css({"font-weight": "bold", "color": "#890188"});
}
else if (data['Status'].toUpperCase() == 'ON PROCESS' ){
$(row).find('td:eq(0)').css({"font-weight": "bold", "color": "#FFC107"});
$(row).find('td:eq(1)').css({"font-weight": "bold", "color": "#FFC107"});
$(row).find('td:eq(2)').css({"font-weight": "bold", "color": "#FFC107"});
$(row).find('td:eq(3)').css({"font-weight": "bold", "color": "#FFC107"});
$(row).find('td:eq(4)').css({"font-weight": "bold", "color": "#FFC107"});
$(row).find('td:eq(5)').css({"font-weight": "bold", "color": "#FFC107"});
$(row).find('td:eq(6)').css({"font-weight": "bold", "color": "#FFC107"});
$(row).find('td:eq(7)').css({"font-weight": "bold", "color": "#FFC107"});
$(row).find('td:eq(8)').css({"font-weight": "bold", "color": "#FFC107"});
}
else if (data['Status'].toUpperCase() == 'SUBJECT FOR CLOSING' ){
$(row).find('td:eq(0)').css({"font-weight": "bold", "color": "#890188"});
$(row).find('td:eq(1)').css({"font-weight": "bold", "color": "#890188"});
$(row).find('td:eq(2)').css({"font-weight": "bold", "color": "#890188"});
$(row).find('td:eq(3)').css({"font-weight": "bold", "color": "#890188"});
$(row).find('td:eq(4)').css({"font-weight": "bold", "color": "#890188"});
$(row).find('td:eq(5)').css({"font-weight": "bold", "color": "#890188"});
$(row).find('td:eq(6)').css({"font-weight": "bold", "color": "#890188"});
$(row).find('td:eq(7)').css({"font-weight": "bold", "color": "#890188"});
$(row).find('td:eq(8)').css({"font-weight": "bold", "color": "#890188"});
}
}


});
}

$("#addmsg").click(function(){
if ($("#slctdtick").val()==""){
noslctd('#msg')
$('#msg').append('<div class=" alert alert-warning  col-md-12"><i class="fa fa-exclamation-circle fa-lg" aria-hidden="true"></i> Select ticket first </div>')
return false;
}

getinfo($("#slctdtick").val(),'remarks',uid);

$('#ticket_modal').modal('show')
});

function  noslctd(thisid){
var del = $(thisid);
del.empty();
del.addClass('alert');

$('.alert').fadeIn('slow',function(){ $(this).delay(3000).fadeOut('slow',function(){
});
});
del.removeClass('alert');
}

setInterval(function () { // refresh datatables every 15 sec.
// table.ajax.reload();
getdata();


}, 15000);
function valtxt(){
if($('#subject').val().trim()==""){
$('#subject').addClass('border-danger');
setTimeout(() => {
$('#subject').removeClass('border-danger');
}, 5000);
return false;

}else if ($('#select_tos').val().trim()==""){
$('#select_tos').addClass('border-danger');
setTimeout(() => {
$('#select_tos').removeClass('border-danger');
}, 5000);
return false;

}else if ($('#concern').val().trim()==""){
$('#concern').addClass('border-danger');
setTimeout(() => {
$('#concern').removeClass('border-danger');
}, 5000);
return false;

}
return true
}
$('#reports_table').on( 'click','tbody tr',function () {

$("#reports_table tbody tr").removeClass('row_selected');        
$(this).addClass('row_selected');
var tdata =table.row(this).data();
$('#slctdtick').val(tdata['TicketNum']);      
$('#ModalTicket_no').val(tdata['TicketNum']);  
$('#ModalDate_create').val(tdata['Dt_Created']);
$('#ModalStore').val(tdata['Scode']);
$('#ModalSubject').val(tdata['Concern']);
$('#ModalTOS').val(tdata['Tos']);
$('#ModalStatus').val(tdata['Status']);

let tickres1 = tdata['TicketNum'];
let ntickres = $('#nticknum').val(tickres1);
let statres1 =  tdata['Status'];
let statOpsres = $('#statOps').val(statres1);
// console.log(statres1);
if (statres1 == "SUBJECT FOR CLOSING") {
  var isGood=confirm('Would you like to close this ticket:'+' '+tickres1);
    if (isGood) {
      // alert('true');
      var data = statres1;
      $.ajax({
        url: "insert.php",
        method: "POST",
        data: new FormData(stat_form),
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
          getdata();
      //     setTimeout(function(){// wait for 5 secs(2)
      //      location.reload(); // then reload the page.(3)
      // }, 2000); 
        },
      });


    } else  {
      // alert('false');
    }
}

else if (statres1 == "READY TO PICK UP") {
  var isGood=confirm('Would you like to confirm pick up this item on ticket:'+' '+tickres1);
    if (isGood) {
      // alert('true');
      var data = statres1;
      $.ajax({
        url: "insert.php",
        method: "POST",
        data: new FormData(stat_form),
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
          getdata();
      //     setTimeout(function(){// wait for 5 secs(2)
      //      location.reload(); // then reload the page.(3)
      // }, 2000); 
        },
      });


    } else  {
      alert('false');
    }
}

else if (statres1 == "DIRECT PULL OUT") {
  var isGood=confirm('Would you like to confirm pick up this item on ticket:'+' '+tickres1);
    if (isGood) {
      // alert('true');
      var data = statres1;
      $.ajax({
        url: "insert.php",
        method: "POST",
        data: new FormData(stat_form),
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
          getdata();
      //     setTimeout(function(){// wait for 5 secs(2)
      //      location.reload(); // then reload the page.(3)
      // }, 2000); 
        },
      });


    } else  {
      alert('false');
    }
}

else if (statres1 == "RETURN TO STORE") {
  var isGood=confirm('Would you like to confirm this return item on ticket:'+' '+tickres1);
    if (isGood) {
      // alert('true');
      var data = statres1;
      $.ajax({
        url: "insert.php",
        method: "POST",
        data: new FormData(stat_form),
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
          getdata();
      //     setTimeout(function(){// wait for 5 secs(2)
      //      location.reload(); // then reload the page.(3)
      // }, 2000); 
        },
      });


    } else  {
      alert('false');
    }
}

else if (statres1 == "RETURN BY SUPPLIER") {
  var isGood=confirm('Would you like to confirm this return item on ticket:'+' '+tickres1);
    if (isGood) {
      // alert('true');
      var data = statres1;
      $.ajax({
        url: "insert.php",
        method: "POST",
        data: new FormData(stat_form),
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
          getdata();
      //     setTimeout(function(){// wait for 5 secs(2)
      //      location.reload(); // then reload the page.(3)
      // }, 2000); 
        },
      });


    } else  {
      alert('false');
    }
}

(tdata['Status']=='CLOSED') ? $("#addmsg").attr('disabled',true):$("#addmsg").attr('disabled',false);
// console.log(tdata)

});


$("#report_form").on('submit', function(event){
event.preventDefault();

const chktxt = valtxt();
if (chktxt){
let frm =$(this).serialize();
let depselectval = $('#deptsel').val();
// console.log(depselectval)
let sve_ticket = $.post('fetch.php', frm,function(data){
  $.LoadingOverlay("show",{
  image       : "",
  custom      : customElement
  });
getdata();
noslctd("#msg");
$('#action').attr('disabled', 'true');
// $('.spinner-border').show();
$('#report_form')[0].reset();
$('#select_tos').val('default');
$('#select_tos').val("default");
$('#action').removeAttr('disabled');
// $('.spinner-border').hide();
  $("#msg").append(data.insertdata.m);
  $.LoadingOverlay("hide", true);

},'json');
}

// clearconsole();



});





$("#modal_form").on('submit', function(event){
event.preventDefault();

let Modal_Reply = $('#Modal_reply').val();

$('#sop').val("Addcomment");
if(Modal_Reply.trim() !='')
{

let frmmodal =$(this).serialize();

$.post('fetch.php', frmmodal,function(data){
$("#remarks_view").empty();
getinfo($('#ModalTicket_no').val(), 'remarks',uid);
$("#Modal_reply").val("");
getdata()
noslctd('#alrtmsg');
// console.log(data.cmmnt)
$("#alrtmsg").append(data.cmmnt);
},'json');

}
else
{

$('#Modal_reply').addClass('border-danger');
setTimeout(() => {
$('#Modal_reply').removeClass('border-danger');
}, 5000);
// return false;
//           alert("Please complete to proceed");
}
// clearconsole();
});


});
$("#clsmodaltick").click(()=>{
$("#remarks_view").empty();
});
var today = new Date();
var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()+' '+today.getHours()+':'+(today.getMinutes()<10?'0':'') + today.getMinutes()+':'+(today.getSeconds()<10?'0':'') + today.getSeconds();
