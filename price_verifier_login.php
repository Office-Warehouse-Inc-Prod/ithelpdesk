<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

require 'database.php';

if (!empty($_SESSION['price_verifier_user_id'])) {
    header('Location: users/price_verifier.php');
    exit();
}

$message = '';

if (!empty($_POST['username']) && !empty($_POST['password'])) {
    $records = $conn->prepare("SELECT
        users.id,
        users.fname,
        users.lstname,
        users.email,
        users.password,
        users.role,
        users.usr_stat,
        users.str_num,
        tbl_branch.SBS_NO,
        tbl_branch.PRICE_LVL
    FROM users
    LEFT JOIN tbl_branch ON users.str_num = tbl_branch.str_num
    WHERE users.usr_stat = 'A' AND users.email = :username");
    $records->bindParam(':username', $_POST['username']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    if (is_array($results) && count($results) > 0 && base64_encode($_POST['password']) == $results['password']) {
        $_SESSION['price_verifier_user_id'] = $results['id'];
        $_SESSION['price_verifier_email'] = $results['email'];
        $_SESSION['price_verifier_name'] = trim($results['fname'] . ' ' . $results['lstname']);
        $_SESSION['price_verifier_role'] = $results['role'];
        $_SESSION['SBS_NO'] = $results['SBS_NO'];
        $_SESSION['PRICE_LVL'] = $results['PRICE_LVL'];

        header('Location: users/price_verifier.php');
        exit();
    }

    $message = 'Invalid username or password.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Price Verifier | Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f172a;
            --panel: #111827;
            --panel-2: #1f2937;
            --accent: #ffd84d;
            --text: #f8fafc;
            --muted: #94a3b8;
            --border: rgba(255,255,255,0.12);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            width: 100%;
            max-width: 440px;
            background: rgba(17, 24, 39, 0.95);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
            padding: 32px;
            backdrop-filter: blur(12px);
        }

        .badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 216, 77, 0.15);
            color: var(--accent);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        h1 {
            margin: 0 0 10px;
            font-size: 1.8rem;
            font-weight: 800;
        }

        p {
            margin: 0 0 24px;
            color: var(--muted);
            line-height: 1.6;
        }

        .field {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.95rem;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 13px 14px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: var(--panel-2);
            color: var(--text);
            font-size: 1rem;
            outline: none;
        }

        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(255, 216, 77, 0.15);
        }

        button {
            width: 100%;
            padding: 13px 16px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), #f59e0b);
            color: #111827;
            font-weight: 800;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 8px;
        }

        .message {
            margin-top: 18px;
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 0.95rem;
            background: rgba(255, 216, 77, 0.12);
            color: #fde68a;
            border: 1px solid rgba(255, 216, 77, 0.18);
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.95rem;
        }

        .back-link:hover {
            color: var(--accent);
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="badge">Online Price Verifier</div>
        <h1>Sign in to continue</h1>
        <p>Use your assigned credentials to access the Online Price Verifier portal.</p>

        <form method="post" action="">
            <div class="field">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>

        <?php if ($message !== ''): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <a class="back-link" href="index.php">← Back to Home</a>
    </div>
</body>
</html>
