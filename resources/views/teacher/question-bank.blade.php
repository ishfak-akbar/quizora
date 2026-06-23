<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('quizora.css') }}">
    <title>Quizora — Question Bank</title>
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

        *,
        *::before,
        *::after {
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
            padding: 10px;
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

        /* CONTENT */
        .content {
            padding: 28px;
            flex: 1;
        }

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

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 38px;
            padding: 0 18px;
            background: var(--color-primary-solid);
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            font-family: var(--font);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            text-decoration: none;
        }

        .btn-primary:hover {
            background: #4338CA;
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        /* STATS ROW */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 14px;
            padding: 18px 20px;
            position: relative;
            overflow: hidden;
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
            background: linear-gradient(90deg, #4F46E5, #A78BFA);
        }

        .stat-card.cyan::before {
            background: linear-gradient(90deg, #0891B2, #22D3EE);
        }

        .stat-card.green::before {
            background: linear-gradient(90deg, #059669, #34D399);
        }

        .stat-card.amber::before {
            background: linear-gradient(90deg, #D97706, #F59E0B);
        }

        .stat-value {
            font-size: 26px;
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

        /* FILTERS */
        .filters {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1.5px solid var(--color-border-light);
            border-radius: 10px;
            padding: 0 14px;
            flex: 1;
            max-width: 380px;
            transition: border-color 0.2s;
        }

        .search-wrap:focus-within {
            border-color: rgba(79, 70, 229, 0.6);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .search-wrap input {
            flex: 1;
            height: 38px;
            background: none;
            border: none;
            outline: none;
            color: #fff;
            font-size: 13px;
            font-family: var(--font);
        }

        .search-wrap input::placeholder {
            color: var(--color-text-muted);
        }

        .search-wrap i {
            color: var(--color-text-muted);
            font-size: 16px;
        }

        .filter-select {
            height: 38px;
            padding: 0 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1.5px solid var(--color-border-light);
            border-radius: 10px;
            color: var(--color-text-secondary);
            font-size: 13px;
            font-family: var(--font);
            cursor: pointer;
            outline: none;
            transition: border-color 0.2s;
        }

        .filter-select:focus {
            border-color: rgba(79, 70, 229, 0.6);
        }

        .filter-select option {
            background: #1E1A3E;
        }

        .filter-btn {
            height: 38px;
            padding: 0 16px;
            border-radius: 10px;
            border: 1.5px solid var(--color-border-light);
            background: transparent;
            color: var(--color-text-secondary);
            font-size: 13px;
            font-family: var(--font);
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: rgba(79, 70, 229, 0.2);
            border-color: rgba(79, 70, 229, 0.5);
            color: var(--color-primary-glow);
            font-weight: 600;
        }

        /* TABLE */
        .table-card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 14px;
            overflow: hidden;
        }

        .table-card table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-card th {
            padding: 11px 20px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: var(--color-text-muted);
            letter-spacing: 0.8px;
            text-transform: uppercase;
            border-bottom: 1px solid var(--color-border-light);
            background: rgba(255, 255, 255, 0.02);
        }

        .table-card th input[type="checkbox"],
        .table-card td input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: var(--color-primary-solid);
            cursor: pointer;
        }

        .table-card td {
            padding: 14px 20px;
            font-size: 13px;
            color: var(--color-text-secondary);
            border-bottom: 1px solid var(--color-border-light);
            vertical-align: middle;
        }

        .table-card tr:last-child td {
            border-bottom: none;
        }

        .table-card tr:hover td {
            background: var(--color-bg-row-hover);
        }

        .q-text {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            max-width: 380px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .q-options {
            font-size: 11px;
            color: var(--color-text-muted);
            margin-top: 3px;
        }

        /* TYPE BADGES */
        .type-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            border: 1.5px solid;
        }

        .type-mcq {
            color: #818CF8;
            border-color: rgba(129, 140, 248, 0.35);
            background: rgba(129, 140, 248, 0.1);
        }

        .type-tf {
            color: #22D3EE;
            border-color: rgba(34, 211, 238, 0.35);
            background: rgba(34, 211, 238, 0.1);
        }

        .type-short {
            color: #F59E0B;
            border-color: rgba(245, 158, 11, 0.35);
            background: rgba(245, 158, 11, 0.1);
        }

        /* POINTS PILL */
        .points-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 700;
            color: var(--color-stat-purple);
        }

        /* ACTION BTNS */
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
            display: inline-flex;
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

        .action-btn.danger:hover {
            background: rgba(248, 113, 113, 0.12);
            color: #F87171;
            border-color: rgba(248, 113, 113, 0.35);
        }

        /* BULK ACTION BAR */
        .bulk-bar {
            display: none;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            background: rgba(79, 70, 229, 0.12);
            border-bottom: 1px solid rgba(79, 70, 229, 0.25);
        }

        .bulk-bar.visible {
            display: flex;
        }

        .bulk-count {
            font-size: 13px;
            color: var(--color-primary-glow);
            font-weight: 600;
        }

        .btn-danger-sm {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            height: 30px;
            padding: 0 14px;
            background: rgba(248, 113, 113, 0.15);
            color: #F87171;
            font-size: 12px;
            font-weight: 600;
            font-family: var(--font);
            border: 1px solid rgba(248, 113, 113, 0.3);
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-danger-sm:hover {
            background: rgba(248, 113, 113, 0.25);
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--color-text-muted);
        }

        .empty-state i {
            font-size: 40px;
            margin-bottom: 12px;
            opacity: 0.4;
            display: block;
        }

        .empty-state p {
            font-size: 14px;
        }

        /* MODAL OVERLAY */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 500;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal {
            background: #1E1A3E;
            border: 1px solid var(--color-border-light);
            border-radius: 20px;
            padding: 32px;
            width: 100%;
            max-width: 560px;
            position: relative;
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.6);
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .modal-title {
            font-size: 17px;
            font-weight: 700;
            color: #fff;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--color-border-light);
            background: transparent;
            color: var(--color-text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.15s, color 0.15s;
        }

        .modal-close:hover {
            background: var(--color-bg-row-hover);
            color: #fff;
        }

        /* FORM */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--color-text-muted);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 7px;
            display: block;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            background: rgba(255, 255, 255, 0.04);
            border: 1.5px solid var(--color-border-light);
            border-radius: 10px;
            color: #fff;
            font-size: 13px;
            font-family: var(--font);
            padding: 10px 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: rgba(79, 70, 229, 0.6);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .form-select option {
            background: #1E1A3E;
        }

        .form-input::placeholder,
        .form-textarea::placeholder {
            color: var(--color-text-muted);
        }

        /* OPTIONS BUILDER */
        .options-builder {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .option-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .option-row input[type="radio"] {
            accent-color: var(--color-primary-solid);
            width: 15px;
            height: 15px;
            flex-shrink: 0;
            cursor: pointer;
        }

        .option-row .form-input {
            flex: 1;
            margin: 0;
        }

        .option-remove {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            border: 1px solid var(--color-border-light);
            background: transparent;
            color: var(--color-text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            flex-shrink: 0;
            transition: background 0.15s, color 0.15s;
        }

        .option-remove:hover {
            background: rgba(248, 113, 113, 0.12);
            color: #F87171;
        }

        .add-option-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: none;
            border: 1.5px dashed var(--color-border-light);
            border-radius: 9px;
            padding: 8px 14px;
            color: var(--color-text-muted);
            font-size: 12px;
            font-family: var(--font);
            cursor: pointer;
            transition: border-color 0.2s, color 0.2s;
            width: 100%;
            margin-top: 4px;
        }

        .add-option-btn:hover {
            border-color: rgba(79, 70, 229, 0.5);
            color: var(--color-primary-glow);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .modal-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--color-border-light);
        }

        .btn-secondary {
            height: 38px;
            padding: 0 18px;
            background: transparent;
            border: 1.5px solid var(--color-border-light);
            border-radius: 10px;
            color: var(--color-text-secondary);
            font-size: 13px;
            font-weight: 600;
            font-family: var(--font);
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }

        .btn-secondary:hover {
            background: var(--color-bg-row-hover);
            color: #fff;
        }

        /* DELETE CONFIRM MODAL */
        .delete-modal {
            max-width: 400px;
            text-align: center;
        }

        .delete-icon {
            font-size: 44px;
            color: #F87171;
            margin-bottom: 14px;
        }

        .delete-title {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
        }

        .delete-desc {
            font-size: 13px;
            color: var(--color-text-muted);
            line-height: 1.6;
        }

        .delete-footer {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 24px;
        }

        .btn-danger {
            height: 38px;
            padding: 0 22px;
            background: rgba(248, 113, 113, 0.15);
            border: 1.5px solid rgba(248, 113, 113, 0.4);
            border-radius: 10px;
            color: #F87171;
            font-size: 13px;
            font-weight: 600;
            font-family: var(--font);
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-danger:hover {
            background: rgba(248, 113, 113, 0.28);
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
            <a href="{{ route('teacher.leaderboard.page') }}" class="nav-item">
                <i class="ti ti-trophy nav-icon"></i>
                <span class="nav-text">Leaderboard</span>
            </a>
            <div class="nav-label">Manage</div>
            <a href="{{ route('teacher.students') }}" class="nav-item">
                <i class="ti ti-users nav-icon"></i>
                <span class="nav-text">Students</span>
            </a>
            <a href="{{ route('teacher.question-bank') }}" class="nav-item active">
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
            <div class="topbar-title">Question Bank</div>
            <div class="topbar-right">
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
                            <button type="submit" class="dropdown-item danger" style="width:100%;border:none;text-align:left;">
                                <i class="ti ti-logout"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <div class="content">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h1>Question Bank</h1>
                    <p>All questions across your quizzes in one place</p>
                </div>
                <button class="btn-primary" onclick="openAddModal()">
                    <i class="ti ti-plus"></i> Add Question
                </button>
            </div>

            <!-- STATS -->
            <div class="stats-row">
                <div class="stat-card purple">
                    <div class="stat-value">48</div>
                    <div class="stat-label">Total Questions</div>
                </div>
                <div class="stat-card cyan">
                    <div class="stat-value">31</div>
                    <div class="stat-label">MCQ</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-value">10</div>
                    <div class="stat-label">True / False</div>
                </div>
                <div class="stat-card amber">
                    <div class="stat-value">7</div>
                    <div class="stat-label">Short Answer</div>
                </div>
            </div>

            <!-- FILTERS -->
            <div class="filters">
                <div class="search-wrap">
                    <i class="ti ti-search"></i>
                    <input type="text" id="searchInput" placeholder="Search questions...">
                </div>
                <select class="filter-select" id="quizFilter" onchange="applyFilters()">
                    <option value="all">All Quizzes</option>
                    <option value="biology">Biology Basics</option>
                    <option value="math">Math Fundamentals</option>
                    <option value="history">World History</option>
                    <option value="physics">Physics Quiz</option>
                    <option value="cs">Intro to CS</option>
                </select>
                <select class="filter-select" id="typeFilter" onchange="applyFilters()">
                    <option value="all">All Types</option>
                    <option value="mcq">MCQ</option>
                    <option value="tf">True / False</option>
                    <option value="short">Short Answer</option>
                </select>
            </div>

            <!-- TABLE -->
            <div class="table-card">

                <!-- BULK ACTION BAR -->
                <div class="bulk-bar" id="bulkBar">
                    <span class="bulk-count" id="bulkCount">0 selected</span>
                    <button class="btn-danger-sm" onclick="openBulkDelete()">
                        <i class="ti ti-trash"></i> Delete Selected
                    </button>
                    <button class="btn-secondary" style="height:30px;padding:0 12px;font-size:12px;" onclick="clearSelection()">
                        Cancel
                    </button>
                </div>

                <table id="questionsTable">
                    <thead>
                        <tr>
                            <th style="width:40px;"><input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)"></th>
                            <th>Question</th>
                            <th>Type</th>
                            <th>Quiz</th>
                            <th>Points</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="questionsBody">

                        <!-- ROW: MCQ -->
                        <tr data-type="mcq" data-quiz="biology" data-q="What is the powerhouse of the cell?">
                            <td><input type="checkbox" class="row-check" onchange="updateBulkBar()"></td>
                            <td>
                                <div class="q-text">What is the powerhouse of the cell?</div>
                                <div class="q-options">Mitochondria · Nucleus · Ribosome · Golgi Apparatus</div>
                            </td>
                            <td><span class="type-badge type-mcq"><i class="ti ti-list-check"></i> MCQ</span></td>
                            <td style="color:#fff;font-size:12px;">Biology Basics</td>
                            <td><span class="points-pill"><i class="ti ti-star-filled" style="font-size:11px;"></i> 5</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn" title="Edit" onclick="openEditModal(this)"><i class="ti ti-pencil"></i></button>
                                    <button class="action-btn danger" title="Delete" onclick="openDeleteModal(this)"><i class="ti ti-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                        <!-- ROW: True/False -->
                        <tr data-type="tf" data-quiz="biology" data-q="Photosynthesis occurs in the mitochondria.">
                            <td><input type="checkbox" class="row-check" onchange="updateBulkBar()"></td>
                            <td>
                                <div class="q-text">Photosynthesis occurs in the mitochondria.</div>
                                <div class="q-options">True · False</div>
                            </td>
                            <td><span class="type-badge type-tf"><i class="ti ti-toggle-left"></i> True/False</span></td>
                            <td style="color:#fff;font-size:12px;">Biology Basics</td>
                            <td><span class="points-pill"><i class="ti ti-star-filled" style="font-size:11px;"></i> 2</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn" title="Edit" onclick="openEditModal(this)"><i class="ti ti-pencil"></i></button>
                                    <button class="action-btn danger" title="Delete" onclick="openDeleteModal(this)"><i class="ti ti-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                        <!-- ROW: MCQ -->
                        <tr data-type="mcq" data-quiz="math" data-q="What is the derivative of x²?">
                            <td><input type="checkbox" class="row-check" onchange="updateBulkBar()"></td>
                            <td>
                                <div class="q-text">What is the derivative of x²?</div>
                                <div class="q-options">2x · x² · 2 · x</div>
                            </td>
                            <td><span class="type-badge type-mcq"><i class="ti ti-list-check"></i> MCQ</span></td>
                            <td style="color:#fff;font-size:12px;">Math Fundamentals</td>
                            <td><span class="points-pill"><i class="ti ti-star-filled" style="font-size:11px;"></i> 5</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn" title="Edit" onclick="openEditModal(this)"><i class="ti ti-pencil"></i></button>
                                    <button class="action-btn danger" title="Delete" onclick="openDeleteModal(this)"><i class="ti ti-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                        <!-- ROW: Short Answer -->
                        <tr data-type="short" data-quiz="history" data-q="Who was the first President of the United States?">
                            <td><input type="checkbox" class="row-check" onchange="updateBulkBar()"></td>
                            <td>
                                <div class="q-text">Who was the first President of the United States?</div>
                                <div class="q-options">Short answer</div>
                            </td>
                            <td><span class="type-badge type-short"><i class="ti ti-writing"></i> Short Answer</span></td>
                            <td style="color:#fff;font-size:12px;">World History</td>
                            <td><span class="points-pill"><i class="ti ti-star-filled" style="font-size:11px;"></i> 10</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn" title="Edit" onclick="openEditModal(this)"><i class="ti ti-pencil"></i></button>
                                    <button class="action-btn danger" title="Delete" onclick="openDeleteModal(this)"><i class="ti ti-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                        <!-- ROW: True/False -->
                        <tr data-type="tf" data-quiz="physics" data-q="The speed of light is approximately 3×10⁸ m/s.">
                            <td><input type="checkbox" class="row-check" onchange="updateBulkBar()"></td>
                            <td>
                                <div class="q-text">The speed of light is approximately 3×10⁸ m/s.</div>
                                <div class="q-options">True · False</div>
                            </td>
                            <td><span class="type-badge type-tf"><i class="ti ti-toggle-left"></i> True/False</span></td>
                            <td style="color:#fff;font-size:12px;">Physics Quiz</td>
                            <td><span class="points-pill"><i class="ti ti-star-filled" style="font-size:11px;"></i> 2</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn" title="Edit" onclick="openEditModal(this)"><i class="ti ti-pencil"></i></button>
                                    <button class="action-btn danger" title="Delete" onclick="openDeleteModal(this)"><i class="ti ti-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                        <!-- ROW: MCQ -->
                        <tr data-type="mcq" data-quiz="cs" data-q="Which data structure uses LIFO order?">
                            <td><input type="checkbox" class="row-check" onchange="updateBulkBar()"></td>
                            <td>
                                <div class="q-text">Which data structure uses LIFO order?</div>
                                <div class="q-options">Stack · Queue · Linked List · Array</div>
                            </td>
                            <td><span class="type-badge type-mcq"><i class="ti ti-list-check"></i> MCQ</span></td>
                            <td style="color:#fff;font-size:12px;">Intro to CS</td>
                            <td><span class="points-pill"><i class="ti ti-star-filled" style="font-size:11px;"></i> 5</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn" title="Edit" onclick="openEditModal(this)"><i class="ti ti-pencil"></i></button>
                                    <button class="action-btn danger" title="Delete" onclick="openDeleteModal(this)"><i class="ti ti-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                        <!-- ROW: Short Answer -->
                        <tr data-type="short" data-quiz="math" data-q="Explain the difference between permutation and combination.">
                            <td><input type="checkbox" class="row-check" onchange="updateBulkBar()"></td>
                            <td>
                                <div class="q-text">Explain the difference between permutation and combination.</div>
                                <div class="q-options">Short answer</div>
                            </td>
                            <td><span class="type-badge type-short"><i class="ti ti-writing"></i> Short Answer</span></td>
                            <td style="color:#fff;font-size:12px;">Math Fundamentals</td>
                            <td><span class="points-pill"><i class="ti ti-star-filled" style="font-size:11px;"></i> 10</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn" title="Edit" onclick="openEditModal(this)"><i class="ti ti-pencil"></i></button>
                                    <button class="action-btn danger" title="Delete" onclick="openDeleteModal(this)"><i class="ti ti-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                        <!-- ROW: MCQ -->
                        <tr data-type="mcq" data-quiz="history" data-q="In which year did World War II end?">
                            <td><input type="checkbox" class="row-check" onchange="updateBulkBar()"></td>
                            <td>
                                <div class="q-text">In which year did World War II end?</div>
                                <div class="q-options">1945 · 1939 · 1941 · 1950</div>
                            </td>
                            <td><span class="type-badge type-mcq"><i class="ti ti-list-check"></i> MCQ</span></td>
                            <td style="color:#fff;font-size:12px;">World History</td>
                            <td><span class="points-pill"><i class="ti ti-star-filled" style="font-size:11px;"></i> 5</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn" title="Edit" onclick="openEditModal(this)"><i class="ti ti-pencil"></i></button>
                                    <button class="action-btn danger" title="Delete" onclick="openDeleteModal(this)"><i class="ti ti-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>

                <!-- EMPTY STATE (hidden by default) -->
                <div class="empty-state" id="emptyState" style="display:none;">
                    <i class="ti ti-database-off"></i>
                    <p>No questions match your filters.</p>
                </div>

            </div><!-- /table-card -->
        </div><!-- /content -->
    </main>
    <!-- ADD / EDIT QUESTION MODAL -->
    <div class="modal-overlay" id="questionModal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title" id="modalTitle">Add Question</div>
                <button class="modal-close" onclick="closeModal('questionModal')"><i class="ti ti-x"></i></button>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Question Type</label>
                    <select class="form-select" id="qType" onchange="onTypeChange()">
                        <option value="mcq">Multiple Choice (MCQ)</option>
                        <option value="tf">True / False</option>
                        <option value="short">Short Answer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Quiz</label>
                    <select class="form-select" id="qQuiz">
                        <option value="biology">Biology Basics</option>
                        <option value="math">Math Fundamentals</option>
                        <option value="history">World History</option>
                        <option value="physics">Physics Quiz</option>
                        <option value="cs">Intro to CS</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Question Text</label>
                <textarea class="form-textarea" id="qText" placeholder="Enter your question here..."></textarea>
            </div>

            <!-- MCQ OPTIONS -->
            <div id="mcqSection" class="form-group">
                <label class="form-label">Answer Options <span style="color:var(--color-text-muted);font-size:11px;">(select the correct one)</span></label>
                <div class="options-builder" id="optionsList">
                    <div class="option-row">
                        <input type="radio" name="correctAnswer" value="0" checked>
                        <input type="text" class="form-input" placeholder="Option A">
                        <button class="option-remove" onclick="removeOption(this)" title="Remove"><i class="ti ti-x"></i></button>
                    </div>
                    <div class="option-row">
                        <input type="radio" name="correctAnswer" value="1">
                        <input type="text" class="form-input" placeholder="Option B">
                        <button class="option-remove" onclick="removeOption(this)" title="Remove"><i class="ti ti-x"></i></button>
                    </div>
                    <div class="option-row">
                        <input type="radio" name="correctAnswer" value="2">
                        <input type="text" class="form-input" placeholder="Option C">
                        <button class="option-remove" onclick="removeOption(this)" title="Remove"><i class="ti ti-x"></i></button>
                    </div>
                    <div class="option-row">
                        <input type="radio" name="correctAnswer" value="3">
                        <input type="text" class="form-input" placeholder="Option D">
                        <button class="option-remove" onclick="removeOption(this)" title="Remove"><i class="ti ti-x"></i></button>
                    </div>
                </div>
                <button class="add-option-btn" onclick="addOption()"><i class="ti ti-plus"></i> Add Option</button>
            </div>

            <!-- TRUE/FALSE OPTIONS -->
            <div id="tfSection" class="form-group" style="display:none;">
                <label class="form-label">Correct Answer</label>
                <select class="form-select" id="tfAnswer">
                    <option value="true">True</option>
                    <option value="false">False</option>
                </select>
            </div>

            <!-- SHORT ANSWER NOTE -->
            <div id="shortSection" class="form-group" style="display:none;">
                <label class="form-label">Expected Answer (for reference)</label>
                <textarea class="form-textarea" id="shortAnswer" placeholder="Enter the expected answer or key points..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Points</label>
                    <input type="number" class="form-input" id="qPoints" value="5" min="1" max="100">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Difficulty</label>
                    <select class="form-select" id="qDifficulty">
                        <option value="easy">Easy</option>
                        <option value="medium" selected>Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal('questionModal')">Cancel</button>
                <button class="btn-primary" onclick="saveQuestion()"><i class="ti ti-check"></i> Save Question</button>
            </div>
        </div>
    </div>

    <!-- DELETE CONFIRM MODAL -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal delete-modal">
            <div class="delete-icon"><i class="ti ti-trash"></i></div>
            <div class="delete-title">Delete Question?</div>
            <div class="delete-desc" id="deleteDesc">This question will be permanently removed from the question bank and all associated quizzes.</div>
            <div class="delete-footer">
                <button class="btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
                <button class="btn-danger" onclick="confirmDelete()"><i class="ti ti-trash"></i> Delete</button>
            </div>
        </div>
    </div>
    <script>
        // ── SIDEBAR TOGGLE ──────────────────────────────────────────────
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const toggleIcon = document.getElementById('toggleIcon');

        toggleBtn.addEventListener('click', () => {
            document.body.classList.toggle('collapsed');
            sidebar.classList.toggle('collapsed');
            toggleIcon.className = sidebar.classList.contains('collapsed') ?
                'ti ti-chevron-right' :
                'ti ti-chevron-left';
        });

        // ── USER DROPDOWN ───────────────────────────────────────────────
        document.getElementById('userBtn').addEventListener('click', (e) => {
            e.stopPropagation();
            document.getElementById('userDropdown').classList.toggle('open');
        });

        document.addEventListener('click', () => {
            document.getElementById('userDropdown').classList.remove('open');
        });

        // ── MODAL HELPERS ───────────────────────────────────────────────
        function openModal(id) {
            document.getElementById(id).classList.add('open');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('open');
        }

        // Close on overlay click
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) overlay.classList.remove('open');
            });
        });

        // ── ADD QUESTION ────────────────────────────────────────────────
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add Question';
            document.getElementById('qText').value = '';
            document.getElementById('qPoints').value = '5';
            document.getElementById('qType').value = 'mcq';
            document.getElementById('qDifficulty').value = 'medium';
            resetOptions();
            onTypeChange();
            openModal('questionModal');
        }

        // ── EDIT QUESTION ───────────────────────────────────────────────
        function openEditModal(btn) {
            const row = btn.closest('tr');
            const qText = row.dataset.q;
            const type = row.dataset.type;
            const quiz = row.dataset.quiz;

            document.getElementById('modalTitle').textContent = 'Edit Question';
            document.getElementById('qText').value = qText;
            document.getElementById('qType').value = type;
            document.getElementById('qQuiz').value = quiz;
            document.getElementById('qPoints').value = '5';
            resetOptions();
            onTypeChange();
            openModal('questionModal');
        }

        // ── TYPE CHANGE ─────────────────────────────────────────────────
        function onTypeChange() {
            const type = document.getElementById('qType').value;
            document.getElementById('mcqSection').style.display = type === 'mcq' ? '' : 'none';
            document.getElementById('tfSection').style.display = type === 'tf' ? '' : 'none';
            document.getElementById('shortSection').style.display = type === 'short' ? '' : 'none';
        }

        // ── OPTIONS BUILDER ─────────────────────────────────────────────
        let optionCount = 4;

        function resetOptions() {
            optionCount = 4;
            document.getElementById('optionsList').innerHTML = `
                <div class="option-row">
                    <input type="radio" name="correctAnswer" value="0" checked>
                    <input type="text" class="form-input" placeholder="Option A">
                    <button class="option-remove" onclick="removeOption(this)" title="Remove"><i class="ti ti-x"></i></button>
                </div>
                <div class="option-row">
                    <input type="radio" name="correctAnswer" value="1">
                    <input type="text" class="form-input" placeholder="Option B">
                    <button class="option-remove" onclick="removeOption(this)" title="Remove"><i class="ti ti-x"></i></button>
                </div>
                <div class="option-row">
                    <input type="radio" name="correctAnswer" value="2">
                    <input type="text" class="form-input" placeholder="Option C">
                    <button class="option-remove" onclick="removeOption(this)" title="Remove"><i class="ti ti-x"></i></button>
                </div>
                <div class="option-row">
                    <input type="radio" name="correctAnswer" value="3">
                    <input type="text" class="form-input" placeholder="Option D">
                    <button class="option-remove" onclick="removeOption(this)" title="Remove"><i class="ti ti-x"></i></button>
                </div>`;
        }

        function addOption() {
            const labels = ['A', 'B', 'C', 'D', 'E', 'F'];
            const list = document.getElementById('optionsList');
            const idx = optionCount;
            const row = document.createElement('div');
            row.className = 'option-row';
            row.innerHTML = `
                <input type="radio" name="correctAnswer" value="${idx}">
                <input type="text" class="form-input" placeholder="Option ${labels[idx] || idx+1}">
                <button class="option-remove" onclick="removeOption(this)" title="Remove"><i class="ti ti-x"></i></button>`;
            list.appendChild(row);
            optionCount++;
        }

        function removeOption(btn) {
            const rows = document.querySelectorAll('#optionsList .option-row');
            if (rows.length <= 2) return; // min 2 options
            btn.closest('.option-row').remove();
        }

        // ── SAVE QUESTION (hardcoded: just closes modal) ────────────────
        function saveQuestion() {
            closeModal('questionModal');
        }

        // ── DELETE ──────────────────────────────────────────────────────
        let rowToDelete = null;

        function openDeleteModal(btn) {
            rowToDelete = btn.closest('tr');
            openModal('deleteModal');
        }

        function confirmDelete() {
            if (rowToDelete) {
                rowToDelete.remove();
                rowToDelete = null;
                clearSelection();
            }
            closeModal('deleteModal');
            checkEmpty();
        }

        // ── BULK DELETE ─────────────────────────────────────────────────
        function openBulkDelete() {
            document.getElementById('deleteDesc').textContent =
                'All selected questions will be permanently removed from the question bank.';
            rowToDelete = '__bulk__';
            openModal('deleteModal');
        }

        // Override confirmDelete for bulk
        const _originalConfirm = confirmDelete;
        window.confirmDelete = function() {
            if (rowToDelete === '__bulk__') {
                document.querySelectorAll('.row-check:checked').forEach(cb => cb.closest('tr').remove());
                clearSelection();
                closeModal('deleteModal');
                checkEmpty();
                document.getElementById('deleteDesc').textContent =
                    'This question will be permanently removed from the question bank and all associated quizzes.';
                rowToDelete = null;
            } else {
                if (rowToDelete) {
                    rowToDelete.remove();
                    rowToDelete = null;
                    clearSelection();
                }
                closeModal('deleteModal');
                checkEmpty();
            }
        };

        // ── BULK SELECTION ──────────────────────────────────────────────
        function toggleSelectAll(master) {
            document.querySelectorAll('.row-check').forEach(cb => {
                if (cb.closest('tr').style.display !== 'none') {
                    cb.checked = master.checked;
                }
            });
            updateBulkBar();
        }

        function updateBulkBar() {
            const checked = document.querySelectorAll('.row-check:checked').length;
            const bar = document.getElementById('bulkBar');
            bar.classList.toggle('visible', checked > 0);
            document.getElementById('bulkCount').textContent = `${checked} selected`;
        }

        function clearSelection() {
            document.querySelectorAll('.row-check').forEach(cb => cb.checked = false);
            document.getElementById('selectAll').checked = false;
            updateBulkBar();
        }

        // ── SEARCH + FILTERS ────────────────────────────────────────────
        document.getElementById('searchInput').addEventListener('input', applyFilters);

        function applyFilters() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            const quiz = document.getElementById('quizFilter').value;
            const type = document.getElementById('typeFilter').value;

            document.querySelectorAll('#questionsBody tr').forEach(row => {
                const text = (row.dataset.q || '').toLowerCase();
                const rowQuiz = row.dataset.quiz;
                const rowType = row.dataset.type;

                const matchSearch = !q || text.includes(q);
                const matchQuiz = quiz === 'all' || rowQuiz === quiz;
                const matchType = type === 'all' || rowType === type;

                row.style.display = (matchSearch && matchQuiz && matchType) ? '' : 'none';
            });

            clearSelection();
            checkEmpty();
        }

        function checkEmpty() {
            const visible = [...document.querySelectorAll('#questionsBody tr')].filter(r => r.style.display !== 'none');
            document.getElementById('emptyState').style.display = visible.length === 0 ? '' : 'none';
        }
    </script>

</body>

</html>