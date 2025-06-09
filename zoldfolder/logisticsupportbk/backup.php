<?php 

session_start();

if ($_SESSION['login']!='true'){
    header("Location: index.php");
    exit();
}


include '../condb.php';




$con1=new dbconfig(); ?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketing System</title>

<link rel="stylesheet" type="text/css" href="../css/main.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arvo" />
<link rel="stylesheet" href="../css/4bootstrap.min.css" />
<script src="../js/moment.min.js"></script>
<link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
<link rel="stylesheet" href="../css/jquery.dataTables.min.css" />  
<link rel="stylesheet" type="text/css" href="../css/responsive.dataTables.min.css" />
<script src="../js/jquery-3.5.1.js"></script>

<script src="../js/bootstrap-datetimepicker.min.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/4bootstrap.min.js"></script>
<script src="../js/jquery.dataTables.min.js"></script>
<script src="../js/ellipsis.js"></script>
<script src="../js/dataTables.responsive.min.js"></script>

 <link href="../dist/summernotes/summernote-bs4.min.css" rel="stylesheet">
    <script src="../dist/summernotes/summernote-bs4.min.js"></script>
  </head>

<style>
* {
  box-sizing: border-box;
}
  
body { 
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}
.ct {
  font-size: 12px;
}

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

</style>




<body>
<br/>
<br/><br/><br/>
<div class="container ct">
    <div class="row">
        <div class="col-md-6">
                <div class="card w-100">
                      <div class="card-header" style="font-weight: bold; font-size: 15px;">
                        Create Ticket
                      </div>
                      <div class="card-body">
                    <form method="post" id="report_form" enctype="multipart/form-data">
                        <div class="row">
                             <div class="form-group col-md-12">
                  <input type = "text"  name = "ticket_no" id="ticket_no">
                  <input type="text" id="currentDate" name="currentDate">
                  <input type = "text"  name = "sesstr_num" id="sesstr_num" value="<?php echo $_SESSION['str_num'];?>">
                        


                                    <label style="font-weight: bold;">Subject</label>

                                   <input type="text" class="form-control" name="subject" id="subject" style="font-size: 12px; text-transform: uppercase;" required="">
                                     </div>
                                     <div>
                         
                                  <br><br>
  
                                     </div>
                                         <label style="font-weight: bold;">Types of services</label>
                                           <div class="form-group col-md-12">
                                             <select class="form-control" name="select_tos" style=" font-size: 12px;" id="select_tos" required>
                                             <option value="">SELECT HERE</option>
                                                   <?php
                                                            $query="select * from tbl_typeofservice";
                                                            $run=$con1->prepare($query);
                                                            $run->execute();
                                                            $rs=$run->get_result();
                                                            while ($res=$rs->fetch_assoc()) {
                                                                $deptid = $res['service_id'];
                                                                $deptdesc = $res['service_desc'];

                                                            ?>
                                                            <option><?= $deptdesc ?></option>
                                                            <?php }?>
                                                            ?>   
                                            </select>
                                          </div>
                                  <input type="text" id="status" name="status" value="WAITING FOR IT HELPDESK RESPONSE">
                                 <div class="form-group col-md-12">
                                    <label style="font-weight: bold;">Concern</label>
                                   <div id="summernote"></div>
 
                                     </div>

                        </div>
                      </form>
                             
                        <input type="text" name="operation" id="operation" value="Add" />
                       <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />

                        <!-- <a href="#" class="btn btn-primary">Add Ticket</a> -->
                      </div>
                    </div>
        </div>










        
    <div class="col-md-6">
            <div class="card" style="width: 700px;">
                      <div class="card-header">
                        <ul class="nav nav-pills card-header-pills">
      <li class="nav-item">
        <a class="nav-link" href="#">Waiting for IT helpdesk response</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Closed</a>
      </li>
    </ul>
                      </div>
                      <div class="card-body">


    <table id="reports_table" class="table table-condensed table-responsive">
     <thead class="" id="table_main">
      <tr>
       <th class="text-white">Ticket #</th>
       <th class="text-white w-30">DATE CREATED</th>
       <th class="text-white w-30">SUBJECT</th>
       <th class="text-white">Types of subject</th>
       <th class="text-white w-50">CONCERN</th>
       <th class="text-white w-20">STATUS</th>
       <th class="text-white w-20">UPDATE</th>

      </tr>
</thead>
</table>
                
                    </div>
        </div>
    </div>
</div>
    
</body>
</html>


<!-- Modal -->
<div class="modal fade" id="ticket_modal" tabindex="-1" role="dialog" aria-labelledby="ticket_modal" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 1250px;" role="dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ref # Here</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
                <div class="form-group col-md-auto">
                          <label>Ref #</label>

                          <input type="text" class="form-control" name="ModalRef_no" id="ModalRef_no" required="" readonly="">
                </div>
                    <div class="form-group col-md-auto">
                          <label>Date Created</label>

                          <input type="text" class="form-control" name="ModalDate_create" id="ModalDate_create" required="" readonly="">
                    </div>
                
                      <div class="form-group col-md-8">
                          <label>Subject</label>

                          <input type="text" class="form-control" name="ModalSubject" id="ModalSubject" required="" readonly="">
                    </div>
                      <div class="form-group col-md-auto">
                          <label>Types of Service</label>

                          <input type="text" class="form-control" name="ModalTOS" id="ModalTOS" required="" readonly="">
                    </div>
                       <div class="form-group col-md-12">
                          <label>Concern</label>
                          <textarea name="Modal_concern" id="Modal_concern" class="form-control" readonly=""></textarea>
                      </div> 


<div class="accordion" id="accordionExample">
  <div class="card2">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Collapsible Group Item #1
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne">
      <div class="card-body">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div>
  <div class="card2">
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Collapsible Group Item #2
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo">
      <div class="card-body">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div>
  <div class="card2">
    <div class="card-header" id="headingThree">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Collapsible Group Item #3
        </button>
      </h2>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree">
      <div class="card-body">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div>
</div>







                      </div>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success">Add comment</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

    <script>
    var today = new Date();
    var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()+' '+today.getHours()+':'+(today.getMinutes()<10?'0':'') + today.getMinutes()+':'+(today.getSeconds()<10?'0':'') + today.getSeconds();
    document.getElementById("currentDate").value = date;
    // console.log( (today.getSeconds()<10?'0':'') + today.getSeconds() );

      $('#summernote').summernote({
        placeholder: 'Input your concerns here.',
        tabsize: 2,
        height: 300,
        width: 500
      });
    </script>


<script type="text/javascript">
    
    $(document).ready( function () {
   let table =  $('#reports_table').DataTable();

$('#reports_table').on('click', 'tr', function () {
        // let name = $('td', this).eq(1).text();
        let data = table.row( this ).data();
        // let linkpage = window.Waiting for IT helpdesk response('Waiting for IT helpdesk responsethread.php');
        alert( data );
        // linkpage.location();
        // alert( 'You clicked on '+data[0]+'\'s row' );
        // alert( data );
        $('#ticket_modal').modal("show");





    });
} );






</script>

<script type="text/javascript">



</script>