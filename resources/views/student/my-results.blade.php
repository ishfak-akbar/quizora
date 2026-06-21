<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('student-layout.css') }}">
    <title>Quizora — My Results</title>
    <style>
        .page-header {
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

        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 24px;
        }

        .mini-stat {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 14px;
            padding: 18px;
            text-align: center;
        }

        .mini-stat-value {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
        }

        .mini-stat-label {
            font-size: 11px;
            color: var(--color-text-muted);
            margin-top: 4px;
        }

        .filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-chip {
            font-size: 13px;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 20px;
            border: 1.5px solid var(--color-border-light);
            background: var(--color-bg-card);
            color: var(--color-text-secondary);
            cursor: pointer;
            transition: all 0.2s;
            font-family: var(--font);
        }

        .filter-chip:hover,
        .filter-chip.active {
            background: rgba(79, 70, 229, 0.15);
            border-color: rgba(79, 70, 229, 0.4);
            color: #fff;
        }

        .results-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .result-card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 18px;
            transition: border-color 0.2s, transform 0.2s;
        }

        .result-card:hover {
            border-color: rgba(79, 70, 229, 0.4);
            transform: translateY(-2px);
        }

        .result-score-badge {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 700;
            flex-shrink: 0;
            border: 2px solid;
        }

        .result-card-info {
            flex: 1;
            min-width: 0;
        }

        .result-card-title {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 4px;
        }

        .result-card-meta {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .result-card-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: var(--color-text-muted);
        }

        .result-card-meta-item i {
            font-size: 14px;
        }

        .result-pass-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            flex-shrink: 0;
        }

        .pass-badge {
            background: rgba(52, 211, 153, 0.15);
            color: var(--color-status-success);
        }

        .fail-badge {
            background: rgba(248, 113, 113, 0.15);
            color: var(--color-status-error);
        }

        .result-view-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--color-primary-glow);
            text-decoration: none;
            flex-shrink: 0;
            padding: 8px 14px;
            border-radius: 9px;
            border: 1px solid var(--color-border-light);
            transition: all 0.2s;
        }

        .result-view-btn:hover {
            background: var(--color-bg-row-hover);
            border-color: rgba(79, 70, 229, 0.4);
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
            <a href="{{ route('student.dashboard') }}" class="nav-item">
                <i class="ti ti-layout-dashboard nav-icon"></i>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="{{ route('student.browse') }}" class="nav-item">
                <i class="ti ti-compass nav-icon"></i>
                <span class="nav-text">Browse Quizzes</span>
            </a>
            <a href="{{ route('student.bookmarks') }}" class="nav-item">
                <i class="ti ti-bookmark nav-icon"></i>
                <span class="nav-text">Bookmarks</span>
                <span class="nav-badge">3</span>
            </a>
            <div class="nav-label">Activity</div>
            <a href="{{ route('student.results') }}" class="nav-item active">
                <i class="ti ti-history nav-icon"></i>
                <span class="nav-text">My Results</span>
            </a>
            <a href="{{ route('student.leaderboard.page') }}" class="nav-item">
                <i class="ti ti-trophy nav-icon"></i>
                <span class="nav-text">Leaderboard</span>
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
            <div class="topbar-title">My Results</div>
            <div class="topbar-right">
                <div class="user-btn" id="userBtn">
                    <div class="user-avatar">N</div>
                    <div>
                        <div class="user-name">Nahian</div>
                        <div class="user-role">Student</div>
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
                <h1>My Results</h1>
                <p>Track your performance across all attempted quizzes</p>
            </div>

            <div class="stats-row">
                <div class="mini-stat">
                    <div class="mini-stat-value">12</div>
                    <div class="mini-stat-label">Total Attempts</div>
                </div>
                <div class="mini-stat">
                    <div class="mini-stat-value" style="color:var(--color-status-success)">78%</div>
                    <div class="mini-stat-label">Average Score</div>
                </div>
                <div class="mini-stat">
                    <div class="mini-stat-value" style="color:var(--color-primary-glow)">96%</div>
                    <div class="mini-stat-label">Best Score</div>
                </div>
                <div class="mini-stat">
                    <div class="mini-stat-value" style="color:#F59E0B">9</div>
                    <div class="mini-stat-label">Quizzes Passed</div>
                </div>
            </div>

            <div class="filters">
                <button class="filter-chip active">All</button>
                <button class="filter-chip">Passed</button>
                <button class="filter-chip">Failed</button>
                <button class="filter-chip">This Month</button>
            </div>

            <div class="results-list">

                <div class="result-card">
                    <div class="result-score-badge" style="border-color:#34D399;color:#34D399;">96%</div>
                    <div class="result-card-info">
                        <div class="result-card-title">Data Structures Midterm</div>
                        <div class="result-card-meta">
                            <div class="result-card-meta-item"><i class="ti ti-calendar"></i> Jun 14, 2026</div>
                            <div class="result-card-meta-item"><i class="ti ti-clock"></i> 38 min</div>
                            <div class="result-card-meta-item"><i class="ti ti-help-circle"></i> 48/50 correct</div>
                        </div>
                    </div>
                    <span class="result-pass-badge pass-badge">Passed</span>
                    <a href="{{ route('student.quiz.result') }}" class="result-view-btn">
                        View <i class="ti ti-arrow-right"></i>
                    </a>
                </div>

                <div class="result-card">
                    <div class="result-score-badge" style="border-color:#22D3EE;color:#22D3EE;">82%</div>
                    <div class="result-card-info">
                        <div class="result-card-title">General Knowledge Quiz</div>
                        <div class="result-card-meta">
                            <div class="result-card-meta-item"><i class="ti ti-calendar"></i> Jun 11, 2026</div>
                            <div class="result-card-meta-item"><i class="ti ti-clock"></i> 22 min</div>
                            <div class="result-card-meta-item"><i class="ti ti-help-circle"></i> 41/50 correct</div>
                        </div>
                    </div>
                    <span class="result-pass-badge pass-badge">Passed</span>
                    <a href="{{ route('student.quiz.result') }}" class="result-view-btn">
                        View <i class="ti ti-arrow-right"></i>
                    </a>
                </div>

                <div class="result-card">
                    <div class="result-score-badge" style="border-color:#F59E0B;color:#F59E0B;">65%</div>
                    <div class="result-card-info">
                        <div class="result-card-title">English Grammar Test</div>
                        <div class="result-card-meta">
                            <div class="result-card-meta-item"><i class="ti ti-calendar"></i> Jun 8, 2026</div>
                            <div class="result-card-meta-item"><i class="ti ti-clock"></i> 18 min</div>
                            <div class="result-card-meta-item"><i class="ti ti-help-circle"></i> 26/40 correct</div>
                        </div>
                    </div>
                    <span class="result-pass-badge pass-badge">Passed</span>
                    <a href="{{ route('student.quiz.result') }}" class="result-view-btn">
                        View <i class="ti ti-arrow-right"></i>
                    </a>
                </div>

                <div class="result-card">
                    <div class="result-score-badge" style="border-color:#F87171;color:#F87171;">45%</div>
                    <div class="result-card-info">
                        <div class="result-card-title">Advanced Calculus Challenge</div>
                        <div class="result-card-meta">
                            <div class="result-card-meta-item"><i class="ti ti-calendar"></i> Jun 3, 2026</div>
                            <div class="result-card-meta-item"><i class="ti ti-clock"></i> 35 min</div>
                            <div class="result-card-meta-item"><i class="ti ti-help-circle"></i> 11/25 correct</div>
                        </div>
                    </div>
                    <span class="result-pass-badge fail-badge">Failed</span>
                    <a href="{{ route('student.quiz.result') }}" class="result-view-btn">
                        View <i class="ti ti-arrow-right"></i>
                    </a>
                </div>

                <div class="result-card">
                    <div class="result-score-badge" style="border-color:#34D399;color:#34D399;">88%</div>
                    <div class="result-card-info">
                        <div class="result-card-title">BCS Preliminary — Bangladesh Affairs</div>
                        <div class="result-card-meta">
                            <div class="result-card-meta-item"><i class="ti ti-calendar"></i> May 28, 2026</div>
                            <div class="result-card-meta-item"><i class="ti ti-clock"></i> 52 min</div>
                            <div class="result-card-meta-item"><i class="ti ti-help-circle"></i> 44/50 correct</div>
                        </div>
                    </div>
                    <span class="result-pass-badge pass-badge">Passed</span>
                    <a href="{{ route('student.quiz.result') }}" class="result-view-btn">
                        View <i class="ti ti-arrow-right"></i>
                    </a>
                </div>

            </div>

        </div>
    </main>

    <script src="{{ asset('student-layout.js') }}"></script>
    <script>
        document.querySelectorAll('.filter-chip').forEach(chip => {
            chip.addEventListener('click', function() {
                document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>

</html>