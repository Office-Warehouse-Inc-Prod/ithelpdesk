<script type='text/javascript'>
$( document ).ready(function() {

    $.fn.dataTable.ext.pager.numbers_length = 10;

    /**
     * Reload dashboard.
     */
    function reload_dashboard() {
        const yr = $("#yearpicker").val();
        const dept_id = $("#dept_id").val();

        getdata(yr);
        get_card_data(yr);
        _overallpie(yr, dept_id);
        _areagraph(yr);

        // default category chart on load
        _categorypie_all(yr, dept_id);
        // Uncomment kapag kailangan mo na rin i-refresh ito
        // _techgraph(yr);
        // _dbline(yr);
        // _catpie(yr);
        // bargrph_tech_res(yr);
        // itsupdata(yr);
        // _storegraph(yr);
    }
 
    $("#yearpicker").on("change", function () {
        reload_dashboard();
    });

    $("#dept_id").on("change", function () {
        reload_dashboard();
    }); 

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

    if(/Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) { $("#ovrall").hide(); }

    var user_id = <?= $_SESSION['user_id']; ?>

    let val = '';
    $('#card_totalval, #card_assigned, #card_onprocess, #card_pending, #card_nonesca, #card_subforclosing, #card_closed').click(function(e) {
        e.preventDefault();
        val =  $(this).attr("value");
        $('html, body').animate({
            scrollTop: $('#report_data').offset().top - 80
        }, 600);
    });

    $('#myInput').on( 'input', function () {
        table.search( this.value ).draw();
    } );

    /**
     * Getdata.
     */
    function getdata(yr) {
        $.post(
            'fetchdata/fetch_data.php',
            {
                yr: yr,
                dept_id: $('#dept_id').val(),
                mode: 'dtb'
            },
            function (data) {
                admin_datatable(data);
            },
            'json'
        );
    }

    /**
     * Getdata Transfer (Added).
     */
    function getdata_transfer(yr) {
        $.post(
            'fetchdata/fetch_data.php',
            {
                yr: yr,
                dept_id: $('#dept_id').val(),
                mode: 'dtb_transfer'
            },
            function (data) {
                admin_datatable_transfer(data);
            },
            'json'
        );
    }

    var table;
    var table_transfer;

    /**
     * Admin datatable.
     */
    function admin_datatable(t){
        const dataset = t.rptdata;
       let currentPage = 0;
        let globalSearch = "";
        let colSearches = [];

        if ($.fn.DataTable.isDataTable("#report_data")) {
            let dt = $("#report_data").DataTable();
            currentPage = dt.page();
            globalSearch = dt.search();
            let colCount = dt.columns().count();
            for(let i = 0; i < colCount; i++) {
                colSearches.push(dt.column(i).search());
            }
        }

        table = $("#report_data").DataTable({
            dom:
                "<'dt-top d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2'"+
                "<'dt-left d-flex align-items-center gap-2'l<f>>" +
                "<'dt-right d-flex align-items-center gap-2'B>" +
                ">" +
                "<'dt-table'rt>" +
                "<'dt-bottom d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2'ip>",
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> <span class="d-none d-md-inline">Export</span>',
                    attr: {
                        title: 'Export to Excel',
                        class: 'btn btn-success btn-sm rounded-pill px-3 shadow-sm'
                    }
                }
            ],
            pagingType: "simple_numbers", 
            bDestroy: true,
            responsive: {
                details: {
                    type: 'inline',
                    target: 'tr'
                }
            },
            lengthChange: false,
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Search tickets…",
                zeroRecords: "No matching tickets found",
                info: "Showing _START_ to _END_ of _TOTAL_ tickets",
                infoEmpty: "No tickets to show"
            },
            pageLength: 10,
            data: dataset,
            order: [[5, "desc"]],
            columns: [
                {
                    title: "Actions",
                    data: null,
                    orderable: false,
                    width: "130px", // ⭐ VERY IMPORTANT
                    className: "text-center",
                    render: function(data, type, row){
                        // ✏️ EDIT
                        let editBtn = `
                            <button class='btn btn-circle btn-edit'
                                    name='update'
                                    title='Edit Ticket'>
                                <i class='fas fa-pen'></i>
                            </button>
                        `;

                        // 📞 VIBER
                        let viberBtn = row.contactNumber
                            ? `
                                <a href="#"
                                    class="btn btn-circle btn-viber viber-call"
                                    data-ticket_no="${row.ticket_no}"
                                    data-dept_id="${row.f_deptsel}"
                                    data-number="${row.contactNumber}"
                                    title="Call via Viber">
                                    <i class="fab fa-viber"></i>
                                </a>
                              `
                            : `
                                <button class="btn btn-circle btn-disabled"
                                        disabled
                                        title="No Contact Number">
                                    <i class="fab fa-viber"></i>
                                </button>
                              `;

                        // ✉️ EMAIL
                        let emailBtn = row.dept_email
                            ? `
                                <a href="mailto:${row.dept_email}?subject=Helpdesk Ticket ${row.ticket_no}"
                                    class="btn btn-circle btn-email"
                                    title="Send Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                              `
                            : `
                                <button class="btn btn-circle btn-disabled"
                                        disabled
                                        title="No Email">
                                    <i class="fas fa-envelope"></i>
                                </button>
                              `;

                        return `
                            <div class="action-btn-group">
                                ${editBtn}
                                ${viberBtn}
                                ${emailBtn}
                            </div>
                        `;
                    }
                },
                {
                    title: "",
                    data: "msg_cnt",
                    className: "text-center",
                    defaultContent: "",
                    render: function (data, type, row) {
                        if (type === 'display') {
                            return (String(data) === '1')
                                ? "<span title='New message'><i class='fas fa-envelope'></i></span>"
                                : "";
                        }
                        return data;
                    }
                },
                { title: "Ticket No", data: "ticket_no", defaultContent: "" },
                { 
                    title: "Priority Level", 
                    data: "priority_desc", 
                    defaultContent: "",
                    render: function (data, type, row) {
                        if (type !== 'display') return data;
                        const s = (data || "").toUpperCase();
                        let cls = "badge bg-secondary";

                        if (s === "CRITICAL") cls = "badge bg-danger";
                        else if (s === "HIGH") cls = "badge bg-warning text-dark";
                        else if (s === "MEDIUM") cls = "badge bg-warning text-dark";
                        else if (s === "LOW") cls = "badge bg-info text-dark";

                        return `<span class="${cls} px-2 py-1">${data}</span>`;
                    }
                },
                { title: "Store", data: "str_code", defaultContent: "" },
                {
                    title: "Date Created",
                    data: "date_created",
                    defaultContent: "",
                    render: function(data, type, row){
                        if(type === 'sort' || type === 'type'){
                            let parts = data.split(" ");
                            let date = parts[0].split("/");
                            let time = parts[1];
                            return date[2] + "-" + date[0] + "-" + date[1] + " " + time;
                        }
                        return data; 
                    }
                },
                { title: "Subject", data: "subject", defaultContent: "" },
                {
                    title: "Status",
                    data: "status",
                    defaultContent: "",
                    render: function (data, type, row) {
                        if (type !== 'display') return data;

                        const s = (data || "").toUpperCase();
                        let cls = "badge bg-secondary";

                        if (s === "ASSIGNED") cls = "badge bg-warning text-dark";
                        else if (s === "CLOSED") cls = "badge bg-success text-white";
                        else if (s === "SUBJECT FOR CLOSING") cls = "badge bg-primary text-white";
                        else if (s === "ON PROCESS") cls = "badge bg-info";
                        else if (s === "ATTENDED WITH FIX ASSET") cls = "badge bg-info text-dark";
                        else if (s === "PENDING") cls = "badge bg-danger text-white";

                        return `<span class="${cls} px-2 py-1">${data}</span>`;
                    }
                },
                {
                    title: "Non Escalated",
                    data: "non_escalated_tag",
                    defaultContent: "",
                    visible: false,
                    searchable: true
                },
                {
                    title: "Assigned Dept",
                    data: null,
                    defaultContent: "",
                    render: function (data, type, row) {
                        const dept = row.dept_desc || row.it_desc || "";
                        return dept;
                    }
                },
                { title: "Dept Personnel", data: "category", defaultContent: "" },
                {
                    title: "Date Closed",
                    data: "date_closed",
                    defaultContent: "",
                    render: function (data, type, row) {
                        if (type !== 'display') return data;
                        if (!data) return "";
                        if (data === "01/01/1970 01:00" || data === "01/01/1970 08:00") return "";
                        return data;
                    }
                },
                {
                    title: "Days",
                    data: "tdc",
                    defaultContent: "",
                    render: function (data, type, row) {
                        if (type !== 'display') return data;
                        if (data === null || data === undefined || data === "") return "";
                        const n = parseInt(data, 10);
                        if (isNaN(n) || n < 0) return "";
                        const isOpen = (row.status || '').toUpperCase() !== 'CLOSED';
                        const dayWord = (n === 1) ? "Day" : "Days";
                        return isOpen ? `${n} ${dayWord} Unresolved` : `${n} ${dayWord}`;
                    }
                },
                {
                    title: "Work Output",
                    data: "remarks",
                    defaultContent: "",
                    render: function (data, type, row) {
                        if (type !== 'display') return data;
                        if (!data) return "";
                        const txt = String(data);
                        return txt.length > 60 ? (txt.slice(0, 60) + "…") : txt;
                    }
                }
            ],
           rowCallback: function (row, data) {
                $(row).removeClass('status-open status-closed status-subject-closing status-fixed');
                const s = (data['status'] || "").toUpperCase();

                if (s === 'ASSIGNED') $(row).addClass('status-open');
                else if (s === 'ON PROCESS') $(row).addClass('status-fixed');
                else if (s === 'CLOSED') $(row).addClass('status-closed');
                else if (s === 'SUBJECT FOR CLOSING') $(row).addClass('status-subject-closing');
            }
        });

       if (globalSearch !== "") {
            table.search(globalSearch);
        }
        colSearches.forEach((val, i) => {
            if (val !== "") {
                table.column(i).search(val);
            }
        });
        table.page(currentPage).draw(false);

        $('#report_data tbody').off('dblclick').on('dblclick', 'tr', function () {
            var data = table.row($(this)).data();
            if (!data) return;
            open_ticket_modal(data);
            $('#subjct').attr('readonly', true);
            var tid = $(this).find('td:eq(2)').html(); 
            $('#ticket_no').val(data['ticket_no']);
            $('#f_deptsel').val(data['f_deptsel']);  
            $('#str_num').val(data['store']);
            $('#store').val(data['store']);
            $('#date_createdx').val(data['date_created']);
            $('#subjct').val(data['subject']);
            $('#concern').val(data['concern']);
            $('#status').val(data['status']);
            $('#non_escalated_tag').val(data['non_escalated_tag']);
            $('#priority_desc').val(data['priority_desc']);
           $('#close_by').val(data['close_by']);
$('#cl_desc').val(data['close_by_desc'] ? data['close_by_desc'] : data['close_by']);

            admin_hideshowforms();
            $('#date_closed').val(data['date_closed']);
            $('#remarks').val(data['remarks']);

            if($('#status').val() == 'CLOSED') {
                $(':input[type="submit"]').prop('disabled', true); 
                $('#date_createdx, #date_refNo, #date_closed, #remarks').attr('readonly', true);
                $('#store, #via, #status, #itsup, #cat, #sub, #isp').prop("disabled", true);
            } else {
                $(':input[type="submit"]').prop('disabled', false); 
                $('#date_createdx, #date_refNo, #date_closed, #remarks').attr('readonly', false);
                $('#store, #via, #status, #itsup, #cat, #sub, #isp').prop("disabled", false);
            }

            var option = document.createElement("option");
            option.value = 0;
            option.id = 'tmpsubid';
            option.selected = 'selected';
            option.text = $(this).find('td:eq(10)').html();

            getinfo(tid, 'remarks', user_id);

            $('.modal-title').text("Ticket Number: " + tid);
            $('#action').val("Save and Reply");
            $('#operation').val("Save and Reply"); 
            $('#userModal').modal({ "show": true, "backdrop": 'static' });

            var valtick = $('#ticket_no').val();
            $.ajax({
                type: 'POST',
                url: 'sesticket.php',
                data: {tktval: valtick},
                success: function(response) {
                    $('#img').html(response);
                }
            });

            $('#msgbtn').show();
            $('#msg_thread').show();
            $('.dv_msg').show();
            $('#remarks_view').show();
            $('#addmsg').val("");
        });
        
        $('#report_data tbody').off('click', '.btn-edit').on('click', '.btn-edit', function (e) {
            e.preventDefault();
            var data = table.row($(this).closest('tr')).data();
            if (!data) return;
            open_ticket_modal(data);
        });
        $('#card_totalval, #card_assigned, #card_onprocess, #card_pending, #card_nonesca, #card_subforclosing, #card_closed').on('click', function () {
            var val =  $(this).attr("value");
            table.columns( 7 ).search(val).draw();
        });

        $('.clcktxt').click(function () { 
            var val =  $(this).attr("value");
            table.columns(7).search(val).draw();
            $('#network_tb').slideToggle();
            $('html, body').animate({ scrollTop: 1600 }, 1000);
        });
    }

    /**
     * Admin datatable for Transferred 
     */
    function admin_datatable_transfer(t) {
        const dataset = t.transferdata;
     let currentPage = 0;
        let globalSearch = "";
        let colSearches = [];

        if ($.fn.DataTable.isDataTable("#transferred_data")) {
            let dt = $("#transferred_data").DataTable();
            currentPage = dt.page();
            globalSearch = dt.search();
            let colCount = dt.columns().count();
            for(let i = 0; i < colCount; i++) {
                colSearches.push(dt.column(i).search());
            }
        }
        table_transfer = $("#transferred_data").DataTable({
            dom:
                "<'dt-top d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2'"+
                "<'dt-left d-flex align-items-center gap-2'l<f>>" +
                "<'dt-right d-flex align-items-center gap-2'B>" +
                ">" +
                "<'dt-table'rt>" +
                "<'dt-bottom d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2'ip>",
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> <span class="d-none d-md-inline">Export Transferred</span>',
                    attr: {
                        title: 'Export Transferred Tickets',
                        class: 'btn btn-success btn-sm rounded-pill px-3 shadow-sm'
                    }
                }
            ],
            pagingType: "simple_numbers",
            bDestroy: true,
            responsive: {
                details: {
                    type: 'inline',
                    target: 'tr'
                }
            },
            lengthChange: false,
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Search transferred...",
                zeroRecords: "No matching tickets found",
                info: "Showing _START_ to _END_ of _TOTAL_ tickets",
                infoEmpty: "No tickets to show"
            },
            pageLength: 10,
            data: dataset,
            order: [[5, "desc"]],
            columns: [
                {
                    title: "Actions",
                    data: null,
                    orderable: false,
                    width: "130px",
                    className: "text-center",
                    render: function(data, type, row){
                        let editBtn = `
                            <button class='btn btn-circle btn-edit' name='update' title='Edit Ticket'>
                                <i class='fas fa-pen'></i>
                            </button>
                        `;

                        let viberBtn = row.contactNumber
                            ? `<a href="#" class="btn btn-circle btn-viber viber-call" data-ticket_no="${row.ticket_no}" data-dept_id="${row.f_deptsel}" data-number="${row.contactNumber}" title="Call via Viber"><i class="fab fa-viber"></i></a>`
                            : `<button class="btn btn-circle btn-disabled" disabled title="No Contact Number"><i class="fab fa-viber"></i></button>`;

                        let emailBtn = row.dept_email
                            ? `<a href="mailto:${row.dept_email}?subject=Helpdesk Ticket ${row.ticket_no}" class="btn btn-circle btn-email" title="Send Email"><i class="fas fa-envelope"></i></a>`
                            : `<button class="btn btn-circle btn-disabled" disabled title="No Email"><i class="fas fa-envelope"></i></button>`;

                        return `<div class="action-btn-group">${editBtn}${viberBtn}${emailBtn}</div>`;
                    }
                },
                {
                    title: "",
                    data: "msg_cnt",
                    className: "text-center",
                    defaultContent: "",
                    render: function (data, type, row) {
                        if (type === 'display') return (String(data) === '1') ? "<span title='New message'><i class='fas fa-envelope'></i></span>" : "";
                        return data;
                    }
                },
                { title: "Ticket No", data: "ticket_no", defaultContent: "" },
               
                { title: "Store", data: "str_code", defaultContent: "" },
                {
                    title: "Date Created",
                    data: "date_created",
                    defaultContent: "",
                    render: function(data, type, row){
                        if(type === 'sort' || type === 'type'){
                            let parts = data.split(" ");
                            let date = parts[0].split("/");
                            let time = parts[1];
                            return date[2] + "-" + date[0] + "-" + date[1] + " " + time;
                        }
                        return data; 
                    }
                },
                { title: "Subject", data: "subject", defaultContent: "" },
                {
                    title: "Status",
                    data: "status",
                    defaultContent: "",
                    render: function (data, type, row) {
                        if (type !== 'display') return data;

                        const s = (data || "").toUpperCase();
                        let cls = "badge bg-secondary";

                        if (s === "ASSIGNED") cls = "badge bg-warning text-dark";
                        else if (s === "CLOSED") cls = "badge bg-success text-white";
                        else if (s === "SUBJECT FOR CLOSING") cls = "badge bg-primary text-white";
                        else if (s === "ON PROCESS") cls = "badge bg-info";
                        else if (s === "ATTENDED WITH FIX ASSET") cls = "badge bg-info text-dark";
                        else if (s === "PENDING") cls = "badge bg-danger text-white";

                        return `<span class="${cls} px-2 py-1">${data}</span>`;
                    }
                },
                { title: "Non Escalated", data: "non_escalated_tag", defaultContent: "", visible: false, searchable: true },
                {
                    title: "Assigned Dept",
                    data: null,
                    defaultContent: "",
                    render: function (data, type, row) {
                        return row.dept_desc || row.it_desc || "";
                    }
                },
                { title: "Dept Personnel", data: "category", defaultContent: "" },
                {
                    title: "Date Closed",
                    data: "date_closed",
                    defaultContent: "",
                    render: function (data, type, row) {
                        if (type !== 'display') return data;
                        if (!data || data === "01/01/1970 01:00" || data === "01/01/1970 08:00") return "";
                        return data;
                    }
                },
                {
                    title: "Days",
                    data: "tdc",
                    defaultContent: "",
                    render: function (data, type, row) {
                        if (type !== 'display') return data;
                        if (data === null || data === undefined || data === "") return "";
                        const n = parseInt(data, 10);
                        if (isNaN(n) || n < 0) return "";
                        const isOpen = (row.status || '').toUpperCase() !== 'CLOSED';
                        const dayWord = (n === 1) ? "Day" : "Days";
                        return isOpen ? `${n} ${dayWord} Unresolved` : `${n} ${dayWord}`;
                    }
                },
                {
                    title: "Work Output",
                    data: "remarks",
                    defaultContent: "",
                    render: function (data, type, row) {
                        if (type !== 'display') return data;
                        if (!data) return "";
                        const txt = String(data);
                        return txt.length > 60 ? (txt.slice(0, 60) + "…") : txt;
                    }
                }
            ],
           rowCallback: function (row, data) {
                $(row).removeClass('status-open status-closed status-subject-closing status-fixed');
                const s = (data['status'] || "").toUpperCase();

                if (s === 'ASSIGNED') $(row).addClass('status-open');
                else if (s === 'ON PROCESS') $(row).addClass('status-fixed');
                else if (s === 'CLOSED') $(row).addClass('status-closed');
                else if (s === 'SUBJECT FOR CLOSING') $(row).addClass('status-subject-closing');
            }
        });

        if (globalSearch !== "") {
            table_transfer.search(globalSearch);
        }
        colSearches.forEach((val, i) => {
            if (val !== "") {
                table_transfer.column(i).search(val);
            }
        });
        table_transfer.page(currentPage).draw(false);

        $('#transferred_data tbody').off('dblclick').on('dblclick', 'tr', function () {
            var data = table_transfer.row($(this)).data();
            if (!data) return;
            open_ticket_modal(data);
        });

        $('#transferred_data tbody').off('click', '.btn-edit').on('click', '.btn-edit', function (e) {
            e.preventDefault();
            var data = table_transfer.row($(this).closest('tr')).data();
            if (!data) return;
            open_ticket_modal(data);
        });
    }

    function open_ticket_modal(data) {
        var tid = data['ticket_no'];
        $('#status').html(window.originalStatusOptions);

        $('#subjct').attr('readonly', true);
        $('#ticket_no').val(data['ticket_no']);
        $('#str_num').val(data['store']);
        $('#store').val(data['store']);
        $('#date_createdx').val(data['date_created']);
        $('#subjct').val(data['subject']);
        $('#concern').val(data['concern']);
        $('#priority_desc').val(data['priority_desc']);
      $('#f_deptsel').val(data['f_deptsel']);
        $('#via').val(data['via']);
        $('#status').val(data['status']);
        $('#it_num').val(data['itsup']);

        if (data['f_deptsel'] && $('#f_deptsel option[value="' + data['f_deptsel'] + '"]').length === 0) {
            $('<option>', {
                value: data['f_deptsel'],
                text: data['dept_desc'] ? data['dept_desc'] : 'Dept ID ' + data['f_deptsel'],
                class: 'temp-option'
            }).appendTo('#f_deptsel');
        }

        $('#f_deptsel').val(data['f_deptsel']);
        
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
$('#cl_desc').val(data['close_by_desc'] ? data['close_by_desc'] : data['close_by']);
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

    $('#store_graph_modal').modal('hide'); 

    if(typeof admin_hideshowforms === "function") admin_hideshowforms();  

    const yr = $("#yearpicker").val();
    getdata(yr);
    getdata_transfer(yr); // Init transfer data
    get_card_data(yr);

    /**
     * Get card data.
     */
    function get_card_data(yr) {
        $.post(
            'fetchdata/fetch_data.php',
            {
                yr: yr,
                dept_id: $('#dept_id').val(),
                mode: 'yearch'
            },
            function (data) {
                let card_data = jQuery.parseJSON(data);
                const a = card_data;
                $('#count_total').html(a[0].total_res);

                $('#count_assigned').html(a[0].assigned_res);
                $('#count_onprocess').html(a[0].onprocess_res);
                $('#count_pending').html(a[0].pending_res);
                $('#count_nonesca').html(a[0].nonesca_res);
                $('#count_subforclosing').html(a[0].subforclosing_res);
                $('#count_closed').html(a[0].closed_res);
            }
        );
    }

    $(function () {
        $('#datetimepicker1, #datetimepicker2, #datetimepicker3').datetimepicker()
    });

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

    $('#add_button').click(function(){
        $('#report_form').trigger('reset');
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
        $('#msgbtn').hide();
        $("#userModal").on('hidden.bs.modal', function(){

        });
        $('#userModal').modal({backdrop: 'static', keyboard: false}) 
        $("#userModal").on('hidden.bs.modal', function(){
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
        else if (Status == 'ASSIGNED'){
            if (DateClosed < DateCreated ){
                alert("Date closed should be greater than date created!");
                return false;
            }
        }
        else if (DateClosed > today ){
            alert("Invalid Closed_Date");
            return false;
        }

        if (
            Store != "" &&
            DateCreated != "" &&
            Concern != "" &&
            Status != "" &&
            Via != "" &&
            ItSupport != "" &&
            cat_id != "" &&
            sub_id != ""
        ) {
            $.ajax({
                url: "insert.php",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function (data) {
                    Swal.fire({
                         icon: 'success',
                         title: 'Your work has been saved',
                         showConfirmButton: false,
                         timer: 1500
                    });
                    $("#userModal").modal("hide");
                    
                    getdata(yr);
                    get_card_data(yr);
                },
            });
        } else {
            alert("All Fields are Required");
        }
    });

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
            $('#operation').val("Edit");
            $('#msgbtn').val("show");
            $('#msg_thread').hide('slow');
        }
    });

    $('#btnClose').click(function(){
        $('report_form')[0].reset();
        $('.dv_msg').hide();
        $('#remarks_view').hide();
        $('#tmpsubid').remove();
        $('#addmsg').val('');
    });

    let activeCall = null; 

    function formatDuration(ms){
        const totalSec = Math.floor(ms / 1000);
        const m = String(Math.floor(totalSec / 60)).padStart(2,'0');
        const s = String(totalSec % 60).padStart(2,'0');
        return `${m}:${s}`;
    }

    function showEndCallSwal(){
        if(!activeCall) return;

        let timerInterval = null;

        Swal.fire({
            title: 'End Call',
            html: `
              <div style="font-size:14px; margin-bottom:8px;">
                <b>Duration:</b> <span id="callDuration">00:00</span>
              </div>

              <select id="callStatus" class="swal2-select">
                <option value="ANSWERED">Answered</option>
                <option value="NO_ANSWER">No Answer</option>
                <option value="BUSY">Busy</option>
                <option value="FAILED">Failed</option>
                <option value="VOICEMAIL">Voicemail</option>
              </select>
            `,
            showCancelButton: true,
            confirmButtonText: 'Hang Up & Save',
            cancelButtonText: 'Not yet',
            allowOutsideClick: false,
            didOpen: () => {
                const durEl = document.getElementById('callDuration');
                timerInterval = setInterval(() => {
                    durEl.textContent = formatDuration(Date.now() - activeCall.start_ms);
                }, 500);
            },
            willClose: () => {
                if(timerInterval) clearInterval(timerInterval);
            },
            preConfirm: () => {
                const status = document.getElementById('callStatus').value;
                return status;
            }
        }).then((result) => {
            if(result.isConfirmed){
                const status = result.value;

                $.ajax({
                    url: 'fetchdata/update_call.php',
                    type: 'POST',
                    data: {
                        call_id: activeCall.call_id,
                        call_status: status
                    },
                    success: function(){
                        Swal.fire('Saved', 'Call log updated.', 'success');
                        activeCall = null;
                    },
                    error: function(xhr){
                        Swal.fire('Error', xhr.responseText || 'Failed to update call log.', 'error');
                    }
                });
            }
        });
    }

    $(document).on('click', '.viber-call', function(e){
        e.preventDefault();

        const ticket_no = $(this).data('ticket_no');
        const dept_id   = $(this).data('dept_id');
        const number    = $(this).data('number');

        $.ajax({
            url: 'fetchdata/log_call.php',
            type: 'POST',
            dataType: 'json',
            data: { ticket_no, dept_id },
            success: function(res){
                activeCall = {
                    call_id: res.call_id,
                    start_ms: Date.parse(res.call_startdate) || Date.now()
                };
                window.location.href = `viber://chat?number=%2B${number}`;
            }
        });
    });

    window.addEventListener('focus', function(){
        if(activeCall){
            showEndCallSwal();
        }
    });

});
</script>