<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <title>Quizora — Authentication</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      width: 100vw;
      height: 100vh;
      overflow: hidden;
      font-family: 'Nunito', 'Segoe UI', sans-serif;
    }

    /* ── BACKGROUND ── */
    .auth-bg {
      min-height: 100vh;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;

      background:
        radial-gradient(ellipse 1400px 600px at 50% -10%,
          rgba(99, 102, 241, 0.25) 0%,
          rgba(139, 92, 246, 0.15) 35%,
          rgba(15, 12, 30, 0.6) 70%,
          transparent 100%),
        linear-gradient(180deg,
          #161233 0%,
          #0E0B20 50%,
          #070514 100%);
    }

    .dots {
      position: absolute;
      display: grid;
      gap: 6px;
    }

    .dots span {
      width: 4px;
      height: 4px;
      border-radius: 50%;
      background: rgba(129, 140, 248, 0.15);
      display: block;
    }

    .dots-tl {
      top: 60px;
      left: 40px;
      grid-template-columns: repeat(5, 1fr);
    }

    .dots-bl {
      bottom: 120px;
      left: 260px;
      grid-template-columns: repeat(5, 1fr);
    }

    .el {
      position: absolute;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: float 4s ease-in-out infinite;
    }

    .el svg {
      display: block;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-9px);
      }
    }

    .sparkle {
      position: absolute;
      color: rgba(34, 211, 238, 0.65);
      font-size: 18px;
      animation: twinkle 2.5s ease-in-out infinite;
    }

    @keyframes twinkle {

      0%,
      100% {
        opacity: 1;
        transform: scale(1);
      }

      50% {
        opacity: 0.25;
        transform: scale(0.55);
      }
    }

    .deco {
      position: absolute;
      color: rgba(129, 140, 248, 0.18);
      font-size: 18px;
      font-weight: 200;
    }

    .login-wrap {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 10;
      width: 390px;
    }

    .top-nav {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 100;
      padding: 18px 36px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .nav-logo {
      font-size: 40px;
      font-weight: 700;
      color: #ffffff;
      letter-spacing: 0.3px;
    }

    .nav-logo span {
      color: #818CF8;
    }

    .login-card {
      background: #1E1A3E;
      border-radius: 18px;
      padding: 40px 28px;
      border: 1px solid rgba(255, 255, 255, 0.08);
      box-shadow:
        0 30px 70px rgba(0, 0, 0, 0.55),
        inset 0 1px 0 rgba(255, 255, 255, 0.04);
      position: relative;
      overflow: hidden;
      height: auto;
      transition: height 0.45s cubic-bezier(0.2, 0.8, 0.2, 1);
      will-change: height;
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg,
          transparent,
          rgba(99, 102, 241, 0.65),
          rgba(139, 92, 246, 0.45),
          transparent);
      opacity: 0.9;
    }

    .card-title {
      font-size: 27px;
      font-weight: 700;
      color: #ffffff;
      margin-bottom: 6px;
      text-align: center;
      letter-spacing: -0.3px;
    }

    .card-sub {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.45);
      text-align: center;
      margin-bottom: 20px;
    }

    .field {
      margin-bottom: 12px;
    }

    .field label {
      display: block;
      font-size: 9px;
      font-weight: 600;
      color: rgba(255, 255, 255, 0.50);
      margin-bottom: 6px;
      letter-spacing: 0.8px;
      text-transform: uppercase;
    }

    .input-wrap {
      display: flex;
      align-items: center;
      gap: 12px;
      background: rgba(255, 255, 255, 0.07);
      border: 1.5px solid rgba(255, 255, 255, 0.10);
      border-radius: 10px;
      padding: 0 16px;
      transition: border-color .2s, background .2s, box-shadow .2s;
    }

    .input-wrap:focus-within {
      border-color: rgba(99, 102, 241, 0.65);
      background: rgba(255, 255, 255, 0.11);
      box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }

    .input-wrap svg {
      flex-shrink: 0;
      opacity: 0.45;
    }

    .input-wrap input {
      flex: 1;
      height: 38px;
      background: none;
      border: none;
      outline: none;
      color: #ffffff;
      font-size: 13px;
      font-family: inherit;
      font-weight: 400;
    }

    .input-wrap input::placeholder {
      color: rgba(255, 255, 255, 0.25);
    }

    .row-meta {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: 14px 0 18px;
    }

    .remember {
      display: flex;
      align-items: center;
      gap: 9px;
      cursor: pointer;
    }

    .remember input[type=checkbox] {
      display: none;
    }

    .check-box {
      width: 15px;
      height: 15px;
      border-radius: 5px;
      border: 1.5px solid rgba(255, 255, 255, 0.22);
      background: rgba(255, 255, 255, 0.05);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background .2s, border-color .2s;
      flex-shrink: 0;
    }

    .remember input:checked+.check-box {
      background: #4F46E5;
      border-color: #4F46E5;
    }

    .remember input:checked+.check-box::after {
      content: '';
      display: block;
      width: 10px;
      height: 6px;
      border-left: 2px solid #fff;
      border-bottom: 2px solid #fff;
      transform: rotate(-45deg) translateY(-1px);
    }

    .remember span {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.55);
    }

    .forgot {
      font-size: 13px;
      color: rgba(129, 140, 248, 0.85);
      text-decoration: none;
      cursor: pointer;
      transition: color .2s;
    }

    .forgot:hover {
      color: #fff;
    }

    .btn-login {
      width: 100%;
      height: 38px;
      background: linear-gradient(135deg, #2E2570 0%, #4F46E5 50%, #818CF8 100%);
      border: none;
      border-radius: 14px;
      color: #fff;
      font-size: 15px;
      font-weight: 700;
      font-family: inherit;
      cursor: pointer;
      letter-spacing: 0.4px;
      position: relative;
      overflow: hidden;

      transition: transform .25s ease, box-shadow .25s ease, background-position .6s ease;
      background-size: 200% 200%;
      background-position: 0% 50%;

      box-shadow: 0 6px 24px rgba(46, 37, 112, 0.6);
    }

    .btn-login:hover {
      transform: translateY(-3px) scale(1.01);
      background-position: 100% 50%;
      box-shadow:
        0 12px 35px rgba(46, 37, 112, 0.8),
        0 0 18px rgba(129, 140, 248, 0.4);
    }

    .btn-login:active {
      transform: translateY(0) scale(0.99);
      box-shadow: 0 6px 18px rgba(46, 37, 112, 0.5);
    }

    /* Shine sweep animation */
    .btn-login::after {
      content: '';
      position: absolute;
      top: -50%;
      left: -60%;
      width: 40%;
      height: 200%;
      background: linear-gradient(120deg,
          transparent 0%,
          rgba(255, 255, 255, 0.25) 50%,
          transparent 100%);
      transform: rotate(25deg);
      transition: left 0.6s ease;
    }

    .btn-login:hover::after {
      left: 120%;
    }

    .divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 26px 0 16px;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: rgba(255, 255, 255, 0.08);
    }

    .divider span {
      font-size: 11px;
      color: rgba(255, 255, 255, 0.25);
    }

    .card-footer {
      text-align: center;
      font-size: 13px;
      color: rgba(255, 255, 255, 0.38);
    }

    .card-footer a {
      color: #818CF8;
      text-decoration: none;
      font-weight: 600;
      cursor: pointer;
      transition: color .2s;
    }

    .card-footer a:hover {
      color: #fff;
    }

    .form-container {
      opacity: 0;
      transform: translateY(8px);
      display: none;
      transition: opacity 0.4s ease, transform 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .form-container.active-view {
      display: block;
    }

    .form-container.fade-in-view {
      opacity: 1;
      transform: translateY(0);
    }

    .role-toggle {
      display: flex;
      background: rgba(255, 255, 255, 0.07);
      border-radius: 12px;
      padding: 4px;
      margin-bottom: 20px;
      border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .role-btn {
      flex: 1;
      height: 36px;
      border: none;
      border-radius: 9px;
      background: transparent;
      color: rgba(255, 255, 255, 0.45);
      font-size: 13px;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      transition: all .25s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
    }

    .role-btn.active {
      background: linear-gradient(135deg, #2E2570, #4F46E5);
      color: #fff;
      box-shadow: 0 4px 14px rgba(46, 37, 112, 0.5);
    }
  </style>
</head>
@php
$showRegisterOnLoad = $errors->has('name') || old('name');
@endphp

<body>
  <div class="auth-bg">
    <nav class="top-nav">
      <a href="{{ url('/') }}" style="text-decoration:none;">
        <div class="nav-logo">Quiz<span>ora</span></div>
      </a>
    </nav>

    <div class="dots dots-tl">
      <span></span><span></span><span></span><span></span><span></span>
      <span></span><span></span><span></span><span></span><span></span>
      <span></span><span></span><span></span><span></span><span></span>
      <span></span><span></span><span></span><span></span><span></span>
    </div>
    <div class="dots dots-bl">
      <span></span><span></span><span></span><span></span><span></span>
      <span></span><span></span><span></span><span></span><span></span>
      <span></span><span></span><span></span><span></span><span></span>
      <span></span><span></span><span></span><span></span><span></span>
    </div>

    <span class="sparkle" style="top:88px;left:1150px;animation-delay:0s">✦</span>
    <span class="sparkle" style="top:52px;left:1242px;animation-delay:0.7s;font-size:13px">✦</span>
    <span class="sparkle" style="top:198px;left:962px;animation-delay:1.2s;font-size:11px">✦</span>
    <span class="sparkle" style="top:312px;left:1102px;animation-delay:0.4s;font-size:10px">✦</span>
    <span class="sparkle" style="top:128px;left:398px;animation-delay:1.8s;font-size:10px">✦</span>
    <span class="sparkle" style="bottom:202px;left:822px;animation-delay:0.9s;font-size:12px">✦</span>

    <span class="deco" style="bottom:62px;left:55px">+</span>
    <span class="deco" style="bottom:62px;left:372px;width:22px;height:22px;border-radius:50%;border:1.5px solid rgba(129,140,248,0.25);display:flex;align-items:center;justify-content:center"></span>

    <div class="el" style="top:90px;left:160px;animation-delay:0s">
      <svg width="68" height="68" viewBox="0 0 68 68">
        <rect x="4" y="4" width="60" height="60" rx="10" fill="none" stroke="rgba(129,140,248,0.35)" stroke-width="2.5" />
        <polyline points="18,34 29,45 50,23" fill="none" stroke="rgba(34,211,238,0.75)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </div>

    <div class="el" style="top:165px;left:80px;animation-delay:0.5s">
      <svg width="80" height="72" viewBox="0 0 80 72">
        <rect x="4" y="4" width="66" height="52" rx="10" fill="none" stroke="rgba(129,140,248,0.3)" stroke-width="2.5" />
        <polygon points="18,56 10,68 30,56" fill="none" stroke="rgba(129,140,248,0.3)" stroke-width="2.5" stroke-linejoin="round" />
        <text x="37" y="37" text-anchor="middle" fill="rgba(34,211,238,0.65)" text-shadow="0 0 10px rgba(34,211,238,0.3)" font-size="26" font-weight="600" font-family="Georgia,serif">?</text>
      </svg>
    </div>

    <div style="position:absolute;top:185px;left:175px">
      <div style="width:120px;height:2.5px;background:rgba(129,140,248,0.25);border-radius:2px;margin-bottom:10px"></div>
      <div style="width:80px;height:2.5px;background:rgba(129,140,248,0.15);border-radius:2px"></div>
    </div>

    <div class="el" style="top:320px;left:55px;flex-direction:column;gap:10px;align-items:flex-start;animation-delay:1s">
      <div style="display:flex;align-items:center;gap:10px;width:310px;height:46px;border:2px solid rgba(99,102,241,0.5);border-radius:24px;padding:0 16px;background:rgba(30,26,62,0.6);box-shadow:0 0 15px rgba(99,102,241,0.15)">
        <div style="width:28px;height:28px;border-radius:50%;border:2px solid rgba(34,211,238,0.7);display:flex;align-items:center;justify-content:center"><span style="color:rgba(34,211,238,0.9);font-size:13px;font-weight:600">A</span></div>
        <div style="flex:1;height:3px;background:rgba(34,211,238,0.4);border-radius:2px"></div>
        <svg width="18" height="18" viewBox="0 0 18 18">
          <polyline points="3,9 7,13 15,5" fill="none" stroke="rgba(34,211,238,0.9)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </div>
      <div style="display:flex;align-items:center;gap:10px;width:310px;height:46px;border:1.5px solid rgba(255,255,255,0.08);border-radius:24px;padding:0 16px;background:rgba(255,255,255,0.03)">
        <div style="width:28px;height:28px;border-radius:50%;border:1.5px solid rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center"><span style="color:rgba(255,255,255,0.4);font-size:13px;font-weight:600">B</span></div>
        <div style="flex:1;height:3px;background:rgba(255,255,255,0.1);border-radius:2px"></div>
      </div>
      <div style="display:flex;align-items:center;gap:10px;width:310px;height:46px;border:1.5px solid rgba(255,255,255,0.08);border-radius:24px;padding:0 16px;background:rgba(255,255,255,0.03)">
        <div style="width:28px;height:28px;border-radius:50%;border:1.5px solid rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center"><span style="color:rgba(255,255,255,0.4);font-size:13px;font-weight:600">C</span></div>
        <div style="flex:1;height:3px;background:rgba(255,255,255,0.1);border-radius:2px"></div>
      </div>
      <div style="display:flex;align-items:center;gap:10px;width:310px;height:46px;border:1.5px solid rgba(255,255,255,0.08);border-radius:24px;padding:0 16px;background:rgba(255,255,255,0.03)">
        <div style="width:28px;height:28px;border-radius:50%;border:1.5px solid rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center"><span style="color:rgba(255,255,255,0.4);font-size:13px;font-weight:600">D</span></div>
        <div style="flex:1;height:3px;background:rgba(255,255,255,0.1);border-radius:2px"></div>
      </div>
    </div>

    <div class="el" style="bottom:100px;left:90px;animation-delay:1.5s">
      <svg width="110" height="110" viewBox="0 0 110 110">
        <circle cx="55" cy="55" r="50" fill="none" stroke="rgba(129,140,248,0.2)" stroke-width="1.5" stroke-dasharray="6 5" />
        <circle cx="55" cy="55" r="36" fill="rgba(255,255,255,0.03)" stroke="rgba(129,140,248,0.4)" stroke-width="2.5" />
        <polyline points="36,55 49,68 74,42" fill="none" stroke="rgba(34,211,238,0.7)" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </div>

    <div class="el" style="top:55px;right:130px;animation-delay:0.4s">
      <svg width="120" height="120" viewBox="0 0 120 120">
        <circle cx="60" cy="60" r="50" fill="rgba(255,255,255,0.03)" stroke="rgba(129,140,248,0.25)" stroke-width="2" />
        <ellipse cx="60" cy="60" rx="68" ry="22" fill="none" stroke="rgba(129,140,248,0.15)" stroke-width="1.5" transform="rotate(-20,60,60)" />
        <circle cx="108" cy="45" r="5" fill="rgba(34,211,238,0.4)" />
        <text x="60" y="75" text-anchor="middle" fill="rgba(139,92,246,0.5)" font-size="48" font-weight="700" font-family="Georgia,serif">?</text>
      </svg>
    </div>

    <div class="el" style="top:270px;right:230px;animation-delay:1.1s">
      <svg width="70" height="70" viewBox="0 0 70 70">
        <rect x="4" y="4" width="62" height="62" rx="10" fill="rgba(255,255,255,0.03)" stroke="rgba(129,140,248,0.3)" stroke-width="2" />
        <circle cx="18" cy="22" r="4" fill="rgba(34,211,238,0.6)" />
        <rect x="28" y="19" width="28" height="5" rx="2.5" fill="rgba(129,140,248,0.3)" />
        <circle cx="18" cy="35" r="4" fill="rgba(34,211,238,0.6)" />
        <rect x="28" y="32" width="22" height="5" rx="2.5" fill="rgba(129,140,248,0.3)" />
        <circle cx="18" cy="48" r="4" fill="rgba(34,211,238,0.6)" />
        <rect x="28" y="45" width="26" height="5" rx="2.5" fill="rgba(129,140,248,0.3)" />
      </svg>
    </div>

    <div class="el" style="top:330px;right:170px;animation-delay:1.6s">
      <svg width="56" height="56" viewBox="0 0 56 56">
        <rect x="4" y="4" width="48" height="48" rx="8" fill="none" stroke="rgba(129,140,248,0.3)" stroke-width="2" />
        <polyline points="14,28 24,38 42,18" fill="none" stroke="rgba(34,211,238,0.65)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </div>

    <div class="el" style="bottom:160px;right:195px;animation-delay:0.9s">
      <svg width="80" height="70" viewBox="0 0 80 70">
        <rect x="8" y="40" width="16" height="26" rx="3" fill="rgba(46,37,112,0.6)" />
        <rect x="30" y="24" width="16" height="42" rx="3" fill="rgba(79,70,229,0.5)" />
        <rect x="52" y="10" width="16" height="56" rx="3" fill="rgba(129,140,248,0.6)" />
      </svg>
    </div>

    <div class="el" style="bottom:120px;right:110px;animation-delay:2.1s">
      <svg width="72" height="72" viewBox="0 0 72 72">
        <rect x="4" y="4" width="64" height="64" rx="10" fill="none" stroke="rgba(129,140,248,0.25)" stroke-width="2" />
        <text x="19" y="31" text-anchor="middle" fill="rgba(255,255,255,0.4)" font-size="14" font-weight="600" font-family="Georgia,serif">A</text>
        <text x="53" y="31" text-anchor="middle" fill="rgba(255,255,255,0.4)" font-size="14" font-weight="600" font-family="Georgia,serif">B</text>
        <text x="19" y="56" text-anchor="middle" fill="rgba(255,255,255,0.4)" font-size="14" font-weight="600" font-family="Georgia,serif">C</text>
        <text x="53" y="56" text-anchor="middle" fill="rgba(255,255,255,0.4)" font-size="14" font-weight="600" font-family="Georgia,serif">D</text>
        <line x1="36" y1="8" x2="36" y2="64" stroke="rgba(129,140,248,0.15)" stroke-width="1" />
        <line x1="8" y1="38" x2="64" y2="38" stroke="rgba(129,140,248,0.15)" stroke-width="1" />
      </svg>
    </div>

    <div class="el" style="bottom:185px;right:265px;animation-delay:1.4s">
      <svg width="60" height="55" viewBox="0 0 60 55">
        <polyline points="4,28 56,4 44,52 26,36 4,28" fill="none" stroke="rgba(129,140,248,0.4)" stroke-width="2" stroke-linejoin="round" />
        <line x1="26" y1="36" x2="56" y2="4" stroke="rgba(129,140,248,0.25)" stroke-width="1.5" />
        <path d="M 46 52 Q 70 30 46 4" fill="none" stroke="rgba(34,211,238,0.15)" stroke-width="1.5" stroke-dasharray="5 4" />
      </svg>
    </div>

    <div class="login-wrap">
      <div class="login-card" id="authCard">

        <div class="form-container login-container {{ !$showRegisterOnLoad ? 'active-view fade-in-view' : '' }}" id="loginContainer">
          <div class="card-title">Welcome back</div>
          <div class="card-sub">Sign in to manage your quizzes</div>

          @if (session('status'))
          <div style="background:rgba(129,140,248,0.1);color:#818CF8;font-size:13px;padding:10px 14px;border-radius:10px;margin-bottom:14px;border:1px solid rgba(129,140,248,0.2);">
            {{ session('status') }}
          </div>
          @endif

          <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="hidden" name="role" class="roleInput" value="teacher">

            <div class="field">
              <label>Email address</label>
              <div class="input-wrap">
                <svg width="17" height="17" viewBox="0 0 16 16" fill="none">
                  <rect x="1" y="3" width="14" height="10" rx="2" stroke="rgba(255,255,255,0.6)" stroke-width="1.4" />
                  <polyline points="1,4 8,9 15,4" stroke="rgba(255,255,255,0.6)" stroke-width="1.4" fill="none" stroke-linecap="round" />
                </svg>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus autocomplete="username" />
              </div>
              @error('email')
              <p style="color:#ffb3b3;font-size:12px;margin-top:5px;padding-left:4px;">{{ $message }}</p>
              @enderror
            </div>

            <div class="field">
              <label>Password</label>
              <div class="input-wrap">
                <svg width="17" height="17" viewBox="0 0 16 16" fill="none">
                  <rect x="3" y="7" width="10" height="8" rx="2" stroke="rgba(255,255,255,0.6)" stroke-width="1.4" />
                  <path d="M5 7V5a3 3 0 0 1 6 0v2" stroke="rgba(255,255,255,0.6)" stroke-width="1.4" fill="none" />
                  <circle cx="8" cy="11" r="1.3" fill="rgba(255,255,255,0.6)" />
                </svg>
                <input type="password" name="password" placeholder="********" id="passwordInput" required autocomplete="current-password" />
                <button type="button" onclick="document.getElementById('passwordInput').type = document.getElementById('passwordInput').type === 'password' ? 'text' : 'password'" style="background:none;border:none;cursor:pointer;padding:0;opacity:0.35;transition:opacity .2s;flex-shrink:0" onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='.35'">
                  <svg width="17" height="17" viewBox="0 0 16 16" fill="none">
                    <ellipse cx="8" cy="8" rx="7" ry="5" stroke="rgba(255,255,255,0.9)" stroke-width="1.4" />
                    <circle cx="8" cy="8" r="2" stroke="rgba(255,255,255,0.9)" stroke-width="1.4" />
                  </svg>
                </button>
              </div>
              @error('password')
              <p style="color:#ffb3b3;font-size:12px;margin-top:5px;padding-left:4px;">{{ $message }}</p>
              @enderror
            </div>

            <div class="row-meta">
              <label class="remember">
                <input type="checkbox" name="remember" id="rem" {{ old('remember') ? 'checked' : '' }}>
                <div class="check-box"></div>
                <span>Remember me</span>
              </label>
              @if (Route::has('password.request'))
              <a class="forgot" href="{{ route('password.request') }}">Forgot password?</a>
              @endif
            </div>

            <button type="submit" class="btn-login">Sign in</button>
          </form>

          <div class="divider"><span>or</span></div>

          <div class="card-footer">
            Don't have an account? <a href="#" id="toRegister">Create Account</a>
          </div>
        </div>


        <div class="form-container register-container {{ $showRegisterOnLoad ? 'active-view fade-in-view' : '' }}" id="registerContainer">
          <div class="card-title">Get Started</div>
          <div class="card-sub">Create your Quizora account</div>

          <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="role-toggle">
              <button type="button" class="role-btn role-btn-teacher active" onclick="selectRole('teacher')">
                <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                  <rect x="1" y="10" width="14" height="5" rx="2" stroke="currentColor" stroke-width="1.4" />
                  <path d="M8 2 L14 5.5 L8 9 L2 5.5 Z" stroke="currentColor" stroke-width="1.4" fill="none" stroke-linejoin="round" />
                </svg>
                Teacher
              </button>
              <button type="button" class="role-btn role-btn-student" onclick="selectRole('student')">
                <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                  <circle cx="8" cy="5" r="3" stroke="currentColor" stroke-width="1.4" />
                  <path d="M2 13.5c0-2.5 2.5-4.5 6-4.5s6 2 6 4.5" stroke="currentColor" stroke-width="1.4" fill="none" />
                </svg>
                Student
              </button>
            </div>
            <input type="hidden" name="role" class="roleInput" value="teacher">

            <div class="field">
              <label>Name</label>
              <div class="input-wrap">
                <svg width="17" height="17" viewBox="0 0 16 16" fill="none">
                  <circle cx="8" cy="5" r="3" stroke="rgba(255,255,255,0.6)" stroke-width="1.4" />
                  <path d="M2 13.5c0-2.5 2.5-4.5 6-4.5s6 2 6 4.5" stroke="rgba(255,255,255,0.6)" stroke-width="1.4" fill="none" />
                </svg>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Your full name" required autocomplete="name" />
              </div>
              @error('name')
              <p style="color:#ffb3b3;font-size:12px;margin-top:5px;padding-left:4px;">{{ $message }}</p>
              @enderror
            </div>

            <div class="field">
              <label>Email address</label>
              <div class="input-wrap">
                <svg width="17" height="17" viewBox="0 0 16 16" fill="none">
                  <rect x="1" y="3" width="14" height="10" rx="2" stroke="rgba(255,255,255,0.6)" stroke-width="1.4" />
                  <polyline points="1,4 8,9 15,4" stroke="rgba(255,255,255,0.6)" stroke-width="1.4" fill="none" stroke-linecap="round" />
                </svg>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="username" />
              </div>
              @error('email')
              <p style="color:#ffb3b3;font-size:12px;margin-top:5px;padding-left:4px;">{{ $message }}</p>
              @enderror
            </div>

            <div class="field">
              <label>Password</label>
              <div class="input-wrap">
                <svg width="17" height="17" viewBox="0 0 16 16" fill="none">
                  <rect x="3" y="7" width="10" height="8" rx="2" stroke="rgba(255,255,255,0.6)" stroke-width="1.4" />
                  <path d="M5 7V5a3 3 0 0 1 6 0v2" stroke="rgba(255,255,255,0.6)" stroke-width="1.4" fill="none" />
                </svg>
                <input type="password" name="password" placeholder="Create strong password" id="regPasswordInput" required autocomplete="new-password" />
                <button type="button" onclick="document.getElementById('regPasswordInput').type = document.getElementById('regPasswordInput').type === 'password' ? 'text' : 'password'" style="background:none;border:none;cursor:pointer;padding:0;opacity:0.35;transition:opacity .2s;flex-shrink:0" onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='.35'">
                  <svg width="17" height="17" viewBox="0 0 16 16" fill="none">
                    <ellipse cx="8" cy="8" rx="7" ry="5" stroke="rgba(255,255,255,0.9)" stroke-width="1.4" />
                    <circle cx="8" cy="8" r="2" stroke="rgba(255,255,255,0.9)" stroke-width="1.4" />
                  </svg>
                </button>
              </div>
              @error('password')
              <p style="color:#ffb3b3;font-size:12px;margin-top:5px;padding-left:4px;">{{ $message }}</p>
              @enderror
            </div>

            <div class="field" style="margin-bottom: 24px;">
              <label>Confirm Password</label>
              <div class="input-wrap">
                <svg width="17" height="17" viewBox="0 0 16 16" fill="none">
                  <rect x="3" y="7" width="10" height="8" rx="2" stroke="rgba(255,255,255,0.6)" stroke-width="1.4" />
                  <path d="M5 7V5a3 3 0 0 1 6 0v2" stroke="rgba(255,255,255,0.6)" stroke-width="1.4" fill="none" />
                </svg>
                <input type="password" name="password_confirmation" placeholder="Repeat your password" id="regConfirmPasswordInput" required autocomplete="new-password" />
              </div>
            </div>

            <button type="submit" class="btn-login">Register</button>
          </form>

          <div class="divider"><span>or</span></div>

          <div class="card-footer">
            Already registered? <a href="#" id="toLogin">Sign In</a>
          </div>
        </div>

      </div>
    </div>

  </div>

  <script>
    const card = document.getElementById('authCard');
    const loginContainer = document.getElementById('loginContainer');
    const registerContainer = document.getElementById('registerContainer');

    window.addEventListener('DOMContentLoaded', () => {
      card.style.height = card.offsetHeight + 'px';
    });

    function selectRole(role) {
      document.querySelectorAll('.role-btn-teacher').forEach(b =>
        b.classList.toggle('active', role === 'teacher'));
      document.querySelectorAll('.role-btn-student').forEach(b =>
        b.classList.toggle('active', role === 'student'));
      document.querySelectorAll('.roleInput').forEach(i => i.value = role);
    }

    function switchView(hideContainer, showContainer) {
      hideContainer.classList.remove('fade-in-view');
      hideContainer.classList.remove('active-view');

      showContainer.classList.add('active-view');

      card.style.height = 'auto';
      const targetHeight = card.offsetHeight;
      card.style.height = card.offsetHeight + 'px';
      card.offsetHeight; // force reflow
      card.style.height = targetHeight + 'px';

      showContainer.classList.add('fade-in-view');
    }

    document.getElementById('toRegister').addEventListener('click', e => {
      e.preventDefault();
      switchView(loginContainer, registerContainer);
    });

    document.getElementById('toLogin').addEventListener('click', e => {
      e.preventDefault();
      switchView(registerContainer, loginContainer);
    });
  </script>
</body>

</html>