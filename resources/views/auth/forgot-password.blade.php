<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <title>Quizora — Forgot Password</title>
    <style>
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

        .login-wrap {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            width: 390px;
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
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .field {
            margin-bottom: 18px;
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
            margin-top: 8px;
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

        .card-footer {
            text-align: center;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.38);
            margin-top: 22px;
        }

        .card-footer a {
            color: #818CF8;
            text-decoration: none;
            font-weight: 600;
            transition: color .2s;
        }

        .card-footer a:hover {
            color: #fff;
        }

        .status-msg {
            background: rgba(129, 140, 248, 0.1);
            color: #818CF8;
            font-size: 13px;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 16px;
            border: 1px solid rgba(129, 140, 248, 0.2);
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="auth-bg">
        <nav class="top-nav">
            <a href="{{ url('/') }}" style="text-decoration:none;">
                <div class="nav-logo">Quiz<span>ora</span></div>
            </a>
        </nav>

        <div class="login-wrap">
            <div class="login-card">
                <div class="card-title">Forgot password?</div>
                <div class="card-sub">
                    No problem. Enter your email and we’ll send you a reset link.
                </div>

                @if (session('status'))
                <div class="status-msg">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

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

                    <button type="submit" class="btn-login">Email Password Reset Link</button>
                </form>

                <div class="card-footer">
                    Remember your password? <a href="{{ route('login') }}">Sign In</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>