@extends('layouts.teacher')
@section('title', 'Quizora — Results')

@push('styles')
<link rel="stylesheet" href="{{ asset('teacher.css') }}">
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
        <div class="panel-sidebar-label">Select Quiz</div>
        <div class="quiz-list">
            @forelse($quizzes as $quiz)
            <div class="quiz-list-item" onclick="loadResults({{ $quiz->id }}, this)">
                <div class="quiz-list-icon"><i class="{{ \App\Helpers\QuizHelper::categoryIcon($quiz->category) }}"></i></div>
                <div style="flex:1;overflow:hidden;">
                    <div class="quiz-list-name">{{ $quiz->title }}</div>
                    <div class="quiz-list-meta">{{ $quiz->submitted_count }} submitted · {{ ucfirst($quiz->display_status) }}</div>
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
                        <div class="stat-card-mini purple">
                            <div class="stat-value">${data.stats.submissions}</div>
                            <div class="stat-label">Submissions</div>
                        </div>
                        <div class="stat-card-mini green">
                            <div class="stat-value" style="color:var(--color-status-success)">${data.stats.avg}%</div>
                            <div class="stat-label">Average Score</div>
                        </div>
                        <div class="stat-card-mini cyan">
                            <div class="stat-value" style="color:var(--color-primary-glow)">${data.stats.highest}%</div>
                            <div class="stat-label">Highest Score</div>
                        </div>
                        <div class="stat-card-mini amber">
                            <div class="stat-value" style="color:var(--color-status-error)">${data.stats.lowest}%</div>
                            <div class="stat-label">Lowest Score</div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h2>Student Submissions</h2>
                            <span style="font-size:12px;color:var(--color-text-muted);">${data.stats.submissions} total</span>
                        </div>
                        <table class="results-table">
                            <thead><tr><th>#</th><th>Student</th><th>Score</th><th>Performance</th><th>Status</th><th>Submitted</th></tr></thead>
                            <tbody>
                                ${data.attempts.map((a, i) => `
                                    <tr>
                                        <td><div class="rank-badge" style="background:rgba(255,255,255,0.05);color:var(--color-text-muted);">
                                            ${i+1}
                                        </div></td>
                                        <td><div class="student-name">${a.name}</div><div class="student-email">${a.email}</div></td>
                                        <td style="font-weight:700;color:#fff;">${a.score} / ${a.total}</td>
                                        <td><div class="score-bar-wrap">
                                            <div class="score-bar"><div class="score-bar-fill" style="width:${a.percentage}%;background:${scoreColor(a.percentage)}"></div></div>
                                            <div class="score-pct" style="color:${scoreColor(a.percentage)}">${a.percentage}%</div>
                                        </div></td>
                                        <td>
                                            <span class="result-status ${a.percentage >= 50 ? 'pass' : 'fail'}">
                                                ${a.percentage >= 50 ? 'Pass' : 'Fail'}
                                            </span>
                                        </td>
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