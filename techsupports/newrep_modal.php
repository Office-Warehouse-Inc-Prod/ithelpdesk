 <!-- Start of Add/Edit Modal -->


  <div class="modal fade" id="newrpt_Modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
  <form method="post" id="newrpt_form" enctype="multipart/form-data">
   <div class="modal-content">
    <div class="modal-header">
     <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
     <h4 class="modal-title" id="userModal_header" value=""></h4>
    </div>


<div class="modal-body">
<!-- <form> -->
        
  <div class="row">
    <div class="form-group col-md-4">
       <label>STORE</label>


        <input type="hidden" name="store" id="store" readonly="" value="">
        <input type="text" class="form-control" name="str_desc" id="str_desc" readonly="" value="">

    </div>
        <div class="form-group col-md-4">
       <label>Created By:</label>
        <input type="text" class="form-control" name="crtd_by" id="crtd_by" readonly="" >

    </div>
    
    <input type = "hidden" class="form-control" name = "ticket_no" id="ticket_no">


    <div class="form-group col-md-4">
      
     <label>DATE CREATED</label>
        <input type="text" class="form-control" name="date_created" id="date_created" readonly="" value="">
        <div></div>
</div>

    <div class="form-group col-md-12">
    <label>SUBJECT</label>
     <textarea name="concern" id="concern" class="form-control" placeholder="Input Concern" 
     style="text-transform:uppercase" onkeyup="this.value = this.value;" readonly></textarea>
     </div>
    <div class="form-group col-md-4">
       <label>Service Requested:</label>
        <input type="text" class="form-control" name="tos" id="tos" readonly="" >

    </div>
      <div class="form-group col-md-12">
    <label>CONCERN</label>
     <textarea name="concern" id="message" class="form-control" placeholder="Input Concern" 
     style="text-transform:uppercase" onkeyup="this.value = this.value;" readonly></textarea>
     </div>

    <div class="form-group col-md-4">
      <label>VIA</label>
     <select class="form-control" name="via" id="via" required>
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
    <div class="form-group col-md-8">
      <label>I.T SUPPORT</label>
          <input type="hidden" name="it_num" id="it_num" readonly="">
          <select class="form-control" name="itsup" id="itsup" required>
             <option value="">Assign support...</option>  
                   <?php
                            $query="select * from it_tech WHERE itsup NOT IN ('4','7','8',12)";
                            $run=$con1->prepare($query);
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
    <div class="form-group col-md-6">

     <label>CATEGORY</label>
          <input type="hidden" name="cat_num" id="cat_num" readonly="">
          <select class="form-control" name="cat" id="cat" required >
             <option value=""> &larr; CATEGORY &rarr;</option>  
                   <?php
                            $query="select * from category";
                            $run=$con1->prepare($query);
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
    <div class="form-group col-md-6">

    <label>SUB CATEGORY</label>
           <input type="hidden" name="sub_num" id="sub_num" readonly="">


              <select class="form-control" name="sub" id="sub">
              </select>
    </div>
    <div class="form-group col-md-4">

         <label for="isp" id="lbl_isp">Service Provider</label>
          <input type="hidden" name="isp_num" id="isp_num" readonly="">
          <select class="form-control" name="isp" id="isp">
             <option value="">Select Network Provider</option>  
                   <?php
                            $query="select * from tbl_isp";
                            $run=$con1->prepare($query);
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
    <div class="form-group col-md-4">
      <label id="lbl_refNo" for="refNo">Reference No:</label>
      <input type="text" class="form-control" name="refNo" id="refNo">
    </div>

 
    <div class="form-group col-md-4">
      
     <label for="date_refNo" class="hidden" id="lbl_DtRefNo">Date of RefNo</label>
  <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
          <input type="text" name="date_refNo" id="date_refNo" class="form-control datetimepicker-input" data-target="#datetimepicker3"/>
          <div class="input-group-append" data-target="#date_created" data-toggle="datetimepicker">
            <div class="input-group-text" id="ico_cal3"><i class="fa fa-calendar"></i></div>
    </div>
  </div>
</div>

    <div class="form-group col-md-4">
        <label>STATUS</label>
    <select class = "form-control" name= "status" id="status" required>
    <option value=""> &larr; Status &rarr;</option>
           <?php
                    $query="select * from status WHERE stat_id NOT IN  ('2','5','6')";
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
    <div class="form-group col-md-4">
      <label id="dateclabel" class="hidden">DATE CLOSED</label>
       <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
          <input type="text" name="date_closed" id="date_closed" class="form-control datetimepicker-input" data-target="#datetimepicker2" autocomplete="off" />
          <div class="input-group-append" data-target="#date_closed" autocomplete="off" data-toggle="datetimepicker">
            <div class="input-group-text" id="ico_cal" name="ico_cal"><i class="fa fa-calendar"></i></div>
    </div>
  </div>
     </div>

    <div class="form-group col-md-4">

     <label id="clby_label" class="hidden">CLOSED BY</label>
        <input type="hidden" name="close_by" id="close_by" value="<?php echo $_SESSION['tech_id'];?>"> 
        <input type="text" class="form-control" name="cl_desc" id="cl_desc" readonly="" value="<?php echo $_SESSION['fname']. '  ' . $_SESSION['lstname'];?>">
    </div>

    <div class="form-group col-md-12">
    <label>Work Output:</label>
     <textarea name="remarks" id="remarks" class="form-control" placeholder="Your Workoutput" 
     style="text-transform:uppercase"></textarea>
     </div>
<hr/>
    <div class="form-group col-md-12">
<p>
      <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
     <button type="button" name="btnClose" id="btnClose" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
  <button class="btn btn-primary float-right mr-2" type="button" name="msgbtn" id="msgbtn" value="show">
    Show Message Thread
  </button>
</p>
<div class="collapse" id="msg_thread">
  <div class="card card-body">

    <div class="row">
            <div  class="col-md-12 dv_msg">
                     <label style="font-weight: bold;">Add Message:</label>
     <textarea name="admsg" id="" class="form-control" placeholder="Reply to their message or give an updates regarding on this ticket..."></textarea> 
      </div>
      <div class="col-md-12 mt-4 mb-2 dv_msg">
            <label for="remarks_view" style="font-weight: bold;">Ticket Thread:</label>
    <div class="container_remarks" >
    <div id="remarks_view"><ul></ul></div>


  </div>
      </div>

    </div>

  </div>
</div>


    </div>

</div>

  <div class="modal-footer">
     <input type="hidden" name="operation" id="operation" />
     <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id'];  ?>">

    </div>
  </div>
  </div>
  </div>
  </form>
</div>
  


   



  <div class="modal-footer">
     <input type="hidden" name="operation" id="operation" />
     <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id'];  ?>">

    </div>

  </form>
</div>
