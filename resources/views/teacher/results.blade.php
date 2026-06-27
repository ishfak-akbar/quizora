@extends('layouts.teacher')
@section('title', 'Quizora — Results')

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

    .results-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 20px;
        align-items: start;
    }

    .quiz-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .quiz-list-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .quiz-list-item:hover {
        border-color: rgba(79, 70, 229, 0.4);
        background: var(--color-bg-row-hover);
    }

    .quiz-list-item.active {
        border-color: var(--color-primary-solid);
        background: rgba(79, 70, 229, 0.15);
    }

    .quiz-list-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: rgba(79, 70, 229, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: var(--color-primary-glow);
        flex-shrink: 0;
    }

    .quiz-list-item.active .quiz-list-icon {
        background: var(--color-primary-solid);
        color: #fff;
    }

    .quiz-list-name {
        font-size: 13px;
        font-weight: 600;
        color: #fff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .quiz-list-meta {
        font-size: 11px;
        color: var(--color-text-muted);
        margin-top: 2px;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }

    .mini-stat {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        padding: 18px;
        text-align: center;
    }

    .mini-stat-value {
        font-size: 22px;
        font-weight: 700;
        color: #fff;
    }

    .mini-stat-label {
        font-size: 11px;
        color: var(--color-text-muted);
        margin-top: 4px;
    }

    .results-table {
        width: 100%;
        border-collapse: collapse;
    }

    .results-table th {
        padding: 10px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: var(--color-text-muted);
        letter-spacing: 0.8px;
        text-transform: uppercase;
        border-bottom: 1px solid var(--color-border-light);
    }

    .results-table td {
        padding: 12px 16px;
        font-size: 13px;
        color: var(--color-text-secondary);
        border-bottom: 1px solid var(--color-border-light);
        vertical-align: middle;
    }

    .results-table tr:last-child td {
        border-bottom: none;
    }

    .results-table tr:hover td {
        background: var(--color-bg-row-hover);
    }

    .rank-badge {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
    }

    .student-name {
        font-size: 13px;
        font-weight: 600;
        color: #fff;
    }

    .student-email {
        font-size: 11px;
        color: var(--color-text-muted);
        margin-top: 2px;
    }

    .score-bar-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .score-bar {
        flex: 1;
        height: 6px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 3px;
        overflow: hidden;
    }

    .score-bar-fill {
        height: 100%;
        border-radius: 3px;
    }

    .score-pct {
        font-size: 13px;
        font-weight: 700;
        min-width: 36px;
        text-align: right;
    }

    .empty-results {
        text-align: center;
        padding: 48px;
        color: var(--color-text-muted);
    }

    .empty-results i {
        font-size: 40px;
        display: block;
        margin-bottom: 12px;
        color: rgba(79, 70, 229, 0.3);
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div>
        <h1>Results</h1>
        <p>View student performance across all your quizzes</p>
    </div>
</div>

<div class="results-layout">

    <div>
        <div style="font-size:12px;font-weight:600;color:var(--color-text-muted);letter-spacing:0.8px;text-transform:uppercase;margin-bottom:12px;">
            Select Quiz
        </div>
        <div class="quiz-list">
            @forelse($quizzes as $quiz)
            <div class="quiz-list-item" onclick="loadResults({{ $quiz->id }}, this)">
                <div class="quiz-list-icon"><i class="ti ti-file-description"></i></div>
                <div style="flex:1;overflow:hidden;">
                    <div class="quiz-list-name">{{ $quiz->title }}</div>
                    <div class="quiz-list-meta">{{ $quiz->submitted_count }} submitted · {{ ucfirst($quiz->status) }}</div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:32px;color:var(--color-text-muted);font-size:13px;">No quizzes yet.</div>
            @endforelse
        </div>
    </div>

    <div id="resultsPanel">
        <div class="empty-results">
            <i class="ti ti-chart-bar"></i>
            <p>Select a quiz to view results</p>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    const medals = ['🥇', '🥈', '🥉'];
    const medalColors = ['#F59E0B', '#9CA3AF', '#D97706'];

    function scoreColor(pct) {
        return pct >= 80 ? 'var(--color-status-success)' : pct >= 50 ? 'var(--color-stat-cyan)' : 'var(--color-status-error)';
    }

    function loadResults(quizId, el) {
        document.querySelectorAll('.quiz-list-item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');

        const panel = document.getElementById('resultsPanel');
        panel.innerHTML = '<div style="text-align:center;padding:48px;color:var(--color-text-muted);font-size:13px;">Loading...</div>';

        fetch(`/teacher/results/${quizId}`)
            .then(res => res.json())
            .then(data => {
                if (data.attempts.length === 0) {
                    panel.innerHTML = `<div class="empty-results"><i class="ti ti-inbox"></i><p>No submissions yet.</p></div>`;
                    return;
                }
                panel.innerHTML = `
                    <div class="stats-row">
                        <div class="mini-stat"><div class="mini-stat-value">${data.stats.submissions}</div><div class="mini-stat-label">Submissions</div></div>
                        <div class="mini-stat"><div class="mini-stat-value" style="color:var(--color-status-success)">${data.stats.avg}%</div><div class="mini-stat-label">Average Score</div></div>
                        <div class="mini-stat"><div class="mini-stat-value" style="color:var(--color-primary-glow)">${data.stats.highest}%</div><div class="mini-stat-label">Highest Score</div></div>
                        <div class="mini-stat"><div class="mini-stat-value" style="color:var(--color-status-error)">${data.stats.lowest}%</div><div class="mini-stat-label">Lowest Score</div></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h2>Student Submissions</h2>
                            <span style="font-size:12px;color:var(--color-text-muted);">${data.stats.submissions} total</span>
                        </div>
                        <table class="results-table">
                            <thead><tr><th>Rank</th><th>Student</th><th>Score</th><th>Performance</th><th>Submitted</th></tr></thead>
                            <tbody>
                                ${data.attempts.map((a, i) => `
                                    <tr>
                                        <td><div class="rank-badge" style="background:${i < 3 ? medalColors[i]+'22' : 'rgba(255,255,255,0.05)'};color:${i < 3 ? medalColors[i] : 'var(--color-text-muted)'};">
                                            ${i < 3 ? medals[i] : i+1}
                                        </div></td>
                                        <td><div class="student-name">${a.name}</div><div class="student-email">${a.email}</div></td>
                                        <td style="font-weight:700;color:#fff;">${a.score} / ${a.total}</td>
                                        <td><div class="score-bar-wrap">
                                            <div class="score-bar"><div class="score-bar-fill" style="width:${a.percentage}%;background:${scoreColor(a.percentage)}"></div></div>
                                            <div class="score-pct" style="color:${scoreColor(a.percentage)}">${a.percentage}%</div>
                                        </div></td>
                                        <td style="color:var(--color-text-muted);font-size:12px;">${a.submitted}</td>
                                    </tr>`).join('')}
                            </tbody>
                        </table>
                    </div>`;
            })
            .catch(() => {
                panel.innerHTML = '<div style="text-align:center;padding:48px;color:var(--color-status-error);font-size:13px;">Failed to load results.</div>';
            });
    }

    // Auto-load first quiz
    const first = document.querySelector('.quiz-list-item');
    if (first) first.click();
</script>
@endpush