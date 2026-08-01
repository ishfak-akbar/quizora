@extends('layouts.admin')

@section('title', 'Quizora — ' . $teacher->name)
@section('page-title', 'Teacher Details')
@section('page-subtitle', $teacher->name)

@push('styles')
<style>
    .teacher-hero {
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

    .teacher-hero-left {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .teacher-avatar {
        width: 64px;
        height: 64px;
        border-radius: 14px;
        background: linear-gradient(135deg, #10B981, #059669);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        color: #fff;
    }

    .teacher-name {
        font-size: 22px;
        font-weight: 700;
        color: #fff;
    }

    .teacher-email {
        font-size: 13px;
        color: var(--color-text-muted);
        margin-top: 4px;
    }

    .teacher-meta {
        display: flex;
        gap: 12px;
        margin-top: 8px;
        flex-wrap: wrap;
    }

    .teacher-meta span {
        font-size: 12px;
        color: var(--color-text-secondary);
        background: rgba(255, 255, 255, 0.04);
        padding: 4px 10px;
        border-radius: 6px;
    }

    .teacher-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .t-btn {
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

    .t-btn.ghost {
        background: transparent;
        border: 1px solid var(--color-border-light);
        color: var(--color-text-secondary);
    }

    .t-btn.ghost:hover {
        background: var(--color-bg-row-hover);
        color: #fff;
    }

    .t-btn.danger {
        background: rgba(248, 113, 113, 0.15);
        border: 1px solid rgba(248, 113, 113, 0.3);
        color: #F87171;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
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
        font-size: 24px;
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

    .quizzes-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        overflow: hidden;
    }

    .q-table {
        width: 100%;
        border-collapse: collapse;
    }

    .q-table th {
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

    .q-table td {
        padding: 13px 18px;
        font-size: 13px;
        color: var(--color-text-secondary);
        border-bottom: 1px solid var(--color-border-light);
    }

    .q-table tr:last-child td {
        border-bottom: none;
    }

    .q-table tr:hover td {
        background: var(--color-bg-row-hover);
    }

    .q-title {
        font-weight: 600;
        color: #fff;
    }

    .status-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
    }

    .status-active {
        background: rgba(16, 185, 129, 0.15);
        color: #34D399;
    }

    .status-draft {
        background: rgba(107, 114, 128, 0.2);
        color: #9CA3AF;
    }

    .status-closed,
    .status-ended {
        background: rgba(248, 113, 113, 0.15);
        color: #F87171;
    }

    .empty-box {
        text-align: center;
        padding: 40px;
        color: var(--color-text-muted);
    }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="teacher-hero">
    <div class="teacher-hero-left">
        <div class="teacher-avatar">{{ strtoupper(substr($teacher->name, 0, 1)) }}</div>
        <div>
            <div class="teacher-name">{{ $teacher->name }}</div>
            <div class="teacher-email">{{ $teacher->email }}</div>
            <div class="teacher-meta">
                <span><i class="ti ti-calendar"></i> Joined {{ $teacher->created_at->format('M d, Y') }}</span>
                <span class="status-badge status-{{ $teacher->status ?? 'active' }}">
                    {{ ucfirst($teacher->status ?? 'active') }}
                </span>
            </div>
        </div>
    </div>

    <div class="teacher-actions">
        <a href="javascript:history.back()" class="t-btn ghost">
            <i class="ti ti-arrow-left"></i> Back
        </a>

        <form method="POST" action="{{ route('admin.users.suspend', $teacher) }}" style="display:inline;">
            @csrf
            @method('PATCH')
            <button type="submit" class="t-btn ghost">
                <i class="ti ti-{{ ($teacher->status ?? 'active') === 'suspended' ? 'player-play' : 'player-pause' }}"></i>
                {{ ($teacher->status ?? 'active') === 'suspended' ? 'Activate' : 'Suspend' }}
            </button>
        </form>

        <form method="POST" action="{{ route('admin.users.destroy', $teacher) }}" style="display:inline;"
            onsubmit="return confirm('Delete this teacher and all their quizzes?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="t-btn danger">
                <i class="ti ti-trash"></i> Delete
            </button>
        </form>
    </div>
</div>

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-box">
        <div class="stat-box-icon"><i class="ti ti-file-description"></i></div>
        <div>
            <div class="stat-box-value">{{ $teacher->quizzes_count }}</div>
            <div class="stat-box-label">Quizzes Created</div>
        </div>
    </div>
    <div class="stat-box">
        <div class="stat-box-icon"><i class="ti ti-clipboard-check"></i></div>
        <div>
            <div class="stat-box-value">{{ $totalSubmissions }}</div>
            <div class="stat-box-label">Total Submissions</div>
        </div>
    </div>
    <div class="stat-box">
        <div class="stat-box-icon"><i class="ti ti-users"></i></div>
        <div>
            <div class="stat-box-value">{{ $uniqueStudents }}</div>
            <div class="stat-box-label">Students Reached</div>
        </div>
    </div>
</div>

{{-- Quizzes list --}}
<div class="section-title">
    <span>Quizzes by {{ $teacher->name }}</span>
    <span style="font-size:13px; color:var(--color-text-muted); font-weight:500;">{{ $quizzes->count() }} total</span>
</div>

<div class="quizzes-card">
    @if($quizzes->count() > 0)
    <table class="q-table">
        <thead>
            <tr>
                <th>Quiz</th>
                <th>Status</th>
                <th>Visibility</th>
                <th>Submissions</th>
                <th>Created</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($quizzes as $quiz)
            <tr>
                <td>
                    <div class="q-title">{{ $quiz->title }}</div>
                    <div style="font-size:11px; color:var(--color-text-muted); margin-top:2px;">
                        {{ $quiz->category ?? 'General' }} · {{ ucfirst($quiz->difficulty ?? 'medium') }}
                    </div>
                </td>
                <td>
                    <span class="status-badge status-{{ $quiz->display_status }}">
                        {{ ucfirst($quiz->display_status) }}
                    </span>
                </td>
                <td>{{ ucfirst($quiz->visibility) }}</td>
                <td>{{ $quiz->submitted_count }}</td>
                <td>{{ $quiz->created_at->format('M d, Y') }}</td>
                <td>
                    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="t-btn ghost" style="padding:6px 10px;">
                        <i class="ti ti-eye"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-box">
        <i class="ti ti-file-off" style="font-size:32px; display:block; margin-bottom:10px; opacity:0.4;"></i>
        This teacher has not created any quizzes yet.
    </div>
    @endif
</div>

@endsection