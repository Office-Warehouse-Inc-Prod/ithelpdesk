<?php
include 'admin.php';
include '../condb.php';
$regcon=new dbconfig();
?>

<head>
<link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/>
<script src="../js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="../css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="styles.css" />
<script src="../js/jquery.dataTables.min.js"></script>
<script src="../js/dataTables.select.min.js"></script>
<script src="../js/dataTables.responsive.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<style>
body {
  background: linear-gradient(to bottom, #ffffff, #99aac8);
  background-attachment: fixed; 
  margin: 0; 
  height: 100vh; 
} 

.swal-btn {
  margin: 10px;
}

.table {
  background-color: #ffffff;
  border-collapse: separate;
  border-spacing: 0;
  border-radius: 8px;
  overflow: hidden;  
  box-shadow: 0 10px 8px rgba(108, 108, 53, 0.4);
  border: 1px solid #e9ecef;
  margin-top: 15px;
}

.table thead th {
  background-color: #54699e;
  color: white;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.85rem;
  letter-spacing: 0.5px;
  padding: 15px;
  border-bottom: 2px solid #dee2e6;
}

.table tbody td {
  padding: 12px 15px;
  vertical-align: middle;
  color: #333;
  border-bottom: 1px solid #f1f1f1;
}

.table tbody tr:hover {
  background-color: #c5cedd !important;
  color: #ffffff !important;
  cursor: pointer;
  transition: all 0.2s ease;
}

.table-responsive {
  border-radius: 8px;
  margin-top: 20px;
}

.dataTables_wrapper .pull-left {
  flex-direction: row;      
  align-items: center;      
  justify-content: flex-start;
  width: 100%;              
  gap: 40px;                
  margin-bottom: 20px; 
}

.dataTables_filter {
  position: relative;
  display: inline-block;    
  margin: 0 !important;    
}

.dataTables_filter label {
  display: flex;
  align-items: center;
  margin-bottom: 0;        
}

.dataTables_filter::before {
  content: "\f002"; 
  font-family: "Font Awesome 5 Free";
  font-weight: 900;
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #213456;
  z-index: 1;
  opacity: 0.6;
}

.dataTables_filter input {
  border: 2px solid #e0e0e0 !important;
  border-radius: 50px !important;
  padding: 8px 15px 8px 35px !important; 
  width: 300px !important;
  background-color: #ffffff !important;
  transition: all 0.3s ease;
  outline: none !important;
  color: #213456;
  margin-left: 0 !important; 
}

.dataTables_filter input:focus {
  border-color: #E1AD01 !important;
  box-shadow: 0 0 10px rgba(225, 173, 1, 0.2) !important;
}

#strbtnact {
  background-color: #E1AD01 !important;
  border: none !important;
  border-radius: 50px !important;
  color: #213456 !important;
  font-weight: 700 !important;
  padding: 10px 25px !important;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-size: 13px;
  transition: all 0.3s ease;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  white-space: nowrap;      
  display: inline-flex;    
  align-items: center;
  gap: 8px;
  margin: 0;                
}

#strbtnact:hover {
  background-color: #213456 !important;
  color: #ffffff !important;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(33, 52, 86, 0.3);
}

#str_crt_modal .modal-content {
  border: none;
  border-radius: 12px;
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
  overflow: hidden;
}

#str_crt_modal .modal-header {
  background: linear-gradient(135deg, #213456, #334c7a);
  color: #fff;
  border-bottom: 4px solid #E1AD01; 
  padding: 20px 25px;
}

#str_crt_modal .store-title {
  font-weight: 700;
  font-size: 1.25rem;
  letter-spacing: 0.5px;
  display: flex;
  align-items: center;
}

#str_crt_modal .close {
  color: #ffffff;
  opacity: 0.8;
  text-shadow: none;
  font-size: 1.5rem;
  transition: all 0.2s ease;
}

#str_crt_modal .close:hover {
  color: #E1AD01;
  opacity: 1;
}

#str_crt_modal .modal-body {
  background-color: #f8f9fa;
  padding: 30px 25px;
}

#str_crt_modal label {
  font-weight: 600;
  color: #213456;
  font-size: 0.9rem;
  margin-bottom: 8px;
}

#str_crt_modal .form-control {
  border-radius: 8px;
  border: 1px solid #ced4da;
  padding: 10px 15px;
  height: auto;
  box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
  transition: all 0.3s ease;
}

#str_crt_modal .form-control:focus {
  border-color: #E1AD01;
  box-shadow: 0 0 0 0.2rem rgba(225, 173, 1, 0.25);
}

#str_crt_modal .modal-footer {
  background-color: #ffffff;
  border-top: 1px solid #e9ecef;
  padding: 20px;
}

#btn_submit {
  background-color: #E1AD01;
  border: none;
  color: #213456;
  font-weight: 700;
  padding: 12px 40px;
  border-radius: 30px;
  transition: all 0.3s ease;
  font-size: 1rem;
}

#btn_submit:hover {
  background-color: #213456;
  color: #E1AD01;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(33, 52, 86, 0.2);
}
</style>

<div class="container mt-3">
  <div class="col-md-12">
      
    <button type="button" id="strbtnact" class="btn btn-primary mb-3" data-toggle="modal" data-target="#str_crt_modal">
      <i class="fas fa-store-alt"></i>&nbsp; Add New Store
    </button>

    <div class="modal fade" id="str_crt_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="store-title" id="exampleModalLabel"><i class="fas fa-store mr-2"></i> Add New Store</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="row">
              <div class="col-md-4 form-group mb-4">
                <label for="str_no">Store No.</label>
                <input type="hidden" name="str_id" id="str_id">
                <input type="text" class="form-control" name="str_no" id="str_no" placeholder="Enter Store No">
              </div>
              <div class="col-md-4 form-group mb-4">
                <label for="str_code">Store Code</label>
                <input type="text" class="form-control" name="str_code" id="str_code" placeholder="Enter Store Code" uppercase>
              </div>
              <div class="col-md-4 form-group mb-4">
                <label for="str_area">Store Area</label>
                <input type="text" class="form-control" name="str_area" id="str_area" placeholder="Enter Store Area">
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 form-group mb-4">
                <label for="str_name">Store Name</label>
                <input type="text" class="form-control" name="str_name" id="str_name" placeholder="Enter Store Name" uppercase>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 form-group mb-4">
                <label for="str_addrs">Store Address</label>
                <input type="text" class="form-control" name="str_addrs" id="str_addrs" placeholder="Enter Full Store Address">
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 form-group mb-4">
                <label for="str_contact">Store Contact</label>
                <input type="text" class="form-control" name="str_contact" id="str_contact" placeholder="Enter Contact Number">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 form-group mb-2">
                <label for="select_AM">Area Manager (A.M.)</label>
                <select class="form-control" name="select_AM" id="select_AM">
                  <option value="0"> &larr; Select A.M. &rarr;</option>
                  <?php
                    $query="SELECT users.id, users.fname, users.lstname, users.role FROM users WHERE users.id NOT IN (65, 68, 70) AND role = 'AM'";
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
              
              <div class="col-md-6 form-group mb-2">
                <label for="select_tech">I.T. Support</label>
                <select class="form-control" name="select_tech" id="select_tech">
                  <option value="0"> &larr; Select I.T. Support &rarr;</option>
                  <?php
                    $query="SELECT tbl_clusers.itsup, tbl_clusers.it_desc FROM tbl_clusers WHERE itsup IN (2,5,44,45,47)";
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
          </div> 

          <div class="modal-footer d-flex justify-content-center">
            <input type="hidden" name="operation" id="operation" value="str_add" /> 
            <button id="btn_submit" class="btn">Submit</button>
          </div>

        </div>
      </div>
    </div>

    <table id="usermtc_table" class="table table-hover table-responsive text-center w-100"></table>

  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){

  var reptable;

  function getdata(){
    $.post('fetchdata/fetch_data.php',{mode:'store_dtable'},function(data){
      admin_datatable(data);
    },'json');
  }
  
  getdata();

  function admin_datatable(t){
    const dataset = t.store_data;
    reptable = $("#usermtc_table").DataTable({
      "dom": '<"pull-left"lf><"pull-right">tip',
      stateSave: true,
      "bDestroy": true,
      "responsive": true, 
      "lengthChange": false, 
      "autoWidth": false,
      language: {
        search: "_INPUT_",
        searchPlaceholder: "Search..."
      },
      pageLength: 10,
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
                data = '<i class="fas fa-store-slash" style="color: red; font-size: 25px;"></i>'
              } else {
                data = '<i class="fas fa-store" style="color: green; font-size: 25px;"></i>'
              }
            }
            return data;
          }
        }
      ]
    }); 

    $('#usermtc_table tbody').on('click', 'button', function () {
      var action = this.id;
      var data = reptable.row( $(this).parents('tr') ).data();
      const strIDx = data.str_id;

      if(action == 'BtnEdit'){
        $("#str_crt_modal").modal("show");
        $('#str_crt_modal #operation').val("stredit");
        $('.store-title').html('<i class="fas fa-edit mr-2"></i> Update Store');

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
          if (result.isConfirmed) {
            Swal.fire({
              title: "STORE HAS BEEN CLOSED!",
              text: "",
              icon: "success",
              timer: 1000,
              showConfirmButton: false 
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
              timer: 1000, 
              showConfirmButton: false, 
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
  }; 

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
          data: { 
            operation: operation, 
            strNo: strNo,
            strCode: strCode,
            strArea: strArea,
            strName: strName,
            strAddrs: strAddrs,
            strContact: strContact,
            slctAM: slctAM,
            slctTech: slctTech 
          },
          success: function() {
            Swal.fire({
              icon: 'success',
              title: 'New Store has been Added',
              showConfirmButton: false,
              timer: 1000
            });
            $("#str_crt_modal").modal("hide");
            getdata();
          }
        });
      } else {
        $.ajax({
          type: 'POST',
          url: 'insert.php', 
          data: { 
            operation: operation, 
            strId: strId,
            strNo: strNo,
            strCode: strCode,
            strArea: strArea,
            strName: strName,
            strAddrs: strAddrs,
            strContact: strContact,
            slctAM: slctAM,
            slctTech: slctTech 
          },
          success: function() {
            Swal.fire({
              icon: 'success',
              title: 'Store has been Updated',
              showConfirmButton: false,
              timer: 1000
            });
            $("#str_crt_modal").modal("hide");
            getdata();
          }
        });
      }
    } else {
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
    $('#str_crt_modal #operation').val("str_add");
    $('.store-title').html('<i class="fas fa-store mr-2"></i> Add New Store');
    
    $("#str_no").val("");
    $("#str_code").val("");
    $("#str_area").val("");
    $("#str_name").val("");
    $("#str_addrs").val("");
    $("#str_contact").val("");
    $("#select_AM").val('0').trigger('change');
    $("#select_tech").val('0').trigger('change');
  });

});
</script> 