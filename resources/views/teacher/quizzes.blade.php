<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('quizora.css') }}">
    <title>Quizora — My Quizzes</title>
    <style>
        :root {
            --color-primary-glow: #818CF8;
            --color-primary-solid: #4F46E5;
            --color-primary-dark: #2E2570;
            --color-bg-main: #0E0B20;
            --color-bg-card: #1E1A3E;
            --color-bg-row-hover: #241E47;
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

        /* PAGE HEADER */
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
            max-width: 550px;
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
            color: #fff;
        }

        .filter-btn.active {
            color: var(--color-primary-glow);
            font-weight: 600;
        }

        .create-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: var(--color-primary-solid);
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.2px;
            padding: 10px 20px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            cursor: pointer;
            font-family: var(--font);
            text-decoration: none;
            white-space: nowrap;
            box-sizing: border-box;

            transition: background-color 0.2s cubic-bezier(0.4, 0, 0.2, 1),
                box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1),
                transform 0.1s ease;

            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25),
                0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .create-btn i {
            font-size: 15px;
            display: inline-block;
            line-height: 1;
            flex-shrink: 0;
            /* Prevents layout engines from smashing the icon width down to zero */
        }

        .create-btn:hover {
            background: #4338CA;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35),
                0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .create-btn:active {
            transform: translateY(1px);
            box-shadow: 0 2px 6px rgba(79, 70, 229, 0.2),
                0 1px 1px rgba(0, 0, 0, 0.2);
        }

        .empty-state .create-btn {
            display: inline-flex !important;
            margin-top: 12px;
        }

        .btn-plus-icon {
            font-size: 18px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 0;
        }

        /* QUIZ GRID */
        .quiz-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 16px;
        }

        .quiz-card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 14px;
            overflow: hidden;
            transition: border-color 0.2s, transform 0.2s;
            display: flex;
            flex-direction: column;
        }

        .quiz-card:hover {
            border-color: rgba(79, 70, 229, 0.4);
            transform: translateY(-2px);
        }

        .quiz-card-top {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--color-border-light);
            flex: 1;
        }

        .quiz-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .quiz-card-title {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            line-height: 1.4;
        }

        .quiz-card-desc {
            font-size: 12px;
            color: var(--color-text-muted);
            margin-top: 4px;
            line-height: 1.5;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            flex-shrink: 0;
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

        .quiz-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 14px;
        }

        .quiz-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            color: var(--color-text-muted);
        }

        .quiz-meta-item i {
            font-size: 14px;
            color: var(--color-primary-glow);
        }

        .quiz-card-footer {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .quiz-progress {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
        }

        .progress-bar {
            flex: 1;
            height: 4px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--color-primary-solid);
            border-radius: 2px;
        }

        .progress-text {
            font-size: 11px;
            color: var(--color-text-muted);
            white-space: nowrap;
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
            text-decoration: none;
            transition: background 0.15s, color 0.15s, border-color 0.15s;
        }

        .action-btn:hover {
            background: var(--color-bg-row-hover);
            color: #fff;
            border-color: rgba(79, 70, 229, 0.4);
        }

        .action-btn.danger:hover {
            background: rgba(248, 113, 113, 0.15);
            color: var(--color-status-error);
            border-color: rgba(248, 113, 113, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--color-text-muted);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            color: rgba(79, 70, 229, 0.3);
            display: block;
        }

        .empty-state h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--color-text-secondary);
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 13px;
            margin-bottom: 20px;
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
            <a href="{{ route('teacher.quizzes') }}" class="nav-item active">
                <i class="ti ti-file-description nav-icon"></i>
                <span class="nav-text">My Quizzes</span>
                <span class="nav-badge">{{ auth()->user()->quizzes()->count() }}</span>
            </a>
            <a href="{{ route('teacher.quiz.create') }}" class="nav-item">
                <i class="ti ti-circle-plus nav-icon"></i>
                <span class="nav-text">Create Quiz</span>
            </a>
            <div class="nav-label">Analytics</div>
            <a href="#" class="nav-item">
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
            <div class="topbar-title">My Quizzes</div>
            <div class="topbar-right">
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

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h1>My Quizzes</h1>
                    <p>Manage and track all your quizzes</p>
                </div>
                <a href="{{ route('teacher.quiz.create') }}" class="create-btn">
                    <i class="ti ti-plus"></i> Create Quiz
                </a>
            </div>

            <!-- FILTERS -->
            <div class="filters">
                <div class="search-wrap">
                    <i class="ti ti-search"></i>
                    <input type="text" id="searchInput" placeholder="Search quizzes..." />
                </div>
                <button class="filter-btn active" onclick="filterQuizzes('all', this)">All</button>
                <button class="filter-btn" onclick="filterQuizzes('active', this)">Active</button>
                <button class="filter-btn" onclick="filterQuizzes('draft', this)">Draft</button>
                <button class="filter-btn" onclick="filterQuizzes('closed', this)">Closed</button>
            </div>

            <!-- QUIZ GRID -->
            <div class="quiz-grid" id="quizGrid">
                @forelse($quizzes as $quiz)
                <div class="quiz-card" data-status="{{ $quiz->status }}" data-title="{{ strtolower($quiz->title) }}">
                    <div class="quiz-card-top">
                        <div class="quiz-card-header">
                            <div>
                                <div class="quiz-card-title">{{ $quiz->title }}</div>
                                @if($quiz->description)
                                <div class="quiz-card-desc">{{ Str::limit($quiz->description, 60) }}</div>
                                @endif
                            </div>
                            <span class="status-badge {{ $quiz->status }}">
                                <span class="status-dot"></span>
                                {{ ucfirst($quiz->status) }}
                            </span>
                        </div>

                        <div class="quiz-meta">
                            <div class="quiz-meta-item">
                                <i class="ti ti-help-circle"></i>
                                {{ $quiz->questions_count }} questions
                            </div>
                            @if($quiz->time_limit)
                            <div class="quiz-meta-item">
                                <i class="ti ti-clock"></i>
                                {{ $quiz->time_limit }} min
                            </div>
                            @endif
                            @if($quiz->ends_at)
                            <div class="quiz-meta-item">
                                <i class="ti ti-calendar"></i>
                                {{ $quiz->ends_at->format('M d, Y') }}
                            </div>
                            @endif
                            <div class="quiz-meta-item">
                                <i class="ti ti-refresh"></i>
                                {{ $quiz->max_attempts }} attempt(s)
                            </div>
                        </div>
                    </div>

                    <div class="quiz-card-footer">
                        <div class="quiz-progress">
                            @php
                            $total = $quiz->attempts_count ?? 0;
                            $submitted = $quiz->submitted_count ?? 0;
                            $percent = $total > 0 ? round(($submitted / $total) * 100) : 0;
                            @endphp
                            <div class="progress-bar">
                                <div class="progress-fill" style="width:{{ $percent }}%"></div>
                            </div>
                            <div class="progress-text">{{ $submitted }} submitted</div>
                        </div>
                        <div class="action-btns" style="margin-left:12px;">
                            <a href="{{ route('teacher.quiz.edit', $quiz->id) }}" class="action-btn" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <a href="#" class="action-btn" title="Results">
                                <i class="ti ti-chart-bar"></i>
                            </a>
                            <form method="POST" action="{{ route('teacher.quiz.destroy', $quiz->id) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn danger" title="Delete"
                                    onclick="return confirm('Delete this quiz?')">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state" style="grid-column:1/-1;">
                    <i class="ti ti-file-off"></i>
                    <h3>No quizzes yet</h3>
                    <p>Create your first quiz to get started</p>
                    <a href="{{ route('teacher.quiz.create') }}" class="create-btn">
                        <span class="btn-plus-icon">+</span> Create Quiz
                    </a>
                </div>
                @endforelse
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

        // Search
        document.getElementById('searchInput').addEventListener('input', function() {
            const val = this.value.toLowerCase();
            document.querySelectorAll('.quiz-card').forEach(card => {
                const title = card.dataset.title;
                card.style.display = title.includes(val) ? 'flex' : 'none';
            });
        });

        // Filter
        let currentFilter = 'all';

        function filterQuizzes(status, btn) {
            currentFilter = status;
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.quiz-card').forEach(card => {
                const match = status === 'all' || card.dataset.status === status;
                card.style.display = match ? 'flex' : 'none';
            });
        }
    </script>
</body>

</html>