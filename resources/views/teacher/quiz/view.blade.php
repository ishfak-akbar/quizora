@extends('layouts.teacher')
@section('title', 'Quizora — ' . $quiz->title)

@push('styles')
<style>
    .qv-layout {
        display: flex;
        gap: 20px;
        align-items: stretch;
        margin-bottom: 24px;
    }

    /* STATS CARD */
   .qv-stats-card {
        background: linear-gradient(180deg, rgba(129,140,248,0.16) 0%, rgba(30,26,62,0.4) 100%);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 16px;
        padding: 8px 20px;
        width: 210px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        box-shadow: 0 8px 32px rgba(0,0,0,0.25);
    }

    .qv-metric {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .qv-metric:last-child {
        border-bottom: none;
    }

    .qv-metric-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .qv-metric.purple .qv-metric-icon {
        background: rgba(79, 70, 229, 0.18);
        color: #818CF8;
    }

    .qv-metric.amber .qv-metric-icon {
        background: rgba(245, 158, 11, 0.15);
        color: #F59E0B;
    }

    .qv-metric.cyan .qv-metric-icon {
        background: rgba(34, 211, 238, 0.15);
        color: #22D3EE;
    }

    .qv-metric.green .qv-metric-icon {
        background: rgba(52, 211, 153, 0.15);
        color: #34D399;
    }

    .qv-metric-value {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        line-height: 1.2;
    }

    .qv-metric-label {
        font-size: 11px;
        color: var(--color-text-muted);
        font-weight: 500;
        margin-top: 1px;
    }

    /* HERO CARD */
    .qv-hero {
        flex: 1;
        min-width: 0;
        background: linear-gradient(135deg, #2E2570 0%, #4F46E5 50%, #818CF8 100%);
        border-radius: 16px;
        padding: 26px 30px;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .qv-hero::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.07);
    }

    .qv-hero::after {
        content: '';
        position: absolute;
        bottom: -60px;
        left: 30%;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
    }

    .qv-hero-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        position: relative;
        z-index: 1;
        margin-bottom: 16px;
    }

    .qv-hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 5px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .qv-hero h1 {
        font-size: 24px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .qv-hero-meta {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }

    .qv-hero-meta span {
        font-size: 12px;
        font-weight: 600;
        color: #fff;
        background: rgba(255, 255, 255, 0.14);
        padding: 5px 12px;
        border-radius: 20px;
    }

    .qv-hero-actions {
        display: flex;
        gap: 10px;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }

    .qv-hero-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 13px;
        font-weight: 700;
        padding: 9px 16px;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .qv-hero-btn.ghost {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #fff;
    }

    .qv-hero-btn.ghost:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    .qv-hero-btn.solid {
        background: #fff;
        color: var(--color-primary-solid);
    }

    .qv-hero-btn.solid:hover {
        background: rgba(255, 255, 255, 0.9);
    }

    .qv-hero-desc {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.14);
        position: relative;
        z-index: 1;
    }

    .qv-hero-desc-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 6px;
    }

    .qv-hero-desc p {
        font-size: 13.5px;
        color: rgba(255, 255, 255, 0.88);
        line-height: 1.6;
    }

    @media (max-width: 820px) {
        .qv-layout {
            flex-direction: column;
        }

        .qv-stats-card {
            width: 100%;
            flex-direction: row;
            flex-wrap: wrap;
            padding: 4px 16px;
        }

        .qv-metric {
            flex: 1 1 50%;
            border-bottom: none;
            border-right: 1px solid var(--color-border-light);
        }

        .qv-metric:nth-child(2n) {
            border-right: none;
        }
    }

    /* QUESTIONS */
    .qv-section-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .qv-section-title h2 {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
    }

    .qv-section-title span {
        font-size: 12px;
        color: var(--color-text-muted);
    }

    .qv-q-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        padding: 20px 22px;
        margin-bottom: 14px;
        transition: border-color 0.2s;
    }

    .qv-q-card:hover {
        border-color: rgba(79, 70, 229, 0.35);
    }

    .qv-q-top {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 14px;
    }

    .qv-q-badge {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        flex-shrink: 0;
        background: linear-gradient(135deg, #4F46E5, #818CF8);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
    }

    .qv-q-text {
        flex: 1;
        font-size: 14.5px;
        font-weight: 600;
        color: #fff;
        line-height: 1.5;
    }

    .qv-q-marks {
        flex-shrink: 0;
        font-size: 11px;
        font-weight: 700;
        color: #F59E0B;
        background: rgba(245, 158, 11, 0.15);
        padding: 4px 11px;
        border-radius: 20px;
        white-space: nowrap;
    }

    .qv-opts {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding-left: 46px;
    }

    .qv-opt {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 500;
        color: var(--color-text-secondary);
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--color-border-light);
        padding: 9px 13px;
        border-radius: 9px;
    }

    .qv-opt .letter {
        width: 20px;
        height: 20px;
        border-radius: 6px;
        flex-shrink: 0;
        background: rgba(255, 255, 255, 0.06);
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color-text-muted);
    }

    .qv-opt.correct {
        background: rgba(52, 211, 153, 0.12);
        border-color: rgba(52, 211, 153, 0.4);
        color: #6EE7B7;
        font-weight: 600;
    }

    .qv-opt.correct .letter {
        background: #34D399;
        color: #0e0b20;
    }

    .qv-empty {
        text-align: center;
        padding: 40px;
        color: var(--color-text-muted);
        font-size: 13px;
    }

    @media (max-width: 900px) {
        .qv-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 700px) {
        .qv-hero-top {
            flex-direction: column;
        }

        .qv-opts {
            grid-template-columns: 1fr;
            padding-left: 0;
        }
    }
</style>
@endpush

@section('content')

<div class="qv-layout">


    <div class="qv-hero">
        <div class="qv-hero-top">
            <div class="qv-hero-pill"><i class="ti ti-file-description"></i> Quiz Overview</div>
            <div class="qv-hero-actions">
                <a href="{{ route('teacher.quizzes') }}" class="qv-hero-btn ghost"><i class="ti ti-arrow-left"></i> Back</a>
                <a href="{{ route('teacher.quiz.edit', $quiz->id) }}" class="qv-hero-btn ghost"><i class="ti ti-edit"></i> Edit</a>
                <a href="{{ route('teacher.quiz.print', $quiz->id) }}" target="_blank" class="qv-hero-btn solid"><i class="ti ti-printer"></i> Print</a>
            </div>
        </div>

        <h1>{{ $quiz->title }}</h1>
        <div class="qv-hero-meta">
            <span><i class="ti ti-tag"></i> {{ $quiz->category ?? 'General' }}</span>
            <span><i class="ti ti-gauge"></i> {{ ucfirst($quiz->difficulty) }}</span>
            <span><i class="ti ti-circle-dot"></i> {{ ucfirst($quiz->display_status) }}</span>
        </div>

        @if($quiz->description)
        <div class="qv-hero-desc">
            <div class="qv-hero-desc-label">Overview</div>
            <p>{{ $quiz->description }}</p>
        </div>
        @endif
    </div>
    <div class="qv-stats-card">
        <div class="qv-metric purple">
            <div class="qv-metric-icon"><i class="ti ti-help-circle"></i></div>
            <div>
                <div class="qv-metric-value">{{ $quiz->questions->count() }}</div>
                <div class="qv-metric-label">Questions</div>
            </div>
        </div>
        <div class="qv-metric amber">
            <div class="qv-metric-icon"><i class="ti ti-medal"></i></div>
            <div>
                <div class="qv-metric-value">{{ $totalMarks }}</div>
                <div class="qv-metric-label">Total Marks</div>
            </div>
        </div>
        <div class="qv-metric cyan">
            <div class="qv-metric-icon"><i class="ti ti-clock"></i></div>
            <div>
                <div class="qv-metric-value">{{ $quiz->time_limit ? $quiz->time_limit . ' min' : '—' }}</div>
                <div class="qv-metric-label">Time Limit</div>
            </div>
        </div>
        <div class="qv-metric green">
            <div class="qv-metric-icon"><i class="ti ti-users"></i></div>
            <div>
                <div class="qv-metric-value">{{ $submittedCount }}</div>
                <div class="qv-metric-label">Submissions</div>
            </div>
        </div>
    </div>
</div>

<div class="qv-section-title">
    <h2>Questions</h2>
    <span>{{ $quiz->questions->count() }} total</span>
</div>

@forelse($quiz->questions as $index => $question)
<div class="qv-q-card">
    <div class="qv-q-top">
        <div class="qv-q-badge">{{ $index + 1 }}</div>
        <div class="qv-q-text">{{ $question->question_text }}</div>
        <div class="qv-q-marks">{{ $question->marks }} mark{{ $question->marks > 1 ? 's' : '' }}</div>
    </div>
    <div class="qv-opts">
        @foreach($question->options as $i => $option)
        <div class="qv-opt {{ $option->is_correct ? 'correct' : '' }}">
            <span class="letter">{{ chr(65+$i) }}</span> {{ $option->option_text }}
        </div>
        @endforeach
    </div>
</div>
@empty
<div class="qv-empty">No questions added yet.</div>
@endforelse

@endsection