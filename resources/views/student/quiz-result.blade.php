@extends('layouts.student')
@section('title', 'Quizora — Result: ' . $quiz->title)

@push('styles')
<style>
    .result-hero {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 20px;
        padding: 40px;
        text-align: center;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .result-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        left: 50%;
        transform: translateX(-50%);
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(79, 70, 229, 0.15) 0%, transparent 70%);
    }

    .result-badge {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 20px;
        margin-bottom: 20px;
    }

    .result-badge.pass {
        background: rgba(52, 211, 153, 0.15);
        color: var(--color-status-success);
    }

    .result-badge.fail {
        background: rgba(248, 113, 113, 0.15);
        color: var(--color-status-error);
    }

    .score-ring-wrap {
        position: relative;
        z-index: 1;
        margin-bottom: 20px;
    }

    .score-circle {
        width: 160px;
        height: 160px;
        margin: 0 auto;
        position: relative;
    }

    .score-circle svg {
        transform: rotate(-90deg);
    }

    .score-circle-bg {
        fill: none;
        stroke: rgba(255, 255, 255, 0.06);
        stroke-width: 10;
    }

    .score-circle-fill {
        fill: none;
        stroke-width: 10;
        stroke-linecap: round;
        transition: stroke-dashoffset 1.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .score-circle-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .score-circle-pct {
        font-size: 36px;
        font-weight: 700;
        color: #fff;
        line-height: 1;
    }

    .score-circle-label {
        font-size: 11px;
        color: var(--color-text-muted);
        margin-top: 4px;
    }

    .result-title {
        font-size: 20px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 6px;
        position: relative;
        z-index: 1;
    }

    .result-subtitle {
        font-size: 13px;
        color: var(--color-text-muted);
        position: relative;
        z-index: 1;
    }

    .result-stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 24px;
    }

    .result-mini-stat {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        padding: 18px;
        text-align: center;
    }

    .result-mini-stat i {
        font-size: 22px;
        color: var(--color-primary-glow);
        margin-bottom: 8px;
        display: block;
    }

    .result-mini-stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #fff;
    }

    .result-mini-stat-label {
        font-size: 11px;
        color: var(--color-text-muted);
        margin-top: 3px;
    }

    .result-actions {
        display: flex;
        gap: 12px;
        margin-bottom: 28px;
    }

    .result-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 13px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        font-family: var(--font);
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
    }

    .result-btn-primary {
        background: var(--color-primary-solid);
        color: #fff;
    }

    .result-btn-primary:hover {
        background: #4338CA;
    }

    .result-btn-secondary {
        background: transparent;
        color: var(--color-text-secondary);
        border: 1px solid var(--color-border-light);
    }

    .result-btn-secondary:hover {
        background: var(--color-bg-row-hover);
        color: #fff;
    }

    .review-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .review-header h2 {
        font-size: 16px;
        font-weight: 700;
        color: #fff;
    }

    .review-filter {
        display: flex;
        gap: 8px;
    }

    .review-filter-btn {
        font-size: 12px;
        font-weight: 500;
        padding: 6px 14px;
        border-radius: 20px;
        border: 1px solid var(--color-border-light);
        background: transparent;
        color: var(--color-text-secondary);
        cursor: pointer;
        font-family: var(--font);
        transition: all 0.2s;
    }

    .review-filter-btn:hover,
    .review-filter-btn.active {
        background: rgba(79, 70, 229, 0.15);
        border-color: rgba(79, 70, 229, 0.4);
        color: #fff;
    }

    .review-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 12px;
        transition: border-color 0.2s;
    }

    .review-card.hidden {
        display: none;
    }

    .review-q-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }

    .review-q-num {
        font-size: 11px;
        font-weight: 700;
        color: var(--color-primary-glow);
        margin-bottom: 4px;
    }

    .review-q-text {
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        line-height: 1.5;
    }

    .review-status-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .review-status-icon.correct {
        background: rgba(52, 211, 153, 0.15);
        color: var(--color-status-success);
    }

    .review-status-icon.incorrect {
        background: rgba(248, 113, 113, 0.15);
        color: var(--color-status-error);
    }

    .review-status-icon.skipped {
        background: rgba(107, 114, 128, 0.2);
        color: var(--color-text-muted);
    }

    .review-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .review-opt {
        font-size: 12px;
        padding: 9px 12px;
        border-radius: 8px;
        color: var(--color-text-secondary);
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--color-border-light);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .review-opt.correct-answer {
        background: rgba(52, 211, 153, 0.1);
        border-color: rgba(52, 211, 153, 0.4);
        color: var(--color-status-success);
    }

    .review-opt.wrong-selected {
        background: rgba(248, 113, 113, 0.1);
        border-color: rgba(248, 113, 113, 0.4);
        color: var(--color-status-error);
    }

    .review-opt i {
        font-size: 14px;
        flex-shrink: 0;
    }

    .skipped-note {
        font-size: 12px;
        color: var(--color-text-muted);
        font-style: italic;
        margin-top: 8px;
    }

    #scoreRing {
        transition: stroke-dashoffset 1.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
@endpush

@section('content')

@php
$passed = $attempt->score_percentage >= 50;
$pct = $attempt->score_percentage;
$circumference = 2 * M_PI * 70; // r=70
$offset = $circumference - ($pct / 100) * $circumference;
$ringColor = $pct >= 75 ? '#34D399' : ($pct >= 50 ? '#F59E0B' : '#F87171');
$correctCount = $answers->where('is_correct', true)->count();
$incorrectCount = $answers->where('is_correct', false)->whereNotNull('option_id')->count();
$skippedCount = $answers->whereNull('option_id')->count();
@endphp

{{-- RESULT HERO --}}
<div class="result-hero" style="max-width:760px;margin:0 auto 24px;">
    <span class="result-badge {{ $passed ? 'pass' : 'fail' }}">
        <i class="ti ti-{{ $passed ? 'circle-check' : 'circle-x' }}"></i>
        {{ $passed ? 'Passed' : 'Failed' }}
    </span>

    <div class="score-ring-wrap">
        <div class="score-circle">
            <svg width="160" height="160" viewBox="0 0 160 160">
                <circle class="score-circle-bg" cx="80" cy="80" r="70"></circle>
                <circle class="score-circle-fill" cx="80" cy="80" r="70" id="scoreRing">
            </svg>
            <div class="score-circle-text">
                <div class="score-circle-pct">{{ $pct }}%</div>
                <div class="score-circle-label">{{ $attempt->score }} / {{ $attempt->total_marks }} marks</div>
            </div>
        </div>
    </div>

    <div class="result-title">
        @if($pct >= 90) Excellent work, {{ auth()->user()->name }}!
        @elseif($pct >= 75) Great job, {{ auth()->user()->name }}!
        @elseif($pct >= 50) Good effort, {{ auth()->user()->name }}!
        @else Keep practicing, {{ auth()->user()->name }}!
        @endif
    </div>
    <div class="result-subtitle">{{ $quiz->title }}</div>
</div>

{{-- STATS ROW --}}
<div class="result-stats-row" style="max-width:760px;margin:0 auto 24px;">
    <div class="result-mini-stat">
        <i class="ti ti-check" style="color:var(--color-status-success)"></i>
        <div class="result-mini-stat-value" style="color:var(--color-status-success)">{{ $correctCount }}</div>
        <div class="result-mini-stat-label">Correct</div>
    </div>
    <div class="result-mini-stat">
        <i class="ti ti-x" style="color:var(--color-status-error)"></i>
        <div class="result-mini-stat-value" style="color:var(--color-status-error)">{{ $incorrectCount }}</div>
        <div class="result-mini-stat-label">Incorrect</div>
    </div>
    <div class="result-mini-stat">
        <i class="ti ti-minus" style="color:var(--color-text-muted)"></i>
        <div class="result-mini-stat-value" style="color:var(--color-text-muted)">{{ $skippedCount }}</div>
        <div class="result-mini-stat-label">Skipped</div>
    </div>
    <div class="result-mini-stat">
        <i class="ti ti-clock"></i>
        <div class="result-mini-stat-value">
            @if($attempt->submitted_at && $attempt->started_at)
            {{ gmdate('i:s', $attempt->submitted_at->diffInSeconds($attempt->started_at)) }}
            @else —
            @endif
        </div>
        <div class="result-mini-stat-label">Time Taken</div>
    </div>
</div>

{{-- ACTIONS --}}
<div class="result-actions" style="max-width:760px;margin:0 auto 28px;">
    <a href="{{ route('student.browse') }}" class="result-btn result-btn-secondary">
        <i class="ti ti-compass"></i> Browse More
    </a>
    @if($quiz->max_attempts > 1)
    @php
    $attemptsUsed = \App\Models\Attempt::where('quiz_id', $quiz->id)
    ->where('student_id', auth()->id())
    ->where('status', 'submitted')->count();
    @endphp
    @if($attemptsUsed < $quiz->max_attempts)
        <a href="{{ route('student.quiz.take', $quiz->id) }}" class="result-btn result-btn-secondary">
            <i class="ti ti-refresh"></i> Retry Quiz
        </a>
        @endif
        @endif
        <a href="{{ route('student.leaderboard.page') }}" class="result-btn result-btn-primary">
            <i class="ti ti-trophy"></i> Leaderboard
        </a>
</div>

{{-- ANSWER REVIEW --}}
@if($quiz->show_results)
<div style="max-width:760px;margin:0 auto;">
    <div class="review-header">
        <h2>Answer Review</h2>
        <div class="review-filter">
            <button class="review-filter-btn active" data-filter="all">All</button>
            <button class="review-filter-btn" data-filter="correct">Correct</button>
            <button class="review-filter-btn" data-filter="incorrect">Incorrect</button>
            @if($skippedCount > 0)
            <button class="review-filter-btn" data-filter="skipped">Skipped</button>
            @endif
        </div>
    </div>

    @foreach($answers as $i => $answer)
    @php
    $question = $answer->question;
    $letters = ['A','B','C','D','E','F'];
    $isCorrect = $answer->is_correct;
    $isSkipped = is_null($answer->option_id);
    $statusClass = $isSkipped ? 'skipped' : ($isCorrect ? 'correct' : 'incorrect');
    $filterAttr = $isSkipped ? 'skipped' : ($isCorrect ? 'correct' : 'incorrect');
    @endphp
    <div class="review-card" data-filter="{{ $filterAttr }}">
        <div class="review-q-header">
            <div>
                <div class="review-q-num">Question {{ $i + 1 }}</div>
                <div class="review-q-text">{{ $question->question_text }}</div>
            </div>
            <div class="review-status-icon {{ $statusClass }}">
                <i class="ti ti-{{ $isSkipped ? 'minus' : ($isCorrect ? 'check' : 'x') }}"></i>
            </div>
        </div>

        <div class="review-options">
            @foreach($question->options as $j => $option)
            @php
            $isThisCorrect = $option->is_correct;
            $isThisSelected = $answer->option_id == $option->id;
            $optClass = '';
            if ($isThisCorrect) $optClass = 'correct-answer';
            elseif ($isThisSelected && !$isThisCorrect) $optClass = 'wrong-selected';
            @endphp
            <div class="review-opt {{ $optClass }}">
                @if($isThisCorrect)
                <i class="ti ti-check"></i>
                @elseif($isThisSelected)
                <i class="ti ti-x"></i>
                @endif
                {{ $letters[$j] }}. {{ $option->option_text }}
            </div>
            @endforeach
        </div>

        @if($isSkipped)
        <div class="skipped-note">You skipped this question.</div>
        @endif
    </div>
    @endforeach
</div>
@else
<div style="max-width:760px;margin:0 auto;padding:32px;text-align:center;
    background:var(--color-bg-card);border:1px solid var(--color-border-light);
    border-radius:14px;color:var(--color-text-muted);font-size:13px;">
    <i class="ti ti-eye-off" style="font-size:32px;display:block;margin-bottom:12px;"></i>
    The teacher has disabled answer review for this quiz.
</div>
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Score Ring Animation
        const QUIZ_PCT = {
            {
                $pct
            }
        };

        try {
            const ring = document.getElementById('scoreRing');

            if (ring) {
                const circumference = 439.82;
                const color =
                    QUIZ_PCT >= 75 ?
                    '#34D399' :
                    (QUIZ_PCT >= 50 ? '#F59E0B' : '#F87171');

                ring.setAttribute('stroke', color);
                ring.setAttribute('stroke-dasharray', circumference);
                ring.setAttribute('stroke-dashoffset', circumference);

                setTimeout(() => {
                    const offset = circumference - (QUIZ_PCT / 100) * circumference;
                    ring.setAttribute('stroke-dashoffset', offset);
                }, 200);
            }
        } catch (e) {
            console.error(e);
        }

        // Answer Review Filter Buttons
        const filterButtons = document.querySelectorAll('.review-filter-btn');
        const reviewCards = document.querySelectorAll('.review-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {

                // Active button styling
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;

                reviewCards.forEach(card => {
                    if (filter === 'all' || card.dataset.filter === filter) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            });
        });

    });
</script>
@endpush