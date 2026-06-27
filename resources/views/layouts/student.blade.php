<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('quizora.css') }}">
    <title>@yield('title', 'Quizora')</title>
    <style>
        :root {
            --color-primary-glow: #818CF8;
            --color-primary-solid: #4F46E5;
            --color-primary-dark: #2E2570;
            --color-bg-main: #0E0B20;
            --color-bg-card: #161233;
            --color-bg-row-hover: #1E1A3E;
            --color-border-light: rgba(255, 255, 255, 0.08);
            --color-border-solid: #2E2570;
            --color-text-primary: #FFFFFF;
            --color-text-secondary: #9CA3AF;
            --color-text-muted: #6B7280;
            --color-stat-purple: #A78BFA;
            --color-stat-cyan: #22D3EE;
            --color-status-success: #34D399;
            --color-status-error: #F87171;
            --sidebar-w: 220px;
            --sidebar-collapsed: 64px;
            --font: 'Plus Jakarta Sans', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font);
            background: var(--color-bg-main);
            color: var(--color-text-primary);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        .page-header {
            margin-bottom: 20px;
        }

        .page-header h1 {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
        }

        .page-header p {
            font-size: 13px;
            color: var(--color-text-muted);
            margin-top: 4px;
        }

        .quiz-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(268px, 1fr));
            gap: 18px;
        }

        .pquiz-card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 16px;
            transition: border-color 0.22s, transform 0.22s, box-shadow 0.22s;
            text-decoration: none;
            display: block;
            position: relative;
            overflow: hidden;
        }

        .pquiz-card:hover {
            border-color: rgba(79, 70, 229, 0.5);
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
        }

        .pquiz-banner {
            height: 96px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .pquiz-banner-icon {
            font-size: 38px;
            color: rgba(255, 255, 255, 0.9);
            position: relative;
            z-index: 1;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3));
        }

        .pquiz-banner-deco {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            pointer-events: none;
            overflow: hidden;
        }

        .pquiz-banner-deco.d1 {
            width: 80px;
            height: 80px;
            bottom: -24px;
            right: -20px;
        }

        .pquiz-banner-deco.d2 {
            width: 44px;
            height: 44px;
            top: -10px;
            left: 16px;
        }

        .pquiz-body {
            padding: 16px 18px 18px;
        }

        .pquiz-category {
            font-size: 10px;
            font-weight: 700;
            color: var(--color-primary-glow);
            text-transform: uppercase;
            letter-spacing: 0.7px;
            margin-bottom: 6px;
        }

        .pquiz-title {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .pquiz-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .pquiz-meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            color: var(--color-text-muted);
        }

        .pquiz-meta-item i {
            font-size: 13px;
        }

        .pquiz-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 12px;
            border-top: 1px solid var(--color-border-light);
        }

        .diff-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .diff-easy {
            background: rgba(52, 211, 153, 0.15);
            color: #34D399;
        }

        .diff-medium {
            background: rgba(245, 158, 11, 0.15);
            color: #F59E0B;
        }

        .diff-hard {
            background: rgba(248, 113, 113, 0.15);
            color: #F87171;
        }

        .pquiz-take-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(79, 70, 229, 0.18);
            border: 1px solid rgba(79, 70, 229, 0.35);
            color: var(--color-primary-glow);
            font-size: 12px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.2s;
            font-family: var(--font);
        }

        .pquiz-take-btn:hover {
            background: rgba(79, 70, 229, 0.3);
            color: #fff;
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background:
                radial-gradient(ellipse 300px 400px at 50% 0%, rgba(99, 102, 241, 0.20) 0%, transparent 70%),
                linear-gradient(180deg, #1e1b45 0%, #141130 50%, #0e0b20 100%);
            border-right: 1px solid var(--color-border-light);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 100;
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar-logo {
            padding: 10px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--color-border-light);
            min-height: 64px;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: var(--color-primary-solid);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .logo-text {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
        }

        .logo-text span {
            color: var(--color-primary-glow);
        }

        .sidebar-nav {
            flex: 1;
            padding: 12px 8px;
            overflow: hidden;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 600;
            color: var(--color-text-muted);
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 8px 10px 4px;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s;
        }

        .sidebar.collapsed .nav-label {
            opacity: 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 10px;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s;
            margin-bottom: 2px;
            white-space: nowrap;
            text-decoration: none;
        }

        .nav-item:hover {
            background: var(--color-bg-row-hover);
        }

        .nav-item.active {
            background: rgba(79, 70, 229, 0.25);
        }

        .nav-item.active .nav-icon {
            color: var(--color-primary-glow);
        }

        .nav-item.active .nav-text {
            color: #fff;
            font-weight: 600;
        }

        .nav-icon {
            font-size: 20px;
            color: var(--color-text-secondary);
            flex-shrink: 0;
            width: 24px;
            text-align: center;
        }

        .nav-text {
            font-size: 14px;
            color: var(--color-text-secondary);
            overflow: hidden;
        }

        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .nav-label {
            display: none;
        }

        .sidebar-bottom {
            padding: 12px 8px;
            border-top: 1px solid var(--color-border-light);
        }

        /* TOGGLE BTN */
        .toggle-btn {
            position: fixed;
            left: calc(var(--sidebar-w) - 14px);
            top: 22px;
            width: 28px;
            height: 28px;
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1), background 0.2s;
            z-index: 101;
            color: var(--color-text-secondary);
            font-size: 14px;
        }

        .toggle-btn:hover {
            background: var(--color-bg-row-hover);
            color: #fff;
        }

        body.collapsed .toggle-btn {
            left: calc(var(--sidebar-collapsed) - 14px);
        }

        /* MAIN */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        body.collapsed .main {
            margin-left: var(--sidebar-collapsed);
        }

        /* TOPBAR */
        .topbar {
            height: 64px;
            background: #1e1b45;
            border-bottom: 1px solid var(--color-border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 28px;
            gap: 16px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            flex: 1;
        }

        .topbar-sub {
            font-size: 13px;
            color: var(--color-text-muted);
            margin-top: 2px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .notif-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid var(--color-border-light);
            background: transparent;
            color: var(--color-text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            position: relative;
            transition: background 0.2s, color 0.2s;
        }

        .notif-btn:hover {
            background: var(--color-bg-row-hover);
            color: #fff;
        }

        .notif-dot {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 7px;
            height: 7px;
            background: var(--color-status-error);
            border-radius: 50%;
            border: 1.5px solid var(--color-bg-card);
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px 6px 6px;
            border-radius: 10px;
            border: 1px solid var(--color-border-light);
            background: transparent;
            cursor: pointer;
            transition: background 0.2s;
            position: relative;
        }

        .user-btn:hover {
            background: var(--color-bg-row-hover);
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--color-primary-solid), var(--color-stat-purple));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
        }

        .user-role {
            font-size: 11px;
            color: var(--color-text-muted);
        }

        .user-chevron {
            font-size: 14px;
            color: var(--color-text-muted);
        }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 180px;
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 12px;
            padding: 6px;
            display: none;
            z-index: 200;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.4);
        }

        .user-dropdown.open {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 13px;
            color: var(--color-text-secondary);
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            text-decoration: none;
        }

        .dropdown-item:hover {
            background: var(--color-bg-row-hover);
            color: #fff;
        }

        .dropdown-item.danger {
            background-color: rgba(153, 27, 27, 0.3);
            color: #FCA5A5;
        }

        .dropdown-item.danger:hover {
            background-color: rgba(239, 68, 68, 0.15);
            color: #F87171;
        }

        .dropdown-divider {
            height: 1px;
            background: var(--color-border-light);
            margin: 4px 0;
        }

        /* CONTENT WRAPPER */
        .content {
            padding: 20px;
            flex: 1;
        }

        /* SHARED COMPONENT STYLES */
        .card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 14px;
            overflow: hidden;
        }

        .card-header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--color-border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h2 {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-badge.active {
            background: rgba(52, 211, 153, 0.15);
            color: var(--color-status-success);
        }

        .status-badge.draft {
            background: rgba(107, 114, 128, 0.2);
            color: var(--color-text-secondary);
        }

        .status-badge.closed {
            background: rgba(248, 113, 113, 0.15);
            color: var(--color-status-error);
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            display: inline-block;
        }

        .action-btns {
            display: flex;
            gap: 6px;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1px solid var(--color-border-light);
            background: transparent;
            color: var(--color-text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 15px;
            transition: background 0.15s, color 0.15s, border-color 0.15s;
        }

        .action-btn:hover {
            background: var(--color-bg-row-hover);
            color: #fff;
            border-color: rgba(79, 70, 229, 0.4);
        }

        .view-all-link {
            font-size: 12px;
            font-weight: 600;
            color: var(--color-primary-glow);
            background: transparent;
            border: 1px solid var(--color-border-light);
            border-radius: 8px;
            padding: 5px 14px;
            cursor: pointer;
            font-family: var(--font);
            transition: background 0.15s;
            text-decoration: none;
        }

        .view-all-link:hover {
            background: var(--color-bg-row-hover);
        }

        /* Professional Bookmark Button */
        .bookmark-btn {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            z-index: 20;
            transition: all 0.25s ease;
            cursor: pointer;
        }

        .bookmark-btn:hover {
            background: rgba(0, 0, 0, 0.7);
            transform: scale(1.08);
        }

        .bookmark-btn.bookmarked {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .bookmark-btn.bookmarked svg {
            stroke: #FFFFFF;
            fill: #FFFFFF;
        }

        .empty-state {
            text-align: center;
            padding: 90px 40px;
            color: var(--color-text-muted);
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 20px;
            margin-top: 40px;
        }

        .empty-icon {
            font-size: 68px;
            margin-bottom: 24px;
            color: rgba(79, 70, 229, 0.4);
            display: block;
        }

        .empty-state h3 {
            font-size: 20px;
            font-weight: 600;
            color: var(--color-text-secondary);
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 14.5px;
            max-width: 260px;
            margin: 0 auto 32px;
            line-height: 1.5;
        }

        /* Button */
        .empty-browse-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--color-primary-solid), #6366F1);
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            padding: 16px 40px;
            border-radius: 14px;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
        }

        .empty-browse-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.45);
            background: linear-gradient(135deg, #4338CA, #4F46E5);
        }
    </style>
    @stack('styles')
</head>

<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">Q</div>
            <div class="logo-text">Quiz<span>ora</span></div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-label">Main</div>
            <a href="{{ route('student.dashboard') }}"
                class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <i class="ti ti-layout-dashboard nav-icon"></i>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="{{ route('student.browse') }}"
                class="nav-item {{ request()->routeIs('student.browse') ? 'active' : '' }}">
                <i class="ti ti-compass nav-icon"></i>
                <span class="nav-text">Browse Quizzes</span>
            </a>
            <a href="{{ route('student.bookmarks') }}"
                class="nav-item {{ request()->routeIs('student.bookmarks') ? 'active' : '' }}">
                <i class="ti ti-bookmark nav-icon"></i>
                <span class="nav-text">Bookmarks</span>
            </a>
            <div class="nav-label">Activity</div>
            <a href="{{ route('student.results') }}"
                class="nav-item {{ request()->routeIs('student.results') ? 'active' : '' }}">
                <i class="ti ti-history nav-icon"></i>
                <span class="nav-text">My Results</span>
            </a>
            <a href="{{ route('student.leaderboard.page') }}"
                class="nav-item {{ request()->routeIs('student.leaderboard.page') ? 'active' : '' }}">
                <i class="ti ti-trophy nav-icon"></i>
                <span class="nav-text">Leaderboard</span>
            </a>
        </nav>
        <div class="sidebar-bottom">
            <a href="{{ route('profile.edit') }}"
                class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <i class="ti ti-settings nav-icon" aria-hidden="true"></i>
                <span class="nav-text">Settings</span>
            </a>
        </div>
    </aside>

    <button class="toggle-btn" id="toggleBtn" aria-label="Toggle sidebar">
        <i class="ti ti-chevron-left" id="toggleIcon" aria-hidden="true"></i>
    </button>

    {{-- MAIN --}}
    <main class="main" id="main">
        <header class="topbar">
            <div>
                <div class="topbar-title">Good morning, {{ auth()->user()->name }} 👋</div>
                <div class="topbar-sub">{{ now()->format('l, F j, Y') }}</div>
            </div>
            <div class="topbar-right">
                <button class="notif-btn" aria-label="Notifications">
                    <i class="ti ti-bell" aria-hidden="true"></i>
                    <span class="notif-dot"></span>
                </button>
                <div class="user-btn" id="userBtn" role="button" tabindex="0">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div>
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">Student</div>
                    </div>
                    <i class="ti ti-chevron-down user-chevron" aria-hidden="true"></i>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="#" class="dropdown-item"><i class="ti ti-user" aria-hidden="true"></i> Profile</a>
                        <a href="#" class="dropdown-item"><i class="ti ti-settings" aria-hidden="true"></i> Settings</a>
                        <a href="#" class="dropdown-item"><i class="ti ti-help-circle" aria-hidden="true"></i> Help</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item danger" style="width:100%;border:none;text-align:left;">
                                <i class="ti ti-logout" aria-hidden="true"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="content">
            @yield('content')
        </div>
    </main>

    {{-- SHARED JS --}}
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const toggleIcon = document.getElementById('toggleIcon');
        toggleBtn.addEventListener('click', () => {
            const collapsed = sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('collapsed', collapsed);
            toggleIcon.className = collapsed ? 'ti ti-chevron-right' : 'ti ti-chevron-left';
        });

        const userBtn = document.getElementById('userBtn');
        const userDropdown = document.getElementById('userDropdown');
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('open');
        });
        document.addEventListener('click', () => userDropdown.classList.remove('open'));
    </script>

    {{-- TOAST --}}
    @if(session('success'))
    <div id="toast" style="
    position:fixed; top:80px; left:50%; transform:translateX(-50%);
    background:rgba(52,211,153,0.15); border:1px solid rgba(52,211,153,0.4);
    color:#34D399; padding:12px 24px; border-radius:12px;
    font-size:13px; font-weight:600; display:flex; align-items:center; gap:10px;
    z-index:9999; backdrop-filter:blur(12px);
    box-shadow:0 8px 32px rgba(0,0,0,0.3);
    transition:opacity 0.5s ease, transform 0.5s ease;">
        <i class="ti ti-circle-check" style="font-size:18px;"></i>
        {{ session('success') }}
    </div>
    <script>
        const toast = document.getElementById('toast');
        if (toast) {
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(-50%) translateY(-12px)';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }
    </script>
    @endif
    <script src="{{ asset('quizora.js') }}"></script>
    <script>
        function toggleBookmark(e, btn, quizId) {
            e.preventDefault();
            e.stopPropagation();

            fetch(`/student/bookmarks/${quizId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.bookmarked) {
                        btn.classList.add('bookmarked');
                        btn.title = 'Remove bookmark';
                    } else {
                        btn.classList.remove('bookmarked');
                        btn.title = 'Bookmark this quiz';
                    }

                    // Update all cards with same quiz
                    document.querySelectorAll(`.bookmark-btn[data-quiz-id="${quizId}"]`).forEach(b => {
                        if (b !== btn) {
                            if (data.bookmarked) {
                                b.classList.add('bookmarked');
                            } else {
                                b.classList.remove('bookmarked');
                            }
                        }
                    });
                })
                .catch(() => {
                    alert('Failed to update bookmark. Please try again.');
                });
        }
    </script>
    @stack('scripts')
</body>

</html>