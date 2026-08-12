@extends('layouts.teacher')
@section('title', 'Leaderboard')
@section('page-title', 'Leaderboard')
@section('page-subtitle', 'View student rankings and quiz performance.')

@push('styles')
<link rel="stylesheet" href="{{ asset('teacher.css') }}">
@endpush

@section('content')

<div class="lb-layout">

  <!-- QUIZ LIST -->
  <div>
    <div class="panel-sidebar-label">Select Quiz</div>
    <div class="quiz-list">
      @forelse($quizzes as $quiz)
      <div class="quiz-list-item" onclick="loadLeaderboard({{ $quiz->id }}, this)">
        <div class="quiz-list-icon">
          <i class="{{ \App\Helpers\QuizHelper::categoryIcon($quiz->category) }}"></i>
        </div>
        <div style="flex:1;overflow:hidden;">
          <div class="quiz-list-name">{{ $quiz->title }}</div>
          <div class="quiz-list-meta">{{ ucfirst($quiz->display_status) }}</div>
        </div>
      </div>
      @empty
      <div style="text-align:center;padding:32px;color:var(--color-text-muted);font-size:13px;">
        No quizzes yet.
      </div>
      @endforelse
    </div>
  </div>

  <!-- LEADERBOARD PANEL -->
  <div id="lbPanel">
    <div class="empty-lb">
      <i class="ti ti-trophy"></i>
      <p>Select a quiz to view leaderboard</p>
    </div>
  </div>

</div>

@endsection

@push('scripts')
<script>
  const colors = ['#4F46E5', '#7C3AED', '#0891B2', '#059669', '#D97706', '#DB2777', '#0891B2'];
  const medals = ['🥇', '🥈', '🥉'];
  const medalColors = ['#F59E0B', '#9CA3AF', '#D97706'];

  function getInitials(name) {
    const parts = name.split(' ');
    return parts.length > 1 ?
      (parts[0][0] + parts[1][0]).toUpperCase() :
      name.substring(0, 2).toUpperCase();
  }

  function loadLeaderboard(quizId, el) {
    document.querySelectorAll('.quiz-list-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');

    const panel = document.getElementById('lbPanel');
    panel.innerHTML = '<div style="text-align:center;padding:48px;color:var(--color-text-muted);font-size:13px;">Loading...</div>';

    fetch(`/teacher/leaderboard/${quizId}`)
      .then(res => res.json())
      .then(data => {
        if (data.length === 0) {
          panel.innerHTML = `
        <div class="empty-lb">
          <i class="ti ti-inbox"></i>
          <p>No submissions yet for this quiz.</p>
        </div>`;
          return;
        }

        // TOP SCORER BANNER
        const topScorer = data[0];
        const topScorerHTML = `
      <div class="top-scorer-banner">
        <div class="top-scorer-icon"><i class="ti ti-crown"></i></div>
        <div class="top-scorer-text">
          <div class="top-scorer-label">Top Scorer</div>
          <div class="top-scorer-name">${topScorer.name}</div>
        </div>
        <div class="top-scorer-score">${topScorer.score}%</div>
      </div>`;

        // TOP 3 PODIUM
        const top3 = data.slice(0, 3);
        const podiumOrder = top3.length >= 2 ? [top3[1], top3[0], top3[2]].filter(Boolean) :
          top3;
        const podiumHeights = top3.length >= 2 ? ['80px', '110px', '60px'] : ['110px'];
        const podiumRanks = top3.length >= 2 ? [2, 1, 3] : [1];

        let podiumHTML = podiumOrder.map((s, i) => `
      <div class="podium-item">
        <div class="podium-avatar" style="background:${colors[podiumRanks[i]-1]}">
          ${getInitials(s.name)}
          <span class="podium-medal">${medals[podiumRanks[i]-1]}</span>
        </div>
        <div class="podium-name">${s.name}</div>
        <div class="podium-score" style="color:${medalColors[podiumRanks[i]-1]}">${s.score}%</div>
        <div class="podium-block" style="height:${podiumHeights[i]};background:${colors[podiumRanks[i]-1]}22;border:1px solid ${colors[podiumRanks[i]-1]}44;">
          ${podiumRanks[i]}
        </div>
      </div>
    `).join('');

        // FULL TABLE
        let tableHTML = data.map((s, i) => `
      <tr>
        <td class="lb-rank-cell">
          ${i < 3
            ? `<span style="font-size:18px;">${medals[i]}</span>`
            : `<span style="color:var(--color-text-muted);">${i+1}</span>`}
        </td>
        <td>
          <div style="display:flex;align-items:center;gap:10px;">
            <div class="lb-avatar-sm" style="background:${colors[i] || '#6B7280'}">
              ${getInitials(s.name)}
            </div>
            <div style="font-weight:600;color:#fff;">${s.name}</div>
          </div>
        </td>
        <td style="font-weight:700;color:#fff;">${s.raw_score} / ${s.total}</td>
        <td>
          <div class="lb-bar-wrap">
            <div class="lb-bar">
              <div class="lb-bar-fill" style="width:${s.score}%;background:${colors[i] || '#6B7280'}"></div>
            </div>
            <span style="font-size:13px;font-weight:700;color:${colors[i] || '#6B7280'};min-width:40px;text-align:right;">${s.score}%</span>
          </div>
        </td>
      </tr>
    `).join('');

        panel.innerHTML = `
      ${topScorerHTML}
      <div class="podium">${podiumHTML}</div>
      <div class="card">
        <div class="card-header">
          <h2>Full Rankings</h2>
          <span style="font-size:12px;color:var(--color-text-muted);">${data.length} students</span>
        </div>
        <table class="lb-table">
          <thead>
            <tr>
              <th>Rank</th>
              <th>Student</th>
              <th>Score</th>
              <th>Performance</th>
            </tr>
          </thead>
          <tbody>${tableHTML}</tbody>
        </table>
      </div>
    `;
      })
      .catch(() => {
        panel.innerHTML = '<div style="text-align:center;padding:48px;color:var(--color-status-error);font-size:13px;">Failed to load leaderboard.</div>';
      });
  }
</script>
@endpush