<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('student-layout.css') }}">
    <title>Quizora — Bookmarks</title>
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

        .quiz-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 16px;
        }

        .pquiz-card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 14px;
            overflow: hidden;
            transition: border-color 0.2s, transform 0.2s;
            text-decoration: none;
            display: block;
            position: relative;
        }

        .pquiz-card:hover {
            border-color: rgba(79, 70, 229, 0.4);
            transform: translateY(-3px);
        }

        .unbookmark-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #F59E0B;
            font-size: 15px;
            cursor: pointer;
            z-index: 2;
            transition: background 0.2s;
        }

        .unbookmark-btn:hover {
            background: rgba(0, 0, 0, 0.5);
        }

        .pquiz-banner {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: rgba(255, 255, 255, 0.9);
            position: relative;
        }

        .pquiz-body {
            padding: 16px;
        }

        .pquiz-title {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 6px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .pquiz-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
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
        }

        .diff-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .diff-easy {
            background: rgba(52, 211, 153, 0.15);
            color: var(--color-status-success);
        }

        .diff-medium {
            background: rgba(245, 158, 11, 0.15);
            color: #F59E0B;
        }

        .diff-hard {
            background: rgba(248, 113, 113, 0.15);
            color: var(--color-status-error);
        }

        .pquiz-attempts {
            font-size: 11px;
            color: var(--color-text-muted);
            display: flex;
            align-items: center;
            gap: 4px;
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

        .empty-browse-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--color-primary-solid);
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            padding: 9px 18px;
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.2s;
        }

        .empty-browse-btn:hover {
            background: #4338CA;
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
            <a href="{{ route('student.bookmarks') }}" class="nav-item active">
                <i class="ti ti-bookmark nav-icon"></i>
                <span class="nav-text">Bookmarks</span>
                <span class="nav-badge">3</span>
            </a>
            <div class="nav-label">Activity</div>
            <a href="{{ route('student.results') }}" class="nav-item">
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
            <div class="topbar-title">Bookmarks</div>
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
                <h1>Bookmarks</h1>
                <p>Quizzes you've saved for later</p>
            </div>

            <div class="quiz-grid" id="bookmarksGrid">

                <a href="{{ route('student.quiz.detail') }}" class="pquiz-card">
                    <button class="unbookmark-btn" onclick="removeBookmark(event, this)"><i class="ti ti-bookmark"></i></button>
                    <div class="pquiz-banner" style="background:linear-gradient(135deg,#4F46E5,#818CF8);">
                        <i class="ti ti-building-bank"></i>
                    </div>
                    <div class="pquiz-body">
                        <div class="pquiz-title">BCS Preliminary — Bangladesh Affairs Full Set</div>
                        <div class="pquiz-meta">
                            <div class="pquiz-meta-item"><i class="ti ti-help-circle"></i> 50 Qs</div>
                            <div class="pquiz-meta-item"><i class="ti ti-clock"></i> 60 min</div>
                        </div>
                        <div class="pquiz-footer">
                            <span class="diff-badge diff-hard">Hard</span>
                            <span class="pquiz-attempts"><i class="ti ti-users"></i> 1.2k attempts</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('student.quiz.detail') }}" class="pquiz-card">
                    <button class="unbookmark-btn" onclick="removeBookmark(event, this)"><i class="ti ti-bookmark"></i></button>
                    <div class="pquiz-banner" style="background:linear-gradient(135deg,#059669,#34D399);">
                        <i class="ti ti-flask"></i>
                    </div>
                    <div class="pquiz-body">
                        <div class="pquiz-title">General Science for Class 9-10</div>
                        <div class="pquiz-meta">
                            <div class="pquiz-meta-item"><i class="ti ti-help-circle"></i> 20 Qs</div>
                            <div class="pquiz-meta-item"><i class="ti ti-clock"></i> 25 min</div>
                        </div>
                        <div class="pquiz-footer">
                            <span class="diff-badge diff-medium">Medium</span>
                            <span class="pquiz-attempts"><i class="ti ti-users"></i> 890 attempts</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('student.quiz.detail') }}" class="pquiz-card">
                    <button class="unbookmark-btn" onclick="removeBookmark(event, this)"><i class="ti ti-bookmark"></i></button>
                    <div class="pquiz-banner" style="background:linear-gradient(135deg,#DB2777,#F472B6);">
                        <i class="ti ti-code"></i>
                    </div>
                    <div class="pquiz-body">
                        <div class="pquiz-title">Data Structures & Algorithms Basics</div>
                        <div class="pquiz-meta">
                            <div class="pquiz-meta-item"><i class="ti ti-help-circle"></i> 30 Qs</div>
                            <div class="pquiz-meta-item"><i class="ti ti-clock"></i> 35 min</div>
                        </div>
                        <div class="pquiz-footer">
                            <span class="diff-badge diff-hard">Hard</span>
                            <span class="pquiz-attempts"><i class="ti ti-users"></i> 654 attempts</span>
                        </div>
                    </div>
                </a>

            </div>

            <div class="empty-state" id="emptyState" style="display:none;">
                <i class="ti ti-bookmark-off"></i>
                <h3>No bookmarks yet</h3>
                <p>Save quizzes you want to attempt later</p>
                <a href="{{ route('student.browse') }}" class="empty-browse-btn">
                    <i class="ti ti-compass"></i> Browse Quizzes
                </a>
            </div>

        </div>
    </main>

    <script src="{{ asset('student-layout.js') }}"></script>
    <script>
        function removeBookmark(event, btn) {
            event.preventDefault();
            event.stopPropagation();
            const card = btn.closest('.pquiz-card');
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';
            setTimeout(() => {
                card.remove();
                const remaining = document.querySelectorAll('#bookmarksGrid .pquiz-card').length;
                if (remaining === 0) {
                    document.getElementById('emptyState').style.display = 'block';
                }
            }, 200);
        }
    </script>
</body>

</html>