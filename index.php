<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Warehouse | Triplex Portals</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sceptile: #83cd6a;
            --sceptile-dark: #1b2e16;
            --wartortle: #8bacf6;
            --wartortle-dark: #161c2e;
            --pikachu: #ffd84d; 
            --charmander: #de5239;
            --white: #ffffff;
            --ease: cubic-bezier(0.85, 0, 0.15, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body, html {
            height: 100%;
            overflow: hidden;
            background: #d8d2d2;
        }

        .split-wrapper {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        .sector {
            position: relative;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: flex var(--ease) 0.7s, filter 0.5s ease;
            overflow: hidden;
            cursor: pointer;
            text-decoration: none;
        }

        .sceptile-sector {
            background: linear-gradient(135deg, #476d39 0%, var(--sceptile-dark) 100%);
            border-right: 1px solid rgba(131, 205, 106, 0.2);
        }

        .wartortle-sector {
            background: linear-gradient(135deg, #1d263b 0%, var(--wartortle-dark) 100%);
        }

        .pikachu-sector {
        background: linear-gradient(135deg, #d3aa18, #3a2f00);
        --accent: var(--pikachu);
        }

        .sector:hover {
            flex: 1.8;
        }

        .split-wrapper:has(.sector:hover) .sector:not(:hover) {
            flex: 0.6;
            filter: grayscale(0.8) brightness(0.4);
        }

        .bg-text {
            position: absolute;
            font-size: 15vw;
            font-weight: 800;
            opacity: 0.03;
            pointer-events: none;
            white-space: nowrap;
            z-index: 0;
        }

        .content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 0 10%;
            max-width: 600px;
            transition: transform 0.5s var(--ease);
        }

        .sector:hover .content {
            transform: scale(1.05);
        }

        .icon-hex {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--accent);
            transform: rotate(45deg);
            transition: all 0.5s var(--ease);
        }

        .icon-hex svg {
            transform: rotate(-45deg);
            color: var(--accent);
            width: 40px;
            height: 40px;
        }

        .sector:hover .icon-hex {
            background: var(--accent);
            transform: rotate(135deg) scale(1.1);
            box-shadow: 0 0 30px var(--accent);
        }

        .sector:hover .icon-hex svg {
            color: #000;
            transform: rotate(-135deg);
        }

        h2 {
            font-size: clamp(2rem, 4vw, 4rem);
            font-weight: 800;
            color: var(--white);
            letter-spacing: -2px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        p {
            color: rgba(255,255,255,0.6);
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 40px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s var(--ease) 0.2s;
        }

        .sector:hover p {
            opacity: 1;
            transform: translateY(0);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 2px;
            border: 1px solid var(--accent);
            color: var(--accent);
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .sceptile-sector { --accent: var(--sceptile); }
        .wartortle-sector { --accent: var(--wartortle); }

        /* --- RESPONSIVE  --- */

        /* Tablet (iPad & Small Laptops) */
        @media (max-width: 1024px) {
            .content {
                padding: 0 5%;
            }
            
            h2 {
                font-size: clamp(1.5rem, 3vw, 2.5rem);
                letter-spacing: -1px;
            }

            .icon-hex {
                width: 80px;
                height: 80px;
                margin-bottom: 20px;
            }
        }

        /* Mobile (Phones) */
        @media (max-width: 768px) {
            .split-wrapper {
                flex-direction: column; 
            }

            .sector {
                flex: 1;
                width: 100%;
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .sector:hover {
                flex: 2.5; 
            }

            .split-wrapper:has(.sector:hover) .sector:not(:hover) {
                flex: 0.5;
            }

            .content {
                transform: scale(0.9); 
            }

            
            p {
                opacity: 0.7; 
                font-size: 0.95rem;
                transform: translateY(0);
                margin-bottom: 20px;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            h2 {
                font-size: 1.8rem;
                margin-bottom: 10px;
            }

            .icon-hex {
                width: 60px;
                height: 60px;
                margin-bottom: 15px;
            }

            .icon-hex svg {
                width: 25px;
                height: 25px;
            }

            .status-badge {
                font-size: 0.6rem;
                padding: 4px 10px;
                margin-bottom: 15px;
            }

            .bg-text {
                display: none;
            }
        }

        @media (max-height: 500px) and (orientation: landscape) {
            .split-wrapper {
                flex-direction: row; 
            }
            .icon-hex {
                display: none; 
            }
            
        }
    </style>
</head>
<body>

<div class="split-wrapper">
    <a href="#" class="sector sceptile-sector">
        <div class="content">
            <div class="status-badge">Digital Documents</div>
            <div class="icon-hex">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </div>
            <h2>DATA TRACKING SYSTEM</h2>
            <p>Track and manage data in real time with an organized platform.</p>
        </div>
    </a>

    <a href="owilogin.php" class="sector wartortle-sector">
        <div class="content">
            <div class="status-badge">Non-Inventorial Services</div>
            <div class="icon-hex">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </div>
            <h2>OWI HELPDESK</h2>
            <p>Manage non-inventorial service requests and track reported issues..</p>
        </div>
    </a>

    <a href="#" class="sector pikachu-sector">
        <div class="content">
            <div class="status-badge">Return</div>

            <div class="icon-hex">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M13 2L3 14h7l-1 8 10-12h-7l1-8z"></path>
                </svg>
            </div>
            <h2>RETURN TO SUPPLIERS</h2>
            <p>Handle return-to-suplier requests and their status updates.</p>
        </div>
    </a>
</div>

</body>
</html>
