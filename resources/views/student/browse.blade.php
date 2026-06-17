<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('student-layout.css') }}">
    <title>Quizora — Browse Quizzes</title>
    <style>
        .page-header {
            margin-bottom: 20px;
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

        /* SEARCH BAR */
        .search-bar-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .search-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1.5px solid var(--color-border-light);
            border-radius: 12px;
            padding: 0 16px;
            flex: 1;
            min-width: 240px;
            transition: border-color 0.2s;
        }

        .search-wrap:focus-within {
            border-color: rgba(79, 70, 229, 0.6);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .search-wrap input {
            flex: 1;
            height: 44px;
            background: none;
            border: none;
            outline: none;
            color: #fff;
            font-size: 14px;
            font-family: var(--font);
        }

        .search-wrap input::placeholder {
            color: var(--color-text-muted);
        }

        .search-wrap i {
            color: var(--color-text-muted);
            font-size: 18px;
        }

        .filter-pill {
            height: 44px;
            padding: 0 18px;
            border-radius: 12px;
            border: 1.5px solid var(--color-border-light);
            background: var(--color-bg-card);
            color: var(--color-text-secondary);
            font-size: 13px;
            font-weight: 500;
            font-family: var(--font);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .filter-pill:hover {
            border-color: rgba(79, 70, 229, 0.4);
            color: #fff;
        }

        /* CATEGORY CHIPS */
        .category-scroll {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 6px;
            margin-bottom: 28px;
            scrollbar-width: none;
        }

        .category-scroll::-webkit-scrollbar {
            display: none;
        }

        .category-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: 24px;
            background: var(--color-bg-card);
            border: 1.5px solid var(--color-border-light);
            color: var(--color-text-secondary);
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .category-chip i {
            font-size: 16px;
        }

        .category-chip:hover {
            border-color: rgba(79, 70, 229, 0.4);
            color: #fff;
        }

        .category-chip.active {
            background: var(--color-primary-solid);
            border-color: var(--color-primary-solid);
            color: #fff;
        }

        /* SECTION */
        .quiz-section {
            margin-bottom: 32px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .section-header h2 {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-header h2 i {
            color: var(--color-primary-glow);
            font-size: 18px;
        }

        .view-all-link {
            font-size: 12px;
            font-weight: 600;
            color: var(--color-primary-glow);
            text-decoration: none;
        }

        .view-all-link:hover {
            color: #fff;
        }

        /* QUIZ CARD GRID */
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
            cursor: pointer;
            text-decoration: none;
            display: block;
        }

        .pquiz-card:hover {
            border-color: rgba(79, 70, 229, 0.4);
            transform: translateY(-3px);
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
            <a href="#" class="nav-item active">
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
            <div class="topbar-title">Browse Quizzes</div>
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

            <div class="page-header">
                <h1>Discover Quizzes</h1>
                <p>Browse public quizzes from school basics to BCS preparation</p>
            </div>

            <!-- SEARCH + FILTERS -->
            <div class="search-bar-wrap">
                <div class="search-wrap">
                    <i class="ti ti-search"></i>
                    <input type="text" placeholder="Search quizzes, topics, or tags..." />
                </div>
                <button class="filter-pill"><i class="ti ti-adjustments-horizontal"></i> Difficulty</button>
                <button class="filter-pill"><i class="ti ti-language"></i> Language</button>
                <button class="filter-pill"><i class="ti ti-sort-descending"></i> Sort by</button>
            </div>

            <!-- CATEGORIES -->
            <div class="category-scroll">
                <div class="category-chip active"><i class="ti ti-apps"></i> All</div>
                <div class="category-chip"><i class="ti ti-calculator"></i> Mathematics</div>
                <div class="category-chip"><i class="ti ti-flask"></i> Science</div>
                <div class="category-chip"><i class="ti ti-building-bank"></i> BCS Cadre</div>
                <div class="category-chip"><i class="ti ti-world"></i> History</div>
                <div class="category-chip"><i class="ti ti-language"></i> English</div>
                <div class="category-chip"><i class="ti ti-code"></i> Programming</div>
                <div class="category-chip"><i class="ti ti-map"></i> Geography</div>
                <div class="category-chip"><i class="ti ti-gavel"></i> General Knowledge</div>
            </div>

            <!-- FEATURED -->
            <div class="quiz-section">
                <div class="section-header">
                    <h2><i class="ti ti-flame"></i> Trending Now</h2>
                    <a href="#" class="view-all-link">View all</a>
                </div>
                <div class="quiz-grid">

                    <a href="#" class="pquiz-card">
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

                    <a href="#" class="pquiz-card">
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

                    <a href="#" class="pquiz-card">
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

                    <a href="#" class="pquiz-card">
                        <div class="pquiz-banner" style="background:linear-gradient(135deg,#B45309,#F59E0B);">
                            <i class="ti ti-calculator"></i>
                        </div>
                        <div class="pquiz-body">
                            <div class="pquiz-title">Algebra Fundamentals — Quick Practice</div>
                            <div class="pquiz-meta">
                                <div class="pquiz-meta-item"><i class="ti ti-help-circle"></i> 15 Qs</div>
                                <div class="pquiz-meta-item"><i class="ti ti-clock"></i> 20 min</div>
                            </div>
                            <div class="pquiz-footer">
                                <span class="diff-badge diff-easy">Easy</span>
                                <span class="pquiz-attempts"><i class="ti ti-users"></i> 2.1k attempts</span>
                            </div>
                        </div>
                    </a>

                </div>
            </div>

            <!-- MATHEMATICS -->
            <div class="quiz-section">
                <div class="section-header">
                    <h2><i class="ti ti-calculator"></i> Mathematics</h2>
                    <a href="#" class="view-all-link">View all</a>
                </div>
                <div class="quiz-grid">

                    <a href="#" class="pquiz-card">
                        <div class="pquiz-banner" style="background:linear-gradient(135deg,#B45309,#F59E0B);">
                            <i class="ti ti-square-root-2"></i>
                        </div>
                        <div class="pquiz-body">
                            <div class="pquiz-title">Geometry Essentials</div>
                            <div class="pquiz-meta">
                                <div class="pquiz-meta-item"><i class="ti ti-help-circle"></i> 18 Qs</div>
                                <div class="pquiz-meta-item"><i class="ti ti-clock"></i> 25 min</div>
                            </div>
                            <div class="pquiz-footer">
                                <span class="diff-badge diff-medium">Medium</span>
                                <span class="pquiz-attempts"><i class="ti ti-users"></i> 432 attempts</span>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="pquiz-card">
                        <div class="pquiz-banner" style="background:linear-gradient(135deg,#B45309,#F59E0B);">
                            <i class="ti ti-infinity"></i>
                        </div>
                        <div class="pquiz-body">
                            <div class="pquiz-title">Advanced Calculus Challenge</div>
                            <div class="pquiz-meta">
                                <div class="pquiz-meta-item"><i class="ti ti-help-circle"></i> 25 Qs</div>
                                <div class="pquiz-meta-item"><i class="ti ti-clock"></i> 40 min</div>
                            </div>
                            <div class="pquiz-footer">
                                <span class="diff-badge diff-hard">Hard</span>
                                <span class="pquiz-attempts"><i class="ti ti-users"></i> 198 attempts</span>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="pquiz-card">
                        <div class="pquiz-banner" style="background:linear-gradient(135deg,#B45309,#F59E0B);">
                            <i class="ti ti-percentage"></i>
                        </div>
                        <div class="pquiz-body">
                            <div class="pquiz-title">Basic Arithmetic for Beginners</div>
                            <div class="pquiz-meta">
                                <div class="pquiz-meta-item"><i class="ti ti-help-circle"></i> 10 Qs</div>
                                <div class="pquiz-meta-item"><i class="ti ti-clock"></i> 15 min</div>
                            </div>
                            <div class="pquiz-footer">
                                <span class="diff-badge diff-easy">Easy</span>
                                <span class="pquiz-attempts"><i class="ti ti-users"></i> 3.4k attempts</span>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="pquiz-card">
                        <div class="pquiz-banner" style="background:linear-gradient(135deg,#B45309,#F59E0B);">
                            <i class="ti ti-chart-line"></i>
                        </div>
                        <div class="pquiz-body">
                            <div class="pquiz-title">Statistics & Probability</div>
                            <div class="pquiz-meta">
                                <div class="pquiz-meta-item"><i class="ti ti-help-circle"></i> 22 Qs</div>
                                <div class="pquiz-meta-item"><i class="ti ti-clock"></i> 30 min</div>
                            </div>
                            <div class="pquiz-footer">
                                <span class="diff-badge diff-medium">Medium</span>
                                <span class="pquiz-attempts"><i class="ti ti-users"></i> 567 attempts</span>
                            </div>
                        </div>
                    </a>

                </div>
            </div>

            <!-- BCS CADRE -->
            <div class="quiz-section">
                <div class="section-header">
                    <h2><i class="ti ti-building-bank"></i> BCS Cadre Preparation</h2>
                    <a href="#" class="view-all-link">View all</a>
                </div>
                <div class="quiz-grid">

                    <a href="#" class="pquiz-card">
                        <div class="pquiz-banner" style="background:linear-gradient(135deg,#4F46E5,#818CF8);">
                            <i class="ti ti-scale"></i>
                        </div>
                        <div class="pquiz-body">
                            <div class="pquiz-title">BCS Preli — Bangladesh Constitution</div>
                            <div class="pquiz-meta">
                                <div class="pquiz-meta-item"><i class="ti ti-help-circle"></i> 35 Qs</div>
                                <div class="pquiz-meta-item"><i class="ti ti-clock"></i> 45 min</div>
                            </div>
                            <div class="pquiz-footer">
                                <span class="diff-badge diff-hard">Hard</span>
                                <span class="pquiz-attempts"><i class="ti ti-users"></i> 876 attempts</span>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="pquiz-card">
                        <div class="pquiz-banner" style="background:linear-gradient(135deg,#4F46E5,#818CF8);">
                            <i class="ti ti-world"></i>
                        </div>
                        <div class="pquiz-body">
                            <div class="pquiz-title">International Affairs & Organizations</div>
                            <div class="pquiz-meta">
                                <div class="pquiz-meta-item"><i class="ti ti-help-circle"></i> 28 Qs</div>
                                <div class="pquiz-meta-item"><i class="ti ti-clock"></i> 35 min</div>
                            </div>
                            <div class="pquiz-footer">
                                <span class="diff-badge diff-medium">Medium</span>
                                <span class="pquiz-attempts"><i class="ti ti-users"></i> 543 attempts</span>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="pquiz-card">
                        <div class="pquiz-banner" style="background:linear-gradient(135deg,#4F46E5,#818CF8);">
                            <i class="ti ti-coin"></i>
                        </div>
                        <div class="pquiz-body">
                            <div class="pquiz-title">Bangladesh Economy Overview</div>
                            <div class="pquiz-meta">
                                <div class="pquiz-meta-item"><i class="ti ti-help-circle"></i> 24 Qs</div>
                                <div class="pquiz-meta-item"><i class="ti ti-clock"></i> 30 min</div>
                            </div>
                            <div class="pquiz-footer">
                                <span class="diff-badge diff-medium">Medium</span>
                                <span class="pquiz-attempts"><i class="ti ti-users"></i> 721 attempts</span>
                            </div>
                        </div>
                    </a>

                </div>
            </div>

        </div>
    </main>

    <script src="{{ asset('student-layout.js') }}"></script>
    <script>
        document.querySelectorAll('.category-chip').forEach(chip => {
            chip.addEventListener('click', function() {
                document.querySelectorAll('.category-chip').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>

</html>