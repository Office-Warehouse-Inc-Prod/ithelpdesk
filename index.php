<?php include 'main_ses.php'; ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Office Warehouse Inc.</title>
    <link rel="icon" type="image/x-icon" href="assets/images/owilogo.jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
      body {
        background-color: #040414;
        font-family: 'Roboto', sans-serif;
        color: white;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
      }
      .login-card {
        background: #0d0d1a;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 0 20px rgba(255, 204, 12, 0.1);
        width: 100%;
        max-width: 900px;
      }
      .form-floating label {
        color: #999;
      }
      .form-control {
        background: transparent;
        border: 1px solid #555;
        color: white;
      }
      .form-control:focus {
        border-color: #ffcc0c;
        box-shadow: none;
      }
      .form-floating > label::after {
        background-color: #0d0d1a !important;
      }
      .btn-login {
        background-color: #ffcc0c;
        color: #000;
        font-weight: 600;
        border-radius: 10px;
        transition: 0.3s;
      }
      .btn-login:hover {
        background-color: #e6b800;
      }
      .logo {
        width: 120px;
        margin-bottom: 1rem;
      }
      .subtitle {
        color: #b3b3b3;
        font-size: 0.95rem;
      }
      #err_mes {
        color: #ff5c5c;
        margin-top: 1rem;
      }
    </style>
  </head>
  <body>
    <div class="login-card row g-0 shadow-lg">
      <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center">
        <img src="assets/helpdesk.png" alt="Helpdesk Image" class="img-fluid p-3">
      </div>
      <div class="col-md-6 p-4">
        <div class="text-center">
          <img src="assets/owilogo.jpeg" class="logo" alt="Logo">
          <h2 class="fw-bold">OWI HELPDESK</h2>
          <p class="subtitle">Login to continue</p>
        </div>

        <?php if (!empty($message)): ?>
          <p id="err_mes"><?= $message ?></p>
        <?php endif; ?>

        <form method="post" id="report_form" enctype="multipart/form-data" class="mt-4">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingUsername" name="email" placeholder="Username" required>
            <label for="floatingUsername">Username</label>
          </div>
          <div class="form-floating mb-4">
            <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>
            <label for="floatingPassword">Password</label>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-login">Login</button>
          </div>
        </form>
      </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>
      setTimeout(() => {
        const err = document.getElementById("err_mes");
        if (err) err.remove();
      }, 5000);
    </script>
  </body>
</html>
