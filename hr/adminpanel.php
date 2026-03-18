<?php

// ======== db  =========
include 'admin.php';
include '../condb.php';
include 'main_js.php';
include 'chrtdashboard.php';
include 'sub_graph_modal.php';
// include 'testcalendar.php';

// $conn=new dbconfig();

?>
<head>
  <link rel="stylesheet" href="adminpanel.css">
  
</head>
<style>
body {
   font-family: 'Poppins', Tahoma, Geneva, Verdana, sans-serif;
}

body.dark-mode {
    background: linear-gradient(145deg, #0f0f0f, #1a1a1a);
    color: #e0e0e0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body.dark-mode .card,
body.dark-mode .card2 {
    background: linear-gradient(145deg, #1a1a1a, #2a2a2a);
    color: #ffffff;
    border: 1px solid #2a2a2a;
    box-shadow: 0 0 10px rgba(0, 255, 255, 0.1);
    border-radius: 15px;
    transition: all 0.3s ease;
}

body.dark-mode .card-header {
    background-color: transparent;
    border-bottom: 1px solid #444;
    font-weight: bold;
    font-size: 18px;
    text-shadow: 0 0 5px rgba(0, 255, 255, 0.4);
}

body.dark-mode .form-check-label,
body.dark-mode .input-group-text,
body.dark-mode label {
    color: #00ffff;
}

body.dark-mode select,
body.dark-mode .form-control {
    background-color: #1d1d1d;
    color: #00ffff;
    border: 1px solid #00ffff;
    border-radius: 10px;
}

body.dark-mode .table {
    color: #ffffff;
    background-color: #1b1b1b;
    border-collapse: collapse;
}

body.dark-mode .table th,
body.dark-mode .table td {
    border: 1px solid #2c2c2c;
}

body.dark-mode .form-control::placeholder {
    color: #888;
} 
.search-input-group input {
    border: none;
    background: transparent;
    padding-left: 35px;
    font-size: 0.9rem;
}

.search-input-group i {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #3A3541AD;
}
/* --- User Profile Dropdown --- */
.avatar-circle {
    width: 40px;
    height: 40px;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: 600;
    cursor: pointer;
}
       
.card{
    border-radius:10px;
    border:none;
    position:relative;
    margin-bottom:0;  
    box-shadow: 0  5px 2px #2d3c597f;
    color: #465172;
    overflow-x: hidden;
    border: 2px solid transparent;
    transition: border-color 0.3 ease, color 0.3s ease;
    
}
.card .card-header{
    border-bottom-color: #213456;
    line-height:30px;
    -ms-grid-row-align:center;
    align-self:center;
    width:100%;
    display:flex;
    align-items:center;
    margin-bottom:40px;
    background-color:#213456;
    border-bottom:1px solid rgba(0,0,0,.125);
}
.card-header:first-child{
    border-radius:calc(.25rem -1px)calc(.25rem -1px)00;
}

/* Your existing hover rule */

.card:hover{
    border-color: #E5BA41;
    background-color: white;
}

.card:hover .card-header,
.card:hover a{
    color: #E1AD01 !important;
}

.container{
    margin-top:20px;
    width:100%;
    padding:-10px; 
    box-shadow: 0 10px 20px rgba(221, 221, 93, 0.4);
    background-color:white;
}

.table:not(.table-sm) thead th{
    border-bottom: none;
    background-color: #e9e9eb;
    color:#666;
    padding-top:15px;
    padding-bottom:15px;
}        
.bg-success{
    background-color: #54ca68 !important;
}

.bg-purple{
    background-color: #9c27b0 !important;
    color: #fff;
}
.bg-cyan{
    background-color: #10cfbd !important;
    color:#fff;
}
.bg-red{
    background-color: #f44336 !important;
    color: #fff;
}
.progress{
    -webkit-box-shadow: 0 0.4rem 0.6rem rgba(0,0,0,0,0.15);
    box-shadow: 0 0.4rem 0.6rem rgba(0,0,0,0.15);
}
.table thead th{
    font-size:12px;
    text-transform: uppercase;
    letter-spacing:1px;
    font-weight:700;
    color:#555;
    padding:20px;
    border-bottom: 2px solid #f0f0f0;
}

.table tbody td{
    padding:15px 10px;
    font-size:14px;
}
  /* body.dark-mode #chartdiv1,
  body.dark-mode #chartdiv2,
  body.dark-mode #chartdiv5,
  body.dark-mode #chartdiv8,
  body.dark-mode #chart_area {
    background-color: #131313;
    border-radius: 15px;
    box-shadow: inset 0 0 10px #00ffff20;
  } */

.form-check-input:checked {
    background-color: #00ffff;
    border-color: #00ffff;
}

::selection {
    background: #00ffff;
    color: #000;
}

.btn-success, .btn-danger {
    border-radius: 5px;
    padding: 0.5em 1.5em;
    font-weight: bold;
    transition: 0.3s ease-in-out;
}

.btn-success:hover {
    background-color: #213456;
    color: white;
}

.btn-danger:hover {
    background-color: #ff4d4d;
    color: #fff;
}

/* .modal-content {
    background: #1c1c1c;
    border-radius: 20px;
    border: 1px solid #2c2c2c;
    box-shadow: 0 0 30px rgba(0, 255, 255, 0.1);
} */


@keyframes pulse-glow {
  0% {
    box-shadow: 0 0 10px #00ffff55;
  }
  50% {
    box-shadow: 0 0 20px #00ffffaa;
  }
  100% {
    box-shadow: 0 0 10px #00ffff55;
  }
}

.dashcard:hover {
  animation: pulse-glow 1.5s infinite;
}

#report_data {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 6px;
  background: #213456; 
  color: #ccc;
  font-family: 'Poppins', Tahoma, Geneva, Verdana, sans-serif;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: none; 
}

#report_data thead tr {
  background:#213456;
  color: #666; 
  text-transform: uppercase;
  letter-spacing: 1px;
  font-weight: 700; 
  font-size: 1.2rem; 
  text-align: center;
  border-bottom: 1px solid #ccc; 
}

#report_data tbody tr {
  background: transparent; 
  transition: background 0.25s ease, color 0.25s ease;
  box-shadow: none;
}

#report_data tbody tr:hover {
  background: #E1AD01; 
  color: #fff;
}

#report_data th,
#report_data td {
  padding: 10px 12px;
  border: none;
  border-bottom: 1px solid #333; 
}

#report_data th:last-child,
#report_data td:last-child {
  border-right: none;
}

#report_data tbody tr td {
  font-weight: 400;
  font-size: 0.9rem;
}

#report_data::-webkit-scrollbar {
  height: 6px;
}

#report_data::-webkit-scrollbar-thumb {
  background: #213456; 
  border-radius: 3px;
}

.status-open td {
  color: red !important;
  border: 1px solid red;
}

.status-fixed td {
  color: red !important;
  border: 1px solid red;
}

.status-closed td {
  color: green !important;
  border: 1px solid green;
}

.status-subject-closing td {
  color: #890188 !important;
  border: 1px solid #890188;
}


.action-bar-container {
    display: flex;
    flex-wrap: wrap; 
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
    padding: 15px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    margin-bottom: 20px;
    gap: 15px; 
}

.year-picker-group {
    flex: 1;
    min-width: 300px; 
}
#showCalendarBtn {
    background-color: #213456;
    color: white;
    border-radius: 8px;
    padding: 8px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    white-space: nowrap;
}

#showCalendarBtn:hover {
    background-color: var(--owi-gold, #E1AD01);
    color: #213456;
}

/* Custom Switch Styling */
.form-switch .form-check-input {
    cursor: pointer;
    width: 3em;
    height: 1.5em;
}

/* Mobile Specific Adjustments */
@media (max-width: 768px) {
    .action-bar-container {
        flex-direction: column; /* Stack vertically */
        align-items: stretch; /* Stretch to full width */
        padding: 15px;
    }

    .year-picker-group {
        min-width: 100%;
    }

    .action-bar-container > div:last-child {
        justify-content: space-between; /* Buttons/Switch side-by-side on mobile */
        width: 100%;
    }

    .input-group-text {
        font-size: 0.8rem; /* Smaller text for mobile screens */
    }
}

/* =========================
   MODAL UI UPGRADE (Desktop + Mobile)
   ========================= */

/* Bigger + cleaner modal */
#userModal .modal-dialog{
  max-width: 1100px; /* desktop width */
  margin: 1.25rem auto;
}

#userModal .modal-content{
  border-radius: 16px;
  border: 1px solid rgba(0,0,0,0.08);
  overflow: hidden;
}

/* Header with hierarchy */
#userModal .modal-header{
  background: linear-gradient(180deg, rgba(79,70,229,0.08), rgba(255,255,255,0));
  border-bottom: 1px solid rgba(0,0,0,0.08);
  padding: 16px 18px;
}

#userModal_header{
  font-weight: 700;
  font-size: 18px;
  margin: 0;
}

/* Body spacing */
#userModal .modal-body{
  padding: 16px 18px;
}

/* Section cards inside modal */
.modal-section{
  background: rgba(255,255,255,0.75);
  border: 1px solid rgba(0,0,0,0.08);
  border-radius: 14px;
  padding: 14px;
  box-shadow: 0 8px 22px rgba(0,0,0,0.04);
}

/* Section title */
.modal-section-title{
  font-size: 13px;
  font-weight: 800;
  letter-spacing: .6px;
  text-transform: uppercase;
  color: rgba(0,0,0,0.55);
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 10px;
}

.modal-section-title:before{
  content: "";
  width: 10px;
  height: 10px;
  border-radius: 3px;
  background: rgba(79,70,229,0.55);
}

/* Make inputs feel “premium” */
#userModal .form-control,
#userModal select,
#userModal textarea{
  border-radius: 10px;
  border: 1px solid rgba(0,0,0,0.12);
  background: white;
}

#userModal label{
  font-weight: 700;
  font-size: 12px;
  letter-spacing: .4px;
  text-transform: uppercase;
  color: #213456;
}

/* Comment thread container: scrollable, not endless */
.container_remarks{
  max-height: 480px;
  overflow: auto;
  padding-right: 6px;
}

/* Thread message card look (works with your existing markup) */
#remarks_view .msg-item{
  border: 1px solid rgba(0,0,0,0.08);
  background: rgba(255,255,255,0.90);
  border-radius: 14px;
  padding: 12px 12px;
  margin-bottom: 10px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.04);
}

#remarks_view .msg-head{
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 6px;
}

#remarks_view .msg-name{
  font-weight: 800;
  font-size: 14px;
}

#remarks_view .msg-meta{
  font-size: 12px;
  color: rgba(0,0,0,0.55);
}

#remarks_view .msg-body{
  font-size: 13px;
  color: rgba(0,0,0,0.80);
  line-height: 1.35;
  white-space: pre-wrap;
}

/* Sticky footer actions (great on mobile) */
#userModal .modal-footer{
  border-top: 1px solid rgba(0,0,0,0.08);
  background: rgba(255,255,255,0.92);
  position: sticky;
  bottom: 0;
  z-index: 5;
  padding: 12px 14px;
}

/* Better button sizing on mobile */
@media (max-width: 991px){
  #userModal .modal-dialog{
    max-width: 96%;
    margin: .75rem auto;
  }

  /* Make thread scroll shorter on small screens */
  .container_remarks{
    max-height: 260px;
  }

  /* Buttons full width */
  #action, #btnClose{
    width: 100%;
  }
}

/* --- Scoped Filter Bar Styling --- */
.action-bar-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    padding: 15px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    border-left: 6px solid #213456; /* Brand Blue */
    margin-bottom: 20px;
    gap: 15px;
    flex-wrap: nowrap;
}

body.dark-mode .action-bar-container {
    background: #1e293b;
    border-left-color: #E1AD01; /* Brand Gold */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

/* Year Picker Group */
.year-picker-group {
    flex: 1;
    max-width: 450px;
}

.year-picker-group .input-group-text {
    background-color: #213456 !important;
    color: #ffffff !important;
    border: none;
    font-weight: 600;
    font-size: 0.8rem;
}

/* Calendar Button */
#showCalendarBtn {
    background-color: #E1AD01 !important;
    color: #213456 !important;
    border: none !important;
    font-weight: 700;
    padding: 8px 20px;
    border-radius: 8px;
    white-space: nowrap;
    transition: all 0.2s;
}

#showCalendarBtn:hover {
    background-color: #c99a01 !important;
    transform: translateY(-2px);
}

/* One-line Dark Mode Toggle */
.mode-toggle-inline {
    display: flex;
    align-items: center;
    background: rgba(33, 52, 86, 0.05);
    padding: 6px 15px;
    border-radius: 50px;
    white-space: nowrap;
}

body.dark-mode .mode-toggle-inline {
    background: rgba(225, 173, 1, 0.1);
}

.toggle-label-text {
    font-weight: 700;
    font-size: 0.75rem;
    margin-left: 10px;
    color: #213456;
    text-transform: uppercase;
}

body.dark-mode .toggle-label-text {
    color: #E1AD01;
}


/* ============================= */
/* Modern Card Container */
/* ============================= */
.custom-container-card {
    background: #ffffff;
    border-radius: 18px;
    border: none;
    box-shadow: 0 20px 50px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: 0.3s ease;
}

.custom-container-card:hover {
    transform: translateY(-3px);
}

/* ============================= */
/* Gradient Header */
/* ============================= */
.header-yellow {
    background: linear-gradient(135deg, #E1AD01, #f7c948);
    padding: 18px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-yellow h5 {
    margin: 0;
    font-weight: 700;
    color: #1a1a1a;
    letter-spacing: 1px;
    font-size: 16px;
}

/* ============================= */
/* Modern Search */
/* ============================= */
.search-input-group {
    position: relative;
    width: 260px;
}

.search-input-group i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
}

.search-input-group .form-control {
    padding-left: 40px;
    border-radius: 30px;
    border: none;
    font-size: 13px;
    height: 38px;
    background: rgba(255,255,255,0.9);
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: 0.3s;
}

.search-input-group .form-control:focus {
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    outline: none;
}

.table {
    background-color: #E1AD01;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 8px;
    overflow: hidden;
     box-shadow: 0 5px 5px rgba(21, 69, 159, 0.4);
    border: 1px solid #e9ecef;
    margin-top: -30px;
}


.card .table thead th {
    background-color: #54699e;
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    padding: 15px;
    border-bottom: 2px solid #dee2e6;
}

.card.table tbody td {
    padding: 12px 15px;
    vertical-align: middle;
    color: #333;
    border-bottom: 1px solid #f1f1f1;
}

/* Hover Effect with requested color #213456 */
.card.table tbody tr:hover {
    background-color: #4c81dc !important;
    color: #ffffff !important;
    cursor: pointer;
    transition: all 0.2s ease;
}

/* Responsive Table Wrapper */
.card .table-responsive {
    border-radius: 8px;
    margin-top: 20px;
}

/* Change Password Modal Custom Styles */
#userModal .modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
}

#userModal .modal-header {
    background-color: #213456;
    color: #fff;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    border-bottom: 4px solid #E1AD01; /* Your Theme Gold */
}

#userModal .modal-title {
    font-weight: 700;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
}

#userModal .input-group-text {
    background-color: white;
    border-right: none;
    color: #213456;
}

#userModal .form-control {
    border-left: none;
    height: 45px;
    border-radius: 0 8px 8px 0;
}

#userModal .form-control:focus {
    border-color: #213456;
    box-shadow: none;
}

#userModal .input-group:focus-within {
    box-shadow: 0 0 0 0.2rem rgba(225, 173, 1, 0.25);
    border-radius: 8px;
}

/* --- Left Side: Input Panel --- */
.m_col {
    background: #ffffff;
    padding: 2rem !important;
    border-right: 1px solid #edf2f7;
}

.m_col label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
    display: block;
}

.m_col .form-control {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.6rem 0.75rem;
    transition: all 0.2s ease;
    background-color: #f8fafc;
}

.m_col .form-control:focus {
    background-color: #fff;
    border-color: #1C0770;
    box-shadow: 0 0 0 3px rgba(28, 7, 112, 0.1);
    outline: none;
}

.m_col textarea {
    min-height: 80px;
}

/* --- Right Side: Message Thread Panel --- */
#msg_thread {
    padding: 1rem 1.5rem;
    background-color: #f8fafc;
    height: 100%;
}

/* Comment Input Area */
#addmsg {
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 1rem;
    background: #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

/* Container for Remarks (The Thread) */
.container_remarks {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    height: 450px;
    overflow-y: auto;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Individual Message Bubbles (to be used in your JS output) */
.chat-bubble {
    max-width: 85%;
    padding: 0.8rem 1rem;
    border-radius: 15px;
    font-size: 0.9rem;
    line-height: 1.5;
    position: relative;
}

/* System/Received messages */
.chat-left {
    align-self: flex-start;
    background: #f1f5f9;
    color: #334155;
    border-bottom-left-radius: 2px;
}

/* User/Sent messages */
.chat-right {
    align-self: flex-end;
    background: #1C0770;
    color: #ffffff;
    border-bottom-right-radius: 2px;
}

.msg-meta {
    font-size: 0.7rem;
    color: #94a3b8;
    margin-bottom: 4px;
    display: block;
}

/* --- Action Buttons --- */
.btn-success {
    background-color: #1C0770 !important;
    border: none;
    padding: 0.6rem 2rem;
    font-weight: 600;
    border-radius: 8px;
    transition: transform 0.2s ease;
}

.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(28, 7, 112, 0.2);
}

.btn-danger {
    background-color: #fff;
    border: 1px solid #e2e8f0;
    color: #e53e3e;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    border-radius: 8px;
}

.btn-danger:hover {
    background-color: #fff5f5;
    color: #c53030;
}

/* --- Global Theme Vars --- */
:root {
    --navy-primary: #213456;
    --gold-accent: #E1AD01;
    --chat-bg: #f4f7f9;
}

/* --- Left Side: Refined Input Panel --- */
.m_col {
    background: #ffffff;
    padding: 2rem !important;
    border-right: 1px solid #edf2f7;
}

.m_col label {
    color: var(--navy-primary);
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 0.05em;
}

.m_col .form-control:focus {
    border-color: var(--gold-accent);
    box-shadow: 0 0 0 3px rgba(225, 173, 1, 0.15);
}

/* --- Right Side: Premium Messenger Thread --- */
#msg_thread {
    background-color: #213456;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.container_remarks {
    background: var(--chat-bg);
    height: 500px;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(123, 128, 44, 0.605);
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
    border: none;
}

/* --- Message Bubbles --- */
.msg-bubble {
    max-width: 80%;
    padding: 12px 16px;
    font-size: 0.9rem;
    position: relative;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

/* Received (Support/Other Users) - Navy Style */
.msg-received {
    align-self: flex-start;
    background-color: var(--navy-primary);
    color: #ffffff;
    border-radius: 20px 20px 20px 5px;
}

/* Sent (You) - Gold Style */
.msg-sent {
    align-self: flex-end;
    background-color: var(--gold-accent);
    color: #ffffff;
    border-radius: 20px 20px 5px 20px;
}

/* Bubble Meta Info */
.msg-info {
    font-size: 0.7rem;
    margin-bottom: 4px;
    font-weight: 600;
    display: block;
}

.msg-received .msg-info { color: rgba(255,255,255,0.7); }
.msg-sent .msg-info { color: #ffffff; text-align: right; }

/* --- Chat Input Footer --- */
.chat-input-box {
    background: #ffffff;
    padding: 20px;
    border-top: 1px solid #e2e8f0;
    border-radius: 0 0 12px 0;
}

#addmsg {
    border-radius: 25px;
    padding: 12px 20px;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

#addmsg:focus {
    background-color: #ffffff;
    border-color: var(--gold-accent);
    box-shadow: 0 4px 12px rgba(225, 173, 1, 0.1);
    outline: none;
}

/* --- Buttons --- */
.btn-success {  
    background-color: var(--navy-primary) !important;
    border: 2px solid var(--navy-primary);
    font-weight: 700;
}

.btn-success:hover {
    background-color: #16243d !important;
    border-color: var(--gold-accent);
    color:white;
}

/* Scrollbar Styling */
.container_remarks::-webkit-scrollbar { width: 6px; }
.container_remarks::-webkit-scrollbar-thumb { 
    background: #cbd5e0; 
    border-radius: 10px; 
}


/* --- Card Container Improvement --- */
.card2 {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    background: #ffffff;
}

/* --- Header using your Navy/Gold Theme --- */
.card2 .card-header {
    background-color: #213456 !important; /* Your Navy */
    color: #E1AD01 !important; /* Your Gold */
    font-weight: 700;
    letter-spacing: 1px;
    padding: 1.2rem 1.5rem;
    border-bottom: 2px solid #E1AD01;
    display: flex;
    align-items: center;
}

/* --- Table Refinement --- */
#report_data, #network_tb {
    border-collapse: separate;
    border-spacing: 0 8px; /* Creates a "card-row" look */
    margin-top: -8px;
}

#report_data thead th, #network_tb thead th {
    background-color: #f8fafc;
    color: #213456;
    text-transform: uppercase;
    font-size: 0.75rem;
    font-weight: 800;
    border: none;
    padding: 15px;
}

#report_data tbody tr, #network_tb tbody tr {
    background-color: #213456;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

/* Hover Effect: Glows slightly and shows your Gold theme */
#report_data tbody tr:hover, #network_tb tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(33, 52, 86, 0.1);
    background-color: #E1AD01; /* Very faint gold tint */
}

#report_data td, #network_tb td {
    padding: 15px;
    vertical-align: middle;
    border-top: 1px solid #edf2f7;
    border-bottom: 1px solid #edf2f7;
    color: #4a5568;
}

/* --- Status Badge Styles (Apply these in your HTML/PHP logic) --- */
.badge-modern {
    padding: 6px 12px;
    border-radius: 30px;
    font-weight: 600;
    font-size: 0.7rem;
}

/* Custom Scrollbar for the table container */
#proTeamScroll::-webkit-scrollbar {
    width: 6px;
}
#proTeamScroll::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 10px;
}




    

    
    





</style>

<!-- =========================
     DASHBOARD MAIN WRAPPER
     ========================= -->
<div class="container-fluid">
  <div id="wrapper">
    <div id="layoutSidenav_content">
      <div class="container-fluid">
        <!-- Hidden Form -->
        <form method="post" name="cof_form" id="cof_form" enctype="multipart/form-data">
          <div class="row">
            <input type="hidden" name="chcksbjcls" id="chcksbjcls" value="check">
          </div>
        </form>
        <div class="action-bar-container" style="box-shadow: 0 5px 10px 2px #2062ac; margin-bottom: -20px;">
          <div class="year-picker-group">
            <div class="input-group">
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="fas fa-history me-2"></i>LOGS IN YEAR OF:
                </span>
              </div>
              <select class="form-control" name="yearpicker" id="yearpicker"required>
                <option value="2019,2020,2021,2022,2023,2024,2025,2026">OVERALL</option>
                <option value="2026" selected>2026</OPTION>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
              </select>
            </div>
          </div>
          <div class="d-flex align-items-center gap-3">
            <form action="testcalendar.php" method="POST" class="m-0">
              <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id'];?>">
              <button type="submit" id="showCalendarBtn" class="btn">
                <i class="fas fa-calendar-alt me-2"></i>CALENDAR
              </button>
            </form>
            <div class="form-check form-switch float-right m-3">
              <input class="form-check-input" style="margin-left:-50px;" type="checkbox" id="darkModeToggle">
              <label class="form-check-label text-dark" for="darkModeToggle">Dark Mode</label>
          </div>
        </div>
      </div>
    </div>

    <!-- KPI CARDS (replaces card-deck properly) -->
    <div class="main-container">  
      <main class="p-4">
        <div class="row g-4">
          <div class="row g-4 mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6">
              <div class="card h-100 dashcard-clickable" data-filter="" style="border-radius: 15px; cursor:pointer;">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class=" bg-opacity-10 p-3 rounded-circle " style="color: #2062ac;">
                      <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                    <h2 class="fw-black mb-1" id="count_total" style="font-size:2.2rem; letter-spacing: -1px; ">0</h2>
                  </div>
                  <div>
                    <p class=" fw-bold text-uppercase mb-0" style="font-size: 0.75rem; color: #2062ac;letter-spacing: 1px;">Total Reports</p>
                    <hr class="mt-2 mb-3" style="border-top: 2px solid #2062ac; opacity: 1; width: 100%;"/>
                    <div class="d-flex justify-content-between align-items-center">
                      <a href="#report_data" class="text-decoration-none small text-muted stretched-link">Click here for more info</a>
                      <i class="fas fa-chevron-right small text-muted"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
              <div class="card  h-100 dashcard-clickable" data-filter="ON PROCESS" style="border-radius: 15px; cursor: pointer;">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class=" bg-opacity-10 p-3 rounded-circle" style="color: #E5BA41;">
                      <i class="fas fa-spinner fa-2x"></i>
                  </div>
                  <h2 class ="fw-black mb-1" id="count_open" style="font-size: 2.2rem; letter-spacing: -1px;">0</h2>
                </div>
                <div>
                  <p class="text-warning fw-bold text-uppercase mb-0" style="font-size: 0.75rem;color: #E5BA41; letter-spacing: 1px;">On Process</p>
                  <hr class="mt-2 mb-3" style="border-top: 2px solid #E5BA41;; opacity:1; width:100%;"/>
                  <div class="d-flex justify-content-between align-items-center">
                    <a href="#report_data" class="text-decoration-none small text-muted stretched-link">Click here for more info</a>
                    <i class="fas fa-chevron-right small text-muted"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card h-100 dashcard-clickable" data-filter="ATTENDED WITH FIX ASSET" style="border-radius: 15px; cursor:pointer;">
              <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger" style="color: #D25353;">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                  </div>
                  <h2 class="fw-black mb-1" id="count_owfa" style="font-size:2.2rem; letter-spacing: -1px;">0</h2>
                </div>
                <div>
                  <p class="text-danger fw-bold text-uppercase mb-0" style="font-size:0.75rem; color: #D25353;letter-spacing:1px;">Over Sla / Pending</p>
                  <hr class="mt-2 mb-3" style="border-top: 2px solid #D25353; opacity:1; width:100%;"/>
                  <div class="d-flex justify-content-between align-items-center">
                    <a href="#report_data" class="text-decoration-none small text-muted stretched-link">Click here for more info</a>
                    <i class="fas fa-chevron-right small text-muted"></i>
                  </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
          <div class="card h-100 dashcard-clickable" data-filter="CLOSED" style="border-radius: 15px; cursor:pointer;">
            <div class="card-body p-4">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success" style="color: #94A378;">
                  <i class="fas fa-check-double fa-2x"></i>
                </div>
                <h2 class="fw-black mb-1" id="count_closed" style="font-size:2.2rem; letter-spacing: -1px;">0</h2>
              </div>
              <div class="mb-2">

              </div>
              <div>
                <p class="text-success fw-bold text-uppercase mb-0" style="font-size:0.75rem; color: #94A378; letter-spacing: 1px;">Closed Reports</p>
                <hr class="mt-2 mb-3" style="border-top: 2px solid #94A378; opacity:1; width:100%;"/>
                <div class="d-flex justify-content-between align-items-center">
                  <a href="#report_data" class="text-decoration-none small text-muted stretched-link">View History</a>
                  <i class="fas fa-chevron-right small text-muted"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
        <!-- CHARTS -->
        <div class="row" id="ovrall">

          <div class="col-12 col-lg-6 mb-3">
            <div class="card card2 h-100">
              <h5 class="card-header" style="background-color: #2062ac;">Overall Status</h5>
              <div class="card-body">
                <div id="chartdiv5"></div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6 mb-3">
            <div class="card card2 h-100">
              <h5 class="card-header text-black"  style="background-color: #2062ac;">H.R Support Logs</h5>
              <div class="card-body">
                <div id="chartdiv8"></div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6 mb-3">
            <div class="card card2 h-100">
              <h5 class="card-header text-black"  style="background-color: #2062ac;">Recently enrolled reports.</h5>
              <div class="card-body">
                <div id="chartdiv1"></div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6 mb-3">
            <div class="card card2 h-100">
              <h5 class="card-header text-black"  style="background-color: #2062ac;">CATEGORIES</h5>
              <div class="card-body">
                <div id="chartdiv2" name="chartdiv2"></div>
              </div>
            </div>
          </div>

          <div class="col-12 mb-3">
            <div class="card card2">
              <h5 class="card-header text-black"  style="background-color: #2062ac;">Number of Escalated Reports Per Area</h5>
              <div class="card-body">
                <div id="chart_area"></div>
              </div>
            </div>
          </div>

          <div class="col-12 mb-3">
            <div class="card card2">
              <h5 class="card-header text-black"  style="background-color: #2062ac;">Non Compliant Stores on End of Day Process (7:AM CUT OFF)</h5>
              <div class="card-body">

                <div class="row mb-3">
                  <div class="col-12 col-md-8 col-lg-6">
                    <div class="input-group">
                      <span class="input-group-text">FROM</span>
                      <input type="date" id="frompolDate" class="form-control">
                      <span class="input-group-text">TO</span>
                      <input type="date" id="topolDate" class="form-control">
                    </div>
                  </div>
                </div>

                <div id="chart_polled"></div>

              </div>
            </div>
          </div>

        </div><!-- /#ovrall -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" />

        <!-- TABLES -->
        <div class="row">

          <div class="col-12 mb-3">
            <div class="card card2">
              <h5 class="card-header text-black"  style="background-color: #2062ac;">TICKETS</h5>
              <div class="card-body">

                <div class="row">
                  <!-- old code with overflow -->
                  <!-- <div class="col-12 mb-3">  
                     <div class="table-responsive" id="proTeamScroll" style="max-height:450px; width:100%;overflow-y:auto;">
                    <table id="report_data" class="table table-hover">

                    </div>
                  </div> -->

                                    <div class="col-12 mb-3">
                     <div class="table-responsive" id="proTeamScroll" style="">
                    <table id="report_data" class="table table-hover">

                    </div>
                  </div>

                  <div class="col-12">
                     <div class="table-responsive" id="proTeamScroll" style="max-height:450px; width:100%;overflow-y:auto;">
                      <table id="network_tb" class="table table-hover">

                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

        </div><!-- /.row -->

        <div class="col-lg-12 Down" id="Down">
          <input type="hidden" id="myInput">
        </div>

      </div><!-- /.container-fluid -->
    </div><!-- /#layoutSidenav_content -->
  </div><!-- /#wrapper -->
</div><!-- /.container-fluid -->


<!-- =========================
Start of Add/Edit Modal
========================= -->
<div class="col-12 col-lg-12 modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="max-width: 100%;">
    <form method="post" id="report_form" enctype="multipart/form-data">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title" id="userModal_header" value="Add Report"></h4>
        </div>

        <div class="modal-body">
          <div class="row">

            <!-- LEFT SIDE -->
            <div class="m_col col-12 col-lg-6">

              <div class="row">

                <div class="form-group col-12 col-md-6">
                  <label>STORE</label>
                  <input type="hidden" name="str_num" id="str_num" readonly value="">
                  <select class="form-control form-control-sm" name="store" id="store" required>
                    <option value="">Select Store...</option>
                    <?php
                      $query="select * from tbl_branch ";
                      $run=$conn->prepare($query);
                      $run->execute();
                      $rs=$run->get_result();
                      while ($res=$rs->fetch_assoc()) {
                        $brcnhid = $res['str_num'];
                        $brnchcd = $res['str_code'].' | '.$res['str_name'];
                    ?>
                      <option value="<?php echo $brcnhid; ?>"><?= $brnchcd; ?></option>
                    <?php } ?>
                  </select>
                </div>

                <input type="hidden" class="form-control form-control-sm" name="ticket_no" id="ticket_no">

                <div class="form-group col-12">
                  <label>SUBJECT/CONCERN</label>
                  <textarea name="subjct" id="subjct" class="form-control form-control-sm"
                    placeholder="Input Concern" style="text-transform:uppercase"
                    onkeyup="this.value = this.value;"></textarea>
                </div>

                <div class="form-group col-12 col-md-4">
                  <label>VIA</label>
                  <select class="form-control form-control-sm" name="via" id="via" required>
                    <option value=""> &larr; VIA &rarr;</option>
                    <?php
                      $query="select * from via_main";
                      $run=$conn->prepare($query);
                      $run->execute();
                      $rs=$run->get_result();
                      while ($res=$rs->fetch_assoc()) {
                    ?>
                      <option><?= $res['via_desc'] ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="form-group col-12 col-md-8">
                  <label>H.R SUPPORT</label>
                  <input type="hidden" name="it_num" id="it_num" readonly>
                  <select class="form-control form-control-sm" name="itsup" id="itsup" required>
                    <option value="">Assign support...</option>
                    <?php
                      $query="select * from it_tech WHERE deptsel = '11' AND itsup NOT IN ('4','7','8','12','14')";
                      $run=$conn->prepare($query);
                      $run->execute();
                      $rs=$run->get_result();
                      while ($res=$rs->fetch_assoc()) {
                        $tchid = $res['itsup'];
                        $tchdesc = $res['it_desc'];
                    ?>
                      <option value="<?php echo $tchid; ?>"><?= $tchdesc; ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="form-group col-12 col-md-4">
                  <label>CATEGORY</label>
                  <input type="hidden" name="cat_num" id="cat_num" readonly>
                  <select class="form-control form-control-sm" name="cat" id="cat" required>
                    <option value=""> &larr; CATEGORY &rarr;</option>
                    <?php
                      $query="select * from category WHERE deptsel = '11'";
                      $run=$conn->prepare($query);
                      $run->execute();
                      $rs=$run->get_result();
                      while ($res=$rs->fetch_assoc()) {
                        $supid = $res['id'];
                        $suppdesc = $res['category_name'];
                    ?>
                      <option value="<?php echo $supid; ?>"><?= $suppdesc; ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="form-group col-12 col-md-4">
                  <label>SUB CATEGORY</label>
                  <input type="hidden" name="sub_num" id="sub_num" readonly>
                  <select class="form-control form-control-sm" name="sub" id="sub"></select>
                </div>

                <div class="form-group col-12 col-md-4 hide_isp">
                  <label for="isp" id="lbl_isp">Service Provider</label>
                  <input type="hidden" name="isp_num" id="isp_num" readonly>
                  <select class="form-control form-control-sm" name="isp" id="isp">
                    <option value="">Select Network Provider</option>
                    <?php
                      $query="select * from tbl_isp";
                      $run=$conn->prepare($query);
                      $run->execute();
                      $rs=$run->get_result();
                      while ($res=$rs->fetch_assoc()) {
                        $ispid = $res['isp_id'];
                        $ispdesc = $res['isp_shortDesc'];
                    ?>
                      <option value="<?php echo $ispid; ?>"><?= $ispdesc; ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="form-group col-12 col-md-4 hide_isp">
                  <label id="lbl_refNo" for="refNo">Reference No:</label>
                  <input type="text" class="form-control form-control-sm" name="refNo" id="refNo">
                </div>

                <div class="form-group col-12 col-md-4 hide_isp">
                  <label for="date_refNo" class="text" id="lbl_DtRefNo">Date of RefNo</label>
                  <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                    <input type="text" name="date_refNo" id="date_refNo"
                      class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker3"/>
                    <div class="input-group-append" data-target="#date_created" data-toggle="datetimepicker">
                      <input type="hidden" class="form-control form-control-sm" name="date_createdx" id="date_createdx">
                      <div class="input-group-text" id="ico_cal3"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>

                <div class="form-group col-12 col-md-4">
                  <label>STATUS</label>
                  <select class="form-control form-control-sm" name="status" id="status" required>
                    <option value=""> &larr; Status &rarr;</option>
                    <?php
                      $query="select * from status  WHERE it_module_tag = 'Y'";
                      $run=$conn->prepare($query);
                      $run->execute();
                      $rs=$run->get_result();
                      while ($res=$rs->fetch_assoc()) {
                    ?>
                      <option><?= $res['stat_desc'] ?></option>
                    <?php } ?>
                    <option value="CLOSED" readonly>CLOSED</option>
                  </select>
                </div>

                <div class="form-group col-12 col-md-4 hide_cl">
                  <label id="dateclabel" class="hidden">DATE CLOSED</label>
                  <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                    <input type="text" name="date_closed" id="date_closed"
                      class="form-control form-control-sm datetimepicker-input"
                      data-target="#datetimepicker2" autocomplete="off"/>
                    <div class="input-group-append" data-target="#date_closed" autocomplete="off" data-toggle="datetimepicker">
                      <div class="input-group-text" id="ico_cal" name="ico_cal"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>

                <div class="form-group col-12 col-md-4 hide_cl">
                  <label id="clby_label" class="hidden">CLOSED BY</label>
                  <input type="hidden" name="close_by" id="close_by" value="<?php echo $_SESSION['tech_id']; ?>">
                  <input type="text" class="form-control form-control-sm" name="cl_desc" id="cl_desc" readonly
                    value="<?php echo $_SESSION['fname']. '  ' . $_SESSION['lstname']; ?>">
                </div>

                <div class="form-group col-12">
                  <label>Work Output:</label>
                  <textarea name="remarks" id="remarks" class="form-control form-control-sm" placeholder="Your Workoutput"></textarea>
                </div>

                <div class="col-12">
                  <label style="font-weight: bold;">Attached File:</label>
                  <p><input id="file-input" type="file" name="file" Multiple></p>
                </div>

                <div class="col-12"><hr/></div>

                <div class="col-12 d-flex justify-content-between align-items-center">
                  <input type="submit" name="action" id="action" class="btn btn-success" value="Add">
                  <button type="button" name="btnClose" id="btnClose" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

                <div class="col-12"><hr/></div>

                <div class="card" id="img" name="img"></div>

              </div><!-- /.row -->

            </div><!-- /.left -->

            <!-- RIGHT SIDE -->
            <div class="col-12 col-lg-6">

              <div id="msg_thread">

                <div class="col-12 mb-3 px-0">
                  <label style="font-weight: bold; color:white;">Add Comment:</label>
                  <textarea name="admsg" id="addmsg" class="form-control form-control-sm"
                    placeholder="Reply to their message or give an updates regarding on this ticket..." required></textarea>
                </div>

                <div class="col-12 mt-4 mb-2 dv_msg px-0">
                  <label for="remarks_view" style="font-weight: bold; color:white;">Comment Thread:</label>
                  <hr>
                  <div class="container_remarks">
                    <div id="remarks_view"></div>
                  </div>
                </div>

              </div><!-- /#msg_thread -->

            </div><!-- /.right -->

          </div><!-- /.row -->
        </div><!-- /.modal-body -->

        <div class="modal-footer">
          <input type="hidden" name="operation" id="operation" value="Add">
          <input type="hidden" name="u_id" id="u_id" value="<?php echo $_SESSION['user_id']; ?>">
        </div>

      </div><!-- /.modal-content -->
    </form>
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
  $(document).ready(function() {
    // KPI Card Click Functionality
    $('.dashcard-clickable').on('click', function() {
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
});
</script>


