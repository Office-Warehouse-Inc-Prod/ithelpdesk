
<script type="text/javascript">
  $(document).ready(function(){


  

// for Status Open    
$("div.selected select").val("OPEN");


  // $('#action').hide();

var reptable;
var user_id = <?= $_SESSION['user_id']; ?>

/**
 * Getdata.
 */
function getdata(){
  $.post('fetchdata/fetch_data.php',{mode:'newrpt_tbl'},function(data){
    // console.log(data);
    admin_datatable(data);
  },'json');
}
getdata();

/**
 * Admin datatable.
 */

    function loadCommentThread(ticket_no) {
        const $remarksView = $('#remarks_view');
        const ticketValue = (ticket_no || '').toString().trim();

        if (!ticketValue) return;
        
        $remarksView.fadeOut(150, function() {
            $remarksView.html('<div class="text-center text-muted mt-4 mb-4"><div class="spinner-border spinner-border-sm me-2 text-primary"></div>Loading conversation...</div>').fadeIn(150);
        });

        $.ajax({
            url: 'get_comments.php', 
            type: 'POST',
            dataType: 'json',
            data: { ticket_no: ticketValue },
            success: function(response) {
                let html = '';
                
                if (Array.isArray(response) && response.length > 0) {
                    var currentUserIdStr = "<?= $_SESSION['user_id'] ?? '' ?>";
                    var currentUserNameStr = "<?= $_SESSION['fname'] ?? '' ?>";
                    let reversedResponse = response.slice().reverse();

                    reversedResponse.forEach(function(comment, index) {
                        let sender = comment.userId || 'Unknown';
                        
                        let isMe = false;
                        if(currentUserIdStr !== "" && sender === currentUserIdStr) isMe = true;
                        if(currentUserNameStr !== "" && sender.includes(currentUserNameStr)) isMe = true;
                        
                        let bubbleClass = isMe ? 'chat-right' : 'chat-left';
                        let delay = index * 0.05; 
                        let relativeTime = timeAgo(comment.comment_date);
                        let replyTimeColor = isMe ? "color: #e2e8f0;" : "color: #64748b;";
                        
                        html += `
                            <div class="chat-bubble ${bubbleClass}" style="animation-delay: ${delay}s;">
                                <div class="msg-meta">
                                    <span class="msg-meta-name">${sender}</span>
                                    <span class="msg-time">${comment.comment_date}</span> 
                                </div>
                                <div style="white-space: pre-wrap;">${comment.comment_details}</div>
                                
                                <div class="reply-time" style="font-size: 0.65rem; text-align: right; margin-top: 6px; opacity: 0.85; font-style: italic; ${replyTimeColor}">
                                    Replied ${relativeTime}
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="text-center text-muted mt-3" style="font-size:13px;"><i class="fas fa-comments mb-2" style="font-size:24px; opacity:0.5;"></i><br>No comments yet. Start the conversation!</div>';
                }
                
               $remarksView.fadeOut(150, function() {
                    $remarksView.html(html).fadeIn(300);
                    $('.dv_msg, .container_remarks').slideDown(300); 

                    setTimeout(() => {
                        const $container = $('.container_remarks');
                        if ($container.length) {
                            $container.animate({ scrollTop: 0 }, 600, 'swing');
                        }
                    }, 200);
                });
            },
            error: function(xhr) {
                $remarksView.html('<div class="text-danger text-center mt-3">Error loading comments.</div>');
            }
        });
    }
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
     var message = data;
     if (typeof data === 'object') {
       message = data.message || JSON.stringify(data);
     }
     Swal.fire({
       icon: 'success',
       title: message,
       showConfirmButton: false,
       timer: 1500
     });
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
