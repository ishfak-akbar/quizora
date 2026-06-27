@extends('layouts.student')
@section('title', 'Quizora — ' . $quiz->title)

@push('styles')
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--color-text-muted);
        font-size: 13px;
        text-decoration: none;
        margin-bottom: 16px;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: #fff;
    }

    .detail-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 24px;
        align-items: start;
    }

    .detail-banner {
        height: 160px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 56px;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .detail-banner::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }

    .detail-category {
        font-size: 12px;
        font-weight: 600;
        color: var(--color-primary-glow);
        background: rgba(79, 70, 229, 0.15);
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
        margin-bottom: 12px;
    }

    .detail-title {
        font-size: 24px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 10px;
        line-height: 1.3;
    }

    .detail-desc {
        font-size: 14px;
        color: var(--color-text-secondary);
        line-height: 1.7;
        margin-bottom: 20px;
    }

    .detail-author {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 0;
        border-top: 1px solid var(--color-border-light);
        border-bottom: 1px solid var(--color-border-light);
        margin-bottom: 20px;
    }

    .author-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--color-primary-solid), var(--color-stat-purple));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
    }

    .author-info p:first-child {
        font-size: 13px;
        font-weight: 600;
        color: #fff;
    }

    .author-info p:last-child {
        font-size: 11px;
        color: var(--color-text-muted);
        margin-top: 1px;
    }

    .detail-section {
        margin-bottom: 24px;
    }

    .detail-section h3 {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 12px;
    }

    .topic-tags {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .topic-tag {
        font-size: 12px;
        color: var(--color-text-secondary);
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--color-border-light);
        padding: 6px 14px;
        border-radius: 20px;
    }

    .instructions-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .instruction-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 13px;
        color: var(--color-text-secondary);
        line-height: 1.6;
    }

    .instruction-item i {
        color: var(--color-status-success);
        font-size: 16px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .start-card {
        background: linear-gradient(145deg, rgba(30, 27, 57, 0.85) 0%, rgba(15, 12, 30, 0.95) 100%);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 20px;
        padding: 26px;
        position: sticky;
        top: 84px;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5),
            inset 0 1px 0px rgba(255, 255, 255, 0.1);
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .start-card:hover {
        border-color: #5134d3;
        box-shadow: 0 25px 45px -10px rgba(0, 0, 0, 0.6),
            0 0 20px -5px rgba(79, 70, 229, 0.2);
    }

    .start-stats {
        display: flex;
        flex-direction: column;
        gap: 14px;
        margin-bottom: 20px;
    }

    .start-stat-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
    }

    .start-stat-row .label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--color-text-muted);
    }

    .start-stat-row .label i {
        font-size: 16px;
        color: var(--color-primary-glow);
    }

    .start-stat-row .value {
        color: #fff;
        font-weight: 600;
    }

    .diff-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .diff-easy {
        background: rgba(52, 211, 153, 0.15);
        color: var(--color-status-success);
    }

    .diff-medium {
        background: rgba(245, 158, 11, 0.15);
        color: #F59E0B;
    }

    .diff-hard {
        background: rgba(248, 113, 113, 0.15);
        color: var(--color-status-error);
    }

    .start-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.06);
        margin: 18px 0;
    }

    .start-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: var(--color-primary-solid);
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        padding: 14px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        font-family: var(--font);
        text-decoration: none;
        transition: background 0.2s, transform 0.15s;
    }

    .start-btn:hover {
        background: #4338CA;
        transform: translateY(-1px);
    }

    .start-btn.disabled {
        background: rgba(255, 255, 255, 0.06);
        color: var(--color-text-muted);
        cursor: not-allowed;
        pointer-events: none;
    }

    .bookmark-btn {
        position: relative;
        margin-left: 14.5px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.02);
        color: var(--color-text-secondary);
        font-size: 13px;
        font-weight: 600;
        padding: 12px;
        border-radius: 12px;
        border: 1px solid var(--color-border-light);
        cursor: pointer;
        font-family: var(--font);
        transition: all 0.2s;
    }

    .bookmark-btn:hover {
        border-color: rgba(79, 70, 229, 0.4);
        background: rgba(79, 70, 229, 0.03);
        color: #fff;
    }

    .bookmark-btn.saved {
        color: #34D399;
        border-color: rgba(52, 211, 153, 0.3);
        background: rgba(52, 211, 153, 0.08);
    }

    .attempts-note {
        font-size: 11px;
        color: var(--color-text-muted);
        text-align: center;
        margin-top: 20px;
    }

    .no-attempts-note {
        font-size: 12px;
        color: var(--color-status-error);
        text-align: center;
        margin-top: 20px;
    }
</style>
@endpush

@section('content')

@php
use App\Helpers\QuizHelper;
$icon = QuizHelper::categoryIcon($quiz->category);

$gradients = [
'math' => 'linear-gradient(135deg,#7C3AED,#A78BFA)',
'science' => 'linear-gradient(135deg,#0891B2,#22D3EE)',
'physics' => 'linear-gradient(135deg,#0891B2,#22D3EE)',
'chemistry' => 'linear-gradient(135deg,#059669,#34D399)',
'biology' => 'linear-gradient(135deg,#15803D,#4ADE80)',
'english' => 'linear-gradient(135deg,#B45309,#F59E0B)',
'history' => 'linear-gradient(135deg,#92400E,#D97706)',
'geography' => 'linear-gradient(135deg,#065F46,#10B981)',
'bcs' => 'linear-gradient(135deg,#1E3A5F,#3B82F6)',
'computer' => 'linear-gradient(135deg,#1D4ED8,#60A5FA)',
'coding' => 'linear-gradient(135deg,#DB2777,#F472B6)',
'general' => 'linear-gradient(135deg,#4F46E5,#818CF8)',
'economics' => 'linear-gradient(135deg,#0F766E,#2DD4BF)',
'religion' => 'linear-gradient(135deg,#78350F,#D97706)',
];
$lower = strtolower($quiz->category ?? '');
$bg = 'linear-gradient(135deg,#4F46E5,#818CF8)';
foreach ($gradients as $key => $grad) {
if (str_contains($lower, $key)) { $bg = $grad; break; }
}

$teacherInitials = strtoupper(substr($quiz->teacher->name, 0, 1));
if (strpos($quiz->teacher->name, ' ') !== false) {
$teacherInitials .= strtoupper(substr($quiz->teacher->name, strpos($quiz->teacher->name, ' ') + 1, 1));
}
@endphp

<a href="{{ route('student.browse') }}" class="back-link">
    <i class="ti ti-arrow-left"></i> Back to Browse
</a>

<div class="detail-layout">

    {{-- MAIN INFO --}}
    <div>
        <div class="detail-banner" style="background: {{ $bg }};">
            <i class="{{ $icon }}"></i>
        </div>

        @if($quiz->category)
        <span class="detail-category">{{ $quiz->category }}</span>
        @endif

        <h1 class="detail-title">{{ $quiz->title }}</h1>

        @if($quiz->description)
        <p class="detail-desc">{{ $quiz->description }}</p>
        @endif

        <div class="detail-author">
            <div class="author-avatar">{{ $teacherInitials }}</div>
            <div class="author-info">
                <p>{{ $quiz->teacher->name }}</p>
                <p>Quiz Creator · {{ $quiz->teacher->quizzes()->count() }} quizzes published</p>
            </div>
        </div>

        @if($quiz->tags)
        <div class="detail-section">
            <h3>Topics Covered</h3>
            <div class="topic-tags">
                @foreach(explode(',', $quiz->tags) as $tag)
                <span class="topic-tag">{{ trim($tag) }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <div class="detail-section">
            <h3>Before You Start</h3>
            <div class="instructions-list">
                <div class="instruction-item">
                    <i class="ti ti-circle-check"></i>
                    This quiz contains {{ $questionCount }} multiple choice question{{ $questionCount !== 1 ? 's' : '' }}
                </div>
                @if($quiz->time_limit)
                <div class="instruction-item">
                    <i class="ti ti-circle-check"></i>
                    You have {{ $quiz->time_limit }} minutes to complete it — the timer starts once you begin
                </div>
                <div class="instruction-item">
                    <i class="ti ti-circle-check"></i>
                    The quiz will auto-submit when time runs out
                </div>
                @else
                <div class="instruction-item">
                    <i class="ti ti-circle-check"></i>
                    No time limit — complete at your own pace
                </div>
                @endif
                <div class="instruction-item">
                    <i class="ti ti-circle-check"></i>
                    You can attempt this quiz up to {{ $quiz->max_attempts }} time{{ $quiz->max_attempts !== 1 ? 's' : '' }}
                </div>
                @if($quiz->show_results)
                <div class="instruction-item">
                    <i class="ti ti-circle-check"></i>
                    Your results and correct answers will be shown immediately after submission
                </div>
                @endif
                @if($quiz->passing_score)
                <div class="instruction-item">
                    <i class="ti ti-circle-check"></i>
                    Passing score is {{ $quiz->passing_score }}%
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- START SIDEBAR --}}
    <div class="start-card">
        <div class="start-stats">
            <div class="start-stat-row">
                <div class="label"><i class="ti ti-help-circle"></i> Questions</div>
                <div class="value">{{ $questionCount }}</div>
            </div>
            @if($quiz->time_limit)
            <div class="start-stat-row">
                <div class="label"><i class="ti ti-clock"></i> Time Limit</div>
                <div class="value">{{ $quiz->time_limit }} min</div>
            </div>
            @endif
            <div class="start-stat-row">
                <div class="label"><i class="ti ti-refresh"></i> Attempts Left</div>
                <div class="value">{{ $attemptsLeft }} of {{ $quiz->max_attempts }}</div>
            </div>
            <div class="start-stat-row">
                <div class="label"><i class="ti ti-gauge"></i> Difficulty</div>
                <span class="diff-badge diff-{{ $quiz->difficulty }}">{{ ucfirst($quiz->difficulty) }}</span>
            </div>
            <div class="start-stat-row">
                <div class="label"><i class="ti ti-users"></i> Attempted by</div>
                <div class="value">{{ $attemptCount }} student{{ $attemptCount !== 1 ? 's' : '' }}</div>
            </div>
            @if($attemptCount > 0)
            <div class="start-stat-row">
                <div class="label"><i class="ti ti-chart-bar"></i> Avg Score</div>
                <div class="value" style="color:var(--color-status-success)">{{ $avgScore }}%</div>
            </div>
            @endif
        </div>

        <div class="start-divider"></div>

        @if($attemptsLeft > 0)
        <a href="{{ route('student.quiz.take', $quiz->id) }}" class="start-btn">
            <i class="ti ti-player-play"></i> Start Quiz
        </a>
        @else
        <span class="start-btn disabled">
            <i class="ti ti-lock"></i> No Attempts Left
        </span>
        @endif

        <button
            class="bookmark-btn {{ $isBookmarked ? 'saved' : '' }}"
            id="bookmarkBtn"
            onclick="toggleBookmark(this, {{ $quiz->id }})">
            <i class="ti ti-bookmark{{ $isBookmarked ? '-filled' : '' }}"></i>
            <span>{{ $isBookmarked ? 'Saved' : 'Save for Later' }}</span>
        </button>

        @if($attemptsLeft === 0)
        <div class="no-attempts-note">You've used all attempts for this quiz.</div>
        @else
        <div class="attempts-note">No time pressure — start whenever you're ready</div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
    function toggleBookmark(btn, quizId) {
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
                    btn.classList.add('saved');
                    btn.innerHTML = '<i class="ti ti-bookmark-filled"></i> <span>Saved</span>';
                } else {
                    btn.classList.remove('saved');
                    btn.innerHTML = '<i class="ti ti-bookmark"></i> <span>Save for Later</span>';
                }
            })
            .catch(() => alert('Something went wrong.'));
    }
</script>
@endpush