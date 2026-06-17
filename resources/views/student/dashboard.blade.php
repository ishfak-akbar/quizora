<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
  <link rel="stylesheet" href="{{ asset('student-layout.css') }}">
  <title>Quizora — Student Dashboard</title>
  <style>
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 24px;
    }

    .stat-card {
      background: var(--color-bg-card);
      border: 1px solid var(--color-border-light);
      border-radius: 14px;
      padding: 20px;
      position: relative;
      overflow: hidden;
      transition: border-color 0.2s, transform 0.2s;
    }

    .stat-card:hover {
      border-color: rgba(79, 70, 229, 0.4);
      transform: translateY(-2px);
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
      background: linear-gradient(90deg, var(--color-primary-solid), var(--color-stat-purple));
    }

    .stat-card.cyan::before {
      background: linear-gradient(90deg, #0891B2, var(--color-stat-cyan));
    }

    .stat-card.green::before {
      background: linear-gradient(90deg, #059669, var(--color-status-success));
    }

    .stat-card.amber::before {
      background: linear-gradient(90deg, #B45309, #F59E0B);
    }

    .stat-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      margin-bottom: 14px;
    }

    .stat-card.purple .stat-icon {
      background: rgba(79, 70, 229, 0.2);
      color: var(--color-primary-glow);
    }

    .stat-card.cyan .stat-icon {
      background: rgba(34, 211, 238, 0.15);
      color: var(--color-stat-cyan);
    }

    .stat-card.green .stat-icon {
      background: rgba(52, 211, 153, 0.15);
      color: var(--color-status-success);
    }

    .stat-card.amber .stat-icon {
      background: rgba(245, 158, 11, 0.15);
      color: #F59E0B;
    }

    .stat-value {
      font-size: 28px;
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

    .browse-section {
      background: linear-gradient(135deg, #2E2570 0%, #4F46E5 50%, #818CF8 100%);
      border-radius: 16px;
      padding: 28px;
      position: relative;
      overflow: hidden;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
    }

    .browse-section::before {
      content: '';
      position: absolute;
      top: -30px;
      right: -30px;
      width: 180px;
      height: 180px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.06);
    }

    .browse-info {
      position: relative;
      z-index: 1;
    }

    .browse-info h2 {
      font-size: 22px;
      font-weight: 700;
      color: #fff;
      margin-bottom: 6px;
    }

    .browse-info p {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.7);
    }

    .browse-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.25);
      color: #fff;
      font-size: 13px;
      font-weight: 600;
      padding: 10px 20px;
      border-radius: 10px;
      text-decoration: none;
      transition: background 0.2s;
      white-space: nowrap;
      position: relative;
      z-index: 1;
    }

    .browse-btn:hover {
      background: rgba(255, 255, 255, 0.25);
    }

    .dashboard-grid {
      display: grid;
      grid-template-columns: 1.4fr 1fr;
      gap: 20px;
    }

    .quiz-row {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 14px 20px;
      border-bottom: 1px solid var(--color-border-light);
      transition: background 0.15s;
    }

    .quiz-row:last-child {
      border-bottom: none;
    }

    .quiz-row:hover {
      background: var(--color-bg-row-hover);
    }

    .quiz-row-icon {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      background: rgba(79, 70, 229, 0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      color: var(--color-primary-glow);
      flex-shrink: 0;
    }

    .quiz-row-info {
      flex: 1;
    }

    .quiz-row-title {
      font-size: 13px;
      font-weight: 600;
      color: #fff;
    }

    .quiz-row-meta {
      font-size: 11px;
      color: var(--color-text-muted);
      margin-top: 2px;
    }

    .quiz-row-badge {
      font-size: 11px;
      font-weight: 600;
      padding: 3px 10px;
      border-radius: 20px;
      flex-shrink: 0;
    }

    .badge-easy {
      background: rgba(52, 211, 153, 0.15);
      color: var(--color-status-success);
    }

    .badge-medium {
      background: rgba(245, 158, 11, 0.15);
      color: #F59E0B;
    }

    .badge-hard {
      background: rgba(248, 113, 113, 0.15);
      color: var(--color-status-error);
    }

    .quiz-row-action {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--color-primary-solid);
      color: #fff;
      font-size: 12px;
      font-weight: 600;
      padding: 7px 14px;
      border-radius: 8px;
      text-decoration: none;
      transition: background 0.2s;
      flex-shrink: 0;
    }

    .quiz-row-action:hover {
      background: #4338CA;
    }

    .result-row {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 20px;
      border-bottom: 1px solid var(--color-border-light);
    }

    .result-row:last-child {
      border-bottom: none;
    }

    .result-score-circle {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 11px;
      font-weight: 700;
      flex-shrink: 0;
      border: 2px solid;
    }

    .result-info {
      flex: 1;
    }

    .result-title {
      font-size: 13px;
      font-weight: 600;
      color: #fff;
    }

    .result-date {
      font-size: 11px;
      color: var(--color-text-muted);
      margin-top: 2px;
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
      <a href="#" class="nav-item active">
        <i class="ti ti-layout-dashboard nav-icon"></i>
        <span class="nav-text">Dashboard</span>
      </a>
      <a href="#" class="nav-item">
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
      <div>
        <div class="topbar-title">Welcome back, Nahian 👋</div>
        <div class="topbar-sub">Ready to test your knowledge today?</div>
      </div>
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

      <!-- STATS -->
      <div class="stats-grid">
        <div class="stat-card purple">
          <div class="stat-icon"><i class="ti ti-clipboard-list"></i></div>
          <div class="stat-value">12</div>
          <div class="stat-label">Quizzes Taken</div>
        </div>
        <div class="stat-card cyan">
          <div class="stat-icon"><i class="ti ti-chart-line"></i></div>
          <div class="stat-value">78%</div>
          <div class="stat-label">Average Score</div>
        </div>
        <div class="stat-card green">
          <div class="stat-icon"><i class="ti ti-star"></i></div>
          <div class="stat-value">96%</div>
          <div class="stat-label">Best Score</div>
        </div>
        <div class="stat-card amber">
          <div class="stat-icon"><i class="ti ti-flame"></i></div>
          <div class="stat-value">5</div>
          <div class="stat-label">Day Streak</div>
        </div>
      </div>

      <!-- BROWSE CTA -->
      <div class="browse-section">
        <div class="browse-info">
          <h2>Discover new quizzes</h2>
          <p>Explore thousands of quizzes across every topic — from school to BCS prep</p>
        </div>
        <a href="#" class="browse-btn">
          <i class="ti ti-compass"></i> Browse Quizzes
        </a>
      </div>

      <!-- MAIN GRID -->
      <div class="dashboard-grid">

        <!-- RECOMMENDED QUIZZES -->
        <div class="card">
          <div class="card-header">
            <h2>Recommended For You</h2>
            <a href="#" class="view-all-link">View all</a>
          </div>
          <div>
            <div class="quiz-row">
              <div class="quiz-row-icon"><i class="ti ti-calculator"></i></div>
              <div class="quiz-row-info">
                <div class="quiz-row-title">Algebra Fundamentals</div>
                <div class="quiz-row-meta">Mathematics · 15 questions · 20 min</div>
              </div>
              <span class="quiz-row-badge badge-easy">Easy</span>
              <a href="#" class="quiz-row-action"><i class="ti ti-player-play"></i> Start</a>
            </div>
            <div class="quiz-row">
              <div class="quiz-row-icon"><i class="ti ti-flask"></i></div>
              <div class="quiz-row-info">
                <div class="quiz-row-title">General Science Basics</div>
                <div class="quiz-row-meta">Science · 20 questions · 25 min</div>
              </div>
              <span class="quiz-row-badge badge-medium">Medium</span>
              <a href="#" class="quiz-row-action"><i class="ti ti-player-play"></i> Start</a>
            </div>
            <div class="quiz-row">
              <div class="quiz-row-icon"><i class="ti ti-building-bank"></i></div>
              <div class="quiz-row-info">
                <div class="quiz-row-title">BCS Preli — Bangladesh Affairs</div>
                <div class="quiz-row-meta">BCS Cadre · 30 questions · 40 min</div>
              </div>
              <span class="quiz-row-badge badge-hard">Hard</span>
              <a href="#" class="quiz-row-action"><i class="ti ti-player-play"></i> Start</a>
            </div>
            <div class="quiz-row">
              <div class="quiz-row-icon"><i class="ti ti-world"></i></div>
              <div class="quiz-row-info">
                <div class="quiz-row-title">World History Highlights</div>
                <div class="quiz-row-meta">History · 18 questions · 22 min</div>
              </div>
              <span class="quiz-row-badge badge-medium">Medium</span>
              <a href="#" class="quiz-row-action"><i class="ti ti-player-play"></i> Start</a>
            </div>
          </div>
        </div>

        <!-- RECENT RESULTS -->
        <div class="card">
          <div class="card-header">
            <h2>Recent Results</h2>
            <a href="#" class="view-all-link">View all</a>
          </div>
          <div>
            <div class="result-row">
              <div class="result-score-circle" style="border-color:#34D399;color:#34D399;">96%</div>
              <div class="result-info">
                <div class="result-title">Data Structures Midterm</div>
                <div class="result-date">Jun 14, 2026</div>
              </div>
            </div>
            <div class="result-row">
              <div class="result-score-circle" style="border-color:#22D3EE;color:#22D3EE;">82%</div>
              <div class="result-info">
                <div class="result-title">General Knowledge Quiz</div>
                <div class="result-date">Jun 11, 2026</div>
              </div>
            </div>
            <div class="result-row">
              <div class="result-score-circle" style="border-color:#F59E0B;color:#F59E0B;">65%</div>
              <div class="result-info">
                <div class="result-title">English Grammar Test</div>
                <div class="result-date">Jun 8, 2026</div>
              </div>
            </div>
            <div class="result-row">
              <div class="result-score-circle" style="border-color:#F87171;color:#F87171;">45%</div>
              <div class="result-info">
                <div class="result-title">Advanced Calculus</div>
                <div class="result-date">Jun 3, 2026</div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </main>

  <script src="{{ asset('student-layout.js') }}"></script>
</body>

</html>