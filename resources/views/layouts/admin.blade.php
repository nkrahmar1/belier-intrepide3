<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1e293b">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Belier Admin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
    @php
        $adminName = Auth::check() ? Auth::user()->name : 'Administrateur';
        $notificationCount = 0;
        if (Schema::hasTable('messages')) {
            $notificationCount = \App\Models\Message::where('is_read', false)->count();
        }
    @endphp
    <script src="{{ asset('assets/lib/bootstrap/jquery/jquery.js') }}"></script>
    <style>
        /* Theme variables (default = green) */
        :root {
            --accent: #06b6d4;
            --accent-gradient-from: #06b6d4;
            --accent-gradient-to: #0891b2;
            --accent-weak: rgba(6,182,212,0.1);
            --accent-strong: rgba(6,182,212,0.9);
            --text-on-accent: #ffffff;
        }

        [data-theme="blue"] {
            --accent: #3b82f6;
            --accent-gradient-from: #3b82f6;
            --accent-gradient-to: #6366f1;
            --accent-weak: rgba(59,130,246,0.1);
            --accent-strong: rgba(59,130,246,0.9);
            --text-on-accent: #ffffff;
        }

        [data-theme="orange"] {
            --accent: #f97316;
            --accent-gradient-from: #fb923c;
            --accent-gradient-to: #f97316;
            --accent-weak: rgba(249,115,22,0.1);
            --accent-strong: rgba(249,115,22,0.9);
            --text-on-accent: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1a1f2e 50%, #0d1117 100%);
            color: #e2e8f0;
            overflow-x: hidden;
        }
        .glass-card {
            background: linear-gradient(135deg, rgba(30,41,59,0.4) 0%, rgba(15,23,42,0.2) 100%);
            border: 1px solid rgba(148,163,184,0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }
        .sidebar {
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #0f172a 0%, #1a1f2e 100%);
            border-right: 1px solid rgba(148,163,184,0.1);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            padding: 24px 16px;
            overflow-y: auto;
            transition: all 0.3s ease;
        }
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(148,163,184,0.3); border-radius: 3px; }
        .sidebar-collapsed { width: 80px; padding: 24px 8px; }
        .sidebar .logo {
            font-size: 18px;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar .logo img {
            width: 32px;
            height: 32px;
            border-radius: 6px;
        }
        .sidebar-collapsed .logo span { display: none; }
        .nav-section { margin-bottom: 24px; }
        .nav-section-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }
        .sidebar-collapsed .nav-section-title {
            text-align: center;
            font-size: 18px;
            margin-bottom: 8px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            margin-bottom: 4px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 14px;
            font-weight: 500;
        }
        .nav-item:hover {
            background: var(--accent-weak);
            color: var(--accent);
        }
        .nav-item.active {
            background: var(--accent-weak);
            color: var(--accent);
            border-left: 3px solid var(--accent);
        }
        .nav-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }
        .nav-item span { flex: 1; }
        .sidebar-collapsed .nav-item span { display: none; }
        .main-container {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }
        .main-container.sidebar-collapsed { margin-left: 80px; }
        .header {
            height: 72px;
            background: linear-gradient(90deg, rgba(15,23,42,0.6) 0%, rgba(30,41,59,0.3) 100%);
            border-bottom: 1px solid rgba(148,163,184,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 24px;
            flex: 1;
        }
        .search-box {
            flex: 1;
            max-width: 400px;
        }
        .search-box input {
            width: 100%;
            padding: 8px 12px 8px 36px;
            background: rgba(30,41,59,0.4);
            border: 1px solid rgba(148,163,184,0.1);
            border-radius: 8px;
            color: #e2e8f0;
            font-size: 13px;
        }
        .search-box input::placeholder { color: #64748b; }
        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .header-icon-btn {
            width: 40px;
            height: 40px;
            background: rgba(30,41,59,0.4);
            border: 1px solid rgba(148,163,184,0.1);
            border-radius: 8px;
            color: #cbd5e1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .header-icon-btn:hover {
            background: var(--accent-weak);
            color: var(--accent);
        }
        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--accent-gradient-from), var(--accent-gradient-to));
        }
        .content {
            flex: 1;
            padding: 32px;
            overflow-y: auto;
        }
        .content::-webkit-scrollbar { width: 8px; }
        .content::-webkit-scrollbar-thumb {
            background: rgba(148,163,184,0.2);
            border-radius: 4px;
        }
        #dashboard-toasts {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .toast {
            padding: 12px 16px;
            background: rgba(30,41,59,0.8);
            border: 1px solid rgba(148,163,184,0.1);
            border-radius: 8px;
            color: #e2e8f0;
            font-size: 13px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            animation: slideIn 0.3s ease;
        }
        .toast.success { border-left: 3px solid #22c55e; }
        .toast.error { border-left: 3px solid #ef4444; }

        body.light {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #0f172a;
        }

        body.light .sidebar {
            background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%);
            border-color: rgba(15,23,42,0.08);
        }

        body.light .glass-card {
            background: rgba(255,255,255,0.85);
            border-color: rgba(148,163,184,0.3);
            color: #0f172a;
            box-shadow: 0 8px 32px rgba(15,23,42,0.15);
        }

        body.light .nav-item {
            color: #0f172a;
        }

        body.light .nav-item:hover,
        body.light .nav-item.active {
            background: rgba(6,182,212,0.08);
            color: #0f172a;
        }

        body.light .header {
            background: rgba(255,255,255,0.92);
            border-color: rgba(148,163,184,0.2);
        }

        body.light .header-icon-btn {
            background: rgba(255,255,255,0.85);
            color: #0f172a;
            border-color: rgba(148,163,184,0.2);
        }

        body.light .search-box input {
            background: rgba(255,255,255,0.95);
            color: #0f172a;
            border-color: rgba(148,163,184,0.3);
        }

        body.light .content {
            color: #0f172a;
        }

        body.light .modal {
            background: rgba(255,255,255,0.96);
            color: #0f172a;
        }

        body.light .toast {
            background: rgba(255,255,255,0.96);
            color: #0f172a;
            border-color: rgba(148,163,184,0.2);
        }

        body.light .badge {
            color: #0f172a;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 9998;
            backdrop-filter: blur(4px);
        }
        .modal-overlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal {
            background: linear-gradient(135deg, rgba(30,41,59,0.95) 0%, rgba(15,23,42,0.95) 100%);
            border: 1px solid rgba(148,163,184,0.1);
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }
        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px;
            border-bottom: 1px solid rgba(148,163,184,0.1);
        }
        .modal-header h2 {
            font-size: 18px;
            font-weight: 600;
            color: #f1f5f9;
        }
        .modal-close {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 24px;
        }
        .modal-body { padding: 24px; }
        .form-group { margin-bottom: 16px; }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 6px;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px 12px;
            background: rgba(30,41,59,0.4);
            border: 1px solid rgba(148,163,184,0.1);
            border-radius: 6px;
            color: #e2e8f0;
            font-size: 13px;
            transition: all 0.2s;
        }
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--accent);
            background: rgba(0,0,0,0.03);
        }
        .modal-footer {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            padding: 24px;
            border-top: 1px solid rgba(148,163,184,0.1);
        }
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent-gradient-from), var(--accent-gradient-to));
            color: var(--text-on-accent);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px var(--accent-weak);
        }
        .btn-secondary {
            background: rgba(30,41,59,0.4);
            color: #cbd5e1;
            border: 1px solid rgba(148,163,184,0.1);
        }
        .btn-secondary:hover { background: rgba(30,41,59,0.6); }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        @media (max-width: 768px) {
            .sidebar { width: 80px; }
            .main-container { margin-left: 80px; }
            .header { padding: 0 16px; }
            .content { padding: 16px; }
            .search-box { max-width: 100%; }
        }
    </style>
    <script>
        (function(){
            try {
                var theme = localStorage.getItem('appTheme') || 'green';
                document.documentElement.setAttribute('data-theme', theme);
                if (localStorage.getItem('darkMode') === 'true') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                var accents = { green: '#06b6d4', blue: '#3b82f6', orange: '#f97316' };
                var meta = document.querySelector('meta[name="theme-color"]');
                if (meta) meta.setAttribute('content', accents[theme] || accents.green);
            } catch (e) {
                console.warn('Theme init error', e);
            }
        })();
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @stack('styles')
</head>
<body x-data="adminApp()" x-init="init()">
    <aside class="sidebar" :class="{ 'sidebar-collapsed': !sidebarOpen }">
        <div class="logo">
            <img src="{{ asset('image/pdci.jpg') }}" alt="Logo">
            <span x-show="sidebarOpen">Belier Admin</span>
        </div>
        <nav>
            <div class="nav-section">
                <div class="nav-section-title">Menu</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-item active">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.articles.index') }}" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6m-6-4h6v4H7v-4z"/></svg>
                    <span>Articles</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Utilisateurs</span>
                </a>
               <!--- <a href="{{ route('admin.products.index') }}" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span>Produits</span>
                </a> --->
                <a href="{{ route('admin.orders.index') }}" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Commandes</span>
                </a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Autres</div>
                <a href="{{ route('admin.stats') }}" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Stats</span>
                </a>
                <a href="#settings" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Paramètres</span>
                </a>
            </div>
        </nav>
    </aside>
    <div class="main-container" :class="{ 'sidebar-collapsed': !sidebarOpen }">
        <header class="header">
            <div class="header-left">
                <button @click="sidebarOpen = !sidebarOpen" class="header-icon-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="search-box">
                    <input type="text" placeholder="Rechercher...">
                </div>
            </div>
            <div class="header-right" style="position: relative;">
                <button class="header-icon-btn" @click="toggleTheme()" title="Changer le thème">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z"/></svg>
                </button>
                <button class="header-icon-btn" @click="window.location.href='{{ route('admin.modal.messages') }}'" title="Notifications">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @if($notificationCount > 0)
                        <span style="position: absolute; top: 4px; right: 4px; width: 16px; height: 16px; border-radius: 999px; display: flex; align-items: center; justify-content: center; font-size: 10px; background: #ef4444; color: white;">{{ $notificationCount }}</span>
                    @endif
                </button>
                <div class="user-menu" style="gap: 12px;">
                    <div class="user-avatar"></div>
                    <div style="display:flex; flex-direction:column; align-items:flex-start;">
                        <span style="font-size:12px; color:#94a3b8;">Bonjour</span>
                        <span style="font-weight:700; color:#f8fafc;">{{ $adminName }}</span>
                    </div>
                    <form method="POST" action="{{ route('app_logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="header-icon-btn" title="Se déconnecter">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </header>
        <div class="content">
            @yield('content')
        </div>
    </div>
    <div id="dashboard-toasts"></div>
    <script>
        function adminApp() {
            return {
                sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
                theme: localStorage.getItem('dashboardTheme') || 'dark',

                init() {
                    const saved = localStorage.getItem('sidebarOpen');
                    if (saved !== null) this.sidebarOpen = saved === 'true';
                    this.applyTheme();
                    this.$watch('sidebarOpen', (val) => localStorage.setItem('sidebarOpen', val));
                },

                applyTheme() {
                    document.body.classList.toggle('light', this.theme === 'light');
                    document.body.classList.toggle('dark', this.theme !== 'light');
                },

                toggleTheme() {
                    this.theme = this.theme === 'dark' ? 'light' : 'dark';
                    localStorage.setItem('dashboardTheme', this.theme);
                    this.applyTheme();
                },

                showToast(message, type = 'info') {
                    const container = document.getElementById('dashboard-toasts');
                    const toast = document.createElement('div');
                    toast.className = `toast ${type}`;
                    toast.textContent = message;
                    container.appendChild(toast);
                    setTimeout(() => toast.remove(), 4500);
                }
            };
        }
    </script>
    @stack('scripts')
</body>
</html>
