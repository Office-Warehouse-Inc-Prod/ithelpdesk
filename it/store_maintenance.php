<?php
include 'admin.php';
include '../condb.php';
$regcon=new dbconfig();
 ?>

<style>
.swal-btn{
  margin: 10px; /* add 10px margin between buttons */
}
</style>

<head>
<link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/>
<script src="../js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="../css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="styles.css" />
<script src="../js/jquery.dataTables.min.js"></script>
<script src="../js/dataTables.select.min.js"></script>
<script src="../js/dataTables.responsive.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script src="../vendor/sweetalert/src/sweetalert2.js"></script> -->
 </head>


<div class="container mt-3">
<div class="col-md-12">
     
<button type="button" id="strbtnact" class="btn btn-primary mb-3" data-toggle="modal" data-target="#str_crt_modal"><i class="fas fa-users-cog">&nbsp</i>Add New Store</button>

<div class="modal fade" id="str_crt_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <h5 class="store-title" id="exampleModalLabel">Add New Store</h5>
           <!--  <small id="emailHelp" class="form-text text-muted">Your information is safe with us.</small> -->
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="row">
      <div class="col-md-4 form-group">
          <input type="hidden" name="str_id" id="str_id">
            <input type="text" class="form-control form-control-sm" name="str_no" id="str_no"  placeholder="Store No" >
      </div>
      <div class="col-md-4 form-group">
            <input type="text" class="form-control form-control-sm" name="str_code" id="str_code"  placeholder="Store Code" uppercase>
      </div>
      <div class="col-md-4 form-group">
            <input type="text" class="form-control form-control-sm" name="str_area" id="str_area"  placeholder="Store Area" >
      </div>
      </div>

      <div class="col-md-12 form-group">
            <input type="text" class="form-control form-control-sm" name="str_name" id="str_name"  placeholder="Store Name" uppercase>
      </div>

      <div class="col-md-12 form-group">
            <input type="text" class="form-control form-control-sm" name="str_addrs" id="str_addrs"  placeholder="Store Address" >
      </div>

      <div class="col-md-12 form-group">
            <input type="text" class="form-control form-control-sm" name="str_contact" id="str_contact"  placeholder="Store Contact" >
      </div>

      <div class="row">
      <div class="col-md-6 form-group">
          <select class="form-control form-control-sm" name="select_AM" id="select_AM" >
          <option value="0"> &larr; Select A.M. &rarr;</option>
            <?php
                     $query="SELECT
                                users.id, 
                                users.fname, 
                                users.lstname, 
                                users.role
                            FROM
                                users
                            WHERE
                        users.id NOT IN (65, 68, 70) AND role = 'AM'";
                     $run=$regcon->prepare($query);
                     $run->execute();
                     $rs=$run->get_result();
                     while ($res=$rs->fetch_assoc()) {
                         $usrtid = $res['id'];
                         $usrdesc = $res['fname'].' '.$res['lstname'];
                     ?>
                     <option value="<?php echo $usrtid;?>"><?=$usrdesc; ?></option>
                     <?php }?>
                        
         </select>   
          </div>
          <div class="col-md-6 form-group">
          <select class="form-control form-control-sm" name="select_tech" id="select_tech" >
          <option value="0"> &larr; Select I.T. Support &rarr;</option>
            <?php
                     $query="SELECT
                                tbl_clusers.itsup, 
                                tbl_clusers.it_desc
                              FROM
                                tbl_clusers 
                              WHERE itsup IN (2,5,44,45,47)";
                     $run=$regcon->prepare($query);
                     $run->execute();
                     $rs=$run->get_result();
                     while ($res=$rs->fetch_assoc()) {
                         $techid = $res['itsup'];
                         $techdesc = $res['it_desc'];
                     ?>
                     <option value="<?php echo $techid;?>"><?=$techdesc; ?></option>
                     <?php }?>
                      
         </select>   
          </div>
      </div>


      <div class="modal-footer border-top-0 d-flex justify-content-center">
          <input type="hidden" name="operation" id="operation" value="str_add" /> 
          <button id="btn_submit" class="btn btn-success">Submit</button>
      </div>

    </div>
  </div>
</div>

</div>

  <table id="usermtc_table" class="table table-dark table-responsive table-condensed text-center"></table>

</div>
<script type="text/javascript">
  $(document).ready(function(){


var reptable;

function getdata(){
  $.post('fetchdata/fetch_data.php',{mode:'store_dtable'},function(data){
    admin_datatable(data);
    // console.log(data);
  },'json');
}
getdata();

function admin_datatable(t){
const dataset=t.store_data;
     reptable =  $("#usermtc_table").DataTable({
           "dom":
          '<"pull-left"lf><"pull-right">tip',
          stateSave: true,
          "bDestroy": true,
          "responsive": true, "lengthChange": false, "autoWidth": false,
          language: {
          search: "_INPUT_",
          searchPlaceholder: "Search..."
          },
          pageLength:10,
          data: dataset,
           "order": [[ 0, "Desc" ]],

               columns: [


               {title:"ID", data:"str_id", "width": "5%","defaultContent": ""},
               {title:"STORE NO", data:"str_num","defaultContent": ""},
               {title:"STORE CODE", data:"str_code","width": "5%", "defaultContent": ""},
               {title:"AREA", data:"area_num","defaultContent": ""},
               {title:"STORE NAME", data:"str_name","defaultContent": ""},
               {title:"STORE ADD", data:"str_adrs","defaultContent": ""},
               {title:"STORE CONTACT", data:"str_contact","defaultContent": ""},
               {title:"STATUS", data:"str_status","defaultContent": ""},
               {title:"A.M.ID", data:"AMsup","defaultContent": ""},
               {title:"A.M.", data:"AMdesc","defaultContent": ""},
               {title:"I.T.ID", data:"itsup","defaultContent": ""},
               {title:"I.T.", data:"it_desc","defaultContent": ""},
	            {title:"Update", data:null,"width": "20%","defaultContent": " <Button class=' GetPosition btn btn-success mr-2' name='BtnEdit' id='BtnEdit'><i class='fas fa-edit'></i></Button> <Button class=' GetPositions btn btn-danger' name='BtnDact' id='BtnDact'><i class='fas fa-window-close'></i></Button>"}
	       ],
	       "columnDefs": [
                {

targets: [7],
"width": "2%",
render: function ( data, type, row) {
    if(type === 'display'){
      if(data == 'CLOSED'){
          data = '<i class="fas fa-store-slash" style="color : red; font-size: 25px;"></i>'
        }
        else {
          data = '<i class="fas fa-store" style="color : green; font-size: 25px;"></i>'

        }
}
return data;
}

}
               ]

   }); //  end of datatable




$('#usermtc_table tbody').on('click', 'button', function () {
      
            var action = this.id;
            var data = reptable.row( $(this).parents('tr') ).data();
            const strIDx = data.str_id

     
         if(action == 'BtnEdit'){

            $("#str_crt_modal").modal("show");
            $('#str_crt_modal #operation').val("stredit");
            $('.store-title').text('Update Store');

            
            $('#str_id').val(data['str_id']);
            $('#str_no').val(data['str_num']);
            $('#str_code').val(data['str_code']);
            $('#str_area').val(data['area_num']);
            $('#str_name').val(data['str_name']);
            $('#str_addrs').val(data['str_adrs']);
            $('#str_contact').val(data['str_contact']);
            $("#select_AM").val(data['AMsup']).trigger('change');
            $("#select_tech").val(data['itsup']).trigger('change');


	 }

if (action == 'BtnDact') {
//  alert (strIDx);

        Swal.fire({
  title: "Do you want to update this store status?",
  showDenyButton: true,
  showCancelButton: true,
  confirmButtonText: "CLOSED",
  denyButtonText: "RE-OPEN",
  buttonsStyling: false,
  customClass: {
    confirmButton: 'btn btn-danger swal-btn',
    denyButton: 'btn btn-success swal-btn',
    cancelButton: 'btn btn-secondary swal-btn'
  }
}).then((result) => {
  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
  Swal.fire({
    title: "STORE HAS BEEN CLOSED!",
    text: "",
    icon: "success",
    timer: 1000, // auto-close the modal after 1.5 seconds
    showConfirmButton: false // no button required
  });
  $.ajax({
    type: 'POST',
    url: 'insert.php', 
    data: { operation: 'ClosedStore', strIDx: strIDx },
    success: function() {
      getdata();
    }
  });
} else if (result.isDenied){
  Swal.fire({
    title: "STORE HAS BEEN RE-OPEN!",
    text: "",
    icon: "success",
    timer: 1000, // auto-close the modal after 1.5 seconds
    showConfirmButton: false, // no button required
  });
  $.ajax({
    type: 'POST',
    url: 'insert.php', 
    data: { operation: 'OpenStore', strIDx: strIDx },
    success: function() {
      getdata();
    }
  });
}
});

         }
           
    
        });

  }; //end of function 

$("#btn_submit").click(function (e) { 
  e.preventDefault();
  var operation = $("#operation").val();
  var strId = $("#str_id").val();
  

  var strNo = $("#str_no").val();  
  var strCode = $("#str_code").val();  
  var strArea = $("#str_area").val();  
  var strName = $("#str_name").val();  
  var strAddrs = $("#str_addrs").val();  
  var strContact = $("#str_contact").val();  
  var slctAM = $("#select_AM").val();  
  var slctTech = $("#select_tech").val();  

  if (strNo != "" && strCode != ""  && strArea != "" && strName != "" && strAddrs != "" && strContact != "" && slctAM != "0" && slctTech != "0") {

    if (operation == 'str_add') {
      
      $.ajax({
    type: 'POST',
    url: 'insert.php', 
    data: { operation: operation, 
      strNo: strNo,
      strCode: strCode,
      strArea: strArea,
      strName: strName,
      strAddrs: strAddrs,
      strContact: strContact,
      slctAM: slctAM,
      slctTech: slctTech },
    success: function() {
      Swal.fire({
             icon: 'success',
             title: 'New Store has been Added',
             showConfirmButton: false,
             timer: 1000
          });
          $("#str_no").val("");
          $("#str_code").val("");
          $("#str_area").val("");
          $("#str_name").val("");
          $("#str_addrs").val("");
          $("#str_contact").val("");
          $("#select_AM").val('0').trigger('change');
          $("#select_tech").val('0').trigger('change');
      getdata();
    }
      });

    }
    else{
      $.ajax({
    type: 'POST',
    url: 'insert.php', 
    data: { operation: operation, 
      strId: strId,
      strNo: strNo,
      strCode: strCode,
      strArea: strArea,
      strName: strName,
      strAddrs: strAddrs,
      strContact: strContact,
      slctAM: slctAM,
      slctTech: slctTech },
    success: function() {
      Swal.fire({
             icon: 'success',
             title: 'New Store has been Updated',
             showConfirmButton: false,
             timer: 1000
          });
      getdata();
    }
      });

    }

  }else{
           Swal.fire({
             icon: 'error',
             title: 'Please Complete Details',
             showConfirmButton: false,
             timer: 1500
          });
  return false;
}



});

$('#strbtnact').click(function (e) { 
  e.preventDefault();
  // alert("GOOD");
  $("#str_no").val("");
  $("#str_code").val("");
  $("#str_area").val("");
  $("#str_name").val("");
  $("#str_addrs").val("");
  $("#str_contact").val("");
  $("#select_AM").val('0').trigger('change');
  $("#select_tech").val('0').trigger('change');
  
  
});


}); // Doc Ready








</script>
