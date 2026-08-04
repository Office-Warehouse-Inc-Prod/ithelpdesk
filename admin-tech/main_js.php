
<script type='text/javascript'>
  $(document).ready(function () {

    const toggle = document.getElementById('darkModeToggle');
    const body = document.body;

    if (localStorage.getItem('theme') === 'dark') {
      body.classList.add('dark-mode');
      if(toggle) toggle.checked = true;
    }

    if(toggle) {
        toggle.addEventListener('change', () => {
          if (toggle.checked) {
            body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
          } else {
            body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light');
          }
        });
    }

    verQTY();

    /**
     * Ver q t y.
     */
    function verQTY() {
      let formxxx = document.getElementById('cof_form');
      if (formxxx) {
          let formData = new FormData(formxxx);
          $.ajax({
            url: "insert.php",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
            },
            error: function (error) {
            }
          });
      }
    }

    function timeAgo(dateParam) {
        if (!dateParam) return "";
        let date = new Date(dateParam.replace(/-/g, "/"));
        let now = new Date();
        let seconds = Math.floor((now - date) / 1000);
        
        let interval = Math.floor(seconds / 86400);
        if (interval >= 1) return interval + " day" + (interval === 1 ? "" : "s") + " ago";
        
        interval = Math.floor(seconds / 3600);
        if (interval >= 1) return interval + " hour" + (interval === 1 ? "" : "s") + " ago";
        
        interval = Math.floor(seconds / 60);
        if (interval >= 1) return interval + " minute" + (interval === 1 ? "" : "s") + " ago";
        
        return "just now";
    }

  
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

    if (/Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) { $("#ovrall").hide(); }

    var user_id = "<?= $_SESSION['user_id'] ?? ''; ?>";

    let val = '';
    $('#card_totalval').click(function (e) {
      e.preventDefault();
      val = $(this).attr("value");
    });
    $('#card_openval').click(function (e) {
      e.preventDefault();
      val = $(this).attr("value");
    });
    $('#card_openwfaval').click(function (e) {
      e.preventDefault();
      val = $(this).attr("value");
    });
    $('#card_closedval').click(function (e) {
      e.preventDefault();
      val = $(this).attr("value");
    });

    $('#myInput').on('input', function () {
      table.search(this.value).draw();
    });

    /**
     * Getdata.
     */

function getdata(yr) {
  $.post('fetchdata/fetch_data.php', { yr: yr, mode: 'dtb' }, function (data) {
    admin_datatable(data);
  }, 'json');
}

var table_tickets;

function admin_datatable(t) {
  const dataset = t.rptdata;
  table_tickets = $("#report_data").DataTable({
    "dom": 'B<"pull-left"lf><"pull-right">tip',
    "buttons": [
      {
        text: '<i class="fas fa-plus"></i>',
        attr: {
          title: 'Add Report',
          id: 'add_button',
          class: 'second btn btn-danger',
        },
        action: function (e, dt, node, config) {
          $('#report_form').trigger('reset');
          $('#remarks_view').empty();
          $('.dv_msg').hide();
          $('.container_remarks').hide();
          $('#msg_thread').hide();
          
          $('#userModal').modal({ "show": true, "backdrop": 'static' });
          $('.modal-title').text("ADD REPORT");
          $('#action').val("Add");
          $('#operation').val("Add");
          $(':input[type="submit"]').prop('disabled', false);
          $('#date_created, #date_refNo, #date_closed, #remarks').attr('readonly', false);
          $('#store, #via, #status, #itsup, #cat, #sub, #isp').prop("disabled", false);
          $('#img').empty();
          $('#addmsg').removeAttr('required');
          $('#is_transfer').prop('checked', false); 

          if(typeof admin_hideshowforms === "function") admin_hideshowforms();
          if(typeof unilayout_netshowmodalform === "function") unilayout_netshowmodalform();
        }
      },
      {
        extend: 'excelHtml5',
        text: '<i class="fas fa-file-excel"></i>',
        attr: {
          title: 'Export to Excel',
          class: 'btn btn-success'
        }
      }
    ],
    "pagingType": "full_numbers",
    "bDestroy": true,
    "responsive": true, 
    "lengthChange": false, 
    "autoWidth": false,
    "language": {
      "search": "_INPUT_",
      "searchPlaceholder": "Search..."
    },
    "initComplete": function (settings, json) {
      var $searchWrapper = $('#report_data_filter');
      var $nativeSearchInput = $searchWrapper.find('input');

      $nativeSearchInput
        .attr('id', 'report_data_filter_disabled')
        .attr('placeholder', 'Auto Fill Status')
        .prop('disabled', true)
        .css('margin-right', '10px');

      var $activeSearch = $('<input type="search" class="form-control form-control-sm">')
        .attr('id', 'report_data_free_search')
        .attr('placeholder', 'Type to Search')
        .css({
          'display': 'inline-block',
          'width': 'auto',
          'margin-left': '10px'
        });

      $searchWrapper.append($activeSearch);

      $.fn.dataTable.ext.search.push(
        function(settings, searchData, index, rowData, counter){
          if (settings.nTable.id !== 'report_data') return true;
          var freeSearchVal = $('#report_data_free_search').val();
          if(!freeSearchVal) return true;
          var searchRegex = new RegExp(freeSearchVal, 'i');
          for(var i=0; i<searchData.length; i++){
            if(searchRegex.test(searchData[i])){
              return true;
            }
          }
          return false;
        }
      );

      $activeSearch.on('input', function () {
        table_tickets.draw();
      });
    },
    "pageLength": 10,
    "data": dataset,
    "order": [[2, "Desc"]],
    "columns": [
      { title: "Update", data: null, "defaultContent": "<Button class='btn btn-danger' name='update' id='dtbsecond'><i class='fas fa-edit'></i></Button>" },
      { title: ".", data: "msg_cnt", "defaultContent": "" },
      { title: "TicketNo", data: "ticket_no", "defaultContent": "" },
      { title: "  Store", data: "str_code", "defaultContent": "" },
      { title: "Date Created", data: "date_created", "defaultContent": "" },
      { title: "Subject", data: "subject", "defaultContent": "" },
      { title: "STATUS", data: "status", "defaultContent": "" },
      { title: "Assigned Support", data: "it_desc", "defaultContent": "" },
      { title: "CATEGORY", data: "category", "defaultContent": "" },
      { title: "SUBCATEGORY", data: "sub_category", "defaultContent": "" },
      { title: "DATE CLOSED", data: "date_closed", "defaultContent": "" },
      { title: "DAYS COMPLETION", data: "tdc", "defaultContent": "" },
      { title: "WORKOUTPUT", data: "remarks", "defaultContent": "" }
    ],
    "columnDefs": [
      {
        targets: [7, 11, 12],
        "width": "2%",
        render: function (data, type, row) {
          if (type === 'display') {
            if (data == '1 Days Unresolved') data = '1 Day Unresolved';
            else if (data == '01/01/1970 01:00' || data == '01/01/1970 08:00' || data < 0 || data == 0 || data == '0 Days Unresolved') data = '';
          }
          return data;
        }
      },
      {
        targets: [1],
        "width": "2%",
        render: function (data, type, row) {
          if (type === 'display') {
            if (data == '1') data = '<i class="fas fa-envelope fa-lg bg-warning"></i>';
            else if (data == '0') data = '';
          }
          return data;
        }
      }
    ],
    rowCallback: function (row, data, index) {
      $(row).removeClass('status-open status-open-msg status-closed status-subject-closing status-pending');

      if (data['status'] === "ON PROCESS") {
        if (data['msg_cnt'] === '1' || data['msg_cnt'] === '0') {
          $(row).addClass('status-open');
        }
      } else if (data['status'] === 'PENDING') {
        $(row).addClass('status-pending');
        $(row).find('td:eq(11)').html(' ');
      } else if (data['status'] === 'CLOSED') {
        $(row).addClass('status-closed');
      } else if (data['status'] === 'SUBJECT FOR CLOSING') {
        $(row).addClass('status-subject-closing');
      }
    }
  });

  $('#report_data tbody').on('dblclick', 'tr', function () {
    var data = table_tickets.row($(this)).data();
    if (!data) return;
    open_ticket_modal(data);
  });

  // KPI Card Filters
  $('#card_totalval, #card_openval, #card_openwfaval, #card_closedval').on('click', function () {
    var val = $(this).attr("value");
    table_tickets.columns(7).search(val).draw();
  });

  $('.clcktxt').click(function () {
    var val = $(this).attr("value");
    table_tickets.columns(7).search(val).draw();
    $('#network_tb').slideToggle();
    $('html, body').animate({ scrollTop: 1600 }, 1000);
  });
}

function getdata_transfer(yr) {
  $.post('fetchdata/fetch_data.php', { yr: yr, mode: 'dtb_transfer' }, function (data) {
    admin_datatable_transfer(data);
  }, 'json');
}

var table_transfer;

function admin_datatable_transfer(t) {
  const dataset = t.transferdata;
  table_transfer = $("#transferred_data").DataTable({
    "dom": 'B<"pull-left"lf><"pull-right">tip',
    "buttons": [
      {
        extend: 'excelHtml5',
        text: '<i class="fas fa-file-excel"></i>',
        attr: {
          title: 'Export Transferred Tickets',
          class: 'btn btn-success'
        }
      }
    ],
    "pagingType": "full_numbers",
    "bDestroy": true,
    "responsive": true, 
    "lengthChange": false, 
    "autoWidth": false,
    "language": {
      "search": "_INPUT_",
      "searchPlaceholder": "Search..."
    },
    "initComplete": function (settings, json) {
      var $searchWrapper = $('#transferred_data_filter');
      var $nativeSearchInput = $searchWrapper.find('input');

      $nativeSearchInput
        .attr('id', 'transferred_data_filter_disabled')
        .attr('placeholder', 'Auto Fill Status')
        .prop('disabled', true)
        .css('margin-right', '10px');

      var $activeSearch = $('<input type="search" class="form-control form-control-sm">')
        .attr('id', 'transferred_data_free_search')
        .attr('placeholder', 'Type to Search')
        .css({
          'display': 'inline-block',
          'width': 'auto',
          'margin-left': '10px'
        });

      $searchWrapper.append($activeSearch);

      $.fn.dataTable.ext.search.push(
        function(settings, searchData, index, rowData, counter){
          if (settings.nTable.id !== 'transferred_data') return true; // Only apply to this table
          var freeSearchVal = $('#transferred_data_free_search').val();
          if(!freeSearchVal) return true;
          var searchRegex = new RegExp(freeSearchVal, 'i');
          for(var i=0; i<searchData.length; i++){
            if(searchRegex.test(searchData[i])){
              return true;
            }
          }
          return false;
        }
      );

      $activeSearch.on('input', function () {
        table_transfer.draw();
      });
    },
    "pageLength": 10,
    "data": dataset,
    "order": [[2, "Desc"]],
    "columns": [
      { title: "Update", data: null, "defaultContent": "<Button class='btn btn-danger' name='update' id='dtbsecond_transfer'><i class='fas fa-edit'></i></Button>" },
      { title: ".", data: "msg_cnt", "defaultContent": "" },
      { title: "TicketNo", data: "ticket_no", "defaultContent": "" },
      { title: "  Store", data: "str_code", "defaultContent": "" },
      { title: "Date Created", data: "date_created", "defaultContent": "" },
      { title: "Subject", data: "subject", "defaultContent": "" },
      { title: "STATUS", data: "status", "defaultContent": "" },
      { title: "Assigned Support", data: "it_desc", "defaultContent": "" },
      { title: "CATEGORY", data: "category", "defaultContent": "" },
      { title: "SUBCATEGORY", data: "sub_category", "defaultContent": "" },
      { title: "DATE CLOSED", data: "date_closed", "defaultContent": "" },
      { title: "DAYS COMPLETION", data: "tdc", "defaultContent": "" },
      { title: "WORKOUTPUT", data: "remarks", "defaultContent": "" }
    ],
    "columnDefs": [
      {
        targets: [7, 11, 12],
        "width": "2%",
        render: function (data, type, row) {
          if (type === 'display') {
            if (data == '1 Days Unresolved') data = '1 Day Unresolved';
            else if (data == '01/01/1970 01:00' || data == '01/01/1970 08:00' || data < 0 || data == 0 || data == '0 Days Unresolved') data = '';
          }
          return data;
        }
      },
      {
        targets: [1],
        "width": "2%",
        render: function (data, type, row) {
          if (type === 'display') {
            if (data == '1') data = '<i class="fas fa-envelope fa-lg bg-warning"></i>';
            else if (data == '0') data = '';
          }
          return data;
        }
      }
    ],
    rowCallback: function (row, data, index) {
      $(row).removeClass('status-open status-open-msg status-closed status-subject-closing status-pending');
      if (data['status'] === "ON PROCESS") {
        if (data['msg_cnt'] === '1' || data['msg_cnt'] === '0') $(row).addClass('status-open');
      } else if (data['status'] === 'PENDING') {
        $(row).addClass('status-pending');
        $(row).find('td:eq(11)').html(' ');
      } else if (data['status'] === 'CLOSED') {
        $(row).addClass('status-closed');
      } else if (data['status'] === 'SUBJECT FOR CLOSING') {
        $(row).addClass('status-subject-closing');
      }
    }
  });

  $('#transferred_data tbody').on('dblclick', 'tr', function () {
    var data = table_transfer.row($(this)).data();
    if (!data) return;
    open_ticket_modal(data);
  });
}


function open_ticket_modal(data) {
  var tid = data['ticket_no'];
  $('#status').html(window.originalStatusOptions);

  if (data['status'] === 'ON PROCESS') {
      $('#status').html(
          '<option value="ON PROCESS" style="color: #333;">ON PROCESS</option>' +
          '<option value="PENDING" style="color: #333;">PENDING</option>' +
          '<option value="SUBJECT FOR CLOSING" style="color: #333;">SUBJECT FOR CLOSING</option>'
      );
  } else if (data['status'] === 'PENDING') {
      $('#status').html(
          '<option value="PENDING" style="color: #333;">PENDING</option>' +
          '<option value="SUBJECT FOR CLOSING" style="color: #333;">SUBJECT FOR CLOSING</option>'
      );
  }

  $('#subjct').attr('readonly', true);
  $('#ticket_no').val(data['ticket_no']);
  $('#subjct').attr('readonly', true);
  $('#ticket_no').val(data['ticket_no']);
  $('#str_num').val(data['store']);
  $('#store').val(data['store']);
  $('#date_createdx').val(data['date_created']);
  $('#subjct').val(data['subject']);
  $('#concern').val(data['concern']);
  $('#via').val(data['via']);
  $('#status').val(data['status']);
  $('#it_num').val(data['itsup']);
  
  console.log("Ticket: " + data['ticket_no'] + " | is_transfer raw value: ", data['is_transfer']);
  
  var isTransferValue = parseInt($.trim(data['is_transfer'])) === 1;

  $('#userModal #is_transfer').prop('checked', isTransferValue).trigger('change');
  if (data['itsup'] && $('#itsup option[value="' + data['itsup'] + '"]').length === 0) {
    $('<option>', {
      value: data['itsup'],
      text: data['it_desc'] ? data['it_desc'] : 'Support ID ' + data['itsup'],
      class: 'temp-option'
    }).appendTo('#itsup');
  }
  $('#itsup').val(data['itsup']);

  $('#cat_num').val(data['cat_id']);
  $('#close_by').val(data['close_by']);
  $('#cl_desc').val(data['clusers']);

  if (data['cat_id'] && $('#cat option[value="' + data['cat_id'] + '"]').length === 0) {
    $('<option>', {
      value: data['cat_id'],
      text: data['category'] ? data['category'] : 'Category ID ' + data['cat_id'],
      class: 'temp-option'
    }).appendTo('#cat');
  }
  $('#cat').val(data['cat_id']);

  $('#sub_num').val(data['sub_id']);
  if (data['sub_id'] && $('#sub option[value="' + data['sub_id'] + '"]').length === 0) {
    $('<option>', {
      value: data['sub_id'],
      text: data['sub_category'] ? data['sub_category'] : 'Sub Category ID ' + data['sub_id'],
      class: 'temp-option'
    }).appendTo('#sub');
  }
  $('#sub').val(data['sub_id']);
  $('#isp_num').val(data['isp_id']);
  $('#isp').val(data['isp_id']);
  $('#refNo').val(data['refNo']);
  $('#date_refNo').val(data['date_refNo']);
  $('#file-input').val("");
  
  if(typeof admin_hideshowforms === "function") admin_hideshowforms();
  
  $('#date_closed').val(data['date_closed']);
  $('#remarks').val(data['remarks']);
  
  $('#remarks_view').show();
  $('.dv_msg').show();
  $('.container_remarks').show();
  $('#msg_thread').slideDown(300);
  $('#userModal').modal({ "show": true, "backdrop": 'static' });
  loadCommentThread(data['ticket_no']);
  
  if(typeof unilayout_netshowmodalform === "function") unilayout_netshowmodalform();

  $('#itsup').off('change').on('change', function () {
    var itfrstsup = $('#it_num').val();
    var itchange = this.value;
    if (itfrstsup != itchange) {
      $('#remarks').attr("placeholder", "Reason for re-assign/ Workoutput");
      $('#remarks').val("");
    } else {
      $('#remarks').val(data['remarks']);
    }
  });

  if ($('#status').val() == 'CLOSED') {
    $(':input[type="submit"]').prop('disabled', true);
    $('#date_createdx, #date_refNo, #date_closed, #remarks').attr('readonly', true);
    $('#store, #via, #status, #itsup, #cat, #sub, #isp, #is_transfer').prop("disabled", true);
  } else {
    $(':input[type="submit"]').prop('disabled', false);
    $('#date_createdx, #date_refNo, #date_closed, #subjct, #remarks').attr('readonly', false);
    $('#store, #via, #status, #itsup, #cat, #sub, #isp, #is_transfer').prop("disabled", false);
  }

  if (typeof getinfo === "function") getinfo(tid, 'remarks', user_id);
  if (typeof gtsub_id === "function") gtsub_id();

  $('.modal-title').text("Ticket Number: " + tid);
  $('#action').val("Save and Reply");
  $('#operation').val("Save and Reply");
  $('#userModal').modal({ "show": true, "backdrop": 'static' });

  $.ajax({
    type: 'POST',
    url: 'sesticket.php',
    data: { tktval: data['ticket_no'] },
    success: function (response) {
      $('#img').html(response);
    }
  });

  $('#msgbtn').show();
  $('#msg_thread').show();
  $('#addmsg').val("");
}


$(document).ready(function() {
  $('#store_graph_modal').modal('hide');

  if(typeof slct_isp === "function") slct_isp();
  if(typeof slct_sub === "function") slct_sub();
  if(typeof gtsub_id === "function") gtsub_id();
  if(typeof admin_hideshowforms === "function") admin_hideshowforms();

  const yr = $("#yearpicker").val();
  
  getdata(yr);
  getdata_transfer(yr);
  if (typeof get_card_data === "function") get_card_data(yr);
});
    
    /**
     * Get card data.
     */
    function get_card_data(y) {
      $.post('fetchdata/fetch_data.php', { yr: y, mode: 'yearch' }, function (data) {
        let card_data = jQuery.parseJSON(data);
        const a = card_data;
        $('#count_total').html(a[0].total_res);
        $('#count_open').html(a[0].open_res);
        $('#count_owfa').html(a[0].owfa_res);
        $('#count_closed').html(a[0].cls_res);
        $('#today_closed').html(a[0].t_res);
      });
    }

    $(function () {
      if($.fn.datetimepicker) {
          $('#datetimepicker1, #datetimepicker2, #datetimepicker3').datetimepicker();
      }
    });

    $("#yearpicker").on('change', function () {
      const yr = $("#yearpicker").val()
      getdata(yr);
      get_card_data(this.value);
      if(typeof _techgraph === "function") _techgraph(yr);
      if(typeof _overallpie === "function") _overallpie(yr);
      if(typeof _dbline === "function") _dbline(yr);
      if(typeof _catpie === "function") _catpie(yr);
      if(typeof _areagraph === "function") _areagraph(yr);
    });

    $('#cat').on('change', function () {
      var category_id = this.value;
      $.ajax({
        url: "get_subcat.php",
        type: "POST",
        data: {
          category_id: category_id
        },
        cache: false,
        success: function (dataResult) {
          $("#sub").html(dataResult);
        }
      });
    });

    $('#add_button').click(function () {
      $('#report_form').trigger('reset');
      $('#msg_thread, .dv_msg, .container_remarks').hide();
      $('#remarks_view').empty();
      $('#userModal').modal({ "show": true, "backdrop": 'static' });
      $('.modal-title').text("ADD REPORT");
      $('#subjct').attr('readonly', false);
      $('#action').val("Add");
      $('#operation').val("Add");
      $('#date_created').attr('readonly', false);
      $('#date_refNo').attr('readonly', false);
      $('#date_closed').attr('readonly', false);
      $('#store').prop("disabled", false);
      $('#via').prop("disabled", false);
      $('#status').prop("disabled", false);
      $('#itsup').prop("disabled", false);
      $('#cat').prop("disabled", false);
      $('#sub').prop("disabled", false);
      $('#isp').prop("disabled", false);
      $(':input[type="submit"]').prop('disabled', false);
      $('#remarks').attr('readonly', false);
      $('#is_transfer').prop('checked', false); 
      $('#is_transfer').prop('disabled', false); 
      $('#msgbtn').hide();
      $('#remarks_view').empty();
      $('.dv_msg').hide();
      $('.container_remarks').hide();

      $("#userModal").on('hidden.bs.modal', function () {
      });
      $('#userModal').modal({ backdrop: 'static', keyboard: false })
      $("#userModal").on('hidden.bs.modal', function () {
        return false;
      });
    });

    $(document).on("submit", "#report_form", function (e) {
      e.preventDefault();
      var TicketNumber = $("#ticket_no").val();
      var Store = $("#store").val();
      var DateCreated = $("#date_created").val();
      var Concern = $("#concern").val();
      var Status = $("#status").val();
      var Via = $("#via").val();
      var ItSupport = $("#itsup").val();
      var cat_id = $("#cat").val();
      var sub_id = $("#sub").val();
      var DateClosed = $("#date_closed").val();
      var CloseBy = $("#close_by").val();
      var remarks = $("#remarks").val();
      var addmsgx = $("#addmsg").val();
      var today = new Date();
      DateCreated = new Date(DateCreated);
      DateClosed = new Date(DateClosed);
      
      if (DateCreated > today) {
        alert("Invalid date");
        return false;
      }
      else if (Status == "ON PROCESS") {
        if (DateClosed < DateCreated) {
          alert("Date closed should be greater than date created!");
          return false;
        }
      }
      else if (DateClosed > today) {
        alert("Invalid Closed_Date");
        return false;
      }

      if (
        Store != "" &&
        DateCreated != "" &&
        Status != "" &&
        Via != "" &&
        ItSupport != "" &&
        cat_id != "" &&
        sub_id != ""
      ) {
        var disabledFields = $('#store, #via, #status, #itsup, #cat, #sub, #is_transfer');
        disabledFields.prop('disabled', false);
        var formData = new FormData(this);
        setTimeout(function() {
          if ($('#status').val() !== 'CLOSED') {
            disabledFields.prop('disabled', true);
          } else {
            $(':input[type="submit"]').prop('disabled', true);
            $('#date_createdx').attr('readonly', true);
            $('#date_refNo').attr('readonly', true);
            $('#date_closed').attr('readonly', true);
            $('#store, #via, #status, #itsup, #cat, #sub, #isp, #is_transfer').prop('disabled', true);
            $('#remarks').attr('readonly', true);
          }
        }, 50);

        $.ajax({
          url: "insert.php",
          method: "POST",
          data: formData,
          contentType: false,
          processData: false,
          success: function (data) {
            Swal.fire({
              icon: 'success',
              title: 'Your work has been saved',
              showConfirmButton: false,
              timer: 1500
            });
            
            if (TicketNumber) {
                $('#addmsg').val('');
                loadCommentThread(TicketNumber);
            } else {
                $("#userModal").modal("hide");
            }
            
            getdata(yr);
            get_card_data(yr);
          },
        });
      } else {
        alert("All Fields are Required");
      }
    });

  $(document).on('click', '#msgbtn', function () {
      $('.dv_msg').show();
      $('#remarks_view').show();

     if ($('#msgbtn').val() == 'show') {
        $('#action').val("Save and Reply");
        $('#operation').val("Save and Reply");
        $('#msgbtn').val("hide");
        $('#msg_thread').slideDown(400);
        $('.dv_msg, .container_remarks').slideDown(400);
        setTimeout(() => {
            const $container = $('.container_remarks');
            $container.animate({ scrollTop: 0 }, 400); 
            
        }, 450);
      }
      else if ($('#msgbtn').val() == 'hide') {
        $('#action').val("Save");
        $('#operation').val("Edit");
        $('#msgbtn').val("show");
        $('#msg_thread').slideUp(400);
      }
    });

    $('#btnClose').click(function () {
      if($('#report_form').length) $('#report_form')[0].reset();
      $('.dv_msg').hide();
      $('#remarks_view').hide();
      $('#tmpsubid').remove();
      $('#addmsg').val('');
    });

    $('#subpie_clsbtn').click(function (event) {
      event.preventDefault();
      $('#chartdiv9').empty();
    });

    $('#substr_clsbtn').click(function (event) {
      event.preventDefault();
      $('#substr_clsbtn').empty();
    });

    $('#action').click(function () {
      if (!$('#file-input').length) return;
      var files = $('#file-input')[0].files;
      var tktno = $('#ticket_no').val();
      var formData = new FormData();

      for (var i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
      }
      formData.append('ticket_no', tktno);

      if (files.length > 0) {
          $.ajax({
            type: "POST",
            url: "insertimg.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
            }
          });
      }
    });

    var uploadField = document.getElementById("file-input");
    if(uploadField) {
        uploadField.onchange = function () {
          for (var i = 0; i < $("#file-input").get(0).files.length; ++i) {
            var file1 = $("#file-input").get(0).files[i].name;

            if (file1) {
              var file_size = $("#file-input").get(0).files[i].size;
              if (file_size < 2097152) {
                var ext = file1.split('.').pop().toLowerCase();
                if ($.inArray(ext, ['jpg', 'jpeg', 'gif', 'png', 'txt', 'pdf', 'docx', 'doc', 'xlsx', 'xls']) === -1) {
                  alert("Invalid file extension");
                  this.value = "";
                  return false;
                }
              } else {
                alert("File must not exceed 2MB");
                this.value = "";
                return false;
              }
            }
          }
        };
    }

    let endDate = new Date();
    endDate.setDate(endDate.getDate() - 1); 
    let startDate = new Date(endDate); 

    $('#frompolDate').val(startDate.toISOString().split('T')[0]);
    $('#topolDate').val(endDate.toISOString().split('T')[0]);

    if(typeof _polledraph === "function") _polledraph($('#frompolDate').val(), $('#topolDate').val());

    $('#frompolDate, #topolDate').change(function () {
      if(typeof _polledraph === "function") _polledraph($('#frompolDate').val(), $('#topolDate').val());
    });

    $('#userModal').on('shown.bs.modal', function () {
        const ticketNo = $('#ticket_no').val();
        if (ticketNo) {
            $('.dv_msg').show();
            $('.container_remarks').show();
            loadCommentThread(ticketNo);
        }
    });

    $(document).on('hidden.bs.modal', '#userModal', function () {
      $('.temp-option').remove();
    });

  });
</script>