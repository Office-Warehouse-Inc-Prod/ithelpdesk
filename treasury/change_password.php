<?php
include 'userheader.php';
 ?>
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


/* Your existing hover rule */

.card:hover{
    border-color: #E5BA41;
}

.card:hover .card-header,
.card:hover a{
    color: #E1AD01 !important;
}
</style>

 <div class="container">

<div class="card w-100">
  <h5 class="card-header">Change Password</h5>
  <div class="card-body">
     <form method="post" id="change_passform">
      <div class="row">

          <div class="col-md-12 mb-3">
            <!-- <label for="email">Current Password</label> -->
            <input type="password" class="form-control" name="curpass" id="curpass" aria-describedby="emailHelp" placeholder="Current Password">
          </div>
          <div class="col-md-12 mb-3">
            <!-- <label for="password">New Password</label> -->
            <input type="password" class="form-control" name="newpass" id="newpass" placeholder="New Password" required>
          </div>
          <div class="col-md-12 mb-3">
            <!-- <label for="confirm_password">Confirm Password</label> -->
            <input type="password" class="form-control" name="confrm_nwpass" id="confrm_nwpass" placeholder="Confirm Password">
          </div>

      </div>


     
        <div class="modal-footer border-top-0 d-flex justify-content-center">
          <input type="hidden" name="operation" id="operation" value="changepass" /> 
          <button id="btn_chngepass" class="btn btn-success">Submit</button>
        </div>
      </form>
  </div>
</div>




 </div>



<script type="text/javascript">
  let oldPass= $('#usr_oldpas').val();
  
$(document).ready(function() {
  console.log(oldPass);
});

//   setTimeout(function() {
//     $('#changepass_modal').modal({
//             backdrop: 'static',
//             keyboard: false
//     });
// }, 1000);

  //   $('#btn_chngepass').on("click", function () {
  //     // alert("TEST");
  //     $.ajax({
  //       url: "../admin/insert.php",
  //       method: "POST",
  //       data: $('#change_passform').serialize(),
  //       success: function (data) {
  //         alert("Updated successfully");
  //         $("#change_passform")[0].reset();
  //         $("#changepass_modal").modal("hide");
            
  //       },
  //     });

  // });

        $('#btn_chngepass').on("click", function () {
        if ($('#newpass').val() != $('#confrm_nwpass').val()) {
          alert("New password is not the same in confirm password!")
          return false
        }
        else {
              $.ajax({
                url: "fetch.php",
                method: "POST",
                data: $('#change_passform').serialize(),
                success: function (data) {
                   Swal.fire({
                            icon: 'success',
                            title: 'Your work has been saved',
                            showConfirmButton: false,
                            timer: 1500
                  }).then(function(){
                     window.location.replace("userpanel.php");
                  });
                },

              });
        }



  });


</script>