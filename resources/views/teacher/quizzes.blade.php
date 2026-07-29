@extends('layouts.teacher')
@section('title', 'Quizora — My Quizzes')

@push('styles')
    <link rel="stylesheet" href="{{ asset('teacher.css') }}">
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
        <i class="ti ti-file-off empty-icon"></i>
        <h3>No quizzes yet</h3>
        <p>Create your first quiz to get started</p>
        <a href="{{ route('teacher.quiz.create') }}" class="empty-browse-btn"><i class="ti ti-plus"></i> Create Quiz</a>
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