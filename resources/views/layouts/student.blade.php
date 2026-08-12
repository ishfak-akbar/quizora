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
    <title>@yield('title', 'Quizora')</title>
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

        .quiz-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(268px, 1fr));
            gap: 18px;
        }

        .pquiz-card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 16px;
            transition: border-color 0.22s, transform 0.22s, box-shadow 0.22s;
            text-decoration: none;
            display: block;
            position: relative;
            overflow: hidden;
        }

        .pquiz-card:hover {
            border-color: rgba(79, 70, 229, 0.5);
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
        }

        .pquiz-banner {
            height: 96px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .pquiz-banner-icon {
            font-size: 38px;
            color: rgba(255, 255, 255, 0.9);
            position: relative;
            z-index: 1;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3));
        }

        .pquiz-banner-deco {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            pointer-events: none;
            overflow: hidden;
        }

        .pquiz-banner-deco.d1 {
            width: 80px;
            height: 80px;
            bottom: -24px;
            right: -20px;
        }

        .pquiz-banner-deco.d2 {
            width: 44px;
            height: 44px;
            top: -10px;
            left: 16px;
        }

        .pquiz-body {
            padding: 16px 18px 18px;
        }

        .pquiz-category {
            font-size: 10px;
            font-weight: 700;
            color: var(--color-primary-glow);
            text-transform: uppercase;
            letter-spacing: 0.7px;
            margin-bottom: 6px;
        }

        .pquiz-title {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .pquiz-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
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
            padding-top: 12px;
            border-top: 1px solid var(--color-border-light);
        }

        .diff-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .diff-easy {
            background: rgba(52, 211, 153, 0.15);
            color: #34D399;
        }

        .diff-medium {
            background: rgba(245, 158, 11, 0.15);
            color: #F59E0B;
        }

        .diff-hard {
            background: rgba(248, 113, 113, 0.15);
            color: #F87171;
        }

        .pquiz-take-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(79, 70, 229, 0.18);
            border: 1px solid rgba(79, 70, 229, 0.35);
            color: var(--color-primary-glow);
            font-size: 12px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.2s;
            font-family: var(--font);
        }

        .pquiz-take-btn:hover {
            background: rgba(79, 70, 229, 0.3);
            color: #fff;
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background:
                radial-gradient(ellipse 300px 400px at 50% 0%, rgba(99, 102, 241, 0.20) 0%, transparent 70%),
                linear-gradient(180deg, #1e1b45 0%, #141130 50%, #0e0b20 100%);
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

        .sidebar.collapsed .nav-text,
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

        /* TOPBAR */
        .topbar {
            height: 64px;
            background: rgba(14, 11, 32, 0.72);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--color-border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
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
        }

        .dropdown-divider {
            height: 1px;
            background: var(--color-border-light);
            margin: 4px 0;
        }

        /* SHARED COMPONENT STYLES */
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

        /* Professional Bookmark Button */
        .bookmark-btn {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            z-index: 20;
            transition: all 0.25s ease;
            cursor: pointer;
        }

        .bookmark-btn:hover {
            background: rgba(0, 0, 0, 0.7);
            transform: scale(1.08);
        }

        .bookmark-btn.bookmarked {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .bookmark-btn.bookmarked svg {
            stroke: #FFFFFF;
            fill: #FFFFFF;
        }

        .empty-state {
            text-align: center;
            padding: 90px 40px;
            color: var(--color-text-muted);
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 20px;
            margin-top: 40px;
        }

        .empty-icon {
            font-size: 68px;
            margin-bottom: 24px;
            color: rgba(79, 70, 229, 0.4);
            display: block;
        }

        .empty-state h3 {
            font-size: 20px;
            font-weight: 600;
            color: var(--color-text-secondary);
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 14.5px;
            max-width: 260px;
            margin: 0 auto 32px;
            line-height: 1.5;
        }

        /* Button */
        .empty-browse-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--color-primary-solid), #6366F1);
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            padding: 16px 40px;
            border-radius: 14px;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
        }

        .empty-browse-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.45);
            background: linear-gradient(135deg, #4338CA, #4F46E5);
        }

        .streak-badge {
            display: flex;
            align-items: center;
            height: 36px;
            gap: 5px;
            padding: 0 7px;
            border-radius: 10px;
            border: 1px solid rgba(245, 158, 11, 0.3);
            background: rgba(245, 158, 11, 0.1);
            color: #F59E0B;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .streak-badge i {
            font-size: 16px;
        }

        .notif-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 2px);
            right: 0;
            width: 340px;
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 16px;
            z-index: 200;
            box-shadow: 0 20px 48px rgba(0, 0, 0, 0.45);
            overflow: hidden;
        }

        .notif-dropdown-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 18px;
            background: linear-gradient(135deg, #2E2570 0%, #4F46E5 60%, #6366F1 100%);
            position: relative;
            overflow: hidden;
        }

        .notif-dropdown-header::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -20px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }

        .notif-dropdown-title {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            position: relative;
            z-index: 1;
        }

        .notif-unread-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            margin-left: 8px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.22);
            color: #fff;
            font-size: 10.5px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .notif-mark-all-btn {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-family: var(--font);
            transition: background 0.2s;
            position: relative;
            z-index: 1;
        }

        .notif-mark-all-btn:hover {
            background: rgba(255, 255, 255, 0.28);
        }

        .notif-list-wrap {
            max-height: 380px;
            overflow-y: auto;
        }

        .notif-list-wrap::-webkit-scrollbar {
            width: 4px;
        }

        .notif-list-wrap::-webkit-scrollbar-track {
            background: transparent;
        }

        .notif-list-wrap::-webkit-scrollbar-thumb {
            background: rgba(129, 140, 248, 0.25);
            border-radius: 2px;
        }

        .notif-item {
            display: flex;
            gap: 12px;
            padding: 14px 18px;
            border-bottom: 1px solid var(--color-border-light);
            text-decoration: none;
            transition: background 0.15s;
            position: relative;
        }

        .notif-item:last-child {
            border-bottom: none;
        }

        .notif-item:hover {
            background: var(--color-bg-row-hover);
        }

        .notif-item.unread {
            background: rgba(79, 70, 229, 0.07);
        }

        .notif-item.unread::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--color-primary-solid);
        }

        .notif-item-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .notif-item-body-wrap {
            flex: 1;
            min-width: 0;
        }

        .notif-item-title {
            font-size: 11px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 3px;
            line-height: 1.4;
        }

        .notif-item-body {
            font-size: 11.5px;
            color: var(--color-text-muted);
            line-height: 1.4;
        }

        .notif-item-time {
            font-size: 10.5px;
            color: var(--color-text-muted);
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .notif-empty {
            padding: 48px 20px;
            text-align: center;
        }

        .notif-empty i {
            font-size: 36px;
            color: rgba(79, 70, 229, 0.3);
            display: block;
            margin-bottom: 12px;
        }

        .notif-empty p {
            font-size: 12.5px;
            color: var(--color-text-muted);
        }
    </style>
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
            <a href="{{ route('student.dashboard') }}"
                class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <i class="ti ti-layout-dashboard nav-icon"></i>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="{{ route('student.browse') }}"
                class="nav-item {{ request()->routeIs('student.browse') ? 'active' : '' }}">
                <i class="ti ti-compass nav-icon"></i>
                <span class="nav-text">Browse Quizzes</span>
            </a>
            <a href="{{ route('student.private-quizzes') }}"
                class="nav-item {{ request()->routeIs('student.private-quizzes') ? 'active' : '' }}">
                <i class="ti ti-lock nav-icon" aria-hidden="true"></i>
                <span class="nav-text">Private Quizzes</span>
            </a>
            <a href="{{ route('student.bookmarks') }}"
                class="nav-item {{ request()->routeIs('student.bookmarks') ? 'active' : '' }}">
                <i class="ti ti-bookmark nav-icon"></i>
                <span class="nav-text">Bookmarks</span>
            </a>
            <div class="nav-label">Activity</div>
            <a href="{{ route('student.results') }}"
                class="nav-item {{ request()->routeIs('student.results') ? 'active' : '' }}">
                <i class="ti ti-history nav-icon"></i>
                <span class="nav-text">My Results</span>
            </a>
            <a href="{{ route('student.leaderboard.page') }}"
                class="nav-item {{ request()->routeIs('student.leaderboard.page') ? 'active' : '' }}">
                <i class="ti ti-trophy nav-icon"></i>
                <span class="nav-text">Leaderboard</span>
            </a>
            <a href="{{ route('student.ai-tutor') }}"
                class="nav-item {{ request()->routeIs('student.ai-tutor') ? 'active' : '' }}">
                <i class="ti ti-brain nav-icon"></i>
                <span class="nav-text">AI Tutor</span>
            </a>
        </nav>
        <div class="sidebar-bottom">
            <a href="{{ route('student.settings') }}"
                class="nav-item {{ request()->routeIs('student.settings') ? 'active' : '' }}">
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
            @php
            $hour = now()->hour;
            $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening' );
                @endphp
                <div>
                <div class="topbar-title">{{ $greeting }}, {{ auth()->user()->name }}</div>
                <div class="topbar-sub">{{ now()->format('l, F j, Y') }}</div>
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
                    <div class="streak-badge" title="{{ \App\Http\Controllers\Student\DashboardController::getCachedStreak(auth()->id()) }} day streak">
                        <i class="ti ti-flame" aria-hidden="true"></i>
                        <span>{{ \App\Http\Controllers\Student\DashboardController::getCachedStreak(auth()->id()) }}</span>
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
                            <div class="user-role">Student</div>
                        </div>
                        <i class="ti ti-chevron-down user-chevron" aria-hidden="true"></i>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="{{ route('student.profile') }}" class="dropdown-item"><i class="ti ti-user" aria-hidden="true"></i> Profile</a>
                            <a href="{{ route('student.settings') }}" class="dropdown-item"><i class="ti ti-settings" aria-hidden="true"></i> Settings</a>
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

        // NOTIFICATIONS
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
            },
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            });
        }

        function markAllNotifsRead() {
            fetch("{{ route('notifications.read-all') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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
    <script>
        function toggleBookmark(e, btn, quizId) {
            e.preventDefault();
            e.stopPropagation();

            fetch(`/student/bookmarks/${quizId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.bookmarked) {
                        btn.classList.add('bookmarked');
                        btn.title = 'Remove bookmark';
                    } else {
                        btn.classList.remove('bookmarked');
                        btn.title = 'Bookmark this quiz';
                    }

                    // Update all cards with same quiz
                    document.querySelectorAll(`.bookmark-btn[data-quiz-id="${quizId}"]`).forEach(b => {
                        if (b !== btn) {
                            if (data.bookmarked) {
                                b.classList.add('bookmarked');
                            } else {
                                b.classList.remove('bookmarked');
                            }
                        }
                    });
                })
                .catch(() => {
                    alert('Failed to update bookmark. Please try again.');
                });
        }
    </script>
    @stack('scripts')
</body>

</html>