<?php 
include 'userheader.php';
include 'switch_modal.php';

if ($_SESSION['login']!='true'){
header("Location: index.php");
exit();
}

include '../condb.php';

$con1=new dbconfig(); 

?>



<style>

body{

background-color: #212529;

}

</style>


<!-- <body class="mt-5">

<div class="container-fluid"> -->
<div class="container-fluid mt-4">
<div class="row ">
<div class="col-md-3 d-inline-flex p-2">
<div class="card" style="width: 500px">
    <div class="card-header" style="font-weight: bold; font-size: 15px;">
        Create Ticket Here 
    </div>
    <div class="card-body">


  <form method="post" id="report_form" enctype="multipart/form-data">
      <div class="row">

<div class="form-group col-md-12">

<!-- <input type = "text"  name = "dept" id="dept"> -->

<input type = "hidden"  name = "ticket_no" id="ticket_no">

<input type = "hidden"  name = "rars_no" id="rars_no">

<input type = "hidden"  name = "sesstr_num" id="sesstr_num" value="<?php echo $_SESSION['str_num'];?>">

<input type = "hidden"  name = "str_code" id="str_code" value="<?php echo $_SESSION['str_code'];?>">

<input type = "hidden"  name = "str_adrs" id="str_adrs" value="<?php echo $_SESSION['str_adrs'];?>">

<input type = "hidden"  name = "str_contact" id="str_contact" value="<?php echo $_SESSION['str_contact'];?>">

      
<!-- <div class="col-md-12 d-inline-flex p-2">


                            <div class="input-group mb-2">
                            <div class="input-group-prepend">
                            <div class="input-group-text">Attention To:</div>
                            </div>
                            <select class="form-control"  name="deptsel" id="deptsel" required>
                            <option value="" selected disabled >Select Here</option>
                            <option value="1" >IT</option>
                            <option value="2" >ADMIN</option>
                            <option value="3">MARKETING</option>
                            <option value="4">REPAIR/SRTF</option>
                            <option value="6">VISUAL</option>




                            </select>
                            </div>
                            </div> -->


                <!-- <label style="font-weight: bold;">Types of services</label>
                          <div class="form-group col-md-12">
                            <select class="form-control selectpicker" name="select_tos" style=" font-size: 12px;" id="select_tos" >
                            <option value="">Select Here</option>

                          </select>
                        </div> -->
    


                <div class="col-md-12">
                <label class="" style="font-weight: bold;"><i class="fas fa-envelope"></i>  Subject</label>
                <input type="text" class="form-control" name="subject" id="subject" style="font-size: 12px; text-transform: uppercase;" minlength="5" maxlength="35"  autocomplete="off">
                <select class="form-control selectpicker" name="subjectimp" id="subjectimp" style=" font-size: 12px; text-transform: uppercase;">
                            <option selected disabled>Select Subject</option>
                            <option value="OSS">OSS</option>
                            <option value="FURNITURE">FURNITURE</option>
                            <option value="TECHNOLOGY">TECHNOLOGY</option>
                          </select>
                </div>

                    </div>
                    <div>
        
                <br><br>

                    </div>

                            
                      <div class="col-md-12 ">

                      <label style="font-weight: bold;" name="Qitem" id="Qitem">Quantity of Items</label>
                          <div class="form-group col-md-12">
                            <select class="form-control selectpicker" name="QItems" id="QItems" style=" font-size: 12px;" >
                            <option selected disabled>Select Here</option>
                            <option value="SINGLE">SINGLE ITEM</option>
                            <option value="MULTIPLE">MULTIPLE ITEM</option>
                          </select>
                        </div>

                      
                        <label style="font-weight: bold;" name="AluN" id="AluN">ALU:</label>
                        <div class ="form-group col-md-12">
                        <input type="text" name="Alu" id="Alu" class="form form-control">
                        </div>

                          <label style="font-weight: bold;" name="DescN" id="DescN">Description:</label>
                        <div class="form-group col-md-12">
                        <input type="text" name="Desc" id="Desc" class="form form-control" readonly>
                        </div>

                        <label style="font-weight: bold;" name="SerialLbl" id="SerialLbl">Serial No:</label>
                        <div class="form-group col-md-12">
                        <input type="text" name="SerialNo" id="SerialNo" class="form form-control" >
                        </div>

                        <label style="font-weight: bold;" name="DefectLbl" id="DefectLbl">Nature of Defect:</label>
                        <div class="form-group col-md-12">
                        <input type="text" name="Defect" id="Defect" class="form form-control" >
                        </div>

                        <label style="font-weight: bold;" name="SupplierLbl" id="SupplierLbl">Supplier:</label>
                        <div class="form-group col-md-12">
                        <input type="text" name="Supplier" id="Supplier" class="form form-control" >
                        </div>

                        <div class="form-group col-md-12 text-right">
                            
                                <input type="button" name="Additem" id="Additem" class="btn btn-success" value="Add" />
                            
                        </div>
                        


                        <label style="font-weight: bold;" name="TypesUnit" id="TypesUnit">Classification:</label>
                          <div class="form-group col-md-12">
                            <select class="form-control selectpicker" name="TypesOfUnit" id="TypesOfUnit" style=" font-size: 12px;" >
                            <option selected disabled>Select Here</option>
                            <option value="1">Store Unit</option>
                            <option value="2">Costumer Stock</option>
                          </select>
                        </div>
 

                      </div>

                        

 
                <input type="hidden" id="status" name="status" value="NEW REPORT">
                <div class="form-group col-md-12">
                  <label style="font-weight: bold;" id="titleconcern">Concern:</label>
                  <p>
                    <textarea class="cttxtarea" id="concern" name="concern" minlength="10" maxlength="1000" row="2"  placeholder="Input your message here"></textarea>
                  </p>
                  
                  <!-- <div id="concern" name="concern"></div> -->

                    </div>
                    <div class="col-md-12">
                    <label style="font-weight: bold;">Attached File:</label>
                    <p>
                    <input id="file-input" type="file" name="file" Multiple>
                    </p>
                    </div>

                    <div class="form-group col-md-4">
              <input type="submit" name="action" id="action" class=" btn btn-primary"   value="Save Ticket"/>

              </div>
            <div class="col-md-4">

            </div>

      </div>
  
        <input type="hidden" name="uId" id="uId" value="<?php echo $_SESSION['user_id'];?>" />
        <input type="hidden" name="operation" id="operation" value="Add" />
        <!-- <input type="text" name="sop" id="sop" value="" /> -->


        </form>
    </div>
  </div>

</div>


<!-- DATATABLE -->
<div class="col-md-9 d-inline-flex p-2" >

<!-- //START OF DEFAULT TABLE -->
<div class="card" id="dvtables" style="width: auto;"> 

    <div class="card-header" style="font-weight: bold; font-size: 15px;">
    Created Tickets:
    </div>



<div class="card-body">
<div class="row">                  
<div class="col-md-10">
<button type="button" id="addmsg" class="btn btn-primary  mb-2">Follow up report <i class="fa fa-comment" aria-hidden="true"></i></button>

</div>
<input type="hidden"  class="form-control form-control-sm" name="slctdtick" id="slctdtick">
<div class="col-md-2">
<select class="form-control" name="stat_picker" id="stat_picker">
<option value="OPEN">OPEN</option>
<option value="CLOSED">CLOSED</option>
<option value="ALL">ALL</option>
</select>
</div>
</div>
<div class="row col-md-12" id="msg"></div>
<table id="reports_table" class="table  hover table-bordered table-condensed text-center" >

</table>
<form method="post" id="stat_form" enctype="multipart/form-data">
  <input type="hidden" name="nticknum" id="nticknum">
  <input type="hidden" name="statOps" id="statOps">
</form>
<br>

  </div>
</div>
<!-- //END OF DEFAULT TABLE -->


<!-- //START OF PD TABLE -->
<div class="card" id="itmcard" style="width: 100%;">
  <div class="card-header" style="font-weight: bold; font-size: 15px;">
    ITEMS
  </div>
  <table id="items_table" class="table hover table-bordered table-condensed text-center">
    <tbody>
      <tr>
        <td colspan="X">No data available</td>
      </tr>
    </tbody>
  </table>
</div>
<!-- //END OF PD TABLE -->
</div>



</div>

</div> 



<script type="text/javascript">




$(document).ready(function(){
  $('#dvtables').show();
  $('#reports_table').show();

  $('#action').attr('disabled', 'true');

  // let operationVAL =  $('#sop').val('minus');
  
$('#Additem').hide();
$('#itmcard').hide();
$('#DescN').hide();
$('#Desc').hide();
$('#AluN').hide();
$('#Alu').hide(); 
$('#SerialNo').hide();
$('#SerialLbl').hide();
$('#TypesUnit').hide();
$('#TypesOfUnit').hide();
$('#subjectimp').hide();
// $('#subject').hide();
$('#Qitem').hide();
$('#QItems').hide();
$('#SupplierLbl').hide();
$('#Supplier').hide();
$('#DefectLbl').hide();
$('#Defect').hide();

$('#QItems').change(function (e) { 

  var QItem = this.value;
  var tickt = $('#ticket_no').val();

  if (QItem == 'MULTIPLE') {
    $('#itmcard').show();
    $('#dvtables').hide();
    $('#Additem').show();
    $('#Alu').attr('required', false);
    $('#SerialNo').attr('required', false);
    $('#Supplier').attr('required', false);
    $('#Defect').attr('required', false);
    $('#TypesOfUnit').attr('required', false);
    getitems(tickt);
    
  }else {
    $('#itmcard').hide();
    $('#dvtables').show();
    $('#Additem').hide();
    $('#Alu').attr('required', 'required');
    $('#SerialNo').attr('required', 'required');
    $('#Defect').attr('required', 'required');
    $('#Supplier').attr('required', 'required');
    $('#TypesOfUnit').attr('required', 'required');

  }
  
});


$("#deptsel").change(function (e) { 
  
  var iN = $("#deptsel").val();  


    
if (iN == "4") {
  $('#Qitem').fadeIn();
  $('#QItems').fadeIn();
  $('#DescN').fadeIn();
  $('#Desc').fadeIn();
  $('#Alu').fadeIn();
  $('#AluN').fadeIn();
  $('#SerialNo').fadeIn();
  $('#SerialLbl').fadeIn();
  $('#Supplier').fadeIn();
  $('#SupplierLbl').fadeIn();
  $('#Defect').fadeIn();
  $('#DefectLbl').fadeIn();
  $('#TypesUnit').fadeIn();
  $('#TypesOfUnit').fadeIn();
  $('#titleconcern').text("CONCERN / REASON / ISSUE:");
  
}
else{
  $('#Qitem').hide();
  $('#QItems').hide();
  $('#DescN').hide();
  $('#Alu').hide();
  $('#Desc').hide();
  $('#AluN').hide();
  $('#SerialNo').hide();
  $('#SerialLbl').hide();
  $('#SupplierLbl').hide();
  $('#Supplier').hide();
  $('#DefectLbl').hide();
  $('#Defect').hide();
  $('#TypesUnit').hide();
  $('#TypesOfUnit').hide();
  $('#titleconcern').text("Concern:");
  $('#subject').fadeIn();
  $('#subject').val('');
  $('#subjectimp').hide();
  $('#Additem').hide();
  $('#itmcard').hide();


}

getTkt(iN);

function getTkt(){

//In  = Deptsel value

  $.post('fetch.php',{iN:iN, operation:'search_tkt'},function(data){
  // console.log(data);

let tkt = jQuery.parseJSON(data); 
const tktno = tkt;

// $('#dept').val(tktno[0].dept);
$('#ticket_no').val(tktno[0].dept +''+ tktno[0].ticket_no);


});

} // end of getTkt

get_rarscount();

function get_rarscount(){
$.post('fetch.php',{iN:iN, operation:'rars_count'},function(data){
let get_count = jQuery.parseJSON(data);
const rars = get_count;
$('#rars_no').val('RARS'+" - "+String('00000'+rars[0].rarscount).slice(-6));

});

}

setInterval(get_rarscount, 1000);

});




$("#select_tos").change(function (e) { 
  // e.preventDefault();
  var Tos = $("#select_tos").val();

  if (Tos == 'IMPORT' || Tos == 'LOCAL') {
    
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
            if(Alu === '')
            return false;
 // ALU CHECKER
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
                        $('#Desc').val("")
 
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
  const alu = $(this).val();
  var desx = $('#Desc').val();
  if (!alu.match(/^\d+$/)) {
    $(this).val('');
    return;
  }
  if (alu == ''){
    $('#Desc').val('');
  }
  getdesc(alu);
});

function getdesc(alu){
  $.post('fetch.php',{alu:alu, operation:'search_desc'},function(data){
    let desc = jQuery.parseJSON(data); 
    const aludesc = desc;
    $('#Desc').val(aludesc[0].Desc1);
  });
}


  

const validationLength = 1000;
const concern = document.getElementById('concern');
const action = document.getElementById('action');

concern.addEventListener('input', function() {
  const inputValue = concern.value;
  const inputLength = inputValue.length;

  if (inputLength > validationLength) {
    concern.value = inputValue.substr(0, validationLength);
  }

  action.disabled = inputLength < 9;
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
tbl(data)

console.log(data)

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
  "order": [[ 7, "Desc" ]],
columns: [
{title:"Date Created",data: "Dt_Created"},
{ title:"Ticket Number",data: "TicketNum" },
{ title:"ASSIGNED TO:",data: "deptsel_val" },
{ title:"SUBJECT",data: "Concern" },
{ title:"Types of Service",data: "Tos" },
{ title:"STATUS",data: "Status" },
{ title:"ASSIGNED SUPPORT",data: "AsgnSup" },
{title:"ID", data:"series_id","defaultContent": "","visible": false},

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

var TktNoxx = $('#ModalTicket_no').val();

if (TktNoxx.includes("PD")) {
  // alert("GOOD");
  $('#rars').show();
}
else{
  $('#rars').hide();
}

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

}onchange
return true
}
$('#reports_table').on( 'click','tbody tr',function () {

$("#reports_table tbody tr").removeClass('row_selected');        
$(this).addClass('row_selected');
var tdata = table.row(this).data();
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

else if (statres1 == "READY FOR PULL OUT") {
  var isGood=confirm('Would you like to confirm pull out this item on ticket:'+' '+tickres1);
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

// console.log(' deptsel change event triggered ');
// console.log(' select_tos change event triggered ');
// console.log(' subjectimp change event triggered ');
// console.log(' Alu keyup event triggered ');
// console.log(' Additem click event triggered ');
// console.log(' report_form submit event triggered ');
// console.log(' modal_form submit event triggered ');
// console.log(' action click event triggered ');
// console.log(' file-input change event triggered ');
// console.log(' Additem click event triggered ');
// console.log(' reports_table row click event triggered ');
// console.log(' stat_picker change event triggered ');
// console.log(' addmsg click event triggered ');
// console.log(' clsmodaltick click event triggered ');

    } else  {
      alert('false');
    }
}

else if (statres1 == "SUPPLIER PULL OUT") {
  var isGood=confirm('Would you like to confirm pull out this item on ticket:'+' '+tickres1);
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
$('#dvtables').show();
$('#itmcard').hide();
$('.pd_div').hide();
// $('#action').removeAttr('disabled');
// $('.spinner-border').hide();
  $("#msg").append(data.insertdata.m);
  $.LoadingOverlay("hide", true);
  setTimeout(function() {
        
        location.reload();
      }, 700);
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


$('#action').click(function () {
    var concern = $('#concern').val(); 
    var Supplierx = $('#Supplier').val(); 
    var tktno = $('#ticket_no').val().trim().toUpperCase(); // Clean and uppercase
    var files = $('#file-input')[0].files;
    var formData = new FormData();

    console.log('Ticket number:', tktno);
    console.log('Contains "PD":', tktno.includes("PD"));

    for (var i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }
    formData.append('ticket_no', tktno);

    // First AJAX call
    $.ajax({
        type: "POST",
        url: "insertimg.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            console.log('First AJAX success');
            
            // Case-insensitive check
            if (tktno.includes("PD")) {
                console.log('PD found, making second AJAX call');
                $.ajax({
                    type: "POST",
                    url: "insertpdf.php",
                    data: {
                        operation: 'PRINTPDF',
                        ticket_no: tktno,
                        reason: concern,
                        Supplierx: Supplierx,
                        Store : $('#str_code').val(),
                        Adrs : $('#str_adrs').val(),
                        Contact : $('#str_contact').val(),
                        RarsNo : $('#rars_no').val(),
                    },
                    success: function (data) {
                        console.log('Second AJAX response:', data);
                        try {
                            var response = JSON.parse(data);
                            if (response.success) {
                                window.open(response.pdfUrl, '_blank');
                            } else {
                                console.error('PDF generation failed:', response.error);
                                alert('PDF generation failed: ' + response.error);
                            }
                        } catch (e) {
                            console.error('Error parsing JSON:', e);
                            alert('Error generating PDF');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Second AJAX failed:", error);
                        alert('Error generating PDF: ' + error);
                    }
                });
            } else {
                console.log('No PD found in ticket number');
            }
        },
        error: function (xhr, status, error) {
            console.error("First insert failed:", error);
            alert('Error uploading files: ' + error);
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

//Start For Merchandising Module Add item Multiple
$('#Additem').click(function() {
            var tickt = $('#ticket_no').val();
            var alu = $('#Alu').val();
            var desc = $('#Desc').val();
            var serialNo = $('#SerialNo').val();
            var Defect = $('#Defect').val();
            var supplier = $('#Supplier').val();

            $.ajax({
                type: 'POST',
                url: 'insertitems.php', 
                data: {
                    tickt: tickt,
                    alu: alu,
                    desc: desc,
                    serialNo: serialNo,
                    Defect: Defect,
                    supplier: supplier
                },
                success: function(data) {
                    // alert('Data inserted successfully!');
                    getitems(tickt);
                    $('#Alu').val("");
                    $('#Desc').val("");
                    $('#SerialNo').val("");
                    $('#Defect').val("");
                    // $('#Supplier').val("");
                }
            });
        });
//End For Merchandising Module Add item Multiple

// var tktnox = $('#ticket_no').val();
function getitems(tickt){
  $.post('fetch.php',{operation:'pditems',tickt:tickt},function(data){
    // console.log(data);
    pd_datatable(data);
  },'json');
}



var pdtblitem
function pd_datatable(t){
const dataset=t.rptpd;


pdtblitem = $("#items_table").DataTable({

"dom":
'B<"pull-left"lf><"pull-right">tip',

"info": true,
"pagingType": "full_numbers",
"bDestroy": true,
"responsive": true, "lengthChange": false, "autoWidth": false,
"language": {
"search": "_INPUT_",
"searchPlaceholder": "Search..."
},
order: [[0, 'desc']],
"pageLength":10,
"data": dataset,

"columns": [
{title:"ID", data:"id","defaultContent": "","visible": false},
{title:"ALU", data:"alu","defaultContent": "",},
{title:"DESCRIPTION", data:"desc","defaultContent": "",},
{title:"SERIAL:", data:"serial","defaultContent": "",},
{title:"SUPPLIER", data:"supplier","defaultContent": "",},
{
        title: "ACTION",
        data: null,
        defaultContent: "<button class='delete-btn'>Delete</button>"
      }
],
"rowCallback": function(row, data, index) {
      $(row).find('.delete-btn').on('click', function() {
        var ticktx = $('#ticket_no').val();
        const rowToDelete = pdtblitem.row($(row));
        const IDx = data.id; 

        $.ajax({
          type: 'POST',
          url: 'insert.php', 
          data: { operation: 'delete', IDx:IDx, ticktx:ticktx },
          success: function() {
            rowToDelete.remove().draw();
          }
        });
      });
    }



});




} 


}); // end of Doc Ready




$("#clsmodaltick").click(()=>{
$("#remarks_view").empty();
});
var today = new Date();
var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()+' '+today.getHours()+':'+(today.getMinutes()<10?'0':'') + today.getMinutes()+':'+(today.getSeconds()<10?'0':'') + today.getSeconds();

</script>






