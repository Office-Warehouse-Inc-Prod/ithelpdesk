
<!-- Start of Add/Edit Modal -->

<style>


/* Modal Content */
.modal-content {
  background-color: #fff;  /* clean white background */
  border-radius: 8px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.15);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #333;
  padding: 1.5rem;
}

/* Modal Header */
.modal-header {
  border-bottom: 1px solid #dee2e6;
  padding-bottom: 1rem;
}

.modal-header .modal-title {
  font-weight: 700;
  font-size: 1.5rem;
  color: #222;
}

/* Form Labels */
label {
  font-weight: 600;
  color: #555;
  font-size: 0.9rem;
}

/* Form Inputs & Selects */
.form-control-sm {
  border: 1px solid #ced4da;
  border-radius: 4px;
  font-size: 0.95rem;
  padding: 0.375rem 0.75rem;
  transition: border-color 0.2s ease;
  color: #333;
}

.form-control-sm:focus {
  border-color: #007bff;
  box-shadow: 0 0 6px rgba(0,123,255,0.25);
  outline: none;
}

/* Textareas */
textarea.form-control-sm {
  resize: vertical;
  min-height: 70px;
  font-size: 0.95rem;
}

/* Buttons */
.btn-success {
  background-color: #007bff;
  border: none;
  font-weight: 600;
  padding: 0.5rem 1.25rem;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.btn-success:hover {
  background-color: #0056b3;
}

.btn-danger {
  border-radius: 5px;
}

/* Comment Thread */
#msg_thread {
  margin-top: 2rem;
  border-top: 1px solid #dee2e6;
  padding-top: 1.5rem;
}

#msg_thread label {
  font-weight: 700;
  margin-bottom: 0.5rem;
  display: block;
  color: #444;
}

/* Comment input */
#admsg {
  border: 1px solid #ced4da;
  border-radius: 4px;
  padding: 0.5rem;
  font-size: 0.95rem;
  min-height: 80px;
  resize: vertical;
}

/* Comment Thread Container */
.container_remarks {
  max-height: 250px;
  overflow-y: auto;
  background: #f8f9fa;
  border-radius: 6px;
  padding: 1rem;
  border: 1px solid #dee2e6;
}

/* Individual comment */
#remarks_view .comment {
  background-color: #e9ecef;
  border-radius: 10px;
  padding: 0.75rem 1rem;
  margin-bottom: 0.75rem;
  font-size: 0.9rem;
  color: #212529;
  box-shadow: 1px 1px 3px rgba(0,0,0,0.05);
}

/* Modal Footer */
.modal-footer {
  border-top: 1px solid #dee2e6;
  padding-top: 1rem;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}


</style>

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
              $query="select * from tbl_branch ";
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
<label>I.T SUPPORT</label>
<input type="hidden" name="it_num" id="it_num" readonly="">
<select class="form-control form-control-sm" name="itsup" id="itsup" required>
<option value="">Assign support...</option>  
     <?php
              $query="select * from it_tech WHERE deptsel = '1' AND itsup NOT IN ('4','7','8','12','14')";
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
              $query="select * from category WHERE deptsel = '1'";
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
<input type="hidden" name="sub_num" id="sub_num" readonly>


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
      $query="select * from status  WHERE it_module_tag = 'Y'";
      $run=$conn->prepare($query);
      $run->execute();
      $rs=$run->get_result();
      while ($res=$rs->fetch_assoc()) {
      ?>
      <option><?=$res['stat_desc'] ?></option>
      
      <?php }?>
      ?>   
      <option value="CLOSED" readonly>CLOSED</option>

</select>
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

  
<div id="msg_thread">
  <label>Add Comment:</label>
  <textarea name="admsg" id="addmsg" class="form-control form-control-sm" placeholder="Reply to their message or give an updates regarding on this ticket..." required></textarea>

  <label class="mt-4">Comment Thread:</label>
  <div class="container_remarks">
    <div id="remarks_view">
      <!-- Dynamically add comment divs here -->
      <!-- Example: <div class="comment">User comment text here</div> -->
    </div>
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
<input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id'];  ?>">

</div>
</form>
</div>
</div>
</div>
</div>