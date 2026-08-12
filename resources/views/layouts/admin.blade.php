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
    <link rel="stylesheet" href="{{ asset('admin.css') }}">
    <title>@yield('title', 'Quizora Admin')</title>
    @stack('styles')
</head>

<body>

    {{-- SIDEBAR (same structure as teacher) --}}
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo" style="text-decoration:none;">
            <div class="logo-icon">Q</div>
            <div class="logo-text">Quiz<span>ora</span></div>
        </a>
        <nav class="sidebar-nav">
            <div class="nav-label">Main</div>
            <a href="{{ route('admin.dashboard') }}"
                class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="ti ti-layout-dashboard nav-icon"></i>
                <span class="nav-text">Dashboard</span>
            </a>

            <div class="nav-label">Management</div>
            <a href="{{ route('admin.users.index') }}"
                class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="ti ti-users nav-icon"></i>
                <span class="nav-text">Users</span>
            </a>
            <a href="{{ route('admin.teachers.index') }}"
                class="nav-item {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                <i class="ti ti-school nav-icon"></i>
                <span class="nav-text">Teachers</span>
            </a>
            <a href="{{ route('admin.students.index') }}"
                class="nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                <i class="ti ti-user nav-icon"></i>
                <span class="nav-text">Students</span>
            </a>
            <a href="{{ route('admin.quizzes.index') }}"
                class="nav-item {{ request()->routeIs('admin.quizzes.*') ? 'active' : '' }}">
                <i class="ti ti-file-description nav-icon"></i>
                <span class="nav-text">Quizzes</span>
            </a>

            <div class="nav-label">System</div>
            <a href="{{ route('admin.announcements.index') }}"
                class="nav-item {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                <i class="ti ti-speakerphone nav-icon"></i>
                <span class="nav-text">Announcements</span>
            </a>
        </nav>
        <div class="sidebar-bottom">
            <a href="{{ route('admin.settings') }}" class="nav-item">
                <i class="ti ti-settings nav-icon"></i>
                <span class="nav-text">Settings</span>
            </a>
        </div>
    </aside>

    <button class="toggle-btn" id="toggleBtn" aria-label="Toggle sidebar">
        <i class="ti ti-chevron-left" id="toggleIcon"></i>
    </button>

    {{-- MAIN --}}
    <main class="main" id="main">
        <header class="topbar">
            <div>
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                <div class="topbar-sub">@yield('page-subtitle', 'Welcome back, ' . auth()->user()->name)</div>
            </div>
            <div class="topbar-right">
                <div class="user-btn" id="userBtn">
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
                        <div class="user-role">Super Admin</div>
                    </div>
                    <i class="ti ti-chevron-down user-chevron"></i>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="{{ route('admin.settings') }}" class="dropdown-item">
                            <i class="ti ti-settings"></i> Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item danger" style="width:100%;border:none;text-align:left;">
                                <i class="ti ti-logout"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="content">
            @yield('content')
        </div>
    </main>

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
    </script>

    @stack('scripts')
</body>

</html>