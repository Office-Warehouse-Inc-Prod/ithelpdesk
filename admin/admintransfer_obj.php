
      <script type="text/javascript">
      $(document).ready(function(){

      // default status
      $("div.selected select").val("OPEN");

      var reptable;
      var user_id = <?= (int)$_SESSION['user_id']; ?>;

      function getdata(){
      $.post('fetchdata/fetch_data.php',{mode:'trans_tbl'},function(data){
      admin_datatable(data);
      },'json');
      }
      getdata();

      function admin_datatable(t){
      const dataset = t.transdata || [];

      reptable = $("#new_rep_table").DataTable({
"dom": '<"pull-left"f><"pull-right">tip',
      stateSave: false,
      "bDestroy": true,
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      language: {
      emptyTable: "No unassigned reports",
      search: "_INPUT_",
      searchPlaceholder: "Search..."
      },
      pageLength: 10,
      data: dataset,
      "order": [[4, "Desc"]],
      columns: [
      {title:"TicketNo", data:"ticket_no","defaultContent": ""},
      {title:"Selected Department", data:"dept_desc","defaultContent": ""},
      {title:"Department/Store", data:"str_code","defaultContent": ""},
      {title:"Created By", data:"full_name","defaultContent": ""},
    //   {title:"Date Created", data:"date_created","defaultContent": ""},
    {
  title:"Date Created",
  data:"date_created",
  defaultContent:"",
  render: function(data, type, row){

      if(type === 'sort' || type === 'type'){
          // Convert MM/DD/YYYY HH:MM:SS to YYYY-MM-DD HH:MM:SS
          let parts = data.split(" ");
          let date = parts[0].split("/");
          let time = parts[1];

          return date[2] + "-" + date[0] + "-" + date[1] + " " + time;
      }

      return data; // display normally
  }
},
      {title:"SUBJECT", data:"concern","defaultContent": ""},
      {title:"Types of Service", data:"service_desc","defaultContent": ""},
      {title:"CONCERN", data:"subject","defaultContent": ""},
      {title:"Update", data:null,"defaultContent":"<button class='btn btn-danger' name='update'><i class='fas fa-edit'></i></button>"}
      ],
      rowCallback: function(row, data){
      if(data['msg_cnt'] == '1'){
      $(row).find('td').css("font-weight", "bold");
      }
      }
      });

      // refresh every 60 sec
      setInterval(function(){
      getdata();
      }, 60000);


      // 🔥 STRICT EQUALS search (exact match only)
$('#new_rep_table_filter input')
    .off()
    .on('keyup', function () {

        var value = $.fn.dataTable.util.escapeRegex($(this).val());

        reptable
            .column(2) // Department/Store column
            .search('^' + value + '$', true, false) // regex true, smart false
            .draw();
    });

      // open modal
      $('#new_rep_table tbody').off('click', 'button').on('click', 'button', function () {
      var data = reptable.row($(this).parents('tr')).data();
      if(!data) return;

      $('#ticket_no').val(data['ticket_no']);
      $('#store').val(data['store']);
      $('#str_desc').val(data['str_code']);
      $('#crtd_by').val(data['full_name']);
      $('#date_createdx').val(data['date_created']);

      // NOTE: your dataset uses concern as "subject text", subject as "concern text"
      $('#concern').val(data['concern']); // SUBJECT textarea
      $('#tos').val(data['service_desc']);
      $('#message').val(data['subject']); // CONCERN textarea

      $('#sub_num').val(data['sub_id'] || '');

      // ✅ new department assignment fields:
      // prefer new field f_deptsel; fallback to old itsup if data still provides it
      var currentDept = data['f_deptsel'] || data['itsup'] || '0';
      $('#old_dept').val(currentDept);
      $('#f_deptsel').val(currentDept !== '0' ? currentDept : '');

      $('#newrpt_Modal').modal('show');
      $('#action').val("Update");
      $('#operation').val("New_Report");

      var tid = data['ticket_no'];
      $('#tick_title').text("Ticket Number: " + tid);

      // thread viewer
      getinfo(tid, 'remarks', user_id);
      });
      }

      // datetime pickers
      $(function () {
      $('#datetimepicker2, #datetimepicker3').datetimepicker();
      });

      // sub-category loading
      $('#cat').on('change', function() {
      var category_id = this.value;
      $.ajax({
      url: "get_subcat.php",
      type: "POST",
      data: { category_id: category_id },
      cache: false,
      success: function(dataResult){
      $("#sub").html(dataResult);
      }
      });
      });

      // external functions (kept as-is)
      // slct_isp();
      // slct_sub();
      // gtsub_id();
      admin_hideshowforms();

      // submit save/reply
    //   $(document).on('submit', '#newrpt_form', function(event){ //old code
    //   event.preventDefault();
    //   event.stopImmediatePropagation();

    //   $.ajax({
    //   url: "insert.php",
    //   method: "POST",
    //   data: new FormData(this),
    //   contentType: false,
    //   processData: false,
    //   success: function(data){
    //   alert(data);
    //   $('#newrpt_form')[0].reset();
    //   $('#newrpt_Modal').modal('hide');
    //   getdata();
    //   location.reload();
    //   }
    //   });
    //   });


    $(document).on('submit', '#newrpt_form', function(event){
    event.preventDefault();
    event.stopImmediatePropagation();

    Swal.fire({
        title: 'Submitting Ticket...',
        text: 'Please wait while the system saves the ticket and sends the email notification.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: function () {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: "insert.php",
        method: "POST",
        data: new FormData(this),
        contentType: false,
        processData: false,

        success: function(data){
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data,
                confirmButtonColor: '#EAAA00'
            }).then(function(){
                $('#newrpt_form')[0].reset();
                $('#newrpt_Modal').modal('hide');
                getdata();
                location.reload();
            });
        },

        error: function(xhr, status, error){
            Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: xhr.responseText ? xhr.responseText : 'Something went wrong while saving the ticket.',
                confirmButtonColor: '#d33'
            });
        }
    });
});


//   let msisdn = $(this).data("msisdn"); // e.g. 63917xxxxxxx (no +)
  // safest: open chat, then user taps call inside Viber
//   let url = "viber://chat?number=%2B" + msisdn; // %2B = "+"
//   window.location.href = url;




      }); // end doc ready


$('#f_deptsel').select2({
    dropdownParent: $('#newrpt_Modal'),
    width: '100%'
});



      // message thread toggle
      $(document).on('click', '#msgbtn', function(){

      $('.dv_msg').show();
      $('#remarks_view').show();

      if($('#msgbtn').val() == 'show'){
      $('#action').val("New_Report");
      $('#operation').val("New_Report");
      $('#msgbtn').val("hide");
      $('#msg_thread').show('slow');
      } else {
      $('#action').val("Save");
      $('#operation').val("New_Report");
      $('#msgbtn').val("show");
      $('#msg_thread').hide('slow');
      }


      


      
      });



$(document).on('change', '#f_deptsel', function () {

    let dept_id = $(this).val();

    if (dept_id !== '') {
        $.ajax({
            url: 'fetchdata/get_contact.php',
            method: 'POST',
            data: { dept_id: dept_id },
            success: function (response) {
                $('#contactNumber').val(response);
                console.log(response)
            },
            error: function () {
                $('#contactNumber').val('');
            }
        });
    } else {
        $('#contactNumber').val('');
    }
});



      </script>
