<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('quizora.css') }}">
    <link rel="stylesheet" href="{{ asset('layout.css') }}">
    <link rel="stylesheet" href="{{ asset('teacher.css') }}">
    <title>@yield('title', 'Quizora')</title>
    @stack('styles')
</head>

<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('welcome') }}" class="sidebar-logo" style="text-decoration:none;">
            <div class="logo-icon">Q</div>
            <div class="logo-text">Quiz<span>ora</span></div>
        </a>
        <nav class="sidebar-nav">
            <div class="nav-label">Main</div>
            <a href="{{ route('teacher.dashboard') }}"
                class="nav-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                <i class="ti ti-layout-dashboard nav-icon" aria-hidden="true"></i>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="{{ route('teacher.quizzes') }}"
                class="nav-item {{ request()->routeIs('teacher.quizzes') ? 'active' : '' }}">
                <i class="ti ti-file-description nav-icon"></i>
                <span class="nav-text">My Quizzes</span>
                <span class="nav-badge">{{ auth()->user()->quizzes()->count() }}</span>
            </a>
            <a href="{{ route('teacher.quiz.create') }}"
                class="nav-item {{ request()->routeIs('teacher.quiz.create') ? 'active' : '' }}">
                <i class="ti ti-circle-plus nav-icon" aria-hidden="true"></i>
                <span class="nav-text">Create Quiz</span>
            </a>
            <a href="{{ route('teacher.quiz.import') }}" class="nav-item {{ request()->routeIs('teacher.quiz.import') ? 'active' : '' }}">
                <i class="ti ti-file-upload nav-icon"></i>
                <span class="nav-text">Import Quiz</span>
            </a>
            <div class="nav-label">Analytics</div>
            <a href="{{ route('teacher.results') }}"
                class="nav-item {{ request()->routeIs('teacher.results') ? 'active' : '' }}">
                <i class="ti ti-chart-bar nav-icon"></i>
                <span class="nav-text">Results</span>
            </a>
            <a href="{{ route('teacher.leaderboard.page') }}"
                class="nav-item {{ request()->routeIs('teacher.leaderboard.page') ? 'active' : '' }}">
                <i class="ti ti-trophy nav-icon" aria-hidden="true"></i>
                <span class="nav-text">Leaderboard</span>
            </a>
            <div class="nav-label">Manage</div>
            <a href="{{ route('teacher.students') }}"
                class="nav-item {{ request()->routeIs('teacher.students') ? 'active' : '' }}">
                <i class="ti ti-users nav-icon" aria-hidden="true"></i>
                <span class="nav-text">Students</span>
            </a>
            <a href="{{ route('teacher.question-bank') }}"
                class="nav-item {{ request()->routeIs('teacher.question-bank') ? 'active' : '' }}">
                <i class="ti ti-database nav-icon" aria-hidden="true"></i>
                <span class="nav-text">Question Bank</span>
            </a>
            <a href="{{ route('teacher.ai-assistant') }}"
                class="nav-item {{ request()->routeIs('teacher.ai-assistant') ? 'active' : '' }}">
                <i class="ti ti-brain nav-icon" aria-hidden="true"></i>
                <span class="nav-text">AI Assistant</span>
            </a>
        </nav>
        <div class="sidebar-bottom">
            <a href="{{ route('teacher.settings') }}"
                class="nav-item {{ request()->routeIs('teacher.settings') ? 'active' : '' }}">
                <i class="ti ti-settings nav-icon" aria-hidden="true"></i>
                <span class="nav-text">Settings</span>
            </a>
        </div>
    </aside>

    <button class="toggle-btn" id="toggleBtn" aria-label="Toggle sidebar">
        <i class="ti ti-chevron-left" id="toggleIcon" aria-hidden="true"></i>
    </button>

    {{-- MAIN --}}
    <main class="main" id="main">
        <header class="topbar">
            <div>
                <div class="page-heading">
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                    <p class="page-subtitle">@yield('page-subtitle')</p>
                </div>
            </div>
            <div class="topbar-right">
                <div style="position:relative;">
                    <button class="notif-btn" id="notifBtn" aria-label="Notifications">
                        <i class="ti ti-bell" aria-hidden="true"></i>
                        <span class="notif-dot" id="notifDot" style="display:none;"></span>
                    </button>
                    <div id="notifDropdown" class="notif-dropdown">
                        <div class="notif-dropdown-header">
                            <div>
                                <span class="notif-dropdown-title">Notifications</span>
                                <span class="notif-unread-badge" id="notifUnreadBadge" style="display:none;"></span>
                            </div>
                            <button onclick="markAllNotifsRead()" class="notif-mark-all-btn">
                                Mark all read
                            </button>
                        </div>
                        <div id="notifList" class="notif-list-wrap"></div>
                    </div>
                </div>
                <div class="user-btn" id="userBtn" role="button" tabindex="0">
                    <div class="user-avatar" style="overflow:hidden;">
                        @if(auth()->user()->hasAvatar())
                        <img src="{{ auth()->user()->avatarUrl() }}" alt=""
                            style="width:100%;height:100%;object-fit:cover;display:block;">
                        @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">Teacher</div>
                    </div>
                    <i class="ti ti-chevron-down user-chevron" aria-hidden="true"></i>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="#" class="dropdown-item"><i class="ti ti-user" aria-hidden="true"></i> Profile</a>
                        <a href="{{ route('teacher.settings') }}" class="dropdown-item"><i class="ti ti-settings" aria-hidden="true"></i> Settings</a>
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
            @yield('content')
        </div>
        {{-- Announcement Modal --}}
        <div id="annModal" style="display:none; position:fixed; inset:0; z-index:9999; align-items:center; justify-content:center;">
            <div id="annModalBackdrop" style="position:absolute; inset:0; background:rgba(0,0,0,0.55); backdrop-filter:blur(8px);"></div>
            <div style="position:relative; z-index:1; width:min(480px, 92vw); background:var(--color-bg-card); border:1px solid var(--color-border-light); border-radius:16px; padding:28px; box-shadow:0 24px 64px rgba(0,0,0,0.5);">
                <button id="annModalClose" style="position:absolute; top:14px; right:14px; width:32px; height:32px; border-radius:8px; border:1px solid var(--color-border-light); background:transparent; color:var(--color-text-muted); cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:18px;">
                    <i class="ti ti-x"></i>
                </button>
                <div id="annModalBadge" style="display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:700; padding:4px 10px; border-radius:20px; margin-bottom:12px;"></div>
                <h3 id="annModalTitle" style="font-size:18px; font-weight:700; color:#fff; margin-bottom:10px; padding-right:28px;"></h3>
                <p id="annModalBody" style="font-size:14px; color:var(--color-text-secondary); line-height:1.6; white-space:pre-wrap;"></p>
            </div>
        </div>
    </main>

    {{-- SHARED JS --}}
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const toggleIcon = document.getElementById('toggleIcon');

        if (localStorage.getItem('sidebarCollapsed') === '1') {
            sidebar.classList.add('collapsed');
            document.body.classList.add('collapsed');
            if (toggleIcon) toggleIcon.className = 'ti ti-chevron-right';
        }

        toggleBtn.addEventListener('click', () => {
            const collapsed = sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('collapsed', collapsed);
            if (toggleIcon) {
                toggleIcon.className = collapsed ? 'ti ti-chevron-right' : 'ti ti-chevron-left';
            }
            localStorage.setItem('sidebarCollapsed', collapsed ? '1' : '0');
        });

        const userBtn = document.getElementById('userBtn');
        const userDropdown = document.getElementById('userDropdown');
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('open');
        });
        document.addEventListener('click', () => userDropdown.classList.remove('open'));
        const notifBtn = document.getElementById('notifBtn');
        const notifDropdown = document.getElementById('notifDropdown');
        const notifDot = document.getElementById('notifDot');
        const notifList = document.getElementById('notifList');

        function timeAgo(dateStr) {
            const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
            if (diff < 60) return 'just now';
            if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
            if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
            return Math.floor(diff / 86400) + 'd ago';
        }

        const notifStyles = {
            new_submission: {
                icon: 'ti-clipboard-check',
                bg: 'rgba(79,70,229,0.18)',
                color: '#818CF8'
            },
            milestone: {
                icon: 'ti-trophy',
                bg: 'rgba(245,158,11,0.18)',
                color: '#F59E0B'
            },
            low_score: {
                icon: 'ti-alert-triangle',
                bg: 'rgba(248,113,113,0.18)',
                color: '#F87171'
            },
            perfect_score: {
                icon: 'ti-star',
                bg: 'rgba(52,211,153,0.18)',
                color: '#34D399'
            },
            new_quiz: {
                icon: 'ti-sparkles',
                bg: 'rgba(34,211,238,0.18)',
                color: '#22D3EE'
            },
            new_quiz_followed_teacher: {
                icon: 'ti-user-star',
                bg: 'rgba(129,140,248,0.18)',
                color: '#A78BFA'
            },
            quiz_unlocked: {
                icon: 'ti-lock-open',
                bg: 'rgba(52,211,153,0.18)',
                color: '#34D399'
            },
            announcement: {
                icon: 'ti-speakerphone',
                bg: 'rgba(16,185,129,0.18)',
                color: '#34D399'
            }
        };

        function notifIconFor(type) {
            return notifStyles[type] || {
                icon: 'ti-bell',
                bg: 'rgba(129,140,248,0.15)',
                color: '#818CF8'
            };
        }

        function loadNotifications() {
            fetch("{{ route('notifications.index') }}")
                .then(r => r.json())
                .then(data => {
                    notifDot.style.display = data.unread_count > 0 ? 'block' : 'none';

                    const badge = document.getElementById('notifUnreadBadge');
                    if (data.unread_count > 0) {
                        badge.style.display = 'inline-flex';
                        badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                    } else {
                        badge.style.display = 'none';
                    }

                    if (data.notifications.length === 0) {
                        notifList.innerHTML = `
                    <div class="notif-empty">
                        <i class="ti ti-bell-off"></i>
                        <p>No notifications yet.</p>
                    </div>`;
                        return;
                    }

                    notifList.innerHTML = data.notifications.map(n => {
                        const style = notifIconFor(n.type);
                        const isAnn = n.type === 'announcement' || n.is_announcement;

                        if (isAnn) {
                            return `
                                <a href="#" class="notif-item ${n.read_at ? '' : 'unread'}"
                                data-ann-id="${n.announcement_id}"
                                data-ann-title="${n.title.replace(/"/g, '&quot;')}"
                                data-ann-body="${(n.body || '').replace(/"/g, '&quot;')}"
                                data-ann-type="${n.ann_type || 'info'}">
                                    <div class="notif-item-icon" style="background:${style.bg}; color:${style.color};">
                                        <i class="ti ${style.icon}"></i>
                                    </div>
                                    <div class="notif-item-body-wrap">
                                        <div class="notif-item-title">${n.title}</div>
                                        ${n.body ? `<div class="notif-item-body">${n.body.substring(0, 80)}${n.body.length > 80 ? '...' : ''}</div>` : ''}
                                        <div class="notif-item-time"><i class="ti ti-clock" style="font-size:10px;"></i> ${timeAgo(n.created_at)}</div>
                                    </div>
                                </a>`;
                        }

                        return `
                    <a href="${n.link || '#'}" class="notif-item ${!n.read_at ? 'unread' : ''}" onclick="markNotifRead(${n.id})">
                        <div class="notif-item-icon" style="background:${style.bg}; color:${style.color};">
                            <i class="ti ${style.icon}"></i>
                        </div>
                        <div class="notif-item-body-wrap">
                            <div class="notif-item-title">${n.title}</div>
                            ${n.body ? `<div class="notif-item-body">${n.body}</div>` : ''}
                            <div class="notif-item-time"><i class="ti ti-clock" style="font-size:10px;"></i> ${timeAgo(n.created_at)}</div>
                        </div>
                    </a>`;
                    }).join('');

                    //Bind announcement clicks
                    notifList.querySelectorAll('[data-ann-title]').forEach(el => {
                        el.addEventListener('click', function(e) {
                            e.preventDefault();
                            openAnnModal(this.dataset.annTitle, this.dataset.annBody, this.dataset.annType, this.dataset.annId);
                            notifDropdown.style.display = 'none';
                        });
                    });
                });
        }

        function openAnnModal(title, body, type, announcementId) {
            const modal = document.getElementById('annModal');
            const badge = document.getElementById('annModalBadge');
            const colors = {
                info: {
                    bg: 'rgba(59,130,246,0.15)',
                    color: '#60A5FA',
                    label: 'Info'
                },
                success: {
                    bg: 'rgba(16,185,129,0.15)',
                    color: '#34D399',
                    label: 'Success'
                },
                warning: {
                    bg: 'rgba(245,158,11,0.15)',
                    color: '#F59E0B',
                    label: 'Warning'
                },
            };
            const c = colors[type] || colors.info;

            badge.style.background = c.bg;
            badge.style.color = c.color;
            badge.textContent = c.label;

            document.getElementById('annModalTitle').textContent = title;
            document.getElementById('annModalBody').textContent = body;
            modal.style.display = 'flex';

            if (announcementId) {
                fetch(`/notifications/announcement/${announcementId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    }
                }).then(() => loadNotifications());
            }
        }

        function closeAnnModal() {
            document.getElementById('annModal').style.display = 'none';
        }

        document.getElementById('annModalClose')?.addEventListener('click', closeAnnModal);
        document.getElementById('annModalBackdrop')?.addEventListener('click', closeAnnModal);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeAnnModal();
        });

        function markNotifRead(id) {
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                }
            });
        }

        function markAllNotifsRead() {
            fetch("{{ route('notifications.read-all') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                }
            }).then(() => loadNotifications());
        }

        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = notifDropdown.style.display === 'block';
            notifDropdown.style.display = isOpen ? 'none' : 'block';
            if (!isOpen) loadNotifications();
        });

        document.addEventListener('click', () => notifDropdown.style.display = 'none');

        loadNotifications();
        setInterval(loadNotifications, 30000);
    </script>

    {{-- TOAST --}}
    @if(session('success'))
    <div id="toast" style="
    position:fixed; top:80px; left:50%; transform:translateX(-50%);
    background:rgba(52,211,153,0.15); border:1px solid rgba(52,211,153,0.4);
    color:#34D399; padding:12px 24px; border-radius:12px;
    font-size:13px; font-weight:600; display:flex; align-items:center; gap:10px;
    z-index:9999; backdrop-filter:blur(12px);
    box-shadow:0 8px 32px rgba(0,0,0,0.3);
    transition:opacity 0.5s ease, transform 0.5s ease;">
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
    <script src="{{ asset('quizora.js') }}"></script>
    @stack('scripts')
</body>

</html>