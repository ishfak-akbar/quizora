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
        transition: border-color 0.2s, transform 0.2s;
    }

    .quiz-card:hover {
        border-color: rgba(79, 70, 229, 0.4);
        transform: translateY(-2px);
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
        padding: 12px 20px;
        border-top: 1px solid var(--color-border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .quiz-progress {
        flex: 1;
    }

    .progress-bar {
        height: 5px;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 4px;
    }

    .progress-fill {
        height: 100%;
        background: var(--color-primary-solid);
        border-radius: 3px;
    }

    .progress-text {
        font-size: 11px;
        color: var(--color-text-muted);
    }

    .action-btn.danger:hover {
        background: rgba(248, 113, 113, 0.15);
        color: var(--color-status-error);
        border-color: rgba(248, 113, 113, 0.3);
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
    <div class="quiz-card" data-status="{{ $quiz->status }}" data-title="{{ strtolower($quiz->title) }}">
        <div class="quiz-card-top">
            <div class="quiz-card-header">
                <div>
                    <div class="quiz-card-title">{{ $quiz->title }}</div>
                    @if($quiz->description)
                    <div class="quiz-card-desc">{{ Str::limit($quiz->description, 60) }}</div>
                    @endif
                </div>
                <span class="status-badge {{ $quiz->status }}">
                    <span class="status-dot"></span>{{ ucfirst($quiz->status) }}
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
        </div>
        <div class="quiz-card-footer">
            <div class="quiz-progress">
                @php
                $total = $quiz->attempts_count ?? 0;
                $submitted = $quiz->submitted_count ?? 0;
                $percent = $total > 0 ? round(($submitted / $total) * 100) : 0;
                @endphp
                <div class="progress-bar">
                    <div class="progress-fill" style="width:{{ $percent }}%"></div>
                </div>
                <div class="progress-text">{{ $submitted }} submitted</div>
            </div>
            <div class="action-btns" style="margin-left:12px;">
                <a href="{{ route('teacher.quiz.edit', $quiz->id) }}" class="action-btn" title="Edit"><i class="ti ti-edit"></i></a>
                <form method="POST" action="{{ route('teacher.quiz.destroy', $quiz->id) }}" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-btn danger" title="Delete" onclick="return confirm('Delete this quiz?')">
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
</script>
@endpush