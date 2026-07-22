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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
      :root {
  --navy: #213456;
  --navy2: #1a2a4a;
  --yellow: #EAAA00;
  --bg: #EEF2F7;
  --card: #ffffff;
  --card2: #F8FAFF;
  --text: #111827;
  --muted: #6B7280;
  --line: #E5E7EB;
  --shadow: 0 14px 34px rgba(17,24,39,.10);
  --radius: 18px;
  --radius-sm: 14px;
  --focus: 0 0 0 .2rem rgba(234,170,0,.18);
  --theme-color: #213456;
}

body {
  background: linear-gradient(to bottom, #ffffff, #99aac8);
  background-attachment: fixed; 
  margin: 0; 
  height: 100vh; 
} 

.container.mt-3 { padding-top: 10px; padding-bottom: 24px; }

#fix_asset_table { 
  width: 100% !important; 
  background-color: #ffffff;
  border-collapse: collapse !important;
  border-spacing: 0 !important;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 10px 8px rgba(108, 108, 53, 0.4);
  border: none !important;
}

table.dataTable {
  border-collapse: collapse !important;
  border-spacing: 0 !important;
}

table.dataTable thead th,
#fix_asset_table thead th {
  background-color: var(--theme-color) !important;
  color: white !important;
  font-weight: 900;
  text-transform: uppercase;
  font-size: 0.85rem;
  letter-spacing: .04em;
  padding: 14px 12px !important;
  border: none !important;
}

table.dataTable tbody tr,
#fix_asset_table tbody tr {
  background: #ffffff !important;
  border: none !important;
  box-shadow: none !important;
  border-radius: 0 !important;
}

table.dataTable tbody td,
#fix_asset_table tbody td {
  padding: 14px 12px !important;
  vertical-align: middle;
  color: rgba(17,24,39,.85) !important;
  border: none !important;
  border-bottom: 1px solid var(--theme-color) !important;
}

#fix_asset_table tbody tr:hover,
table.dataTable tbody tr:hover {
  background-color: #bec5d1 !important;
  color: #ffffff !important;
  cursor: pointer;
  transform: none !important;
  transition: all 0.2s ease;
}

table.dataTable tbody tr td:first-child,
table.dataTable tbody tr td:last-child {
  border-radius: 0 !important;
}

.table-responsive {
  border-radius: 8px;
  margin-top: 20px;
}

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

/* Dropdown items */
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

/* Spacing in grid */
.form-group{ margin-bottom: 14px !important; }

/* ===== Buttons (OWI style) ===== */
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

/* --- Buttons --- */
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
    </style>
</head>

<div class="container" style="max-width:1800px;">
    <div class="row mb-12 align-items-end">
        <div class="row mb-3">
    <div class="col-md-4">
        <label>Year</label>
        <select id="filter_year" class="form-control filter-trigger">
            <option value="">All Years</option>
            <option value="2026">2026</option>
            </select>
    </div>
    <div class="col-md-4">
        <label>Month</label>
        <select id="filter_month" class="form-control filter-trigger">
            <option value="">All Months</option>
            <option value="01">January</option>
            </select>
    </div>
    <div class="col-md-4">
        <label>Status</label>
        <select id="filter_status" class="form-control filter-trigger">
            <option value="">All Statuses</option>
            <option value="Submitted">Submitted</option>
            <option value="Noted">Noted</option>
            <option value="Validated">Validated</option>
            <option value="Printed">Printed</option>
            <option value="Recorded">Recorded</option>
            <option value="Verified">Verified</option>
            <option value="Approved">Approved</option>
            <option value="Completed">Completed</option>
        </select>
    </div>
</div>
    </div>
  
    <div id="metrics_summary_div" class="mb-3" style="margin-top:30px;"></div>

    <div class="table-responsive-xl table-wrap">
        <table class="table table-hover" id="fa_reports_table" style="width:100%;"></table>
    </div>
</div>
    
<script src="../js/coms.js"></script> 

<div class="modal fade" id="fa_reports_Modal" tabindex="-1" aria-hidden="true">
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
             <div class="col-md-5 border-right pt-2 pb-2">
                 <h6 class="text-uppercase mb-3" style="color:#E1AD01; font-weight: 800;">Request Details</h6>
                <div class="row">
                  <div class="form-group col-md-5">
                     <label>Ticket No</label>
                      <input type="text" class="form-control" name="ticket_no" id="ticket_no"></input>
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
                    <label>Item Received By</label>
                    <input type="text" class="form-control" name="item_received_by" id="it_desc" readonly>
                  </div>
                  <input type="hidden" class="form-control" name="received_by" value="<?php echo $_SESSION['tech_id'] ?? ''; ?>" readonly>
                  <div class="form-group col-md-5">
                    <label>Date Received</label>
                    <input type="text" class="form-control" name="date_received" id="date_received" required>
                  </div>
                  <div class="form-group col-md-5">
                    <label>Noted By</label>
                    <input type="text" class="form-control" name="noted_by_desc" id="noted_by_desc" readonly>
                  </div>
                  <div class="form-group col-md-5">
                     <label>Status</label>
                    <select class="form-control" name="status" id="status">
                        <option value="">UPDATE STATUS</option>
                        <option value="PRINTED">PRINTED</option>
                        <option value="RECORDED">RECORDED</option>
                        <option value="VERIFIED">VERIFIED</option>
                        <option value="APPROVED">APPROVED</option>
                        <option value="COMPLETED">COMPLETED</option>
                    </select>
                  </div>

                    <div class="fform-group col-md-4" id="datePrintedGroup" style="display: none;">
                        <label>Date Printed</label>
                        <input type="datetime-local" class="form-control status-date-input" name="date_printed" id="date_printed" disabled>
                    </div>
                    <div class="fform-group col-md-4" id="dateRecordedGroup" style="display: none;">
                        <label>Date Recorded</label>
                        <input type="datetime-local" class="form-control status-date-input" name="date_recorded" id="date_recorded" disabled>
                    </div>
                    <div class="fform-group col-md-4" id="dateVerifiedGroup" style="display: none;">
                        <label>Date Verified</label>
                        <input type="datetime-local" class="form-control status-date-input" name="date_verified" id="date_verified" disabled>
                    </div>
                    <div class="fform-group col-md-4" id="dateApprovedGroup" style="display: none;">
                        <label>Date Approved</label>
                        <input type="datetime-local" class="form-control status-date-input" name="date_approved" id="date_approved" disabled>
                    </div>
                    <div class="fform-group col-md-4" id="dateCompletedGroup" style="display: none;">
                        <label>Date Completed</label>
                        <input type="datetime-local" class="form-control status-date-input" name="date_completed" id="date_completed" disabled>
                    </div>
                </div>
              </div>

               <div class="col-md-4 border-right pt-2 pb-2" style="background: linear-gradient(to bottom, #ffffff, #f0f3f7);">
            <div class="form-group col-md-12">
                    <label>Workoutput (Under Technical Evaluation)</label>
                    <textarea class="form-control" name="technical_workoutput" id="technical_workoutput" style="height: 150px;" readonly></textarea>
                  </div>


                  <div class="form-group col-md-12">
                    <label>Purpose of Request (From Store/Dept User)</label>
                    <textarea class="form-control" name="purpose_of_request" id="purpose_of_request" style="height: 150px;" readonly></textarea>
                  </div>

                   <div class="form-group col-md-12">
                    <label>Purpose of Request (Rephrase for Printing)</label>
                    <textarea class="form-control" name="revised_request" id="revised_request"  style="height: 150px;" maxlength="70"></textarea>
                  </div>
          </div>


              <div class="col-md-3 pt-2 pb-2" style=" background: linear-gradient(to bottom, #ffffff, #d7dce4);border-radius: 0 8px 8px 0;">
                  <h6 class="text-uppercase mb-3" style="color:#E1AD01; font-weight: 800;">Asset Request Progress</h6>
                  <div class="tracking-container" style="max-height: 500px; overflow-y: auto; padding-right: 10px;">
                      <ul class="tracking-timeline" id="trackingMap"></ul>
                  </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <input type="hidden" name="operation" id="operation" value="save_request">
            <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">
              <button type="submit" class="btn"><strong>SAVE FIXED ASSET</strong></button>
          </div>
        </div>
      </form>
    </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    
    // Trigger on any filter change
    $('.filter-trigger').change(function() {
        refreshData();
    });

    function refreshData() {
        let month = $('#filter_month').val();
        let year = $('#filter_year').val();
        let status = $('#filter_status').val();

        $.post('fetchdata/fetch_data.php', {
            mode: 'fa_reports_tbl',
            month: month,
            year: year,
            status: status // Send status to PHP
        }, function(response) {
            // Update table
            if ($.fn.DataTable.isDataTable('#fa_reports_table')) {
                reptable.clear().rows.add(response.table_data).draw();
            } else {
                admin_datatable(response);
            }
            // Update metrics
            if(response.metrics) {
                updateMetricsUI(response.metrics);
            }
        }, 'json');
    }

    // Initial load
    refreshData();
});
$(document).ready(function(){

  var reptable;
  var user_id = <?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>; 

  function getUrlParam(param) {
    var urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
  }
  var targetTicket = getUrlParam('ticket_no');

  // Initial trigger
  getFAData($('#filter_month').val(), $('#filter_year').val());

  // Auto filter data when inputs change
  $('.filter-trigger').change(function() {
      let month = $('#filter_month').val();
      let year = $('#filter_year').val();
      
      if($(this).attr('id') === 'filter_status') {
          applyStatusFilter();
      } else {
          getFAData(month, year);
      }
  });

  setInterval(function () {
    let month = $('#filter_month').val();
    let year = $('#filter_year').val();
    getFAData(month, year);
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
        {title:"Requesting Dept/Branch", data:"str_name","defaultContent": ""},
        {title:"Requesting Employee", data:"full_name","defaultContent": ""},
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
          targetTicket = null;
        }
      }
    });
  }

  $('#fa_reports_table tbody').on('click', 'button[name="update"]', function (e) {
      e.preventDefault();
      var row = reptable.row($(this).parents('tr'));
      var data = row.data();
      if (!data) return;

      $('#ticket_no').val(data.ticket_no || '');
      $('#str_name').val(data.str_name || '');
      $('#full_name').val(data.full_name || '');
      $('#ticket_created').val(data.ticket_created || '');
      $('#item_code').val(data.item_code || '');
      $('#description').val(data.description || '');
      $('#serial_number').val(data.serial_number || '');
      $('#purpose_of_request').val(data.purpose_of_request || '');
         $('#revised_request').val(data.revised_request || '');
          $('#technical_workoutput').val(data.technical_workoutput || '');
      $('#it_desc').val(data.it_desc || '');
      $('#noted_by_desc').val(data.noted_by_desc || '');
      $('#date_received').val(data.date_received || '');
      $('#status').val(data.status || '');
      $('#operation').val("update_request");
      $('#fa_reports_Modal').modal('show');

      var tid = data.ticket_no;
      if (typeof getinfo === "function") getinfo(tid, 'remarks', user_id);
      loadTimeline(tid, data);
  });

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

    $('#ticket_no').val(data.ticket_no);
    $('#str_name').val(data.str_name);
    $('#full_name').val(data.full_name);
    $('#ticket_created').val(data.ticket_created);
    $('#item_code').val(data.item_code);
    $('#description').val(data.description);
    $('#serial_number').val(data.serial_number);
    $('#purpose_of_request').val(data.purpose_of_request);
     $('#revised_request').val(data.revised_request);
    $('#it_desc').val(data.it_desc);
    $('#noted_by_desc').val(data.noted_by_desc);
    $('#date_received').val(data.date_received);
    $('#status').val(data.status);
    $('#fa_reports_Modal').modal('show');

    loadTimeline(data.ticket_no, data);
}

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
                { desc: "Under technical evaluation", date: response.date_created, reqLevel: 0 },
                { desc: "Submitted to technical head", date: response.date_submitted, reqLevel: 1 },
                { desc: "Approved and noted by technical head", date: response.date_noted, reqLevel: 2 },
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
// Fixed target ID here to point correctly to #status elements
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
</script>


<script>
$(document).on('click', '.print-btn', function() {
    let ticket_no = $(this).data('id');
    
    $('#pdfForm')[0].reset();
    
    $('#modal_ticket_no').val(ticket_no);
    
    $('#dataModal').modal('show');
});
</script>
<div class="modal fade" id="dataModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="pdfForm" action="print_form.php" method="POST" style="width: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-file-pdf mr-2"></i> PDF Generation Confirmation
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

           

                <div class="modal-body text-center py-4">
                    <div class="confirmation-text mb-4">
                        <p class="lead mb-1">Do you want to generate a report for this Fixed Asset form?</p>
                        <span class="text-muted">Review the Ticket Number below before proceeding.</span>
                    </div>

                    <div class="row justify-content-center">
                        <div class="form-group col-md-8 text-left">
                            <label for="modal_ticket_no" class="font-weight-bold text-secondary">Ticket No</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                                </div>
                                <input type="text" class="form-control" name="ticket_no" id="modal_ticket_no" readonly>
                            </div>
                        </div>
                             <div class="progress-container">
                    <div id="loadingBar" class="progress-bar-fill"></div>
                </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary px-4 py-2 rounded-pill" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary-custom">
                        <span class="btn-text">Generate PDF</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

 <style>
/* Modal Base Styling */
#dataModal .modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    overflow: hidden; /* Clips the loading bar to the border radius */
}

#dataModal .modal-header {
    background-color: #213456;
    color: #fff;
    border-bottom: 4px solid #E1AD01;
}

#dataModal .modal-title {
    font-weight: 700;
    letter-spacing: 0.5px;
}

/* Custom Loading Bar Styles */
.progress-container {
    width: 100%;
    height: 5px;
    background-color: #f1f1f1;
    position: relative;
    overflow: hidden;
}

.progress-bar-fill {
    height: 100%;
    width: 0%;
    background-color: #E1AD01;
    transition: width 2s linear; /* Smooth 2-second acceleration transition */
}

/* Form Styling */
#dataModal .confirmation-text p {
    color: #213456;
    font-weight: 600;
}

#dataModal .input-group-text {
    background-color: #f8f9fa;
    border-right: none;
    color: #213456;
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}

#dataModal .form-control {
    border-left: none;
    height: 45px;
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
    background-color: #fcfcfc;
}

#dataModal .form-control:focus {
    border-color: #ced4da;
    box-shadow: none;
}

.btn-primary-custom {
    background-color: #E1AD01;
    border: none;
    color: #213456;
    font-weight: 700;
    padding: 10px 40px;
    border-radius: 30px;
    transition: all 0.3s ease;
}

.btn-primary-custom:hover:not(:disabled) {
    background-color: #213456;
    color: #E1AD01;
    transform: translateY(-2px);
}

.btn-primary-custom:disabled {
    background-color: #cccccc;
    color: #666666;
    cursor: not-allowed;
}
</style>
 <script>
document.getElementById('pdfForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const loadingBar = document.getElementById('loadingBar');
    const submitBtn = document.getElementById('btnSubmit');
    
    // Disable submit button during action
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
</script>

