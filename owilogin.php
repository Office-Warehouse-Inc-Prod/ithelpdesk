<?php

include 'main_ses.php';
// 1. Get the system from the URL (default to 'helpdesk' if not set)
$system = isset($_GET['system']) ? $_GET['system'] : 'helpdesk';

// 2. Define names and colors based on the system
if ($system === 'dts') {
    $display_name = 'Data Tracking System';
    $accent = '#83cd6a'; // Sceptile Green
    $accent_rgb = '131, 205, 106';
    $accent_dark = '#0d1a0a';
} else {
    // Default or 'helpdesk'
    $display_name = 'OWI Helpdesk';
    $accent = '#8bacf6'; // Wartortle Blue
    $accent_rgb = '139, 172, 246';
    $accent_dark = '#0a0e1a';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | <?php echo $display_name; ?></title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<style>
    :root {
        --accent: <?php echo $accent; ?>;
        --accent-rgb: <?php echo $accent_rgb; ?>;
        --bg-dark: <?php echo $accent_dark; ?>;
        --glass: rgba(255, 255, 255, 0.03);
        --glass-border: rgba(255, 255, 255, 0.08);
        --ease: cubic-bezier(0.16, 1, 0.3, 1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    body {
        height: 100vh;
        background-color: var(--bg-dark);
        background-image: 
            radial-gradient(at 0% 0%, rgba(var(--accent-rgb), 0.15) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(var(--accent-rgb), 0.1) 0px, transparent 50%);
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        color: white;
    }

    /* Animated background elements */
    body::before {
        content: "";
        position: absolute;
        width: 150%;
        height: 150%;
        background: url('https://grainy-gradients.vercel.app/noise.svg');
        opacity: 0.15;
        pointer-events: none;
    }

    .login-container {
        display: flex;
        width: 1000px;
        min-height: 600px;
        background: var(--glass);
        backdrop-filter: blur(40px) saturate(150%);
        -webkit-backdrop-filter: blur(40px) saturate(150%);
        border: 1px solid var(--glass-border);
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 40px 100px rgba(0,0,0,0.8);
        z-index: 10;
        animation: slideUp 0.8s var(--ease);
    }

    /* Left Section: Branding */
    .login-left {
        flex: 1.2;
        padding: 80px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        border-right: 1px solid var(--glass-border);
        background: linear-gradient(135deg, rgba(var(--accent-rgb), 0.1) 0%, transparent 100%);
    }

    .brand-content h1 {
        font-size: 3.5rem;
        font-weight: 800;
        letter-spacing: -2px;
        line-height: 1;
        margin-bottom: 20px;
        color: #fff;
    }

    .icon-wrapper {
        width: 80px;
        height: 80px;
        background: var(--accent);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 40px rgba(var(--accent-rgb), 0.4);
        margin-bottom: 40px;
        animation: float 6s ease-in-out infinite;
    }

    .icon-wrapper svg {
        width: 40px;
        height: 40px;
        color: var(--bg-dark);
    }

    /* Right Section: Form */
    .login-right {
        flex: 1;
        padding: 80px 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(var(--accent-rgb), 0.1);
        border: 1px solid rgba(var(--accent-rgb), 0.3);
        color: var(--accent);
        padding: 6px 16px;
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 40px;
        width: fit-content;
    }

    .status-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        background: var(--accent);
        border-radius: 50%;
        box-shadow: 0 0 10px var(--accent);
    }

    .form-group {
        position: relative;
        margin-bottom: 30px;
    }

    .form-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: rgba(255,255,255,0.5);
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .form-group input {
        width: 100%;
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--glass-border);
        padding: 18px 24px;
        border-radius: 16px;
        color: white;
        font-size: 1rem;
        transition: all 0.3s var(--ease);
    }

    .form-group input:focus {
        outline: none;
        background: rgba(255,255,255,0.07);
        border-color: var(--accent);
        box-shadow: 0 0 20px rgba(var(--accent-rgb), 0.15);
    }

    .btn-login {
        width: 100%;
        padding: 20px;
        background: var(--accent);
        color: var(--bg-dark);
        border: none;
        border-radius: 16px;
        font-size: 1rem;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.4s var(--ease);
        margin-top: 10px;
    }

    .btn-login:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(var(--accent-rgb), 0.3);
        filter: brightness(1.1);
    }

    .back-link {
        margin-top: 30px;
        text-align: center;
        color: rgba(255,255,255,0.3);
        text-decoration: none;
        font-size: 0.85rem;
        transition: 0.3s;
    }

    .back-link:hover { color: var(--accent); }

    /* Animations */
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    @media (max-width: 950px) {
        .login-container { flex-direction: column; width: 100%; height: 100vh; border-radius: 0; }
        .login-left { padding: 40px; flex: 0; }
        .login-left h1 { font-size: 2rem; }
        .login-right { padding: 40px; }
        .icon-wrapper { display: none; }
    }


    .btn-login.loading {
    pointer-events: none;
    opacity: .9;
}

.btn-login.loading::after {
    content: "";
    width: 18px;
    height: 18px;
    border: 3px solid rgba(0,0,0,0.2);
    border-top: 3px solid #000;
    border-radius: 50%;
    display: inline-block;
    margin-left: 10px;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.login-success {
    animation: successUp 0.8s cubic-bezier(0.16,1,0.3,1) forwards;
}

@keyframes successUp {
    0% {
        opacity: 1;
        transform: translateY(0);
        filter: blur(0);
    }
    100% {
        opacity: 0;
        transform: translateY(-120px);
        filter: blur(10px);
    }
}


.btn-login.loading {
    pointer-events: none;
    opacity: 0.95;
}

.btn-login.loading::after {
    content: "";
    width: 18px;
    height: 18px;
    border: 3px solid rgba(0,0,0,0.2);
    border-top: 3px solid #000;
    border-radius: 50%;
    display: inline-block;
    margin-left: 10px;
    vertical-align: middle;
    animation: spin 0.7s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.login-container.submitting {
    animation: loginLift 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes loginLift {
    0% {
        opacity: 1;
        transform: translateY(0) scale(1);
        filter: blur(0);
    }
    100% {
        opacity: 0.15;
        transform: translateY(-40px) scale(0.98);
        filter: blur(6px);
    }
}

.login-alert {
    background: rgba(255, 80, 80, 0.08);
    border: 1px solid rgba(255, 80, 80, 0.35);
    color: #ffb3b3;
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 0.85rem;
    margin-bottom: 18px;
    animation: shakeAlert 0.35s ease;
}

@keyframes shakeAlert {
    0% { transform: translateX(0); }
    25% { transform: translateX(-4px); }
    50% { transform: translateX(4px); }
    75% { transform: translateX(-2px); }
    100% { transform: translateX(0); }
}
</style>
</head>
<body>

<div class="login-container">
    <div class="login-left">
        <div class="brand-content">
            <div class="icon-wrapper">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <?php if($system === 'dts'): ?>
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    <?php else: ?>
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    <?php endif; ?>
                </svg>
            </div>
            <h1><?php echo $display_name; ?></h1>
        </div>
       
    </div>

<div class="login-right">
    <div class="status-badge">LOGIN FORM</div>

    <form method="post" id="report_form">
        <input type="hidden" name="system" value="<?php echo $system; ?>">

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="email" class="form-control" placeholder="Username" required autofocus autocomplete="off">
        </div>

<div class="form-group">
    <label>Password</label>
    <input type="password" name="password" id="password" placeholder="••••••••" required>
</div>

<?php if(!empty($message)): ?>
<div class="login-alert">
    <?php echo $message; ?>
</div>
<?php endif; ?>

<button type="submit" class="btn btn-primary w-100 shadow btn-login">LOGIN</button>
    </form>
</div>
</div>
                    </form>
</body>
</html>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    let submitting = false;

    $("#report_form").on("submit", function (e) {
        if (submitting) return true;

        e.preventDefault();
        submitting = true;

        $(".btn-login").addClass("loading").text("Signing in");
        $(".login-container").addClass("submitting");

        setTimeout(() => {
            this.submit();
        }, 700);
    });
});
</script>