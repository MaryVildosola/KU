<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'KU — 空界' }} | KU Platform</title>
    <meta name="description" content="KU — 空界: The digital interface for KU synchronized institutional disconnection.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&family=Noto+Serif+JP:wght@300;400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg-primary: #0a0e17;
            --bg-secondary: #0f172a;
            --bg-tertiary: #1e293b;
            --glass-bg: rgba(15,23,42,0.7);
            --glass-border: rgba(255,255,255,0.07);
            --color-primary: #6366f1;
            --color-secondary: #0ea5e9;
            --color-accent: #10b981;
            --color-warning: #f59e0b;
            --color-danger: #ef4444;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
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
        }
        /* Sidebar */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--bg-secondary);
            border-right: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            padding: 1.5rem 1rem;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
        }
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--glass-border);
            margin-bottom: 1.5rem;
            text-decoration: none;
        }
        .sidebar-logo-kanji {
            font-family: var(--font-serif);
            font-size: 1.8rem;
            color: var(--text-primary);
            background: linear-gradient(135deg, #6366f1, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .sidebar-logo-text h1 {
            font-family: var(--font-display);
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.05em;
        }
        .sidebar-logo-text p {
            font-size: 0.65rem;
            color: var(--text-muted);
            letter-spacing: 0.08em;
        }
        .sidebar-section-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            font-weight: 600;
            padding: 0 0.5rem;
            margin: 1rem 0 0.4rem;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.6rem 0.75rem;
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.15s ease;
            margin-bottom: 2px;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: rgba(99,102,241,0.12);
            color: var(--text-primary);
        }
        .sidebar-nav a.active {
            border-left: 3px solid var(--color-primary);
        }
        .sidebar-nav a .nav-icon { font-size: 1rem; width: 20px; text-align: center; }
        .sidebar-bottom {
            margin-top: auto;
            border-top: 1px solid var(--glass-border);
            padding-top: 1rem;
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.5rem;
        }
        .sidebar-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #0ea5e9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .sidebar-user-info { flex: 1; min-width: 0; }
        .sidebar-user-name { font-size: 0.8rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-user-role { font-size: 0.65rem; color: var(--text-muted); }
        /* Main content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--glass-border);
            padding: 0.9rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 90;
        }
        .topbar-title {
            font-family: var(--font-display);
            font-size: 1.1rem;
            font-weight: 600;
        }
        .topbar-right { display: flex; align-items: center; gap: 1rem; }
        .window-badge {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 600;
            border: 1px solid;
        }
        .window-badge.active {
            background: rgba(14,165,233,0.1);
            border-color: rgba(14,165,233,0.3);
            color: #0ea5e9;
        }
        .window-badge.standby {
            background: rgba(100,116,139,0.1);
            border-color: rgba(100,116,139,0.2);
            color: var(--text-muted);
        }
        .window-badge .dot {
            width: 7px; height: 7px;
            border-radius: 50%;
        }
        .window-badge.active .dot {
            background: #0ea5e9;
            box-shadow: 0 0 6px #0ea5e9;
            animation: pulse 1.5s infinite;
        }
        .window-badge.standby .dot { background: var(--text-muted); }
        @keyframes pulse { 0%,100%{opacity:0.7;transform:scale(1);} 50%{opacity:1;transform:scale(1.15);} }
        /* Page content */
        .page-content { padding: 2rem; flex: 1; }
        /* Cards */
        .card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 14px;
            backdrop-filter: blur(8px);
        }
        .card-body { padding: 1.5rem; }
        .card-header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }
        .card-title {
            font-family: var(--font-display);
            font-size: 1rem;
            font-weight: 600;
        }
        /* KPI Cards */
        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap: 1.25rem; margin-bottom: 2rem; }
        .kpi-card {
            padding: 1.25rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        .kpi-label { font-size: 0.78rem; color: var(--text-secondary); font-weight: 500; }
        .kpi-value { font-family: var(--font-display); font-size: 2rem; font-weight: 800; }
        .kpi-trend { font-size: 0.72rem; font-weight: 600; }
        .kpi-trend.good { color: var(--color-accent); }
        .kpi-trend.warn { color: var(--color-warning); }
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-primary { background: var(--color-primary); color: white; }
        .btn-primary:hover { background: #4f46e5; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,0.3); }
        .btn-secondary { background: rgba(255,255,255,0.05); color: var(--text-secondary); border: 1px solid var(--glass-border); }
        .btn-secondary:hover { background: rgba(255,255,255,0.1); color: var(--text-primary); }
        .btn-danger { background: rgba(239,68,68,0.15); color: var(--color-danger); border: 1px solid rgba(239,68,68,0.2); }
        .btn-danger:hover { background: rgba(239,68,68,0.25); }
        .btn-success { background: rgba(16,185,129,0.15); color: var(--color-accent); border: 1px solid rgba(16,185,129,0.2); }
        .btn-success:hover { background: rgba(16,185,129,0.25); }
        .btn-sm { padding: 0.35rem 0.85rem; font-size: 0.78rem; }
        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 50px;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        /* Tables */
        .ku-table { width: 100%; border-collapse: collapse; }
        .ku-table th {
            text-align: left;
            padding: 0.75rem 1rem;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--glass-border);
        }
        .ku-table td {
            padding: 0.85rem 1rem;
            font-size: 0.85rem;
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }
        .ku-table tr:last-child td { border-bottom: none; }
        .ku-table tr:hover td { background: rgba(255,255,255,0.015); }
        /* Form Elements */
        .form-group { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1.25rem; }
        .form-label { font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); }
        .form-control {
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            padding: 0.65rem 0.9rem;
            color: var(--text-primary);
            font-family: var(--font-sans);
            font-size: 0.88rem;
            transition: border-color 0.15s;
        }
        .form-control:focus { outline: none; border-color: var(--color-primary); background: rgba(99,102,241,0.05); }
        select.form-control option { background: #1e293b; color: white; }
        /* Alert flash */
        .alert {
            padding: 0.85rem 1.25rem;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .alert-success { background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.25); color: var(--color-accent); }
        .alert-error { background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25); color: var(--color-danger); }
        /* Section headers */
        .section-header { margin-bottom: 1.5rem; }
        .section-header h2 { font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; }
        .section-header p { color: var(--text-secondary); font-size: 0.88rem; margin-top: 0.25rem; }
        /* Divider */
        .divider { height: 1px; background: var(--glass-border); margin: 1.5rem 0; }
        /* Pill tag */
        .pill { display: inline-flex; padding: 2px 8px; border-radius: 50px; font-size: 0.72rem; font-weight: 600; }
        .pill-indigo { background: rgba(99,102,241,0.15); color: #818cf8; }
        .pill-sky { background: rgba(14,165,233,0.15); color: #38bdf8; }
        .pill-emerald { background: rgba(16,185,129,0.15); color: #34d399; }
        .pill-amber { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .pill-red { background: rgba(239,68,68,0.15); color: #f87171; }
        .pill-slate { background: rgba(100,116,139,0.15); color: #94a3b8; }
        /* Toggle Switch */
        .toggle-wrap { display:flex; align-items:center; gap:0.5rem; }
        .toggle { position:relative; width:42px; height:24px; }
        .toggle input { opacity:0; width:0; height:0; }
        .toggle-slider {
            position:absolute; cursor:pointer;
            top:0; left:0; right:0; bottom:0;
            background: var(--bg-tertiary);
            border:1px solid rgba(255,255,255,0.1);
            border-radius:34px;
            transition:.3s;
        }
        .toggle-slider:before {
            position:absolute; content:"";
            height:16px; width:16px; left:3px; bottom:3px;
            background:white; border-radius:50%; transition:.3s;
        }
        .toggle input:checked + .toggle-slider { background:var(--color-primary); }
        .toggle input:checked + .toggle-slider:before { transform:translateX(18px); }
        /* Scrollbar */
        ::-webkit-scrollbar { width:6px; }
        ::-webkit-scrollbar-track { background:transparent; }
        ::-webkit-scrollbar-thumb { background:var(--bg-tertiary); border-radius:4px; }
    </style>
</head>
<body>
    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <div class="sidebar-logo-kanji">空</div>
            <div class="sidebar-logo-text">
                <h1>KU</h1>
                <p>空界 PLATFORM</p>
            </div>
        </a>

        {{-- Admin Nav --}}
        @if(auth()->user()->isAdmin())
        <span class="sidebar-section-label">Administration</span>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Dashboard
            </a>
            <a href="{{ route('admin.windows') }}" class="{{ request()->routeIs('admin.windows*') ? 'active' : '' }}">
                <span class="nav-icon">🌿</span> Synchronized Windows
            </a>
            <a href="{{ route('admin.simulator') }}" class="{{ request()->routeIs('admin.simulator*') ? 'active' : '' }}">
                <span class="nav-icon">🧪</span> AI Window Simulator
            </a>
            <a href="{{ route('admin.analytics') }}" class="{{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <span class="nav-icon">📈</span> Analytics
            </a>
            <a href="{{ route('admin.fomo') }}" class="{{ request()->routeIs('admin.fomo*') ? 'active' : '' }}">
                <span class="nav-icon">🔬</span> FOMO Risk Index
            </a>
            <a href="{{ route('admin.policies') }}" class="{{ request()->routeIs('admin.policies') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span> Policies
            </a>
            <a href="{{ route('admin.accommodations') }}" class="{{ request()->routeIs('admin.accommodations*') ? 'active' : '' }}">
                <span class="nav-icon">📝</span> Accommodations
            </a>
            <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <span class="nav-icon">👥</span> Users
            </a>
        </nav>
        @endif

        {{-- Faculty Nav --}}
        @if(auth()->user()->isFaculty())
        <span class="sidebar-section-label">Faculty Portal</span>
        <nav class="sidebar-nav">
            <a href="{{ route('faculty.dashboard') }}" class="{{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span> Dashboard
            </a>
            <a href="{{ route('faculty.shield') }}" class="{{ request()->routeIs('faculty.shield') ? 'active' : '' }}">
                <span class="nav-icon">🛡️</span> Faculty Shield
            </a>
            <a href="{{ route('faculty.queue') }}" class="{{ request()->routeIs('faculty.queue') ? 'active' : '' }}">
                <span class="nav-icon">📨</span> Message Queue
            </a>
            <a href="{{ route('faculty.analytics') }}" class="{{ request()->routeIs('faculty.analytics') ? 'active' : '' }}">
                <span class="nav-icon">📉</span> My Analytics
            </a>
            <a href="{{ route('faculty.schedule') }}" class="{{ request()->routeIs('faculty.schedule') ? 'active' : '' }}">
                <span class="nav-icon">📅</span> Window Schedule
            </a>
        </nav>
        @endif

        {{-- Research Nav --}}
        @if(auth()->user()->isResearcher())
        <span class="sidebar-section-label">Research Portal</span>
        <nav class="sidebar-nav">
            <a href="{{ route('research.dashboard') }}" class="{{ request()->routeIs('research.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🔭</span> Datasets
            </a>
            <a href="{{ route('research.apply') }}" class="{{ request()->routeIs('research.apply') ? 'active' : '' }}">
                <span class="nav-icon">📋</span> Apply for Access
            </a>
        </nav>
        @endif

        <div class="sidebar-bottom">
            <div class="sidebar-user">
                <div class="sidebar-avatar">{{ auth()->user()->avatar_initials ?? substr(auth()->user()->name,0,2) }}</div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    <div class="sidebar-user-role">{{ auth()->user()->role_label }}</div>
                </div>
            </div>
            <nav class="sidebar-nav" style="margin-top:0.5rem;">
                <a href="{{ route('profile.edit') }}"><span class="nav-icon">👤</span> Profile</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="nav-icon">🚪</span> Sign Out
                </a>
            </nav>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="main-content">
        <div class="topbar">
            <span class="topbar-title">{{ $title ?? 'KU — 空界' }}</span>
            <div class="topbar-right">
                @php $activeWin = app(App\Services\WindowService::class)->getActiveWindow(); @endphp
                @if($activeWin)
                    <div class="window-badge active">
                        <div class="dot"></div>
                        <span>{{ $activeWin->label }} — Active</span>
                    </div>
                @else
                    <div class="window-badge standby">
                        <div class="dot"></div>
                        <span>No Active Window</span>
                    </div>
                @endif
                <span style="font-size:0.75rem;color:var(--text-muted);">{{ now()->format('D, M j · g:i A') }}</span>
            </div>
        </div>

        <main class="page-content">
            @if(session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">⚠️ {{ session('error') }}</div>
            @endif

            {{ $slot }}
        </main>
    </div>
</body>
</html>
