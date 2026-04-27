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
        
                  <input type = "hidden"  name = "ticket_no" id="ticket_no">
               
                  <input type = "hidden"  name = "sesstr_num" id="sesstr_num" value="<?php echo $_SESSION['str_num'];?>">
                        

                                    <label style="font-weight: bold;">Subject</label>

                                   <input type="text" class="form-control" name="subject" id="subject" style="font-size: 12px; text-transform: uppercase;" minlength="5" maxlength="35"  autocomplete="off">
                                     </div>
                                     <div>
                         
                                  <br><br>
  
                                     </div>
                                         <label style="font-weight: bold;">Types of services</label>
                                           <div class="form-group col-md-12">
                                             <select class="form-control selectpicker" name="select_tos" style=" font-size: 12px;" id="select_tos" >
                                             <option value="">Select Here</option>
                                                   <?php
                                                            $query="select * from tbl_typeofservice";
                                                            $run=$con1->prepare($query);
                                                            $run->execute();
                                                            $rs=$run->get_result();
                                                            while ($res=$rs->fetch_assoc()) {
                                                                $service_icon = $res['service_icon']; 
                                                                $deptid = $res['service_id'];
                                                                $deptdesc = $res['service_desc'];

                                                            ?>
                                                                <option data-content="<i class='<?= $service_icon; ?>' aria-hidden='true'</i> &nbsp &nbsp <?=  $deptdesc; ?>"><?=  $deptdesc; ?></option>
                                                            <?php }?>
                                                            ?>   
                                            </select>
                                          </div>
                                  <input type="hidden" id="status" name="status" value="NEW REPORT">
                                 <div class="form-group col-md-12">
                                    <label style="font-weight: bold;">Concern:</label>
                                    <p>
                                      <textarea class="cttxtarea" id="concern" name="concern" minlength="10" maxlength="200" row="2"  placeholder="Input your message here"></textarea>
                                    </p>
                                   
                                   <!-- <div id="concern" name="concern"></div> -->
 
                                     </div>

                                     <div class="form-group col-md-4">
                                <input type="submit" name="action" id="action" class=" btn btn-primary" style=""  value="Save Ticket" />
                                </div>
                        </div>
                    
                         <input type="hidden" name="uId" id="uId" value="<?php echo $_SESSION['user_id'];?>" />
                         <input type="hidden" name="operation" id="operation" value="Add" />
            
                  
                          </form>
                      </div>
                    </div>

        </div>


<!-- DATATABLE -->
    <div class="col-md-9 d-inline-flex p-2">
            <div class="card" style="width: auto;">

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
<br>
                
                    </div>
        </div>
    </div>



</div>

 </div> 

    
<!-- </div> -->
<!-- <div class="footer">
    <div class="row">
      <div class="col-md-4" id="date_tdy"></div>
      <div class="col-md-4">Office Warehouse - I.T Department 2021</div>
    </div> -->
<!-- </div>
</body> -->



<script type="text/javascript">

$(document).ready(function(){
  var uid = <?= $_SESSION['user_id'] ?>;
$("#stat_picker").change(function(){
  
 
  getdata(this.value)
})
 
$('#action').val("Save ticket");
$('#operation').val("Add");
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
          pageLength:05,
          data: dataset,
           "order": [[ 0, "Desc" ]],
          columns: [
          { title:"Ticket Number",data: "TicketNum" },
          {title:"Date Created",data: "Dt_Created"},
          { title:"DEPARTMENT/STORE",data: "brncd_dptdesc" },
          { title:"Subject",data: "Concern" },
          { title:"Types of Service",data: "Tos" },
          { title:"Status",data: "Status" },
          { title:"Assigned Support",data: "AsgnSup" }

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
    
    if(data['NewRpt'] > 0){

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
         if (data['Status'].toUpperCase() == 'OPEN'){
      $(row).find('td:eq(0)').css("color", "red");
      $(row).find('td:eq(1)').css("color", "red");
      $(row).find('td:eq(2)').css("color", "red");
      $(row).find('td:eq(3)').css("color", "red");
      $(row).find('td:eq(4)').css("color", "red");
      $(row).find('td:eq(5)').css("color", "red");
      $(row).find('td:eq(6)').css("color", "red");
      $(row).find('td:eq(7)').css("color", "red");
      $(row).find('td:eq(8)').css("color", "red");

    }

            else if (data['Status'].toUpperCase() == 'OPEN' && data['NewMes'] == '2'){
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

                else if (data['Status'].toUpperCase() == 'CLOSED'){
      $(row).find('td:eq(0)').css({"font-weight": "bold", "color": "green"});
      $(row).find('td:eq(1)').css({"font-weight": "bold", "color": "green"});
      $(row).find('td:eq(2)').css({"font-weight": "bold", "color": "green"});
      $(row).find('td:eq(3)').css({"font-weight": "bold", "color": "green"});
      $(row).find('td:eq(4)').css({"font-weight": "bold", "color": "green"});
      $(row).find('td:eq(5)').css({"font-weight": "bold", "color": "green"});
      $(row).find('td:eq(6)').css({"font-weight": "bold", "color": "green"});
      $(row).find('td:eq(7)').css({"font-weight": "bold", "color": "green"});
      $(row).find('td:eq(8)').css({"font-weight": "bold", "color": "green"});

    }


           if (data['NewMes'] == '1'){
      $(row).find('td:eq(0)').css("font-weight", "bold");
      $(row).find('td:eq(1)').css("font-weight", "bold");
      $(row).find('td:eq(2)').css("font-weight", "bold");
      $(row).find('td:eq(3)').css("font-weight", "bold");
      $(row).find('td:eq(4)').css("font-weight", "bold");
      $(row).find('td:eq(5)').css("font-weight", "bold");
      $(row).find('td:eq(6)').css("font-weight", "bold");
      $(row).find('td:eq(7)').css("font-weight", "bold");
      $(row).find('td:eq(8)').css("font-weight", "bold");
      $(row).find('td:eq(9)').css("font-weight", "bold");
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
        // $('#Modal_concern').val(tdata['Sbjct']);
        (tdata['Status']=='CLOSED') ? $("#addmsg").attr('disabled',true):$("#addmsg").attr('disabled',false);
      // console.log(tdata)
   
    });
$("#report_form").on('submit', function(event){
          event.preventDefault();
          const chktxt = valtxt();
          if (chktxt){
            let frm =$(this).serialize();
        $.post('fetch.php', frm,function(data){
                  console.log(data.insertdata)
                  getdata()
                  noslctd("#msg");
                  $("#msg").append(data.insertdata.m);
                  $('#report_form')[0].reset();
                  $('#select_tos').val('default');
                  $('#select_tos').val("default");
   
      },'json');
  }
          
             });





  $("#modal_form").on('submit', function(event){
          event.preventDefault();

            let Modal_Reply = $('#Modal_reply').val();

            $('#operation').val("Addcomment");
            if(Modal_Reply.trim() !='')
            {

             let frmmodal =$(this).serialize();

              $.post('fetch.php', frmmodal,function(data){
              $("#remarks_view").empty();
              getinfo($('#ModalTicket_no').val(), 'remarks',uid);
              $("#Modal_reply").val("");
              getdata()
              noslctd('#alrtmsg');
              console.log(data.cmmnt)
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
             });


  });
  $("#clsmodaltick").click(()=>{
  $("#remarks_view").empty();
  });
  var today = new Date();
    var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()+' '+today.getHours()+':'+(today.getMinutes()<10?'0':'') + today.getMinutes()+':'+(today.getSeconds()<10?'0':'') + today.getSeconds();

</script>


   



