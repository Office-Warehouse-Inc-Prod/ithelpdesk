<?php 
$conn=new dbconfig();
// ======== header =========
$datetime = new DateTime();
$timezone = new DateTimeZone('Asia/Manila');
$datetime->setTimezone($timezone);

 ?>
<style>
/* =========================
   DEPARTMENT MODAL LAYOUT
========================= */

#dept_graph_modal .modal-dialog.modal-dept-wide {
    width: 96vw !important;
    max-width: 96vw !important;
    margin: 1rem auto !important;
}

#dept_graph_modal .modal-content {
    width: 100% !important;
    border-radius: 0.5rem;
}

#dept_graph_modal .modal-header {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #dee2e6;
}

#dept_graph_modal .modal-title {
    font-size: 1.1rem;
    font-weight: 600;
}

#dept_graph_modal .modal-body {
    max-height: 86vh;
    overflow-y: auto;
    padding: 1rem;
}


/* =========================
   CHART AREA
========================= */

#dept_graph {
    width: 100% !important;
    height: 38vh !important;
    min-height: 18rem;
    margin-bottom: 1rem;
}


#store_graph_modal .modal-dialog {
    width: 100vw !important;
    max-width: 100vw !important;
    height: 100vh !important;
    max-height: 100vh !important;
    margin: 0 !important;
}

#store_graph_modal .modal-content {
    width: 100vw !important;
    height: 100vh !important;
    border-radius: 0 !important;
    border: none !important;
}

#store_graph_modal .modal-header {
    height: 56px;
    padding: 12px 20px;
    background: #ffffff;
    border-bottom: 1px solid #ddd;
}

#store_graph_modal .modal-body {
    height: calc(100vh - 56px) !important;
    padding: 18px 24px !important;
    overflow-y: auto !important;
}

#store_graph {
    width: 100% !important;
    height: 520px !important;
    min-height: 520px !important;
}

#store_ticket_section {
    margin-top: 18px;
}

#tbl_store_tickets {
    font-size: 12px;
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



<div class="modal fade" id="dept_graph_modal" tabindex="-1" role="dialog" aria-labelledby="dept_graph_modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dept-wide" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Per Department</h5>
        <button type="button" class="close" id="substr_clsbtn" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="row">
          <div class="col-md-12">
            <div id="dept_graph"></div>
          </div>
<div class="col-md-12 mt-3">
  <div class="table-responsive dept-table-wrapper">
<div class="table-responsive dept-table-wrapper">
    <table class="table table-striped table-bordered table-hover" id="tbl_deptbreak" style="width:100%;"></table>
</div>
  </div>
</div>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Sub-Storegraph modal -->
<div class="modal fade" id="store_graph_modal" tabindex="-1" role="dialog" aria-labelledby="store_graph_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dept-wide" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Per Store</h5>
                <button type="button" class="close" id="substr_clsbtn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <!-- Store Graph -->
                <div id="store_graph"></div>

                <hr>

                <!-- Store Ticket Table -->
                <div id="store_ticket_section" style="display:none;">
                    <h5 id="storeTicketTableTitle" style="font-weight:700; margin-bottom:12px;">
                        Store Ticket Details
                    </h5>

                    <div class="table-responsive">
                        <table id="tbl_store_tickets" class="table table-bordered table-striped table-sm" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Ticket No</th>
                                    <th>Store</th>
                                    <th>Date Created</th>
                                    <th>Concern</th>
                                    <th>Via</th>
                                    <th>Status</th>
                                    <th>DTDF</th>
                                    <th>Department</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Date Closed</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

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

                      <div class="col-md-9">
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



<div class="modal fade" id="category_ticket_modal" tabindex="-1" role="dialog" aria-labelledby="categoryTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width:95vw;">
        <div class="modal-content">

            <div class="modal-header" style="background:#1f375c; color:#ffc400;">
                <h5 class="modal-title" id="categoryTicketModalLabel">
                    Category Ticket Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <table id="tbl_category_tickets" class="table table-bordered table-striped table-sm" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Ticket No</th>
                            <th>Store</th>
                            <th>Date Created</th>
                            <th>Concern</th>
                            <th>Via</th>
                            <th>Status</th>
                            <th>DTDF</th>
                            <th>Department</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Date Closed</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>
</div>