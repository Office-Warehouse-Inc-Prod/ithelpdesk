<?php

include 'main_ses.php';
  ?>







<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" href="images/owi.ico" type="image/icon type">

  <style type="text/css">


html, body {
  width: 100%;
  height:100%;
}

body {
    background: linear-gradient(-45deg, rgba(2,0,36,1) 0%, rgba(0,0,0,1) 77%, rgba(0,212,255,1) 98%, #434343);
    background-size: 400% 400%;
    animation: gradient 15s ease infinite;
}

@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

        .card {
            border: 1px solid #eeeeee;
        }
        .card-login {
            margin-top: 130px;
            padding: 18px;
            max-width: 30rem;
            box-shadow: inset 0 3px 6px rgba(0,0,0,0.16), 0 4px 6px rgba(0,0,0,0.45);
            border-radius: 10px;
        }

        .card-header {
            color: #fff;
            /*background: #ff0000;*/
            font-family: sans-serif;
            font-size: 20px;
            font-weight: 600 !important;
            margin-top: 10px;
            border-bottom: 0;
        }

        .input-group-prepend span{
            width: 50px;
            background-color: #5383D3;
            color: #fff;
            border:0 !important;
        }

        input:focus{
            outline: 0 0 0 0  !important;
            box-shadow: 0 0 0 0 !important;
        }

        .login_btn{
            width: 130px;
        }

        .login_btn:hover{
            color: #fff;
            background-color: #5383D3;
        }

        .btn-outline-primary {
            color: #fff;
            font-size: 18px;
            background-color: #5383D3;
            background-image: none;
            border-color: #28a745;
        }

        .form-control {
            display: block;
            width: 100%;
            height: calc(2.25rem + 2px);
            padding: 0.375rem 0.75rem;
            font-size: 1.2rem;
            line-height: 1.6;
            color: #28a745;
            background-color: transparent;
            background-clip: padding-box;
            border: 1px solid #28a745;
            border-radius: 0;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .input-group-text {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding: 0.375rem 0.75rem;
            margin-bottom: 0;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.6;
            color: #495057;
            text-align: center;
            white-space: nowrap;
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            border-radius: 0;
        }
        #dash {
            color: #FFD700;
        }
/*        .title_png{
              height: 200px;
              width: 50%;
          /*background-color: powderblue;*/
        }
*/



  </style>

<link href="css/4.1.1bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" type="text/css" href="css/main-loader.css">
<link rel="stylesheet" type="text/css" href="sass/style.css">
<script src="js/4.1.1bootstrap.min.js"></script>
<script src="js/3.2.1jquery.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>I.T HELPDESK</title>
</head>
<body>


<div class="container">
    <div class="card card-login mx-auto text-center bg-dark">
        <div class="card-header mx-auto bg-dark">
            <span> <img src="assets/helpdesk2.png" class="title_png w-75 h-25"  alt="Logo"> </span><br/>
                        <span class="logo_title mt-5" id="dash"> Login Dashboard </span>
<!--            <h1>--><?php //echo $message?><!--</h1>-->

  <?php if(!empty($message)): ?>
    <p id="err_mes" class="text-white"><?= $message ?></p>
  <?php endif; ?>

        </div>
        <div class="card-body">
  <form method="post" id="report_form" enctype="multipart/form-data">
                <div class="input-group form-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" name="email" class="form-control" placeholder="Username">
                </div>

                <div class="input-group form-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                    </div>
                    <input type="password" name="password" class="form-control" placeholder="Password">
                </div>

                <div class="form-group">
                    <input type="submit" name="btn" value="Login" class="btn btn-outline-primary float-right login_btn">
                </div>

            </form>
        </div>
    </div>
</div>


</body>
</html>


<script type="text/javascript">
  
$(document).ready(function() {
  
  setTimeout(function(){
    $('body').addClass('loaded');
    // $('h1').css('color','#222222');
  }, 1500);
  
});


setTimeout(function() {
  $("#err_mes").remove();
}, 5000);



</script>


