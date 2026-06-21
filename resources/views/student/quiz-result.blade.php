<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('student-layout.css') }}">
    <title>Quizora — Quiz Result</title>
    <style>
        .result-hero {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }

        .result-hero::before {
            content: '';
            position: absolute;
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.15) 0%, transparent 70%);
        }

        .result-badge {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 20px;
            margin-bottom: 20px;
        }

        .result-badge.pass {
            background: rgba(52, 211, 153, 0.15);
            color: var(--color-status-success);
        }

        .result-badge.fail {
            background: rgba(248, 113, 113, 0.15);
            color: var(--color-status-error);
        }

        .score-ring-wrap {
            position: relative;
            z-index: 1;
            margin-bottom: 20px;
        }

        .score-circle {
            width: 160px;
            height: 160px;
            margin: 0 auto;
            position: relative;
        }

        .score-circle svg {
            transform: rotate(-90deg);
        }

        .score-circle-bg {
            fill: none;
            stroke: rgba(255, 255, 255, 0.06);
            stroke-width: 10;
        }

        .score-circle-fill {
            fill: none;
            stroke: var(--color-status-success);
            stroke-width: 10;
            stroke-linecap: round;
            transition: stroke-dashoffset 1s ease;
        }

        .score-circle-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .score-circle-pct {
            font-size: 36px;
            font-weight: 700;
            color: #fff;
            line-height: 1;
        }

        .score-circle-label {
            font-size: 11px;
            color: var(--color-text-muted);
            margin-top: 4px;
        }

        .result-title {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
        }

        .result-subtitle {
            font-size: 13px;
            color: var(--color-text-muted);
            position: relative;
            z-index: 1;
        }

        /* STATS ROW */
        .result-stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .result-mini-stat {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 14px;
            padding: 18px;
            text-align: center;
        }

        .result-mini-stat i {
            font-size: 22px;
            color: var(--color-primary-glow);
            margin-bottom: 8px;
            display: block;
        }

        .result-mini-stat-value {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
        }

        .result-mini-stat-label {
            font-size: 11px;
            color: var(--color-text-muted);
            margin-top: 3px;
        }

        /* ACTION BUTTONS */
        .result-actions {
            display: flex;
            gap: 12px;
            margin-bottom: 28px;
        }

        .result-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            font-family: var(--font);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .result-btn-primary {
            background: var(--color-primary-solid);
            color: #fff;
            border: none;
        }

        .result-btn-primary:hover {
            background: #4338CA;
        }

        .result-btn-secondary {
            background: transparent;
            color: var(--color-text-secondary);
            border: 1px solid var(--color-border-light);
        }

        .result-btn-secondary:hover {
            background: var(--color-bg-row-hover);
            color: #fff;
        }

        /* REVIEW SECTION */
        .review-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .review-header h2 {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
        }

        .review-filter {
            display: flex;
            gap: 8px;
        }

        .review-filter-btn {
            font-size: 12px;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 20px;
            border: 1px solid var(--color-border-light);
            background: transparent;
            color: var(--color-text-secondary);
            cursor: pointer;
            font-family: var(--font);
            transition: all 0.2s;
        }

        .review-filter-btn:hover,
        .review-filter-btn.active {
            background: rgba(79, 70, 229, 0.15);
            border-color: rgba(79, 70, 229, 0.4);
            color: #fff;
        }

        .review-card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 12px;
        }

        .review-q-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .review-q-text {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            line-height: 1.5;
        }

        .review-status-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .review-status-icon.correct {
            background: rgba(52, 211, 153, 0.15);
            color: var(--color-status-success);
        }

        .review-status-icon.incorrect {
            background: rgba(248, 113, 113, 0.15);
            color: var(--color-status-error);
        }

        .review-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .review-opt {
            font-size: 12px;
            padding: 9px 12px;
            border-radius: 8px;
            color: var(--color-text-secondary);
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--color-border-light);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .review-opt.correct-answer {
            background: rgba(52, 211, 153, 0.1);
            border-color: rgba(52, 211, 153, 0.4);
            color: var(--color-status-success);
        }

        .review-opt.wrong-selected {
            background: rgba(248, 113, 113, 0.1);
            border-color: rgba(248, 113, 113, 0.4);
            color: var(--color-status-error);
        }

        .review-opt i {
            font-size: 14px;
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
            <div class="topbar-title">Quiz Result</div>
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

        <div class="content" style="max-width:760px;margin:0 auto;">

            <!-- RESULT HERO -->
            <div class="result-hero">
                <span class="result-badge pass">
                    <i class="ti ti-circle-check"></i> Passed
                </span>

                <div class="score-ring-wrap">
                    <div class="score-circle">
                        <svg width="160" height="160" viewBox="0 0 160 160">
                            <circle class="score-circle-bg" cx="80" cy="80" r="70"></circle>
                            <circle class="score-circle-fill" cx="80" cy="80" r="70"
                                stroke-dasharray="439.8" stroke-dashoffset="105.5"></circle>
                        </svg>
                        <div class="score-circle-text">
                            <div class="score-circle-pct">76%</div>
                            <div class="score-circle-label">38 / 50 correct</div>
                        </div>
                    </div>
                </div>

                <div class="result-title">Great job, Nahian!</div>
                <div class="result-subtitle">BCS Preliminary — Bangladesh Affairs Full Set</div>
            </div>

            <!-- STATS -->
            <div class="result-stats-row">
                <div class="result-mini-stat">
                    <i class="ti ti-check"></i>
                    <div class="result-mini-stat-value" style="color:var(--color-status-success)">38</div>
                    <div class="result-mini-stat-label">Correct</div>
                </div>
                <div class="result-mini-stat">
                    <i class="ti ti-x"></i>
                    <div class="result-mini-stat-value" style="color:var(--color-status-error)">12</div>
                    <div class="result-mini-stat-label">Incorrect</div>
                </div>
                <div class="result-mini-stat">
                    <i class="ti ti-clock"></i>
                    <div class="result-mini-stat-value">42:18</div>
                    <div class="result-mini-stat-label">Time Taken</div>
                </div>
                <div class="result-mini-stat">
                    <i class="ti ti-trophy"></i>
                    <div class="result-mini-stat-value">#47</div>
                    <div class="result-mini-stat-label">Your Rank</div>
                </div>
            </div>

            <!-- ACTIONS -->
            <div class="result-actions">
                <a href="{{ route('student.browse') }}" class="result-btn result-btn-secondary">
                    <i class="ti ti-compass"></i> Browse More Quizzes
                </a>
                <a href="#" class="result-btn result-btn-primary">
                    <i class="ti ti-trophy"></i> View Leaderboard
                </a>
            </div>

            <!-- ANSWER REVIEW -->
            <div class="review-header">
                <h2>Answer Review</h2>
                <div class="review-filter">
                    <button class="review-filter-btn active">All</button>
                    <button class="review-filter-btn">Correct</button>
                    <button class="review-filter-btn">Incorrect</button>
                </div>
            </div>

            <div class="review-card">
                <div class="review-q-header">
                    <div class="review-q-text">1. Which article of the Constitution of Bangladesh declares the country as a unitary state?</div>
                    <div class="review-status-icon correct"><i class="ti ti-check"></i></div>
                </div>
                <div class="review-options">
                    <div class="review-opt">A. Article 1</div>
                    <div class="review-opt correct-answer"><i class="ti ti-check"></i> B. Article 2</div>
                    <div class="review-opt">C. Article 3</div>
                    <div class="review-opt">D. Article 4</div>
                </div>
            </div>

            <div class="review-card">
                <div class="review-q-header">
                    <div class="review-q-text">2. In which year did Bangladesh gain independence?</div>
                    <div class="review-status-icon incorrect"><i class="ti ti-x"></i></div>
                </div>
                <div class="review-options">
                    <div class="review-opt wrong-selected"><i class="ti ti-x"></i> A. 1970</div>
                    <div class="review-opt correct-answer"><i class="ti ti-check"></i> B. 1971</div>
                    <div class="review-opt">C. 1972</div>
                    <div class="review-opt">D. 1969</div>
                </div>
            </div>

            <div class="review-card">
                <div class="review-q-header">
                    <div class="review-q-text">3. Who is regarded as the Father of the Nation of Bangladesh?</div>
                    <div class="review-status-icon correct"><i class="ti ti-check"></i></div>
                </div>
                <div class="review-options">
                    <div class="review-opt">A. Ziaur Rahman</div>
                    <div class="review-opt correct-answer"><i class="ti ti-check"></i> B. Sheikh Mujibur Rahman</div>
                    <div class="review-opt">C. Tajuddin Ahmad</div>
                    <div class="review-opt">D. A. K. Fazlul Huq</div>
                </div>
            </div>

        </div>
    </main>

    <script src="{{ asset('student-layout.js') }}"></script>
    <script>
        document.querySelectorAll('.review-filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.review-filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>

</html>