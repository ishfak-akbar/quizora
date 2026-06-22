<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('student-layout.css') }}">
    <title>Quizora — Leaderboard</title>
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

        .lb-layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 20px;
            align-items: start;
        }

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

        /* MY RANK CARD */
        .my-rank-card {
            background: linear-gradient(135deg, #2E2570 0%, #4F46E5 100%);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .my-rank-number {
            font-size: 32px;
            font-weight: 800;
            color: #fff;
            min-width: 50px;
            text-align: center;
        }

        .my-rank-info {
            flex: 1;
        }

        .my-rank-info p:first-child {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
        }

        .my-rank-info p:last-child {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            margin-top: 2px;
        }

        .my-rank-score {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
        }

        /* PODIUM */
        .podium {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 16px;
            margin-bottom: 24px;
            padding: 24px;
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 16px;
        }

        .podium-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            flex: 1;
            max-width: 140px;
        }

        .podium-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            position: relative;
        }

        .podium-medal {
            position: absolute;
            top: -8px;
            right: -4px;
            font-size: 18px;
            line-height: 1;
        }

        .podium-name {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            text-align: center;
        }

        .podium-score {
            font-size: 13px;
            font-weight: 700;
        }

        .podium-block {
            width: 100%;
            border-radius: 8px 8px 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            color: rgba(255, 255, 255, 0.6);
        }

        /* TABLE */
        .lb-table {
            width: 100%;
            border-collapse: collapse;
        }

        .lb-table th {
            padding: 10px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: var(--color-text-muted);
            letter-spacing: 0.8px;
            text-transform: uppercase;
            border-bottom: 1px solid var(--color-border-light);
        }

        .lb-table td {
            padding: 12px 16px;
            font-size: 13px;
            color: var(--color-text-secondary);
            border-bottom: 1px solid var(--color-border-light);
            vertical-align: middle;
        }

        .lb-table tr:last-child td {
            border-bottom: none;
        }

        .lb-table tr:hover td {
            background: var(--color-bg-row-hover);
        }

        .lb-table tr.is-me td {
            background: rgba(79, 70, 229, 0.1);
        }

        .lb-table tr.is-me .lb-name-cell {
            color: var(--color-primary-glow);
            font-weight: 700;
        }

        .lb-rank-cell {
            font-size: 13px;
            font-weight: 700;
            width: 40px;
        }

        .lb-avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
        }

        .you-tag {
            font-size: 10px;
            font-weight: 700;
            color: var(--color-primary-glow);
            background: rgba(79, 70, 229, 0.2);
            padding: 2px 8px;
            border-radius: 20px;
            margin-left: 8px;
        }

        .lb-bar-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .lb-bar {
            flex: 1;
            height: 6px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 3px;
            overflow: hidden;
        }

        .lb-bar-fill {
            height: 100%;
            border-radius: 3px;
            background: var(--color-primary-solid);
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
            <a href="{{ route('student.leaderboard.page') }}" class="nav-item active">
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
            <div class="topbar-title">Leaderboard</div>
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
                <h1>Leaderboard</h1>
                <p>See how you rank against other students</p>
            </div>

            <div class="lb-layout">

                <!-- QUIZ LIST -->
                <div>
                    <div style="font-size:12px;font-weight:600;color:var(--color-text-muted);letter-spacing:0.8px;text-transform:uppercase;margin-bottom:12px;">
                        Select Quiz
                    </div>
                    <div class="quiz-list">
                        <div class="quiz-list-item active">
                            <div class="quiz-list-icon"><i class="ti ti-building-bank"></i></div>
                            <div style="flex:1;overflow:hidden;">
                                <div class="quiz-list-name">BCS Preliminary — Bangladesh Affairs</div>
                                <div class="quiz-list-meta">1.2k attempts</div>
                            </div>
                        </div>
                        <div class="quiz-list-item">
                            <div class="quiz-list-icon"><i class="ti ti-flask"></i></div>
                            <div style="flex:1;overflow:hidden;">
                                <div class="quiz-list-name">General Science Basics</div>
                                <div class="quiz-list-meta">890 attempts</div>
                            </div>
                        </div>
                        <div class="quiz-list-item">
                            <div class="quiz-list-icon"><i class="ti ti-calculator"></i></div>
                            <div style="flex:1;overflow:hidden;">
                                <div class="quiz-list-name">Algebra Fundamentals</div>
                                <div class="quiz-list-meta">2.1k attempts</div>
                            </div>
                        </div>
                        <div class="quiz-list-item">
                            <div class="quiz-list-icon"><i class="ti ti-code"></i></div>
                            <div style="flex:1;overflow:hidden;">
                                <div class="quiz-list-name">Data Structures Midterm</div>
                                <div class="quiz-list-meta">654 attempts</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LEADERBOARD PANEL -->
                <div>

                    <!-- MY RANK -->
                    <div class="my-rank-card">
                        <div class="my-rank-number">#47</div>
                        <div class="my-rank-info">
                            <p>Your current rank</p>
                            <p>Out of 1,247 students</p>
                        </div>
                        <div class="my-rank-score">88%</div>
                    </div>

                    <!-- PODIUM -->
                    <div class="podium">
                        <div class="podium-item">
                            <div class="podium-avatar" style="background:#7C3AED;">
                                SI
                                <span class="podium-medal">🥈</span>
                            </div>
                            <div class="podium-name">Sadia Islam</div>
                            <div class="podium-score" style="color:#9CA3AF">97%</div>
                            <div class="podium-block" style="height:80px;background:#7C3AED22;border:1px solid #7C3AED44;">2</div>
                        </div>
                        <div class="podium-item">
                            <div class="podium-avatar" style="background:#4F46E5;">
                                IR
                                <span class="podium-medal">🥇</span>
                            </div>
                            <div class="podium-name">Ishfak Rahman</div>
                            <div class="podium-score" style="color:#F59E0B">99%</div>
                            <div class="podium-block" style="height:110px;background:#4F46E522;border:1px solid #4F46E544;">1</div>
                        </div>
                        <div class="podium-item">
                            <div class="podium-avatar" style="background:#0891B2;">
                                TA
                                <span class="podium-medal">🥉</span>
                            </div>
                            <div class="podium-name">Tanvir Ahmed</div>
                            <div class="podium-score" style="color:#D97706">95%</div>
                            <div class="podium-block" style="height:60px;background:#0891B222;border:1px solid #0891B244;">3</div>
                        </div>
                    </div>

                    <!-- FULL TABLE -->
                    <div class="card">
                        <div class="card-header">
                            <h2>Full Rankings</h2>
                            <span style="font-size:12px;color:var(--color-text-muted);">1,247 students</span>
                        </div>
                        <table class="lb-table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Student</th>
                                    <th>Score</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="lb-rank-cell"><span style="font-size:18px;">🥇</span></td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:10px;">
                                            <div class="lb-avatar-sm" style="background:#4F46E5">IR</div>
                                            <div style="font-weight:600;color:#fff;">Ishfak Rahman</div>
                                        </div>
                                    </td>
                                    <td style="font-weight:700;color:#fff;">99 / 100</td>
                                    <td>
                                        <div class="lb-bar-wrap">
                                            <div class="lb-bar">
                                                <div class="lb-bar-fill" style="width:99%;background:#4F46E5"></div>
                                            </div>
                                            <span style="font-size:13px;font-weight:700;color:#4F46E5;min-width:40px;text-align:right;">99%</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="lb-rank-cell"><span style="font-size:18px;">🥈</span></td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:10px;">
                                            <div class="lb-avatar-sm" style="background:#7C3AED">SI</div>
                                            <div style="font-weight:600;color:#fff;">Sadia Islam</div>
                                        </div>
                                    </td>
                                    <td style="font-weight:700;color:#fff;">97 / 100</td>
                                    <td>
                                        <div class="lb-bar-wrap">
                                            <div class="lb-bar">
                                                <div class="lb-bar-fill" style="width:97%;background:#7C3AED"></div>
                                            </div>
                                            <span style="font-size:13px;font-weight:700;color:#7C3AED;min-width:40px;text-align:right;">97%</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="lb-rank-cell"><span style="font-size:18px;">🥉</span></td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:10px;">
                                            <div class="lb-avatar-sm" style="background:#0891B2">TA</div>
                                            <div style="font-weight:600;color:#fff;">Tanvir Ahmed</div>
                                        </div>
                                    </td>
                                    <td style="font-weight:700;color:#fff;">95 / 100</td>
                                    <td>
                                        <div class="lb-bar-wrap">
                                            <div class="lb-bar">
                                                <div class="lb-bar-fill" style="width:95%;background:#0891B2"></div>
                                            </div>
                                            <span style="font-size:13px;font-weight:700;color:#0891B2;min-width:40px;text-align:right;">95%</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="lb-rank-cell" style="color:var(--color-text-muted);">4</td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:10px;">
                                            <div class="lb-avatar-sm" style="background:#059669">NJ</div>
                                            <div style="font-weight:600;color:#fff;">Nusrat Jahan</div>
                                        </div>
                                    </td>
                                    <td style="font-weight:700;color:#fff;">93 / 100</td>
                                    <td>
                                        <div class="lb-bar-wrap">
                                            <div class="lb-bar">
                                                <div class="lb-bar-fill" style="width:93%;background:#059669"></div>
                                            </div>
                                            <span style="font-size:13px;font-weight:700;color:#059669;min-width:40px;text-align:right;">93%</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="is-me">
                                    <td class="lb-rank-cell" style="color:var(--color-primary-glow);">47</td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:10px;">
                                            <div class="lb-avatar-sm" style="background:#4F46E5">N</div>
                                            <div class="lb-name-cell">Nahian <span class="you-tag">You</span></div>
                                        </div>
                                    </td>
                                    <td style="font-weight:700;color:#fff;">88 / 100</td>
                                    <td>
                                        <div class="lb-bar-wrap">
                                            <div class="lb-bar">
                                                <div class="lb-bar-fill" style="width:88%;background:#818CF8"></div>
                                            </div>
                                            <span style="font-size:13px;font-weight:700;color:#818CF8;min-width:40px;text-align:right;">88%</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('student-layout.js') }}"></script>
</body>

</html>