<script type="text/javascript">
$(document).ready(function() {
    let reptable;
    const user_id = <?= (int)$_SESSION['user_id']; ?>;

    $("div.selected select").val("OPEN");

    $('#f_deptsel').select2({
        dropdownParent: $('#newrpt_Modal'),
        width: '100%'
    });

    $('#datetimepicker2, #datetimepicker3').datetimepicker();

    if (typeof admin_hideshowforms === "function") {
        admin_hideshowforms();
    }

    getdata();
    setInterval(getdata, 60000); 
    function getdata() {
        $.post('fetchdata/fetch_data.php', { mode: 'trans_tbl' }, function(data) {
            admin_datatable(data);
        }, 'json');
    }

    function admin_datatable(t) {
        const dataset = t.transdata || [];

        reptable = $("#new_rep_table").DataTable({
            dom: '<"pull-left"f><"pull-right">tip',
            stateSave: false,
            bDestroy: true,
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            language: {
                emptyTable: "No unassigned reports",
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            },
            pageLength: 10,
            data: dataset,
            order: [[4, "Desc"]],
            columns: [
                { title: "TicketNo", data: "ticket_no", defaultContent: "" },
                { title: "Selected Department", data: "dept_desc", defaultContent: "" },
                { title: "Department/Store", data: "str_code", defaultContent: "" },
                { title: "Created By", data: "full_name", defaultContent: "" },
                {
                    title: "Date Created",
                    data: "date_created",
                    defaultContent: "",
                    render: function(data, type, row) {
                        if (type === 'sort' || type === 'type') {
                            let parts = data.split(" ");
                            let date = parts[0].split("/");
                            let time = parts[1];
                            return `${date[2]}-${date[0]}-${date[1]} ${time}`;
                        }
                        return data; 
                    }
                },
                { title: "SUBJECT", data: "concern", defaultContent: "" },
                { title: "Types of Service", data: "service_desc", defaultContent: "" },
                { title: "CONCERN", data: "subject", defaultContent: "" },
                { title: "Update", data: null, defaultContent: "<button class='btn btn-danger' name='update'><i class='fas fa-edit'></i></button>" }
            ],
            rowCallback: function(row, data) {
                if (data['msg_cnt'] == '1') {
                    $(row).find('td').css("font-weight", "bold");
                }
            }
        });

        $('#new_rep_table_filter input').off().on('keyup', function () {
            let value = $.fn.dataTable.util.escapeRegex($(this).val());
            reptable
                .column(2)
                .search('^' + value + '$', true, false) 
                .draw();
        });

        $('#new_rep_table tbody').off('click', 'button').on('click', 'button', function () {
            let data = reptable.row($(this).parents('tr')).data();
            if (!data) return;

            $('#ticket_no').val(data['ticket_no']);
            $('#store').val(data['store']);
            $('#str_desc').val(data['str_code']);
            $('#crtd_by').val(data['full_name']);
            $('#date_createdx').val(data['date_created']);
            $('#concern').val(data['concern']); 
            $('#tos').val(data['service_desc']);
            $('#message').val(data['subject']); 
            $('#sub_num').val(data['sub_id'] || '');
            let currentDept = data['itsup'] || data['f_deptsel'] || '0';
            $('#old_dept').val(currentDept); 
            
            $('#f_deptsel').val(currentDept !== '0' ? currentDept : '').trigger('change');

            $('#newrpt_Modal').modal('show');
            $('#action').val("Update");
            $('#operation').val("New_Report");

            let tid = data['ticket_no'];
            $('#tick_title').text("Ticket Number: " + tid);

            if (typeof getinfo === "function") {
                getinfo(tid, 'remarks', user_id);
            }
            
            // Execute the AJAX call
            loadDeptsel(currentDept);
        });
    }

   function loadDeptsel(itsup_value) {
        if (itsup_value && itsup_value !== "0") {
            $('#deptsel').val("Fetching..."); 
            
            $.ajax({
                url: "fetch_deptsel.php", 
                method: "POST",
                data: { itsup: itsup_value },
                success: function(response) {
                    $('#deptsel').val(response.trim()); 
                },
                error: function() {
                    $('#deptsel').val("Error fetching data");
                }
            });
        } else {
            $('#deptsel').val("No Department Assigned");
        }
    }

    $('#cat').on('change', function() {
        let category_id = this.value;
        $.ajax({
            url: "get_subcat.php",
            type: "POST",
            data: { category_id: category_id },
            cache: false,
            success: function(dataResult) {
                $("#sub").html(dataResult);
            }
        });
    });

    $(document).on('submit', '#newrpt_form', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();

        Swal.fire({
            title: 'Submitting Ticket...',
            text: 'Please wait while the system saves the ticket.',
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
            success: function(data) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data,
                    confirmButtonColor: '#EAAA00'
                }).then(function() {
                    $('#newrpt_form')[0].reset();
                    $('#newrpt_Modal').modal('hide');
                    getdata();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Failed',
                    text: xhr.responseText || 'Something went wrong.',
                    confirmButtonColor: '#d33'
                });
            }
        });
    });

    $(document).on('click', '#msgbtn', function() {
        $('.dv_msg').show();
        $('#remarks_view').show();

        let $btn = $(this);

        if ($btn.val() === 'show') {
            $('#action').val("New_Report");
            $('#operation').val("New_Report");
            $btn.val("hide");
            $('#msg_thread').show('slow');
        } else {
            $('#action').val("Save");
            $('#operation').val("New_Report");
            $btn.val("show");
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
                },
                error: function () {
                    $('#contactNumber').val('');
                }
            });
        } else {
            $('#contactNumber').val('');
        }
    });

}); 
</script>