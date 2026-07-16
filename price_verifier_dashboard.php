<?php
session_start();

if (empty($_SESSION['price_verifier_user_id'])) {
    header('Location: price_verifier_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Price Verifier | Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            color: #0f172a;
        }
        .wrap {
            max-width: 900px;
            margin: 60px auto;
            padding: 24px;
        }
        .card {
            background: white;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }
        h1 { margin-top: 0; }
        .muted { color: #64748b; }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 16px;
            background: #ffd84d;
            color: #111827;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>Online Price Verifier</h1>
            <p class="muted">Welcome, <?php echo htmlspecialchars($_SESSION['price_verifier_name'] ?? 'User'); ?>.</p>
            <p>This is the landing page for the Online Price Verifier module.</p>
            <a class="btn" href="price_verifier_login.php">Logout</a>
        </div>
    </div>
</body>
</html>
