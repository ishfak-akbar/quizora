@extends('layouts.teacher')
@section('title', 'Quizora — Dashboard')
@push('styles')
<style>
  /* STATS */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }

  .stat-card {
    background: var(--color-bg-card);
    border: 1px solid var(--color-border-light);
    border-radius: 14px;
    padding: 20px;
    position: relative;
    overflow: hidden;
    transition: border-color 0.2s, transform 0.2s;
  }

  .stat-card:hover {
    border-color: rgba(79, 70, 229, 0.4);
    transform: translateY(-2px);
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
  }

  .stat-card.purple::before {
    background: linear-gradient(90deg, var(--color-primary-solid), var(--color-stat-purple));
  }

  .stat-card.cyan::before {
    background: linear-gradient(90deg, #0891B2, var(--color-stat-cyan));
  }

  .stat-card.green::before {
    background: linear-gradient(90deg, #059669, var(--color-status-success));
  }

  .stat-card.pink::before {
    background: linear-gradient(90deg, #DB2777, #F472B6);
  }

  .stat-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-bottom: 14px;
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
    background: rgba(244, 114, 182, 0.15);
    color: #F472B6;
  }

  .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #fff;
    line-height: 1;
    margin-bottom: 4px;
  }

  .stat-label {
    font-size: 12px;
    color: var(--color-text-muted);
    font-weight: 500;
  }

  .stat-change {
    position: absolute;
    top: 20px;
    right: 16px;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 20px;
  }

  .stat-change.up {
    background: rgba(52, 211, 153, 0.15);
    color: var(--color-status-success);
  }

  .stat-change.down {
    background: rgba(248, 113, 113, 0.15);
    color: var(--color-status-error);
  }

  /* GRID LAYOUT */
  .dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px;
    margin-bottom: 24px;
  }

  /* CREATE QUIZ SECTION */
  .create-section {
    background: var(--color-bg-card);
    border: 1px solid var(--color-border-light);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 20px;
  }

  .create-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--color-border-light);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .create-header h2 {
    font-size: 15px;
    font-weight: 600;
    color: #fff;
  }

  .create-body {
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
  }

  .create-visual {
    width: 80px;
    height: 80px;
    border-radius: 16px;
    background: rgba(79, 70, 229, 0.2);
    border: 1px solid rgba(79, 70, 229, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    flex-shrink: 0;
    color: var(--color-primary-glow);
  }

  .create-info h3 {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 6px;
  }

  .create-info p {
    font-size: 13px;
    color: var(--color-text-secondary);
    line-height: 1.6;
    margin-bottom: 16px;
  }

  .create-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--color-primary-solid);
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    font-family: var(--font);
    transition: background 0.2s, transform 0.15s;
    text-decoration: none;
  }

  .create-btn:hover {
    background: #4338CA;
    transform: translateY(-1px);
  }

  /* CARD */
  .card {
    background: var(--color-bg-card);
    border: 1px solid var(--color-border-light);
    border-radius: 14px;
    overflow: visible;
  }

  .card-header {
    padding: 18px 20px;
    border-bottom: 1px solid var(--color-border-light);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .card-header h2 {
    font-size: 14px;
    font-weight: 600;
    color: #fff;
  }

  /* QUIZ TABLE */
  .quiz-table {
    width: 100%;
    border-collapse: collapse;
  }

  .quiz-table th {
    padding: 10px 20px;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    color: var(--color-text-muted);
    letter-spacing: 0.8px;
    text-transform: uppercase;
    border-bottom: 1px solid var(--color-border-light);
  }

  .quiz-table td {
    padding: 13px 20px;
    font-size: 13px;
    color: var(--color-text-secondary);
    border-bottom: 1px solid var(--color-border-light);
    vertical-align: middle;
  }

  .quiz-table tr:last-child td {
    border-bottom: none;
  }

  .quiz-table tr:hover td {
    background: var(--color-bg-row-hover);
  }

  .quiz-name {
    font-weight: 600;
    color: #fff;
    font-size: 13px;
  }

  .quiz-type {
    font-size: 11px;
    color: var(--color-text-muted);
    margin-top: 2px;
  }

  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
  }

  .status-badge.active {
    background: rgba(52, 211, 153, 0.15);
    color: var(--color-status-success);
  }

  .status-badge.draft {
    background: rgba(107, 114, 128, 0.2);
    color: var(--color-text-secondary);
  }

  .status-badge.closed {
    background: rgba(248, 113, 113, 0.15);
    color: var(--color-status-error);
  }

  .status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    display: inline-block;
  }

  .action-btns {
    display: flex;
    gap: 6px;
  }

  .action-btn {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    border: 1px solid var(--color-border-light);
    background: transparent;
    color: var(--color-text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 15px;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
  }

  .action-btn:hover {
    background: var(--color-bg-row-hover);
    color: #fff;
    border-color: rgba(79, 70, 229, 0.4);
  }

  /* LEADERBOARD */
  .leaderboard-filter {
    padding: 12px 16px;
    border-bottom: 1px solid var(--color-border-light);
  }

  .quiz-select {
    width: 100%;
    background: var(--color-bg-main);
    border: 1px solid var(--color-border-light);
    border-radius: 8px;
    color: #fff;
    font-size: 12px;
    font-family: var(--font);
    padding: 8px 12px;
    outline: none;
    cursor: pointer;
  }

  .quiz-select option {
    background: var(--color-bg-card);
  }

  .leaderboard-list {
    padding: 8px 0;
  }

  .lb-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 16px;
    transition: background 0.15s;
  }

  .lb-item:hover {
    background: var(--color-bg-row-hover);
  }

  .lb-rank {
    width: 24px;
    text-align: center;
    font-size: 13px;
    font-weight: 700;
  }

  .lb-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
  }

  .lb-info {
    flex: 1;
  }

  .lb-name {
    font-size: 13px;
    font-weight: 600;
    color: #fff;
  }

  .lb-score-bar {
    height: 3px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 2px;
    margin-top: 4px;
  }

  .lb-score-fill {
    height: 100%;
    border-radius: 2px;
  }

  .lb-score {
    font-size: 13px;
    font-weight: 700;
    color: var(--color-primary-glow);
  }

  .view-all-btn {
    display: block;
    width: 100%;
    padding: 12px;
    text-align: center;
    font-size: 12px;
    font-weight: 600;
    color: var(--color-primary-glow);
    border-top: 1px solid var(--color-border-light);
    background: transparent;
    border-left: none;
    border-right: none;
    border-bottom: none;
    cursor: pointer;
    font-family: var(--font);
    transition: background 0.15s;
  }

  .view-all-btn:hover {
    background: var(--color-bg-row-hover);
  }

  .section-tag {
    font-size: 11px;
    font-weight: 600;
    color: var(--color-primary-glow);
    background: rgba(79, 70, 229, 0.15);
    padding: 3px 10px;
    border-radius: 20px;
  }

  .view-all-link {
    font-size: 12px;
    font-weight: 600;
    color: var(--color-primary-glow);
    background: transparent;
    border: 1px solid var(--color-border-light);
    border-radius: 8px;
    padding: 5px 14px;
    cursor: pointer;
    font-family: var(--font);
    transition: background 0.15s;
    text-decoration: none;
  }

  .view-all-link:hover {
    background: var(--color-bg-row-hover);
  }
</style>
@endpush
@section('content')
<div class="content">
  <div class="stats-grid">
    <!-- Total Quizzes -->
    <div class="stat-card purple">
      <div class="stat-icon"><i class="ti ti-file-description" aria-hidden="true"></i></div>
      <div class="stat-value">{{ $totalQuizzes }}</div>
      <div class="stat-label">Total Quizzes</div>
    </div>
    <!-- Active Quizzes -->
    <div class="stat-card cyan">
      <div class="stat-icon"><i class="ti ti-player-play" aria-hidden="true"></i></div>
      <div class="stat-value">{{ $activeQuizzes }}</div>
      <div class="stat-label">Active Quizzes</div>
    </div>
    <!-- Total Students -->
    <div class="stat-card green">
      <div class="stat-icon"><i class="ti ti-users" aria-hidden="true"></i></div>
      <div class="stat-value">{{ $totalStudents }}</div>
      <div class="stat-label">Total Students</div>
    </div>
    <!-- Total Submissions -->
    <div class="stat-card pink">
      <div class="stat-icon"><i class="ti ti-clipboard-check" aria-hidden="true"></i></div>
      <div class="stat-value">{{ $totalSubmissions }}</div>
      <div class="stat-label">Submissions</div>
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
                <span class="status-dot"></span> {{ ucfirst($quiz->status) }}
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
    <div class="card">
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
</div>
@endsection
@push('scripts')
<script>
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