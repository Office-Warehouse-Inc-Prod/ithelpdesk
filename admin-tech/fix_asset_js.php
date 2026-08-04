
<script type="text/javascript">
$(document).ready(function() {
  window.user_id = <?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
  var reptable;
  
  $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
      if (settings.nTable.id !== 'fa_reports_table') return true;
      
      let filterYear = $('#filter_year').val();
      let filterMonth = $('#filter_month').val();
      let dateStr = data[3] || ''; 
      
      if (filterYear && dateStr.substring(0, 4) !== filterYear) {
          return false;
      }
      if (filterMonth && dateStr.substring(5, 7) !== filterMonth) {
          return false;
      }
      return true;
  });

  $('.filter-trigger').change(function() {
      if (reptable) {
          reptable.draw(); 
          if($(this).attr('id') === 'filter_status') {
              applyStatusFilter();
          }
      }
      refreshData();
  });

  function getUrlParam(param) {
      var urlParams = new URLSearchParams(window.location.search);
      return urlParams.get(param);
  }
  var targetTicket = getUrlParam('ticket_no');

  function refreshData() {
      let month = $('#filter_month').val();
      let year = $('#filter_year').val();
      let status = $('#filter_status').val();

      $.post('fetchdata/fetch_data.php', {
          mode: 'fa_reports_tbl',
          month: month,
          year: year,
          status: status 
      }, function(response) {
          if ($.fn.DataTable.isDataTable('#fa_reports_table')) {
              reptable.clear().rows.add(response.table_data || []).draw(false);
              reptable.draw(); 
              applyStatusFilter(); 
          } else {
              admin_datatable(response);
          }
          
          if(response.metrics) {
              updateMetricsUI(response.metrics);
          }
      }, 'json');
  }

  refreshData();
  setInterval(function () {
      refreshData();
  }, 60000);

  function getFAData(month = '', year = '') {
      $.post('fetchdata/fetch_data.php', {
          mode: 'fa_reports_tbl', 
          month: month, 
          year: year
      }, function(response) {
          admin_datatable(response);
          if(response.metrics) {
              updateMetricsUI(response.metrics);
          }
      }, 'json');
  }

  function updateMetricsUI(metrics) {
      let html = '<div class="row d-flex justify-content-start w-100">';
      
      metrics.forEach(function(item) {
          let statName = item.status ? item.status.toUpperCase() : 'UNKNOWN';
           html += `
    <div class="col-md-2 col-sm-4 mb-2">
        <div class="card p-3 text-center shadow-sm" style="border-radius: 12px; border: 1px solid #e9ecef; background-color: white;">
            <h6 class="mb-1 text-truncate" style="color: #54699e; font-weight: 800; font-size: 0.75rem;">${statName}</h6>
            <h3 class="mb-0 count-${(item.status || 'unknown').toLowerCase()}" style="color: #E1AD01; font-weight: 900;">${item.count || 0}</h3>
        </div>
    </div>`;
      });
      html += '</div>';
      $('#metrics_summary_div').html(html);
  }

  function applyStatusFilter() {
      if (reptable) {
          let statusVal = $('#filter_status').val();
          reptable.column(10).search(statusVal ? '^' + statusVal + '$' : '', true, false).draw();
      }
  }

  function admin_datatable(response){
    const dataset = response.table_data || [];
    
    reptable = $("#fa_reports_table").DataTable({
      "dom": '<"pull-left"lf><"pull-right">tip',
      stateSave: true,
      "bDestroy": true,
      "responsive": true, 
      "lengthChange": false, 
      "autoWidth": false,
      language: {
        emptyTable: "No fixed asset reports",
        search: "_INPUT_",
        searchPlaceholder: "Search..."
      },
      pageLength: 5,
      data: dataset,
      "order": [[ 0, "Desc" ]],
      columns: [
        {title:"Ticket No", data:"ticket_no","defaultContent": ""},
        {title:"Dept/Branch", data:"str_name","defaultContent": ""},
        {title:"Employee", data:"full_name","defaultContent": ""},
        {title:"Ticket Date", data:"ticket_created","defaultContent": ""},
        {title:"Item Code", data:"item_code","defaultContent": ""},
        {title:"Description", data:"description","defaultContent": ""},
        {title:"Serial", data:"serial_number","defaultContent": ""},
        {title:"Received by", data:"it_desc","defaultContent": ""},
        {title:"Noted by", data:"noted_by_desc","defaultContent": ""},
        {title:"Date Received", data:"date_received","defaultContent": ""},
        {title:"Status", data:"status","defaultContent": ""},
        {
          title: "Action", 
          data: null, 
          render: function(data, type, row){
            return `
            <div style="display: flex; gap: 5px;">
            <button type='button' class='btn btn-primary' onclick='openViewModal(this)'><i class='fas fa-eye'> </i> View</button>
                  </div>
                 `;
          }
        }
      ],
      rowCallback: function(row, data, index){
        if(data['msg_cnt'] == '1'){
          $(row).find('td').css("font-weight", "bold");
        }
      },
      initComplete: function() {
        applyStatusFilter();

        if (targetTicket) {
          setTimeout(function() {
            var foundRow = null;
            reptable.rows().every(function () {
              var rowData = this.data();
              if (rowData && rowData.ticket_no == targetTicket) {
                foundRow = this.node();
              }
            });

            if (foundRow) {
              $(foundRow).find('.btn-primary').trigger('click');
              $('html, body').animate({
                scrollTop: $(foundRow).offset().top - 100
              }, 800, function() {
                $(foundRow).css('transition', 'background-color 0.5s ease');
                $(foundRow).css('background-color', '#ffff99'); 
                setTimeout(function() {
                  $(foundRow).css('background-color', ''); 
                }, 1200);
              });
            }
          }, 600);
          targetTicket = null;
        }
      }
    });
  }

  $(document).on('submit', '#fa_form', function(event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var formData = new FormData(this);

    $.ajax({
      url: "insert.php",
      method: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'json',
      cache: false,
      success: function(response) {
        if (typeof response !== 'object') {
          try { response = JSON.parse(response); } 
          catch (e) { response = { status: 'error', message: String(response) }; }
        }

        if (response.status === 'success' || response.status === true) {
          Swal.fire({
            icon: 'success',
            title: response.message || 'Saved successfully',
            showConfirmButton: false,
            timer: 1500
          }).then(function() {
            $('#fa_form')[0].reset();
            $('#fa_reports_Modal').modal('hide');
            getFAData($('#filter_month').val(), $('#filter_year').val());
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Save failed',
            text: response.message || 'Please try again.'
          });
        }
      },
      error: function(xhr, status, error) {
        var message = 'Please try again.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          message = xhr.responseJSON.message;
        } else if (xhr.responseText) {
          message = xhr.responseText.trim();
        }
        Swal.fire({
          icon: 'error',
          title: 'Save failed',
          text: message
        });
      }
    });
  });

});

let inactivityTime = function(){
  let time;
  window.onload = resetTimer;
  document.onmousemove = resetTimer;
  document.onkeypress = resetTimer;
  document.onscroll = resetTimer;
  document.onclick = resetTimer;

  function logout(){
    window.location.href = 'adminpanel.php';
  }
  function resetTimer(){
    clearTimeout(time);
    time = setTimeout(logout, 180000)
  }
};
inactivityTime();

function handleDropdownChange(selectElement) {
  if (selectElement.value === "") {
    selectElement.classList.add("placeholder-active");
    selectElement.classList.remove("has-value");
  } else {
    selectElement.classList.remove("placeholder-active");
    selectElement.classList.add("has-value");
  }
}

function openViewModal(btn) {
    var tr = $(btn).closest('tr');
    var data = $('#fa_reports_table').DataTable().row(tr).data();
    if (!data) return;

    $('#ticket_no').val(data.ticket_no || '');
    $('#str_name').val(data.str_name || '');
    $('#full_name').val(data.full_name || '');
    $('#ticket_created').val(data.ticket_created || '');
    $('#item_code').val(data.item_code || '');
    $('#description').val(data.description || '');
    $('#serial_number').val(data.serial_number || '');
    $('#asset_tag_number').val(data.asset_tag_number || '');
    $('#purpose_of_request').val(data.purpose_of_request || '');
    $('#technical_workoutput').val(data.technical_workoutput || '');
    $('#revised_request').val(data.revised_request || '');
    $('#it_desc').val(data.it_desc || '');
    $('#noted_by_desc').val(data.noted_by_desc || '');
    $('#date_received').val(data.date_received || '');
    $('#status').val(data.status || '');
    
    $('#operation').val("update_request");
    
    $('#fa_reports_Modal').modal('show');
    if (typeof getinfo === "function") getinfo(data.ticket_no, 'remarks', window.user_id || '');
    loadRemarks(data.ticket_no);
    loadTimeline(data.ticket_no, data);
}

function loadRemarks(ticket_no) {
      $('#remarks_thread_container').html('<div class="text-center mt-4"><i class="fas fa-spinner fa-spin fa-2x" style="color:#cbd5e1;"></i></div>');
      
      $.ajax({
          url: window.location.href,
          type: 'POST',
          data: { mode: 'fetch_remarks', ticket_no: ticket_no },
          dataType: 'json',
          success: function(response) {
              let html = '';
              if (Array.isArray(response) && response.length > 0) {
                  response.forEach(function(rmk) {
                    
                      let userName = rmk.it_desc ? rmk.it_desc : 'System';
                      html += `
                          <div class="chat-message">
                                <span style="font-size: 11px; color: #64748b; margin-bottom: 4px;"><strong>${rmk.user_fullname || 'System'}</strong> • ${rmk.date_remarks}</span>
                              <div class="chat-bubble">${rmk.remarks_note}</div>
                          </div>
                      `;
                  });
              } else {
                  html = `<div class="text-center mt-4 text-muted" style="font-size: 12px; font-style: italic;">No remarks found. Start the conversation!</div>`;
              }
              
              $('#remarks_thread_container').html(html);
              var chatDiv = document.getElementById("remarks_thread_container");
              if (chatDiv) chatDiv.scrollTop = chatDiv.scrollHeight;
          },
          error: function(xhr) {
              console.error("Remarks Fetch Error:", xhr.responseText);
              $('#remarks_thread_container').html('<div class="text-danger text-center mt-3" style="font-size: 12px;">Failed to fetch remarks.</div>');
          }
      });
  }

  $('#btn_send_remark').off('click').on('click', function() {
      var remarks = $('#new_remark_input').val();
      var ticket_no = $('#ticket_no').val();

      if (!remarks.trim()) {
          Swal.fire('Warning', 'Please type a remark first.', 'warning');
          return;
      }

      var $btn = $(this);
      $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');

      $.ajax({
          url: window.location.href,
          type: 'POST',
          data: { 
              mode: 'add_remarks_only',
              ticket_no: ticket_no, 
              remarks_adtech: remarks 
          },
          dataType: 'json',
          success: function(response) {
              if (response.status === 'success') {
                  $('#new_remark_input').val('');
                  loadRemarks(ticket_no); 
                  Swal.fire({ icon: 'success', title: 'Sent!', timer: 1000, showConfirmButton: false });
              } else {
                  Swal.fire('Error', response.message, 'error');
              }
          },
          error: function(xhr) {
              Swal.fire('Error', 'Communication failed.', 'error');
              console.error(xhr.responseText);
          },
          complete: function() {
              $btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Send Remark');
          }
      });
  });

function loadTimeline(ticket_no, rowData) {
    var target = $('#trackingMap');
    target.html('<p class="text-muted" style="font-size: 12px; margin-top: 10px;">Loading timeline...</p>');

    $.ajax({
        url: 'get_first_comment.php',
        type: 'POST',
        dataType: 'json',
        data: { ticket_no: ticket_no },
        success: function(response) {
            const statusLevels = {
                'submitted': 1, 'noted': 2, 'validated': 3, 
                'verified': 4, 'printed': 5, 'approved': 6, 'completed': 7
            };

            let dbStatus = (response.status || "").toLowerCase().trim();
            let currentLevel = statusLevels[dbStatus] || 0; 

           const trackSteps = [
                { desc: "Request submitted by store/user", date: response.date_created, reqLevel: 0 },
                { desc: "Under assigned support evaluation", date: response.date_created, reqLevel: 0 },
                { desc: "Submitted to technical/dept head", date: response.date_submitted, reqLevel: 1 },
                { desc: "Approved and noted by technical/dept head", date: response.date_noted, reqLevel: 2 },
                { desc: "For admin support validation", date: null, reqLevel: 2 }, 
                { desc: "Validated by admin support", date: response.date_validated, reqLevel: 3 },
                { desc: "For administrative verification", date: null, reqLevel: 3 }, 
                { desc: "Verified by the administrator", date: response.date_verified, reqLevel: 4 },
                { desc: "For printing request form", date: null, reqLevel: 4 }, 
                { desc: "Printed", date: response.date_printed, reqLevel: 5 },
                { desc: "For General Manager Approval", date: null, reqLevel: 5 }, 
                { desc: "Approved by General Manager", date: response.date_approved, reqLevel: 6 },
                { desc: "Ready for asset replacement", date: null, reqLevel: 6 }, 
                { desc: "Asset replaced / Completed", date: response.date_completed, reqLevel: 7 }
            ];

            let timelineHtml = '';
            trackSteps.forEach((step) => {
                let statusClass = (currentLevel >= step.reqLevel) ? "completed" : "";
                let dateDisplay = step.date ? `<div class="timeline-date">${step.date}</div>` : '';

                timelineHtml += `
                    <li class="timeline-item ${statusClass}">
                        <div class="timeline-icon"></div>
                        <div class="timeline-desc">${step.desc}</div>
                        ${dateDisplay}
                    </li>
                `;
            });

            target.html(timelineHtml);
        },
        error: function() {
            target.html('<li class="text-danger">Failed to load timeline.</li>');
        }
    });
}
</script>

<script>
document.getElementById('status').addEventListener('change', function() {
    const status = this.value;
    
    document.querySelectorAll('.date-input-container').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.status-date-input').forEach(el => {
        el.disabled = true;
        el.value = ''; 
    });

    if (status) {
        let targetInputId = 'date_' + status.toLowerCase();
        let targetGroup = document.getElementById('date' + status.charAt(0).toUpperCase() + status.slice(1).toLowerCase() + 'Group');
        let targetInput = document.getElementById(targetInputId);

        if (targetInput && targetGroup) {
            targetGroup.style.display = 'block';
            targetInput.disabled = false;

            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            
            targetInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
        }
    }
});

document.getElementById('pdfForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const loadingBar = document.getElementById('loadingBar');
    const submitBtn = document.getElementById('btnSubmit');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Generating...';

    setTimeout(() => {
        loadingBar.style.width = '100%';
    }, 50);

    setTimeout(() => {
        form.submit();
    }, 1050); 
});

$('#dataModal').on('hidden.bs.modal', function () {
    document.getElementById('loadingBar').style.width = '0%';
    const submitBtn = document.getElementById('btnSubmit');
    submitBtn.disabled = false;
    submitBtn.innerHTML = 'Generate PDF';
});

$(document).on('click', '.print-btn', function() {
    let ticket_no = $(this).data('id');
    
    $('#pdfForm')[0].reset();
    
    $('#modal_ticket_no').val(ticket_no);
    
    $('#dataModal').modal('show');
});
</script>