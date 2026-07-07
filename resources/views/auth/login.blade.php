<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Institutional Login | KU — 空</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&family=Noto+Serif+JP:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0a0e17;
            --bg-secondary: #0f172a;
            --glass-bg: rgba(15,23,42,0.6);
            --glass-border: rgba(255,255,255,0.08);
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        
        /* Background Effects */
        body::before {
            content: '';
            position: absolute;
            width: 800px; height: 800px;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, rgba(10,14,23,0) 70%);
            top: -200px; left: -200px;
            z-index: -1;
            animation: float 20s ease-in-out infinite alternate;
        }
        body::after {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(14,165,233,0.1) 0%, rgba(10,14,23,0) 70%);
            bottom: -100px; right: -100px;
            z-index: -1;
            animation: float 15s ease-in-out infinite alternate-reverse;
        }
        
        @keyframes float {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            padding: 2rem;
            z-index: 10;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            position: relative;
            overflow: hidden;
        }

        /* Subtle glowing edge */
        .glass-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(99,102,241,0.5), transparent);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2.5rem;
            text-decoration: none;
            display: block;
            transition: transform 0.3s;
        }
        .logo-section:hover {
            transform: scale(1.02);
        }
        .logo-kanji {
            font-family: var(--font-serif);
            font-size: 3.5rem;
            background: linear-gradient(135deg, #6366f1, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.2rem;
            line-height: 1;
        }
        .logo-text h1 {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: 0.1em;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            transition: color 0.3s;
        }

        .form-input {
            width: 100%;
            background: rgba(10,14,23,0.5);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--text-primary);
            padding: 0.875rem 1rem;
            border-radius: 12px;
            font-size: 1rem;
            font-family: var(--font-sans);
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: var(--color-primary);
            background: rgba(15,23,42,0.8);
            box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
        }
        .form-input:focus + .form-label,
        .form-input:not(:placeholder-shown) + .form-label {
            color: var(--color-primary);
        }

        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            font-size: 0.85rem;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            color: var(--text-secondary);
            transition: color 0.2s;
        }
        .checkbox-container:hover {
            color: var(--text-primary);
        }

        .checkbox-container input {
            accent-color: var(--color-primary);
            width: 16px; height: 16px;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s, text-shadow 0.2s;
        }
        .forgot-link:hover {
            color: #818cf8;
            text-shadow: 0 0 8px rgba(129,140,248,0.4);
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #6366f1, #0ea5e9);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            font-family: var(--font-display);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(99,102,241,0.5);
        }
        .btn-submit:active {
            transform: translateY(0);
        }

        .error-message {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.5rem;
            display: block;
        }
        
        .status-message {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #10b981;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <a href="/" class="logo-section">
            <div class="logo-kanji">空</div>
            <div class="logo-text">
                <h1>KU</h1>
            </div>
        </a>

        <div class="glass-card">
            <!-- Session Status -->
            @if (session('status'))
                <div class="status-message">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Institutional Email</label>
                    <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@institution.edu">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" class="form-input" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="options-row">
                    <label for="remember_me" class="checkbox-container">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-submit">
                    Authenticate
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="m12 5 7 7-7 7"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</body>
</html>
