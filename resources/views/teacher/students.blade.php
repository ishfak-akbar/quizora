<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('quizora.css') }}">
    <title>Quizora — Students</title>
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

        *,
        *::before,
        *::after {
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

        /* SIDEBAR */
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
            padding: 10px;
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

        /* TOGGLE */
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

        /* CONTENT */
        .content {
            padding: 28px;
            flex: 1;
        }

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

        /* STATS ROW */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 14px;
            padding: 18px 20px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
        }

        .stat-card.purple::before {
            background: linear-gradient(90deg, #4F46E5, #A78BFA);
        }

        .stat-card.cyan::before {
            background: linear-gradient(90deg, #0891B2, #22D3EE);
        }

        .stat-card.green::before {
            background: linear-gradient(90deg, #059669, #34D399);
        }

        .stat-card.amber::before {
            background: linear-gradient(90deg, #D97706, #F59E0B);
        }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: #fff;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 12px;
            color: var(--color-text-muted);
            font-weight: 500;
        }

        /* FILTERS */
        .filters {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1.5px solid var(--color-border-light);
            border-radius: 10px;
            padding: 0 14px;
            flex: 1;
            max-width: 420px;
            transition: border-color 0.2s;
        }

        .search-wrap:focus-within {
            border-color: rgba(79, 70, 229, 0.6);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .search-wrap input {
            flex: 1;
            height: 38px;
            background: none;
            border: none;
            outline: none;
            color: #fff;
            font-size: 13px;
            font-family: var(--font);
        }

        .search-wrap input::placeholder {
            color: var(--color-text-muted);
        }

        .search-wrap i {
            color: var(--color-text-muted);
            font-size: 16px;
        }

        .filter-btn {
            height: 38px;
            padding: 0 16px;
            border-radius: 10px;
            border: 1.5px solid var(--color-border-light);
            background: transparent;
            color: var(--color-text-secondary);
            font-size: 13px;
            font-family: var(--font);
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: rgba(79, 70, 229, 0.2);
            border-color: rgba(79, 70, 229, 0.5);
            color: var(--color-primary-glow);
            font-weight: 600;
        }

        /* TABLE */
        .table-card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 14px;
            overflow: hidden;
        }

        .table-card table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-card th {
            padding: 11px 20px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: var(--color-text-muted);
            letter-spacing: 0.8px;
            text-transform: uppercase;
            border-bottom: 1px solid var(--color-border-light);
            background: rgba(255, 255, 255, 0.02);
        }

        .table-card td {
            padding: 14px 20px;
            font-size: 13px;
            color: var(--color-text-secondary);
            border-bottom: 1px solid var(--color-border-light);
            vertical-align: middle;
        }

        .table-card tr:last-child td {
            border-bottom: none;
        }

        .table-card tr:hover td {
            background: var(--color-bg-row-hover);
        }

        .student-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .student-avatar {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .student-name {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
        }

        .student-email {
            font-size: 11px;
            color: var(--color-text-muted);
            margin-top: 2px;
        }

        .score-pill {
            display: inline-flex;
            align-items: center;
            font-size: 12px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            border: 1.5px solid;
        }

        .score-high {
            color: #34D399;
            border-color: rgba(52, 211, 153, 0.35);
            background: rgba(52, 211, 153, 0.08);
        }

        .score-mid {
            color: #F59E0B;
            border-color: rgba(245, 158, 11, 0.35);
            background: rgba(245, 158, 11, 0.08);
        }

        .score-low {
            color: #F87171;
            border-color: rgba(248, 113, 113, 0.35);
            background: rgba(248, 113, 113, 0.08);
        }

        .activity-dot {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
        }

        .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
        }

        .dot-active {
            background: #34D399;
            box-shadow: 0 0 6px rgba(52, 211, 153, 0.5);
        }

        .dot-recent {
            background: #F59E0B;
        }

        .dot-inactive {
            background: #6B7280;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1px solid var(--color-border-light);
            background: transparent;
            color: var(--color-text-muted);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 15px;
            text-decoration: none;
            transition: background 0.15s, color 0.15s, border-color 0.15s;
        }

        .action-btn:hover {
            background: var(--color-bg-row-hover);
            color: #fff;
            border-color: rgba(79, 70, 229, 0.4);
        }

        /* MODAL */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 500;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal {
            background: #1E1A3E;
            border: 1px solid var(--color-border-light);
            border-radius: 20px;
            padding: 32px;
            width: 100%;
            max-width: 540px;
            position: relative;
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.6);
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .modal-title {
            font-size: 17px;
            font-weight: 700;
            color: #fff;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--color-border-light);
            background: transparent;
            color: var(--color-text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.15s, color 0.15s;
        }

        .modal-close:hover {
            background: var(--color-bg-row-hover);
            color: #fff;
        }

        .modal-student-header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--color-border-light);
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .modal-avatar {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            color: #fff;
        }

        .modal-student-name {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
        }

        .modal-student-email {
            font-size: 12px;
            color: var(--color-text-muted);
            margin-top: 3px;
        }

        .modal-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .modal-stat {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--color-border-light);
            border-radius: 10px;
            padding: 14px;
            text-align: center;
        }

        .modal-stat-value {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
        }

        .modal-stat-label {
            font-size: 11px;
            color: var(--color-text-muted);
            margin-top: 4px;
        }

        .modal-section-title {
            font-size: 12px;
            font-weight: 600;
            color: var(--color-text-muted);
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .attempt-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--color-border-light);
            border-radius: 10px;
            margin-bottom: 8px;
        }

        .attempt-quiz {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
        }

        .attempt-date {
            font-size: 11px;
            color: var(--color-text-muted);
            margin-top: 2px;
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
            </a>
            <a href="{{ route('teacher.quiz.create') }}" class="nav-item">
                <i class="ti ti-circle-plus nav-icon"></i>
                <span class="nav-text">Create Quiz</span>
            </a>
            <div class="nav-label">Analytics</div>
            <a href="{{ route('teacher.results') }}" class="nav-item">
                <i class="ti ti-chart-bar nav-icon"></i>
                <span class="nav-text">Results</span>
            </a>
            <a href="{{ route('teacher.leaderboard.page') }}" class="nav-item">
                <i class="ti ti-trophy nav-icon"></i>
                <span class="nav-text">Leaderboard</span>
            </a>
            <div class="nav-label">Manage</div>
            <a href="#" class="nav-item active">
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
            <div class="topbar-title">Students</div>
            <div class="topbar-right">
                <div class="user-btn" id="userBtn">
                    <div class="user-avatar">T</div>
                    <div>
                        <div class="user-name">Teacher</div>
                        <div class="user-role">Teacher</div>
                    </div>
                    <i class="ti ti-chevron-down" style="font-size:14px;color:var(--color-text-muted);"></i>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="#" class="dropdown-item"><i class="ti ti-user"></i> Profile</a>
                        <a href="#" class="dropdown-item"><i class="ti ti-settings"></i> Settings</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item danger"><i class="ti ti-logout"></i> Logout</a>
                    </div>
                </div>
            </div>
        </header>

        <div class="content">

            <div class="page-header">
                <div>
                    <h1>Students</h1>
                    <p>All students who have attempted your quizzes</p>
                </div>
            </div>

            <!-- STATS -->
            <div class="stats-row">
                <div class="stat-card purple">
                    <div class="stat-value">24</div>
                    <div class="stat-label">Total Students</div>
                </div>
                <div class="stat-card cyan">
                    <div class="stat-value">18</div>
                    <div class="stat-label">Active This Month</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-value">76%</div>
                    <div class="stat-label">Avg. Score</div>
                </div>
                <div class="stat-card amber">
                    <div class="stat-value">142</div>
                    <div class="stat-label">Total Attempts</div>
                </div>
            </div>

            <!-- FILTERS -->
            <div class="filters">
                <div class="search-wrap">
                    <i class="ti ti-search"></i>
                    <input type="text" id="searchInput" placeholder="Search by name or email...">
                </div>
                <button class="filter-btn active" onclick="filterStudents('all', this)">All</button>
                <button class="filter-btn" onclick="filterStudents('active', this)">Active</button>
                <button class="filter-btn" onclick="filterStudents('recent', this)">Recent</button>
                <button class="filter-btn" onclick="filterStudents('inactive', this)">Inactive</button>
            </div>

            <!-- TABLE -->
            <div class="table-card">
                <table id="studentsTable">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Quizzes Taken</th>
                            <th>Avg Score</th>
                            <th>Last Active</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="studentsBody">

                        <tr data-status="active" data-name="nahian islam">
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar" style="background:linear-gradient(135deg,#4F46E5,#818CF8);">N</div>
                                    <div>
                                        <div class="student-name">Nahian Islam</div>
                                        <div class="student-email">nahian@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td>12</td>
                            <td><span class="score-pill score-high">88%</span></td>
                            <td>Today</td>
                            <td><span class="activity-dot"><span class="dot dot-active"></span> Active</span></td>
                            <td>
                                <button class="action-btn" title="View Details"
                                    onclick="openModal('Nahian Islam','nahian@example.com','N','linear-gradient(135deg,#4F46E5,#818CF8)','12','88%','3')">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <tr data-status="active" data-name="rafi ahmed">
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar" style="background:linear-gradient(135deg,#059669,#34D399);">R</div>
                                    <div>
                                        <div class="student-name">Rafi Ahmed</div>
                                        <div class="student-email">rafi@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td>9</td>
                            <td><span class="score-pill score-high">92%</span></td>
                            <td>Yesterday</td>
                            <td><span class="activity-dot"><span class="dot dot-active"></span> Active</span></td>
                            <td>
                                <button class="action-btn" title="View Details"
                                    onclick="openModal('Rafi Ahmed','rafi@example.com','R','linear-gradient(135deg,#059669,#34D399)','9','92%','2')">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <tr data-status="recent" data-name="sadia rahman">
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar" style="background:linear-gradient(135deg,#DB2777,#F472B6);">S</div>
                                    <div>
                                        <div class="student-name">Sadia Rahman</div>
                                        <div class="student-email">sadia@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td>7</td>
                            <td><span class="score-pill score-mid">71%</span></td>
                            <td>Jun 18, 2026</td>
                            <td><span class="activity-dot"><span class="dot dot-recent"></span> Recent</span></td>
                            <td>
                                <button class="action-btn" title="View Details"
                                    onclick="openModal('Sadia Rahman','sadia@example.com','S','linear-gradient(135deg,#DB2777,#F472B6)','7','71%','1')">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <tr data-status="recent" data-name="imran hossain">
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar" style="background:linear-gradient(135deg,#D97706,#F59E0B);">I</div>
                                    <div>
                                        <div class="student-name">Imran Hossain</div>
                                        <div class="student-email">imran@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td>5</td>
                            <td><span class="score-pill score-mid">65%</span></td>
                            <td>Jun 15, 2026</td>
                            <td><span class="activity-dot"><span class="dot dot-recent"></span> Recent</span></td>
                            <td>
                                <button class="action-btn" title="View Details"
                                    onclick="openModal('Imran Hossain','imran@example.com','I','linear-gradient(135deg,#D97706,#F59E0B)','5','65%','1')">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <tr data-status="inactive" data-name="tania akter">
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar" style="background:linear-gradient(135deg,#0891B2,#22D3EE);">T</div>
                                    <div>
                                        <div class="student-name">Tania Akter</div>
                                        <div class="student-email">tania@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td>3</td>
                            <td><span class="score-pill score-low">48%</span></td>
                            <td>May 30, 2026</td>
                            <td><span class="activity-dot"><span class="dot dot-inactive"></span> Inactive</span></td>
                            <td>
                                <button class="action-btn" title="View Details"
                                    onclick="openModal('Tania Akter','tania@example.com','T','linear-gradient(135deg,#0891B2,#22D3EE)','3','48%','0')">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <tr data-status="active" data-name="farhan kabir">
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar" style="background:linear-gradient(135deg,#7C3AED,#A78BFA);">F</div>
                                    <div>
                                        <div class="student-name">Farhan Kabir</div>
                                        <div class="student-email">farhan@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td>14</td>
                            <td><span class="score-pill score-high">84%</span></td>
                            <td>Today</td>
                            <td><span class="activity-dot"><span class="dot dot-active"></span> Active</span></td>
                            <td>
                                <button class="action-btn" title="View Details"
                                    onclick="openModal('Farhan Kabir','farhan@example.com','F','linear-gradient(135deg,#7C3AED,#A78BFA)','14','84%','4')">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <tr data-status="inactive" data-name="meherun nessa">
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar" style="background:linear-gradient(135deg,#6B7280,#9CA3AF);">M</div>
                                    <div>
                                        <div class="student-name">Meherun Nessa</div>
                                        <div class="student-email">meherun@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td>2</td>
                            <td><span class="score-pill score-low">52%</span></td>
                            <td>May 10, 2026</td>
                            <td><span class="activity-dot"><span class="dot dot-inactive"></span> Inactive</span></td>
                            <td>
                                <button class="action-btn" title="View Details"
                                    onclick="openModal('Meherun Nessa','meherun@example.com','M','linear-gradient(135deg,#6B7280,#9CA3AF)','2','52%','0')">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <!-- STUDENT DETAIL MODAL -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Student Details</div>
                <button class="modal-close" onclick="closeModal()"><i class="ti ti-x"></i></button>
            </div>

            <div class="modal-student-header">
                <div class="modal-avatar" id="modalAvatar">N</div>
                <div>
                    <div class="modal-student-name" id="modalName">—</div>
                    <div class="modal-student-email" id="modalEmail">—</div>
                </div>
            </div>

            <div class="modal-stats">
                <div class="modal-stat">
                    <div class="modal-stat-value" id="modalQuizzes">—</div>
                    <div class="modal-stat-label">Quizzes Taken</div>
                </div>
                <div class="modal-stat">
                    <div class="modal-stat-value" id="modalAvgScore" style="color:#34D399;">—</div>
                    <div class="modal-stat-label">Avg Score</div>
                </div>
                <div class="modal-stat">
                    <div class="modal-stat-value" id="modalPassed" style="color:#818CF8;">—</div>
                    <div class="modal-stat-label">Quizzes Passed</div>
                </div>
            </div>

            <div class="modal-section-title">Recent Attempts</div>

            <div id="modalAttempts">
                <div class="attempt-row">
                    <div>
                        <div class="attempt-quiz">BCS Preliminary — Bangladesh Affairs</div>
                        <div class="attempt-date">Jun 14, 2026</div>
                    </div>
                    <span class="score-pill score-high">88%</span>
                </div>
                <div class="attempt-row">
                    <div>
                        <div class="attempt-quiz">General Knowledge Quiz</div>
                        <div class="attempt-date">Jun 11, 2026</div>
                    </div>
                    <span class="score-pill score-high">82%</span>
                </div>
                <div class="attempt-row">
                    <div>
                        <div class="attempt-quiz">Data Structures Basics</div>
                        <div class="attempt-date">Jun 8, 2026</div>
                    </div>
                    <span class="score-pill score-mid">70%</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sidebar toggle
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

        // Search
        document.getElementById('searchInput').addEventListener('input', function() {
            const val = this.value.toLowerCase();
            document.querySelectorAll('#studentsBody tr').forEach(row => {
                row.style.display = row.dataset.name.includes(val) ? '' : 'none';
            });
        });

        // Filter
        function filterStudents(status, btn) {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('#studentsBody tr').forEach(row => {
                row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
            });
        }

        // Modal
        function openModal(name, email, initial, bg, quizzes, avg, passed) {
            document.getElementById('modalName').textContent = name;
            document.getElementById('modalEmail').textContent = email;
            document.getElementById('modalAvatar').textContent = initial;
            document.getElementById('modalAvatar').style.background = bg;
            document.getElementById('modalQuizzes').textContent = quizzes;
            document.getElementById('modalAvgScore').textContent = avg;
            document.getElementById('modalPassed').textContent = passed;
            document.getElementById('modalOverlay').classList.add('open');
        }

        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('open');
        }

        document.getElementById('modalOverlay').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>

</body>

</html>