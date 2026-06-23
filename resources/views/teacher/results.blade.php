<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('quizora.css') }}">
    <title>Quizora — Results</title>
    <style>
        :root {
            --color-primary-glow: #818CF8;
            --color-primary-solid: #4F46E5;
            --color-primary-dark: #2E2570;
            --color-bg-main: #0E0B20;
            --color-bg-card: #1E1A3E;
            --color-bg-row-hover: #241E47;
            --color-border-light: rgba(255, 255, 255, 0.08);
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

        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: linear-gradient(180deg, #1e1b45 0%, #131030 50%, #0e0b20 100%);
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
            padding: 20px 18px;
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

        .nav-badge {
            margin-left: auto;
            background: var(--color-primary-solid);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
            flex-shrink: 0;
        }

        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .nav-badge,
        .sidebar.collapsed .nav-label {
            display: none;
        }

        .sidebar-bottom {
            padding: 12px 8px;
            border-top: 1px solid var(--color-border-light);
        }

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

        .topbar {
            height: 64px;
            background: #1e1b45;
            border-bottom: 1px solid var(--color-border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
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

        .dropdown-item.danger:hover {
            background: rgba(248, 113, 113, 0.15);
            color: var(--color-status-error);
        }

        .dropdown-divider {
            height: 1px;
            background: var(--color-border-light);
            margin: 4px 0;
        }

        .content {
            padding: 28px;
            flex: 1;
        }

        /* PAGE SPECIFIC */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
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

        .results-layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 20px;
            align-items: start;
        }

        /* QUIZ LIST */
        .quiz-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .quiz-list-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .quiz-list-item:hover {
            border-color: rgba(79, 70, 229, 0.4);
            background: var(--color-bg-row-hover);
        }

        .quiz-list-item.active {
            border-color: var(--color-primary-solid);
            background: rgba(79, 70, 229, 0.15);
        }

        .quiz-list-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: rgba(79, 70, 229, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: var(--color-primary-glow);
            flex-shrink: 0;
        }

        .quiz-list-item.active .quiz-list-icon {
            background: var(--color-primary-solid);
            color: #fff;
        }

        .quiz-list-info {
            flex: 1;
            overflow: hidden;
        }

        .quiz-list-name {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .quiz-list-meta {
            font-size: 11px;
            color: var(--color-text-muted);
            margin-top: 2px;
        }

        /* STATS ROW */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .mini-stat {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
        }

        .mini-stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
        }

        .mini-stat-label {
            font-size: 11px;
            color: var(--color-text-muted);
            margin-top: 4px;
        }

        /* RESULTS TABLE */
        .results-table {
            width: 100%;
            border-collapse: collapse;
        }

        .results-table th {
            padding: 10px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: var(--color-text-muted);
            letter-spacing: 0.8px;
            text-transform: uppercase;
            border-bottom: 1px solid var(--color-border-light);
        }

        .results-table td {
            padding: 12px 16px;
            font-size: 13px;
            color: var(--color-text-secondary);
            border-bottom: 1px solid var(--color-border-light);
            vertical-align: middle;
        }

        .results-table tr:last-child td {
            border-bottom: none;
        }

        .results-table tr:hover td {
            background: var(--color-bg-row-hover);
        }

        .student-name {
            font-weight: 600;
            color: #fff;
        }

        .student-email {
            font-size: 11px;
            color: var(--color-text-muted);
            margin-top: 2px;
        }

        .score-bar-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .score-bar {
            flex: 1;
            height: 6px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 3px;
            overflow: hidden;
        }

        .score-bar-fill {
            height: 100%;
            border-radius: 3px;
        }

        .score-pct {
            font-size: 13px;
            font-weight: 700;
            min-width: 40px;
            text-align: right;
        }

        .rank-badge {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }

        .empty-results {
            text-align: center;
            padding: 48px 20px;
            color: var(--color-text-muted);
        }

        .empty-results i {
            font-size: 40px;
            display: block;
            margin-bottom: 12px;
            color: rgba(79, 70, 229, 0.3);
        }

        .empty-results p {
            font-size: 13px;
        }
    </style>
</head>

<body>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">Q</div>
            <div class="logo-text">Quiz<span>ora</span></div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-label">Main</div>
            <a href="{{ route('teacher.dashboard') }}" class="nav-item">
                <i class="ti ti-layout-dashboard nav-icon"></i>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="{{ route('teacher.quizzes') }}" class="nav-item">
                <i class="ti ti-file-description nav-icon"></i>
                <span class="nav-text">My Quizzes</span>
                <span class="nav-badge">{{ auth()->user()->quizzes()->count() }}</span>
            </a>
            <a href="{{ route('teacher.quiz.create') }}" class="nav-item">
                <i class="ti ti-circle-plus nav-icon"></i>
                <span class="nav-text">Create Quiz</span>
            </a>
            <div class="nav-label">Analytics</div>
            <a href="{{ route('teacher.results') }}" class="nav-item active">
                <i class="ti ti-chart-bar nav-icon"></i>
                <span class="nav-text">Results</span>
            </a>
            <a href="{{ route('teacher.leaderboard.page') }}" class="nav-item">
                <i class="ti ti-trophy nav-icon"></i>
                <span class="nav-text">Leaderboard</span>
            </a>
            <div class="nav-label">Manage</div>
            <a href="#" class="nav-item">
                <i class="ti ti-users nav-icon"></i>
                <span class="nav-text">Students</span>
            </a>
            <a href="#" class="nav-item">
                <i class="ti ti-database nav-icon"></i>
                <span class="nav-text">Question Bank</span>
            </a>
        </nav>
        <div class="sidebar-bottom">
            <a href="#" class="nav-item">
                <i class="ti ti-settings nav-icon"></i>
                <span class="nav-text">Settings</span>
            </a>
        </div>
    </aside>

    <button class="toggle-btn" id="toggleBtn">
        <i class="ti ti-chevron-left" id="toggleIcon"></i>
    </button>

    <main class="main" id="main">
        <header class="topbar">
            <div class="topbar-title">Results</div>
            <div class="topbar-right">
                <button class="notif-btn">
                    <i class="ti ti-bell"></i>
                    <span class="notif-dot"></span>
                </button>
                <div class="user-btn" id="userBtn">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div>
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">Teacher</div>
                    </div>
                    <i class="ti ti-chevron-down" style="font-size:14px;color:var(--color-text-muted);"></i>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="#" class="dropdown-item"><i class="ti ti-user"></i> Profile</a>
                        <a href="#" class="dropdown-item"><i class="ti ti-settings"></i> Settings</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item danger"
                                style="width:100%;border:none;text-align:left;">
                                <i class="ti ti-logout"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="content">

            @if(session('success'))
            <div id="toast" style="position:fixed;top:80px;left:50%;transform:translateX(-50%);background:rgba(52,211,153,0.15);border:1px solid rgba(52,211,153,0.4);color:#34D399;padding:12px 24px;border-radius:12px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:10px;z-index:9999;backdrop-filter:blur(12px);box-shadow:0 8px 32px rgba(0,0,0,0.3);transition:opacity 0.5s ease,transform 0.5s ease;">
                <i class="ti ti-circle-check" style="font-size:18px;"></i>
                {{ session('success') }}
            </div>
            @endif

            <div class="page-header">
                <div>
                    <h1>Results</h1>
                    <p>View student performance across all your quizzes</p>
                </div>
            </div>

            <div class="results-layout">

                <!-- QUIZ LIST -->
                <div>
                    <div style="font-size:12px;font-weight:600;color:var(--color-text-muted);letter-spacing:0.8px;text-transform:uppercase;margin-bottom:12px;">
                        Select Quiz
                    </div>

                    <div class="custom-select" id="quizCustomSelect">
                        <div class="custom-select-trigger" id="selectTrigger">
                            <span class="selected" id="triggerText">Midterm Examination — Physics</span>
                            <i class="ti ti-chevron-down custom-select-chevron"></i>
                        </div>
                        <div class="custom-select-dropdown">
                            <div class="custom-select-option selected" data-value="1">
                                <span>Midterm Examination — Physics</span>
                                <i class="ti ti-check check-icon"></i>
                            </div>
                            <div class="custom-select-option" data-value="2">
                                <span>Final Term — Higher Mathematics</span>
                                <i class="ti ti-check check-icon"></i>
                            </div>
                            <div class="custom-select-option" data-value="3">
                                <span>Programming Concept Quiz</span>
                                <i class="ti ti-check check-icon"></i>
                            </div>
                        </div>
                    </div>

                    <div class="quiz-list" style="margin-top: 16px;">
                        <div class="quiz-list-item active">
                            <div class="quiz-list-icon">
                                <i class="ti ti-file-description"></i>
                            </div>
                            <div class="quiz-list-info">
                                <div class="quiz-list-name">Midterm Examination — Physics</div>
                                <div class="quiz-list-meta">142 submitted · Active</div>
                            </div>
                        </div>
                        <div class="quiz-list-item">
                            <div class="quiz-list-icon">
                                <i class="ti ti-file-description"></i>
                            </div>
                            <div class="quiz-list-info">
                                <div class="quiz-list-name">Final Term — Higher Mathematics</div>
                                <div class="quiz-list-meta">98 submitted · Closed</div>
                            </div>
                        </div>
                        <div class="quiz-list-item">
                            <div class="quiz-list-icon">
                                <i class="ti ti-file-description"></i>
                            </div>
                            <div class="quiz-list-info">
                                <div class="quiz-list-name">Programming Concept Quiz</div>
                                <div class="quiz-list-meta">64 submitted · Active</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RESULTS PANEL -->
                <div id="resultsPanel">

                    <div class="stats-row" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 24px;">
                        <div class="mini-stat">
                            <div class="mini-stat-value">142</div>
                            <div class="mini-stat-label">Total Submissions</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value" style="color:var(--color-status-success)">76%</div>
                            <div class="mini-stat-label">Average Score</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value" style="color:var(--color-primary-glow)">98%</div>
                            <div class="mini-stat-label">Highest Score</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value" style="color:var(--color-status-error)">12%</div>
                            <div class="mini-stat-label">Lowest Score</div>
                        </div>
                    </div>

                    <div class="card" style="background: var(--color-bg-card); border: 1px solid var(--color-border-light); border-radius: 14px; overflow: hidden; padding: 20px;">
                        <div class="card-header" style="margin-bottom: 16px;">
                            <h2 style="font-size: 16px; font-weight: 600;">Student Submissions</h2>
                        </div>
                        <table class="results-table" style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Student</th>
                                    <th>Score</th>
                                    <th>Performance</th>
                                    <th>Submitted</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="rank-badge" style="background: rgba(245, 158, 11, 0.15); color: #F59E0B;">🥇</div>
                                    </td>
                                    <td>
                                        <div class="student-name">Nahian Islam</div>
                                        <div class="student-email">nahian@example.com</div>
                                    </td>
                                    <td style="font-weight: 700; color: #fff;">49 / 50</td>
                                    <td>
                                        <div class="score-bar-wrap">
                                            <div class="score-bar">
                                                <div class="score-bar-fill" style="width: 98%; background: var(--color-status-success);"></div>
                                            </div>
                                            <div class="score-pct" style="color: var(--color-status-success);">98%</div>
                                        </div>
                                    </td>
                                    <td>2 hours ago</td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="rank-badge" style="background: rgba(156, 163, 175, 0.15); color: #9CA3AF;">🥈</div>
                                    </td>
                                    <td>
                                        <div class="student-name">Rafi Ahmed</div>
                                        <div class="student-email">rafi@example.com</div>
                                    </td>
                                    <td style="font-weight: 700; color: #fff;">45 / 50</td>
                                    <td>
                                        <div class="score-bar-wrap">
                                            <div class="score-bar">
                                                <div class="score-bar-fill" style="width: 90%; background: var(--color-status-success);"></div>
                                            </div>
                                            <div class="score-pct" style="color: var(--color-status-success);">90%</div>
                                        </div>
                                    </td>
                                    <td>Yesterday</td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="rank-badge" style="background: rgba(217, 119, 6, 0.15); color: #D97706;">🥉</div>
                                    </td>
                                    <td>
                                        <div class="student-name">Sadia Rahman</div>
                                        <div class="student-email">sadia@example.com</div>
                                    </td>
                                    <td style="font-weight: 700; color: #fff;">37 / 50</td>
                                    <td>
                                        <div class="score-bar-wrap">
                                            <div class="score-bar">
                                                <div class="score-bar-fill" style="width: 74%; background: var(--color-stat-cyan);"></div>
                                            </div>
                                            <div class="score-pct" style="color: var(--color-stat-cyan);">74%</div>
                                        </div>
                                    </td>
                                    <td>3 days ago</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </main>

    <script src="{{ asset('quizora.js') }}"></script>
    <script>
        // Sidebar
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const toggleIcon = document.getElementById('toggleIcon');
        toggleBtn.addEventListener('click', () => {
            const collapsed = sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('collapsed', collapsed);
            toggleIcon.className = collapsed ? 'ti ti-chevron-right' : 'ti ti-chevron-left';
        });

        // User dropdown
        const userBtn = document.getElementById('userBtn');
        const userDropdown = document.getElementById('userDropdown');
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('open');
        });
        document.addEventListener('click', () => userDropdown.classList.remove('open'));

        // Toast
        const toast = document.getElementById('toast');
        if (toast) {
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(-50%) translateY(-12px)';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }

        // Load results
        function loadResults(quizId, el) {
            document.querySelectorAll('.quiz-list-item').forEach(i => i.classList.remove('active'));
            el.classList.add('active');

            const panel = document.getElementById('resultsPanel');
            panel.innerHTML = '<div style="text-align:center;padding:48px;color:var(--color-text-muted);font-size:13px;">Loading...</div>';

            fetch(`/teacher/results/${quizId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.attempts.length === 0) {
                        panel.innerHTML = `
            <div class="empty-results">
              <i class="ti ti-inbox"></i>
              <p>No submissions yet for this quiz.</p>
            </div>`;
                        return;
                    }

                    const medals = ['🥇', '🥈', '🥉'];
                    const colors = ['#F59E0B', '#9CA3AF', '#D97706'];

                    function scoreColor(pct) {
                        if (pct >= 80) return 'var(--color-status-success)';
                        if (pct >= 50) return 'var(--color-stat-cyan)';
                        return 'var(--color-status-error)';
                    }

                    panel.innerHTML = `
          <!-- STATS ROW -->
          <div class="stats-row">
            <div class="mini-stat">
              <div class="mini-stat-value">${data.stats.submissions}</div>
              <div class="mini-stat-label">Submissions</div>
            </div>
            <div class="mini-stat">
              <div class="mini-stat-value" style="color:var(--color-status-success)">${data.stats.avg}%</div>
              <div class="mini-stat-label">Average Score</div>
            </div>
            <div class="mini-stat">
              <div class="mini-stat-value" style="color:var(--color-primary-glow)">${data.stats.highest}%</div>
              <div class="mini-stat-label">Highest Score</div>
            </div>
            <div class="mini-stat">
              <div class="mini-stat-value" style="color:var(--color-status-error)">${data.stats.lowest}%</div>
              <div class="mini-stat-label">Lowest Score</div>
            </div>
          </div>

          <!-- TABLE -->
          <div class="card">
            <div class="card-header">
              <h2>Student Submissions</h2>
              <span style="font-size:12px;color:var(--color-text-muted);">${data.stats.submissions} total</span>
            </div>
            <table class="results-table">
              <thead>
                <tr>
                  <th>Rank</th>
                  <th>Student</th>
                  <th>Score</th>
                  <th>Performance</th>
                  <th>Submitted</th>
                </tr>
              </thead>
              <tbody>
                ${data.attempts.map((a, i) => `
                  <tr>
                    <td>
                      <div class="rank-badge" style="background:${i < 3 ? colors[i] + '22' : 'rgba(255,255,255,0.05)'};color:${i < 3 ? colors[i] : 'var(--color-text-muted)'};">
                        ${i < 3 ? medals[i] : i + 1}
                      </div>
                    </td>
                    <td>
                      <div class="student-name">${a.name}</div>
                      <div class="student-email">${a.email}</div>
                    </td>
                    <td style="font-weight:700;color:#fff;">${a.score} / ${a.total}</td>
                    <td>
                      <div class="score-bar-wrap">
                        <div class="score-bar">
                          <div class="score-bar-fill" style="width:${a.percentage}%;background:${scoreColor(a.percentage)}"></div>
                        </div>
                        <div class="score-pct" style="color:${scoreColor(a.percentage)}">${a.percentage}%</div>
                      </div>
                    </td>
                    <td>${a.submitted}</td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        `;
                })
                .catch(() => {
                    panel.innerHTML = '<div style="text-align:center;padding:48px;color:var(--color-status-error);font-size:13px;">Failed to load results.</div>';
                });
        }
        const customSelect = document.getElementById('quizCustomSelect');
        const selectTrigger = document.getElementById('selectTrigger');
        const triggerText = document.getElementById('triggerText');
        const options = document.querySelectorAll('.custom-select-option');

        // Toggle the drop-down view open/close state
        selectTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            customSelect.classList.toggle('open');
        });

        // Toggle selection state styles when option variants are clicked
        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();

                options.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');

                triggerText.textContent = this.querySelector('span').textContent;
                customSelect.classList.remove('open');
            });
        });

        // Safely close select box windows when clicking anywhere else on the document
        document.addEventListener('click', () => {
            customSelect.classList.remove('open');
        });
    </script>
</body>

</html>