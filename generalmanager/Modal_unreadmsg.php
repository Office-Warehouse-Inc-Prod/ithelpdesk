 <?php 

session_start();

if ($_SESSION['login']!='true'){
    header("Location: index.php");
    exit();
}

include 'db.php';
include '../condb.php';
$con1=new dbconfig();
$catres = mysqli_query($concat,"SELECT * FROM category"); ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketing System</title>

<!-- <link rel="stylesheet" type="text/css" href="../css/main.css"> -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arvo" />
<link rel="stylesheet" href="../css/4bootstrap.min.css" />
<script src="../js/moment.min.js"></script>
<!-- <link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/> -->
<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
<link rel="stylesheet" href="../css/jquery.dataTables.min.css" />  
<link rel="stylesheet" type="text/css" href="../css/responsive.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="../css/animate.min.css" />


<script src="../js/jquery-3.5.1.js"></script>
<script src="../js/jquery.timeago.js"></script>
  <script src="https://kit.fontawesome.com/426b4bab4c.js" crossorigin="anonymous"></script>
<script src="../js/helpdesk.js"></script> 
<!-- <script src="../js/bootstrap-datetimepicker.min.js"></script> -->
<script src="../js/popper.min.js"></script>
<script src="../js/4bootstrap.min.js"></script>
<script src="../js/jquery.dataTables.min.js"></script>
<script src="../js/ellipsis.js"></script>
<script src="../js/dataTables.responsive.min.js"></script>
<link rel="stylesheet" href="../css/bootstrap-datetimepicker-build.css">
<script src="../js/bootstrap-datetimepicker.js"></script>
  </head>

<style>
* {
  box-sizing: border-box;
}
  
body { 
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}
.newmsg_con {
  font-size: 12px;
    margin-left: 70px;
}

/*td:nth-child(9), td:nth-child(11) {
    display: none
}*/

table#reports_table {
  max-width:  100%;

}

/*table#reports_table td:first-child,*/
/*table#reports_table td:nth-child(12) ~ td {
  max-width: 1px;
  display: none;
}
table#reports_table th:nth-child(12) ~th {
  max-width: 1px;
  display: none;
}*/

.header {
  overflow: hidden;
  background-color: #f1f1f1;
  padding: 20px 10px;
}

.header a {
  float: left;
  color: black;
  text-align: center;
  padding: 12px;
  text-decoration: none;
  font-size: 18px; 
  line-height: 25px;
  border-radius: 4px;
}

.header a.logo {
  font-size: 25px;
  font-weight: bold;
}

.header a:hover {
  background-color: #ddd;
  color: black;
}

.header a.active {
  background-color: dodgerblue;
  color: white;
}

.header-right {
  float: right;
}

@media screen and (max-width: 500px) {
  .header a {
    float: none;
    display: block;
    text-align: left;
  }
  
  .header-right {
    float: none;
  }
}


.modal-lg {
  max-width: 1250px;
}
h6 {
  font-family: sans-serif;
}

[data-toggle="collapse"]:after {
display: inline-block;
    display: inline-block;
    font: normal normal normal 14px/1 FontAwesome;
    font-size: inherit;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  content: "/f054";
  transform: rotate(90deg) ;
  transition: all linear 0.25s;
  float: right;
  }   
[data-toggle="collapse"].collapsed:after {
  transform: rotate(0deg) ;
}


.cttxtarea {
  width: 100%;
  height: 250px;
  padding: 12px 20px;
  box-sizing: border-box;
  border: 2px solid #ccc;
  border-radius: 4px;
  background-color: #f8f8f8;
  resize: none;
}
.uModaltxtarea {
  width: 100%;
  height: 200px;
  /*padding: 12px 20px;*/
  box-sizing: border-box;
  border: 2px solid #ccc;
  border-radius: 4px;
  background-color: #f8f8f8;
  resize: none;
}

.cbtn {
  float: right;
  padding: 2px;
  border-radius: 2px;
}
td{
  font-size: 10px;
    max-width: 0;
  overflow: hidden;
/*  text-overflow: ellipsis;
  white-space: nowrap;*/
  text-align: center;
}
#modal_form{
  display: none;
}
.card_wfit {
  border-color: black;
}
label{
  font-weight: bold;
}
</style>




<body>
  <br><br><br><br><br> 

<div class="container-fluid newmsg_con col-md-12">

<!-- DATATABLE -->
    <div class="animate__bounceInDown col-md-12 d-inline-flex p-2">
      <div class="card " style="width: auto;">
                <div class="card-header"></div>
                <div class="card-body">


          <table id="reports_table" class="table table-condensed table-bordered table-responsive" style="width: 100%">
           <thead class="text-center" id="table_main">
            <tr>
             <th class="text-white">Ticket#</th>
             <th class="text-white">DATE CREATED</th>
             <th class="text-white">STORE</th>
             <th class="text-white">CONCERN</th>
             <th class="text-white">TYPES OF CONCERN</th>
             <th class="amsg text-white">MESSAGE</th>
             <th class="text-white">VIA</th>
             <th class="text-white">STATUS</th>
             <th class="text-white">ISSUED TO:</th>
             <th class="text-white">CATEGORY</th>
             <th class="text-white">SUBCATEGORY</th>
             <th class="text-white">Update and Reply</th>
             <th class="text-white">newrep</th>
             <th class="text-white">newmes</th>

            </tr>
      </thead>
      </table>
                      
                          </div>
              </div>
          </div>


      <div class="formview col-md-12 d-inline-flex p-2">
          <form id="modal_form">  
                   <div class="card card_wfit" style="width: 85rem;">
        <div class="card-header" style="background-color: #6794DC;"></div>

        <div class="card-body">
          <div class="form-group">

              <fieldset> 

              <div class="row">
                <div class="form-group col-md-2">
                                <label>Ticket #</label>

                                <input type="text" class="form-control" name="ModalTicket_no" id="ModalTicket_no" required="" readonly="" style="background-color: #fff;">
                      </div>
                          <div class="form-group col-md-2">
                                <label>Date Created</label>

                                <input type="text" class="form-control" name="ModalDate_create" id="ModalDate_create" required="" readonly="" style="background-color: #fff;">
                          </div>
                                <div class="form-group col-md-2">
                                <label>Store</label>

                                <input type="text" class="form-control" name="ModalStore" id="ModalStore" required="" readonly="" style="background-color: #fff;">
                                </div>
                      
                            <div class="form-group col-md-2">
                                <label>Subject</label>

                                <input type="text" class="form-control" name="ModalSubject" id="ModalSubject" required="" readonly="" style="background-color: #fff;">
                          </div>
                            <div class="form-group col-md-2">
                                <label>Types of Service</label>

                                <input type="text" class="form-control" name="ModalTOS" id="ModalTOS" required="" readonly="" style="background-color: #fff;">
                          </div>
                              <div class="form-group col-md-2">
                                  <label>STATUS</label>
                              <select class = "form-control" name= "Modalstatus" id="Modalstatus" required>
                              <option value=""> &larr; Status &rarr;</option>
                                     <?php
                                              $query="select * from status WHERE stat_id NOT IN ('2','5','6')";
                                              $run=$con1->prepare($query);
                                              $run->execute();
                                              $rs=$run->get_result();
                                              while ($res=$rs->fetch_assoc()) {
                                              ?>
                                              <option><?=$res['stat_desc'] ?></option>
                                              <?php }?>
                                              ?>   
                              </select>
                            </div>

                                <div class="form-group col-md-3">

                                <label>VIA</label>

                               <select class="form-control" name="Modalvia" id="Modalvia" required>
                               <option value=""> &larr; VIA &rarr;</option>
                                     <?php
                                              $query="select * from via_main";
                                              $run=$con1->prepare($query);
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
                                  <label>ASSIGNED TO:</label>
                       
                          <select class="form-control" name="ModalSelect_support" id="ModalSelect_support" required>
                             <option value=""> &larr; ASSIGNED TO: &rarr;</option>  
                                   <?php
                                            $query="select * from it_tech WHERE itsup NOT IN ('4','7','8','12')";
                                            $run=$con1->prepare($query);
                                            $run->execute();
                                            $rs=$run->get_result();
                                            while ($res=$rs->fetch_assoc()) {
                                              $supid = $res['itsup'];
                                              $suppdesc = $res['it_desc'];
                                            ?>

                                            <option value="<?php echo $supid;?>"><?= $suppdesc; ?></option>
                                            <?php }?>
                                            ?>   
                                </select> 
                              </div>
        <!--                     <div class="form-group col-md-2"></div> -->

                            <div class="form-group col-md-3">
                                  <label>CATEGORY</label>
                       
                          <select class="form-control" name="ModalSelect_cat" id="ModalSelect_cat" required>
                             <option value=""> &larr; CATEGORY &rarr;</option>  
                                   <?php
                                            $query="select * from category";
                                            $run=$con1->prepare($query);
                                            $run->execute();
                                            $rs=$run->get_result();
                                            while ($res=$rs->fetch_assoc()) {
                                              $catid = $res['id'];
                                              $catdesc = $res['category_name'];
                                            ?>

                                            <option value="<?php echo $catid;?>"><?= $catdesc; ?></option>
                                            <?php }?>
                                            ?>   
                                </select> 
                              </div>
                                    <div class="form-group col-md-3">
                                      <label for="sel1">Sub Category</label>
                                      <select class="form-control" name="ModalSelect_subcat" id="ModalSelect_subcat">
                                      </select>
                                    </div>

          <script type="text/javascript">
            wfithsforms();
          </script>


          <div class="form-group col-md-4">
            <label id="dateclabel" class="hidden">DATE CLOSED</label>
 <!--             <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                <input type="text" name="ModalDate_close" id="ModalDate_close" class="form-control datetimepicker-input" data-target="#datetimepicker2" autocomplete="off" />
                <div class="input-group-append" data-target="#ModalDate_close" autocomplete="off" data-toggle="datetimepicker">
                  <div class="input-group-text" id="ico_cal" name="ico_cal"><i class="fa fa-calendar"></i></div>
          </div>
            </div> -->
            <input type="text" class="form-control" name="ModalDate_close" id="ModalDate_close">

           </div>

          <div class="form-group col-md-8">
           <!-- <label id="clby_label" class="hidden">Assisted By:</label> -->
    
            <input type="text" name="Modalclose_by" id="Modalclose_by" value="<?php echo $_SESSION['tech_id']; ?>">


          </div>


           <div class="form-group col-md-12">
                  <label>Concern</label>
                  <textarea name="Modal_concern" id="Modal_concern" class="form-control" readonly="" style="background-color: #fff;"></textarea>
            </div> 

                  <input type="hidden" name="user_id" id="user_id" />
                <div class="form-group col-md-12">

                       <label>REMARKS / WORK OUTPUT</label>
                       <textarea name="Modalremarks" id="Modalremarks" class="form-control" placeholder="Remarks"></textarea> 
               </div>



              </div>
                
              </fieldset>    
                      

              <div class="col-md-12" id="remarks_view"><ul></ul></div>
          </div>
        <div class="col-md-12">
              <label style="font-weight: bold;">Reply</label>
              <br />
          <textarea class="uModaltxtarea" id="Modal_reply" name="Modal_reply"></textarea>
            </div>
           <input type="hidden" name="Modal_uId" id="Modal_uId" value="<?php echo $_SESSION['user_id'];?>" />

           <input type="hidden" name="Modal_operation" id="Modal_operation" value="Comment" />
            <input type="submit" name="Modal_action" id="Modal_action"  value="Reply & Save" />
             <!-- <button type="button" class="btn_cancel" onclick="closeForm()">Close</button> -->


                </form>
              </div>
        </div>
      </div>


      </div>
 </body>
</html>

<script type="text/javascript">

$(document).ready(function(){

    var today = new Date();
    var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()+' '+today.getHours()+':'+(today.getMinutes()<10?'0':'') + today.getMinutes()+':'+(today.getSeconds()<10?'0':'') + today.getSeconds();


$(function () {

  $('#ModalDate_close').datetimepicker();

});


$('#ModalDate_close').hide();
$('#ico_cal').hide();
// $('#Modalclose_by').hide();


  $('#ModalSelect_cat').on('change', function() {
      var category_id = this.value;
      $.ajax({
        url: "get_subcat.php",
        type: "POST",
        data: {
          category_id: category_id
        },
        cache: false,
        success: function(dataResult){
          $("#ModalSelect_subcat").html(dataResult);
        }
      });
    
    
  });

let dataTable = $('#reports_table').DataTable({
  "processing":true,
  "serverSide":true,
  "pageLength": 5,
  "select": true,
  "searching": false,
  "order":[],
  "ajax":{
   url:"fetchdata/fetch_undread.php",
   type:"POST"
  },
    rowCallback: function(row, data, index){
    if(data[12] == '1'){
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
      $(row).find('td:eq(10)').css("font-weight", "bold");
      $(row).find('td:eq(11)').css("font-weight", "bold");
 
    }
        else if(data[13] == '2'){
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
      $(row).find('td:eq(10)').css("font-weight", "bold");
      $(row).find('td:eq(11)').css("font-weight", "bold");
 
    }

  }
      
 });

setInterval(function () { // refresh datatables every 2 minutes.
      dataTable.ajax.reload();
  }, 120000);


 function getinfo(tid,gettype){

        var Cid =tid;
        var gettype = gettype;
    $.ajax({
        type:"GET",
          url:"info.php",
          data:{tid:Cid,gettype:gettype},
          datatype:'JSON',
          cache: false,
          async: false,
        success:function(data)
        {
          var obj = JSON.parse(data);
           // console.log(obj);
           if (gettype == 'remarks'){

    $('#remarks_view').empty();
          for (var I = 0; I <= obj.length; I++)
      { 
           var dv="";
           var str = "Sent :";
        dv += "<div class='accordion' id='accordionExample'>";
        dv += "<div class='card2'>";
        dv += "<div class='card-header' id='headingOne'>";
        dv +=  "<h6 class='mb-0'>";
        dv +=   "<button class='list-group-item list-group-item-action collapsed hdr' id='mdrb' type='button' data-toggle='collapse' data-target='#collapseOne"+ I +"' aria-expanded='false' aria-controls='collapseOne"+ I +"'>"+'“Replied by'+" "+obj[I].tech+  
        "</button>";

        dv += "</h6>";
        dv += "</div>";
        dv += "</div>";
        dv += "</div>";
        dv += "<div id='collapseOne"+ I +"' class='collapse show' aria-labelledby='headingOne' data-parent='#accordionExample'>"
        dv += "<div class='card-body'>"+ obj[I].desc + "</div>";
        dv += "<div class='card-body'>"+ str  +"&nbsp &nbsp &nbsp &nbsp ";
        dv +=  obj[I].dt +"</div>";
        dv += "</div>";
        dv += "</div>";
        document.getElementById("remarks_view").innerHTML += dv; 
       $('#remarks_view').append('&nbsp &nbsp &nbsp <time class="timeago" datetime="'+obj[I].dt+'"></time>')
       $("time.timeago").timeago();
     
     
      }

        }

        }
       });
  }

  $("#table").on('click',function(){

$
  });




  $("#reports_table").on('click', '.update', function(event){
  event.preventDefault();
  $("#modal_form").toggle('show');
  $("#modal_form").fadeIn("slow");
  // $(".msgcnt").val("0");
  $('html,body').animate({scrollTop: $(".formview").offset().top},'slow');
  
  var tid=$(this).parent().siblings(':first').html();
$('#ModalTicket_no').val($(this).parent().siblings(':first').html());  
$('#ModalDate_create').val($(this).parent().siblings(':nth-of-type(2)').html());
$('#ModalStore').val($(this).parent().siblings(':nth-of-type(3)').html());
$('#ModalSubject').val($(this).parent().siblings(':nth-of-type(4)').html());
$('#ModalTOS').val($(this).parent().siblings(':nth-of-type(5)').html());
$('#Modal_concern').val($(this).parent().siblings(':nth-of-type(6)').html());
$('#Modalvia').val($(this).parent().siblings(':nth-of-type(7)').html());
$('#Modalstatus').val($(this).parent().siblings(':nth-of-type(8)').html());

     var rsn = document.querySelector("#ModalSelect_support");  //supportselect
          var option = document.createElement("option");
          option.value=0;
          option.id='tmpsuppid';
          option.selected='selected';
          option.text = $(this).parent().siblings(':nth-of-type(9)').html();
          rsn.add(option);
  

 var sct = document.querySelector("#ModalSelect_cat");  
          var option = document.createElement("option");
          option.value=0;
          option.id='tmpcatid';
          option.selected='selected';
          option.text = $(this).parent().siblings(':nth-of-type(10)').html();
          sct.add(option);

 var sst = document.querySelector("#ModalSelect_subcat");  
          var option = document.createElement("option");
          option.value=0;
          option.id='tmpsubid';
          option.selected='selected';
          option.text = $(this).parent().siblings(':nth-of-type(11)').html();
          sst.add(option);


  getinfo(tid, 'remarks');
updatemsg($(this).parent().siblings(':first').html(),$(this).parent().siblings(':nth-of-type(13)').html());
newmes($(this).parent().siblings(':first').html(),$(this).parent().siblings(':nth-of-type(14)').html());
// alert($(this).parent().siblings(':nth-of-type(13)').html());


   });


  function updatemsg(ticketno,cnt){

    //ajax here
 
     if (cnt > '0')
     {
      // console.log(cnt);
      $.ajax({
        url: 'get_msgcnt.php',
        method:'POST',
        async:false,
        data:{tickno:ticketno},
        success:function(data) {
          // console.log(data);
          dataTable.ajax.reload();
        }
      });
     }
  }

   function newmes(ticketno,cnt){

    //ajax here
 
     if (cnt > '0')
     {
      // console.log(cnt);
      $.ajax({
        url: 'newmsg.php',
        method:'POST',
        async:false,
        data:{tickno:ticketno},
        success:function(data) {
          // console.log(data);
          dataTable.ajax.reload();
        }
      });
     }
  }




  $("#modal_form").on('submit', function(event){
          event.preventDefault();
            // alert('clicked');
            let Modal_Reply = $('#Modal_reply').val();
            let Modal_via = $('#Modalvia').val();
            let Modal_Support = $('#ModalSelect_support').val();

            if(Modal_Reply !='')
            {
              $.ajax({
                url: 'add_comment.php',
                method: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success:function(data)
                {
                  alert(data);
                  $('#modal_form, #Modal_Reply').val('');
                  // $('#mss').append('<div class="alert alert-success">'+data+'</div>')
                  getinfo($('#ModalTicket_no').val(), 'remarks');
                  dataTable.ajax.reload();
                }
                
              });
            }
            else
            {
              alert("Please complete to proceed");
            }
             });


  });


  $(document).on('click', '.btn_cancel', function(event){
    dataTable.ajax.reload();
  });
  
</script>
