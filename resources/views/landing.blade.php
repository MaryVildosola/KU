<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KU — 空 | Synchronized Institutional Disconnection Platform</title>
    <meta name="description" content="A Collective AI Platform for Synchronized Institutional Disconnection.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&family=Noto+Serif+JP:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0a0e17;
            --bg-secondary: #0f172a;
            --glass-bg: rgba(15,23,42,0.7);
            --glass-border: rgba(255,255,255,0.07);
            --color-primary: #6366f1;
            --color-secondary: #0ea5e9;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --font-sans: 'Inter', sans-serif;
            --font-display: 'Outfit', sans-serif;
            --font-serif: 'Noto Serif JP', serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: var(--font-sans);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        /* Nav */
        nav {
            position: fixed;
            top: 0; width: 100%;
            background: rgba(10,14,23,0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glass-border);
            z-index: 1000;
            padding: 1.25rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
        }
        .logo-kanji {
            font-family: var(--font-serif);
            font-size: 2rem;
            background: linear-gradient(135deg, #6366f1, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .logo-text h1 { font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; color: var(--text-primary); letter-spacing: 0.1em; }
        .logo-text p { font-size: 0.75rem; color: var(--text-secondary); letter-spacing: 0.2em; text-transform: uppercase; margin-top: -2px; }
        .nav-links { display: flex; gap: 2rem; align-items: center; }
        .nav-links a { color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.2s; }
        .nav-links a:hover { color: var(--text-primary); }
        .btn-login {
            background: linear-gradient(135deg, #6366f1, #0ea5e9);
            color: white !important;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(99,102,241,0.4); }

        /* Hero */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8rem 5% 5rem;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            width: 800px; height: 800px;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, rgba(10,14,23,0) 70%);
            top: -200px; left: -200px;
            z-index: -1;
        }
        .hero::after {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(14,165,233,0.1) 0%, rgba(10,14,23,0) 70%);
            bottom: -100px; right: -100px;
            z-index: -1;
        }
        .hero-content {
            max-width: 900px;
            text-align: center;
        }
        .hero h2 {
            font-family: var(--font-display);
            font-size: 4.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
        }
        .hero h2 span {
            background: linear-gradient(135deg, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero p {
            font-size: 1.25rem;
            color: var(--text-secondary);
            max-width: 700px;
            margin: 0 auto 3rem;
            line-height: 1.7;
        }

        /* Active Window Banner */
        .status-banner {
            max-width: 600px;
            margin: 0 auto;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .status-banner.active {
            background: linear-gradient(135deg, rgba(14,165,233,0.15), rgba(99,102,241,0.1));
            border-color: rgba(14,165,233,0.3);
        }
        .status-left { display: flex; align-items: center; gap: 1rem; }
        .status-dot {
            width: 12px; height: 12px;
            border-radius: 50%;
            background: var(--text-muted);
        }
        .status-banner.active .status-dot {
            background: #0ea5e9;
            box-shadow: 0 0 10px #0ea5e9;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse { 0%,100%{transform:scale(1);opacity:0.8;} 50%{transform:scale(1.3);opacity:1;} }
        .status-text h3 { font-family: var(--font-display); font-size: 1.1rem; }
        .status-text p { font-size: 0.85rem; color: var(--text-secondary); margin: 0; }
        .status-time { font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; color: var(--text-primary); }

        /* Features */
        .features {
            padding: 5rem 5%;
            background: var(--bg-secondary);
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .feature-card {
            background: rgba(10,14,23,0.5);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 2.5rem;
            transition: transform 0.3s;
        }
        .feature-card:hover { transform: translateY(-5px); border-color: rgba(99,102,241,0.3); }
        .feature-icon { font-size: 2.5rem; margin-bottom: 1.5rem; }
        .feature-card h3 { font-family: var(--font-display); font-size: 1.3rem; margin-bottom: 1rem; }
        .feature-card p { color: var(--text-secondary); font-size: 0.95rem; }

        footer {
            text-align: center;
            padding: 3rem 5%;
            border-top: 1px solid var(--glass-border);
            color: var(--text-muted);
            font-size: 0.85rem;
        }
        /* Gateway Screen Overlay */
        .gateway-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: #060913;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .gateway-overlay.hidden {
            opacity: 0;
            visibility: hidden;
        }
        .enso-container {
            position: relative;
            width: 260px; height: 260px;
            margin-bottom: 2rem;
            display: flex; align-items: center; justify-content: center;
        }
        .enso-svg {
            width: 100%; height: 100%;
            transform: rotate(-90deg);
        }
        .enso-circle {
            fill: none;
            stroke: url(#ensoGradient);
            stroke-width: 8;
            stroke-linecap: round;
            stroke-dasharray: 800;
            stroke-dashoffset: 800;
            animation: drawEnso 4.5s cubic-bezier(0.25, 1, 0.5, 1) forwards;
        }
        .enso-text {
            position: absolute;
            font-family: var(--font-serif);
            font-size: 5rem;
            color: var(--text-primary);
            opacity: 0;
            animation: fadeIn 3s 1.5s ease forwards;
            letter-spacing: 2px;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.15);
        }
        .gateway-title {
            font-family: var(--font-display);
            font-size: 2.2rem;
            font-weight: 400;
            letter-spacing: 0.15em;
            margin-bottom: 0.5rem;
            text-align: center;
            opacity: 0;
            animation: fadeIn 2s 2s ease forwards;
        }
        .gateway-subtitle {
            font-family: var(--font-sans);
            color: var(--text-secondary);
            font-size: 1rem;
            letter-spacing: 0.05em;
            margin-bottom: 3rem;
            text-align: center;
            opacity: 0;
            animation: fadeIn 2s 2.5s ease forwards;
        }
        .gateway-btn {
            background: transparent;
            border: 1px solid var(--text-muted);
            color: var(--text-primary);
            padding: 0.8rem 2.5rem;
            font-family: var(--font-sans);
            font-size: 0.95rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            animation: fadeIn 2s 3s ease forwards;
        }
        .gateway-btn:hover {
            background: var(--text-primary);
            color: var(--bg-primary);
            border-color: var(--text-primary);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        @keyframes drawEnso {
            to { stroke-dashoffset: 50; }
        }
        @keyframes fadeIn {
            to { opacity: 1; }
        }
        body.locked {
            overflow: hidden;
            height: 100vh;
        }
    </style>
</head>
<body class="locked">

    <!-- Gateway Screen (Zen Gateway) -->
    <div id="gatewayScreen" class="gateway-overlay">
        <div class="enso-container">
            <svg class="enso-svg" viewBox="0 0 160 160">
                <defs>
                    <linearGradient id="ensoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#6366f1" stop-opacity="0.8" />
                        <stop offset="50%" stop-color="#0ea5e9" stop-opacity="0.9" />
                        <stop offset="100%" stop-color="#10b981" stop-opacity="0.5" />
                    </linearGradient>
                </defs>
                <circle class="enso-circle" cx="80" cy="80" r="70" />
            </svg>
            <div class="enso-text">空</div>
        </div>
        <h2 class="gateway-title">KU — 空</h2>
        <p class="gateway-subtitle">A Collective AI Platform for Synchronized Institutional Disconnection</p>
        <button class="gateway-btn" onclick="enterSilence()">ENTER THE SILENCE</button>
    </div>    <nav>
        <a href="/" class="logo">
            <div class="logo-kanji">空</div>
            <div class="logo-text">
                <h1>KU</h1>
                <p>Platform</p>
            </div>
        </a>
        <div class="nav-links">
            <a href="#about">About</a>
            <a href="#features">Features</a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-login">Go to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-login">Institutional Login</a>
            @endauth
        </div>
    </nav>

    <header class="hero">
        <div class="hero-content">
            <h2>The Structural Impossible<br><span>Now Made Possible</span></h2>
            <p>KU (空) dissolves the social coordination barrier known as FOMO by synchronizing institutional disconnection. When the university pauses, the mind rests.</p>

            <div class="status-banner {{ $activeWindow ? 'active' : '' }}">
                <div class="status-left">
                    <div class="status-dot"></div>
                    <div class="status-text">
                        <h3>{{ $activeWindow ? 'Institutional Silence is Active' : 'System Standby' }}</h3>
                        <p>{{ $activeWindow ? 'Notifications are currently queued.' : 'Next window: ' . ($nextWindow ? $nextWindow['window']->label : 'Unscheduled') }}</p>
                    </div>
                </div>
                <div class="status-time">
                    @if($activeWindow)
                        LIVE
                    @elseif($nextWindow)
                        <span id="hero-countdown" data-seconds="{{ $nextWindow['seconds_until'] }}">--:--</span>
                    @else
                        --:--
                    @endif
                </div>
            </div>
        </div>
    </header>

    <section id="features" class="features">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🧠</div>
                <h3>FOMO Risk Diagnostic</h3>
                <p>Analyzes institutional notification pressure, deadline clustering, and late-night LMS activity to identify peak structural anxiety windows.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌿</div>
                <h3>Synchronized Windows</h3>
                <p>When an AI-optimized window triggers, non-urgent communications are held, deadlines are suspended, and the entire institution goes offline simultaneously.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🛡️</div>
                <h3>Faculty Shield</h3>
                <p>Protects faculty from the expectation of 24/7 availability while assuring students that their messages have been safely queued.</p>
            </div>
        </div>
    </section>

    <footer>
        <p>KU — 空 | KU — 空界 &copy; {{ date('Y') }} Tsunagaranai Kachi. OGIS Ignite PH 2026.</p>
    </footer>

    <script>
        function enterSilence() {
            const gatewayScreen = document.getElementById('gatewayScreen');
            
            // Fade out the entire splash screen to reveal the landing page
            gatewayScreen.classList.add('hidden');
            document.body.classList.remove('locked');

            // Remove splash screen from DOM flow
            setTimeout(() => {
                gatewayScreen.style.display = 'none';
            }, 600); // 0.6s is the transition time
        }

        const el = document.getElementById('hero-countdown');
        if (el) {
            let s = parseInt(el.dataset.seconds);
            const tick = () => {
                const h = Math.floor(s/3600);
                const m = Math.floor((s%3600)/60);
                el.textContent = h > 0 ? `${h}h ${m}m` : `${m}m ${s%60}s`;
                if(s>0){s--;setTimeout(tick,1000);}
            };
            tick();
        }
    </script>
</body>
</html>
