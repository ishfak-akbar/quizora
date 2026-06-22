<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <title>Quizora — Learn, Teach, Compete</title>
    <style>
        :root {
            --color-primary-glow: #818CF8;
            --color-primary-solid: #4F46E5;
            --color-primary-dark: #1E1A3E;
            --color-bg-main: #0E0B20;
            --color-bg-card: #1E1A3E;
            --color-border-light: rgba(255, 255, 255, 0.08);
            --color-text-primary: #FFFFFF;
            --color-text-secondary: #9CA3AF;
            --color-text-muted: #6B7280;
            --color-status-success: #34D399;
            --color-stat-cyan: #22D3EE;
            --font: 'Plus Jakarta Sans', sans-serif;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font);
            background: var(--color-bg-main);
            color: var(--color-text-primary);
            overflow-x: hidden;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            max-width: 1160px;
            margin: 0 auto;
            padding: 0 32px;
        }

        /* GRADIENT TEXT */
        .text-gradient {
            background: linear-gradient(135deg, var(--color-primary-glow) 0%, var(--color-stat-cyan) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* BADGE */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(79, 70, 229, 0.12);
            border: 1px solid rgba(129, 140, 248, 0.25);
            color: var(--color-primary-glow);
            padding: 7px 16px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .badge i {
            font-size: 14px;
            color: #C084FC;
        }

        /* SECTION HEADER */
        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-tag {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--color-primary-glow);
            background: rgba(79, 70, 229, 0.12);
            border: 1px solid rgba(129, 140, 248, 0.2);
            padding: 5px 14px;
            border-radius: 100px;
            margin-bottom: 14px;
        }

        .section-title {
            font-size: clamp(28px, 4vw, 40px);
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.02em;
            margin-bottom: 14px;
            line-height: 1.2;
        }

        .section-sub {
            font-size: 16px;
            color: var(--color-text-secondary);
            max-width: 560px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* BUTTONS */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: var(--font);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--color-primary-solid) 0%, #6366F1 100%);
            color: #fff;
            padding: 13px 26px;
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(79, 70, 229, 0.45);
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--color-border-light);
            color: #fff;
            padding: 13px 26px;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .btn-lg {
            padding: 16px 36px;
            font-size: 15px;
            border-radius: 14px;
        }

        /* NAVBAR */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            z-index: 1000;
            background: rgba(14, 11, 32, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--color-border-light);
            display: flex;
            align-items: center;
            transition: background 0.3s;
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 22px;
            font-weight: 800;
            color: #fff;
        }

        .nav-logo-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--color-primary-solid), #818CF8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 800;
            color: #fff;
        }

        .nav-logo span {
            color: var(--color-primary-glow);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 36px;
        }

        .nav-link {
            font-size: 14px;
            font-weight: 500;
            color: var(--color-text-secondary);
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: #fff;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-nav-ghost {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            padding: 10px 20px;
            border-radius: 10px;
            transition: color 0.2s;
        }

        .btn-nav-ghost:hover {
            color: var(--color-primary-glow);
        }

        /* HERO */
        .hero {
            padding: 180px 0 100px;
            text-align: center;
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(ellipse 900px 500px at 50% -50px,
                    rgba(79, 70, 229, 0.22) 0%,
                    rgba(99, 102, 241, 0.08) 50%,
                    transparent 75%),
                linear-gradient(180deg, #161233 0%, #0E0B20 60%, #070514 100%);
        }

        .hero-glow {
            position: absolute;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            height: 500px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.18) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: clamp(38px, 6vw, 68px);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.03em;
            margin-bottom: 22px;
        }

        .hero p {
            font-size: clamp(15px, 2vw, 18px);
            color: var(--color-text-secondary);
            max-width: 600px;
            margin: 0 auto 38px;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        /* STATS BANNER */
        .stats-banner {
            padding: 52px 0;
            background: linear-gradient(90deg, rgba(30, 26, 62, 0.3) 0%, rgba(14, 11, 32, 0.6) 50%, rgba(30, 26, 62, 0.3) 100%);
            border-top: 1px solid var(--color-border-light);
            border-bottom: 1px solid var(--color-border-light);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            text-align: center;
        }

        .stat-divider {
            width: 1px;
            background: var(--color-border-light);
        }

        .stat-value {
            font-size: 40px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* AUDIENCES */
        .audiences {
            padding: 120px 0;
        }

        .audiences-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
        }

        .audience-card {
            border-radius: 24px;
            padding: 52px;
            border: 1px solid var(--color-border-light);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: border-color 0.3s, transform 0.3s;
        }

        .audience-card:hover {
            transform: translateY(-4px);
        }

        .audience-card::after {
            content: '';
            position: absolute;
            bottom: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.15;
            pointer-events: none;
        }

        .audience-card.teacher {
            background: linear-gradient(135deg, #1a1050 0%, #2a1f6a 100%);
            border-color: rgba(79, 70, 229, 0.25);
        }

        .audience-card.teacher::after {
            background: var(--color-primary-solid);
        }

        .audience-card.teacher:hover {
            border-color: rgba(129, 140, 248, 0.4);
        }

        .audience-card.student {
            background: linear-gradient(135deg, #0a1e3a 0%, #0c3050 100%);
            border-color: rgba(34, 211, 238, 0.2);
        }

        .audience-card.student::after {
            background: var(--color-stat-cyan);
        }

        .audience-card.student:hover {
            border-color: rgba(34, 211, 238, 0.4);
        }

        .audience-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 100px;
            margin-bottom: 20px;
        }

        .teacher .audience-badge {
            background: rgba(79, 70, 229, 0.2);
            border: 1px solid rgba(129, 140, 248, 0.3);
            color: var(--color-primary-glow);
        }

        .student .audience-badge {
            background: rgba(34, 211, 238, 0.1);
            border: 1px solid rgba(34, 211, 238, 0.25);
            color: var(--color-stat-cyan);
        }

        .audience-card h3 {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }

        .audience-card p {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.65);
            line-height: 1.7;
            margin-bottom: 32px;
            position: relative;
            z-index: 1;
        }

        .audience-list {
            list-style: none;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .audience-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 14px;
        }

        .audience-list li i {
            font-size: 18px;
            flex-shrink: 0;
        }

        .teacher .audience-list li i {
            color: var(--color-primary-glow);
        }

        .student .audience-list li i {
            color: var(--color-stat-cyan);
        }

        .audience-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            position: relative;
            z-index: 1;
            transition: gap 0.2s;
        }

        .audience-link:hover {
            gap: 14px;
        }

        /* FEATURES */
        .features {
            padding: 100px 0;
            background: #070514;
            border-top: 1px solid var(--color-border-light);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .feature-card {
            background: linear-gradient(135deg, #1E1A3E 0%, rgba(30, 26, 62, 0.5) 100%);
            border: 1px solid var(--color-border-light);
            border-radius: 20px;
            padding: 36px;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            border-color: rgba(129, 140, 248, 0.3);
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.4);
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 22px;
        }

        .feature-card:nth-child(1) .feature-icon {
            background: rgba(34, 211, 238, 0.08);
            color: var(--color-stat-cyan);
            border: 1px solid rgba(34, 211, 238, 0.15);
        }

        .feature-card:nth-child(2) .feature-icon {
            background: rgba(79, 70, 229, 0.1);
            color: var(--color-primary-glow);
            border: 1px solid rgba(79, 70, 229, 0.2);
        }

        .feature-card:nth-child(3) .feature-icon {
            background: rgba(52, 211, 153, 0.08);
            color: var(--color-status-success);
            border: 1px solid rgba(52, 211, 153, 0.15);
        }

        .feature-card:nth-child(4) .feature-icon {
            background: rgba(245, 158, 11, 0.08);
            color: #F59E0B;
            border: 1px solid rgba(245, 158, 11, 0.15);
        }

        .feature-card:nth-child(5) .feature-icon {
            background: rgba(244, 114, 182, 0.08);
            color: #F472B6;
            border: 1px solid rgba(244, 114, 182, 0.15);
        }

        .feature-card:nth-child(6) .feature-icon {
            background: rgba(167, 139, 250, 0.08);
            color: #A78BFA;
            border: 1px solid rgba(167, 139, 250, 0.15);
        }

        .feature-card h3 {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
        }

        .feature-card p {
            font-size: 14px;
            color: var(--color-text-secondary);
            line-height: 1.65;
        }

        /* HOW IT WORKS */
        .hiw {
            padding: 120px 0;
        }

        .hiw-inner {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: start;
        }

        .hiw-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 32px;
        }

        .hiw-tab {
            padding: 10px 22px;
            border-radius: 100px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: 1.5px solid var(--color-border-light);
            color: var(--color-text-secondary);
            background: transparent;
            font-family: var(--font);
            transition: all 0.2s;
        }

        .hiw-tab.active {
            background: var(--color-primary-solid);
            border-color: var(--color-primary-solid);
            color: #fff;
            box-shadow: 0 4px 16px rgba(79, 70, 229, 0.3);
        }

        .hiw-steps {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .hiw-step {
            display: flex;
            align-items: flex-start;
            gap: 18px;
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 16px;
            padding: 22px;
            transition: border-color 0.2s, transform 0.2s;
        }

        .hiw-step:hover {
            border-color: rgba(79, 70, 229, 0.3);
            transform: translateX(4px);
        }

        .hiw-step-num {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--color-primary-solid), #6366F1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0;
        }

        .hiw-step h4 {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 5px;
        }

        .hiw-step p {
            font-size: 13px;
            color: var(--color-text-secondary);
            line-height: 1.6;
        }

        /* MOCK UI */
        .mock-ui {
            position: sticky;
            top: 100px;
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.5);
        }

        .mock-titlebar {
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid var(--color-border-light);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mock-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .mock-body {
            padding: 22px;
        }

        .mock-q-tag {
            font-size: 10px;
            font-weight: 700;
            color: var(--color-primary-glow);
            background: rgba(79, 70, 229, 0.15);
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 12px;
        }

        .mock-q-text {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 16px;
            line-height: 1.4;
        }

        .mock-options {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .mock-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 10px;
            font-size: 13px;
            border: 1.5px solid var(--color-border-light);
            background: rgba(255, 255, 255, 0.02);
            color: var(--color-text-secondary);
            transition: all 0.2s;
        }

        .mock-option.selected {
            border-color: var(--color-primary-solid);
            background: rgba(79, 70, 229, 0.12);
            color: #fff;
            font-weight: 500;
        }

        .mock-letter {
            width: 26px;
            height: 26px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.05);
            color: var(--color-text-muted);
            flex-shrink: 0;
        }

        .mock-option.selected .mock-letter {
            background: var(--color-primary-solid);
            color: #fff;
        }

        .mock-footer {
            padding: 14px 22px;
            border-top: 1px solid var(--color-border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mock-timer {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(248, 113, 113, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.25);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
            color: #F87171;
        }

        .mock-progress {
            display: flex;
            gap: 5px;
        }

        .mock-prog-dot {
            width: 28px;
            height: 4px;
            border-radius: 2px;
        }

        /* CTA */
        .cta {
            padding: 80px 0 120px;
        }

        .cta-box {
            background: linear-gradient(135deg, #161233 0%, #0E0B20 100%);
            border: 1px solid rgba(129, 140, 248, 0.2);
            border-radius: 28px;
            padding: 80px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
        }

        .cta-box::before {
            content: '';
            position: absolute;
            top: -120px;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 300px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.18) 0%, transparent 70%);
            pointer-events: none;
        }

        .cta-box h2 {
            font-size: clamp(26px, 4vw, 42px);
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .cta-box p {
            font-size: 16px;
            color: var(--color-text-secondary);
            max-width: 500px;
            margin: 0 auto 36px;
            position: relative;
            z-index: 1;
            line-height: 1.7;
        }

        .cta-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        /* FOOTER */
        footer {
            background: #070514;
            border-top: 1px solid var(--color-border-light);
            padding: 48px 0;
        }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-logo {
            font-size: 20px;
            font-weight: 800;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-logo span {
            color: var(--color-primary-glow);
        }

        .footer-logo-icon {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: linear-gradient(135deg, var(--color-primary-solid), #818CF8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            color: #fff;
        }

        .footer-links {
            display: flex;
            gap: 28px;
        }

        .footer-link {
            font-size: 13px;
            color: var(--color-text-muted);
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: #fff;
        }

        .footer-copy {
            font-size: 12px;
            color: var(--color-text-muted);
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .audiences-grid {
                grid-template-columns: 1fr;
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .hiw-inner {
                grid-template-columns: 1fr;
                gap: 48px;
            }

            .mock-ui {
                position: static;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 32px;
            }
        }

        @media (max-width: 640px) {
            .navbar {
                height: 68px;
            }

            .nav-links {
                display: none;
            }

            .hero {
                padding: 130px 0 70px;
            }

            .container {
                padding: 0 20px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .audience-card {
                padding: 32px;
            }

            .cta-box {
                padding: 48px 24px;
            }

            footer {
                padding: 32px 0;
            }

            .footer-inner {
                flex-direction: column;
                text-align: center;
            }

            .footer-links {
                justify-content: center;
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="container nav-inner">
            <a href="/" class="nav-logo">
                <div class="nav-logo-icon">Q</div>
                <div class="logo-text">Quiz<span>ora</span></div>
            </a>
            <div class="nav-links">
                <a href="#audiences" class="nav-link">Solutions</a>
                <a href="#features" class="nav-link">Features</a>
                <a href="#how-it-works" class="nav-link">How it works</a>
            </div>
            <div class="nav-actions">
                <a href="{{ route('login') }}" class="btn-nav-ghost">Sign In</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Get Started Free</a>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-glow"></div>
        <div class="container hero-content">
            <div class="badge">
                <i class="ti ti-sparkles"></i> Public Quiz Platform for Everyone
            </div>
            <h1>
                Learn Smarter.<br>
                Teach <span class="text-gradient">Better.</span><br>
                Compete Together.
            </h1>
            <p>
                Quizora is an open quiz platform where students discover and attempt quizzes
                on any topic — from school basics to BCS preparation — and teachers create,
                manage, and analyze with ease.
            </p>
            <div class="hero-actions">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    <i class="ti ti-rocket"></i> Start for Free
                </a>
                <a href="#how-it-works" class="btn btn-outline btn-lg">
                    <i class="ti ti-player-play"></i> See how it works
                </a>
            </div>
        </div>
    </section>

    <!-- STATS BANNER -->
    <div class="stats-banner">
        <div class="container">
            <div class="stats-grid">
                <div style="text-align:center;">
                    <div class="stat-value counter" data-target="500">0</div>
                    <div class="stat-label">Public Quizzes</div>
                </div>
                <div style="text-align:center;">
                    <div class="stat-value counter" data-target="10000">0</div>
                    <div class="stat-label">Quiz Attempts</div>
                </div>
                <div style="text-align:center;">
                    <div class="stat-value counter" data-target="200">0</div>
                    <div class="stat-label">Active Teachers</div>
                </div>
                <div style="text-align:center;">
                    <div class="stat-value counter" data-target="15">0</div>
                    <div class="stat-label">Categories</div>
                </div>
            </div>
        </div>
    </div>

    <!-- AUDIENCES -->
    <section class="audiences" id="audiences">
        <div class="container">
            <div class="section-header">
                <div class="section-tag">Who is Quizora for?</div>
                <h2 class="section-title">Built for two sides of learning</h2>
                <p class="section-sub">Whether you want to test your knowledge or help others grow theirs — Quizora has a place for you.</p>
            </div>

            <div class="audiences-grid">

                <!-- TEACHER -->
                <div class="audience-card teacher">
                    <div>
                        <div class="audience-badge"><i class="ti ti-device-laptop"></i> For Instructors</div>
                        <h3>Publish & Monitor</h3>
                        <p>Create professional quizzes with full control over timing, grading, and student performance analytics — all in one dashboard.</p>
                        <ul class="audience-list">
                            <li><i class="ti ti-circle-check-filled"></i> 3-step quiz builder with MCQ support</li>
                            <li><i class="ti ti-circle-check-filled"></i> Set time limits, due dates and max attempts</li>
                            <li><i class="ti ti-circle-check-filled"></i> Auto-grading and instant result publishing</li>
                            <li><i class="ti ti-circle-check-filled"></i> Real-time submission stats on dashboard</li>
                            <li><i class="ti ti-circle-check-filled"></i> Leaderboard and per-quiz result analysis</li>
                            <li><i class="ti ti-circle-check-filled"></i> Public or private quiz visibility control</li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}" class="audience-link">
                        Start teaching <i class="ti ti-arrow-right"></i>
                    </a>
                </div>

                <!-- STUDENT -->
                <div class="audience-card student">
                    <div>
                        <div class="audience-badge"><i class="ti ti-user-check"></i> For Students</div>
                        <h3>Discover & Excel</h3>
                        <p>Browse thousands of public quizzes across every subject — attempt them at your own pace and track your progress over time.</p>
                        <ul class="audience-list">
                            <li><i class="ti ti-circle-check-filled"></i> Browse by category and difficulty</li>
                            <li><i class="ti ti-circle-check-filled"></i> Topics from school math to BCS cadre prep</li>
                            <li><i class="ti ti-circle-check-filled"></i> Countdown timer with auto-submit</li>
                            <li><i class="ti ti-circle-check-filled"></i> Instant results with full answer review</li>
                            <li><i class="ti ti-circle-check-filled"></i> Personal leaderboard rank per quiz</li>
                            <li><i class="ti ti-circle-check-filled"></i> Save quizzes to attempt later</li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}" class="audience-link">
                        Start learning <i class="ti ti-arrow-right"></i>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header">
                <div class="section-tag">Features</div>
                <h2 class="section-title">Everything you need in one place</h2>
                <p class="section-sub">Powerful tools for teachers, seamless experience for students — built with simplicity in mind.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="ti ti-circle-plus"></i></div>
                    <h3>3-Step Quiz Builder</h3>
                    <p>Create professional quizzes in minutes — add details, build questions, review and publish. Clean, guided, no complexity.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="ti ti-clock"></i></div>
                    <h3>Countdown Timer</h3>
                    <p>Set time limits on quizzes. Students see a live countdown and the quiz auto-submits when time runs out.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="ti ti-chart-bar"></i></div>
                    <h3>Real-time Analytics</h3>
                    <p>Teachers get instant stats — submissions, average score, highest and lowest — as students complete quizzes.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="ti ti-trophy"></i></div>
                    <h3>Leaderboards</h3>
                    <p>Every quiz has a live leaderboard. Students see their rank instantly after submitting — motivation built in.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="ti ti-compass"></i></div>
                    <h3>Public Quiz Discovery</h3>
                    <p>Students browse thousands of public quizzes by category, difficulty, and topic — no invitation needed.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="ti ti-clipboard-check"></i></div>
                    <h3>Instant Auto-grading</h3>
                    <p>MCQ quizzes are graded automatically the moment a student submits. Results and answer reviews appear immediately.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="hiw" id="how-it-works">
        <div class="container">
            <div class="hiw-inner">

                <div>
                    <div class="section-tag">How it works</div>
                    <h2 class="section-title" style="text-align:left;margin-bottom:12px;">Simple for everyone</h2>
                    <p class="section-sub" style="text-align:left;margin:0 0 32px;">Two different experiences, one platform. Pick your role and get started in minutes.</p>

                    <div class="hiw-tabs">
                        <button class="hiw-tab active" onclick="switchHiw('teacher', this)">For Teachers</button>
                        <button class="hiw-tab" onclick="switchHiw('student', this)">For Students</button>
                    </div>

                    <div class="hiw-steps" id="hiw-teacher">
                        <div class="hiw-step">
                            <div class="hiw-step-num">1</div>
                            <div>
                                <h4>Create your account</h4>
                                <p>Register as a teacher in seconds — no credit card, no setup fees. Just your name and email.</p>
                            </div>
                        </div>
                        <div class="hiw-step">
                            <div class="hiw-step-num">2</div>
                            <div>
                                <h4>Build your quiz</h4>
                                <p>Use the 3-step quiz builder to add questions, set time limits, difficulty, category, and visibility.</p>
                            </div>
                        </div>
                        <div class="hiw-step">
                            <div class="hiw-step-num">3</div>
                            <div>
                                <h4>Publish and share</h4>
                                <p>Publish publicly or keep it private. Share the link or let students discover it on the platform.</p>
                            </div>
                        </div>
                        <div class="hiw-step">
                            <div class="hiw-step-num">4</div>
                            <div>
                                <h4>Track performance</h4>
                                <p>Watch submissions live. View per-student scores, leaderboards and export results to CSV.</p>
                            </div>
                        </div>
                    </div>

                    <div class="hiw-steps" id="hiw-student" style="display:none;">
                        <div class="hiw-step">
                            <div class="hiw-step-num">1</div>
                            <div>
                                <h4>Sign up for free</h4>
                                <p>Create a student account in seconds. No fees, no subscriptions — completely free to use.</p>
                            </div>
                        </div>
                        <div class="hiw-step">
                            <div class="hiw-step-num">2</div>
                            <div>
                                <h4>Browse and discover</h4>
                                <p>Explore quizzes by category, difficulty, or topic. From school basics to BCS cadre preparation.</p>
                            </div>
                        </div>
                        <div class="hiw-step">
                            <div class="hiw-step-num">3</div>
                            <div>
                                <h4>Take the quiz</h4>
                                <p>Answer MCQ questions with a live countdown timer. Auto-submits when time is up.</p>
                            </div>
                        </div>
                        <div class="hiw-step">
                            <div class="hiw-step-num">4</div>
                            <div>
                                <h4>See your results instantly</h4>
                                <p>Get your score, see correct answers, check your leaderboard rank — all immediately after submitting.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MOCK UI -->
                <div class="mock-ui">
                    <div class="mock-titlebar">
                        <div class="mock-dot" style="background:#F87171;"></div>
                        <div class="mock-dot" style="background:#F59E0B;"></div>
                        <div class="mock-dot" style="background:#34D399;"></div>
                        <div style="flex:1;height:24px;background:rgba(255,255,255,0.04);border-radius:6px;margin-left:10px;"></div>
                    </div>
                    <div class="mock-body">
                        <div class="mock-q-tag">Question 3 of 10</div>
                        <div class="mock-q-text">Which article of Bangladesh's Constitution declares the country as a unitary state?</div>
                        <div class="mock-options">
                            <div class="mock-option">
                                <div class="mock-letter">A</div> Article 1
                            </div>
                            <div class="mock-option selected">
                                <div class="mock-letter">B</div> Article 2
                            </div>
                            <div class="mock-option">
                                <div class="mock-letter">C</div> Article 3
                            </div>
                            <div class="mock-option">
                                <div class="mock-letter">D</div> Article 4
                            </div>
                        </div>
                    </div>
                    <div class="mock-footer">
                        <div class="mock-timer">
                            <i class="ti ti-clock" style="font-size:15px;"></i> 12:34
                        </div>
                        <div class="mock-progress">
                            <div class="mock-prog-dot" style="background:#34D399;"></div>
                            <div class="mock-prog-dot" style="background:#34D399;"></div>
                            <div class="mock-prog-dot" style="background:#4F46E5;"></div>
                            <div class="mock-prog-dot" style="background:rgba(255,255,255,0.1);"></div>
                            <div class="mock-prog-dot" style="background:rgba(255,255,255,0.1);"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta">
        <div class="container">
            <div class="cta-box">
                <div class="badge" style="margin-bottom:20px;">
                    <i class="ti ti-rocket"></i> Free Forever
                </div>
                <h2>Ready to quiz smarter?</h2>
                <p>Join thousands of students and teachers already using Quizora — free forever, no credit card needed.</p>
                <div class="cta-actions">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        <i class="ti ti-rocket"></i> Create Free Account
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline btn-lg">
                        <i class="ti ti-login"></i> Sign In
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container footer-inner">
            <div class="footer-logo">
                <div class="footer-logo-icon">Q</div>
                Quiz<span>ora</span>
            </div>
            <div class="footer-links">
                <a href="#audiences" class="footer-link">Solutions</a>
                <a href="#features" class="footer-link">Features</a>
                <a href="#how-it-works" class="footer-link">How it works</a>
                <a href="{{ route('login') }}" class="footer-link">Sign In</a>
                <a href="{{ route('register') }}" class="footer-link">Register</a>
            </div>
            <div class="footer-copy">© {{ date('Y') }} Quizora. Built with Laravel & Vanilla JS.</div>
        </div>
    </footer>

    <script>
        // TAB SWITCHER
        function switchHiw(role, btn) {
            document.querySelectorAll('.hiw-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('hiw-teacher').style.display = role === 'teacher' ? 'flex' : 'none';
            document.getElementById('hiw-student').style.display = role === 'student' ? 'flex' : 'none';
        }

        // NAVBAR SCROLL
        window.addEventListener('scroll', () => {
            document.querySelector('.navbar').style.background =
                window.scrollY > 60 ? 'rgba(14,11,32,0.98)' : 'rgba(14,11,32,0.8)';
        });

        // COUNTER ANIMATION
        const counters = document.querySelectorAll('.counter');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const el = entry.target;
                const target = parseInt(el.getAttribute('data-target'));
                const suffix = target >= 1000 ? 'k+' : '+';
                const displayTarget = target >= 1000 ? target / 1000 : target;
                const frames = 50;
                const step = displayTarget / frames;
                let current = 0;
                const timer = setInterval(() => {
                    current += step;
                    if (current >= displayTarget) {
                        el.textContent = displayTarget + suffix;
                        clearInterval(timer);
                    } else {
                        el.textContent = Math.floor(current) + suffix;
                    }
                }, 25);
                observer.unobserve(el);
            });
        }, {
            threshold: 0.3
        });
        counters.forEach(c => observer.observe(c));
    </script>
</body>

</html>