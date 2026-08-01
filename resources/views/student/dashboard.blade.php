@extends('layouts.student')
@section('title', 'Quizora — Dashboard')
@push('styles')
<style>
  .cta-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 24px;
  }

  .browse-section {
    background: linear-gradient(135deg, #2E2570 0%, #4F46E5 50%, #818CF8 100%);
    border-radius: 16px;
    padding: 28px;
    min-height: 190px;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 16px;
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
    display: flex;
    flex-direction: column;
    gap: 6px;
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
    justify-content: center;
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
    align-self: flex-start;
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
    padding: 8px 20px;
    border-bottom: 1px solid var(--color-border-light);
    transition: background 0.15s;
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

  .card-header h2 {
    font-size: 15px;
  }

  .card {
    background: radial-gradient(ellipse 100% 400px at 50% 0%,
        rgba(99, 102, 241, 0.18) 0%,
        transparent 65%),
      linear-gradient(180deg, #1c1842 0%, #161233 50%, #0f0c1e 100%);
    ;
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }

  .stat-card {
    border: 1px solid var(--color-border-light);
    border-radius: 14px;
    padding: 18px 20px 16px;
    position: relative;
    overflow: hidden;
    height: 168px;
    display: flex;
    flex-direction: column;
    transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
  }

  .stat-card.purple {
    background: linear-gradient(180deg, rgba(79, 70, 229, 0.32) 0%, rgba(79, 70, 229, 0.06) 45%, var(--color-bg-card) 100%);
  }

  .stat-card.cyan {
    background: linear-gradient(180deg, rgba(8, 145, 178, 0.30) 0%, rgba(8, 145, 178, 0.05) 45%, var(--color-bg-card) 100%);
  }

  .stat-card.green {
    background: linear-gradient(180deg, rgba(5, 150, 105, 0.30) 0%, rgba(5, 150, 105, 0.05) 45%, var(--color-bg-card) 100%);
  }

  .stat-card.pink {
    background: linear-gradient(180deg, rgba(219, 39, 119, 0.30) 0%, rgba(219, 39, 119, 0.05) 45%, var(--color-bg-card) 100%);
  }

  .stat-card.pink::before {
    background: linear-gradient(90deg, #DB2777, #F472B6);
  }

  .stat-card:hover {
    border-color: rgba(129, 140, 248, 0.45);
    transform: translateY(-3px);
    box-shadow:
      0 0 0 1px rgba(129, 140, 248, 0.10),
      0 10px 26px rgba(79, 70, 229, 0.20);
  }

  .stat-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(360px circle at var(--mx, 50%) var(--my, 0%), rgba(129, 140, 248, 0.14), transparent 60%);
    opacity: 0;
    transition: opacity 0.25s;
    pointer-events: none;
  }

  .stat-card:hover::after {
    opacity: 1;
  }

  .stat-card-head {
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    z-index: 1;
    flex-shrink: 0;
  }

  .stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    flex-shrink: 0;
  }

  .stat-card.purple .stat-icon {
    background: rgba(79, 70, 229, 0.2);
    color: var(--color-primary-glow);
  }

  .stat-card.cyan .stat-icon {
    background: rgba(34, 211, 238, 0.15);
    color: var(--color-stat-cyan);
  }

  .stat-card.green .stat-icon {
    background: rgba(52, 211, 153, 0.15);
    color: var(--color-status-success);
  }

  .stat-card.pink .stat-icon {
    background: rgba(219, 39, 119, 0.15);
    color: #DB2777;
  }

  .stat-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--color-text-muted);
    line-height: 1.3;
    margin-bottom: 4px;
  }

  .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #fff;
    line-height: 1;
    position: relative;
    z-index: 1;
  }

  .stat-visual {
    flex: 1;
    display: flex;
    align-items: flex-end;
    position: relative;
    z-index: 1;
    min-height: 0;
  }

  .stat-visual>* {
    width: 100%;
  }

  .stat-divider {
    height: 1px;
    background: var(--color-border-light);
    margin: 14px 0 12px;
    position: relative;
    z-index: 1;
    flex-shrink: 0;
  }

  .stat-legend {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    position: relative;
    z-index: 1;
    font-size: 11.5px;
    color: #fff;
    font-weight: 600;
    flex-shrink: 0;
  }

  .stat-legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .stat-legend-pipe {
    color: var(--color-text-muted);
    font-weight: 400;
  }

  .stat-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
  }

  .stat-caption {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 600;
    position: relative;
    z-index: 1;
    flex-shrink: 0;
  }

  .stat-caption i {
    font-size: 14px;
  }

  .stat-card.purple .stat-caption {
    color: var(--color-primary-glow);
  }

  .stat-card.cyan .stat-caption {
    color: var(--color-stat-cyan);
  }

  .stat-card.green .stat-caption {
    color: var(--color-status-success);
  }

  .stat-card.ink .stat-caption {
    color: #F472B6;
  }

  .stat-stack-bar {
    display: flex;
    height: 8px;
    border-radius: 4px;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.06);
  }

  .stat-stack-seg.passed {
    background: linear-gradient(90deg, #059669, var(--color-status-success));
  }

  .stat-stack-seg.failed {
    background: linear-gradient(90deg, #B91C1C, #F87171);
  }

  .stat-mini-chart {
    display: block;
  }

  .stat-scale {
    position: relative;
    height: 6px;
    border-radius: 3px;
    background: linear-gradient(90deg, #F87171, #F59E0B, var(--color-status-success));
    opacity: 0.35;
  }

  .stat-scale-marker {
    position: absolute;
    top: 50%;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid var(--color-status-success);
    transform: translate(-50%, -50%);
    box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.25);
  }

  .stat-dot-row {
    display: flex;
    gap: 6px;
    align-items: center;
  }

  .stat-dot-row .fill-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: #F59E0B;
  }

  .stat-dot-row .empty-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
  }

  .ai-tutor-section {
    background: linear-gradient(135deg, #1E3A5F 0%, #0891B2 50%, #22D3EE 100%);
    border-radius: 16px;
    padding: 28px;
    min-height: 190px;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 16px;
  }

  .ai-tutor-section::before {
    content: '';
    position: absolute;
    top: -30px;
    right: -30px;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.08);
  }

  .ai-tutor-section::after {
    content: '';
    position: absolute;
    bottom: -50px;
    left: 30%;
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05);
  }

  .ai-tutor-info {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    gap: 14px;
  }

  .ai-tutor-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    color: #fff;
    flex-shrink: 0;
  }

  .ai-tutor-info h2 {
    font-size: 20px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .ai-tutor-info h2 .badge-new {
    font-size: 10px;
    font-weight: 700;
    background: rgba(255, 255, 255, 0.2);
    padding: 2px 8px;
    border-radius: 20px;
    letter-spacing: 0.5px;
  }

  .ai-tutor-info p {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.75);
    max-width: 420px;
  }

  .ai-tutor-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #fff;
    color: #0891B2;
    font-size: 13px;
    font-weight: 700;
    padding: 11px 22px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.2s;
    white-space: nowrap;
    position: relative;
    z-index: 1;
    align-self: flex-start;
  }

  .ai-tutor-btn:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-1px);
  }
</style>
@endpush
@section('content')

{{-- STATS --}}
<div class="stats-grid">

  <!-- Quizzes Taken -->
  <div class="stat-card purple">
    <div class="stat-card-head">
      <div class="stat-icon"><i class="ti ti-clipboard-list"></i></div>
      <div>
        <div class="stat-title">Quizzes Taken</div>
        <div class="stat-value">{{ $totalAttempts }}</div>
      </div>
    </div>
    @php $failedCount = max($totalAttempts - $quizzesPassed, 0); @endphp
    <div class="stat-visual">
      <div class="stat-stack-bar">
        @if($totalAttempts > 0)
        <div class="stat-stack-seg passed" style="width:{{ round(($quizzesPassed / $totalAttempts) * 100) }}%"></div>
        <div class="stat-stack-seg failed" style="width:{{ round(($failedCount / $totalAttempts) * 100) }}%"></div>
        @endif
      </div>
    </div>
    <div class="stat-divider"></div>
    <div class="stat-legend">
      <span class="stat-legend-item"><span class="stat-dot" style="background:var(--color-status-success)"></span> {{ $quizzesPassed }} Passed</span>
      <span class="stat-legend-pipe">|</span>
      <span class="stat-legend-item"><span class="stat-dot" style="background:#F87171"></span> {{ $failedCount }} Failed</span>
    </div>
  </div>

  <!-- Average Score -->
  <div class="stat-card cyan">
    <div class="stat-card-head">
      <div class="stat-icon"><i class="ti ti-chart-line"></i></div>
      <div>
        <div class="stat-title">Average Score</div>
        <div class="stat-value">{{ $avgScore }}%</div>
      </div>
    </div>
    <div class="stat-visual">
      @if($recentScores->count() >= 2)
      @php
      $max = max($recentScores->max(), 1);
      $w = 220; $h = 34; $step = $w / max($recentScores->count() - 1, 1);
      $points = $recentScores->map(fn($v, $i) => ($i * $step) . ',' . ($h - ($v / $max) * ($h - 4) - 2))->implode(' L ');
      @endphp
      <svg class="stat-mini-chart" width="100%" height="{{ $h }}" viewBox="0 0 {{ $w }} {{ $h }}" preserveAspectRatio="none">
        <path d="M {{ $points }}" fill="none" stroke="var(--color-stat-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
      @endif
    </div>
    <div class="stat-divider"></div>
    <div class="stat-caption">
      <i class="ti ti-{{ $avgScore >= 50 ? 'trending-up' : 'trending-down' }}"></i>
      {{ $avgScore >= 50 ? 'On track' : 'Needs improvement' }}
    </div>
  </div>

  <!-- Best Score -->
  <div class="stat-card green">
    <div class="stat-card-head">
      <div class="stat-icon"><i class="ti ti-star"></i></div>
      <div>
        <div class="stat-title">Best Score</div>
        <div class="stat-value">{{ $bestScore }}%</div>
      </div>
    </div>
    <div class="stat-visual">
      <div class="stat-scale">
        <div class="stat-scale-marker" style="left:{{ $bestScore }}%"></div>
      </div>
    </div>
    <div class="stat-divider"></div>
    <div class="stat-caption">
      <i class="ti ti-trophy"></i> Your top performance
    </div>
  </div>

  <!-- Bookmarks -->
  <div class="stat-card pink">
    <div class="stat-card-head">
      <div class="stat-icon"><i class="ti ti-bookmark"></i></div>
      <div>
        <div class="stat-title">Bookmarks</div>
        <div class="stat-value">{{ $bookmarkCount }}</div>
      </div>
    </div>
    <div class="stat-visual">
      <div class="stat-dot-row">
        @for($i = 0; $i < 5; $i++)
          <span class="{{ $i < min($bookmarkCount, 5) ? 'fill-dot' : 'empty-dot' }}"></span>
          @endfor
          @if($bookmarkCount > 5)
          <span style="font-size:11px; color:var(--color-text-muted); margin-left:4px;">+{{ $bookmarkCount - 5 }}</span>
          @endif
      </div>
    </div>
    <div class="stat-divider"></div>
    <div class="stat-caption">
      <i class="ti ti-clock"></i> Saved for later
    </div>
  </div>

</div>

<div class="cta-row">

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

  {{-- AI TUTOR CTA --}}
  <div class="ai-tutor-section">
    <div class="ai-tutor-info">
      <div>
        <h2>Meet your AI Tutor <span class="badge-new">AI</span></h2>
        <p>Ask about your results, get topics explained, and find out exactly what to study next.</p>
      </div>
    </div>
    <a href="{{ route('student.ai-tutor') }}" class="ai-tutor-btn">
      <i class="ti ti-message-chatbot"></i> Chat with AI Tutor
    </a>
  </div>

</div>

{{-- MAIN GRID --}}
<div class="dashboard-grid">

  {{-- RECOMMENDED QUIZZES --}}
  <div class="card">
    <div class="card-header">
      <div>
        <h2 style="display:flex;align-items:center;gap:6px;">
          @if($isAiRecommended)
          <i class="ti ti-sparkles" style="font-size:15px;color:var(--color-primary-glow);"></i>
          @endif
          {{ $isAiRecommended ? 'AI Picks For You' : 'Recommended For You' }}
        </h2>
        <p style="font-size:11px;color:var(--color-text-muted);margin-top:2px;">
          {{ $isAiRecommended ? 'Based on your quiz performance' : 'Explore something new' }}
        </p>
      </div>
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
@push('scripts')
<script>
  document.querySelectorAll('.stat-card').forEach(card => {
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      card.style.setProperty('--mx', `${e.clientX - rect.left}px`);
      card.style.setProperty('--my', `${e.clientY - rect.top}px`);
    });
  });
</script>
@endpush