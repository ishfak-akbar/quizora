<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('quizora.css') }}">
    <title>Quizora — Leaderboard</title>
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

        /* TOP 3 PODIUM */
        .podium {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 16px;
            margin-bottom: 28px;
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

        /* LB TABLE */
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

        .empty-lb {
            text-align: center;
            padding: 48px;
            color: var(--color-text-muted);
        }

        .empty-lb i {
            font-size: 40px;
            display: block;
            margin-bottom: 12px;
            color: rgba(79, 70, 229, 0.3);
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
            <a href="{{ route('teacher.results') }}" class="nav-item">
                <i class="ti ti-chart-bar nav-icon"></i>
                <span class="nav-text">Results</span>
            </a>
            <a href="{{ route('teacher.leaderboard.page') }}" class="nav-item active">
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
            <div class="topbar-title">Leaderboard</div>
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

            <div class="page-header">
                <div>
                    <h1>Leaderboard</h1>
                    <p>Top performing students per quiz</p>
                </div>
            </div>

            <div class="lb-layout">

                <!-- QUIZ LIST -->
                <div>
                    <div style="font-size:12px;font-weight:600;color:var(--color-text-muted);letter-spacing:0.8px;text-transform:uppercase;margin-bottom:12px;">
                        Select Quiz
                    </div>
                    <div class="quiz-list">
                        @forelse($quizzes as $quiz)
                        <div class="quiz-list-item" onclick="loadLeaderboard({{ $quiz->id }}, this)">
                            <div class="quiz-list-icon">
                                <i class="ti ti-file-description"></i>
                            </div>
                            <div style="flex:1;overflow:hidden;">
                                <div class="quiz-list-name">{{ $quiz->title }}</div>
                                <div class="quiz-list-meta">{{ ucfirst($quiz->status) }}</div>
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center;padding:32px;color:var(--color-text-muted);font-size:13px;">
                            No quizzes yet.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- LEADERBOARD PANEL -->
                <div id="lbPanel">
                    <div class="empty-lb">
                        <i class="ti ti-trophy"></i>
                        <p>Select a quiz to view leaderboard</p>
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

        const colors = ['#4F46E5', '#7C3AED', '#0891B2', '#059669', '#D97706', '#DB2777', '#0891B2'];
        const medals = ['🥇', '🥈', '🥉'];
        const medalColors = ['#F59E0B', '#9CA3AF', '#D97706'];

        function getInitials(name) {
            const parts = name.split(' ');
            return parts.length > 1 ?
                (parts[0][0] + parts[1][0]).toUpperCase() :
                name.substring(0, 2).toUpperCase();
        }

        function loadLeaderboard(quizId, el) {
            document.querySelectorAll('.quiz-list-item').forEach(i => i.classList.remove('active'));
            el.classList.add('active');

            const panel = document.getElementById('lbPanel');
            panel.innerHTML = '<div style="text-align:center;padding:48px;color:var(--color-text-muted);font-size:13px;">Loading...</div>';

            fetch(`/teacher/leaderboard/${quizId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        panel.innerHTML = `
            <div class="empty-lb">
              <i class="ti ti-inbox"></i>
              <p>No submissions yet for this quiz.</p>
            </div>`;
                        return;
                    }

                    // TOP 3 PODIUM
                    const top3 = data.slice(0, 3);
                    const podiumOrder = top3.length >= 2 ?
                        [top3[1], top3[0], top3[2]].filter(Boolean) :
                        top3;
                    const podiumHeights = top3.length >= 2 ? ['80px', '110px', '60px'] : ['110px'];
                    const podiumRanks = top3.length >= 2 ? [2, 1, 3] : [1];

                    let podiumHTML = podiumOrder.map((s, i) => `
          <div class="podium-item">
            <div class="podium-avatar" style="background:${colors[podiumRanks[i]-1]}">
              ${getInitials(s.name)}
              <span class="podium-medal">${medals[podiumRanks[i]-1]}</span>
            </div>
            <div class="podium-name">${s.name}</div>
            <div class="podium-score" style="color:${medalColors[podiumRanks[i]-1]}">${s.score}%</div>
            <div class="podium-block" style="height:${podiumHeights[i]};background:${colors[podiumRanks[i]-1]}22;border:1px solid ${colors[podiumRanks[i]-1]}44;">
              ${podiumRanks[i]}
            </div>
          </div>
        `).join('');

                    // FULL TABLE
                    let tableHTML = data.map((s, i) => `
          <tr>
            <td class="lb-rank-cell">
              ${i < 3
                ? `<span style="font-size:18px;">${medals[i]}</span>`
                : `<span style="color:var(--color-text-muted);">${i+1}</span>`}
            </td>
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                <div class="lb-avatar-sm" style="background:${colors[i] || '#6B7280'}">
                  ${getInitials(s.name)}
                </div>
                <div style="font-weight:600;color:#fff;">${s.name}</div>
              </div>
            </td>
            <td style="font-weight:700;color:#fff;">${s.raw_score} / ${s.total}</td>
            <td>
              <div class="lb-bar-wrap">
                <div class="lb-bar">
                  <div class="lb-bar-fill" style="width:${s.score}%;background:${colors[i] || '#6B7280'}"></div>
                </div>
                <span style="font-size:13px;font-weight:700;color:${colors[i] || '#6B7280'};min-width:40px;text-align:right;">${s.score}%</span>
              </div>
            </td>
          </tr>
        `).join('');

                    panel.innerHTML = `
          <div class="podium">${podiumHTML}</div>
          <div class="card">
            <div class="card-header">
              <h2>Full Rankings</h2>
              <span style="font-size:12px;color:var(--color-text-muted);">${data.length} students</span>
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
              <tbody>${tableHTML}</tbody>
            </table>
          </div>
        `;
                })
                .catch(() => {
                    panel.innerHTML = '<div style="text-align:center;padding:48px;color:var(--color-status-error);font-size:13px;">Failed to load leaderboard.</div>';
                });
        }
    </script>
</body>

</html>