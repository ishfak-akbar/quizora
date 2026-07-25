@extends('layouts.teacher')
@section('title', 'Quizora — My Quizzes')

@push('styles')
<style>
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
        max-width: 550px;
        transition: border-color 0.2s;
    }

    .search-wrap:focus-within {
        border-color: rgba(79, 70, 229, 0.6);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .search-wrap i {
        color: var(--color-text-muted);
        font-size: 16px;
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

    .filter-btn {
        height: 38px;
        padding: 0 16px;
        border-radius: 10px;
        border: 1.5px solid var(--color-border-light);
        background: transparent;
        color: var(--color-text-secondary);
        font-size: 13px;
        font-weight: 500;
        font-family: var(--font);
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: rgba(79, 70, 229, 0.15);
        border-color: rgba(79, 70, 229, 0.4);
        color: #fff;
    }

    .create-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--color-primary-solid);
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 10px;
        text-decoration: none;
        transition: background 0.2s;
    }

    .create-btn:hover {
        background: #4338CA;
    }

    .quiz-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 16px;
    }

    .quiz-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
        transition: border-color 0.2s, transform 0.2s;
    }

    .quiz-card:hover {
        border-color: rgba(129, 140, 248, 0.55);
        transform: translateY(-4px);
        box-shadow:
            0 0 0 1px rgba(129, 140, 248, 0.15),
            0 12px 32px rgba(79, 70, 229, 0.28),
            0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .quiz-card::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 14px;
        background: radial-gradient(420px circle at var(--mx, 50%) var(--my, 0%), rgba(129, 140, 248, 0.14), transparent 60%);
        opacity: 0;
        transition: opacity 0.25s;
        pointer-events: none;
        z-index: 1;
    }

    .quiz-card:hover::after {
        opacity: 1;
    }

    .quiz-card-accent {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 4px;
        z-index: 2;
    }

    /* .quiz-card-accent.status-active {
        background: linear-gradient(180deg, #34D399, #059669);
    }

    .quiz-card-accent.status-draft {
        background: linear-gradient(180deg, #9CA3AF, #6B7280);
    }

    .quiz-card-accent.status-scheduled {
        background: linear-gradient(180deg, #22D3EE, #0891B2);
    }

    .quiz-card-accent.status-ended,
    .quiz-card-accent.status-closed {
        background: linear-gradient(180deg, #F59E0B, #B45309);
    } */

    .qc-banner {
        height: 60px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
        gap: 10px;
    }

    .qc-banner-icon {
        font-size: 22px;
        color: rgba(255, 255, 255, 0.9);
        position: relative;
        z-index: 1;
        flex-shrink: 0;
    }

    .qc-banner-deco {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.12);
    }

    .qc-banner-deco.d1 {
        width: 70px;
        height: 70px;
        top: -28px;
        left: -10px;
    }

    .qc-banner-deco.d2 {
        width: 38px;
        height: 38px;
        bottom: -18px;
        right: 40px;
    }

    .qc-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(0deg, rgba(0, 0, 0, 0.45) 0%, rgba(0, 0, 0, 0) 55%);
        z-index: 0;
    }

    .qc-banner-title {
        position: relative;
        z-index: 1;
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        line-height: 1.3;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.35);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .qc-stat-row {
        display: flex;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--color-border-light);
        border-radius: 10px;
        padding: 10px 4px;
        margin-top: 12px;
    }

    .qc-stat {
        flex: 1;
        text-align: center;
        padding: 0 6px;
        border-right: 1px solid var(--color-border-light);
    }

    .qc-stat:last-child {
        border-right: none;
    }

    .qc-stat-value {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        line-height: 1.2;
    }

    .qc-stat-label {
        font-size: 10px;
        color: var(--color-text-muted);
        margin-top: 2px;
    }

    .quiz-card-top {
        padding: 18px 20px;
        flex: 1;
    }

    .quiz-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 14px;
    }

    .quiz-card-title {
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 4px;
    }

    .quiz-card-desc {
        font-size: 12px;
        color: var(--color-text-muted);
    }

    .quiz-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .quiz-meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: var(--color-text-muted);
    }

    .quiz-meta-item i {
        font-size: 14px;
    }

    .quiz-card-footer {
        padding: 9px 20px;
        border-top: 1px solid var(--color-border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .quiz-card .action-btn {
        width: 30px;
        height: 30px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(79, 70, 229, 0.14);
        border: none;
        color: #A5B4FC;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.18s;
    }

    .quiz-card .action-btn:hover {
        background: rgba(79, 70, 229, 0.28);
        transform: translateY(-1px);
    }

    .quiz-card .action-btn.danger {
        background: rgba(248, 113, 113, 0.14);
        color: #FCA5A5;
    }

    .quiz-card .action-btn.danger:hover {
        background: rgba(248, 113, 113, 0.28);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--color-text-muted);
    }

    .empty-state i {
        font-size: 48px;
        display: block;
        margin-bottom: 16px;
        color: rgba(79, 70, 229, 0.3);
    }

    .empty-state h3 {
        font-size: 16px;
        font-weight: 600;
        color: #9ca3af;
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 13px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div>
        <h1>My Quizzes</h1>
        <p>Manage and track all your quizzes</p>
    </div>
    <a href="{{ route('teacher.quiz.create') }}" class="create-btn">
        <i class="ti ti-plus"></i> Create Quiz
    </a>
</div>

<div class="filters">
    <div class="search-wrap">
        <i class="ti ti-search"></i>
        <input type="text" id="searchInput" placeholder="Search quizzes..." />
    </div>
    <button class="filter-btn active" onclick="filterQuizzes('all', this)">All</button>
    <button class="filter-btn" onclick="filterQuizzes('active', this)">Active</button>
    <button class="filter-btn" onclick="filterQuizzes('draft', this)">Draft</button>
    <button class="filter-btn" onclick="filterQuizzes('closed', this)">Closed</button>
</div>

<div class="quiz-grid" id="quizGrid">
    @forelse($quizzes as $quiz)
    @php
    $filterStatus = in_array($quiz->display_status, ['closed', 'ended']) ? 'closed' : $quiz->display_status;
    @endphp
    @php
    [$bannerBg, $bannerIcon] = \App\Helpers\QuizHelper::bookmarkBannerConfig($quiz->category ?? '', $loop->index);
    @endphp
    <div class="quiz-card" data-status="{{ $filterStatus }}" data-title="{{ strtolower($quiz->title) }}" onclick="window.location.href='{{ route('teacher.quiz.view', $quiz->id) }}'" style="cursor:pointer;">

        <div class="quiz-card-accent status-{{ $quiz->display_status }}"></div>

        <div class="qc-banner" style="background: {{ $bannerBg }};">
            <div class="qc-banner-deco d1"></div>
            <div class="qc-banner-deco d2"></div>
            <div class="qc-banner-title">{{ $quiz->title }}</div>
            <i class="{{ $bannerIcon }} qc-banner-icon"></i>
        </div>

        <div class="quiz-card-top">
            <div class="quiz-card-header">
                <div>
                    @if($quiz->description)
                    <div class="quiz-card-desc">{{ Str::limit($quiz->description, 60) }}</div>
                    @endif
                </div>
                <span class="status-badge {{ $quiz->status }}">
                    <span class="status-dot"></span>{{ ucfirst($quiz->display_status) }}
                </span>
            </div>
            <div class="quiz-meta">
                <div class="quiz-meta-item"><i class="ti ti-help-circle"></i> {{ $quiz->questions_count }} questions</div>
                @if($quiz->time_limit)
                <div class="quiz-meta-item"><i class="ti ti-clock"></i> {{ $quiz->time_limit }} min</div>
                @endif
                @if($quiz->ends_at)
                <div class="quiz-meta-item"><i class="ti ti-calendar"></i> {{ $quiz->ends_at->format('M d, Y') }}</div>
                @endif
                <div class="quiz-meta-item"><i class="ti ti-refresh"></i> {{ $quiz->max_attempts }} attempt(s)</div>
            </div>

            <div class="qc-stat-row">
                <div class="qc-stat">
                    <div class="qc-stat-value">{{ $quiz->questions_count }}</div>
                    <div class="qc-stat-label">Questions</div>
                </div>
                <div class="qc-stat">
                    <div class="qc-stat-value">{{ $quiz->submitted_count }}</div>
                    <div class="qc-stat-label">Submitted</div>
                </div>
                <div class="qc-stat">
                    <div class="qc-stat-value">{{ $quiz->avg_score !== null ? $quiz->avg_score . '%' : '—' }}</div>
                    <div class="qc-stat-label">Avg Score</div>
                </div>
            </div>
        </div>

        <div class="quiz-card-footer">
            <div class="action-btns" style="margin-left:auto;" onclick="event.stopPropagation();">
                <a href="{{ route('teacher.quiz.edit', $quiz->id) }}" class="action-btn" title="Edit"><i class="ti ti-edit"></i></a>
                <a href="{{ route('teacher.quiz.print', $quiz->id) }}" class="action-btn" title="Print Question Paper" target="_blank"><i class="ti ti-printer"></i></a>
                <form method="POST" action="{{ route('teacher.quiz.destroy', $quiz->id) }}" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-btn danger" title="Delete" onclick="event.stopPropagation(); return confirm('Delete this quiz?')">
                        <i class="ti ti-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state" style="grid-column:1/-1;">
        <i class="ti ti-file-off"></i>
        <h3>No quizzes yet</h3>
        <p>Create your first quiz to get started</p>
        <a href="{{ route('teacher.quiz.create') }}" class="create-btn"><i class="ti ti-plus"></i> Create Quiz</a>
    </div>
    @endforelse
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('.quiz-card').forEach(card => {
            card.style.display = card.dataset.title.includes(val) ? 'flex' : 'none';
        });
    });

    function filterQuizzes(status, btn) {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.quiz-card').forEach(card => {
            card.style.display = (status === 'all' || card.dataset.status === status) ? 'flex' : 'none';
        });
    }
    document.querySelectorAll('.quiz-card').forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            card.style.setProperty('--mx', `${e.clientX - rect.left}px`);
            card.style.setProperty('--my', `${e.clientY - rect.top}px`);
        });
    });
</script>
@endpush