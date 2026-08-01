@extends('layouts.admin')

@section('title', 'Quizora — ' . $student->name)
@section('page-title', 'Student Details')
@section('page-subtitle', $student->name)

@push('styles')
<style>
    .student-hero {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .student-hero-left {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .student-avatar {
        width: 64px;
        height: 64px;
        border-radius: 14px;
        background: linear-gradient(135deg, #3B82F6, #2563EB);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        color: #fff;
    }

    .student-name {
        font-size: 22px;
        font-weight: 700;
        color: #fff;
    }

    .student-email {
        font-size: 13px;
        color: var(--color-text-muted);
        margin-top: 4px;
    }

    .student-meta {
        display: flex;
        gap: 12px;
        margin-top: 8px;
        flex-wrap: wrap;
    }

    .student-meta span {
        font-size: 12px;
        color: var(--color-text-secondary);
        background: rgba(255, 255, 255, 0.04);
        padding: 4px 10px;
        border-radius: 6px;
    }

    .student-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .s-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        font-family: var(--font);
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .s-btn.ghost {
        background: transparent;
        border: 1px solid var(--color-border-light);
        color: var(--color-text-secondary);
    }

    .s-btn.ghost:hover {
        background: var(--color-bg-row-hover);
        color: #fff;
    }

    .s-btn.danger {
        background: rgba(248, 113, 113, 0.15);
        border: 1px solid rgba(248, 113, 113, 0.3);
        color: #F87171;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }

    .stat-box {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .stat-box-icon {
        width: 44px;
        height: 44px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        background: rgba(16, 185, 129, 0.15);
        color: #34D399;
    }

    .stat-box-value {
        font-size: 22px;
        font-weight: 700;
        color: #fff;
    }

    .stat-box-label {
        font-size: 12px;
        color: var(--color-text-muted);
        margin-top: 2px;
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .attempts-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        overflow: hidden;
    }

    .a-table {
        width: 100%;
        border-collapse: collapse;
    }

    .a-table th {
        text-align: left;
        padding: 12px 18px;
        font-size: 11px;
        font-weight: 600;
        color: var(--color-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--color-border-light);
        background: rgba(255, 255, 255, 0.02);
    }

    .a-table td {
        padding: 13px 18px;
        font-size: 13px;
        color: var(--color-text-secondary);
        border-bottom: 1px solid var(--color-border-light);
    }

    .a-table tr:last-child td {
        border-bottom: none;
    }

    .a-table tr:hover td {
        background: var(--color-bg-row-hover);
    }

    .a-title {
        font-weight: 600;
        color: #fff;
    }

    .score-good {
        color: #34D399;
        font-weight: 700;
    }

    .score-mid {
        color: #F59E0B;
        font-weight: 700;
    }

    .score-low {
        color: #F87171;
        font-weight: 700;
    }

    .empty-box {
        text-align: center;
        padding: 40px;
        color: var(--color-text-muted);
    }

    @media (max-width: 900px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')

<div class="student-hero">
    <div class="student-hero-left">
        <div class="student-avatar">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
        <div>
            <div class="student-name">{{ $student->name }}</div>
            <div class="student-email">{{ $student->email }}</div>
            <div class="student-meta">
                <span><i class="ti ti-calendar"></i> Joined {{ $student->created_at->format('M d, Y') }}</span>
                <span class="status-badge status-{{ $student->status ?? 'active' }}">
                    {{ ucfirst($student->status ?? 'active') }}
                </span>
            </div>
        </div>
    </div>

    <div class="student-actions">
        <a href="{{ route('admin.students.index') }}" class="s-btn ghost">
            <i class="ti ti-arrow-left"></i> Back
        </a>

        <form method="POST" action="{{ route('admin.users.suspend', $student) }}" style="display:inline;">
            @csrf
            @method('PATCH')
            <button type="submit" class="s-btn ghost">
                <i class="ti ti-{{ ($student->status ?? 'active') === 'suspended' ? 'player-play' : 'player-pause' }}"></i>
                {{ ($student->status ?? 'active') === 'suspended' ? 'Activate' : 'Suspend' }}
            </button>
        </form>

        <form method="POST" action="{{ route('admin.users.destroy', $student) }}" style="display:inline;"
            onsubmit="return confirm('Delete this student?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="s-btn danger">
                <i class="ti ti-trash"></i> Delete
            </button>
        </form>
    </div>
</div>

<div class="stats-row">
    <div class="stat-box">
        <div class="stat-box-icon"><i class="ti ti-clipboard-list"></i></div>
        <div>
            <div class="stat-box-value">{{ $totalAttempts }}</div>
            <div class="stat-box-label">Total Attempts</div>
        </div>
    </div>
    <div class="stat-box">
        <div class="stat-box-icon"><i class="ti ti-chart-bar"></i></div>
        <div>
            <div class="stat-box-value">{{ $avgScore }}%</div>
            <div class="stat-box-label">Average Score</div>
        </div>
    </div>
    <div class="stat-box">
        <div class="stat-box-icon"><i class="ti ti-trophy"></i></div>
        <div>
            <div class="stat-box-value">{{ $bestScore }}%</div>
            <div class="stat-box-label">Best Score</div>
        </div>
    </div>
    <div class="stat-box">
        <div class="stat-box-icon"><i class="ti ti-circle-check"></i></div>
        <div>
            <div class="stat-box-value">{{ $quizzesPassed }}</div>
            <div class="stat-box-label">Quizzes Passed</div>
        </div>
    </div>
</div>

<div class="section-title">
    <span>Attempt History</span>
    <span style="font-size:13px; color:var(--color-text-muted); font-weight:500;">{{ $attempts->count() }} total</span>
</div>

<div class="attempts-card">
    @if($attempts->count() > 0)
    <table class="a-table">
        <thead>
            <tr>
                <th>Quiz</th>
                <th>Score</th>
                <th>Percentage</th>
                <th>Submitted</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attempts as $attempt)
            @php
            $pct = $attempt->total_marks > 0
            ? round(($attempt->score / $attempt->total_marks) * 100)
            : 0;
            $scoreClass = $pct >= 70 ? 'score-good' : ($pct >= 40 ? 'score-mid' : 'score-low');
            @endphp
            <tr onclick="window.location='{{ route('admin.students.attempt', [$student, $attempt]) }}'"
                style="cursor: pointer;">
                <td>
                    <div class="a-title">{{ $attempt->quiz->title ?? 'Deleted Quiz' }}</div>
                </td>
                <td>{{ $attempt->score }} / {{ $attempt->total_marks }}</td>
                <td class="{{ $scoreClass }}">{{ $pct }}%</td>
                <td>{{ $attempt->submitted_at?->format('M d, Y H:i') ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-box">
        <i class="ti ti-clipboard-off" style="font-size:32px; display:block; margin-bottom:10px; opacity:0.4;"></i>
        This student has not submitted any quizzes yet.
    </div>
    @endif
</div>

@endsection