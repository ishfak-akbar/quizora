@extends('layouts.student')
@section('title', 'Quizora — My Results')

@push('styles')
<style>
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }

    .mini-stat {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        padding: 18px;
        text-align: center;
    }

    .mini-stat-value {
        font-size: 22px;
        font-weight: 700;
        color: #fff;
    }

    .mini-stat-label {
        font-size: 11px;
        color: var(--color-text-muted);
        margin-top: 4px;
    }

    .filters {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .filter-chip {
        font-size: 13px;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 20px;
        border: 1.5px solid var(--color-border-light);
        background: var(--color-bg-card);
        color: var(--color-text-secondary);
        cursor: pointer;
        transition: all 0.2s;
        font-family: var(--font);
    }

    .filter-chip:hover,
    .filter-chip.active {
        background: rgba(79, 70, 229, 0.15);
        border-color: rgba(79, 70, 229, 0.4);
        color: #fff;
    }

    .results-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .result-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-left: 3px solid transparent;
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 18px;
        transition: border-color 0.2s, transform 0.2s;
    }

    .result-card:hover {
        border-color: rgba(79, 70, 229, 0.4);
        transform: translateY(-2px);
    }

    .result-card[data-status="passed"] {
        border-left-color: #34D399;
    }

    .result-card[data-status="failed"] {
        border-left-color: #F87171;
    }

    .result-score-badge {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        font-weight: 700;
        flex-shrink: 0;
        border: 2px solid;
    }

    .result-card-info {
        flex: 1;
        min-width: 0;
    }

    .result-card-title {
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .result-card-meta {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .result-card-meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: var(--color-text-muted);
    }

    .result-card-meta-item i {
        font-size: 14px;
    }

    .result-pass-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        flex-shrink: 0;
    }

    .pass-badge {
        background: rgba(52, 211, 153, 0.15);
        color: #34D399;
    }

    .fail-badge {
        background: rgba(248, 113, 113, 0.15);
        color: #F87171;
    }

    .result-view-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        color: var(--color-primary-glow);
        text-decoration: none;
        flex-shrink: 0;
        padding: 8px 14px;
        border-radius: 9px;
        border: 1px solid var(--color-border-light);
        transition: all 0.2s;
    }

    .result-view-btn:hover {
        background: var(--color-bg-row-hover);
        border-color: rgba(79, 70, 229, 0.4);
    }

    .hidden {
        display: none !important;
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <h1>My Results</h1>
    <p>Track your performance across all attempted quizzes</p>
</div>

<div class="stats-row">
    <div class="mini-stat">
        <div class="mini-stat-value">{{ $totalAttempts }}</div>
        <div class="mini-stat-label">Total Attempts</div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-value" style="color:#34D399">{{ $avgScore }}%</div>
        <div class="mini-stat-label">Average Score</div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-value" style="color:var(--color-primary-glow)">{{ $bestScore }}%</div>
        <div class="mini-stat-label">Best Score</div>
    </div>
    <div class="mini-stat">
        <div class="mini-stat-value" style="color:#F59E0B">{{ $quizzesPassed }}</div>
        <div class="mini-stat-label">Quizzes Passed</div>
    </div>
</div>

<div class="filters">
    <button class="filter-chip active" data-filter="all">All</button>
    <button class="filter-chip" data-filter="passed">Passed</button>
    <button class="filter-chip" data-filter="failed">Failed</button>
    <button class="filter-chip" data-filter="month">This Month</button>
</div>

@if($attempts->isEmpty())
<div class="empty-state">
    <i class="ti ti-clipboard-off empty-icon"></i>
    <h3>No attempts yet</h3>
    <p>Take a quiz to see your results here</p>
    <a href="{{ route('student.browse') }}" class="empty-browse-btn">
        <i class="ti ti-compass"></i> Browse Quizzes
    </a>
</div>
@else
<div class="results-list" id="resultsList">
    @foreach($attempts as $attempt)
    @php
    $pct = $attempt->score_percentage;
    $passed = $pct >= 50;
    $color = $pct >= 75 ? '#34D399' : ($pct >= 50 ? '#F59E0B' : '#F87171');
    $date = ($attempt->submitted_at ?? $attempt->created_at);
    $thisMonth = $date->month === now()->month && $date->year === now()->year;
    @endphp
    <div class="result-card"
        data-status="{{ $passed ? 'passed' : 'failed' }}"
        data-month="{{ $thisMonth ? 'yes' : 'no' }}">

        <div class="result-score-badge" style="border-color:{{ $color }};color:{{ $color }};">
            {{ $pct }}%
        </div>

        <div class="result-card-info">
            <div class="result-card-title">{{ $attempt->quiz->title }}</div>
            <div class="result-card-meta">
                <div class="result-card-meta-item">
                    <i class="ti ti-calendar"></i>
                    {{ $date->format('M d, Y') }}
                </div>
                <div class="result-card-meta-item">
                    <i class="ti ti-checkup-list"></i>
                    {{ $attempt->score }} / {{ $attempt->total_marks }} marks
                </div>
                @if($attempt->quiz->category)
                <div class="result-card-meta-item">
                    <i class="ti ti-tag"></i>
                    {{ $attempt->quiz->category }}
                </div>
                @endif
            </div>
        </div>

        <span class="result-pass-badge {{ $passed ? 'pass-badge' : 'fail-badge' }}">
            {{ $passed ? 'Passed' : 'Failed' }}
        </span>

        <a href="{{ route('student.quiz.result', $attempt->quiz_id) }}" class="result-view-btn">
            View <i class="ti ti-arrow-right"></i>
        </a>
    </div>
    @endforeach
</div>
@endif

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;
            document.querySelectorAll('.result-card').forEach(card => {
                if (filter === 'all') card.classList.remove('hidden');
                else if (filter === 'passed') card.classList.toggle('hidden', card.dataset.status !== 'passed');
                else if (filter === 'failed') card.classList.toggle('hidden', card.dataset.status !== 'failed');
                else if (filter === 'month') card.classList.toggle('hidden', card.dataset.month !== 'yes');
            });
        });
    });
</script>
@endpush