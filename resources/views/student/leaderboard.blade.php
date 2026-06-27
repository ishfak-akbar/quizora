@extends('layouts.student')
@section('title', 'Quizora — Leaderboard')

@push('styles')
<style>
    .lb-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 20px;
        align-items: start;
    }

    .lb-section-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--color-text-muted);
        letter-spacing: 0.8px;
        text-transform: uppercase;
        margin-bottom: 12px;
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
        text-decoration: none;
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

    /* MY RANK BANNER */
    .my-rank-banner {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 20px;
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.2), rgba(129, 140, 248, 0.1));
        border: 1px solid rgba(79, 70, 229, 0.35);
        border-radius: 14px;
        margin-bottom: 20px;
    }

    .my-rank-num {
        font-size: 28px;
        font-weight: 800;
        color: var(--color-primary-glow);
        line-height: 1;
        flex-shrink: 0;
    }

    .my-rank-label {
        font-size: 12px;
        color: var(--color-text-muted);
        margin-top: 3px;
    }

    .my-rank-score {
        margin-left: auto;
        font-size: 22px;
        font-weight: 700;
        color: #fff;
    }

    /* PODIUM */
    .podium {
        display: flex;
        align-items: flex-end;
        justify-content: center;
        gap: 16px;
        margin-bottom: 20px;
        padding: 24px;
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 16px;
    }

    .podium-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        flex: 1;
        max-width: 140px;
    }

    .podium-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        position: relative;
    }

    .podium-avatar.is-me {
        box-shadow: 0 0 0 3px #fff, 0 0 0 5px var(--color-primary-solid);
    }

    .podium-medal {
        position: absolute;
        top: -8px;
        right: -4px;
        font-size: 18px;
        line-height: 1;
    }

    .podium-name {
        font-size: 13px;
        font-weight: 600;
        color: #fff;
        text-align: center;
    }

    .podium-score {
        font-size: 13px;
        font-weight: 700;
    }

    .podium-block {
        width: 100%;
        border-radius: 8px 8px 0 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 800;
        color: rgba(255, 255, 255, 0.6);
    }

    /* TABLE */
    .lb-table {
        width: 100%;
        border-collapse: collapse;
    }

    .lb-table th {
        padding: 10px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: var(--color-text-muted);
        letter-spacing: 0.8px;
        text-transform: uppercase;
        border-bottom: 1px solid var(--color-border-light);
    }

    .lb-table td {
        padding: 12px 16px;
        font-size: 13px;
        color: var(--color-text-secondary);
        border-bottom: 1px solid var(--color-border-light);
        vertical-align: middle;
    }

    .lb-table tr:last-child td {
        border-bottom: none;
    }

    .lb-table tr:hover td {
        background: var(--color-bg-row-hover);
    }

    .lb-table tr.my-row td {
        background: rgba(79, 70, 229, 0.1);
    }

    .lb-table tr.my-row:hover td {
        background: rgba(79, 70, 229, 0.18);
    }

    .lb-avatar-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        color: #fff;
    }

    .lb-bar-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .lb-bar {
        flex: 1;
        height: 6px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 3px;
        overflow: hidden;
    }

    .lb-bar-fill {
        height: 100%;
        border-radius: 3px;
    }

    .empty-lb {
        text-align: center;
        padding: 48px;
        color: var(--color-text-muted);
    }

    .empty-lb i {
        font-size: 40px;
        display: block;
        margin-bottom: 12px;
        color: rgba(79, 70, 229, 0.3);
    }

    .empty-lb p {
        font-size: 14px;
    }

    .lb-you-badge {
        font-size: 10px;
        font-weight: 700;
        background: rgba(79, 70, 229, 0.3);
        color: var(--color-primary-glow);
        padding: 2px 8px;
        border-radius: 20px;
        margin-left: 8px;
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <h1>Leaderboard</h1>
    <p>See how you rank against other students</p>
</div>

<div class="lb-layout">

    {{-- QUIZ LIST --}}
    <div>
        <div class="lb-section-label">Select Quiz</div>
        <div class="quiz-list">
            @forelse($quizzes as $quiz)
            <div class="quiz-list-item" onclick="loadLeaderboard({{ $quiz->id }}, this)">
                <div class="quiz-list-icon">
                    <i class="ti ti-file-description"></i>
                </div>
                <div style="flex:1;overflow:hidden;">
                    <div class="quiz-list-name">{{ $quiz->title }}</div>
                    <div class="quiz-list-meta">{{ $quiz->category ?? 'General' }} · {{ ucfirst($quiz->difficulty) }}</div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:32px;color:var(--color-text-muted);font-size:13px;">
                No quizzes available yet.
            </div>
            @endforelse
        </div>
    </div>

    {{-- LEADERBOARD PANEL --}}
    <div id="lbPanel">
        <div class="empty-lb">
            <i class="ti ti-trophy"></i>
            <p>Select a quiz to see the leaderboard</p>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    const colors = ['#F59E0B', '#9CA3AF', '#D97706', '#4F46E5', '#7C3AED', '#0891B2', '#059669', '#DB2777'];
    const medals = ['🥇', '🥈', '🥉'];
    const podiumColors = ['#F59E0B', '#9CA3AF', '#D97706'];

    function getInitials(name) {
        const parts = name.trim().split(' ');
        return parts.length > 1 ?
            (parts[0][0] + parts[parts.length - 1][0]).toUpperCase() :
            name.substring(0, 2).toUpperCase();
    }

    function loadLeaderboard(quizId, el) {
        document.querySelectorAll('.quiz-list-item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');

        const panel = document.getElementById('lbPanel');
        panel.innerHTML = '<div style="text-align:center;padding:48px;color:var(--color-text-muted);font-size:13px;"><i class="ti ti-loader-2" style="font-size:28px;display:block;margin-bottom:12px;animation:spin 1s linear infinite;"></i>Loading...</div>';

        fetch(`/student/leaderboard/${quizId}`)
            .then(r => r.json())
            .then(({
                entries,
                my_rank,
                my_score
            }) => {
                if (!entries.length) {
                    panel.innerHTML = `<div class="empty-lb"><i class="ti ti-inbox"></i><p>No submissions yet for this quiz.</p></div>`;
                    return;
                }

                // MY RANK BANNER
                let myBanner = '';
                if (my_rank) {
                    myBanner = `
                        <div class="my-rank-banner">
                            <div>
                                <div class="my-rank-num">#${my_rank}</div>
                                <div class="my-rank-label">Your Rank</div>
                            </div>
                            <div style="color:var(--color-text-muted);font-size:13px;">You scored <strong style="color:#fff;">${my_score}%</strong> on this quiz</div>
                            <div class="my-rank-score">${my_score}%</div>
                        </div>`;
                }

                // PODIUM
                const top3 = entries.slice(0, 3);
                const podiumOrder = top3.length >= 2 ?
                    [top3[1], top3[0], top3[2]].filter(Boolean) :
                    [top3[0]];
                const podiumRanks = top3.length >= 2 ? [1, 0, 2] : [0];
                const podiumHeights = top3.length >= 2 ? ['80px', '110px', '60px'] : ['110px'];

                const podiumHTML = podiumOrder.map((s, i) => {
                    const ri = podiumRanks[i];
                    return `
                        <div class="podium-item">
                            <div class="podium-avatar ${s.is_me ? 'is-me' : ''}" style="background:${colors[ri]}">
                                ${getInitials(s.name)}
                                <span class="podium-medal">${medals[ri]}</span>
                            </div>
                            <div class="podium-name">${s.name}${s.is_me ? ' <span style="font-size:10px;color:var(--color-primary-glow);">(You)</span>' : ''}</div>
                            <div class="podium-score" style="color:${podiumColors[ri]}">${s.score}%</div>
                            <div class="podium-block" style="height:${podiumHeights[i]};background:${colors[ri]}22;border:1px solid ${colors[ri]}44;">${ri + 1}</div>
                        </div>`;
                }).join('');

                // TABLE
                const tableHTML = entries.map((s, i) => `
                    <tr class="${s.is_me ? 'my-row' : ''}">
                        <td style="font-weight:700;width:48px;">
                            ${i < 3
                                ? `<span style="font-size:18px">${medals[i]}</span>`
                                : `<span style="color:var(--color-text-muted)">${i + 1}</span>`}
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="lb-avatar-sm" style="background:${colors[i] || '#6B7280'}">${getInitials(s.name)}</div>
                                <span style="font-weight:600;color:#fff;">${s.name}</span>
                                ${s.is_me ? '<span class="lb-you-badge">You</span>' : ''}
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
                    </tr>`).join('');

                panel.innerHTML = `
                    ${myBanner}
                    <div class="podium">${podiumHTML}</div>
                    <div class="card">
                        <div class="card-header">
                            <h2>Full Rankings</h2>
                            <span style="font-size:12px;color:var(--color-text-muted);">${entries.length} student${entries.length !== 1 ? 's' : ''}</span>
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
                    </div>`;
            })
            .catch(() => {
                panel.innerHTML = '<div style="text-align:center;padding:48px;color:var(--color-status-error);font-size:13px;">Failed to load leaderboard.</div>';
            });
    }
</script>
<style>
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
@endpush