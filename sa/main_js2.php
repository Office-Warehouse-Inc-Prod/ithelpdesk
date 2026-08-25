<script type="text/javascript">
$(document).ready(function () {

$("#deptsel").on("change", function(){
    $('#subject').val('');
    $('#subcategory_container').fadeOut();
    $('#subcategory').val('');
        let val = $(this).val();
        
            $("#subject").select2({
      width: '100%',
      minimumResultsForSearch: Infinity,
      ajax: {
        url: "select.php",
        type: "get",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            type: 'category',
            val : val
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      }
    });

        if(val == "4"){ // MERCHANDISING
        $("#helpdesk_row").fadeOut();
        $("#merchDrCard").fadeIn();
        }
    });
  
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

  $('.dashcard-clickable').on('click', function () {
    const filterValue = $(this).data('filter');
    if ($.fn.DataTable.isDataTable('#report_data')) {
      const table = $('#report_data').DataTable();
      table.search(filterValue).draw();
    }
    $('html, body').animate({
      scrollTop: $("#report_data").offset().top - 100
    }, 600);
    $(this).fadeOut(100).fadeIn(100);
  });

  $('#card_totalval, #card_openval, #card_openwfaval, #card_closedval').click(function (e) {
    e.preventDefault();
    if (typeof table !== 'undefined') {
      var val = $(this).attr("value");
      table.columns(7).search(val).draw();
    }
  });

  $('.clcktxt').click(function () {
    if (typeof table !== 'undefined') {
      var val = $(this).attr("value");
      table.columns(7).search(val).draw();
    }
    $('#network_tb').slideToggle();
    $('html, body').animate({ scrollTop: 1600 }, 1000);
  });



function checkFixAssetCondition() {
    var dept = $('#create_deptsel').val(); 
    var subj = ($('#subject option:selected').text() || '').toUpperCase();
    var subcatText = ($('#subcategory option:selected').text() || '').toUpperCase();
    
    var isFixAsset = (dept == '2' && (subj.includes('FIX ASSET') || subj.includes('FIXED ASSET')));
    var isReplacement = subcatText.includes('REPLACEMENT');
    var isNew = subcatText.includes('NEW');
    var isTransfer = subcatText.includes('TRANSFER');

    if (isReplacement) {
        $('#create_file-input, #file-input').prop('required', true);
        $('#create_file-input').prev('label').html('<i class="fas fa-paperclip mr-1"></i> Attach Files <span class="text-danger">*</span>');
        $('#file-input').parent().prev('label').html('Attached File: <span class="text-danger">*</span>');
    } else {
        $('#create_file-input, #file-input').prop('required', false);
        $('#create_file-input').prev('label').html('<i class="fas fa-paperclip mr-1"></i> Attach Files (Optional)');
        $('#file-input').parent().prev('label').html('Attached File:');
    }

    if (isFixAsset) {
        $('#is_fix_asset').val('1');
        $('#inline_fixed_asset_fields').slideDown();
        $('#concern').prop('required', true);

        if (isReplacement) {
            $('#inline_fa_description_container').show();
            $('#inline_fa_serial_container').show();
        } else if (isNew) {
            $('#inline_fa_description_container').show();
            $('#inline_fa_serial_container').hide();
        } else if (isTransfer) {
            $('#inline_fa_description_container').hide();
            $('#inline_fa_serial_container').hide();
        } else {
            $('#inline_fa_description_container').hide();
            $('#inline_fa_serial_container').hide();
        }
    } else {
        $('#is_fix_asset').val('0');
        $('#inline_fixed_asset_fields').hide();
        $('#concern').prop('required', true);
    }

    validateSubmitButton();
}

function validateSubmitButton() {
    var isFixAsset = $('#is_fix_asset').val() == '1';
    var concernLength = $('#concern').val().trim().length;
    var isReplacement = ($('#subcategory option:selected').text() || '').toUpperCase().includes('REPLACEMENT');
    var isValid = true;

    if (isFixAsset) {
        if ($('#inline_fa_description_container').is(':visible')) {
            var descSel = $('#inline_fa_description_sel').val();
            var descTxt = $('#inline_fa_description_txt').val();
            if (!descSel || (descSel === 'OTHER' && !descTxt)) {
                isValid = false; 
            }
        }
    }
    
    if (concernLength < 10) {
        isValid = false;
    }

    if (isReplacement) {
        var fileInput = $('#create_file-input')[0];
        if (fileInput && fileInput.files.length === 0) {
            isValid = false;
        }
    }

    $('#action').prop('disabled', !isValid);
}

function valtxt() {
    if($('#subject').val() == null || $('#subject').val().trim() == ""){
        $('#subject').addClass('border-danger');
        setTimeout(() => { $('#subject').removeClass('border-danger'); }, 5000);
        return false;
    } 
    
    let isFixAsset = $('#is_fix_asset').val() == '1';
    
    if ($('#concern').val().trim() == ""){
        $('#concern').addClass('border-danger');
        setTimeout(() => { $('#concern').removeClass('border-danger'); }, 5000);
        return false;
    }

    if (isFixAsset) {
        if ($('#inline_fa_description_container').is(':visible')) {
            var descSel = $('#inline_fa_description_sel').val();
            var descTxt = $('#inline_fa_description_txt').val();
            if (!descSel || (descSel === 'OTHER' && !descTxt)) {
                alert("Please complete the Fixed Asset Description.");
                return false;
            }
        }
    }

    return true;
}
$('#inline_fa_description_sel').on('change', function() {
    if($(this).val() === 'OTHER') {
        $('#inline_fa_description_txt').show();
    } else {
        $('#inline_fa_description_txt').hide().val('');
    }
    validateSubmitButton();
});
$('#create_deptsel, #subject, #subcategory').on('change', function() {
    checkFixAssetCondition();
});
const validationLength = 1000;
const concern = document.getElementById('concern');
const action = document.getElementById('action');

concern.addEventListener('input', function() {
  const inputValue = concern.value;
  const inputLength = inputValue.length;

  if (inputLength > validationLength) {
    concern.value = inputValue.substr(0, validationLength);
  }
  
  validateSubmitButton();
});

  function resetCreateTicketForm() {
    const $form = $('#create_report_form');
    
    $form[0].reset(); 
    $('#concern').val('');
    $('#create_store').val('201');
    $('#create_deptsel').val('');
    $('#subject').html('<option value="" selected disabled>---Select Category---</option>');
    $('#subcategory').html('<option value="" selected disabled>---Select Subcategory---</option>');
    $('#subcategory_container, #inline_fixed_asset_fields').hide();
    $('#create_ticket_lbl').text('---');
    $('#create_ticket_no').val('');
    $form.find('input[type="file"]').val(''); 
    $('#create_file-input, #file-input').prop('required', false);
    $('#create_file-input').prev('label').html('<i class="fas fa-paperclip mr-1"></i> Attach Files (Optional)');
    $('#file-input').parent().prev('label').html('Attached File:');
    
    $('#is_fix_asset').val('0');
    $('#inline_fa_description_sel').val('');
    $('#inline_fa_description_txt').hide().val('');
    $('#inline_fa_serial_number').val('');
}
  
  resetCreateTicketForm();

  $('#create_report_form').on('submit', function (e) {
    e.preventDefault();
    var form = this;
    var formData = new FormData(form);
    var $submitBtn = $(form).find('button[type="submit"]');

    $.ajax({
      url: "api_create_dept_report.php",
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      beforeSend: function () {
        $submitBtn.prop('disabled', true);
        $.LoadingOverlay("show", { background: "rgba(0, 0, 0, 0.45)" });
      },
      success: function (response) {
        $.LoadingOverlay("hide");
        $submitBtn.prop('disabled', false);

        let res = typeof response === 'string' ? JSON.parse(response) : response;

        if (res.Response || res.status === "success") {
          
          var files = $(form).find('input[type="file"]')[0].files;
          if (files && files.length > 0) {
            var fileData = new FormData();
            for (var i = 0; i < files.length; i++) {
              fileData.append('files[]', files[i]);
            }
            fileData.append('ticket_no', res.ticket_no);
            $.ajax({
              type: "POST",
              url: "insertimg.php",
              data: fileData,
              processData: false,
              contentType: false
            });
          }

          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Report successfully submitted to OWI HELPDESK.',
            timer: 2000,
            showConfirmButton: false
          }).then(function () {
            resetCreateTicketForm();
            if (typeof get_dept_data === 'function') get_dept_data();
            const currentYr = $("#yearpicker").val() || new Date().getFullYear();
            if (typeof getdata === 'function') getdata(currentYr);
            if (typeof get_card_data === 'function') get_card_data(currentYr);
          });

        } else {
          Swal.fire({ icon: 'error', title: 'Submission Failed', html: res.m || res.message });
        }
      },
      error: function () {
        $.LoadingOverlay("hide");
        $submitBtn.prop('disabled', false);
        Swal.fire({ icon: 'error', title: 'Error', text: 'An unexpected error occurred while saving the report.' });
      }
    });
  });


  $('#create_deptsel').on("change", function () {
    const deptVal = $(this).val();
    const $subject = $('#subject');
    const $subCategory = $('#subcategory');
    
    $subject.html('<option value="" selected disabled>Loading categories...</option>');
    $subCategory.html('<option value="" selected disabled>---Select Subcategory---</option>');
    $('#subcategory_container').hide();
    
    if (typeof checkFixAssetCondition === "function") {
      checkFixAssetCondition();
    }

    if (!deptVal) return; 

    $.ajax({
      url: "select.php",
      type: "GET",
      dataType: 'json',
      cache: true,
      data: { type: 'category_id', val: deptVal },
      success: function (response) {
        $subject.empty().append('<option value="" selected disabled>---Select Category---</option>');
        if (Array.isArray(response) && response.length > 0) {
          response.forEach(function (item) {
            if (item?.id !== undefined && item?.text !== undefined) {
              $subject.append($('<option>', { value: item.id, text: item.text }));
            }
          });
        } else {
          $subject.append('<option value="" disabled>No categories available</option>');
        }
      },
      error: function () {
        $subject.html('<option value="" selected disabled>Error loading categories</option>');
      }
    });

    $.ajax({
      url: '../users/fetch.php',
      method: 'POST',
      dataType: 'json',
      data: { operation: 'search_tkt', iN: deptVal },
      success: function (data) {
        if (data && data[0]) {
          const next_tktno = data[0].ticket_no;
          const deptabr = data[0].dept;
          const ticketNo = deptabr + '' + next_tktno;
          
          $('#create_ticket_no').val(ticketNo);
          $('#create_ticket_lbl').text(ticketNo);
        } else {
          $('#create_ticket_lbl').text('Ticket generation failed');
        }
      },
      error: function () {
        $('#create_ticket_lbl').text('Ticket generation failed');
      }
    });
  });

  $("#subject, #cat").on("change", function () {
    const isCreate = $(this).attr('id') === 'subject';
    const categoryId = $(this).val();
    
    const $targetSub = isCreate ? $('#subcategory') : $('#sub');
    const $targetGroup = isCreate ? $('#subcategory_container') : null;

    if (!categoryId) {
      if ($targetGroup) $targetGroup.hide();
      return;
    }

    $targetSub.html('<option value="" selected disabled>Loading...</option>');

    $.ajax({
      url: "get_subcat.php",
      type: "POST",
      data: { category_id: categoryId },
      cache: false,
      success: function (dataResult) {
        $targetSub.html(dataResult);
        if ($targetGroup) $targetGroup.show();
      },
      error: function () {
        $targetSub.html('<option value="">Error loading subcategories</option>');
        if ($targetGroup) $targetGroup.show();
      }
    });
  });

$('#create_file-input').on('change', function () {
    for (var i = 0; i < this.files.length; ++i) {
      var file = this.files[i];
      if (file.size > 2097152) { 
        Swal.fire({ icon: 'error', title: 'File Too Large', text: 'File "' + file.name + '" must not exceed 2MB.' });
        this.value = "";
        validateSubmitButton(); // Re-validate if file is rejected
        return false;
      }
      var ext = file.name.split('.').pop().toLowerCase();
      var validExtensions = ['jpg', 'jpeg', 'gif', 'png', 'txt', 'pdf', 'docx', 'doc', 'xlsx', 'xls'];
      if ($.inArray(ext, validExtensions) === -1) {
        Swal.fire({ icon: 'error', title: 'Invalid File Type', text: 'File "' + file.name + '" has an invalid extension.' });
        this.value = "";
        validateSubmitButton(); // Re-validate if file is rejected
        return false;
      }
    }
    
    validateSubmitButton(); // Validate button when a valid file is successfully attached
});

  var reptable;
  var user_id = "<?= $_SESSION['user_id'] ?? ''; ?>";

  function get_dept_data() {
    $.post('fetchdata/fetch_data.php', { mode: 'dept_tbl' }, function (data) {
      dept_datatable(data);
    }, 'json');
  }
  get_dept_data();
  setInterval(get_dept_data, 3000);

  function dept_datatable(t) {
    const dataset = t.deptdata;
    if ($.fn.DataTable.isDataTable('#create_dept_table')) {
      var table = $('#create_dept_table').DataTable();
      table.clear().rows.add(dataset).draw(false);
      return;
    }
    
    reptable = $("#create_dept_table").DataTable({
      "dom": '<"pull-left"lf><"pull-right">tip',
      stateSave: true,
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      language: { emptyTable: "No unassigned reports", search: "_INPUT_", searchPlaceholder: "Search..." },
      pageLength: 5,
      data: dataset,
      "order": [[0, "Desc"]],
      columns: [
        { title: "Ticket No", data: "ticket_no", "defaultContent": "" },
        { title: "SUBJECT", data: "subject", "defaultContent": "" },
        { title: "Concern", data: "concern", "defaultContent": "" },
        { title: "Date Created", data: "date_created", "defaultContent": "" },
        { title: "Status", data: "status", "defaultContent": "" },
        { title: "Update", data: null, "defaultContent": "<Button class='btn btn-danger' name='update'><i class='fas fa-edit'></i></Button>" }
      ],
      rowCallback: function (row, data, index) {
        if (data['msg_cnt'] == '1') {
          $(row).find('td').css("font-weight", "bold");
        }
      }
    });

    $('#create_dept_table tbody').off('click', 'button').on('click', 'button', function () {
      var data = reptable.row($(this).parents('tr')).data();
      if (!data) return;

      $('#ticket_no').val(data['ticket_no']);
      $('#store').val(data['store']);
      $('#via').val(data['via'] || 'WEB');
      $('#itsup').val(data['itsup']);
      $('#it_num').val(data['itsup']);
      $('#cat').val(data['cat_id']);
      $('#sub').val(data['sub_id']);
      $('#sub_num').val(data['sub_id']);
      $('#date_created').val(data['date_created']);
      $('#date_createdx').val(data['date_created']);
      $('#concern').val(data['concern']);

      if (data['str_name']) $('#str_name').val(data['str_name']);
      if (data['deptsel']) $('#deptsel').val(data['deptsel']);
      $('#subjct').val(data['subject']);
      if (data['it_desc']) $('#it_desc').val(data['it_desc']);
      if (data['cat_desc']) $('#cat_desc').val(data['cat_desc']);
      if (data['sub_cat']) $('#sub_cat').val(data['sub_cat']);
      if (data['status']) $('#status').val(data['status']);

      $('#remarks').val(data['concern']);
      $('#action').val("Update");
      $('#operation').val("Save and Reply");

      var tid = data['ticket_no'];
      $('#userModal_header').text("Ticket Number: " + tid);
      $('#userModal').modal('show');

      displayAttachmentsFromData(data, tid);
      
      if(typeof getinfo === 'function') getinfo(tid, 'remarks', user_id);
      loadCommentThread(tid);
    });
  }

  var table;

  function getdata(yr) {
    $.post('fetchdata/fetch_data.php', { yr: yr, mode: 'dtb' }, function (data) {
      admin_datatable(data);
    }, 'json');
  }

  function admin_datatable(t) {
    const dataset = t.rptdata;
    if ($.fn.DataTable.isDataTable('#report_data')) {
      table = $('#report_data').DataTable();
      table.clear().rows.add(dataset).draw(false);
      return;
    }
    table = $("#report_data").DataTable({
      "dom": 'B<"pull-left"lf><"pull-right">tip',
      "buttons": [
        {
          text: '<i class="fas fa-plus"></i>',
          attr: { title: 'Add Report', id: 'add_button', class: 'second btn btn-danger' },
          action: function (e, dt, node, config) {
            $('#report_form').trigger('reset');
            $('#remarks_view').empty();
            $('.dv_msg, .container_remarks, #msg_thread').hide();
            
            $('#userModal').modal({ "show": true, "backdrop": 'static' });
            $('.modal-title').text("ADD REPORT");
            $('#action').val("Add");
            $('#operation').val("Add");
            $(':input[type="submit"]').prop('disabled', false);
            $('#date_created, #date_refNo, #date_closed, #remarks, #subjct').attr('readonly', false);
            $('#store, #via, #status, #itsup, #cat, #sub, #isp').prop("disabled", false);
            $('#img').empty();
            $('#addmsg').removeAttr('required');

            if (typeof admin_hideshowforms === "function") admin_hideshowforms();
            if (typeof unilayout_netshowmodalform === "function") unilayout_netshowmodalform();
          }
        },
        { extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i>', attr: { title: 'Export to Excel', class: 'btn btn-success' } }
      ],
      "pagingType": "full_numbers",
      "bDestroy": true,
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "language": { "search": "_INPUT_", "searchPlaceholder": "Search..." },
      "initComplete": function (settings, json) {
        var $searchWrapper = $('#report_data_filter');
        var $nativeSearchInput = $searchWrapper.find('input');

        $nativeSearchInput.attr('id', 'report_data_filter_disabled').attr('placeholder', 'Auto Fill Status').prop('disabled', true).css('margin-right', '10px');
        var $activeSearch = $('<input type="search" class="form-control form-control-sm">')
          .attr('id', 'report_data_free_search').attr('placeholder', 'Type to Search').css({ 'display': 'inline-block', 'width': 'auto', 'margin-left': '10px' });

        $searchWrapper.append($activeSearch);

        $.fn.dataTable.ext.search.push(function (settings, searchData, index, rowData, counter) {
          var freeSearchVal = $('#report_data_free_search').val();
          if (!freeSearchVal) return true;
          var searchRegex = new RegExp(freeSearchVal, 'i');
          for (var i = 0; i < searchData.length; i++) {
            if (searchRegex.test(searchData[i])) return true;
          }
          return false;
        });

        $activeSearch.on('input', function () { table.draw(); });
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
        { title: "WORKOUTPUT", data: "remarks", "defaultContent": "", }
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

    $('#report_data tbody').off('dblclick', 'tr').on('dblclick', 'tr', function () {
      var data = table.row($(this)).data();
      if (!data) return;

      $('#subjct').attr('readonly', true);
      var tid = data['ticket_no'];
      $('#ticket_no').val(data['ticket_no']);
      $('#str_num').val(data['store']);
      $('#store').val(data['store']);
      $('#date_createdx').val(data['date_created']);
      $('#subjct').val(data['subject']);
      $('#concern').val(data['concern']);
      $('#via').val(data['via']);
      $('#status').val(data['status']);
      $('#it_num').val(data['itsup']);

      if (data['itsup'] && $('#itsup option[value="' + data['itsup'] + '"]').length === 0) {
        $('<option>', { value: data['itsup'], text: data['it_desc'] ? data['it_desc'] : 'Support ID ' + data['itsup'], class: 'temp-option' }).appendTo('#itsup');
      }
      $('#itsup').val(data['itsup']);

      $('#cat_num').val(data['cat_id']);
      $('#close_by').val(data['close_by']);
      $('#cl_desc').val(data['clusers']);

      if (data['cat_id'] && $('#cat option[value="' + data['cat_id'] + '"]').length === 0) {
        $('<option>', { value: data['cat_id'], text: data['category'] ? data['category'] : 'Category ID ' + data['cat_id'], class: 'temp-option' }).appendTo('#cat');
      }
      $('#cat').val(data['cat_id']);

      $('#sub_num').val(data['sub_id']);
      if (data['sub_id'] && $('#sub option[value="' + data['sub_id'] + '"]').length === 0) {
        $('<option>', { value: data['sub_id'], text: data['sub_category'] ? data['sub_category'] : 'Sub Category ID ' + data['sub_id'], class: 'temp-option' }).appendTo('#sub');
      }
      $('#sub').val(data['sub_id']);
      $('#isp_num').val(data['isp_id']);
      $('#isp').val(data['isp_id']);
      $('#refNo').val(data['refNo']);
      $('#date_refNo').val(data['date_refNo']);
      $('#file-input').val("");

      if (typeof admin_hideshowforms === "function") admin_hideshowforms();

      $('#date_closed').val(data['date_closed']);
      $('#remarks').val(data['remarks']);

      $('#remarks_view, .dv_msg, .container_remarks').show();
      $('#msg_thread').slideDown(300);
      $('#userModal').modal({ "show": true, "backdrop": 'static' });
      loadCommentThread(data['ticket_no']);

      if (typeof unilayout_netshowmodalform === "function") unilayout_netshowmodalform();

      $('#itsup').off('change').on('change', function () {
        if ($('#it_num').val() != this.value) {
          $('#remarks').attr("placeholder", "Reason for re-assign/ Workoutput").val("");
        } else {
          $('#remarks').val(data['remarks']);
        }
      });

      if ($('#status').val() == 'CLOSED') {
        $(':input[type="submit"]').prop('disabled', true);
        $('#date_createdx, #date_refNo, #date_closed, #remarks').attr('readonly', true);
        $('#store, #via, #status, #itsup, #cat, #sub, #isp').prop("disabled", true);
      } else {
        $(':input[type="submit"]').prop('disabled', false);
        $('#date_createdx, #date_refNo, #date_closed, #subjct, #remarks').attr('readonly', false);
        $('#store, #via, #status, #itsup, #cat, #sub, #isp').prop("disabled", false);
      }

      if (typeof getinfo === "function") getinfo(tid, 'remarks', user_id);
      if (typeof gtsub_id === "function") gtsub_id();

      $('.modal-title').text("Ticket Number: " + tid);
      $('#action').val("Save and Reply");
      $('#operation').val("Save and Reply");

      // HYBRID RENDER INTEGRATED HERE 
      displayAttachmentsFromData(data, tid);

      $('#msgbtn, #msg_thread').show();
      $('#addmsg').val("");
    });
  }

  var targetTicket = getUrlParam('ticket_no');
  function getUrlParam(param) {
    return new URLSearchParams(window.location.search).get(param);
  }

  if (targetTicket) {
    setTimeout(function () {
      var foundRow = null;
      if (typeof reptable !== 'undefined') {
        reptable.rows().every(function () {
          if (this.data() && this.data().ticket_no == targetTicket) foundRow = this.node();
        });
        if (foundRow) {
          $(foundRow).find('button[name="update"]').trigger('click');
          $('html, body').animate({ scrollTop: $(foundRow).offset().top - 100 }, 800, function () {
            $(foundRow).css({ 'transition': 'background-color 0.5s ease', 'background-color': '#ffff99' });
            setTimeout(function () { $(foundRow).css('background-color', ''); }, 1200);
          });
        }
      }
    }, 600);
  }

  $(document).on("submit", "#report_form", function (e) {
    e.preventDefault();

    var TicketNumber = $("#ticket_no").val();
    var DateCreated = new Date($("#date_created").val());
    var Status = $("#status").val();
    var DateClosed = new Date($("#date_closed").val());
    var today = new Date();

    if (DateCreated > today) { alert("Invalid date"); return false; }
    else if (Status == "ON PROCESS" && DateClosed < DateCreated) { alert("Date closed should be greater than date created!"); return false; }
    else if (DateClosed > today) { alert("Invalid Closed_Date"); return false; }

    var disabledFields = $('#store, #via, #status, #itsup, #cat, #sub');
    disabledFields.prop('disabled', false); 

    var formData = new FormData(this);
    
    var operation = $('#operation').val();
    var submitUrl = (operation === 'Add') ? 'api_create_dept_report.php' : 'insert.php';

    if(operation === 'Add') {
        formData.append('subject', $('#cat').val() || $('#subjct').val() || '');
        formData.append('concern', $('#remarks').val() || $('#addmsg').val() || '');
    }

    setTimeout(function () {
      if ($('#status').val() !== 'CLOSED') {
        disabledFields.prop('disabled', true);
      } else {
        $(':input[type="submit"]').prop('disabled', true);
        $('#date_createdx, #date_refNo, #date_closed, #remarks').attr('readonly', true);
        $('#store, #via, #status, #itsup, #cat, #sub, #isp').prop('disabled', true);
      }
    }, 50);

    $.ajax({
      url: submitUrl,
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (data) {
        let res = typeof data === 'string' ? JSON.parse(data) : data;
        
        let isSuccess = (res.Response === true) || (res.status === "success");
        let finalTicketNo = TicketNumber || res.ticket_no; 

        if ($('#file-input').length) {
          var files = $('#file-input')[0].files;
          if (files.length > 0 && finalTicketNo) {
            var fileData = new FormData();
            for (var i = 0; i < files.length; i++) {
              fileData.append('files[]', files[i]);
            }
            fileData.append('ticket_no', finalTicketNo);

            $.ajax({
              type: "POST",
              url: "insertimg.php",
              data: fileData,
              processData: false,
              contentType: false
            });
          }
        }

        Swal.fire({
          icon: isSuccess ? 'success' : 'error',
          title: isSuccess ? 'Your work has been saved' : 'Action Failed',
          html: res.m || res.message || '',
          showConfirmButton: false,
          timer: 1500
        });

        if (finalTicketNo && isSuccess) {
          $('#addmsg').val('');
          loadCommentThread(finalTicketNo);
          
          $('#ticket_no').val(finalTicketNo);
          $('#operation').val('Edit'); 
          $('#action').val('Save');
        } else if(isSuccess) {
          $("#userModal").modal("hide");
        }

        const yr = $("#yearpicker").val() || new Date().getFullYear();
        if (typeof getdata === 'function') getdata(yr);
        if (typeof get_card_data === 'function') get_card_data(yr);
        if (typeof get_dept_data === 'function') get_dept_data(); 
      }
    });
  });

  var uploadField = document.getElementById("file-input");
  if (uploadField) {
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

  function loadCommentThread(ticket_no) {
    const $remarksView = $('#remarks_view');
    const ticketValue = (ticket_no || '').toString().trim();
    if (!ticketValue) return;
    $remarksView.stop(true, true).hide().html('<div class="text-center text-muted mt-4 mb-4"><div class="spinner-border spinner-border-sm me-2 text-primary"></div>Loading conversation...</div>').fadeIn(150);

    $.ajax({
      url: 'get_comments.php',
      type: 'POST',
      dataType: 'json',
      data: { ticket_no: ticketValue },
      success: function (response) {
        let html = '';
        if (Array.isArray(response) && response.length > 0) {
          var currentUserIdStr = "<?= $_SESSION['user_id'] ?? '' ?>";
          var currentUserNameStr = "<?= $_SESSION['fname'] ?? '' ?>";

          response.forEach(function (comment, index) {
            let sender = comment.userId || 'Unknown';
            let isMe = false;
            if (currentUserIdStr !== "" && sender === currentUserIdStr) isMe = true;
            if (currentUserNameStr !== "" && sender.includes(currentUserNameStr)) isMe = true;

            let bubbleClass = isMe ? 'chat-right' : 'chat-left';
            let delay = index * 0.05;

            html += `
              <div class="chat-bubble ${bubbleClass}" style="animation-delay: ${delay}s;">
                  <div class="msg-meta">
                      <span class="msg-meta-name">${sender}</span>
                      <span>${comment.comment_date}</span>
                  </div>
                  <div style="white-space: pre-wrap;">${comment.comment_details}</div>
              </div>`;
          });
        } else {
          html = '<div class="text-center text-muted mt-3" style="font-size:13px;"><i class="fas fa-comments mb-2" style="font-size:24px; opacity:0.5;"></i><br>No comments yet. Start the conversation!</div>';
        }

        $remarksView.stop(true, true).hide().html(html).fadeIn(300);
        $('.container_remarks').css('display', 'flex').hide().slideDown(300);
        $('.dv_msg').slideDown(300);

        setTimeout(() => {
          const $container = $('.container_remarks');
          if ($container.length) $container.animate({ scrollTop: $container.prop("scrollHeight") }, 600, 'swing');
        }, 200);
      },
      error: function () {
        $remarksView.stop(true, true).html('<div class="text-danger text-center mt-3">Error loading comments.</div>').show();
      }
    });
  }

  $(document).on('click', '#msgbtn', function () {
    $('.dv_msg, #remarks_view').show();
    if ($('#msgbtn').val() == 'show') {
      $('#action, #operation').val("Save and Reply");
      $('#msgbtn').val("hide");
      $('#msg_thread, .container_remarks').slideDown(400);
      setTimeout(() => {
        const $container = $('.container_remarks');
        $container.animate({ scrollTop: $container.prop("scrollHeight") }, 400);
      }, 450);
    } else if ($('#msgbtn').val() == 'hide') {
      $('#action').val("Save");
      $('#operation').val("Edit");
      $('#msgbtn').val("show");
      $('#msg_thread').slideUp(400);
    }
  });


  function displayAttachmentsFromData(data, ticketNo) {
    const container = $('#img');
    container.html('<p class="text-muted text-center w-100"><i class="fas fa-spinner fa-spin"></i> Loading attachments...</p>');

    const fileData = data['attachment_files'] || data['images'];

    if (fileData && fileData.trim() !== '') {
        const filePaths = fileData.split(/[|,]+/).filter(f => f.trim() !== '');
        
        if (filePaths.length === 0) {
            container.html('<p class="text-muted text-center w-100 mb-0">No attachments for this ticket.</p>');
            return;
        }

        let html = '<div class="d-flex flex-wrap gap-2">';
        filePaths.forEach(imagePath => {
            let fullPath = imagePath.trim();
            if (!fullPath.includes('users/image/')) fullPath = '../users/image/' + fullPath;

            html += `
                <div class="position-relative shadow-sm border" style="width: 100px; height: 100px; border-radius: 8px; overflow: hidden;">
                    <img src="${fullPath}" 
                         class="img-fluid"
                         style="width: 100%; height: 100%; object-fit: cover; cursor: pointer; transition: transform 0.2s ease;"
                         onmouseover="this.style.transform='scale(1.08)'"
                         onmouseout="this.style.transform='scale(1.0)'"
                         onclick="window.open('${fullPath}', '_blank')"
                         alt="Attachment">
                </div>
            `;
        });
        html += '</div>';
        
        container.html(html);
    } 
    else {
        $.ajax({
            type: 'POST',
            url: 'sesticket.php',
            data: { tktval: ticketNo },
            success: function(response) {
                if(!response || response.trim() === '') {
                    container.html('<p class="text-muted text-center w-100 mb-0">No attachments found.</p>');
                } else {
                    container.html(response);
                }
            },
            error: function(xhr, status, error) {
                console.error("Failed to load attachments:", error);
                container.html('<p class="text-danger text-center w-100 mb-0"><i class="fas fa-exclamation-triangle"></i> Error loading attachments.</p>');
            }
        });
    }
  }


  $('#btnClose').click(function () {
    if ($('#report_form').length) $('#report_form')[0].reset();
    $('.dv_msg, #remarks_view').hide();
    $('#tmpsubid').remove();
    $('#addmsg').val('');
  });

  $(function () {
    if ($.fn.datetimepicker) {
      $('#datetimepicker1, #datetimepicker2, #datetimepicker3, #ModalDate_close, #date_refNo').datetimepicker();
    }
  });

  $("div.selected select").val("OPEN");

  if (/Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) { 
    $("#ovrall").hide(); 
  }

  $('#subpie_clsbtn, #substr_clsbtn').click(function (e) {
    e.preventDefault();
    $(this.id === 'subpie_clsbtn' ? '#chartdiv9' : '#substr_clsbtn').empty();
  });

  let inactivityTime = function () {
    let time;
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.onscroll = resetTimer;
    document.onclick = resetTimer;

    function logout() { window.location.href = 'adminpanel.php'; }
    function resetTimer() { clearTimeout(time); time = setTimeout(logout, 180000); }
  };
  inactivityTime();

  function verQTY() {
    let formxxx = document.getElementById('cof_form');
    if (formxxx) {
      $.ajax({ url: "insert.php", method: "POST", data: new FormData(formxxx), contentType: false, processData: false });
    }
  }
  verQTY();

  let endDate = new Date();
  endDate.setDate(endDate.getDate() - 1);
  let startDate = new Date(endDate);
  $('#frompolDate').val(startDate.toISOString().split('T')[0]);
  $('#topolDate').val(endDate.toISOString().split('T')[0]);

  if (typeof _polledraph === "function") _polledraph($('#frompolDate').val(), $('#topolDate').val());
  $('#frompolDate, #topolDate').change(function () {
    if (typeof _polledraph === "function") _polledraph($('#frompolDate').val(), $('#topolDate').val());
  });

  $('#userModal').on('shown.bs.modal', function () {
    const ticketNo = $('#ticket_no').val();
    if (ticketNo) {
      $('.dv_msg, .container_remarks').show();
      loadCommentThread(ticketNo);
    }
  });

  $(document).on('hidden.bs.modal', '#userModal', function () {
    $('.temp-option').remove();
    $('#img').empty();
  });


  const yr = $("#yearpicker").val() || new Date().getFullYear();
  getdata(yr);
  
  function get_card_data(y) {
    $.post('fetchdata/fetch_data.php', { yr: y, mode: 'yearch' }, function (data) {
      let card_data = typeof data === 'string' ? JSON.parse(data) : data;
      const a = card_data;
      $('#count_total').html(a[0].total_res);
      $('#count_open').html(a[0].open_res);
      $('#count_owfa').html(a[0].owfa_res);
      $('#count_closed').html(a[0].cls_res);
      $('#today_closed').html(a[0].t_res);
    });
  }
  get_card_data(yr);

  $("#yearpicker").on('change', function () {
    const yr = $(this).val();
    getdata(yr);
    get_card_data(yr);
    if (typeof _techgraph === "function") _techgraph(yr);
    if (typeof _overallpie === "function") _overallpie(yr);
    if (typeof _dbline === "function") _dbline(yr);
    if (typeof _catpie === "function") _catpie(yr);
    if (typeof _areagraph === "function") _areagraph(yr);
  });

  $('#store_graph_modal').modal('hide');
  if (typeof slct_isp === "function") slct_isp();
  if (typeof slct_sub === "function") slct_sub();
  if (typeof gtsub_id === "function") gtsub_id();
  if (typeof admin_hideshowforms === "function") admin_hideshowforms();

});


</script>