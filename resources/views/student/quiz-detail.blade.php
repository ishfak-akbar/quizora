<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('student-layout.css') }}">
    <title>Quizora — Quiz Details</title>
    <style>
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--color-text-muted);
            font-size: 13px;
            text-decoration: none;
            margin-bottom: 16px;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #fff;
        }

        .detail-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
            align-items: start;
        }

        .detail-banner {
            height: 160px;
            border-radius: 16px;
            background: linear-gradient(135deg, #4F46E5, #818CF8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 56px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .detail-banner::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }

        .detail-category {
            font-size: 12px;
            font-weight: 600;
            color: var(--color-primary-glow);
            background: rgba(79, 70, 229, 0.15);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 12px;
        }

        .detail-title {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .detail-desc {
            font-size: 14px;
            color: var(--color-text-secondary);
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .detail-author {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 0;
            border-top: 1px solid var(--color-border-light);
            border-bottom: 1px solid var(--color-border-light);
            margin-bottom: 20px;
        }

        .author-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-primary-solid), var(--color-stat-purple));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
        }

        .author-info p:first-child {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
        }

        .author-info p:last-child {
            font-size: 11px;
            color: var(--color-text-muted);
            margin-top: 1px;
        }

        .detail-section {
            margin-bottom: 24px;
        }

        .detail-section h3 {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 12px;
        }

        .topic-tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .topic-tag {
            font-size: 12px;
            color: var(--color-text-secondary);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--color-border-light);
            padding: 6px 14px;
            border-radius: 20px;
        }

        .instructions-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .instruction-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13px;
            color: var(--color-text-secondary);
            line-height: 1.6;
        }

        .instruction-item i {
            color: var(--color-status-success);
            font-size: 16px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        /* SIDEBAR CARD */
        .start-card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 16px;
            padding: 24px;
            position: sticky;
            top: 84px;
        }

        .start-stats {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 20px;
        }

        .start-stat-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
        }

        .start-stat-row .label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--color-text-muted);
        }

        .start-stat-row .label i {
            font-size: 16px;
            color: var(--color-primary-glow);
        }

        .start-stat-row .value {
            color: #fff;
            font-weight: 600;
        }

        .diff-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
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

        .start-divider {
            height: 1px;
            background: var(--color-border-light);
            margin: 16px 0;
        }

        .start-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--color-primary-solid);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            padding: 14px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            font-family: var(--font);
            text-decoration: none;
            transition: background 0.2s, transform 0.15s;
            margin-bottom: 10px;
        }

        .start-btn:hover {
            background: #4338CA;
            transform: translateY(-1px);
        }

        .bookmark-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: transparent;
            color: var(--color-text-secondary);
            font-size: 13px;
            font-weight: 600;
            padding: 11px;
            border-radius: 10px;
            border: 1px solid var(--color-border-light);
            cursor: pointer;
            font-family: var(--font);
            transition: all 0.2s;
        }

        .bookmark-btn:hover {
            border-color: rgba(79, 70, 229, 0.4);
            color: #fff;
        }

        .bookmark-btn.saved {
            color: #F59E0B;
            border-color: rgba(245, 158, 11, 0.3);
            background: rgba(245, 158, 11, 0.08);
        }

        .attempts-note {
            font-size: 11px;
            color: var(--color-text-muted);
            text-align: center;
            margin-top: 12px;
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
            <a href="{{ route('student.browse') }}" class="nav-item active">
                <i class="ti ti-compass nav-icon"></i>
                <span class="nav-text">Browse Quizzes</span>
            </a>
            <a href="#" class="nav-item">
                <i class="ti ti-bookmark nav-icon"></i>
                <span class="nav-text">Bookmarks</span>
                <span class="nav-badge">3</span>
            </a>
            <div class="nav-label">Activity</div>
            <a href="#" class="nav-item">
                <i class="ti ti-history nav-icon"></i>
                <span class="nav-text">My Results</span>
            </a>
            <a href="#" class="nav-item">
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
            <div class="topbar-title">Quiz Details</div>
            <div class="topbar-right">
                <button class="notif-btn">
                    <i class="ti ti-bell"></i>
                    <span class="notif-dot"></span>
                </button>
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

            <a href="{{ route('student.browse') }}" class="back-link">
                <i class="ti ti-arrow-left"></i> Back to Browse
            </a>

            <div class="detail-layout">

                <!-- MAIN INFO -->
                <div>
                    <div class="detail-banner">
                        <i class="ti ti-building-bank"></i>
                    </div>

                    <span class="detail-category">BCS Cadre</span>
                    <h1 class="detail-title">BCS Preliminary — Bangladesh Affairs Full Set</h1>
                    <p class="detail-desc">
                        A comprehensive practice set covering Bangladesh's constitution, history, geography,
                        and current affairs — designed to mirror the actual BCS Preliminary exam pattern.
                        Perfect for serious BCS aspirants looking to test their preparation level.
                    </p>

                    <div class="detail-author">
                        <div class="author-avatar">RH</div>
                        <div class="author-info">
                            <p>Rashed Hasan</p>
                            <p>Quiz Creator · 24 quizzes published</p>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Topics Covered</h3>
                        <div class="topic-tags">
                            <span class="topic-tag">Constitution</span>
                            <span class="topic-tag">Liberation War</span>
                            <span class="topic-tag">Geography</span>
                            <span class="topic-tag">Current Affairs</span>
                            <span class="topic-tag">Government Structure</span>
                            <span class="topic-tag">Economy</span>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Before You Start</h3>
                        <div class="instructions-list">
                            <div class="instruction-item">
                                <i class="ti ti-circle-check"></i>
                                This quiz contains 50 multiple choice questions
                            </div>
                            <div class="instruction-item">
                                <i class="ti ti-circle-check"></i>
                                You have 60 minutes to complete it — the timer starts once you begin
                            </div>
                            <div class="instruction-item">
                                <i class="ti ti-circle-check"></i>
                                The quiz will auto-submit when time runs out
                            </div>
                            <div class="instruction-item">
                                <i class="ti ti-circle-check"></i>
                                You can attempt this quiz up to 2 times
                            </div>
                            <div class="instruction-item">
                                <i class="ti ti-circle-check"></i>
                                Your results and correct answers will be shown immediately after submission
                            </div>
                        </div>
                    </div>
                </div>

                <!-- START SIDEBAR -->
                <div class="start-card">
                    <div class="start-stats">
                        <div class="start-stat-row">
                            <div class="label"><i class="ti ti-help-circle"></i> Questions</div>
                            <div class="value">50</div>
                        </div>
                        <div class="start-stat-row">
                            <div class="label"><i class="ti ti-clock"></i> Time Limit</div>
                            <div class="value">60 min</div>
                        </div>
                        <div class="start-stat-row">
                            <div class="label"><i class="ti ti-refresh"></i> Attempts Left</div>
                            <div class="value">2 of 2</div>
                        </div>
                        <div class="start-stat-row">
                            <div class="label"><i class="ti ti-gauge"></i> Difficulty</div>
                            <span class="diff-badge diff-hard">Hard</span>
                        </div>
                        <div class="start-stat-row">
                            <div class="label"><i class="ti ti-users"></i> Attempted by</div>
                            <div class="value">1.2k students</div>
                        </div>
                        <div class="start-stat-row">
                            <div class="label"><i class="ti ti-chart-bar"></i> Avg Score</div>
                            <div class="value" style="color:var(--color-status-success)">68%</div>
                        </div>
                    </div>

                    <div class="start-divider"></div>

                    <a href="{{ route('student.quiz.take') }}" class="start-btn">
                        <i class="ti ti-player-play"></i> Start Quiz
                    </a>
                    <button class="bookmark-btn" id="bookmarkBtn" onclick="toggleBookmark(this)">
                        <i class="ti ti-bookmark"></i> Save for Later
                    </button>

                    <div class="attempts-note">No time pressure — start whenever you're ready</div>
                </div>

            </div>
        </div>
    </main>

    <script src="{{ asset('student-layout.js') }}"></script>
    <script>
        function toggleBookmark(btn) {
            btn.classList.toggle('saved');
            if (btn.classList.contains('saved')) {
                btn.innerHTML = '<i class="ti ti-bookmark-filled"></i> Saved';
            } else {
                btn.innerHTML = '<i class="ti ti-bookmark"></i> Save for Later';
            }
        }
    </script>
</body>

</html>