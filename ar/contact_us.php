<?php
include 'userheader.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
<link rel="stylesheet" href="../css/4bootstrap.min.css" />
<script src="../js/moment.min.js"></script>

<script src="https://use.fontawesome.com/f942a1dc17.js"></script>

<style>
   :root {
  --primary-color: #E1AD01;
  --primary-light: #F4F0FF;
  --bg-body: #F4F5FA;
  --sidebar-width: 260px;
  --topbar-height: 70px;
  --card-shadow: 0 4px 12px 0 rgba(58, 53, 65, 0.1);
}


  /* ===== Reduce Header Height ===== */
.navbar,
header,
.topbar,
.navbar-default {
    padding-top: 6px !important;
    padding-bottom: 6px !important;
    min-height: 60px !important;
}

.navbar-brand {
    font-size: 18px !important;
    font-weight: 700;
}

.navbar img {
    max-height: 42px !important;
}

  .container-fluid.mt-4{ padding-left:16px; padding-right:16px; }

  

  /* sticky form on desktop */
  @media (min-width: 992px){
    .sticky-form{
      position: sticky;
      top: 16px;
      align-self: flex-start;
    }
  }

  /* text + labels */
  label{
    color: var(--text);
    font-weight: 800 !important;
    margin-bottom: 6px;
  }
  small, .text-muted{ color: var(--muted) !important; }

  /* inputs */
  .form-control, .custom-select, select.form-control{
    background: #fff !important;
    color: var(--text) !important;
    border: 1px solid var(--border) !important;
    border-radius: 12px !important;
    padding: 10px 12px !important;
    height: auto !important;
    transition: .15s ease;
  }
  .form-control:focus, select.form-control:focus, textarea:focus{
    border-color: rgba(37,99,235,.55) !important;
    box-shadow: 0 0 0 .25rem rgba(37,99,235,.18) !important;
    outline: none !important;
  }
  .form-control::placeholder{ color: #9aa3b2; }

  /* textarea */
  .cttxtarea{
    width: 100%;
    min-height: 140px;
    resize: vertical;
    background: #fff;
    color: var(--text);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 12px;
    line-height: 1.35;
  }

  /* file input */
  #file-input{
    width: 100%;
    padding: 10px;
    background: #fff;
    border: 1px dashed #cfd6e6;
    border-radius: 14px;
    color: var(--muted);
  }

  /* buttons */
  .btn{
    border-radius: 12px !important;
    font-weight: 800;
    padding: 10px 14px;
  }
  .btn-primary{
    background: var(--primary) !important;
    border-color: var(--primary) !important;
    box-shadow: 0 10px 18px rgba(37,99,235,.18);
  }
  .btn-primary:hover{ background: var(--primary2) !important; border-color: var(--primary2) !important; }
  .btn-success{
    background: var(--success) !important;
    border-color: var(--success) !important;
    box-shadow: 0 10px 18px rgba(22,163,74,.16);
  }

  /* section divider */
  .soft-divider{
    height: 1px;
    background: var(--border);
    margin: 12px 0 14px;
  }

  /* tables */
  table.table{
    color: var(--text);
    border-color: var(--border) !important;
    margin-bottom: 0;
    background: #fff;
  }
  .table thead th{
    background: #f6f8fd;
    color: #374151;
    border-color: var(--border) !important;
    font-weight: 900;
    white-space: nowrap;
  }
  .table td, .table th{ border-color: var(--border) !important; vertical-align: middle !important; }
  .table.hover tbody tr:hover{ background: #f8fbff !important; }

  /* datatables */
  .dataTables_wrapper .dataTables_filter input,
  .dataTables_wrapper .dataTables_length select{
    background: #fff !important;
    border: 1px solid var(--border) !important;
    color: var(--text) !important;
    border-radius: 10px !important;
  }
  .dataTables_wrapper .dataTables_info,
  .dataTables_wrapper .dataTables_paginate{
    color: var(--muted) !important;
  }
  .page-link{
    background: #fff !important;
    border: 1px solid var(--border) !important;
    color: var(--text) !important;
  }
  .page-item.active .page-link{
    background: var(--primary) !important;
    border-color: var(--primary) !important;
    color: #fff !important;
  }

  /* mobile */
  @media (max-width: 767.98px){
    .container-fluid.mt-4{ margin-top: 12px !important; }
    .card-body{ padding: 14px !important; }
    #addmsg{ width: 100%; }
    #stat_picker{ width: 100%; margin-top: 8px; }
    .w-100-mobile{ width: 100% !important; }
  }

  /* tiny polish */
  .section-title{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
  }
  .section-title small{
    color: var(--muted);
    font-weight: 700;
  }

  /* ... KEEP YOUR EXISTING CSS ABOVE ... */

  /* Make the row breathe */
  .container-fluid.mt-4 { padding-left:16px; padding-right:16px; }
  .container-fluid.mt-4 .row { margin-left:-8px; margin-right:-8px; }
  .container-fluid.mt-4 .row > [class*="col-"] { padding-left:8px; padding-right:8px; }

  /* IMPORTANT: sticky should be on the card, not the whole column */
  @media (min-width: 992px){
    .sticky-form{ position: static; } /* override your current sticky-form */
    .sticky-form .card{ position: sticky; top: 16px; }
  }

  /* Right cards should never look squeezed */
  #dvtables, #itmcard{
    width: 100% !important;
    max-width: 100%;
  }

  /* Make DataTables area stable */
  .dt-wrap{
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 14px;
  }
  table.dataTable{ width: 100% !important; }
  #reports_table, #items_table{ width: 100% !important; table-layout: auto; }

  /* Header controls: better alignment, no squeezing */
  .ticket-controls{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    flex-wrap:wrap;
  }
  .ticket-controls .left-actions{
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
  }
  .ticket-controls .right-actions{
    margin-left:auto;
    min-width: 220px;
  }

  /* Mobile: full width controls */
  @media (max-width: 767.98px){
    .ticket-controls{ flex-direction:column; align-items:stretch; }
    .ticket-controls .right-actions{ min-width: 100%; }
    #addmsg{ width:100%; }
    #stat_picker{ width:100%; }
  }

  /* ===== Header / Navbar color override ===== */
.navbar,
header,
.topbar,
.navbar-default{
  background: #121C31 !important;
  border-color: rgba(255,255,255,.12) !important;
}

/* Brand + links */
.navbar .navbar-brand,
.navbar .navbar-brand span,
.navbar a,
.navbar-nav > li > a{
  color: #ffffff !important;
}

/* Hover/focus */
.navbar a:hover,
.navbar-nav > li > a:hover,
.navbar a:focus,
.navbar-nav > li > a:focus{
  color: #E5E7EB !important;
  opacity: .95;
}

/* Dropdown + caret (if any) */
.navbar .dropdown-menu{
  background: #121C31 !important;
  border: 1px solid rgba(255,255,255,.12) !important;
}
.navbar .dropdown-menu a{
  color: #ffffff !important;
}
.navbar .dropdown-menu a:hover{
  background: rgba(255,255,255,.08) !important;
}

/* Icons */
.navbar i, .navbar .fa, .navbar .fas{
  color: #ffffff !important;
}


      
.card{
    border-radius:10px;
    border:none;
    position:relative;
    margin-bottom:10px;  
    box-shadow: 0  5px 2px #2d3c597f;
    color: #465172;
    overflow-x: hidden;
    border: 2px solid transparent;
    transition: border-color 0.3 ease, color 0.3s ease;
    background-color: #f1f4f4;

    
}
.card .card-header{
  color:white;
    border-bottom-color: #213456;
    line-height:30px;
    font-size:20px;
 
    width:100%;
   
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
}

.card:hover .card-header,
.card:hover a{
    color: #E1AD01 !important;
}
</style>



<div class="col-md-12 col-sm-12 col-12">
    <div class="card">
        <div class="card-header">IT SCHEDULE AND CONTACT INFORMATION</div>
        <div class="card-body text-dark">
              <span class="info-box-text font-weight-bold">Monday to Friday - 8:00 AM to 8:00 PM</span>
                <p>Tel Nos: 8567-3235, 8376-0841,8376-0887,8567-3261,8567-3545
                    <br>Local Nos: <br>8201 (Raymart manalo);
                               <br>8202 (Arnold Penilla)
                             <br>  8303 (Renafe Ilao)
<br>
<br>
                </p>
                <p><span class="font-weight-bold"> &#x2022; Saturday</span> - 8:00 AM to 5:00 PM (For IT Technical Support Only)
                </p>
                <p><span class="font-weight-bold"> &#x2022; Sunday</span>  - kindly escalate issues to IT Helpdesk(Raymart Manalo) who will forward issues to assigned IT support or IT subject expert.
                </p>
                <p><span class="font-weight-bold">NOTE:</span> &nbsp; Weekend support will be limited to POS or Retail Pro operations only while other encountered issues can be logged to IT Helpdesk Website.</p>

        </div>
    </div>


    <div class="col-md-12 col-sm-12 col-12">
    <div class="card">
        <div class="card-header">Mobile Contact Nos:</div>
        <div class="card-body text-dark">
             <div class="info-box-content">
                <span class="font-weight-bold text-center"></span>
                <table id="example" class="text-center display table-bordered  table-hover table-dark" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Mobile Nos.</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Raymart Manalo</td>
                <td>IT Helpdesk</td>
                <td>0917-8628024</td>
	    </tr>
            <tr>
                <td>Gerald Decena</td>
                <td>IT Technical Support</td>
                <td>0917-8628243</td>
	    </tr>
            <tr>
                <td>Gerald Balasta</td>
                <td>IT Technical Support</td>
                <td>0994-3300528</td>
	    </tr>
            <tr>
                <td>John Lexter Domingo</td>
                <td>IT Technical Support</td>
                <td>0965-2961571</td>
            </tr>
            <tr>
                <td>Reynan Del Rosario</td>
                <td>IT Technical Support</td>
                <td>0917-8627938</td>
            </tr>
            <tr>
                <td>Joel Ballesteros</td>
                <td>IT Technical Support</td>
                <td>0917-8628113</td>
            </tr>
            <tr>
                <td>Mark Allan B. Espinal</td>
                <td>Jr. Systems Developer</td>
                <td>0998-7538068</td>
            </tr> 
            <tr>
                <td>Crisjerando Gutana</td>
                <td>Jr. Programmer</td>
                <td>0955-1445880</td>
            </tr> 

            <tr>
                <td>Renafe Ilao</td>
                <td>Systems Admin Assistant</td>
                <td>0935-8926389</td>
            </tr>    
            <tr>
                <td>Florante Retana</td>
                <td>Systems Admin Officer</td>
                <td>0917-8628042</td>
            </tr>    
            <tr>
                <td>Arnold Penilla</td>
                <td>Server and Network Administrator</td>
                <td>0917-3064459</td>
            </tr>   
            <tr>
                <td>Donnaflor Gonzales</td>
                <td>IT Manager</td>
                <td>	
0917-8627993 /
0919-2905801</td>
            </tr>                                                                     
        </tbody>
    </table>

              </div>
              </div>
        </div>
    </div>


  
</body>
</html>
