
<script type="text/javascript">
  $(document).ready(function(){


  

// for Status Open    
$("div.selected select").val("OPEN");


  // $('#action').hide();

var reptable;
var user_id = <?= $_SESSION['user_id']; ?>

function getdata(){
  $.post('fetchdata/fetch_data.php',{mode:'newrpt_tbl'},function(data){
    // console.log(data);
    admin_datatable(data);
  },'json');
}
getdata();

function admin_datatable(t){
const dataset=t.newrptdata;
     reptable =  $("#new_rep_table").DataTable({
           "dom":
          '<"pull-left"lf><"pull-right">tip',
           // ajax: t,
          stateSave: true,
          "bDestroy": true,
          "responsive": true, "lengthChange": false, "autoWidth": false,
          language: {
          emptyTable: "No unassinged reports",
          search: "_INPUT_",
          searchPlaceholder: "Search..."
          },
          pageLength:5,
          data: dataset,
           "order": [[ 0, "Desc" ]],

          columns: [
          {title:"TicketNo", data:"ticket_no","defaultContent": ""},
          {title:"Department/Store", data:"str_code","defaultContent": ""},
          {title:"Created By", data:"full_name","defaultContent": ""},
          {title:"Date Created", data:"date_created","defaultContent": ""},
          {title:"SUBJECT", data:"concern","defaultContent": ""},
          {title:"Types of Service", data:"service_desc","defaultContent": ""},
          {title:"CONCERN", data:"subject","defaultContent": ""},
          {title:"Update", data:null,"defaultContent": "<Button class='btn btn-danger' name='update'><i class='fas fa-edit'></i></Button>"}


          ],
              rowCallback: function(row, data, index){
    if(data['msg_cnt'] == '1'){
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



   }); //  end of datatable


   setInterval( function () {
    getdata();
   // admin_datatable();
}, 60000);
 $('#new_rep_table tbody').on( 'click', 'button', function () {
        var data = reptable.row( $(this).parents('tr') ).data();

                $('#ticket_no').val(data['ticket_no']);
                $('#store').val(data['store']);
                $('#str_desc').val(data['str_code']);
                $('#crtd_by').val(data['full_name']);
                $('#date_createdx').val(data['date_created']);
                $('#concern').val(data['concern']);
                $('#tos').val(data['service_desc']);
                $('#message').val(data['subject']);
                $('#sub_num').val(data['sub_id']);



  $('#newrpt_Modal').modal('show');
  $('#action').val("Update");
  $('#operation').val("Save and Reply"); 

var tid=$(this).parent().siblings(':first').html();
$('#tick_title').text("Ticker Number: "+tid+"");

getinfo(tid, 'remarks', user_id);
// console.log(tid)

// $('#itsup').change(function(e) { 
//     e.preventDefault();
//     let Dataxxx = $(this).val(); // Correct way to get value in jQuery
//     alert(Dataxxx);
//   });

        });

  $('#userModal').modal({"show": true, "backdrop": 'static'});

  }; //end of function 

  $('#ModalDate_close').datetimepicker();
  $('#date_refNo').datetimepicker();


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


$(function () {
          $('#datetimepicker2, #datetimepicker3').datetimepicker()
      });


slct_isp();
slct_sub();
gtsub_id();
admin_hideshowforms();


$(document).on('submit', '#newrpt_form', function(event)
 {
  event.preventDefault();
  event.stopImmediatePropagation();
   $.ajax({
    url:"insert.php",
    method:'POST',
    data:new FormData(this),
    contentType:false,
    processData:false,
    success:function(data)
    {
     alert(data);
     $('#newrpt_form')[0].reset();
     $('#newrpt_Modal').modal('hide');
     getdata();
     location.reload(); 
    }
   });
 });





}); // end of docu.ready

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
$('#operation').val("Save and Reply");
$('#msgbtn').val("show");
$('#msg_thread').hide('slow');
}

});



</script>
