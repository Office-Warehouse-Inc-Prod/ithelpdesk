
<!-- // DEVELOPMENT NOTE: -->
<!-- Main Dev:
Derek Alejo
CJ Gutana

Enhancemennts and Fixes:
Mark Allan Espinal 
OJT - Karl Magpayo -->


<!-- // Enhancements: -->
<!-- - Refined navbar styling for a more modern and cohesive look.
- Implemented dynamic notification badge updates for new reports and messages.
- Added smooth scrolling and highlight effects when navigating to specific tickets from notifications.
- Optimized AJAX calls for fetching notifications and counts to reduce server load and improve responsiveness.
Minor Fixes:
- Fixed potential JavaScript errors when notification data is empty or malformed. -->

<?php
session_start();
if ($_SESSION['login'] != 'true') {
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>OWI HELPDESK</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../images/owi.ico" type="image/icon type">
  <link rel="stylesheet" href="../css/4bootstrap.min.css" />
  <link rel="stylesheet" href="../vendor/sweetalert/dist/sweetalert2.min.css" />
  <script src="../js/jquery-3.5.1.js"></script>
  <script src="../js/moment.min.js"></script>
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" type="text/css" href="../dist/fontawesome/css/fontawesome.min.css" />
  <script src="https://kit.fontawesome.com/426b4bab4c.js" crossorigin="anonymous"></script>
  <script src="../js/jquery.timeago.js"></script>
  <script src="../js/helpdesk.js"></script>
  <script src="../js/coms.js"></script>
  <script src="../js/amcharts/core.js"></script>
  <script src="../js/amcharts/charts.js"></script>
  <script src="../js/amcharts/material.js"></script>
  <script src="../js/amcharts/animated.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/4bootstrap.min.js"></script>
  <script src="../vendor/sweetalert/dist/sweetalert2.all.min.js"></script>
  <link rel="stylesheet" href="../plugins/DataTables-1.10.25/media/css/dataTables.bootstrap.min.css" />
  <link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.css" />
  <link rel="stylesheet" href="../assets/Date-Time-Picker-Bootstrap-4/src/sass/bootstrap-datetimepicker-build.css" />
  <link href="https://fonts.googleapis.com/css2?family=Edu+NSW+ACT+Foundation&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

  <script src="../assets/Date-Time-Picker-Bootstrap-4/src/js/bootstrap-datetimepicker.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600&display=swap" rel="stylesheet">
  <script src="../plugins/DataTables-1.10.25/media/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.js"></script>
  <script src="../js/ellipsis.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../dist/select2/dist/css/select2.min.css" />
  <script src="../dist/select2/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
</head>

<style>
  :root {
  --primary-color: #E1AD01;
  --primary-light: #F4F0FF;
  --bg-body: #F4F5FA;
  --sidebar-width: 260px;
  --topbar-height: 70px;
  --card-shadow: 0 4px 12px 0 rgba(58, 53, 65, 0.1);
}

body {
  font-family: 'Poppins', sans-serif;
  color: #3A3541DE;
  overflow-x: hidden;
  min-height: 100vh;
}

.owi-navbar {
  background: linear-gradient(135deg, #213456, #334c7a) !important;
  box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.15);
  padding: 0.5rem 1.5rem !important;
  transition: all 0.3s ease;
}

.navbar-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  font-family: 'Orbitron', sans-serif;
  font-size: 1.4rem;
  letter-spacing: 1.5px;
  font-weight: 600;
}

.owi-navbar .nav-link,
.owi-navbar .navbar-brand {
  color: #ffffff !important;
  font-weight: 500;
  letter-spacing: .3px;
}

.owi-navbar .nav-item,
.owi-navbar .nav-link,
.owi-navbar .nav-item.active,
.owi-navbar .nav-item.active .nav-link,
.owi-navbar .nav-link.active {
  border-bottom: 0px solid transparent !important;
  border-top: 0px solid transparent !important;
  text-decoration: none !important;
  box-shadow: none !important;
}

.owi-navbar .nav-link {
  position: relative;
  padding: 0.8rem 1rem !important;
  color: rgba(255, 255, 255, 0.85) !important;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: all 0.3s ease;
}

.owi-navbar .nav-link i {
  font-size: 1.05rem;
  color: inherit !important; 
}

.owi-navbar .badge {
  position: absolute;
  top: 4px;
  right: 0px;
  font-size: 0.65rem;
  padding: 3px 6px;
  border-radius: 50rem;
  font-weight: 700;
  line-height: 1;
}

.owi-navbar .badge-danger {
  background-color: #ff4d4d !important;
  color: #fff !important;
}

.owi-navbar .badge-info {
  background-color: #28c7ff !important;
  color: #002a4a !important;
}

.owi-navbar .nav-item {
  position: relative;
  margin: 0 3px;
}

.owi-navbar .nav-link::after {
  content: '';
  position: absolute;
  width: 0;
  height: 3px;
  bottom: 0px; 
  left: 50%;
  background-color: var(--primary-color) !important;
  transition: width 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), left 0.3s ease;
  transform: translateX(-50%);
  border-radius: 10px;
}

.owi-navbar .nav-item:hover .nav-link {
  color: #E1AD01 !important;
  opacity: 1;
}

.owi-navbar .nav-item:not(.dropdown):hover .nav-link::after {
  width: 70%; 
}

.owi-navbar .nav-item.active .nav-link {
  color: var(--primary-color) !important;
  font-weight: 700;
}

.owi-navbar .nav-item.active:not(.dropdown) .nav-link::after {
  width: 70% !important; 
  background-color: var(--primary-color) !important;
}

.owi-navbar .dropdown-menu {
  background-color: #ffffff !important;
  border: none !important;
  min-width: 240px;
  padding: 0.5rem !important;
  box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
  border-radius: 8px !important;
  border-top: 4px solid var(--primary-color) !important;
  margin-top: 5px !important;
}

.owi-navbar .dropdown-item {
  color: #1f2937 !important;
  background-color: transparent !important;
  border-radius: 6px;
  padding: 0.6rem 0.9rem;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: all 0.2s ease;
}

.owi-navbar .dropdown-item i {
  width: 20px;
  text-align: center;
  color: #4b5563 !important; 
  transition: color 0.2s ease;
}

.owi-navbar .dropdown-item:hover {
  background-color: #b5c1d7 !important;
  color: linear-gradient(135deg, #213456, #334c7a) !important;
}

.owi-navbar .dropdown-item:hover i {
  color: linear-gradient(135deg, #213456, #334c7a) !important; 
}

.owi-navbar .change-pass {
  color: #1e40af !important;
}
.owi-navbar .change-pass i {
  color: #1e40af !important;
}
.owi-navbar .change-pass:hover {
  background-color: #eff6ff !important;
}

.owi-navbar .logout-btn {
  color: #dc2626 !important;
  font-weight: 600;
}
.owi-navbar .logout-btn i {
  color: #dc2626 !important;
}
.owi-navbar .logout-btn:hover {
  background-color: #fef2f2 !important;
}

.owi-navbar .dropdown-divider {
  border-top: 1px solid #e5e7eb !important;
  margin: 0.4rem 0;
}

.notif-dropdown {
  width: 360px;
  max-width: 92vw;
}

.owi-navbar .navbar-toggler {
  border-color: rgba(255,255,255,0.25);
  padding: 0.4rem 0.6rem;
}

.owi-navbar .navbar-toggler-icon {
  filter: brightness(0) invert(1);
}

  .notification-dropdown-wrapper {
  position: relative;
}

.notif-bell-icon {
  font-size: 1.25rem;
  color: #ffffff;
  transition: color 0.2s ease;
}
.nav-link:hover .notif-bell-icon {
  color: #E1AD01;
}

.notif-badge {
  position: absolute;
  top: 4px;
  right: 2px;
  font-size: 0.65rem;
  font-weight: 700;
  padding: 0.25em 0.5em;
  border-radius: 50rem;
  border: 2px solid #fff; 
  transform: translate(20%, -20%);
}
.notif-badge:empty {
  display: none;
}

.modern-notif-dropdown {
  width: 340px;
  max-width: 90vw;
  padding: 0;
  border-radius: 12px !important; 
  margin-top: 10px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
}

.notif-scroll-area {
  max-height: 400px;
  overflow-y: auto;
}
.notif-scroll-area::-webkit-scrollbar {
  width: 5px;
}
.notif-scroll-area::-webkit-scrollbar-track {
  background: transparent;
}
.notif-scroll-area::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}

.style-clean-table {
  border-collapse: collapse;
}
.style-clean-table tr {
  border-bottom: 1px solid #f1f5f9;
  transition: background-color 0.15s ease;
}
.style-clean-table tr:last-child {
  border-bottom: none;
}
.style-clean-table tr:hover {
  background-color: #f8fafc;
}

.notif-cell {
  padding: 12px 16px !important;
  display: block;
}
.notif-cell.unread {
  background-color: #f0f9ff;
}
.notif-cell.unread:hover {
  background-color: #e0f2fe;
}

.notif-text {
  font-size: 0.875rem;
  color: #334155;
  line-height: 1.4;
  white-space: normal; 
}
.notif-date {
  font-size: 0.75rem;
  display: block;
  margin-top: 4px;
  color: #94a3b8 !important;
}

#notif_dataxx thead {
  display: none;
}

#notif_dataxx td {
  padding: 0 !important;
  border-top: none !important;
}

.custom-notif-icon-badge {
  background-color: linear-gradient(135deg, #213456, #334c7a) !important;
  color: #ffffff !important;
  width: 32px;
  height: 32px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%; 
  font-size: 0.85rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.notif-cell {
  padding: 12px 16px !important;
}
</style>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark owi-navbar sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="adminpanel.php">
        <img src="../images/owi.ico" width="35" height="35" class="d-inline-block align-top" alt="OWI Logo">
        <span>OWI <span style="color: var(--primary-color);">HELPDESK</span></span>
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="adminpanel.php">
              <i class="fa fa-home"></i> HOME
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="adminwfit.php">
              <i class="fa fa-envelope-o">
                <span class="badge badge-danger" id="notif_newrep"></span>
              </i>
              NEW REPORTS
            </a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="genReportDrop" role="button" data-toggle="dropdown">
              <i class="fa fa-chart-line"></i> REPORTS
            </a>
            <div class="dropdown-menu" aria-labelledby="genReportDrop">
              <a class="dropdown-item" href="genrep_bycat.php">
                <i class="fa fa-calendar-day"></i> By Categories
              </a>
              <a class="dropdown-item" href="performance.php">
                <i class="fa-solid fa-arrow-trend-up"></i> IT Performance
              </a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="maintDrop" role="button" data-toggle="dropdown">
              <i class="fa fa-sliders-h"></i> MAINTENANCE
            </a>
            <div class="dropdown-menu" aria-labelledby="maintDrop">
              <a class="dropdown-item" href="user_maintenance.php"><i class="fas fa-user-cog"></i> User Maintenance</a>
              <a class="dropdown-item" href="store_maintenance.php"><i class="fas fa-store"></i> Store Maintenance</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="admincreateticket.php">
              <i class="fa fa-plus-circle" style="color: var(--primary-color);"></i> CREATE TICKET
            </a>
          </li>
        </ul>

        <ul class="navbar-nav ml-auto">
         <li class="nav-item dropdown notification-dropdown-wrapper">
            <a class="nav-link position-relative d-inline-block p-2" href="#" id="notifDrop" role="button" data-toggle="dropdown">
              <i class="fa fa-bell notif-bell-icon"></i>
              <span class="badge badge-info notif-badge" id="notif_newmsg"></span>
            </a>
            
            <div class="dropdown-menu dropdown-menu-right modern-notif-dropdown shadow-lg border-0" aria-labelledby="notifDrop">
              <div class="dropdown-header text-dark py-3 border-bottom">
                <h6 class="m-0 font-weight-bold">Recent Notifications</h6>
              </div>

              <div class="notif-scroll-area">
                <table id="notif_dataxx" class="table table-sm mb-0 style-clean-table" style="width:100%;">
                
                </table>
              </div>
            </div>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown">
              <img class="rounded-circle border border-light" src="../images/users/<?= $_SESSION['imguser']; ?>" alt="User" style="width: 30px; height: 30px; object-fit: cover;">
              <span class="ml-2 d-none d-lg-inline">
                <?php echo $_SESSION['fname'] . ' ' . $_SESSION['lstname']; ?>
              </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
              <a class="dropdown-item" href="change_password.php"><i class="fas fa-key mr-2"></i>Change Password</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item text-danger" href="../logout.php"><i class="fas fa-sign-out-alt mr-2"></i>Log Out</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <script type="text/javascript">
    $(document).ready(function () {
      countnewrep();
      countNwMsg();

      // Initial Fetch
      getdata();
      setInterval(getdata, 1000);

      /**
       * Getdata.
       */
      function getdata() {
        $.post('fetchdata/fetch_data.php', { mode: 'notif_support' }, function (data) {
          notifdatas(data);
        }, 'json');
      }

     
   var table;
      /**
       * Notifdatas.
       */
      function notifdatas(t) {
        const dataset = t.ntfsupdata || [];
        table = $("#notif_dataxx").DataTable({
          "dom": '<"pull-left"lf><"pull-right">tip',
          "pagingType": "full_numbers",
          "bDestroy": true,
          "responsive": true, 
          "lengthChange": false, 
          "autoWidth": false,
          "bInfo": false,
          "bFilter": false,
          "paging": false,
          "select": true,
          "pageLength": 10,
          "language": {
            "emptyTable": "No new Notification"
          },
          "data": dataset,
        "columns": [
  { 
    title: "NOTIFICATION", 
    data: null, 
    "defaultContent": "",
    "render": function (data, type, row) {
      let timeAgo = '';
      if (row.notif_date) {
        timeAgo = moment(row.notif_date).fromNow(); 
      }

      let iconHtml = '';
      switch (String(row.notif_val)) {
        case '1':
          iconHtml = '<span class="custom-notif-icon-badge"><i class="fa-solid fa-ticket"></i></span>';
          break;
        case '2':
          iconHtml = '<span class="custom-notif-icon-badge"><i class="fa-solid fa-comment"></i></span>';
          break;
        case '3':
          iconHtml = '<span class="custom-notif-icon-badge"><i class="fa-solid fa-circle-check"></i></span>';
          break;
        case '4':
          iconHtml = '<span class="custom-notif-icon-badge"><i class="fa-solid fa-spinner fa-spin-pulse"></i></span>';
          break;
        case '5':
          iconHtml = '<span class="custom-notif-icon-badge"><i class="fa-solid fa-boxes-stacked"></i></span>';
          break;
           case '6':
          iconHtml = '<span class="custom-notif-icon-badge"><i class="fa-solid fa-boxes-stacked"></i></span>';
          break;
           case '7':
          iconHtml = '<span class="custom-notif-icon-badge"><i class="fa-solid fa-boxes-stacked"></i></span>';
          break;
           case '8':
          iconHtml = '<span class="custom-notif-icon-badge"><i class="fa-solid fa-boxes-stacked"></i></span>';
          break;
           case '9':
          iconHtml = '<span class="custom-notif-icon-badge"><i class="fa-solid fa-boxes-stacked"></i></span>';
          break;
        default:
          iconHtml = '<span class="custom-notif-icon-badge"><i class="fa-solid fa-bell"></i></span>';
          break;
      }

      return `
        <div class="notif-cell d-flex align-items-start">
          <div class="mt-1 me-2">${iconHtml}</div>
          <div class="flex-grow-1">
            <div class="notif-text">${row.notif_data}</div>
            <span class="notif-date text-muted"><i class="fa-regular fa-clock mr-1"></i>${timeAgo}</span>
          </div>
        </div>
      `;
    }
  }
],
          "columnDefs": [
            {
              targets: 0,
              className: 'bolded'
            }
          ],
          "initComplete": function() {
            handleUrlTicketHighlight();
          }
        });
        
        $('#notif_dataxx tbody').off('click', 'tr').on('click', 'tr', function () {
          var data = table.row(this).data();
          if (!data) return;

          var ticketVal = data.ticket_no;
          var notifVal = data.notif_val;
          var ticketStatus = data.status ? data.status.toUpperCase().trim() : '';

          $('#myInput').val(ticketVal).trigger('input');
          $.post('change_notif.php', { ticketVal: ticketVal }, function (response) {
            getdata();
          });

          if (notifVal == '1') {
            window.location.href = "adminwfit.php?ticket_no=" + encodeURIComponent(ticketVal);
          } 
          else if (notifVal == '2') {
            if (ticketStatus === 'ON PROCESS') {
              window.location.href = "adminpanel.php?ticket_no=" + encodeURIComponent(ticketVal) + "#report_data";
            } 
            else if (ticketStatus === 'ASSIGNED') {
              window.location.href = "adminwfit.php?ticket_no=" + encodeURIComponent(ticketVal);
            }
          }
          else if(notifVal == '5'){
            window.location.href = "fix_asset.php?ticket_no=" + encodeURIComponent(ticketVal);
          }
        });
      }

      /**
       * Get url param.
       */
      function getUrlParam(param) {
        var urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
      }

      var targetTicket = getUrlParam('ticket_no');

      if (targetTicket && typeof reptable !== 'undefined') {
        setTimeout(function() {
          var foundRow = null;

          reptable.rows().every(function (rowIdx, tableLoop, rowLoop) {
            var rowData = this.data();
            if (rowData && rowData.ticket_no == targetTicket) {
              foundRow = this.node();
            }
          });

          if (foundRow) {
            $(foundRow).find('button[name="update"]').trigger('click');

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
      }

      /**
       * Handle url ticket highlight.
       */
      function handleUrlTicketHighlight() {
        const urlParams = new URLSearchParams(window.location.search);
        const ticketNo = urlParams.get('ticket_no');
        
        if (ticketNo && window.location.hash === '#report_data') {
          let reportElement = $('#report_data');
          
          if (reportElement.length) {
            $('html, body').animate({
              scrollTop: reportElement.offset().top - 100
            }, 800, function() {
              let matchedRow = $(`tr:contains('${ticketNo}')`);
              if (matchedRow.length) {
                matchedRow.addClass('highlight-row');
                setTimeout(() => {
                  matchedRow.removeClass('highlight-row');
                }, 2500);
              } else {
                reportElement.css('transition', 'background-color 0.5s');
                reportElement.css('background-color', '#ffff99');
                setTimeout(() => { reportElement.css('background-color', 'transparent'); }, 1200);
              }
            });
          }
        }
      }
    });

    document.addEventListener("DOMContentLoaded", function () {
      setInterval(getNewReportCount, 5000);
    });

    async function getNewReportCount() {
      try {
        const response = await fetch("fetchdata/notif_newrep.php?_=" + Date.now());
        const count = (await response.text()).trim();
        const badge = document.getElementById("notif_newrep");
        if (!badge) return;

        if (count === "0" || count === "") {
          badge.style.display = "none";
        } else {
          badge.style.display = "inline-block";
          badge.innerHTML = count;
        }
      } catch (error) {
        console.error("Notification count dev error:", error);
      }
    }

    async function getTransferCount() {
      try {
        const xcall = await fetch("fetchdata/notif_transfer.php?_=" + Date.now());
        const xcount = (await xcall.text()).trim();
        const xbadge = document.getElementById("notif_transfer");
        if (!xbadge) return;

        if (xcount === "0" || xcount === "") {
          xbadge.style.display = "none";
        } else {
          xbadge.style.display = "inline-block";
          xbadge.innerHTML = xcount;
        }
      } catch (error) {
        console.error("Notification count error:", error);
      }
    }

    /**
     * Countnewrep.
     */
    function countnewrep() {
      setInterval(function () {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            var badge = document.getElementById("notif_newrep");
            if (badge) badge.innerHTML = this.responseText;
          }
        };
        xhttp.open("GET", "fetchdata/notif_newrep.php", true);
        xhttp.send();
      }, 1000);
    }

    /**
     * Count nw msg.
     */
    function countNwMsg() {
      setInterval(function () {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            var badge = document.getElementById("notif_newmsg");
            if (badge) badge.innerHTML = this.responseText;
          }
        };
        xhttp.open("GET", "fetchdata/fetch_newmsg.php", true);
        xhttp.send();
      }, 1000);
    }

    var currentUrl = window.location.pathname.split("/").pop();
    if (currentUrl === "" || currentUrl === "index.php") {
      currentUrl = "adminpanel.php";
    }

    $('.navbar-nav .nav-item').each(function () {
      var $this = $(this);
      var linkHref = $this.find('a').attr('href');
      $this.removeClass('active');

      if (linkHref === currentUrl) {
        $this.addClass('active');
      }

      if ($this.hasClass('dropdown')) {
        $this.find('.dropdown-item').each(function () {
          if ($(this).attr('href') === currentUrl) {
            $this.addClass('active');
          }
        });
      }
    });
  </script>