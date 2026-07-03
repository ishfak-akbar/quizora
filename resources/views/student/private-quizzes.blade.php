@extends('layouts.student')
@section('title', 'Quizora — Private Quizzes')

@push('styles')
<style>
    .unlock-hero {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 48px 24px;
        background: linear-gradient(135deg, #2E2570 0%, #4F46E5 50%, #818CF8 100%);
        border-radius: 18px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }

    .unlock-hero::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
    }

    .unlock-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: #fff;
        margin-bottom: 18px;
        position: relative;
        z-index: 1;
    }

    .unlock-hero h2 {
        font-size: 20px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 6px;
        position: relative;
        z-index: 1;
    }

    .unlock-hero p {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.75);
        margin-bottom: 24px;
        max-width: 380px;
        position: relative;
        z-index: 1;
    }

    .unlock-form {
        display: flex;
        gap: 10px;
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 360px;
    }

    .unlock-input {
        flex: 1;
        text-transform: uppercase;
        text-align: center;
        letter-spacing: 5px;
        font-size: 16px;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(8px);
        border: 1.5px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        padding: 14px;
        color: #fff;
        font-family: var(--font);
        outline: none;
        transition: all 0.2s;
    }

    .unlock-input::placeholder {
        color: rgba(255, 255, 255, 0.4);
        letter-spacing: 2px;
        font-size: 14px;
    }

    .unlock-input:focus {
        background: rgba(255, 255, 255, 0.18);
        border-color: rgba(255, 255, 255, 0.6);
    }

    .unlock-submit {
        background: #fff;
        color: var(--color-primary-solid);
        border: none;
        font-size: 13px;
        font-weight: 700;
        padding: 0 22px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .unlock-submit:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-1px);
    }

    .unlock-error {
        color: #FECACA;
        background: rgba(248, 113, 113, 0.2);
        border: 1px solid rgba(248, 113, 113, 0.4);
        font-size: 12.5px;
        font-weight: 600;
        padding: 10px 16px;
        border-radius: 10px;
        margin-top: 14px;
        position: relative;
        z-index: 1;
        max-width: 360px;
    }

    .section-title {
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .empty-private {
        text-align: center;
        padding: 50px 20px;
        color: var(--color-text-muted);
    }

    .empty-private i {
        font-size: 42px;
        display: block;
        margin-bottom: 12px;
        opacity: 0.35;
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <h1>Private Quizzes</h1>
    <p>Enter a code from your teacher to unlock a private quiz</p>
</div>

{{-- UNLOCK HERO --}}
<div class="unlock-hero">
    <div class="unlock-icon"><i class="ti ti-lock"></i></div>
    <h2>Have an access code?</h2>
    <p>Enter the 6-character code your teacher shared with you to unlock the quiz instantly.</p>

    <form method="POST" action="{{ route('student.quiz.unlock') }}" class="unlock-form">
        @csrf
        <input type="text" name="code" maxlength="6" placeholder="ABC123" required
            class="unlock-input" autocomplete="off" autofocus>
        <button type="submit" class="unlock-submit">Unlock</button>
    </form>

    @error('code')
    <div class="unlock-error">{{ $message }}</div>
    @enderror

    @if(session('error'))
    <div class="unlock-error">{{ session('error') }}</div>
    @endif
</div>

{{-- UNLOCKED QUIZZES --}}
<div class="section-title">
    <i class="ti ti-lock-open" style="color:var(--color-primary-glow);"></i>
    Your Unlocked Quizzes
</div>

@if($unlockedQuizzes->isEmpty())
<div class="empty-private">
    <i class="ti ti-lock-off"></i>
    <p>No private quizzes unlocked yet. Enter a code above to get started.</p>
</div>
@else
<div class="quiz-grid">
    @foreach($unlockedQuizzes as $quiz)
    @php
    [$bg, $icon] = \App\Helpers\QuizHelper::bookmarkBannerConfig($quiz->category ?? '', $loop->index);
    @endphp
    <a href="{{ route('student.quiz.detail', $quiz->id) }}" class="pquiz-card">
        <div class="pquiz-banner" style="background: {{ $bg }};">
            <div class="pquiz-banner-deco d1"></div>
            <div class="pquiz-banner-deco d2"></div>
            <i class="{{ $icon }} pquiz-banner-icon"></i>
        </div>

        <div class="pquiz-body">
            @if($quiz->category)
            <div class="pquiz-category">{{ $quiz->category }}</div>
            @endif
            <div class="pquiz-title">{{ $quiz->title }}</div>
            <div class="pquiz-meta">
                <div class="pquiz-meta-item">
                    <i class="ti ti-help-circle"></i> {{ $quiz->questions_count }} Qs
                </div>
                @if($quiz->time_limit)
                <div class="pquiz-meta-item">
                    <i class="ti ti-clock"></i> {{ $quiz->time_limit }} min
                </div>
                @endif
            </div>
            <div class="pquiz-footer">
                <span class="diff-badge diff-{{ $quiz->difficulty }}">{{ ucfirst($quiz->difficulty) }}</span>
                <span class="pquiz-take-btn"><i class="ti ti-player-play"></i> Start</span>
            </div>
        </div>
    </a>
    @endforeach
</div>
@endif

@endsection