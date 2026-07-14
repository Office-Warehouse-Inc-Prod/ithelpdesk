<?php
$inactive = 180;

if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > $inactive)){
    session_unset();
    session_destroy();
    header("Location: adminpanel.php");
    exit();
}

$_SESSION['start'] = time();
  
include 'admin.php';
include '../condb.php';

$con1 = new dbconfig();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mode']) && $_POST['mode'] === 'fa_tbl') {
    try {
        $sql = "SELECT 
                    r.ticket_no, 
                    r.date_created, 
                    r.concern, 
                    r.service_desc, 
                    r.subject,
                    GROUP_CONCAT(i.files_name SEPARATOR '|') AS attachment_files,
                    r.sub_id,
                    r.f_deptsel,
                    r.itsup,
                    r.store
                FROM reports r
                LEFT JOIN images i ON r.ticket_no = i.ticket_no
                WHERE r.status = 'Assigned' 
                GROUP BY r.ticket_no
                ORDER BY r.date_created DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['fadata' => $results]);
        
    } catch (Exception $e) {
        echo json_encode(['fadata' => [], 'error' => $e->getMessage()]);
    }
    
    exit; 
}
?>

<head>
   <link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css"/>
      <script src="../js/bootstrap-datetimepicker.min.js"></script>

      <link rel="stylesheet" href="../css/jquery.dataTables.min.css" />
      <script src="../js/jquery.dataTables.min.js"></script>
      <script src="../js/dataTables.select.min.js"></script>
      <script src="../js/dataTables.responsive.min.js"></script>
      <script src="../js/fnReloadAjax.js"></script>
    
</head>
<style>

.change-pass {
    color: #1e3a8a !important; 
    font-weight: 500;
}

.change-pass:hover {
    background-color: #f1f5ff;
    color: #1e3a8a !important;
}

/* Logout */
.logout-btn {
    color: #dc2626 !important; 
    font-weight: 600;
}

.logout-btn:hover {
    background-color: #ffe5e5;
    color: #dc2626 !important;
}


.dropdown-menu .dropdown-item {
    color: #1f2937 !important; 
}

.dropdown-menu .change-pass {
    color: #1e3a8a !important; 
}

.dropdown-menu .logout-btn {
    color: #dc2626 !important; 
    font-weight: 600;
}

.dropdown-menu .dropdown-item:hover {
  background-color: #405f9e;
    color: inherit !important;
}

.dropdown-menu .dropdown-item i {
    width: 18px;
    margin-right: 8px;
    color: inherit !important;
}

.change-pass {
    color: #1e40af !important; 
}

.logout-btn {
    color: #dc2626 !important;
    font-weight: 600;
}


:root {
  --primary-color: #E1AD01;
  --primary-light: #F4F0FF;
  --bg-body: #F4F5FA;
  --sidebar-width: 260px;
  --topbar-height: 70px;
  --card-shadow: 0 4px 12px 0 rgba(58, 53, 65, 0.1);
  --theme-color: #213456;
}

body {
  font-family: 'Public Sans', sans-serif;
  background-color: var(--bg-body);
  color: #3A3541DE;
  overflow-x: hidden;
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  background-repeat: no-repeat;
  min-height: 100vh;
}

.owi-navbar {
  
    background-color: #213456 !important;
  box-shadow: 0 2px 10px 2px #66738e;
  margin-bottom: 40px;
}

/* Make links clean + readable */
.owi-navbar .nav-link,
.owi-navbar .navbar-brand {
  color: #fff !important;
  font-weight: 600;
  letter-spacing: .3px;
}

.owi-navbar .nav-link i {
  margin-right: 6px;
}

.owi-navbar .nav-link:hover,
.owi-navbar .navbar-brand:hover {
  opacity: .92;
}

.owi-navbar .dropdown-menu {
  background-color: #ffffff;
  border: none;
  min-width: 220px;
  padding: .35rem;
  box-shadow: 0 12px 24px rgba(0,0,0,0.25);
  border-radius: 12px;
}

.owi-navbar .dropdown-item {
  color: black;
  
  background-color: #ffffff;
  border-radius: 10px;
  padding: .55rem .75rem;
  white-space: normal; 
}

.owi-navbar .dropdown-item i {
  margin-right: 8px;
}


.owi-navbar .dropdown-item:hover {
  background-color: #768cc5;
  color: #fff;
}

.owi-navbar .dropdown-divider {
  border-top: 1px solid rgba(255,255,255,0.2);
}
.notif-dropdown {
  width: 360px;
  max-width: 92vw;
}

@media (max-width: 576px) {
  .notif-dropdown {
    width: 92vw;
  }
}

.owi-navbar .badge-danger {
  background-color: #ff4d4d;
}

.owi-navbar .badge-info {
  background-color: #28c7ff;
  color: #002a4a;
  font-weight: 700;
}

.owi-navbar .navbar-toggler {
  border-color: rgba(255,255,255,0.35);
}

.owi-navbar .navbar-toggler-icon {
  filter: brightness(0) invert(1);
}

.owi-navbar .nav-item {
  position: relative;
  margin: 0 5px;
  display: flex;
  align-items: center;
}

.owi-navbar .nav-link {
  position: relative;
  padding: 0.8rem 1rem !important;
  color: rgba(255, 255, 255, 0.8) !important;
  transition: all 0.3s ease;
}

.owi-navbar .nav-link::after {
  content: '';
  position: absolute;
  width: 0;
  height: 3px;
  bottom: 5px; 
  left: 50%;
  background-color: var(--primary-color);
  transition: width 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), left 0.3s ease;
  transform: translateX(-50%);
  border-radius: 10px;
}

.owi-navbar .nav-item:hover .nav-link {
  color: #fff !important;
}

.owi-navbar .nav-item:hover .nav-link::after {
  width: 70%; 
}

.owi-navbar .nav-item.active .nav-link {
  color: var(--primary-color) !important;
  font-weight: 700;
}

.owi-navbar .nav-item.active .nav-link::after {
  width: 70%; 
  background-color: var(--primary-color);
}

.owi-navbar .dropdown-menu {
  border-top: 3px solid var(--primary-color) !important;
  border-radius: 0 0 8px 8px !important;
  margin-top: 0;
}
.navbar-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  font-family: 'Orbitron', sans-serif;
  font-size: 1.4rem;
  letter-spacing: 1px;
}

.navbar-brand img {
  transition: transform 0.3s ease;
}

.navbar-brand:hover img {
  transform: rotate(-10deg) scale(1.1);
}

.owi-navbar .dropdown-menu {
  border-top: 3px solid var(--primary-color);
  margin-top: 10px;
}
.owi-navbar .dropdown-menu:hover {
  border-top: 3px solid var(--primary-color);
  margin-top: 10px;
  background-color: #213456;
}

  #fix_asset_table {
    background-color: #ffffff;
    border-collapse: collapse;
    border-spacing: 0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 10px 8px rgba(108, 108, 53, 0.4);
    border: none;
  }

  #fix_asset_table thead th {
    background-color: var(--theme-color);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    padding: 15px;
    border: none;
  }

  #fix_asset_table tbody td {
    padding: 12px 15px;
    vertical-align: middle;
    color: #333;
    border: none;
    border-bottom: 1px solid var(--theme-color);
  }

  #fix_asset_table tbody tr:hover {
    background-color: #bec5d1 !important;
    color: #ffffff !important;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .table-responsive {
    border-radius: 8px;
    margin-top: 20px;
  }
  #fa_Modal .modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
  }

  #fa_Modal .modal-header {
    background-color: #213456;
    color: #fff;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    border-bottom: 4px solid #E1AD01; 
  }

  #fa_Modal .modal-title {
    font-weight: 700;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
  }

  #fa_Modal .input-group-text {
    background-color: #f8f9fa;
    border-right: none;
    color: #213456;
  }

  #fa_Modal .form-control {
    border-left: none;
    height: 45px;
    border-radius: 0 8px 8px 0;
  }

  #fa_Modal .form-control:focus {
    border-color: #ced4da;
    box-shadow: none;
  }

  #fa_Modal .input-group:focus-within {
    box-shadow: 0 0 0 0.2rem rgba(225, 173, 1, 0.25);
    border-radius: 8px;
  }

  #fa_Modal .border-right {
    border-right: 1px solid var(--theme-color) !important;
  }

  #msgbtn {
    background-color: #E1AD01;
    border: none;
    color: #213456;
    font-weight: 700;
    padding: 10px 40px;
    border-radius: 30px;
    transition: all 0.3s ease;
  }

  #msgbtn:hover {
    background-color: #213456;
    color: #E1AD01;
    transform: translateY(-2px);
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

  :root {
    --navy:#213456;
    --navy2:#1a2a4a;
    --yellow:#EAAA00;
    --bg:#EEF2F7;
    --card:#ffffff;
    --card2:#F8FAFF;
    --text:#111827;
    --muted:#6B7280;
    --line:#E5E7EB;
    --shadow: 0 14px 34px rgba(17,24,39,.10);
    --radius:18px;
    --radius-sm:14px;
    --focus: 0 0 0 .2rem rgba(234,170,0,.18);
  }
  
body {
  background: linear-gradient(to bottom, #ffffff, #99aac8);
  background-attachment: fixed; 
  margin: 0; 
  height: 100vh; 
} 

  .container.mt-3 { padding-top: 10px; padding-bottom: 24px; }

  #fix_asset_table { width:100% !important; }

  .table-wrap {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 14px;
  }

  .dataTables_wrapper {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 14px;
  }

  .dataTables_wrapper .dataTables_length label,
  .dataTables_wrapper .dataTables_filter label,
  .dataTables_wrapper .dataTables_info {
    color: var(--muted) !important;
    font-weight: 600;
  }

  .dataTables_wrapper .dataTables_filter input:focus,
  .dataTables_wrapper .dataTables_length select:focus {
    box-shadow: var(--focus) !important;
    border-color: rgba(234,170,0,.45) !important;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 12px !important;
    border: 1px solid transparent !important;
    color: var(--text) !important;
    background: transparent !important;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    border-color: var(--line) !important;
    background: #F8FAFC !important;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: rgba(234,170,0,.18) !important;
    border-color: rgba(234,170,0,.35) !important;
  }

  table.dataTable {
    border-collapse: collapse !important;
    border-spacing: 0 !important;
  }

  table.dataTable thead th {
    color: white !important;
    font-weight: 900;
    letter-spacing: .04em;
    text-transform: uppercase;
    border: none !important;
    background: var(--theme-color) !important;
    padding: 14px 12px !important;
  }

  table.dataTable tbody tr {
    background: #ffffff !important;
    border: none !important;
    box-shadow: none !important;
  }

  table.dataTable tbody td {
    border: none !important;
    border-bottom: 1px solid var(--theme-color) !important;
    color: rgba(17,24,39,.85) !important;
    padding: 14px 12px !important;
  }

  table.dataTable tbody tr:hover {
    transform: none !important;
    background: #bec5d1 !important;
  }

  table.dataTable tbody tr td:first-child,
  table.dataTable tbody tr td:last-child {
    border-radius: 0 !important;
  }
  .modal-content {
    border: 1px solid var(--line) !important;
    border-radius: var(--radius) !important;
    background: #ffffff !important;
    box-shadow: 0 22px 60px rgba(17,24,39,.18);
  }

  .modal-header {
    border-bottom: 3px solid var(--yellow) !important;
    padding: 16px 18px !important;
    background: #213456 !important;
    color: white;
  }

  .modal-title {
    font-size: 16px;
    font-weight: 900;
    letter-spacing: .02em;
    color: white;
    text-transform: uppercase;
  }

  .modal-body { padding: 18px !important; }
  .modal-footer {
    border-top: 1px solid var(--line) !important;
    padding: 14px 18px !important;
  }
label {
  font-size: 11px;
  font-weight: 900;
  color: #213456;
  letter-spacing: .08em;
  text-transform: uppercase;
  margin-bottom: 6px;
}

input.form-control,
textarea.form-control {
  color: #6c757d !important;
  background-color: transparent !important; 
  border: none !important; 
  border-bottom: 1px solid #213456 !important; 
  border-radius: 0px !important; 
  resize: none !important; 
}

select.custom-select-placeholder.placeholder-active,
textarea.form-control.custom-select-placeholder:placeholder-shown {
  color: red !important;
  border: 1px solid #ced4da !important;
  border-radius: .2rem !important;
  background-color: #fff !important;
}

textarea.form-control.custom-select-placeholder::placeholder {
  color: red !important;
  opacity: 0.7;
}

select.custom-select-placeholder.has-value,
textarea.form-control.custom-select-placeholder:not(:placeholder-shown) {
  color: #212529 !important; 
  border: none !important; 
  border-bottom: 1px solid #213456 !important; 
  border-radius: 0px !important;
  background-color: transparent !important;
}

  .form-control,
  .form-control-sm,
  select.form-control,
  textarea.form-control {
    background: #fff !important;
    border: 1px solid var(--line) !important;
    color: var(--text) !important;
    border-radius: 14px !important;
    padding: 10px 12px !important;
  }

  .form-control:focus,
  .form-control-sm:focus,
  select.form-control:focus,
  textarea.form-control:focus {
    box-shadow: var(--focus) !important;
    border-color: rgba(234,170,0,.45) !important;
  }

  .form-control[readonly],
  textarea[readonly] { opacity: .95; }

  .form-group { margin-bottom: 14px !important; }

  .btn-danger {
    background: rgba(239,68,68,.14) !important;
    border-color: rgba(239,68,68,.28) !important;
    color: #991b1b !important;
  }
  .btn-danger:hover { background: rgba(239,68,68,.18) !important; }

  #msg_thread .card.card-body {
    background: #213456 !important;
    border: 1px solid var(--line) !important;
    border-radius: var(--radius-sm) !important;
  }

  .container_remarks {
    background: #F8FAFF;
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    padding: 12px;
    max-height: 280px;
    box-shadow: 0 20px 60px rgba(123, 128, 44, 0.605);
    overflow: auto;
  }

  #remarks_view ul { list-style: none; padding-left: 0; margin: 0; }

  #remarks_view li {
    padding: 10px 12px;
    border: 1px solid var(--line);
    background: #ffffff;
    border-radius: 14px;
    margin-bottom: 10px;
    box-shadow: 0 10px 18px rgba(17,24,39,.06);
  }

  hr { border-top: 1px solid var(--line) !important; }

  .priority-chip {
    padding:4px 10px;
    border-radius:999px;
    font-weight:900;
    font-size:11px;
    letter-spacing:.05em;
  }
  .p-critical { background: rgba(239,68,68,.14); color:#991b1b; border:1px solid rgba(239,68,68,.25); }
  .p-high {     background: rgba(251,146,60,.14); color:#9a3412; border:1px solid rgba(251,146,60,.25); }
  .p-medium {   background: rgba(234,170,0,.16); color:#7a5200; border:1px solid rgba(234,170,0,.30); }
  .p-low {      background: rgba(34,197,94,.14); color:#166534; border:1px solid rgba(34,197,94,.25); }

  .select2-container--default .select2-selection--single {
    background-color: #ffffff !important;
    border: 1px solid var(--line) !important;
    border-radius: 14px !important;
    height: 42px !important;
    display: flex !important;
    align-items: center !important;
    padding: 4px 10px !important;
    color: var(--text) !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: var(--text) !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 42px !important;
  }

  .select2-dropdown {
    background-color: #ffffff !important;
    color: var(--text) !important;
    border: 1px solid var(--line) !important;
    border-radius: 14px !important;
    box-shadow: 0 18px 40px rgba(17,24,39,.14);
  }
  .select2-results__option { color: var(--text) !important; }
  .select2-results__option--highlighted {
    background: rgba(234,170,0,.16) !important;
    color: var(--text) !important;
  }
  .btn {  
    background-color: white !important;
    border: 2px solid #213456;
    border-color: var(--gold-accent);
    font-weight: 700;
    color: #213456;
  }

  .btn:hover {
    background-color: #16243d !important;
    border-color: var(--gold-accent);
    color: white;
  }

  .btn-success {  
    background-color: #7a5200 !important;
    border: 2px solid #213456;
    font-weight: 700;
    color: white;
  }

  .btn-success:hover {
    background-color: #16243d !important;
    border-color: yellow;
    color: white;
  }

  ::-webkit-scrollbar {
    width: 8px;
  }
  ::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 10px;
  }
  ::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #837031, #E1AD01);
    border-radius: 10px;
  }
  ::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #837031, #E1AD01);
  }

  .placeholder-style {
    color: #6c757d; 
    font-style: italic; 
  }
  
  #dataModal .modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
  }

  #dataModal .modal-header {
    background-color: #213456;
    color: #fff;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    border-bottom: 4px solid #E1AD01;
  }

  #dataModal .modal-title {
    font-weight: 700;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
  }

  #dataModal .input-group-text {
    background-color: #494949;
    border-right: none;
    color: #213456;
  }

  #dataModal .form-control {
    border-left: none;
    height: 45px;
  }

  #dataModal .form-control:focus {
    border-color: #213456;
    box-shadow: none;
  }

  #dataModal .input-group:focus-within {
    box-shadow: 0 0 0 0.2rem rgba(225, 173, 1, 0.25);
    border-radius: 8px;
  }

  #btn_chngepass {
    background-color: #E1AD01;
    border: none;
    color: #213456;
    font-weight: 700;
    padding: 10px 40px;
    border-radius: 30px;
    transition: all 0.3s ease;
  }

  #btn_chngepass:hover {
    background-color: #213456;
    color: #E1AD01;
    transform: translateY(-2px);
  }

  .toggle-password {
    cursor: pointer;
    position: absolute;
    right: 15px;
    top: 13px;
    z-index: 10;
    color: #6c757d;
  }

  .tracking-timeline {
    list-style: none;
    padding: 0;
    margin: 0;
    position: relative;
  }

  .tracking-timeline::before {
    content: '';
    position: absolute;
    top: 5px;
    bottom: 0;
    left: 11px; 
    width: 2px;
    border-left: 2px dotted #a3a3a3;
    z-index: 1;
  }

  .timeline-item {
    position: relative;
    padding-left: 35px;
    padding-bottom: 20px;
  }

  .timeline-icon {
    position: absolute;
    left: 4px;
    top: 2px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background-color: #e0e0e0;
    border: 3px solid #ffffff;
    z-index: 2;
    box-shadow: 0 0 0 1px #ccc;
    transition: all 0.3s ease;
  }

  .timeline-item.completed .timeline-icon {
    background-color: #16A34A; 
    box-shadow: 0 0 0 2px #16A34A;
  }

  .timeline-item.pending .timeline-icon {
    background-color: #E1AD01; 
    box-shadow: 0 0 0 2px #E1AD01;
  }

  .timeline-desc {
    font-size: 12px;
    font-weight: 700;
    color: #333;
    margin-bottom: 2px;
    text-transform: uppercase;
  }

  .timeline-date {
    font-size: 11px;
    color: #6c757d;
    font-style: italic;
  }
  
.owi-navbar {
  background-color: #213456 !important;
  box-shadow: 0 2px 10px 2px #66738e;
  margin-bottom: 40px;
}

.owi-navbar .nav-link,
.owi-navbar .navbar-brand {
  color: #fff !important;
  font-weight: 600;
  letter-spacing: .3px;
}

.owi-navbar .nav-link i {
  margin-right: 6px;
}

.owi-navbar .nav-link:hover,
.owi-navbar .navbar-brand:hover {
  opacity: .92;
}

.owi-navbar .dropdown-menu {
  background-color: #ffffff;
  border: none;
  min-width: 220px;
  padding: .35rem;
  box-shadow: 0 12px 24px rgba(0,0,0,0.25);
  border-radius: 12px;
}

.owi-navbar .dropdown-item {
  color: black;
  border-radius: 10px;
  padding: .55rem .75rem;
  white-space: normal; 
}

.owi-navbar .dropdown-item i {
  margin-right: 8px;
}

.owi-navbar .dropdown-item:hover {
  background-color: #54699e;
  color: #fff;
}

.owi-navbar .dropdown-divider {
  border-top: 1px solid rgba(255,255,255,0.2);
}
.notif-dropdown {
  width: 360px;
  max-width: 92vw;
}

@media (max-width: 576px) {
  .notif-dropdown {
    width: 92vw;
  }
}

.owi-navbar .badge-danger {
  background-color: #ff4d4d;
}

.owi-navbar .badge-info {
  background-color: #28c7ff;
  color: #002a4a;
  font-weight: 700;
}

.owi-navbar .navbar-toggler {
  border-color: rgba(255,255,255,0.35);
}

.owi-navbar .navbar-toggler-icon {
  filter: brightness(0) invert(1);
}

.owi-navbar .nav-item {
  position: relative;
  margin: 0 5px;
  display: flex;
  align-items: center;
}

.owi-navbar .nav-link {
  position: relative;
  padding: 0.8rem 1rem !important;
  color: rgba(255, 255, 255, 0.8) !important;
  transition: all 0.3s ease;
}

.owi-navbar .nav-link::after {
  content: '';
  position: absolute;
  width: 0;
  height: 3px;
  bottom: 5px; 
  left: 50%;
  background-color: var(--primary-color);
  transition: width 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), left 0.3s ease;
  transform: translateX(-50%);
  border-radius: 10px;
}

/* Hover State */
.owi-navbar .nav-item:hover .nav-link {
  color: #fff !important;
}

.owi-navbar .nav-item:hover .nav-link::after {
  width: 70%; 
}

.owi-navbar .nav-item.active .nav-link {
  color: var(--primary-color) !important;
  font-weight: 700;
}

.owi-navbar .nav-item.active .nav-link::after {
  width: 70%; 
  background-color: var(--primary-color);
}

.owi-navbar .dropdown-menu {
  border-top: 3px solid var(--primary-color) !important;
  border-radius: 0 0 8px 8px !important;
  margin-top: 0;
}
.navbar-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  font-family: 'Orbitron', sans-serif;
  font-size: 1.4rem;
  letter-spacing: 1px;
}

.navbar-brand img {
  transition: transform 0.3s ease;
}

.navbar-brand:hover img {
  transform: rotate(-10deg) scale(1.1);
}

.owi-navbar .dropdown-menu {
  border-top: 3px solid var(--primary-color);
  margin-top: 10px;
}


:root{
  --navy:#121C31;
  --navy2:#1a2a4a;
  --yellow:#EAAA00;

  --bg:#EEF2F7;
  --card:#ffffff;
  --card2:#F8FAFF;
  --text:#111827;
  --muted:#6B7280;
  --line:#E5E7EB;

  --shadow: 0 14px 34px rgba(17,24,39,.10);
  --radius:18px;
  --radius-sm:14px;
  --focus: 0 0 0 .2rem rgba(234,170,0,.18);
}

html, body{ height:100%; }

body {
  background: linear-gradient(to bottom, #ffffff, #99aac8);
  background-attachment: fixed; 
  margin: 0; 
  height: 100vh; 
} 
.container.mt-3{ padding-top: 10px; padding-bottom: 24px; }

.navbar, header, .topbar, .navbar-default{
  background-color: #213456 !important;
  border-color: rgba(255,255,255,.10) !important;
  margin-bottom: 40px;
}
.navbar a, .navbar-brand, .navbar-nav > li > a,
.navbar i, .navbar .fa, .navbar .fas{
  color: #fff !important;
  font-weight: 600;
}
.navbar-nav > li.active > a,
.navbar-nav > li > a:hover{
  color: var(--yellow) !important;
}
.navbar-nav > li.active > a{
  border-bottom: 3px solid var(--yellow);
}
#new_rep_table { width:100% !important; }

.table-wrap {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 14px;
}

.dataTables_wrapper {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 14px;
}

.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label,
.dataTables_wrapper .dataTables_info {
  color: var(--muted) !important;
  font-weight: 600;
}

.dataTables_wrapper .dataTables_filter input:focus,
.dataTables_wrapper .dataTables_length select:focus {
  box-shadow: var(--focus) !important;
  border-color: rgba(234,170,0,.45) !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
  border-radius: 12px !important;
  border: 1px solid transparent !important;
  color: var(--text) !important;
  background: transparent !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
  border-color: var(--line) !important;
  background: #F8FAFC !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
  background: rgba(234,170,0,.18) !important;
  border-color: rgba(234,170,0,.35) !important;
}

table.dataTable {
  border-collapse: collapse !important; 
  width: 100% !important;
}

table.dataTable thead th {
  color: white !important;
  font-weight: 900;
  letter-spacing: .04em;
  text-transform: uppercase;
  border: none !important;
  border-bottom: 2px solid #213456 !important; 
  background: #5273ad !important;
  padding: 14px 12px !important;
}

table.dataTable tbody tr {
  background: #ffffff !important;
  box-shadow: 0 10px 22px rgba(17,24,39,.08);
}

table.dataTable tbody td {
  border-top: none !important;
  border-bottom: 1px solid #213456 !important; 
  color: rgba(17,24,39,.85) !important;
  padding: 14px 12px !important;
}

table.dataTable tbody tr:hover {
  transition: .15s ease;
  background: #F8FAFF !important;
}

.modal-content{
  border: 1px solid var(--line) !important;
  background: #ffffff !important;
  box-shadow: 0 22px 60px rgba(17,24,39,.18);
}

.modal-header{
  border-bottom: 3px solid var(--yellow) !important;
  padding: 16px 18px !important;
  background: #213456 !important;
}

.modal-title{
  font-size: 16px;
  font-weight: 900;
  letter-spacing: .02em;
  color: white;
  text-transform: uppercase;
}

.modal-body{ padding: 18px !important; }
.modal-footer{
  border-top: 1px solid var(--line) !important;
  padding: 14px 18px !important;
}

label {
  font-size: 11px;
  font-weight: 900;
  color: #213456;
  letter-spacing: .08em;
  text-transform: uppercase;
  margin-bottom: 6px;
}

input.form-control,
textarea.form-control {
  color: #6c757d !important;
  background-color: transparent !important; 
  border: none !important; 
  border-bottom: 1px solid #213456 !important; 
  border-radius: 0px !important; 
  resize: none !important; 
}

select.custom-select-placeholder.placeholder-active,
textarea.form-control.custom-select-placeholder:placeholder-shown {
  color: red !important;
  border: 1px solid #ced4da !important;
  border-radius: .2rem !important;
  background-color: #fff !important;
}

textarea.form-control.custom-select-placeholder::placeholder {
  color: red !important;
  opacity: 0.7;
}

select.custom-select-placeholder.has-value,
textarea.form-control.custom-select-placeholder:not(:placeholder-shown) {
  color: #212529 !important; 
  border: none !important; 
  border-bottom: 1px solid #213456 !important; 
  border-radius: 0px !important;
  background-color: transparent !important;
}
.form-control,
.form-control-sm,
select.form-control,
textarea.form-control{
  background: #fff !important;
  border: 1px solid var(--line) !important;
  color: var(--text) !important;
  border-radius: 14px !important;
}

.form-control:focus,
.form-control-sm:focus,
select.form-control:focus,
textarea.form-control:focus{
  box-shadow: var(--focus) !important;
  border-color: rgba(234,170,0,.45) !important;
}

.form-control[readonly],
textarea[readonly]{ opacity: .95; }

.form-group{ margin-bottom: 14px !important; }

.btn{
  border-radius: 14px !important;
  padding: 10px 14px !important;
  font-weight: 900 !important;
  letter-spacing: .02em;
  border: 1px solid transparent !important;
}

.btn-primary{
  background: white !important;
  border-color: var(--navy) !important;
  color: #213456 !important;
}
.btn-primary:hover{ background: #213456; color:white;}

.btn-success{
  background: rgba(22,163,74,.14) !important;
  border-color: rgba(22,163,74,.28) !important;
  color: #166534 !important;
}
.btn-success:hover{ background: rgba(22,163,74,.18) !important; }

.btn-danger{
  background: rgba(239,68,68,.14) !important;
  border-color: rgba(239,68,68,.28) !important;
  color: #991b1b !important;
}
.btn-danger:hover{ background: rgba(239,68,68,.18) !important; }

#msg_thread .card.card-body{
  background: #213456 !important;
  border: 1px solid var(--line) !important;
  border-radius: var(--radius-sm) !important;
}

.container_remarks{
  background: #F8FAFF;
  border: 1px solid var(--line);
  border-radius: var(--radius-sm);
  padding: 12px;
  max-height: 280px;
  box-shadow: 0 20px 60px rgba(123, 128, 44, 0.605);
  overflow: auto;
}

#remarks_view ul{ list-style: none; padding-left: 0; margin: 0; }

#remarks_view li{
  padding: 10px 12px;
  border: 1px solid var(--line);
  background: #ffffff;
  border-radius: 14px;
  margin-bottom: 10px;
  box-shadow: 0 10px 18px rgba(17,24,39,.06);
}

hr{ border-top: 1px solid var(--line) !important; }

.priority-chip{
  padding:4px 10px;
  border-radius:999px;
  font-weight:900;
  font-size:11px;
  letter-spacing:.05em;
}
.p-critical{ background: rgba(239,68,68,.14); color:#991b1b; border:1px solid rgba(239,68,68,.25); }
.p-high{     background: rgba(251,146,60,.14); color:#9a3412; border:1px solid rgba(251,146,60,.25); }
.p-medium{   background: rgba(234,170,0,.16); color:#7a5200; border:1px solid rgba(234,170,0,.30); }
.p-low{      background: rgba(34,197,94,.14); color:#166534; border:1px solid rgba(34,197,94,.25); }

.select2-container--default .select2-selection--single{
  background-color: #ffffff !important;
  border: 1px solid var(--line) !important;
  border-radius: 14px !important;
  height: 42px !important;
  display: flex !important;
  align-items: center !important;
  padding: 4px 10px !important;
  color: var(--text) !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered{
  color: var(--text) !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow{
  height: 42px !important;
}

.select2-dropdown{
  background-color: #ffffff !important;
  color: var(--text) !important;
  border: 1px solid var(--line) !important;
  border-radius: 14px !important;
  box-shadow: 0 18px 40px rgba(17,24,39,.14);
}
.select2-results__option{ color: var(--text) !important; }
.select2-results__option--highlighted{
  background: rgba(234,170,0,.16) !important;
  color: var(--text) !important;
}
.btn {  
    background-color: white !important;
    border: 2px solid #213456;
    border-color: var(--gold-accent);
    font-weight: 700;
    color: #213456;
}

.btn:hover {
    background-color: #16243d !important;
    border-color: var(--gold-accent);
    color: white;
}

.btn-success {  
    background-color: white !important;
    border: 2px solid #213456;
    font-weight: 700;
    color: #213456;
}

.btn-success:hover {
    background-color: #16243d !important;
    border-color: var(--gold-accent);
    color:white;
}

::-webkit-scrollbar {
  width: 8px;
}
::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}
::-webkit-scrollbar-thumb {
background: linear-gradient(135deg, #837031, #E1AD01);
  border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(135deg, #837031, #E1AD01);
}

.owi-navbar {
  background-color: #213456 !important;
  box-shadow: 0 2px 10px 2px #66738e;
  margin-bottom: 40px;
}

.owi-navbar .nav-link,
.owi-navbar .navbar-brand {
  color: #fff !important;
  font-weight: 600;
  letter-spacing: .3px;
}

.owi-navbar .nav-link i {
  margin-right: 6px;
}

.owi-navbar .nav-link:hover,
.owi-navbar .navbar-brand:hover {
  opacity: .92;
}

.owi-navbar .dropdown-menu {
  background-color: #ffffff;
  border: none;
  min-width: 220px;
  padding: .35rem;
  box-shadow: 0 12px 24px rgba(0,0,0,0.25);
  border-radius: 12px;
}

.owi-navbar .dropdown-item {
  color: black;
  border-radius: 10px;
  padding: .55rem .75rem;
  white-space: normal; 
}

.owi-navbar .dropdown-item i {
  margin-right: 8px;
}

.owi-navbar .dropdown-item:hover {
  background-color: #54699e;
  color: #fff;
}

.owi-navbar .dropdown-divider {
  border-top: 1px solid rgba(255,255,255,0.2);
}
.notif-dropdown {
  width: 360px;
  max-width: 92vw;
}

@media (max-width: 576px) {
  .notif-dropdown {
    width: 92vw;
  }
}

.owi-navbar .badge-danger {
  background-color: #ff4d4d;
}

.owi-navbar .badge-info {
  background-color: #28c7ff;
  color: #002a4a;
  font-weight: 700;
}

.owi-navbar .navbar-toggler {
  border-color: rgba(255,255,255,0.35);
}

.owi-navbar .navbar-toggler-icon {
  filter: brightness(0) invert(1);
}

.owi-navbar .nav-item {
  position: relative;
  margin: 0 5px;
  display: flex;
  align-items: center;
}

.owi-navbar .nav-link {
  position: relative;
  padding: 0.8rem 1rem !important;
  color: rgba(255, 255, 255, 0.8) !important;
  transition: all 0.3s ease;
}

.owi-navbar .nav-link::after {
  content: '';
  position: absolute;
  width: 0;
  height: 3px;
  bottom: 5px; 
  left: 50%;
  background-color: var(--primary-color);
  transition: width 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), left 0.3s ease;
  transform: translateX(-50%);
  border-radius: 10px;
}

.owi-navbar .nav-item:hover .nav-link {
  color: #fff !important;
}

.owi-navbar .nav-item:hover .nav-link::after {
  width: 70%; 
}

.owi-navbar .nav-item.active .nav-link {
  color: var(--primary-color) !important;
  font-weight: 700;
}

.owi-navbar .nav-item.active .nav-link::after {
  width: 70%; 
  background-color: var(--primary-color);
}

.owi-navbar .dropdown-menu {
  border-top: 3px solid var(--primary-color) !important;
  border-radius: 0 0 8px 8px !important;
  margin-top: 0;
}
.navbar-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  font-family: 'Orbitron', sans-serif;
  font-size: 1.4rem;
  letter-spacing: 1px;
}

.navbar-brand img {
  transition: transform 0.3s ease;
}

.navbar-brand:hover img {
  transform: rotate(-10deg) scale(1.1);
}

.owi-navbar .dropdown-menu {
  border-top: 3px solid var(--primary-color);
  margin-top: 10px;
}


  .btn-remarks-red {
  background-color: #dc3545 !important;
  border: 2px solid #b21f2d !important;
  font-weight: 700;
  color: white !important;
  border-radius: 4px;
}

.btn-remarks-red:hover {
  background-color: #bd2130 !important;
  border-color: #b21f2d !important;
  color: white !important;
}
#remarks_Modal{
  background: #a8a9aa6a;
}
</style>
 
<div class="container" style="max-width:1800px;">
  <div class="table-responsive-xl">
    <table class="table table-hover" id="fix_asset_table"></table>
  </div>
</div>

<script src="../js/coms.js"></script> 
<div class="modal fade" id="fa_Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 80%; width: 80%;">
      <form id="fa_form" action="insert.php" method="POST">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Fixed Asset Information</h5>
              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>

          <div class="modal-body">
            <div class="row">
              <div class="col-md-7 border-right pt-2 pb-2">
            
                <h6 class="text-uppercase mb-3" style="color:#213456; font-weight: 800;">Request Details</h6> 
                
                <div class="row">

                  <div class="form-group col-md-5">
                     <label>Ticket No</label>
                      <input type="text" class="form-control"name="ticket_no" id="ticket_no"></input>
                  </div>

                  <div class="form-group col-md-5">
                    <label>Requesting Dept/Branch</label>
                    <input type="text" class="form-control" name="requested_db" id="str_name" readonly>
                  </div>

                  <div class="form-group col-md-5">
                    <label>Requesting Employee</label>
                    <input type="text" class="form-control" name="requested_by" id="full_name" readonly>
                  </div>

                  <div class="form-group col-md-5">
                    <label>Ticket Created</label>
                    <input type="text" class="form-control" name="ticket_created" id="ticket_created" readonly>
                  </div>

                  <div class="form-group col-md-5">
                    <label>Item Code</label>
                    <input type="text" class="form-control" name="item_code" id="item_code" readonly>
                  </div>

                  <div class="form-group col-md-5">
                    <label>Description</label>
                    <input type="text" class="form-control" name="description" id="description" readonly>
                  </div>

                  <div class="form-group col-md-5">
                    <label>Serial Number</label>
                    <input type="text" class="form-control" name="serial_number" id="serial_number" required>
                  </div>

                   <div class="form-group col-md-5">
                    <label>Asset Tag Number</label>
                    <input type="text" class="form-control" name="asset_tag_number" id="asset_tag_number" required>
                  </div>

                  <div class="form-group col-md-12">
                    <label>Purpose of Request (Created by Store/Dept User)</label>
                    <textarea class="form-control" name="purpose_of_request" id="purpose_of_request" style="height: 150px;"></textarea>
                  </div>

                   <div class="form-group col-md-12">
                    <label>Purpose of Request (Rephrase for Printing)</label>
                    <textarea class="form-control" name="revised_request" id="revised_request" style="height: 150px;"></textarea>
                  </div>

                  <div class="form-group col-md-4">
                    <label>Item Received By</label>
                    <input type="text" class="form-control" name="item_received_by" id="it_desc" readonly>
                  </div>
                    
                  <input type="hidden" class="form-control" name="received_by" value="<?php echo $_SESSION['tech_id'] ?? ''; ?>" readonly>

                  <div class="form-group col-md-4">
                    <label>Date Received</label>
                    <input type="text" class="form-control" name="date_received" id="date_received" required>
                  </div>

                   <div class="form-group col-md-4">
                    <label>Noted by</label>
                    <input type="text" class="form-control"  id="noted_by_desc" required>
                  </div>
                </div>
              </div>

              <div class="col-md-5 pt-2 pb-2" style="background: linear-gradient(to bottom, #ffffff, #bbc2cf);  border-radius: 8px;">
                  <h6 class="text-uppercase mb-3" style="color:#E1AD01; font-weight: 800;">Asset Request Progress</h6>
                  <div class="tracking-container" style="max-height: 950px; overflow-y: auto; padding-right: 10px;">
                      <ul class="tracking-timeline" id="trackingMap">
                          </ul>
                  </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <input type="hidden" name="operation" id="operation" value="update_request">
            <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">
            <div class="form-group col-md-3">
      <select class="form-control form-control-sm custom-select-placeholder placeholder-active" name="approve_method_agm" id="approve_method_agm" required>
      <option value=""> SELECT APPROVAL METHOD </option>
      <option value="1">APPROVE ONLY</option>
      <option value="2">APPROVE WITH E-SIGNATURE</option>
      </select>
      </div>
              <button type="button" class="btn btn-remarks-red" id="btn_open_remarks"><strong>REMARKS</strong></button>
            <button type="submit" class="btn"><strong>APPROVE FIXED ASSET REQUEST</strong></button>
          </div>
        </div>
      </form>
    </div>
</div>



<div class="modal fade" id="remarks_Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 60%; width: 60%;">
      <form id="remarks_submit_form" action="insert.php" method="POST">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #dc3545; border-bottom: 4px solid #E1AD01;">
                    <h5 class="modal-title text-white">Add Ticket Remarks</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Target Ticket No</label>
                        <input type="text" class="form-control" name="ticket_no" id="remarks_ticket_no" readonly />
                    </div>
                    <div class="form-group">
                        <label>Remarks Description</label>
                        <textarea class="form-control" name="remarks_adtech" id="modal_textarea_remarks" style="height: 120px;" placeholder="Type your notes here..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="operation" value="add_remarks_only">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Notes</button>
                </div>
            </div>
        </form>
    </div>
</div>




<script type="text/javascript">
$(document).ready(function(){

  function getUrlParam(param) {
    var urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
  }

  var targetTicket = getUrlParam('ticket_no');
  var reptable;
  var user_id = <?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>; 

  if (targetTicket) {
    setTimeout(function() {
      var foundRow = null;
      if (reptable) {
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
      }
    }, 600);
  }

  $("div.selected select").val("OPEN");

  function getdata(){
    $.post('fetchdata/fetch_data.php', {mode: 'fa_tbl'}, function(data){
      admin_datatable(data);
    }, 'json');
  }
  getdata();

  function admin_datatable(t){
    const dataset = t.fadata;
    reptable = $("#fix_asset_table").DataTable({
      "dom": '<"pull-left"lf><"pull-right">tip',
      stateSave: true,
      "bDestroy": true,
      "responsive": true, 
      "lengthChange": false, 
      "autoWidth": false,
      language: {
        emptyTable: "No unassigned reports",
        search: "_INPUT_",
        searchPlaceholder: "Search..."
      },
      pageLength: 5,
      data: dataset,
      "order": [[ 0, "Desc" ]],
      columns: [
        {title:"Ticket No", data:"ticket_no","defaultContent": ""},
        {title:"Requesting Dept/Branch", data:"str_name","defaultContent": ""},
        {title:"Requesting Employee", data:"full_name","defaultContent": ""},
        {title:"Ticket Date", data:"ticket_created","defaultContent": ""},
        {title:"Item Code", data:"item_code","defaultContent": ""},
        {title:"Description", data:"description","defaultContent": ""},
        {title:"Serial", data:"serial_number","defaultContent": ""},
        {title:"Received by", data:"it_desc","defaultContent": ""},
        {title:"Date Received", data:"date_received","defaultContent": ""},
             {title:"Noted by", data:"noted_by_desc","defaultContent": ""},
        {title:"Status", data:"status","defaultContent": ""},
        {title:"Update", data:null,"defaultContent": "<Button class='btn btn-danger' name='update'><i class='fas fa-edit'></i></Button>"}
      ],
      rowCallback: function(row, data, index){
        if(data['msg_cnt'] == '1'){
          $(row).find('td').css("font-weight", "bold");
        }
      }
    });

    // Enforce border removal updates after rendering context finishes
    $('#fix_asset_table').css('border', 'none');

    setInterval(function () {
      getdata();
    }, 60000);

    // Main Update Handler with merged Tracking Map Logic
    $('#fix_asset_table tbody').off('click', 'button[name="update"]').on('click', 'button[name="update"]', function (e) {
      e.stopPropagation();
      var data = reptable.row($(this).parents('tr')).data();
      if(!data) return;

      // Populate basic form details
      $('#ticket_no').val(data['ticket_no']);
      $('#str_name').val(data['str_name']);
      $('#full_name').val(data['full_name']);
      $('#ticket_created').val(data['ticket_created']);
      $('#item_code').val(data['item_code']);
      $('#description').val(data['description']);
      $('#serial_number').val(data['serial_number']);
       $('#asset_tag_number').val(data['asset_tag_number']);
      $('#purpose_of_request').val(data['purpose_of_request']);
       $('#revised_request').val(data['revised_request']);
      $('#it_desc').val(data['it_desc']);
       $('#noted_by_desc').val(data['noted_by_desc']);
      $('#date_received').val(data['date_received']);
     $('#status').val(data['status']);

      $('#action').val("Update");
      $('#operation').val("update_request"); 

      var tid = $(this).parent().siblings(':first').html() || data['ticket_no'];
      $('#tick_title').text("Ticket Number: " + tid);
      
      if (typeof displayAttachmentsFromData === "function") displayAttachmentsFromData(data);
      if (typeof getinfo === "function") getinfo(tid, 'remarks', user_id);

      // Fetch Timeline Progress for Tracking Map
      $.ajax({
        url: 'get_first_comment.php', 
        type: 'POST',
        dataType: 'json', 
        data: { ticket_no: data['ticket_no'] },
        success: function(response) {
           const statusLevels = {
                      'submitted': 1, 'noted': 2, 'validated': 3, 'printed': 4,
                      'verified': 5,  'recorded': 6,'approved': 7, 'completed': 8
                  };

                  let dbStatus = (response.status || data['status'] || "").toLowerCase().trim();
                  let currentLevel = statusLevels[dbStatus] || 0; 

                  const trackSteps = [
                      { desc: "Request submitted by store/user", date: response.date_created || data['ticket_created'], reqLevel: 0 },
                      { desc: "Under technical evaluation", date: response.date_created || data['ticket_created'], reqLevel: 0 },
                      { desc: "Submitted to technical head", date: response.date_submitted, reqLevel: 1 },
                      { desc: "Approved and noted by technical head", date: response.date_noted, reqLevel: 2 },
                      { desc: "For admin support validation", date: null, reqLevel: 2 }, 
                      { desc: "Validated by admin support", date: response.date_validated, reqLevel: 3 },
                      { desc: "For printing request form", date: null, reqLevel: 3 }, 
                      { desc: "Printed", date: response.date_printed, reqLevel: 4 },
                      { desc: "For administrative verification", date: null, reqLevel: 4 }, 
                      { desc: "Verified by the administrator", date: response.date_verified, reqLevel: 5 },
                      { desc: "For recording", date: null, reqLevel: 5 }, 
                      { desc: "Recorded", date: response.date_recorded, reqLevel: 6 },
                      { desc: "For AGM approval", date: null, reqLevel: 6 }, 
                      { desc: "Approved by AGM", date: response.date_approved, reqLevel: 7 },
                      { desc: "Ready for asset replacement", date: null, reqLevel: 7 }, 
                      { desc: "Asset replaced / Completed", date: response.date_completed, reqLevel: 8 }
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

            $('#trackingMap').html(timelineHtml);
        },
        error: function() {
            $('#trackingMap').html('<p class="text-danger">Failed to load progress timeline.</p>');
        },
        complete: function() {
            $('#fa_Modal').modal('show');
        }
      });
    });
  }
  
   // Handle Opening the Sub-Modal for Remarks
  $(document).on('click', '#btn_open_remarks', function() {
      var ticketNo = $('#ticket_no').val();
      
      if(!ticketNo) {
          Swal.fire({
              icon: 'warning',
              title: 'Validation Error',
              text: 'No active ticket number was found to append remarks to.'
          });
          return;
      }
      
      // Inject variables into target modal nodes
      $('#remarks_ticket_no').val(ticketNo);
      $('#modal_textarea_remarks').val($('#remarks_adtech').val());
      
      // Toggle views
      $('#remarks_Modal').modal('show');
  });

  // Handle Dedicated Form Submission for the Remarks Modal
  $(document).on('submit', '#remarks_submit_form', function(event) {
      event.preventDefault();
      var formData = new FormData(this);
      
      // Synchronize back to the main hidden form field just in case
      $('#remarks_adtech').val($('#modal_textarea_remarks').val());

      $.ajax({
          url: "insert.php",
          method: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          dataType: 'json',
          success: function(response) {
              if (response.status === 'success' || response.status === true) {
                  Swal.fire({
                      icon: 'success',
                      title: 'Remarks added successfully',
                      showConfirmButton: false,
                      timer: 1500
                  }).then(function() {
                      $('#remarks_Modal').modal('hide');
                      $('#fa_Modal').modal('hide');
                      getdata();
                      location.reload();
                  });
              } else {
                  Swal.fire({
                      icon: 'error',
                      title: 'Execution Failed',
                      text: response.message || 'Please check backend logs.'
                  });
              }
          }
      });
  });

  // Form Submission
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
            $('#fa_Modal').modal('hide');
            getdata();
            location.reload();
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

// Global Utilities
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
</script>