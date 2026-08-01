@extends('layouts.admin')

@section('title', 'Quizora — ' . $quiz->title)
@section('page-title', 'Quiz Overview')
@section('page-subtitle', $quiz->title)

@push('styles')
<style>
    .qv-layout {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-bottom: 28px;
    }

    .qv-hero {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 16px;
        padding: 28px;
        position: relative;
        overflow: hidden;
    }

    .qv-hero-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .qv-hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(16, 185, 129, 0.12);
        color: #34D399;
        font-size: 12px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
    }

    .qv-hero-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .qv-hero-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        font-family: var(--font);
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .qv-hero-btn.ghost {
        background: transparent;
        border: 1px solid var(--color-border-light);
        color: var(--color-text-secondary);
    }

    .qv-hero-btn.ghost:hover {
        background: var(--color-bg-row-hover);
        color: #fff;
    }

    .qv-hero-btn.solid {
        background: #10B981;
        color: #fff;
    }

    .qv-hero-btn.solid:hover {
        background: #059669;
    }

    .qv-hero-btn.danger {
        background: rgba(248, 113, 113, 0.15);
        border: 1px solid rgba(248, 113, 113, 0.3);
        color: #F87171;
    }

    .qv-hero-btn.danger:hover {
        background: rgba(248, 113, 113, 0.25);
    }

    .qv-hero h1 {
        font-size: 24px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 10px;
    }

    .qv-hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        font-size: 13px;
        color: var(--color-text-muted);
        margin-bottom: 16px;
    }

    .qv-hero-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .qv-hero-desc {
        margin-top: 8px;
        padding-top: 16px;
        border-top: 1px solid var(--color-border-light);
    }

    .qv-hero-desc-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--color-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 6px;
    }

    .qv-hero-desc p {
        font-size: 14px;
        color: var(--color-text-secondary);
        line-height: 1.6;
    }

    .qv-stats-card {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
    }

    .qv-metric {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .qv-metric-icon {
        width: 42px;
        height: 42px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .qv-metric.purple .qv-metric-icon {
        background: rgba(16, 185, 129, 0.15);
        color: #34D399;
    }

    .qv-metric.amber .qv-metric-icon {
        background: rgba(245, 158, 11, 0.15);
        color: #F59E0B;
    }

    .qv-metric.cyan .qv-metric-icon {
        background: rgba(45, 212, 191, 0.15);
        color: #2DD4BF;
    }

    .qv-metric.green .qv-metric-icon {
        background: rgba(16, 185, 129, 0.15);
        color: #10B981;
    }

    .qv-metric-value {
        font-size: 22px;
        font-weight: 700;
        color: #fff;
        line-height: 1.1;
    }

    .qv-metric-label {
        font-size: 12px;
        color: var(--color-text-muted);
        margin-top: 2px;
    }

    .qv-section-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .qv-section-title h2 {
        font-size: 16px;
        font-weight: 700;
        color: #fff;
    }

    .qv-section-title span {
        font-size: 13px;
        color: var(--color-text-muted);
    }

    .qv-q-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 14px;
    }

    .qv-q-top {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 14px;
    }

    .qv-q-badge {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: rgba(16, 185, 129, 0.15);
        color: #34D399;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .qv-q-text {
        flex: 1;
        font-size: 14.5px;
        font-weight: 600;
        color: #fff;
        line-height: 1.5;
    }

    .qv-q-marks {
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-muted);
        white-space: nowrap;
    }

    .qv-opts {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding-left: 42px;
    }

    .qv-opt {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--color-border-light);
        border-radius: 10px;
        font-size: 13px;
        color: var(--color-text-secondary);
    }

    .qv-opt.correct {
        background: rgba(16, 185, 129, 0.1);
        border-color: rgba(16, 185, 129, 0.35);
        color: #34D399;
        font-weight: 600;
    }

    .qv-opt .letter {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .qv-opt.correct .letter {
        background: rgba(16, 185, 129, 0.25);
        color: #34D399;
    }

    .qv-empty {
        text-align: center;
        padding: 40px;
        color: var(--color-text-muted);
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
    }

    @media (max-width: 900px) {
        .qv-stats-card {
            grid-template-columns: repeat(2, 1fr);
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
                <a href="javascript:history.back()" class="qv-hero-btn ghost">
                    <i class="ti ti-arrow-left"></i> Back
                </a>

                @if($quiz->status === 'active')
                <form method="POST" action="{{ route('admin.quizzes.close', $quiz) }}" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="qv-hero-btn ghost"
                        onclick="return confirm('Force close this quiz?')">
                        <i class="ti ti-player-stop"></i> Force Close
                    </button>
                </form>
                @endif

                <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" style="display:inline;"
                    onsubmit="return confirm('Are you sure you want to delete this quiz?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="qv-hero-btn danger">
                        <i class="ti ti-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <h1>{{ $quiz->title }}</h1>

        <div class="qv-hero-meta">
            <span><i class="ti ti-user"></i> {{ $quiz->teacher->name ?? 'Unknown' }}</span>
            <span><i class="ti ti-tag"></i> {{ $quiz->category ?? 'General' }}</span>
            <span><i class="ti ti-gauge"></i> {{ ucfirst($quiz->difficulty ?? 'medium') }}</span>
            <span><i class="ti ti-circle-dot"></i> {{ ucfirst($quiz->display_status) }}</span>
            <span><i class="ti ti-eye"></i> {{ ucfirst($quiz->visibility) }}</span>
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
            <span class="letter">{{ chr(65 + $i) }}</span> {{ $option->option_text }}
        </div>
        @endforeach
    </div>
</div>
@empty
<div class="qv-empty">No questions added yet.</div>
@endforelse

@endsection