<?php

// ======== db  =========
include 'admin.php';
include '../condb.php';
include 'chrtdashboard.php';
include 'sub_graph_modal.php';

// $conn=new dbconfig();




?>


<style>

#img {
  width: 100%;

}

#img img {
  max-width: 100%; /* Set the maximum width of the image to 100% of its parent */
  width: auto; /* Set the width of the image to auto */
  margin: auto; /* Center the image horizontally and vertically */
}    

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
                 <option value="2019,2020,2021,2022,2023,2024" >OVERALL</option>
                 <option value="2024" selected >2024</option>
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

<div class="dashcard card text-white mb-4 bg-primary border-dark hide_sched" style="width: 20rem; height: 9rem;">
<div class="card-body">

<div class="card-title">SCHEDULE FOR PULL OUT: <span class="" id="count_sched" style="font-size: 18px;"></span></div>
                          
</div>                                  
<div class="card-footer d-flex align-items-center justify-content-between">

                     <a class=" second small text-white stretched-link" id="card_sched" href="#bottom" value="SCHEDULE FOR PULL OUT" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                          
                      </div>
</div>


<div class="dashcard card text-white mb-4 bg-danger hide_okay" style="width: 20rem; height: 9rem; ">
<div class="card-body">

<div class="card-title">OKAY FOR PULL OUT: <span class="" id="count_okay" style="font-size: 18px;"></span></div>
</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                       
                          <a class="small text-white stretched-link" id="card_okay" href="#bottom" value="OKAY FOR PULL OUT" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-secondary hide_pickup" style="width: 18rem; height: 9rem; ">
<div class="card-body">

<div class="card-title">READY TO PICK UP: <span class="float-right" id="count_pickup" style="font-size: 32px;"></span></div>
</div>
<div class="card-footer d-flex align-items-center justify-content-between">

                       <a class=" text-white stretched-link" id="card_ready" href="#bottom" value="READY TO PICK UP" ><span class="small text-white">Click here for more info.</span></a>
                   

                          <div class="go-arrow">  </div>
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-info hide_approved" style="width: 18rem; height: 9rem; ">
<div class="card-body">

<div class="card-title">APPROVED: <span class="float-right" id="count_approved" style="font-size: 32px;"></span></div>

</div>
<div class="card-footer d-flex align-items-center justify-content-between">

                       <a class=" text-white stretched-link" id="card_approved" href="#bottom" value="APPROVED" ><span class="small text-white">Click here for more info.</span></a>
                   

                          <div class="go-arrow">  </div>
                      </div>
</div>



<div class="dashcard card text-white mb-4 bg-primary hide_confirm" style="width: 18rem; height: 9rem; ">
<div class="card-body">

<div class="card-title">CONFIRM PICK UP: <span class="float-right" id="count_confirm" style="font-size: 32px;"></span></div>

</div>
<div class="card-footer d-flex align-items-center justify-content-between">

                       <a class=" text-white stretched-link" id="card_confirm" href="#bottom" value="CONFIRM PICK UP" ><span class="small text-white">Click here for more info.</span></a>
                   

                          <div class="go-arrow">  </div>
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-success hide_received" style="width: 18rem; height: 9rem; ">
<div class="card-body">

<div class="card-title">ITEM RECEIVED: <span class="float-right" id="count_received" style="font-size: 32px;"></span></div>

</div>
<div class="card-footer d-flex align-items-center justify-content-between">

                       <a class=" text-white stretched-link" id="card_received" href="#bottom" value="ITEM RECEIVED" ><span class="small text-white">Click here for more info.</span></a>
                   

                          <div class="go-arrow">  </div>
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-success hide_evaluate" style="width: 18rem; height: 9rem; ">
<div class="card-body">

<div class="card-title">EVALUATE: <span class="float-right" id="count_evaluate" style="font-size: 32px;"></span></div>

</div>
<div class="card-footer d-flex align-items-center justify-content-between">

                       <a class=" text-white stretched-link" id="card_evaluate" href="#bottom" value="EVALUATE" ><span class="small text-white">Click here for more info.</span></a>
                   

                          <div class="go-arrow">  </div>
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-warning hide_repaired" style="width: 18rem; height: 9rem; ">
<div class="card-body">

<div class="card-title">REPAIRED: <span class="float-right" id="count_repaired" style="font-size: 32px;"></span></div>

</div>
<div class="card-footer d-flex align-items-center justify-content-between">

                       <a class=" text-white stretched-link" id="card_repaired" href="#bottom" value="REPAIRED" ><span class="small text-white">Click here for more info.</span></a>
                   

                          <div class="go-arrow">  </div>
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-success hide_return" style="width: 20rem; height: 9rem;">
<div class="card-body">

<div class="card-title">RETURN TO STORE <span class="" id="count_return" style="font-size: 18px;"></span></div>
</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" id="card_return" href="#bottom" value="RETURN TO STORE" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                      </div>


</div>

<div class="dashcard card text-white mb-4 bg-success hide_list" style="width: 20rem; height: 9rem;">
<div class="card-body">

<div class="card-title">LIST FOR DISPOSAL <span class="float-right" id="count_list" style="font-size: 32px;"></span></div>
</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" id="card_list" href="#bottom" value="LIST FOR DISPOSAL" >Click here for more info.</a>
                          <div class="go-arrow">  </div>
                      </div>


</div>

<div class="dashcard card text-white mb-4 bg-primary border-dark totalrpt" style="width: 18rem; height: 9rem;">
<div class="card-body">

<div class="card-title">TOTAL REPORTS: <span class="float-right" id="count_total" style="font-size: 32px;"></span></div>
                          
</div>                                  
<div class="card-footer d-flex align-items-center justify-content-between">
<a class=" text-white stretched-link" id="card_totalval" href="#bottom" value="" ><span class="small text-white">Click here for more info.</span></a>
                          <div class="go-arrow">  </div>
                          
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-danger openrpt" style="width: 18rem; height: 9rem; ">
<div class="card-body">

<div class="card-title">OPEN REPORTS: <span class="float-right" id="count_open" style="font-size: 32px;"></span></div>
</div>
<div class="card-footer d-flex align-items-center justify-content-between">

                       <a class=" text-white stretched-link" id="card_openval" href="#bottom" value="OPEN" ><span class="small text-white">Click here for more info.</span></a>
                   

                          <div class="go-arrow">  </div>
                      </div>
</div>

<div class="dashcard card text-white mb-4 bg-success closerpt" style="width: 18rem; height: 9rem;">
<div class="card-body">

<div class="card-title">CLOSED REPORTS <span class="float-right" id="count_closed" style="font-size: 32px;"></span></div>
</div>
<div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="text-white stretched-link" id="card_closedval" href="#bottom" value="CLOSED" ><span class="small text-white">Click here for more info.</span></a>
                          <div class="go-arrow">  </div>
                      </div>


</div>

</div>



<div class="row ovrallhide" id="ovrall">

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
<label>SUPPORT</label>
<input type="hidden" name="it_num" id="it_num" readonly="">
<select class="form-control form-control-sm" name="itsup" id="itsup" required>
<option value="">Assign support...</option>  
     <?php
              $query="select * from it_tech WHERE deptsel = '4' AND service ='IMPORT'";
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
              $query="select * from category where deptsel = '4'";
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
    // session_start();
    $user =  $_SESSION['user_id'];
  
   switch ($user) {
    case '260':
      $varsel = 'ld_tag_import_fur';
      $order = 'ld_imp_fur_seq';
    break;
    case '271':
      $varsel = 'ld_tag_import_sup';
      $order = 'ld_imp_sup_seq';
    break;
    case '273':
      $varsel = 'ld_tag_import_fel';
      $order = 'ld_imp_fel_seq';
    break;
    case ($user == '259' || $user == '272' || $user == '274'):
      $varsel = 'ld_tag_import';
      $order = 'ld_imp_seq';
    break;
    default:
      break;
   }
              
    // if ($user == '259') { // user_id
    //     $varsel = 'pd_module_tag_import';
    // }
    // else if ($user == '260'){
    //   $varsel = 'ld_tag_import_fur';
    // }

      $query="select stat_desc  from status WHERE {$varsel} = 'Y' ORDER BY {$order} ASC";
      $run=$conn->prepare($query);
      $run->execute();
      $rs=$run->get_result();
      while ($res=$rs->fetch_assoc()) {
      ?>
      <option><?=$res['stat_desc'] ?></option>
      <?php }?>
      
</select>
</div>

<div class="form-group col-4 col-md-4 col-lg-4 divhide" style ="display:none">
<label id="alulbl">ALU</label>
<input type="text" class="form form-control" name="alu" id="alu" placeholder="ALU" readonly>
</div>

<div class="form-group col-4 col-md-4 col-lg-4 divnewalu" style ="display:none">
<label id="newalulbl">ALU</label>
<input type="text" class="form form-control" name="newalu" id="newalu" placeholder="ALU" readonly>
</div>

<div class="form-group col-4 col-md-4 col-lg-4 divdesc">
<label id="desclbl">DESCRIPTION</label>
<input type="text" class="form form-control" name="desc" id="desc" placeholder="DESCRIPTION" readonly>
</div>

<div class="form-group col-4 col-md-4 col-lg-4 divserial" style ="display:none">
<label id="serialnolbl">SERIAL NO</label>
<input type="text" class="form form-control" name="serialno" id="serialno" placeholder="SERIAL NO" readonly>
</div>

<div class="form-group col-4 col-md-4 col-lg-4 divnewserial">
<label id="newserialnolbl">NEW SERIAL NO</label>
<input type="text" class="form form-control" name="newserialno" id="newserialno" placeholder="SERIAL NO">
</div>

<div class="form-group col-4 col-md-4 col-lg-4">
<label id="rtvnolbl">RTV #</label>
<input type="text" class="form form-control" name="rtvno" id="rtvno" placeholder="RTV #">
</div>

<div class="form-group col-4 col-md-4 col-lg-4 trip1" style="display:none">
<label id="tripticket1lbl">TRIP TICKET# (READY TO PICK UP)</label>
<input type="text" class="form form-control" name="tripticket1" id="tripticket1" placeholder="READY TO PICK UP">
</div>

<div class="form-group col-4 col-md-4 col-lg-4 trip2" style="display:none">
<label id="tripticket2lbl">TRIP TICKET# (RETURN TO STORE)</label>
<input type="text" class="form form-control" name="tripticket2" id="tripticket2" placeholder="RETURN TO STORE">
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

<div class="col-md-12">
<label style="font-weight: bold;">Attached File:</label>
<p>
<input id="file-input" type="file" name="file" Multiple>
</p>
</div>

<hr/>

<div class="row">
    
<div class=" col-6 col-md-8">
<input type="submit" name="action" id="action" class="btn btn-success" value="Add" />   
</div>
<div class=" col-6 col-md-4 ">
<button type="button" name="btnClose" id="btnClose" class="btn btn-danger float-right" data-dismiss="modal">Close</button>  
</div>
</div>

<hr/>


<div class="card" id="img" name="img">


</div>

<div class="form-group col-lg-12">
<p>



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
<input type="hidden" name="u_id" id="u_id" value="<?php echo $_SESSION['user_id'];  ?>">

</div>
</form>
</div>
</div>
</div>
</div>



<!-- modal addnew button -->

<script type='text/javascript'>
  
$( document ).ready(function() {


$('#newalu').keyup(function (e) { 

let alu = this.value;

getdesc(alu);

function getdesc(){
$.post('fetchdata/fetch_data.php',{alu:alu, mode:'search_desc'},function(data){
  // console.log(data);
let desc = jQuery.parseJSON(data); 
const aludesc = desc;
$('#desc').val(aludesc[0].Desc1);
// console.log(desc);

});
}



});





  $("#alulbl").hide();
  $("#alu").hide();
  $("#serialno").hide();
  $("#serialnolbl").hide();
  $("#desc").hide();
  $("#desclbl").hide();
  $("#newserialno").hide();
  $("#newserialnolbl").hide();
  $("#rtvnolbl").hide();
  $("#rtvno").hide();
  $("#newalulbl").hide();
  $("#newalu").hide();
  $("#tripticket1lbl").hide();
  $("#tripticket1").hide();
  $("#tripticket2lbl").hide();
  $("#tripticket2").hide();


  $("#status").change(function (e) { 
  
  var Status = $("#status").val(); 
  // console.log(iN) 
  
    
if (Status == "REPAIRED SAME UNIT AND SERIAL") {
  $('.divhide').css({'display':'block'});
  $('.divserial').css({'display':'block'});
  $('.divdesc').css({'display':'block'});
  $('.divnewalu').css({'display':'none'});
  $('#alulbl').fadeIn();
  $('#alu').fadeIn();
  $('#alu').attr('readonly', true);
  $('#serialno').fadeIn();
  $('#serialnolbl').fadeIn();
  $('#serialno').attr('readonly', true);
  $('#desc').fadeIn();
  $('#desclbl').fadeIn();
  $("#newserialno").hide();
  $("#newserialnolbl").hide();
  $("#rtvno").hide();
  $("#rtvnolbl").hide();
  // alert("TRUES");
}
else if (Status == "REPAIRED SAME UNIT DIFFERENT SERIAL") {
  $('.divhide').css({'display':'block'});
  $('.divnewserial').css({'display':'block'});
  $('.divdesc').css({'display':'block'});
  $('.divserial').css({'display':'none'});
  $('.divnewalu').css({'display':'none'});
  $('#alulbl').fadeIn();
  $('#alu').fadeIn();
  $('#alu').attr('readonly', true);
  $("#newserialno").fadeIn();
  $("#newserialnolbl").fadeIn();
  $('#desc').fadeIn();
  $('#desclbl').fadeIn();
  $('#serialno').hide();
  $('#serialnolbl').hide();
  $("#rtvno").hide();
  $("#rtvnolbl").hide();
  $("#newalu").hide();
  $("#newalulbl").hide();
}
else if (Status == "REPLACE") {

$('.divserial').css({'display':'block'});
$('.divnewalu').css({'display':'block'});
$('.divdesc').css({'display':'block'});
$('.divhide').css({'display':'none'});
$('.divnewserial').css({'display':'none'});
$('#newalulbl').fadeIn();
$('#newalu').fadeIn();
$('#newalu').attr('readonly', false);
$('#serialno').fadeIn();
$('#serialno').attr('readonly', false);
$('#serialnolbl').fadeIn();
$('#desc').fadeIn();
$('#desclbl').fadeIn();
$("#newserialno").hide();
$("#newserialnolbl").hide();
$("#rtvno").hide();
$("#rtvnolbl").hide();
}
else if (Status == "RTV") {

$('.divhide').css({'display':'none'});
$('.divdesc').css({'display':'none'});
$('.divserial').css({'display':'none'});
$('.divnewserial').css({'display':'none'});
$('.divnewalu').css({'display':'none'});
$('#alulbl').hide();
$('#alu').hide();
$('#alu').attr('readonly', false);
$('#serialno').hide();
$('#serialno').attr('readonly', false);
$('#serialnolbl').hide();
$('#desc').hide();
$('#desclbl').hide();
$("#newserialno").hide();
$("#newserialnolbl").hide();
$("#rtvno").fadeIn();
$("#rtvnolbl").fadeIn();

}
else if (Status == "READY TO PICK UP") {

$('.divhide').css({'display':'none'});
$('.divdesc').css({'display':'none'});
$('.divserial').css({'display':'none'});
$('.divnewserial').css({'display':'none'});
$('.divnewalu').css({'display':'none'});
$('.trip1').css({'display':'block'});
$('.trip2').css({'display':'none'});
$('#alulbl').hide();
$('#alu').hide();
$('#alu').attr('readonly', false);
$('#serialno').hide();
$('#serialno').attr('readonly', false);
$('#serialnolbl').hide();
$('#desc').hide();
$('#desclbl').hide();
$("#newserialno").hide();
$("#newserialnolbl").hide();
$("#rtvno").hide();
$("#rtvnolbl").hide();
$("#tripticket1lbl").fadeIn();
$("#tripticket1").fadeIn();
$("#tripticket2lbl").hide();
$("#tripticket2").hide();

}
else if (Status == "RETURN TO STORE") {

$('.divhide').css({'display':'none'});
$('.divdesc').css({'display':'none'});
$('.divserial').css({'display':'none'});
$('.divnewserial').css({'display':'none'});
$('.divnewalu').css({'display':'none'});
$('.trip1').css({'display':'none'});
$('.trip2').css({'display':'block'});
$('#alulbl').hide();
$('#alu').hide();
$('#alu').attr('readonly', false);
$('#serialno').hide();
$('#serialno').attr('readonly', false);
$('#serialnolbl').hide();
$('#desc').hide();
$('#desclbl').hide();
$("#newserialno").hide();
$("#newserialnolbl").hide();
$("#rtvno").hide();
$("#rtvnolbl").hide();
$("#tripticket1lbl").hide();
$("#tripticket1").hide();
$("#tripticket2lbl").fadeIn();
$("#tripticket2").fadeIn();

}
else{
  $('#alulbl').hide();
  $('#alu').hide();
  $('#serialno').hide();
  $('#serialnolbl').hide();
  $("#desc").hide();
  $("#desclbl").hide();
  $("#newserialno").hide();
  $("#newserialnolbl").hide();
  $("#rtvno").hide();
  $("#rtvnolbl").hide();
  $("#newalulbl").hide();
  $("#newalu").hide();
  $("#tripticket1lbl").hide();
  $("#tripticket1").hide();
  $("#tripticket2lbl").hide();
  $("#tripticket2").hide();
}


});




//for debug purposes enable here
console.log($('#date_created').val());


if(/Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { $("#ovrall").hide(); }

var user_id = <?= $_SESSION['user_id']; ?>

let val = '';
$('#card_totalval').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");

});
// $('#card_openval').click(function(e) {
// e.preventDefault();
// val =  $(this).attr("value");
// // alert(val);
// });

$('#card_forpickup').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});

$('#card_delsup').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});

$('#card_delstore').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});

$('#card_closedval').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});

$('#card_sched').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});

$('#card_okay').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});

$('#card_ready').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});

$('#card_approved').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});

$('#card_repaired').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});

$('#card_confirm').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});

$('#card_received').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});

$('#card_evaluate').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});


$('#card_return').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});

$('#card_list').click(function(e) {
e.preventDefault();
val =  $(this).attr("value");
});

$('#myInput').on( 'input', function () {
    table.search( this.value ).draw();
} );




if (user_id == '259' || '260') //259 = LD VERON, 260 = LD GAB
{
  $(".hide_sched").hide();
  $(".hide_okay").hide();
  $(".hide_pickup").hide();
  $(".hide_approved").hide();
  $(".hide_confirm").hide();
  $(".hide_received").hide();
  $(".hide_evaluate").hide();
  $(".hide_repaired").hide();
  $(".hide_return").hide(); 
  $(".hide_list").hide();
  $(".maintenance").hide();

}
if (user_id == '271') { // LD JEANA
  $(".hide_list").show();
  $(".hide_received").show();
  $(".hide_okay").show();
  $(".hide_sched").show();
  $(".hide_pickup").hide();
  $(".hide_approved").hide();
  $(".hide_confirm").hide();
  $(".hide_evaluate").hide();
  $(".hide_repaired").hide();
  $(".hide_return").hide(); 
  $(".totalrpt").hide();
  $(".openrpt").hide();
  $(".closerpt").hide();
  $(".ovrallhide").hide();
  $(".newrpt").hide();
  $(".genrpt").hide();
  $(".maintenance").hide();
}
if (user_id == '273') { // LD LEO
  // alert("GOOD");
  $(".hide_sched").show();
  $(".hide_okay").show();
  $(".hide_pickup").show();
  $(".hide_approved").show();
  $(".hide_repaired").show();
  $(".hide_return").show();
  $(".hide_confirm").hide();
  $(".hide_received").hide();
  $(".hide_evaluate").hide();
  $(".hide_list").hide();
  $(".totalrpt").hide();
  $(".openrpt").hide();
  $(".closerpt").hide();
  $(".ovrallhide").hide();
  $(".newrpt").hide();
  $(".genrpt").hide();
  $(".maintenance").hide();
 }
if (user_id == '274') { // LD NORLYN
  $(".hide_confirm").show();
  $(".hide_received").show();
  $(".hide_sched").hide();
  $(".hide_okay").hide();
  $(".hide_pickup").hide();
  $(".hide_approved").hide();
  $(".hide_evaluate").hide();
  $(".hide_repaired").hide();
  $(".hide_return").hide();
  $(".hide_list").hide();
  $(".totalrpt").hide();
  $(".openrpt").hide();
  $(".closerpt").hide();
  $(".ovrallhide").hide();
  $(".newrpt").hide();
  $(".genrpt").hide();
  $(".maintenance").hide();
}
if (user_id == '272') { // LD BIEN
  $(".hide_received").show();
  $(".hide_evaluate").show();
  $(".hide_repaired").show();
  $(".hide_sched").hide();
  $(".hide_okay").hide();
  $(".hide_pickup").hide();
  $(".hide_approved").hide();
  $(".hide_confirm").hide();
  $(".hide_return").hide();
  $(".hide_list").hide();
  $(".totalrpt").hide();
  $(".openrpt").hide();
  $(".closerpt").hide();
  $(".ovrallhide").hide();
  $(".newrpt").hide();
  $(".genrpt").hide();
  $(".maintenance").hide();

}

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
{title:"WORKOUTPUT", data:"remarks","defaultContent": "",},
{title:"ALU", data:"alu_no","defaultContent": "",},
{title:"SERIAL", data:"serial_no","defaultContent": "",}
// {title:"NEW ALU", data:"n_alu","defaultContent": "",},
// {title:"NEW SERIAL", data:"n_serial_no","defaultContent": "",},
// {title:"RTV", data:"rtv","defaultContent": "",}









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
else if (data['status'] == 'SCHEDULE FOR PULL OUT'){
$(row).find('td:eq(0)').css('color', '#1597BB');
$(row).find('td:eq(1)').css('color', 'red');
$(row).find('td:eq(2)').css('color', '#1597BB');
$(row).find('td:eq(3)').css('color', '#1597BB');
$(row).find('td:eq(4)').css('color', '#1597BB');
$(row).find('td:eq(5)').css('color', '#1597BB');
$(row).find('td:eq(6)').css('color', '#1597BB');
$(row).find('td:eq(7)').css('color', '#1597BB');
$(row).find('td:eq(8)').css('color', '#1597BB');
$(row).find('td:eq(9)').css('color', '#1597BB');
$(row).find('td:eq(10)').css('color', '#1597BB');
$(row).find('td:eq(11)').css('color', '#1597BB');
$(row).find('td:eq(12)').css('color', '#1597BB');
$(row).find('td:eq(13)').css('color', '#1597BB');
$(row).find('td:eq(14)').css('color', '#1597BB');
$(row).find('td:eq(15)').css('color', '#1597BB');
$(row).find('td:eq(16)').css('color', '#1597BB');
$(row).find('td:eq(17)').css('color', '#1597BB');
$(row).find('td:eq(18)').css('color', '#1597BB');
}
else if (data['status'] == 'READY TO PICK UP'){
$(row).find('td:eq(0)').css('color', '#78A083');
$(row).find('td:eq(1)').css('color', 'red');
$(row).find('td:eq(2)').css('color', '#78A083');
$(row).find('td:eq(3)').css('color', '#78A083');
$(row).find('td:eq(4)').css('color', '#78A083');
$(row).find('td:eq(5)').css('color', '#78A083');
$(row).find('td:eq(6)').css('color', '#78A083');
$(row).find('td:eq(7)').css('color', '#78A083');
$(row).find('td:eq(8)').css('color', '#78A083');
$(row).find('td:eq(9)').css('color', '#78A083');
$(row).find('td:eq(10)').css('color', '#78A083');
$(row).find('td:eq(11)').css('color', '#78A083');
$(row).find('td:eq(12)').css('color', '#78A083');
$(row).find('td:eq(13)').css('color', '#78A083');
$(row).find('td:eq(14)').css('color', '#78A083');
$(row).find('td:eq(15)').css('color', '#78A083');
$(row).find('td:eq(16)').css('color', '#78A083');
$(row).find('td:eq(17)').css('color', '#78A083');
$(row).find('td:eq(18)').css('color', '#78A083');
}
else if (data['status'] == 'ALREADY PICK UP'){
$(row).find('td:eq(0)').css('color', '#F6B17A');
$(row).find('td:eq(1)').css('color', 'red');
$(row).find('td:eq(2)').css('color', '#F6B17A');
$(row).find('td:eq(3)').css('color', '#F6B17A');
$(row).find('td:eq(4)').css('color', '#F6B17A');
$(row).find('td:eq(5)').css('color', '#F6B17A');
$(row).find('td:eq(6)').css('color', '#F6B17A');
$(row).find('td:eq(7)').css('color', '#F6B17A');
$(row).find('td:eq(8)').css('color', '#F6B17A');
$(row).find('td:eq(9)').css('color', '#F6B17A');
$(row).find('td:eq(10)').css('color', '#F6B17A');
$(row).find('td:eq(11)').css('color', '#F6B17A');
$(row).find('td:eq(12)').css('color', '#F6B17A');
$(row).find('td:eq(13)').css('color', '#F6B17A');
$(row).find('td:eq(14)').css('color', '#F6B17A');
$(row).find('td:eq(15)').css('color', '#F6B17A');
$(row).find('td:eq(16)').css('color', '#F6B17A');
$(row).find('td:eq(17)').css('color', '#F6B17A');
$(row).find('td:eq(18)').css('color', '#F6B17A');
}
else if (data['status'] == 'RETURN TO STORE'){
$(row).find('td:eq(0)').css('color', '#F05941');
$(row).find('td:eq(1)').css('color', 'red');
$(row).find('td:eq(2)').css('color', '#F05941');
$(row).find('td:eq(3)').css('color', '#F05941');
$(row).find('td:eq(4)').css('color', '#F05941');
$(row).find('td:eq(5)').css('color', '#F05941');
$(row).find('td:eq(6)').css('color', '#F05941');
$(row).find('td:eq(7)').css('color', '#F05941');
$(row).find('td:eq(8)').css('color', '#F05941');
$(row).find('td:eq(9)').css('color', '#F05941');
$(row).find('td:eq(10)').css('color', '#F05941');
$(row).find('td:eq(11)').css('color', '#F05941');
$(row).find('td:eq(12)').css('color', '#F05941');
$(row).find('td:eq(13)').css('color', '#F05941');
$(row).find('td:eq(14)').css('color', '#F05941');
$(row).find('td:eq(15)').css('color', '#F05941');
$(row).find('td:eq(16)').css('color', '#F05941');
$(row).find('td:eq(17)').css('color', '#F05941');
$(row).find('td:eq(18)').css('color', '#F05941');
}
else if (data['status'] == 'ITEM RECEIVED'){
$(row).find('td:eq(0)').css('color', '#435585');
$(row).find('td:eq(1)').css('color', 'red');
$(row).find('td:eq(2)').css('color', '#435585');
$(row).find('td:eq(3)').css('color', '#435585');
$(row).find('td:eq(4)').css('color', '#435585');
$(row).find('td:eq(5)').css('color', '#435585');
$(row).find('td:eq(6)').css('color', '#435585');
$(row).find('td:eq(7)').css('color', '#435585');
$(row).find('td:eq(8)').css('color', '#435585');
$(row).find('td:eq(9)').css('color', '#435585');
$(row).find('td:eq(10)').css('color', '#435585');
$(row).find('td:eq(11)').css('color', '#435585');
$(row).find('td:eq(12)').css('color', '#435585');
$(row).find('td:eq(13)').css('color', '#435585');
$(row).find('td:eq(14)').css('color', '#435585');
$(row).find('td:eq(15)').css('color', '#435585');
$(row).find('td:eq(16)').css('color', '#435585');
$(row).find('td:eq(17)').css('color', '#435585');
$(row).find('td:eq(18)').css('color', '#435585');
}
else if (data['status'] == 'APRROVED'){
$(row).find('td:eq(0)').css('color', '#005B41');
$(row).find('td:eq(1)').css('color', 'red');
$(row).find('td:eq(2)').css('color', '#005B41');
$(row).find('td:eq(3)').css('color', '#005B41');
$(row).find('td:eq(4)').css('color', '#005B41');
$(row).find('td:eq(5)').css('color', '#005B41');
$(row).find('td:eq(6)').css('color', '#005B41');
$(row).find('td:eq(7)').css('color', '#005B41');
$(row).find('td:eq(8)').css('color', '#005B41');
$(row).find('td:eq(9)').css('color', '#005B41');
$(row).find('td:eq(10)').css('color', '#005B41');
$(row).find('td:eq(11)').css('color', '#005B41');
$(row).find('td:eq(12)').css('color', '#005B41');
$(row).find('td:eq(13)').css('color', '#005B41');
$(row).find('td:eq(14)').css('color', '#005B41');
$(row).find('td:eq(15)').css('color', '#005B41');
$(row).find('td:eq(16)').css('color', '#005B41');
$(row).find('td:eq(17)').css('color', '#005B41');
$(row).find('td:eq(18)').css('color', '#005B41');
}
else if (data['status'] == 'EVLAUATE'){
$(row).find('td:eq(0)').css('color', '#183D3D');
$(row).find('td:eq(1)').css('color', 'red');
$(row).find('td:eq(2)').css('color', '#183D3D');
$(row).find('td:eq(3)').css('color', '#183D3D');
$(row).find('td:eq(4)').css('color', '#183D3D');
$(row).find('td:eq(5)').css('color', '#183D3D');
$(row).find('td:eq(6)').css('color', '#183D3D');
$(row).find('td:eq(7)').css('color', '#183D3D');
$(row).find('td:eq(8)').css('color', '#183D3D');
$(row).find('td:eq(9)').css('color', '#183D3D');
$(row).find('td:eq(10)').css('color', '#183D3D');
$(row).find('td:eq(11)').css('color', '#183D3D');
$(row).find('td:eq(12)').css('color', '#183D3D');
$(row).find('td:eq(13)').css('color', '#183D3D');
$(row).find('td:eq(14)').css('color', '#183D3D');
$(row).find('td:eq(15)').css('color', '#183D3D');
$(row).find('td:eq(16)').css('color', '#183D3D');
$(row).find('td:eq(17)').css('color', '#183D3D');
$(row).find('td:eq(18)').css('color', '#183D3D');
}
else if (data['status'] == 'REPAIRED'){
$(row).find('td:eq(0)').css('color', '#8CABFF');
$(row).find('td:eq(1)').css('color', 'red');
$(row).find('td:eq(2)').css('color', '#8CABFF');
$(row).find('td:eq(3)').css('color', '#8CABFF');
$(row).find('td:eq(4)').css('color', '#8CABFF');
$(row).find('td:eq(5)').css('color', '#8CABFF');
$(row).find('td:eq(6)').css('color', '#8CABFF');
$(row).find('td:eq(7)').css('color', '#8CABFF');
$(row).find('td:eq(8)').css('color', '#8CABFF');
$(row).find('td:eq(9)').css('color', '#8CABFF');
$(row).find('td:eq(10)').css('color', '#8CABFF');
$(row).find('td:eq(11)').css('color', '#8CABFF');
$(row).find('td:eq(12)').css('color', '#8CABFF');
$(row).find('td:eq(13)').css('color', '#8CABFF');
$(row).find('td:eq(14)').css('color', '#8CABFF');
$(row).find('td:eq(15)').css('color', '#8CABFF');
$(row).find('td:eq(16)').css('color', '#8CABFF');
$(row).find('td:eq(17)').css('color', '#8CABFF');
$(row).find('td:eq(18)').css('color', '#8CABFF');
}
else if (data['status'] == 'REQUEST FOR I.R.'){
$(row).find('td:eq(0)').css('color', '#750E21');
$(row).find('td:eq(1)').css('color', 'red');
$(row).find('td:eq(2)').css('color', '#750E21');
$(row).find('td:eq(3)').css('color', '#750E21');
$(row).find('td:eq(4)').css('color', '#750E21');
$(row).find('td:eq(5)').css('color', '#750E21');
$(row).find('td:eq(6)').css('color', '#750E21');
$(row).find('td:eq(7)').css('color', '#750E21');
$(row).find('td:eq(8)').css('color', '#750E21');
$(row).find('td:eq(9)').css('color', '#750E21');
$(row).find('td:eq(10)').css('color', '#750E21');
$(row).find('td:eq(11)').css('color', '#750E21');
$(row).find('td:eq(12)').css('color', '#750E21');
$(row).find('td:eq(13)').css('color', '#750E21');
$(row).find('td:eq(14)').css('color', '#750E21');
$(row).find('td:eq(15)').css('color', '#750E21');
$(row).find('td:eq(16)').css('color', '#750E21');
$(row).find('td:eq(17)').css('color', '#750E21');
$(row).find('td:eq(18)').css('color', '#750E21');
}
else if (data['status'] == 'SCHEDULE FOR DISPOSAL'){
$(row).find('td:eq(0)').css('color', '#A78295');
$(row).find('td:eq(1)').css('color', 'red');
$(row).find('td:eq(2)').css('color', '#A78295');
$(row).find('td:eq(3)').css('color', '#A78295');
$(row).find('td:eq(4)').css('color', '#A78295');
$(row).find('td:eq(5)').css('color', '#A78295');
$(row).find('td:eq(6)').css('color', '#A78295');
$(row).find('td:eq(7)').css('color', '#A78295');
$(row).find('td:eq(8)').css('color', '#A78295');
$(row).find('td:eq(9)').css('color', '#A78295');
$(row).find('td:eq(10)').css('color', '#A78295');
$(row).find('td:eq(11)').css('color', '#A78295');
$(row).find('td:eq(12)').css('color', '#A78295');
$(row).find('td:eq(13)').css('color', '#A78295');
$(row).find('td:eq(14)').css('color', '#A78295');
$(row).find('td:eq(15)').css('color', '#A78295');
$(row).find('td:eq(16)').css('color', '#A78295');
$(row).find('td:eq(17)').css('color', '#A78295');
$(row).find('td:eq(18)').css('color', '#A78295');
}
else if (data['status'] == 'SUBJECT FOR ADJUSTMENT'){
$(row).find('td:eq(0)').css('color', '#3F2E3E');
$(row).find('td:eq(1)').css('color', 'red');
$(row).find('td:eq(2)').css('color', '#3F2E3E');
$(row).find('td:eq(3)').css('color', '#3F2E3E');
$(row).find('td:eq(4)').css('color', '#3F2E3E');
$(row).find('td:eq(5)').css('color', '#3F2E3E');
$(row).find('td:eq(6)').css('color', '#3F2E3E');
$(row).find('td:eq(7)').css('color', '#3F2E3E');
$(row).find('td:eq(8)').css('color', '#3F2E3E');
$(row).find('td:eq(9)').css('color', '#3F2E3E');
$(row).find('td:eq(10)').css('color', '#3F2E3E');
$(row).find('td:eq(11)').css('color', '#3F2E3E');
$(row).find('td:eq(12)').css('color', '#3F2E3E');
$(row).find('td:eq(13)').css('color', '#3F2E3E');
$(row).find('td:eq(14)').css('color', '#3F2E3E');
$(row).find('td:eq(15)').css('color', '#3F2E3E');
$(row).find('td:eq(16)').css('color', '#3F2E3E');
$(row).find('td:eq(17)').css('color', '#3F2E3E');
$(row).find('td:eq(18)').css('color', '#3F2E3E');
}
else if (data['status'] == 'APPROVED SUMMARY ADJUSTMENT'){
$(row).find('td:eq(0)').css('color', '#0E8388');
$(row).find('td:eq(1)').css('color', 'red');
$(row).find('td:eq(2)').css('color', '#0E8388');
$(row).find('td:eq(3)').css('color', '#0E8388');
$(row).find('td:eq(4)').css('color', '#0E8388');
$(row).find('td:eq(5)').css('color', '#0E8388');
$(row).find('td:eq(6)').css('color', '#0E8388');
$(row).find('td:eq(7)').css('color', '#0E8388');
$(row).find('td:eq(8)').css('color', '#0E8388');
$(row).find('td:eq(9)').css('color', '#0E8388');
$(row).find('td:eq(10)').css('color', '#0E8388');
$(row).find('td:eq(11)').css('color', '#0E8388');
$(row).find('td:eq(12)').css('color', '#0E8388');
$(row).find('td:eq(13)').css('color', '#0E8388');
$(row).find('td:eq(14)').css('color', '#0E8388');
$(row).find('td:eq(15)').css('color', '#0E8388');
$(row).find('td:eq(16)').css('color', '#0E8388');
$(row).find('td:eq(17)').css('color', '#0E8388');
$(row).find('td:eq(18)').css('color', '#0E8388');
}
else if (data['status'] == 'SUBJECT FOR CLOSING'){
$(row).find('td:eq(0)').css('color', '#890188');
$(row).find('td:eq(1)').css('color', 'red');
$(row).find('td:eq(2)').css('color', '#890188');
$(row).find('td:eq(3)').css('color', '#890188');
$(row).find('td:eq(4)').css('color', '#890188');
$(row).find('td:eq(5)').css('color', '#890188');
$(row).find('td:eq(6)').css('color', '#890188');
$(row).find('td:eq(7)').css('color', '#890188');
$(row).find('td:eq(8)').css('color', '#890188');
$(row).find('td:eq(9)').css('color', '#890188');
$(row).find('td:eq(10)').css('color', '#890188');
$(row).find('td:eq(11)').css('color', '#890188');
$(row).find('td:eq(12)').css('color', '#890188');
$(row).find('td:eq(13)').css('color', '#890188');
$(row).find('td:eq(14)').css('color', '#890188');
$(row).find('td:eq(15)').css('color', '#890188');
$(row).find('td:eq(16)').css('color', '#890188');
$(row).find('td:eq(17)').css('color', '#890188');
$(row).find('td:eq(18)').css('color', '#890188');
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
$(row).find('td:eq(14)').css('color', 'green');
$(row).find('td:eq(15)').css('color', 'green');
$(row).find('td:eq(16)').css('color', 'green');
$(row).find('td:eq(17)').css('color', 'green');
$(row).find('td:eq(18)').css('color', 'green');
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
$('#cat_num').val(data['cat_id']);
$('#cat').val(data['cat_id']);
$('#sub_num').val(data['sub_id']);
$('#sub').val(data['sub_category']);
$('#isp_num').val(data['isp_id']);
$('#isp').val(data['isp_id']);
$('#refNo').val(data['refNo']);
$('#refNo').val(data['refNo']);
$('#alu').val(data['alu_no']);
$('#desc').val(data['Desc']);
$('#serialno').val(data['serial_no']);
$('#newalu').val(data['n_alu']);
$('#newserialno').val(data['n_serial_no']);
$('#rtv').val(data['rtv']);
$('#tripticket1').val(data['tripticket1']);
$('#tripticket2').val(data['tripticket2']);
$('#file-input').val("");
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
$('#network_tb').slideToggle();
    $('html, body').animate({
        scrollTop: 1600
    }, 1000);
} );


$('#card_openval').on('click', function () {
// var val =  $(this).attr("value");
var value = "SCHEDULE FOR PULL OUT|READY TO PICK UP|RETURN TO STORE|APRROVED|EVLAUATE|REPAIRED|SCHEDULE FOR DISPOSAL|SUBJECT FOR ADJUSTMENT|APPROVED SUMMARY ADJUSTMENT|OKAY FOR PULL OUT|CONFIRM PICK UP|ITEM RECEIVED|LIST FOR DISPOSAL|SUBJECT FOR CLOSING";
// alert(value);
table
.columns( 7 )
.search(value, true, false)
.draw();
$('#network_tb').slideToggle();
    $('html, body').animate({
        scrollTop: 1600
    }, 1000);



} );

$('#card_forpickup').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 7 )
.search(val)
.draw();
} );

$('#card_delsup').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 7 )
.search(val)
.draw();
} );

$('#card_delstore').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 7 )
.search(val)
.draw();
} );

$('#card_sched').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 7 )
.search(val)
.draw();
} );

$('#card_okay').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 7 )
.search(val)
.draw();
} );

$('#card_ready').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 7 )
.search(val)
.draw();
} );

$('#card_approved').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 7 )
.search(val)
.draw();
} );

$('#card_repaired').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 7 )
.search(val)
.draw();
} );

$('#card_confirm').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 7 )
.search(val)
.draw();
} );

$('#card_received').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 7 )
.search(val)
.draw();
} );

$('#card_evaluate').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 7 )
.search(val)
.draw();
} );


$('#card_return').on('click', function () {
var val =  $(this).attr("value");
// alert(val);
table
.columns( 7 )
.search(val)
.draw();
} );

$('#card_list').on('click', function () {
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
$('#network_tb').slideToggle();
    $('html, body').animate({
        scrollTop: 1600
    }, 1000);
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
$('#count_forpickup').html(a[0].t_pickup);
$('#count_delsup').html(a[0].t_deliver_supplier);
$('#count_fordev').html(a[0].t_for_delivery);
$('#count_sched').html(a[0].schedule);
$('#count_okay').html(a[0].okay);
$('#count_pickup').html(a[0].pickup);
$('#count_approved').html(a[0].approved);
$('#count_evaluate').html(a[0].evaluate);
$('#count_repaired').html(a[0].repaired);
$('#count_confirm').html(a[0].confirm);
$('#count_received').html(a[0].received);
$('#count_return').html(a[0].return);
$('#count_list').html(a[0].list);


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
          // document.getElementById("ovrall").reset();

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

var val = jQuery('#ticket_no').val();


$.ajax({
    type: 'POST',
    url: 'sesticket.php',
    data: {tktval: val},
    success: function(response) {
      $('#img').html(response);
    }
  });

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


});//document ready close

</script>

