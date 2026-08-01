@extends('layouts.admin')

@section('title', 'Quizora — Students')
@section('page-title', 'Students')
@section('page-subtitle', 'Manage all students on the platform')

@push('styles')
<style>
    .students-toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .students-search {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 10px;
        padding: 0 14px;
        flex: 1;
        max-width: 360px;
        height: 40px;
    }

    .students-search input {
        background: none;
        border: none;
        outline: none;
        color: #fff;
        font-size: 13px;
        width: 100%;
        font-family: var(--font);
    }

    .students-search input::placeholder {
        color: var(--color-text-muted);
    }

    .students-table-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        overflow: hidden;
    }

    .students-table {
        width: 100%;
        border-collapse: collapse;
    }

    .students-table th {
        text-align: left;
        padding: 12px 18px;
        font-size: 11px;
        font-weight: 600;
        color: var(--color-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        border-bottom: 1px solid var(--color-border-light);
        background: rgba(255, 255, 255, 0.02);
    }

    .students-table td {
        padding: 14px 18px;
        font-size: 13px;
        color: var(--color-text-secondary);
        border-bottom: 1px solid var(--color-border-light);
        vertical-align: middle;
    }

    .students-table tr:last-child td {
        border-bottom: none;
    }

    .students-table tr:hover td {
        background: var(--color-bg-row-hover);
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar-sm {
        width: 38px;
        height: 38px;
        border-radius: 9px;
        background: linear-gradient(135deg, #3B82F6, #2563EB);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .user-name {
        font-weight: 600;
        color: #fff;
        font-size: 13.5px;
    }

    .user-email {
        font-size: 12px;
        color: var(--color-text-muted);
        margin-top: 1px;
    }

    .stat-num {
        font-weight: 700;
        color: #fff;
        font-size: 14px;
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

    .status-suspended {
        background: rgba(248, 113, 113, 0.15);
        color: #F87171;
    }

    .action-btns {
        display: flex;
        gap: 6px;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--color-border-light);
        background: transparent;
        color: var(--color-text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 15px;
        transition: all 0.15s;
        text-decoration: none;
    }

    .action-btn:hover {
        background: var(--color-bg-row-hover);
        color: #fff;
        border-color: rgba(16, 185, 129, 0.4);
    }

    .action-btn.danger:hover {
        background: rgba(248, 113, 113, 0.15);
        color: #F87171;
        border-color: rgba(248, 113, 113, 0.4);
    }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: var(--color-text-muted);
    }

    .pagination-wrap {
        padding: 16px 18px;
        border-top: 1px solid var(--color-border-light);
        display: flex;
        justify-content: flex-end;
    }
</style>
@endpush

@section('content')

<form method="GET" action="{{ route('admin.students.index') }}" class="students-toolbar">
    <div class="students-search">
        <i class="ti ti-search" style="color:var(--color-text-muted); font-size:16px;"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search students...">
    </div>
</form>

<div class="students-table-card">
    <table class="students-table">
        <thead>
            <tr>
                <th>Student</th>
                <th>Attempts</th>
                <th>Avg Score</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
            <tr>
                <td>
                    <div class="user-cell">
                        <div class="user-avatar-sm">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="user-name">{{ $student->name }}</div>
                            <div class="user-email">{{ $student->email }}</div>
                        </div>
                    </div>
                </td>
                <td><span class="stat-num">{{ $student->total_attempts }}</span></td>
                <td>
                    <span class="stat-num">
                        {{ $student->avg_score !== null ? $student->avg_score . '%' : '—' }}
                    </span>
                </td>
                <td>
                    <span class="status-badge status-{{ $student->status ?? 'active' }}">
                        {{ ucfirst($student->status ?? 'active') }}
                    </span>
                </td>
                <td>{{ $student->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('admin.students.show', $student) }}" class="action-btn" title="View">
                            <i class="ti ti-eye"></i>
                        </a>

                        <form method="POST" action="{{ route('admin.users.suspend', $student) }}" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="action-btn" title="{{ ($student->status ?? 'active') === 'suspended' ? 'Activate' : 'Suspend' }}">
                                <i class="ti ti-{{ ($student->status ?? 'active') === 'suspended' ? 'player-play' : 'player-pause' }}"></i>
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.users.destroy', $student) }}" style="display:inline;"
                            onsubmit="return confirm('Are you sure you want to delete this student?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn danger" title="Delete">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <i class="ti ti-user" style="font-size:36px; display:block; margin-bottom:12px; opacity:0.4;"></i>
                        No students found.
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($students->hasPages())
    <div class="pagination-wrap">
        {{ $students->links() }}
    </div>
    @endif
</div>

@endsection