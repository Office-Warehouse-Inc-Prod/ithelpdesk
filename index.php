<?php include 'main_ses.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Office Warehouse Inc.</title>
    <link rel="icon" type="image/x-icon" href="assets/images/owilogo.jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(34, 56, 167, 0.3), rgba(214, 216, 184, 0.27)), 
                        url('images/bg_login.png'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        .main-wrapper {
            flex: 1;
            display: flex;
            align-items: center; /* Vertical Center */
            justify-content: center; /* Horizontal Center */
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 420px; /* Fixed maximum width for the card */
            z-index: 2;
        }

        /* Responsive behavior for the branding image */
        .side-left-img {
            max-height: 600px;
            width: 100%;
            object-fit: contain;
            border-radius: 15px;
        }

        .divider-text h2 {
            color: #213456;
            font-weight: 800;
            font-size: 2.2rem;
            margin: 0;
        }

        .login-title {
            font-size: 13px;
            letter-spacing: 1px;
            font-weight: 600;
            color: #666;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-left: none;
        }

        .input-group-text {
            background-color: #fff;
            color: #213456;
        }

        .btn-primary {
            background: #213456;
            border: none;
            padding: 0.8rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #150556;
            transform: translateY(-1px);
            border-color: #E5BA41;
        }

        /* Centering Logic for Mobile/Desktop */
        @media (max-width: 991px) {
            .side-left-container {
                display: none !important; /* Hide image on smaller screens */
            }
        }
        
        footer {
            background: #888787;
            color: #213456 ;
            padding: 10px 0;
            text-align: center;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            
            <div class="col-lg-6 d-none d-lg-block side-left-container text-end pe-5">
                <img src="images/bg_login blue.png" class="side-left-img" alt="Branding">
            </div>

            <div class="col-12 col-md-8 col-lg-5 d-flex justify-content-center">
                <div class="login-card">
                    <div class="text-center mb-4">
                        <div class="divider-text">
                            <h2>WELCOME H2</h2>
                        </div>
                        <div class="login-title text-uppercase mt-1">Log in your details</div>
                    </div>

                    <form method="post" id="report_form">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="email" class="form-control" placeholder="Username" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                                <span class="input-group-text toggle-password" style="cursor:pointer;">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 shadow">Login</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function () {
        $('.toggle-password').on('click', function () {
            const passwordInput = $('#password');
            const icon = $(this).find('i');
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
</script>
</body>
</html>