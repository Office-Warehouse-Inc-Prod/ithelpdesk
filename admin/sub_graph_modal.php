<?php 
$conn=new dbconfig();
// ======== header =========
$datetime = new DateTime();
$timezone = new DateTimeZone('Asia/Manila');
$datetime->setTimezone($timezone);

 ?>

 <style>
  #store_graph_modal .modal-dialog{
  max-width: 1100px; /* desktop width */
  margin: 1.25rem auto;
}

#store_graph_modal .modal-content{
  border-radius: 16px;
  border: 1px solid rgba(0,0,0,0.08);
  overflow: hidden;
}

/* Header with hierarchy */
#store_graph_modal .modal-header{
  background: linear-gradient(180deg, rgba(79,70,229,0.08), rgba(255,255,255,0));
  border-bottom: 1px solid rgba(0,0,0,0.08);
  padding: 16px 18px;
}

#store_graph_modal_header{
  font-weight: 700;
  font-size: 18px;
  margin: 0;
}

/* Body spacing */
#store_graph_modal .modal-body{
  padding: 16px 18px;
}

/* Section cards inside modal */
.modal-section{
  background: rgba(255,255,255,0.75);
  border: 1px solid rgba(0,0,0,0.08);
  border-radius: 14px;
  padding: 14px;
  box-shadow: 0 8px 22px rgba(0,0,0,0.04);
}

/* Section title */
.modal-section-title{
  font-size: 13px;
  font-weight: 800;
  letter-spacing: .6px;
  text-transform: uppercase;
  color: rgba(0,0,0,0.55);
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 10px;
}

.modal-section-title:before{
  content: "";
  width: 10px;
  height: 10px;
  border-radius: 3px;
  background: rgba(79,70,229,0.55);
}

/* Make inputs feel “premium” */
#store_graph_modal .form-control,
#store_graph_modal select,
#store_graph_modal textarea{
  border-radius: 10px;
  border: 1px solid rgba(0,0,0,0.12);
  background: white;
}

#store_graph_modal label{
  font-weight: 700;
  font-size: 12px;
  letter-spacing: .4px;
  text-transform: uppercase;
  color: #213456;
}
 </style>
<div class="modal fade bd-example-modal-lg" id="genModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Generate Report</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

<div class="alert alert-danger div_alrt" role="alert">
  <span id="div_alrtmsg"></span>
</div>

                 <br />
    <div class="row">

    <!-- <div class="col-md-6">
      <label class="sr-only" for="inlineFormInputGroup">Start Date</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text">START DATE</div>
        </div>
       
        <input type="date" class="form-control" name="start_date" id="start_date">
      </div>
    </div> -->
    <input type="hidden" name="start_date" id="start_date" value="2018-01-01">

    <input type="hidden" name="end_date" id="end_date" value="<?php echo date('Y-m-d')?>">

        <!-- <div class="col-md-6">
      <label class="sr-only" for="inlineFormInputGroup">END Date</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text">END DATE</div>
        </div>
       
        <input type="date" class="form-control" name="end_date" id="end_date">
      </div>
    </div> -->

        <div class="col-md-6">
      <label class="sr-only" for="inlineFormInputGroup">SELECT CATEGORY</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text">SELECT CATEGORY</div>
        </div>
                  <select class="form-control form-control" name="slct_cat" id="slct_cat">
          <!-- <option value="1,2,3,4,5,6,7,8">ALL</option>   -->
             <?php
                  $query="select * from categories";
                  $run=$conn->prepare($query);
                  $run->execute();
                  $rs=$run->get_result();
                  while ($res=$rs->fetch_assoc()) {
                    $cat_id = $res['cat_id'];
                    $cat_desc = $res['cat_desc'];
                  ?>

                  <option value="<?php echo $cat_id;?>"><?= $cat_desc; ?></option>
                  <?php }?>
                  ?>   
      </select> 
      </div>
    </div>


        <div class="col-md-6">
      <label class="sr-only" for="inlineFormInputGroup">SELECT STATUS</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text">SELECT STATUS</div>
        </div>
          <select class="form-control" name="slct_stat" id="slct_stat">
          <!-- <option value="1,2,3,4,5,6,7,8">ALL</option>   -->
             <?php
                  $query="select * from tbl_status";
                  $run=$conn->prepare($query);
                  $run->execute();
                  $rs=$run->get_result();
                  while ($res=$rs->fetch_assoc()) {
                    // $cat_id = $res['cat_id'];
                    $stat_desc = $res['stat_desc'];
                  ?>

                  <option value="<?php echo $stat_desc;?>"><?= $stat_desc; ?></option>
                  <?php }?>
                  ?>   
      </select> 
      </div>
    </div>
  
    

      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
         <button type="button" id="search" style="float: right;" class=" btn btn-info"><i class="fas fa-search"></i>SEARCH</button>
      </div>
<div class="row"> </div>
 
      </div>
    </div>
  </div>
</div>

<div class="table-responsive">
    <table id="dtbl_itsup" class="table table-bordered table-striped" style="width:100%">
    
    </table>
</div>

<!-- Sub-Piegraph modal -->
<div class="modal fade" id="piegraphModal" tabindex="-1" role="dialog" aria-labelledby="piegraphModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">SUB CATEGORY</h5>
        <button type="button" class="close" id="subpie_clsbtn" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div id="chartdiv9"></div>
      </div>
      <table class="table table-striped" id="tbl_cat"></table>
    </div>
  </div>
</div>

<!-- Sub-Storegraph modal -->
<div class="modal fade" id="store_graph_modal" tabindex="-1" role="dialog" aria-labelledby="store_graph_modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: #465c81;">
        <h5 class="modal-title" id="exampleModalLongTitle" style="color:white;">CATEGORIES</h5>
        <button type="button" class="close" id="substr_clsbtn" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div id="store_graph"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="storenet_graph_modal" tabindex="-1" role="dialog" aria-labelledby="storenet_graph_modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Per Store</h5>
        <button type="button" class="close" id="substr_clsbtn" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div id="storenet_graph"></div>
      </div>
    </div>
  </div>
</div>




<!-- I.T Bar modal -->
<div class="col-md-12 col-12 justify-content-between">
<div class="modal fade" id="tech_bar_modal" tabindex="-1" role="dialog" aria-labelledby="tech_bar_modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg mw-100" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" id="substr_clsbtn" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
                    <div class="d-flex justify-content-center row mr-5">
                      <div class="  d-flex justify-content-center col-xs-12 col-12 col-md-3">
                          <img class="img-responsive" id="tech_img" src="" alt="User Image" width="300" height="250"/>
                      </div>

                      <div class="col-md-9" style="">
                              <h5 class="" id="ITName"></h5>
                              <span style="font-size: 14px;" class="" id="cmprole"></span>
                              <hr style="border: 1px solid;">
                  <div class="itdiv1 text-white">
                      <div class="row">
                       <ul class="col-md-12 list-group flex-md-row text-dark">
                        <li class="mr-4 list-group-item d-flex justify-content-between align-items-center">
                         Number of assigned tickets:
                          <span class="ml-2 badge badge-primary badge-pill" id="itm_total"></span>
                        </li>
                        <li class="mr-4 list-group-item d-flex justify-content-between align-items-center">
                          Open Tickets:
                          <span class="ml-2 badge badge-primary badge-pill" id="itm_open"></span>
                        </li>
                        <li class="mr-4 list-group-item d-flex justify-content-between align-items-center">
                          Attended with Fix Asset:
                          <span class="ml-2 badge badge-primary badge-pill" id="itm_wfa"></span>
                        </li>
                          <li class="mr-4 list-group-item d-flex justify-content-between align-items-center">
                          Closed Tickets:
                          <span class="ml-2 badge badge-primary badge-pill" id="itm_closed"></span>
                        </li>
                      </ul> 

                       <ul class="col-md-12 list-group flex-md-row text-dark mt-4">
                        <li class="mr-4 list-group-item d-flex justify-content-between align-items-center">
                          Number of Reassigned Ticket:
                          <span class="ml-2 badge badge-primary badge-pill" id="itm_resasncnt"></span>
                        </li>

                        <li class="mr-4 list-group-item d-flex justify-content-between align-items-center">
                          Number of tickets (SLA): 
                          <span class="ml-2 badge badge-primary badge-pill" id="itm_cntsla"></span>
                        </li>
                        <li class="mr-4 list-group-item d-flex justify-content-between align-items-center">
                         Work Summary:
                          <span class="ml-2 badge badge-primary badge-pill" id="itm_sla"></span>
                        </li>
                      </ul>

                  </div>

                              <!-- End of row -->

                              </div>
                             

                    </div>
              
</div> 

      <div class="col-md-12 mt-3">
       <hr style="border: 1px solid;">

       <table id="dtbl_itsup" class="table table-striped table-responsive table-condensed text-center"></table>
        </div>
      </div>
      </div>
    </div>
  </div>
</div>
</div>