@extends('layouts.student')
@section('title', 'Quizora — Dashboard')
@push('styles')
<style>
  .browse-section {
    background: linear-gradient(135deg, #2E2570 0%, #4F46E5 50%, #818CF8 100%);
    border-radius: 16px;
    padding: 28px;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
  }

  .browse-section::before {
    content: '';
    position: absolute;
    top: -30px;
    right: -30px;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.06);
  }

  .browse-info {
    position: relative;
    z-index: 1;
  }

  .browse-info h2 {
    font-size: 22px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 6px;
  }

  .browse-info p {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.7);
  }

  .browse-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 10px;
    text-decoration: none;
    transition: background 0.2s;
    white-space: nowrap;
    position: relative;
    z-index: 1;
  }

  .browse-btn:hover {
    background: rgba(255, 255, 255, 0.25);
  }

  .dashboard-grid {
    display: grid;
    grid-template-columns: 1.4fr 1fr;
    gap: 20px;
  }

  .quiz-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 20px;
    border-bottom: 1px solid var(--color-border-light);
    transition: background 0.15s;
  }

  .quiz-row:last-child {
    border-bottom: none;
  }

  .quiz-row:hover {
    background: var(--color-bg-row-hover);
  }

  .quiz-row-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(79, 70, 229, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: var(--color-primary-glow);
    flex-shrink: 0;
  }

  .quiz-row-info {
    flex: 1;
  }

  .quiz-row-title {
    font-size: 13px;
    font-weight: 600;
    color: #fff;
  }

  .quiz-row-meta {
    font-size: 11px;
    color: var(--color-text-muted);
    margin-top: 2px;
  }

  .quiz-row-badge {
    font-size: 11px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    flex-shrink: 0;
  }

  .badge-easy {
    background: rgba(52, 211, 153, 0.15);
    color: var(--color-status-success);
  }

  .badge-medium {
    background: rgba(245, 158, 11, 0.15);
    color: #F59E0B;
  }

  .badge-hard {
    background: rgba(248, 113, 113, 0.15);
    color: var(--color-status-error);
  }

  .quiz-row-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--color-primary-solid);
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    padding: 7px 14px;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.2s;
    flex-shrink: 0;
  }

  .quiz-row-action:hover {
    background: #4338CA;
  }

  .result-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    border-bottom: 1px solid var(--color-border-light);
  }

  .result-row:last-child {
    border-bottom: none;
  }

  .result-score-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
    border: 2px solid;
  }

  .result-info {
    flex: 1;
  }

  .result-title {
    font-size: 13px;
    font-weight: 600;
    color: #fff;
  }

  .result-date {
    font-size: 11px;
    color: var(--color-text-muted);
    margin-top: 2px;
  }
</style>
@endpush
@section('content')

{{-- STATS --}}
<div class="stats-grid">
  <div class="stat-card purple">
    <div class="stat-icon"><i class="ti ti-clipboard-list"></i></div>
    <div class="stat-value">{{ $totalAttempts }}</div>
    <div class="stat-label">Quizzes Taken</div>
  </div>
  <div class="stat-card cyan">
    <div class="stat-icon"><i class="ti ti-chart-line"></i></div>
    <div class="stat-value">{{ $avgScore }}%</div>
    <div class="stat-label">Average Score</div>
  </div>
  <div class="stat-card green">
    <div class="stat-icon"><i class="ti ti-star"></i></div>
    <div class="stat-value">{{ $bestScore }}%</div>
    <div class="stat-label">Best Score</div>
  </div>
  <div class="stat-card amber">
    <div class="stat-icon"><i class="ti ti-bookmark"></i></div>
    <div class="stat-value">{{ $bookmarkCount }}</div>
    <div class="stat-label">Bookmarks</div>
  </div>
</div>

{{-- BROWSE CTA --}}
<div class="browse-section">
  <div class="browse-info">
    <h2>Discover new quizzes</h2>
    <p>Explore quizzes across every topic — from school to BCS prep</p>
  </div>
  <a href="{{ route('student.browse') }}" class="browse-btn">
    <i class="ti ti-compass"></i> Browse Quizzes
  </a>
</div>

{{-- MAIN GRID --}}
<div class="dashboard-grid">

  {{-- RECOMMENDED QUIZZES --}}
  <div class="card">
    <div class="card-header">
      <h2>Recommended For You</h2>
      <a href="{{ route('student.browse') }}" class="view-all-link">View all</a>
    </div>
    <div>
      @forelse($recommendedQuizzes as $quiz)
      <div class="quiz-row">
        <div class="quiz-row-icon"><i class="ti ti-help-circle"></i></div>
        <div class="quiz-row-info">
          <div class="quiz-row-title">{{ $quiz->title }}</div>
          <div class="quiz-row-meta">
            {{ $quiz->category }} · {{ $quiz->questions_count }} questions
            @if($quiz->time_limit) · {{ $quiz->time_limit }} min @endif
          </div>
        </div>
        <span class="quiz-row-badge badge-{{ $quiz->difficulty }}">{{ ucfirst($quiz->difficulty) }}</span>
        <a href="{{ route('student.quiz.detail', $quiz->id) }}" class="quiz-row-action">
          <i class="ti ti-player-play"></i> Start
        </a>
      </div>
      @empty
      <div style="padding:32px;text-align:center;color:var(--color-text-muted);font-size:13px;">
        No quizzes available yet.
      </div>
      @endforelse
    </div>
  </div>

  {{-- RECENT RESULTS --}}
  <div class="card">
    <div class="card-header">
      <h2>Recent Results</h2>
      <a href="{{ route('student.results') }}" class="view-all-link">View all</a>
    </div>
    <div>
      @forelse($recentAttempts as $attempt)
      @php
      $pct = $attempt->total_marks > 0
      ? round(($attempt->score / $attempt->total_marks) * 100)
      : 0;
      $color = $pct >= 75 ? '#34D399' : ($pct >= 50 ? '#F59E0B' : '#F87171');
      @endphp
      <div class="result-row">
        <div class="result-score-circle" style="border-color: {{ $color }}; color: {{ $color }};">
          {{ $pct }}%
        </div>
        <div class="result-info">
          <div class="result-title">{{ $attempt->quiz->title }}</div>
          <div class="result-date">{{ $attempt->created_at->format('M d, Y') }}</div>
        </div>
      </div>
      @empty
      <div style="padding:32px;text-align:center;color:var(--color-text-muted);font-size:13px;">
        No attempts yet. <a href="{{ route('student.browse') }}" style="color:var(--color-primary-glow);">Take a quiz!</a>
      </div>
      @endforelse
    </div>
  </div>

</div>

@endsection