<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
  <link rel="stylesheet" href="{{ asset('quizora.css') }}">
  <title>Quizora — Teacher Dashboard</title>
  <style>
    :root {
      --color-primary-glow: #818CF8;
      --color-primary-solid: #4F46E5;
      --color-primary-dark: #2E2570;
      --color-bg-main: #0E0B20;
      --color-bg-card: #161233;
      --color-bg-row-hover: #1E1A3E;
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

    /* SIDEBAR */
    .sidebar {
      width: var(--sidebar-w);
      min-height: 100vh;
      background:
        radial-gradient(ellipse 300px 400px at 50% 0%,
          rgba(99, 102, 241, 0.20) 0%,
          transparent 70%),
        linear-gradient(180deg,
          #1e1b45 0%,
          #141130 50%,
          #0e0b20 100%);
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
      padding: 10px 18px;
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

    /* TOGGLE BTN */
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
      gap: 16px;
      position: sticky;
      top: 0;
      z-index: 50;
    }

    .topbar-title {
      font-size: 16px;
      font-weight: 600;
      color: #fff;
      flex: 1;
    }

    .topbar-sub {
      font-size: 13px;
      color: var(--color-text-muted);
      margin-top: 2px;
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

    .user-chevron {
      font-size: 14px;
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

    .dropdown-item.danger {
      background-color: rgba(153, 27, 27, 0.3);
      color: #FCA5A5;
    }

    .dropdown-item.danger:hover {
      background-color: rgba(239, 68, 68, 0.15);
      color: #F87171;
      /* Changes text/icon to a matching light red on hover */
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

    /* STATS */
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

    .stat-card.pink::before {
      background: linear-gradient(90deg, #DB2777, #F472B6);
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

    .stat-card.pink .stat-icon {
      background: rgba(244, 114, 182, 0.15);
      color: #F472B6;
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

    .stat-change {
      position: absolute;
      top: 20px;
      right: 16px;
      font-size: 11px;
      font-weight: 600;
      padding: 3px 8px;
      border-radius: 20px;
    }

    .stat-change.up {
      background: rgba(52, 211, 153, 0.15);
      color: var(--color-status-success);
    }

    .stat-change.down {
      background: rgba(248, 113, 113, 0.15);
      color: var(--color-status-error);
    }

    /* GRID LAYOUT */
    .dashboard-grid {
      display: grid;
      grid-template-columns: 1fr 340px;
      gap: 20px;
      margin-bottom: 24px;
    }

    /* CREATE QUIZ SECTION */
    .create-section {
      background: var(--color-bg-card);
      border: 1px solid var(--color-border-light);
      border-radius: 14px;
      overflow: hidden;
      margin-bottom: 20px;
    }

    .create-header {
      padding: 20px 24px;
      border-bottom: 1px solid var(--color-border-light);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .create-header h2 {
      font-size: 15px;
      font-weight: 600;
      color: #fff;
    }

    .create-body {
      padding: 24px;
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .create-visual {
      width: 80px;
      height: 80px;
      border-radius: 16px;
      background: rgba(79, 70, 229, 0.2);
      border: 1px solid rgba(79, 70, 229, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 36px;
      flex-shrink: 0;
      color: var(--color-primary-glow);
    }

    .create-info h3 {
      font-size: 18px;
      font-weight: 700;
      color: #fff;
      margin-bottom: 6px;
    }

    .create-info p {
      font-size: 13px;
      color: var(--color-text-secondary);
      line-height: 1.6;
      margin-bottom: 16px;
    }

    .create-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: var(--color-primary-solid);
      color: #fff;
      font-size: 14px;
      font-weight: 600;
      padding: 10px 20px;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      font-family: var(--font);
      transition: background 0.2s, transform 0.15s;
      text-decoration: none;
    }

    .create-btn:hover {
      background: #4338CA;
      transform: translateY(-1px);
    }

    /* CARD */
    .card {
      background: var(--color-bg-card);
      border: 1px solid var(--color-border-light);
      border-radius: 14px;
      overflow: hidden;
    }

    .card-header {
      padding: 18px 20px;
      border-bottom: 1px solid var(--color-border-light);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .card-header h2 {
      font-size: 14px;
      font-weight: 600;
      color: #fff;
    }

    /* QUIZ TABLE */
    .quiz-table {
      width: 100%;
      border-collapse: collapse;
    }

    .quiz-table th {
      padding: 10px 20px;
      text-align: left;
      font-size: 11px;
      font-weight: 600;
      color: var(--color-text-muted);
      letter-spacing: 0.8px;
      text-transform: uppercase;
      border-bottom: 1px solid var(--color-border-light);
    }

    .quiz-table td {
      padding: 13px 20px;
      font-size: 13px;
      color: var(--color-text-secondary);
      border-bottom: 1px solid var(--color-border-light);
      vertical-align: middle;
    }

    .quiz-table tr:last-child td {
      border-bottom: none;
    }

    .quiz-table tr:hover td {
      background: var(--color-bg-row-hover);
    }

    .quiz-name {
      font-weight: 600;
      color: #fff;
      font-size: 13px;
    }

    .quiz-type {
      font-size: 11px;
      color: var(--color-text-muted);
      margin-top: 2px;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
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
      transition: background 0.15s, color 0.15s, border-color 0.15s;
    }

    .action-btn:hover {
      background: var(--color-bg-row-hover);
      color: #fff;
      border-color: rgba(79, 70, 229, 0.4);
    }

    /* LEADERBOARD */
    .leaderboard-filter {
      padding: 12px 16px;
      border-bottom: 1px solid var(--color-border-light);
    }

    .quiz-select {
      width: 100%;
      background: var(--color-bg-main);
      border: 1px solid var(--color-border-light);
      border-radius: 8px;
      color: #fff;
      font-size: 12px;
      font-family: var(--font);
      padding: 8px 12px;
      outline: none;
      cursor: pointer;
    }

    .quiz-select option {
      background: var(--color-bg-card);
    }

    .leaderboard-list {
      padding: 8px 0;
    }

    .lb-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 16px;
      transition: background 0.15s;
    }

    .lb-item:hover {
      background: var(--color-bg-row-hover);
    }

    .lb-rank {
      width: 24px;
      text-align: center;
      font-size: 13px;
      font-weight: 700;
    }

    .lb-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: 700;
      color: #fff;
      flex-shrink: 0;
    }

    .lb-info {
      flex: 1;
    }

    .lb-name {
      font-size: 13px;
      font-weight: 600;
      color: #fff;
    }

    .lb-score-bar {
      height: 3px;
      background: rgba(255, 255, 255, 0.08);
      border-radius: 2px;
      margin-top: 4px;
    }

    .lb-score-fill {
      height: 100%;
      border-radius: 2px;
    }

    .lb-score {
      font-size: 13px;
      font-weight: 700;
      color: var(--color-primary-glow);
    }

    .view-all-btn {
      display: block;
      width: 100%;
      padding: 12px;
      text-align: center;
      font-size: 12px;
      font-weight: 600;
      color: var(--color-primary-glow);
      border-top: 1px solid var(--color-border-light);
      background: transparent;
      border-left: none;
      border-right: none;
      border-bottom: none;
      cursor: pointer;
      font-family: var(--font);
      transition: background 0.15s;
    }

    .view-all-btn:hover {
      background: var(--color-bg-row-hover);
    }

    .section-tag {
      font-size: 11px;
      font-weight: 600;
      color: var(--color-primary-glow);
      background: rgba(79, 70, 229, 0.15);
      padding: 3px 10px;
      border-radius: 20px;
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
  <script src="{{ asset('quizora.js') }}"></script>
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon">Q</div>
      <div class="logo-text">Quiz<span>ora</span></div>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-label">Main</div>
      <a href="{{ route('teacher.dashboard') }}" class="nav-item active">
        <i class="ti ti-layout-dashboard nav-icon" aria-hidden="true"></i>
        <span class="nav-text">Dashboard</span>
      </a>
      <a href="{{ route('teacher.quizzes') }}" class="nav-item">
        <i class="ti ti-file-description nav-icon"></i>
        <span class="nav-text">My Quizzes</span>
        <span class="nav-badge">{{ auth()->user()->quizzes()->count() }}</span>
      </a>
      <a href="#" class="nav-item">
        <i class="ti ti-circle-plus nav-icon" aria-hidden="true"></i>
        <span class="nav-text">Create Quiz</span>
      </a>
      <div class="nav-label">Analytics</div>
      <a href="{{ route('teacher.results') }}" class="nav-item">
        <i class="ti ti-chart-bar nav-icon"></i>
        <span class="nav-text">Results</span>
      </a>
      <a href="{{ route('teacher.leaderboard.page') }}" class="nav-item">
        <i class="ti ti-trophy nav-icon" aria-hidden="true"></i>
        <span class="nav-text">Leaderboard</span>
      </a>
      <div class="nav-label">Manage</div>
      <a href="{{ route('teacher.students') }}" class="nav-item">
        <i class="ti ti-users nav-icon" aria-hidden="true"></i>
        <span class="nav-text">Students</span>
      </a>
      <a href="#" class="nav-item">
        <i class="ti ti-database nav-icon" aria-hidden="true"></i>
        <span class="nav-text">Question Bank</span>
      </a>
    </nav>
    <div class="sidebar-bottom">
      <a href="#" class="nav-item">
        <i class="ti ti-settings nav-icon" aria-hidden="true"></i>
        <span class="nav-text">Settings</span>
      </a>
    </div>
  </aside>
  <button class="toggle-btn" id="toggleBtn" aria-label="Toggle sidebar">
    <i class="ti ti-chevron-left" id="toggleIcon" aria-hidden="true"></i>
  </button>
  <main class="main" id="main">
    <header class="topbar">
      <div>
        <div class="topbar-title">Good morning, {{ auth()->user()->name }} 👋</div>
        <div class="topbar-sub">{{ now()->format('l, F j, Y') }}</div>
      </div>
      <div class="topbar-right">
        <button class="notif-btn" aria-label="Notifications">
          <i class="ti ti-bell" aria-hidden="true"></i>
          <span class="notif-dot"></span>
        </button>
        <div class="user-btn" id="userBtn" role="button" tabindex="0">
          <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
          <div>
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div class="user-role">Teacher</div>
          </div>
          <i class="ti ti-chevron-down user-chevron" aria-hidden="true"></i>
          <div class="user-dropdown" id="userDropdown">
            <a href="#" class="dropdown-item"><i class="ti ti-user" aria-hidden="true"></i> Profile</a>
            <a href="#" class="dropdown-item"><i class="ti ti-settings" aria-hidden="true"></i> Settings</a>
            <a href="#" class="dropdown-item"><i class="ti ti-help-circle" aria-hidden="true"></i> Help</a>
            <div class="dropdown-divider"></div>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item danger" style="width:100%;border:none;text-align:left;">
                <i class="ti ti-logout" aria-hidden="true"></i> Logout
              </button>
            </form>
          </div>
        </div>
      </div>
    </header>
    <div class="content">
      <div class="stats-grid">
        <!-- Total Quizzes -->
        <div class="stat-card purple">
          <div class="stat-icon"><i class="ti ti-file-description" aria-hidden="true"></i></div>
          <div class="stat-value">{{ $totalQuizzes }}</div>
          <div class="stat-label">Total Quizzes</div>
        </div>
        <!-- Active Quizzes -->
        <div class="stat-card cyan">
          <div class="stat-icon"><i class="ti ti-player-play" aria-hidden="true"></i></div>
          <div class="stat-value">{{ $activeQuizzes }}</div>
          <div class="stat-label">Active Quizzes</div>
        </div>
        <!-- Total Students -->
        <div class="stat-card green">
          <div class="stat-icon"><i class="ti ti-users" aria-hidden="true"></i></div>
          <div class="stat-value">{{ $totalStudents }}</div>
          <div class="stat-label">Total Students</div>
        </div>
        <!-- Total Submissions -->
        <div class="stat-card pink">
          <div class="stat-icon"><i class="ti ti-clipboard-check" aria-hidden="true"></i></div>
          <div class="stat-value">{{ $totalSubmissions }}</div>
          <div class="stat-label">Submissions</div>
        </div>
      </div>
      <!-- CREATE QUIZ CTA -->
      <!-- QUICK ACTION CARDS -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">

        <!-- CREATE QUIZ CARD -->
        <div style="
    background: linear-gradient(135deg, #2E2570 0%, #4F46E5 50%, #818CF8 100%);
    border-radius: 16px;
    padding: 28px;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 160px;
  ">
          <!-- background decoration -->
          <div style="position:absolute;top:-30px;right:-30px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,0.06);"></div>
          <div style="position:absolute;bottom:-40px;right:40px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,0.04);"></div>
          <div style="position:absolute;top:20px;right:20px;opacity:0.15;font-size:80px;line-height:1;">
            <i class="ti ti-pencil-plus"></i>
          </div>

          <div>
            <div style="font-size:22px;font-weight:700;color:#fff;margin-bottom:6px;">
              Create New Quiz
            </div>
            <div style="font-size:13px;color:rgba(255,255,255,0.65);line-height:1.5;">
              Build tailored MCQ quizzes, customize your timers<br>and deadlines, and publish them to your classes<br>in just a click.
            </div>
          </div>

          <div style="margin-top:20px;">
            <a href="{{ route('teacher.quiz.create') }}" style="
              display: inline-flex;
              align-items: center;
              gap: 8px;
              background: rgba(255,255,255,0.15);
              backdrop-filter: blur(8px);
              border: 1px solid rgba(255,255,255,0.25);
              color: #fff;
              font-size: 13px;
              font-weight: 600;
              padding: 9px 18px;
              border-radius: 9px;
              text-decoration: none;
              transition: background 0.2s;
              font-family: var(--font);
            " onmouseover="this.style.background='rgba(255,255,255,0.25)'"
              onmouseout="this.style.background='rgba(255,255,255,0.15)'">
              <i class="ti ti-plus"></i> Create Quiz
            </a>
          </div>
        </div>

        <!-- OVERALL RESULTS CARD -->
        <div style="
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            min-height: 160px;
        ">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <div>
              <div style="font-size:15px;font-weight:700;color:#fff;">Quiz Results</div>
              <div style="font-size:12px;color:var(--color-text-muted);margin-top:2px;">Overall performance</div>
            </div>
            <i class="ti ti-chart-bar" style="font-size:22px;color:var(--color-primary-glow);"></i>
          </div>

          <div id="resultSelectContainer" style="margin-bottom:16px;"></div>

          <div id="resultStats" style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
            <div style="background:rgba(255,255,255,0.04);border-radius:10px;padding:12px;text-align:center;">
              <div style="font-size:20px;font-weight:700;color:#fff;" id="res-submissions">—</div>
              <div style="font-size:11px;color:var(--color-text-muted);margin-top:3px;">Submissions</div>
            </div>
            <div style="background:rgba(255,255,255,0.04);border-radius:10px;padding:12px;text-align:center;">
              <div style="font-size:20px;font-weight:700;color:var(--color-status-success);" id="res-avg">—</div>
              <div style="font-size:11px;color:var(--color-text-muted);margin-top:3px;">Avg Score</div>
            </div>
            <div style="background:rgba(255,255,255,0.04);border-radius:10px;padding:12px;text-align:center;">
              <div style="font-size:20px;font-weight:700;color:var(--color-primary-glow);" id="res-highest">—</div>
              <div style="font-size:11px;color:var(--color-text-muted);margin-top:3px;">Highest</div>
            </div>
          </div>
        </div>

      </div>
      <!-- MAIN GRID -->
      <div class="dashboard-grid">
        <!-- QUIZ TABLE -->
        <div class="card">
          <div class="card-header">
            <h2>Recent Quizzes</h2>
            <a href="#" class="view-all-link">View all</a>
          </div>
          <table class="quiz-table">
            <thead>
              <tr>
                <th>Quiz</th>
                <th>Students</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentQuizzes as $quiz)
              <tr>
                <td>
                  <div class="quiz-name">{{ $quiz->title }}</div>
                  <div class="quiz-type">
                    {{ strtoupper($quiz->type) }} ·
                    {{ $quiz->questions()->count() }} questions
                    @if($quiz->time_limit) · {{ $quiz->time_limit }} min @endif
                  </div>
                </td>
                <td>{{ $quiz->submitted_attempts }} / {{ $quiz->total_attempts }}</td>
                <td>{{ $quiz->ends_at ? $quiz->ends_at->format('M d, Y') : 'No deadline' }}</td>
                <td>
                  <span class="status-badge {{ $quiz->status }}">
                    <span class="status-dot"></span> {{ ucfirst($quiz->status) }}
                  </span>
                </td>
                <td>
                  <div class="action-btns">
                    <a href="{{ route('teacher.quiz.edit', $quiz->id) }}" class="action-btn" title="Edit">
                      <i class="ti ti-edit"></i>
                    </a>
                    <a href="#" class="action-btn" title="Results">
                      <i class="ti ti-chart-bar" aria-hidden="true"></i>
                    </a>
                    <form method="POST" action="{{ route('teacher.quiz.destroy', $quiz->id) }}">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="action-btn" title="Delete"
                        onclick="return confirm('Are you sure you want to delete this quiz?')">
                        <i class="ti ti-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" style="text-align:center;padding:32px;color:var(--color-text-muted);">
                  No quizzes yet. Create your first quiz!
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- LEADERBOARD -->
        <div class="card">
          <div class="card-header">
            <h2>Leaderboard</h2>
            <i class="ti ti-trophy" style="color:#F59E0B;font-size:18px"></i>
          </div>
          <div style="padding:12px 16px;border-bottom:1px solid var(--color-border-light);">
            <div id="lbSelectContainer"></div>
          </div>
          <div class="leaderboard-list" id="lbList">
            <div style="text-align:center;padding:32px;color:var(--color-text-muted);font-size:13px;">
              Select a quiz to view leaderboard
            </div>
          </div>
          <button class="view-all-btn">View full leaderboard</button>
        </div>
      </div>
    </div>
  </main>
  <script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    const toggleIcon = document.getElementById('toggleIcon');
    toggleBtn.addEventListener('click', () => {
      const collapsed = sidebar.classList.toggle('collapsed');
      document.body.classList.toggle('collapsed', collapsed);
      toggleIcon.className = collapsed ? 'ti ti-chevron-right' : 'ti ti-chevron-left';
    });

    const userBtn = document.getElementById('userBtn');
    const userDropdown = document.getElementById('userDropdown');
    userBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      userDropdown.classList.toggle('open');
    });
    document.addEventListener('click', () => userDropdown.classList.remove('open'));

    const quizSelect = document.getElementById('quizSelect');
    const lbList = document.getElementById('lbList');
    const colors = ['#4F46E5', '#7C3AED', '#0891B2', '#059669', '#D97706'];
    const medals = ['🥇', '🥈', '🥉'];

    function renderLeaderboard(data) {
      if (data.length === 0) {
        lbList.innerHTML = '<div style="text-align:center;padding:32px;color:var(--color-text-muted);font-size:13px;">No submissions yet for this quiz.</div>';
        return;
      }
      lbList.innerHTML = data.map((s, i) => `
            <div class="lb-item">
                <div class="lb-rank">${i < 3 ? medals[i] : i + 1}</div>
                <div class="lb-avatar" style="background:${colors[i] || '#6B7280'}">${s.initials}</div>
                <div class="lb-info">
                    <div class="lb-name">${s.name}</div>
                    <div class="lb-score-bar">
                        <div class="lb-score-fill" style="width:${s.score}%;background:${colors[i] || '#6B7280'}"></div>
                    </div>
                </div>
                <div class="lb-score">${s.score}%</div>
            </div>
        `).join('');
    }

    function fetchLeaderboard(quizId) {
      lbList.innerHTML = '<div style="text-align:center;padding:32px;color:var(--color-text-muted);font-size:13px;">Loading...</div>';
      fetch(`/teacher/leaderboard/${quizId}`)
        .then(res => res.json())
        .then(data => renderLeaderboard(data))
        .catch(() => {
          lbList.innerHTML = '<div style="text-align:center;padding:32px;color:var(--color-status-error);font-size:13px;">Failed to load.</div>';
        });
    }

    function fetchQuizResult(quizId) {
      if (!quizId) {
        document.getElementById('res-submissions').textContent = '—';
        document.getElementById('res-avg').textContent = '—';
        document.getElementById('res-highest').textContent = '—';
        return;
      }
      fetch(`/teacher/quiz/${quizId}/results-summary`)
        .then(res => res.json())
        .then(data => {
          document.getElementById('res-submissions').textContent = data.submissions;
          document.getElementById('res-avg').textContent = data.avg;
          document.getElementById('res-highest').textContent = data.highest;
        });
    }

    const lbOptions = [
      @foreach($quizzes as $quiz) {
        value: "{{ $quiz->id }}",
        label: "{{ $quiz->title }}"
      },
      @endforeach
    ];

    createCustomSelect(
      document.getElementById('lbSelectContainer'),
      lbOptions,
      'Select a quiz...',
      (value) => fetchLeaderboard(value)
    );

    createCustomSelect(
      document.getElementById('resultSelectContainer'),
      lbOptions,
      'Select a quiz...',
      (value) => fetchQuizResult(value)
    );

    if (quizSelect) {
      quizSelect.addEventListener('change', () => {
        if (quizSelect.value) fetchLeaderboard(quizSelect.value);
      });

      if (!quizSelect.value || quizSelect.options.length === 0) {
        lbList.innerHTML = '<div style="text-align:center;padding:32px;color:var(--color-text-muted);font-size:13px;">No quizzes yet. Create a quiz first.</div>';
      } else {
        fetchLeaderboard(quizSelect.value);
      }
    }
  </script>
  @if(session('success'))
  <div id="toast" style="
      position: fixed;
      top: 80px;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(52,211,153,0.15);
      border: 1px solid rgba(52,211,153,0.4);
      color: #34D399;
      padding: 12px 24px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
      z-index: 9999;
      backdrop-filter: blur(12px);
      box-shadow: 0 8px 32px rgba(0,0,0,0.3);
      transition: opacity 0.5s ease, transform 0.5s ease;
  ">
    <i class="ti ti-circle-check" style="font-size:18px;"></i>
    {{ session('success') }}
  </div>

  <script>
    const toast = document.getElementById('toast');
    if (toast) {
      setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-50%) translateY(-12px)';
        setTimeout(() => toast.remove(), 500);
      }, 3000);
    }
  </script>
  @endif
</body>

</html>