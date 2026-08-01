@extends('layouts.teacher')
@section('title', 'Quizora — Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('teacher.css') }}">
<style>
  .dq-card {
    background:
      radial-gradient(ellipse 100% 400px at 50% 0%,
        rgba(99, 102, 241, 0.18) 0%,
        transparent 65%),
      linear-gradient(180deg, #1c1842 0%, #161233 50%, #0f0c1e 100%);
    border-radius: 16px;
    overflow: hidden;
    position: relative;
  }

  .dq-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
  }

  .dq-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 18px 20px;
    border-bottom: 1px solid var(--color-border-light);
  }

  .dq-card-head-left {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .dq-card-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
  }

  .dq-card-icon.indigo {
    background: rgba(79, 70, 229, 0.18);
    color: #818CF8;
  }

  .dq-card-icon.violet {
    background: rgba(79, 70, 229, 0.18);
    color: #818CF8;
  }

  .dq-card-icon.amber {
    background: rgba(79, 70, 229, 0.15);
    color: #A5B4FC;
  }

  .dq-card-title {
    font-size: 14.5px;
    font-weight: 700;
    color: #fff;
    line-height: 1.3;
  }

  .dq-card-sub {
    font-size: 11.5px;
    color: var(--color-text-muted);
    margin-top: 2px;
  }

  .dq-view-all {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 600;
    color: var(--color-primary-glow);
    background: rgba(79, 70, 229, 0.1);
    border: 1px solid rgba(79, 70, 229, 0.25);
    border-radius: 8px;
    padding: 6px 12px;
    text-decoration: none;
    transition: background 0.15s, border-color 0.15s;
    white-space: nowrap;
  }

  .dq-view-all:hover {
    background: rgba(79, 70, 229, 0.2);
    border-color: rgba(129, 140, 248, 0.5);
  }

  .dq-view-all i {
    font-size: 13px;
  }

  /* Results card */
  .dq-results-card {
    padding: 20px;
    display: flex;
    flex-direction: column;
    overflow: visible !important;
  }

  .dq-results-card .dq-card-head {
    padding: 0 0 16px;
    border-bottom: none;
  }

  .dq-select-wrap {
    margin-bottom: 16px;
  }

  .dq-stat-trio {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
  }

  .dq-stat-pill {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid var(--color-border-light);
    border-radius: 12px;
    padding: 12px;
    transition: border-color 0.2s, background 0.2s;
  }

  .dq-stat-pill:hover {
    border-color: rgba(79, 70, 229, 0.35);
    background: rgba(255, 255, 255, 0.06);
  }

  .dq-stat-pill-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
  }

  .dq-stat-pill-icon.indigo {
    background: rgba(79, 70, 229, 0.18);
    color: #818CF8;
  }

  .dq-stat-pill-icon.green {
    background: rgba(52, 211, 153, 0.15);
    color: #34D399;
  }

  .dq-stat-pill-icon.amber {
    background: rgba(79, 70, 229, 0.15);
    color: #A5B4FC;
  }

  .dq-stat-pill-value {
    font-size: 17px;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
  }

  .dq-stat-pill-label {
    font-size: 10.5px;
    color: var(--color-text-muted);
    margin-top: 1px;
  }

  /* Recent Quizzes table */
  .dq-table-wrap {
    overflow-x: auto;
  }

  .dq-table {
    width: 100%;
    border-collapse: collapse;
  }

  .dq-table th {
    padding: 11px 20px;
    text-align: left;
    font-size: 10.5px;
    font-weight: 700;
    color: var(--color-text-muted);
    letter-spacing: 0.8px;
    text-transform: uppercase;
    border-bottom: 1px solid var(--color-border-light);
    background: rgba(255, 255, 255, 0.02);
  }

  .dq-table td {
    padding: 14px 20px;
    font-size: 13px;
    color: var(--color-text-secondary);
    border-bottom: 1px solid var(--color-border-light);
    vertical-align: middle;
  }

  .dq-table tr:last-child td {
    border-bottom: none;
  }

  .dq-table tr:hover td {
    background: var(--color-bg-row-hover);
  }

  .dq-quiz-cell {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .dq-quiz-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(79, 70, 229, 0.15);
    color: var(--color-primary-glow);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
  }

  .dq-quiz-name {
    font-size: 13.5px;
    font-weight: 600;
    color: #fff;
  }

  .dq-quiz-type {
    font-size: 11px;
    color: var(--color-text-muted);
    margin-top: 2px;
  }

  .dq-empty-row {
    text-align: center;
    padding: 40px 20px;
    color: var(--color-text-muted);
  }

  .dq-empty-row i {
    font-size: 30px;
    display: block;
    margin-bottom: 10px;
    opacity: 0.4;
  }

  /* Leaderboard card */
  .dq-leader-card {
    display: flex;
    flex-direction: column;
    max-height: 460px;
  }

  .dq-leader-filter {
    padding: 14px 20px;
    border-bottom: 1px solid var(--color-border-light);
  }

  .dq-leader-list {
    overflow-y: auto;
    flex: 1;
    padding: 6px 8px;
  }

  .dq-leader-list::-webkit-scrollbar {
    width: 4px;
  }

  .dq-leader-list::-webkit-scrollbar-track {
    background: transparent;
  }

  .dq-leader-list::-webkit-scrollbar-thumb {
    background: rgba(129, 140, 248, 0.25);
    border-radius: 2px;
  }

  .dq-lb-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 10px;
    transition: background 0.15s;
  }

  .dq-lb-item:hover {
    background: var(--color-bg-row-hover);
  }

  .dq-lb-rank {
    width: 26px;
    text-align: center;
    font-size: 13px;
    font-weight: 700;
    color: var(--color-text-muted);
    flex-shrink: 0;
  }

  .dq-lb-rank.medal {
    font-size: 16px;
  }

  .dq-lb-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
  }

  .dq-lb-info {
    flex: 1;
    min-width: 0;
  }

  .dq-lb-name {
    font-size: 13px;
    font-weight: 600;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .dq-lb-bar {
    height: 4px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 2px;
    margin-top: 5px;
    overflow: hidden;
  }

  .dq-lb-fill {
    height: 100%;
    border-radius: 2px;
  }

  .dq-lb-score {
    font-size: 13px;
    font-weight: 700;
    color: var(--color-primary-glow);
    flex-shrink: 0;
  }

  .dq-view-all-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 13px;
    text-align: center;
    font-size: 12px;
    font-weight: 600;
    color: var(--color-primary-glow);
    border-top: 1px solid var(--color-border-light);
    text-decoration: none;
    transition: background 0.15s;
  }

  .dq-view-all-footer:hover {
    background: var(--color-bg-row-hover);
  }

  .dq-empty-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--color-text-muted);
    font-size: 13px;
  }

  .dq-empty-state i {
    font-size: 30px;
    display: block;
    margin-bottom: 10px;
    opacity: 0.35;
  }
</style>
@endpush

@section('content')
<div class="stats-grid">
  <!-- Total Quizzes -->
  <div class="stat-card purple">
    <i class="ti ti-file-description stat-watermark"></i>
    <div class="stat-card-head">
      <div class="stat-icon"><i class="ti ti-file-description"></i></div>
      <div>
        <div class="stat-title">Total Quizzes</div>
        <div class="stat-value">{{ $totalQuizzes }}</div>
      </div>
    </div>
    @php $publishedCount = $totalQuizzes - $draftQuizzes; @endphp
    <div class="stat-visual">
      <div class="stat-stack-bar">
        @if($totalQuizzes > 0)
        <div class="stat-stack-seg published" style="width:{{ round(($publishedCount / $totalQuizzes) * 100) }}%"></div>
        <div class="stat-stack-seg draft" style="width:{{ round(($draftQuizzes / $totalQuizzes) * 100) }}%"></div>
        @endif
      </div>
    </div>
    <div class="stat-divider"></div>
    <div class="stat-legend">
      <span class="stat-legend-item"><span class="stat-dot" style="background:var(--color-status-success)"></span> {{ $totalQuizzes - $draftQuizzes }} Published</span>
      <span class="stat-legend-pipe">|</span>
      <span class="stat-legend-item"><span class="stat-dot" style="background:#F59E0B"></span> {{ $draftQuizzes }} Draft</span>
    </div>
  </div>

  <!-- Live Quizzes -->
  <div class="stat-card cyan">
    <i class="ti ti-broadcast stat-watermark"></i>
    <div class="stat-card-head">
      <div class="stat-icon"><i class="ti ti-player-play"></i></div>
      <div>
        <div class="stat-title">Live Quizzes</div>
        <div class="stat-value">{{ $activeQuizzes }}</div>
      </div>
    </div>
    <div class="stat-visual"></div>
    <div class="stat-divider"></div>
    <div class="stat-legend">
      @if($nearestEndingQuiz)
      <span class="stat-legend-item">
        <i class="ti ti-clock"></i> {{ Str::limit($nearestEndingQuiz->title, 18) }} · {{ $nearestEndingLabel }}
      </span>
      @else
      <span class="stat-legend-item" style="color:var(--color-text-muted); font-weight:500;">
        <span class="stat-pulse-dot"></span> No quizzes currently live
      </span>
      @endif
    </div>
  </div>

  <!-- Total Students -->
  <div class="stat-card green">
    <i class="ti ti-users stat-watermark"></i>
    <div class="stat-card-head">
      <div class="stat-icon"><i class="ti ti-users"></i></div>
      <div>
        <div class="stat-title">Total Students</div>
        <div class="stat-value">{{ $totalStudents }}</div>
      </div>
    </div>
    <div class="stat-visual">
      @php
      $max = max($dailyAttempts->max(), 1);
      $w = 220; $h = 34; $step = $w / 6;
      $points = $dailyAttempts->map(fn($v, $i) => ($i * $step) . ',' . ($h - ($v / $max) * ($h - 4) - 2))->implode(' L ');
      @endphp
      <svg class="stat-mini-chart" width="100%" height="{{ $h }}" viewBox="0 0 {{ $w }} {{ $h }}" preserveAspectRatio="none">
        <path d="M {{ $points }}" fill="none" stroke="var(--color-status-success)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </div>
    <div class="stat-divider"></div>
    <div class="stat-legend">
      <span class="stat-legend-item"><i class="ti ti-trending-up" style="color:var(--color-status-success)"></i> +{{ $newStudentsThisWeek }} this week</span>
      <span class="stat-legend-pipe">|</span>
      <span class="stat-legend-item">{{ $newStudentsThisMonth }} this month</span>
    </div>
  </div>

  <!-- Submissions -->
  <div class="stat-card pink">
    <i class="ti ti-chart-bar stat-watermark"></i>
    <div class="stat-card-head">
      <div class="stat-icon"><i class="ti ti-clipboard-check"></i></div>
      <div>
        <div class="stat-title">Submissions</div>
        <div class="stat-value">{{ $totalSubmissions }}</div>
      </div>
    </div>
    @php $maxSub = max($dailySubmissions->max(), 1); @endphp
    <div class="stat-visual">
      <div class="stat-mini-bars">
        @foreach($dailySubmissions as $count)
        <span style="height:{{ max(round(($count / $maxSub) * 100), 8) }}%"></span>
        @endforeach
      </div>
    </div>
    <div class="stat-divider"></div>
    <div class="stat-legend">
      <span class="stat-legend-item"><i class="ti ti-chart-dots"></i> {{ $avgSubmissionsPerQuiz }} avg/quiz</span>
      <span class="stat-legend-pipe">|</span>
      <span class="stat-legend-item">{{ $submissionsThisWeek }} this week</span>
    </div>
  </div>

</div>
<!-- CREATE QUIZ CTA -->
<!-- QUICK ACTION CARDS -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">

  <!-- CREATE QUIZ CARD -->
  <div style="
    background: linear-gradient(135deg, #2E2570 0%, #4F46E5 50%, #818CF8 100%);
    border-radius: 16px;
    padding: 28px;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 160px;
  ">
    <!-- background decoration -->
    <div style="position:absolute;top:-30px;right:-30px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,0.06);"></div>
    <div style="position:absolute;bottom:-40px;right:40px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,0.04);"></div>
    <div style="position:absolute;top:20px;right:20px;opacity:0.15;font-size:80px;line-height:1;">
      <i class="ti ti-pencil-plus"></i>
    </div>

    <div>
      <div style="font-size:22px;font-weight:700;color:#fff;margin-bottom:6px;">
        Create New Quiz
      </div>
      <div style="font-size:13px;color:rgba(255,255,255,0.65);line-height:1.5;">
        Build tailored MCQ quizzes, customize your timers<br>and deadlines, and publish them to your classes<br>in just a click.
      </div>
    </div>

    <div style="margin-top:20px;">
      <a href="{{ route('teacher.quiz.create') }}" style="
              display: inline-flex;
              align-items: center;
              gap: 8px;
              background: rgba(255,255,255,0.15);
              backdrop-filter: blur(8px);
              border: 1px solid rgba(255,255,255,0.25);
              color: #fff;
              font-size: 13px;
              font-weight: 600;
              padding: 9px 18px;
              border-radius: 9px;
              text-decoration: none;
              transition: background 0.2s;
              font-family: var(--font);
            " onmouseover="this.style.background='rgba(255,255,255,0.25)'"
        onmouseout="this.style.background='rgba(255,255,255,0.15)'">
        <i class="ti ti-plus"></i> Create Quiz
      </a>
    </div>
  </div>

  <!-- QUIZ RESULTS CARD -->
  <div class="dq-card dq-results-card">
    <div class="dq-card-head">
      <div class="dq-card-head-left">
        <div class="dq-card-icon indigo"><i class="ti ti-chart-bar"></i></div>
        <div>
          <div class="dq-card-title">Quiz Results</div>
          <div class="dq-card-sub">Overall performance snapshot</div>
        </div>
      </div>
    </div>

    <div id="resultSelectContainer" class="dq-select-wrap"></div>

    <div id="resultStats" class="dq-stat-trio">
      <div class="dq-stat-pill">
        <div class="dq-stat-pill-icon indigo"><i class="ti ti-users"></i></div>
        <div>
          <div class="dq-stat-pill-value" id="res-submissions">—</div>
          <div class="dq-stat-pill-label">Submissions</div>
        </div>
      </div>
      <div class="dq-stat-pill">
        <div class="dq-stat-pill-icon green"><i class="ti ti-trending-up"></i></div>
        <div>
          <div class="dq-stat-pill-value" id="res-avg" style="color:var(--color-status-success)">—</div>
          <div class="dq-stat-pill-label">Avg Score</div>
        </div>
      </div>
      <div class="dq-stat-pill">
        <div class="dq-stat-pill-icon amber"><i class="ti ti-award"></i></div>
        <div>
          <div class="dq-stat-pill-value" id="res-highest" style="color:var(--color-primary-glow)">—</div>
          <div class="dq-stat-pill-label">Highest</div>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- CHARTS ROW -->
<div style="display:grid;grid-template-columns:1.6fr 1fr;gap:20px;margin-bottom:24px;">

  <!-- SUBMISSIONS OVER TIME -->
  <div class="dq-card" style="padding:20px;">
    <div class="dq-card-head" style="padding:0 0 18px; border-bottom:none;">
      <div class="dq-card-head-left">
        <div class="dq-card-icon indigo"><i class="ti ti-chart-line"></i></div>
        <div>
          <div class="dq-card-title">Growth Overview (30 Days)</div>
          <div class="dq-card-sub">Submissions across all your quizzes</div>
        </div>
      </div>
    </div>
    <div id="submissionsLegend" style="display:flex; gap:20px; justify-content:center; margin-bottom:14px;"></div>
    <canvas id="submissionsChart" height="80"></canvas>
  </div>

  <!-- QUIZ CATEGORIES -->
  <div class="dq-card" style="padding:20px;">
    <div class="dq-card-head" style="padding:0 0 18px; border-bottom:none;">
      <div class="dq-card-head-left">
        <div class="dq-card-icon amber"><i class="ti ti-chart-donut"></i></div>
        <div>
          <div class="dq-card-title">Quiz Categories</div>
          <div class="dq-card-sub">Distribution across your quizzes</div>
        </div>
      </div>
    </div>
    @if($categoryDistribution->isEmpty())
    <div class="dq-empty-state">
      <i class="ti ti-chart-donut"></i>
      <p>No category data yet.</p>
    </div>
    @else
    <div style="display:flex; align-items:center; gap:24px;">
      <div style="width:150px; height:150px; flex-shrink:0;">
        <canvas id="categoryChart"></canvas>
      </div>
      <div id="categoryLegend" style="display:flex; flex-direction:column; gap:10px;"></div>
    </div>
    @endif
  </div>

</div>

<!-- MAIN GRID -->
<div class="dashboard-grid">

  <!-- RECENT QUIZZES -->
  <div class="dq-card dq-table-card">
    <div class="dq-card-head">
      <div class="dq-card-head-left">
        <div class="dq-card-icon violet"><i class="ti ti-file-description"></i></div>
        <div>
          <div class="dq-card-title">Recent Quizzes</div>
          <div class="dq-card-sub">Latest activity across your quizzes</div>
        </div>
      </div>
      <a href="{{ route('teacher.quizzes') }}" class="dq-view-all">View all <i class="ti ti-arrow-right"></i></a>
    </div>

    <div class="dq-table-wrap">
      <table class="dq-table">
        <thead>
          <tr>
            <th>Quiz</th>
            <th>Students</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentQuizzes as $quiz)
          <tr>
            <td>
              <div class="dq-quiz-cell">
                <div class="dq-quiz-icon">
                  <i class="{{ \App\Helpers\QuizHelper::categoryIcon($quiz->category) }}"></i>
                </div>
                <div>
                  <div class="dq-quiz-name">{{ $quiz->title }}</div>
                  <div class="dq-quiz-type">
                    {{ strtoupper($quiz->type) }} ·
                    {{ $quiz->questions()->count() }} questions
                    @if($quiz->time_limit) · {{ $quiz->time_limit }} min @endif
                  </div>
                </div>
              </div>
            </td>
            <td>{{ $quiz->submitted_attempts }} / {{ $quiz->total_attempts }}</td>
            <td>{{ $quiz->ends_at ? $quiz->ends_at->format('M d, Y') : 'No deadline' }}</td>
            <td>
              <span class="status-badge {{ $quiz->status }}">
                <span class="status-dot"></span> {{ ucfirst($quiz->display_status) }}
              </span>
            </td>
            <td>
              <div class="action-btns">
                <a href="{{ route('teacher.quiz.edit', $quiz->id) }}" class="action-btn" title="Edit">
                  <i class="ti ti-edit"></i>
                </a>
                <a href="#" class="action-btn" title="Results">
                  <i class="ti ti-chart-bar" aria-hidden="true"></i>
                </a>
                <form method="POST" action="{{ route('teacher.quiz.destroy', $quiz->id) }}">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="action-btn" title="Delete"
                    onclick="return confirm('Are you sure you want to delete this quiz?')">
                    <i class="ti ti-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5">
              <div class="dq-empty-row">
                <i class="ti ti-file-off"></i>
                No quizzes yet. Create your first quiz!
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- LEADERBOARD -->
  <div class="dq-card dq-leader-card">
    <div class="dq-card-head">
      <div class="dq-card-head-left">
        <div class="dq-card-icon amber"><i class="ti ti-trophy"></i></div>
        <div>
          <div class="dq-card-title">Leaderboard</div>
          <div class="dq-card-sub">Top performers by quiz</div>
        </div>
      </div>
    </div>

    <div class="dq-leader-filter">
      <div id="lbSelectContainer"></div>
    </div>

    <div class="dq-leader-list" id="lbList">
      <div class="dq-empty-state">
        <i class="ti ti-trophy"></i>
        <p>Select a quiz to view leaderboard</p>
      </div>
    </div>

    <a href="{{ route('teacher.leaderboard.page') }}" class="dq-view-all-footer">
      View full leaderboard <i class="ti ti-arrow-right"></i>
    </a>
  </div>
</div>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
  const submissionsCtx = document.getElementById('submissionsChart');
if (submissionsCtx) {
    document.getElementById('submissionsLegend').innerHTML = `
        <div style="display:flex; align-items:center; gap:6px; font-size:12.5px; color:var(--color-text-secondary);">
            <span style="width:11px;height:11px;border-radius:3px;background:#818CF8;display:inline-block;"></span> Submissions
        </div>`;

    new Chart(submissionsCtx, {
      type: 'line',
      data: {
        labels: @json($dailySubmissions30->pluck('label')),
        datasets: [{
          label: 'Submissions',
          data: @json($dailySubmissions30->pluck('count')),
          fill: true,
          borderColor: '#818CF8',
          backgroundColor: 'rgba(129,140,248,0.18)',
          tension: 0.4,
          pointRadius: 3,
          pointBackgroundColor: '#818CF8',
          pointBorderColor: '#818CF8',
          pointHoverRadius: 5,
          borderWidth: 2,
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          x: {
            ticks: { color: '#6B7280', maxTicksLimit: 8 },
            grid: { color: 'rgba(255,255,255,0.05)', drawTicks: false }
          },
          y: {
            ticks: { color: '#6B7280', precision: 0 },
            grid: { color: 'rgba(255,255,255,0.05)', drawTicks: false },
            beginAtZero: true
          }
        }
      }
    });
}

const categoryCtx = document.getElementById('categoryChart');
if (categoryCtx) {
    const catLabels = @json($categoryDistribution->pluck('category'));
    const catCounts = @json($categoryDistribution->pluck('count'));
    const catColors = ['#34D399', '#60A5FA', '#F59E0B', '#F87171', '#C084FC', '#5EEAD4', '#F472B6', '#818CF8'];

    document.getElementById('categoryLegend').innerHTML = catLabels.map((label, i) => `
        <div style="display:flex; align-items:center; gap:8px; font-size:12.5px; color:var(--color-text-secondary);">
            <span style="width:11px;height:11px;border-radius:3px;background:${catColors[i % catColors.length]};display:inline-block;flex-shrink:0;"></span>
            ${label}
        </div>`).join('');

    new Chart(categoryCtx, {
      type: 'doughnut',
      data: {
        labels: catLabels,
        datasets: [{
          data: catCounts,
          backgroundColor: catColors,
          borderColor: '#161233',
          borderWidth: 3,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        cutout: '68%',
        plugins: { legend: { display: false } }
      }
    });
}

  document.querySelectorAll('.stat-card').forEach(card => {
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      card.style.setProperty('--mx', `${e.clientX - rect.left}px`);
      card.style.setProperty('--my', `${e.clientY - rect.top}px`);
    });
  });
  const quizSelect = document.getElementById('quizSelect');
  const lbList = document.getElementById('lbList');
  const colors = ['#4F46E5', '#7C3AED', '#0891B2', '#059669', '#D97706'];
  const medals = ['🥇', '🥈', '🥉'];

  function renderLeaderboard(data) {
    if (data.length === 0) {
      lbList.innerHTML = '<div class="dq-empty-state"><i class="ti ti-inbox"></i><p>No submissions yet for this quiz.</p></div>';
      return;
    }
    lbList.innerHTML = data.map((s, i) => `
            <div class="dq-lb-item">
                <div class="dq-lb-rank ${i < 3 ? 'medal' : ''}">${i < 3 ? medals[i] : i + 1}</div>
                <div class="dq-lb-avatar" style="background:${colors[i] || '#6B7280'}">${s.initials}</div>
                <div class="dq-lb-info">
                    <div class="dq-lb-name">${s.name}</div>
                    <div class="dq-lb-bar">
                        <div class="dq-lb-fill" style="width:${s.score}%;background:${colors[i] || '#6B7280'}"></div>
                    </div>
                </div>
                <div class="dq-lb-score">${s.score}%</div>
            </div>
        `).join('');
  }

  function fetchLeaderboard(quizId) {
    lbList.innerHTML = '<div style="text-align:center;padding:32px;color:var(--color-text-muted);font-size:13px;">Loading...</div>';
    fetch(`/teacher/leaderboard/${quizId}`)
      .then(res => res.json())
      .then(data => renderLeaderboard(data))
      .catch(() => {
        lbList.innerHTML = '<div style="text-align:center;padding:32px;color:var(--color-status-error);font-size:13px;">Failed to load.</div>';
      });
  }

  function fetchQuizResult(quizId) {
    if (!quizId) {
      document.getElementById('res-submissions').textContent = '—';
      document.getElementById('res-avg').textContent = '—';
      document.getElementById('res-highest').textContent = '—';
      return;
    }
    fetch(`/teacher/quiz/${quizId}/results-summary`)
      .then(res => res.json())
      .then(data => {
        document.getElementById('res-submissions').textContent = data.submissions;
        document.getElementById('res-avg').textContent = data.avg;
        document.getElementById('res-highest').textContent = data.highest;
      });
  }

  const lbOptions = [
    @foreach($quizzes as $quiz) {
      value: "{{ $quiz->id }}",
      label: "{{ $quiz->title }}"
    },
    @endforeach
  ];

  createCustomSelect(
    document.getElementById('lbSelectContainer'),
    lbOptions,
    'Select a quiz...',
    (value) => fetchLeaderboard(value)
  );

  createCustomSelect(
    document.getElementById('resultSelectContainer'),
    lbOptions,
    'Select a quiz...',
    (value) => fetchQuizResult(value)
  );

  if (quizSelect) {
    quizSelect.addEventListener('change', () => {
      if (quizSelect.value) fetchLeaderboard(quizSelect.value);
    });

    if (!quizSelect.value || quizSelect.options.length === 0) {
      lbList.innerHTML = '<div style="text-align:center;padding:32px;color:var(--color-text-muted);font-size:13px;">No quizzes yet. Create a quiz first.</div>';
    } else {
      fetchLeaderboard(quizSelect.value);
    }
  }
</script>
@endpush