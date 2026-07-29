@extends('layouts.teacher')
@section('title', 'Quizora — Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('teacher.css') }}">
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

  <!-- OVERALL RESULTS CARD -->
  <div style="
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            min-height: 160px;
        ">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
      <div>
        <div style="font-size:15px;font-weight:700;color:#fff;">Quiz Results</div>
        <div style="font-size:12px;color:var(--color-text-muted);margin-top:2px;">Overall performance</div>
      </div>
      <i class="ti ti-chart-bar" style="font-size:22px;color:var(--color-primary-glow);"></i>
    </div>

    <div id="resultSelectContainer" style="margin-bottom:16px;"></div>

    <div id="resultStats" style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
      <div style="background:rgba(255,255,255,0.04);border-radius:10px;padding:12px;text-align:center;">
        <div style="font-size:20px;font-weight:700;color:#fff;" id="res-submissions">—</div>
        <div style="font-size:11px;color:var(--color-text-muted);margin-top:3px;">Submissions</div>
      </div>
      <div style="background:rgba(255,255,255,0.04);border-radius:10px;padding:12px;text-align:center;">
        <div style="font-size:20px;font-weight:700;color:var(--color-status-success);" id="res-avg">—</div>
        <div style="font-size:11px;color:var(--color-text-muted);margin-top:3px;">Avg Score</div>
      </div>
      <div style="background:rgba(255,255,255,0.04);border-radius:10px;padding:12px;text-align:center;">
        <div style="font-size:20px;font-weight:700;color:var(--color-primary-glow);" id="res-highest">—</div>
        <div style="font-size:11px;color:var(--color-text-muted);margin-top:3px;">Highest</div>
      </div>
    </div>
  </div>

</div>
<!-- MAIN GRID -->
<div class="dashboard-grid">
  <!-- QUIZ TABLE -->
  <div class="card">
    <div class="card-header">
      <h2>Recent Quizzes</h2>
      <a href="{{ route('teacher.quizzes') }}" class="view-all-link">View all</a>
    </div>
    <table class="quiz-table">
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
            <div class="quiz-name">{{ $quiz->title }}</div>
            <div class="quiz-type">
              {{ strtoupper($quiz->type) }} ·
              {{ $quiz->questions()->count() }} questions
              @if($quiz->time_limit) · {{ $quiz->time_limit }} min @endif
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
          <td colspan="5" style="text-align:center;padding:32px;color:var(--color-text-muted);">
            No quizzes yet. Create your first quiz!
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <!-- LEADERBOARD -->
  <div class="card leaderboard-card">
    <div class="card-header">
      <h2>Leaderboard</h2>
      <i class="ti ti-trophy" style="color:#F59E0B;font-size:18px"></i>
    </div>
    <div style="padding:12px 16px;border-bottom:1px solid var(--color-border-light);">
      <div id="lbSelectContainer"></div>
    </div>
    <div class="leaderboard-list" id="lbList">
      <div style="text-align:center;padding:32px;color:var(--color-text-muted);font-size:13px;">
        Select a quiz to view leaderboard
      </div>
    </div>
    <a href="{{ route('teacher.leaderboard.page') }}" class="view-all-btn">View full leaderboard</a>
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
  const quizSelect = document.getElementById('quizSelect');
  const lbList = document.getElementById('lbList');
  const colors = ['#4F46E5', '#7C3AED', '#0891B2', '#059669', '#D97706'];
  const medals = ['🥇', '🥈', '🥉'];

  function renderLeaderboard(data) {
    if (data.length === 0) {
      lbList.innerHTML = '<div style="text-align:center;padding:32px;color:var(--color-text-muted);font-size:13px;">No submissions yet for this quiz.</div>';
      return;
    }
    lbList.innerHTML = data.map((s, i) => `
            <div class="lb-item">
                <div class="lb-rank">${i < 3 ? medals[i] : i + 1}</div>
                <div class="lb-avatar" style="background:${colors[i] || '#6B7280'}">${s.initials}</div>
                <div class="lb-info">
                    <div class="lb-name">${s.name}</div>
                    <div class="lb-score-bar">
                        <div class="lb-score-fill" style="width:${s.score}%;background:${colors[i] || '#6B7280'}"></div>
                    </div>
                </div>
                <div class="lb-score">${s.score}%</div>
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