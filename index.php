<?php

include 'main_ses.php';
  ?>

<style>

body {
    font-family: "Roboto", sans-serif;
    background-color:#040414 !important;
}  
p {
    color: #b3b3b3;
    font-weight: 300; 
    font-size: .9em;
}
h1{
    font-family: "Roboto", sans-serif;
    color: white !important;
}
.content {
    padding: 0; 
}
.content .logo{
    height: 8%;
    width: 8%;
    margin: 20px;
}
@media (max-width: 991.98px) {
    .content .bg {
    height: 500px; } 
}
.content .contents, .content .bg {
    width: 50%; 
}
@media (max-width: 1199.98px) {
    .content .contents, .content .bg {
     width: 100%; } 
}
.content .bg {
    background-size: cover;
    background-position: center; 
}
  
.input-box{
    position: relative;
    width: 100%;
    height: 50px;
    border-bottom: 2px solid #808080;
    margin: 30px 0;
}
.input-box label{
    position: absolute;
    top: 40px;
    left: 5px;
    transform: translateY(-100%);
    font-size: 1em;
    color: #ffcc0c;
    font-weight: 500;
    pointer-events: none;
    transition: .5s;
}
.input-box input:focus~label,
.input-box input:valid~label{
    top: -5px;
}
.input-box input{
     color: white;
    width: 100%;
    height: 80%;
    background: transparent;
    border: none;
    outline: none;
    font-size: 1em;
    font-weight: 600;
    padding: 0 35px 0 5px;
}
.input-box .icon{
    position: absolute;
    right: 8px;
    font-size: 1.2em;
    line-height: 57px;
    color: white;
}
.checkbox label input{
    margin-bottom: 20px;
    accent-color:#ffcc0c;    
}
.checkbox label{
    color: white;
}
.btn-login{
    width: 100%;
    height: 45px;
    border: none;
    outline: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 1em;
}
.btn-login:hover{
    font-weight: 600;
    border: none;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    cursor: pointer;
    background: #ffcc0c;
    color: black;
}




</style>  

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    
    <title>Login | Office Warehouse Inc.</title>
    <link rel="icon" type="image/x-icon" href="assets/images/owilogo.jpeg">
  </head>
  <body>
  <br><br><br><br>
  <div class="content">
     <div class="logo">
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <img src="assets/helpdesk.png" alt="Image" class="img-fluid">
        </div>
        <div class="col-md-6 contents">
          <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="row">
                <div class="col-md-4 mb-4">
            <img src="assets/owilogo.jpeg" alt="Image" style="width: 120px;" class="img-fluid">

              <!-- <p class="mb-4">Login</p> -->
            </div>
              <div class=" col-md-8 mb-4">
              <h1>OWI HELPDESK</h>
              <p class="mb-4">Login</p>
            </div>
                </div>


  <?php if(!empty($message)): ?>
    <p id="err_mes" class="text-white"><?= $message ?></p>
  <?php endif; ?>
  <form method="post" id="report_form" enctype="multipart/form-data">
            <div class="input-box">
              <span class="icon"><ion-icon name="mail-outline"></ion-icon></span>
                <input type="text" name="email" required>
                <label>Username</label>
            </div>
            <div class="input-box">
                <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
                <input type="password" name="password" required>
                <label>Password</label>
            </div>
            <div class="checkbox">
                <!-- <label><input type="checkbox">Remember me</label> -->
            </div>
              <input type="submit"  class="btn-login" value="LogIn">
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  </body>
</html>


<!-- Log in page updated by OJT Paula Ramos  -->

<script type="text/javascript">
  

setTimeout(function() {
  $("#err_mes").remove();
}, 5000);



</script>