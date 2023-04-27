<?php  
include 'header.php';
?>
<html>
 <head>
  <title>Date Range Search</title>
  <script src="../js/jquery-1.12.4.js"></script>
  <link rel="stylesheet" href="../css/bootstrap.min.css" />
  <script src="../js/jquery.dataTables.min.js"></script>
  <script src="../js/dataTables.bootstrap.min.js"></script>
  <link rel="stylesheet" href="../css/bootstrap-datepicker.css" />
  <script src="../js/bootstrap-datepicker.js"></script>
  <style>
   body
   {
    margin:0;
    padding:0;
    background-color:#f1f1f1;
   }
   .box
   {
    width:1270px;
    padding:20px;
    background-color:#fff;
    border:1px solid #ccc;
    border-radius:5px;
    margin-top:25px;
   }
  </style>
  

 </head>
 <body>
  <div class="container box">
   <h1 align="center">Date Range Search</h1>
   <br />
   <div class="table-responsive">
    <br />
    <div class="row">
     <div class="input-daterange">
      <div class="col-md-4">
       <input type="text" name="start_date" id="start_date" class="form-control" />
      </div>
      <div class="col-md-4">
       <input type="text" name="end_date" id="end_date" class="form-control" />
      </div>      
     </div>
     <div class="col-md-4">
      <input type="button" name="search" id="search" value="Search" class="btn btn-info" />
    
    <button id="export" class="btn btn-success">Print</button> 
  </div>
  </div>
    <br />
    <table id="order_data" class="table table-bordered table-striped">
     <thead>
      <tr>
       <th>Ticket#</th>
       <th>Store</th>
       <th>Date Created</th>
       <th>Concern</th>
       <th>Status</th>
       <th>I.T Support</th>
       <th>Remarks</th>
       <th>Date Closed</th>
      </tr>
     </thead>
    </table>
    
   </div>
  </div>
 </body>
</html>



<script type="text/javascript" language="javascript" >
$(document).ready(function(){
 
 $('.input-daterange').datepicker({
  todayBtn:'linked',
  format: "yyyy-mm-dd",
  autoclose: true
 });

 fetch_data('no');

 function fetch_data(is_date_search, start_date='', end_date='')
 {
  var dataTable = $('#order_data').DataTable({
   "processing" : true,
   "serverSide" : true,
   "order" : [],
   "ajax" : {
    url:"fetch_date.php",
    type:"POST",
    data:{
     is_date_search:is_date_search, start_date:start_date, end_date:end_date
    }
   }
  });
 }

 $('#search').click(function(){
  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  if(start_date != '' && end_date !='')
  {
   $('#order_data').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
  }
  else
  {
   alert("Both Date is Required");
  }
 }); 
 
});
</script>

<!-- generate report -->

<script type="text/javascript"></script>
  <script src = "js/jquery.dataTables.js"></script>
  <script src="../plugins/table2excel/src/jquery.table2excel.js" type="text/javascript"></script>
  <script>
    $("#export").click(function(){
        $("#order_data").table2excel({

            // exclude CSS class
            exclude: ".noExl", // excluded type of excil
            name: "Worksheet Name",
        filename: "TechnicalReport", //name ng file
        columns: [0,1,2,3,4,5,6,7]

    });
    });
    </script>