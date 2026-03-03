<?php include 'main_ses.php';
 date_default_timezone_set('Asia/Manila');
 ?>
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
      background: radial-gradient(circle at top left, #1e1e2f, #040414);
      font-family: 'Roboto', sans-serif;
      color: white;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .login-card {
      background: rgba(13, 13, 26, 0.85);
      backdrop-filter: blur(8px);
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 8px 25px rgba(255, 204, 12, 0.15);
      width: 100%;
      max-width: 900px;
      animation: slideUp 0.8s ease-out;
      transform: translateY(20px);
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .form-control {
      background: transparent;
      border: 1px solid #555;
      color: white;
      transition: box-shadow 0.3s, border-color 0.3s;
    }

    .form-control:focus {
      border-color: #ffcc0c;
      box-shadow: 0 0 10px rgba(255, 204, 12, 0.6);
    }

    .btn-login {
      background-color: #ffcc0c;
      color: #000;
      font-weight: 600;
      border-radius: 10px;
      transition: transform 0.3s, background-color 0.3s;
    }

    .btn-login:hover {
      background-color: #e6b800;
      transform: scale(1.05);
      box-shadow: 0 4px 15px rgba(255, 204, 12, 0.4);
    }

    .logo {
      width: 120px;
      margin-bottom: 1rem;
      animation: pop 0.8s ease;
    }

    @keyframes pop {
      0% { transform: scale(0.7); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }

    .subtitle {
      color: #b3b3b3;
      font-size: 0.95rem;
    }

    #err_mes {
      color: #ff5c5c;
      margin-top: 1rem;
      animation: shake 0.3s;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      50% { transform: translateX(5px); }
      75% { transform: translateX(-5px); }
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
        <h2 class="fw-bold">OWI HELPDESK Rev 3.0</h2>
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

  <script>
    setTimeout(() => {
      const err = document.getElementById("err_mes");
      if (err) err.remove();
    }, 5000);
  </script>
</body>
</html>
