@extends('layouts.admin')

@section('title', 'Quizora — Teachers')
@section('page-title', 'Teachers')
@section('page-subtitle', 'Manage all teachers on the platform')

@push('styles')
<style>
    .teachers-toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .teachers-search {
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

    .teachers-search input {
        background: none;
        border: none;
        outline: none;
        color: #fff;
        font-size: 13px;
        width: 100%;
        font-family: var(--font);
    }

    .teachers-search input::placeholder {
        color: var(--color-text-muted);
    }

    .teachers-table-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        overflow: hidden;
    }

    .teachers-table {
        width: 100%;
        border-collapse: collapse;
    }

    .teachers-table th {
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

    .teachers-table td {
        padding: 14px 18px;
        font-size: 13px;
        color: var(--color-text-secondary);
        border-bottom: 1px solid var(--color-border-light);
        vertical-align: middle;
    }

    .teachers-table tr:last-child td {
        border-bottom: none;
    }

    .teachers-table tr:hover td {
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
        background: linear-gradient(135deg, #10B981, #059669);
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

{{-- Toolbar --}}
<form method="GET" action="{{ route('admin.teachers.index') }}" class="teachers-toolbar">
    <div class="teachers-search">
        <i class="ti ti-search" style="color:var(--color-text-muted); font-size:16px;"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search teachers...">
    </div>
</form>

{{-- Table --}}
<div class="teachers-table-card">
    <table class="teachers-table">
        <thead>
            <tr>
                <th>Teacher</th>
                <th>Quizzes</th>
                <th>Submissions</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teachers as $teacher)
            <tr>
                <td>
                    <div class="user-cell">
                        <div class="user-avatar" style="overflow:hidden;">
                            @if(auth()->user()->hasAvatar())
                            <img src="{{ auth()->user()->avatarUrl() }}" alt=""
                                style="width:100%;height:100%;object-fit:cover;display:block;">
                            @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="user-name">{{ $teacher->name }}</div>
                            <div class="user-email">{{ $teacher->email }}</div>
                        </div>
                    </div>
                </td>
                <td><span class="stat-num">{{ $teacher->quizzes_count }}</span></td>
                <td><span class="stat-num">{{ $teacher->total_submissions }}</span></td>
                <td>
                    <span class="status-badge status-{{ $teacher->status ?? 'active' }}">
                        {{ ucfirst($teacher->status ?? 'active') }}
                    </span>
                </td>
                <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('admin.teachers.show', $teacher) }}" class="action-btn" title="View">
                            <i class="ti ti-eye"></i>
                        </a>
                        {{-- Suspend / Activate --}}
                        <form method="POST" action="{{ route('admin.users.suspend', $teacher) }}" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="action-btn" title="{{ ($teacher->status ?? 'active') === 'suspended' ? 'Activate' : 'Suspend' }}">
                                <i class="ti ti-{{ ($teacher->status ?? 'active') === 'suspended' ? 'player-play' : 'player-pause' }}"></i>
                            </button>
                        </form>

                        {{-- Delete --}}
                        <form method="POST" action="{{ route('admin.users.destroy', $teacher) }}" style="display:inline;"
                            onsubmit="return confirm('Are you sure you want to delete this teacher?')">
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
                        <i class="ti ti-school" style="font-size:36px; display:block; margin-bottom:12px; opacity:0.4;"></i>
                        No teachers found.
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($teachers->hasPages())
    <div class="pagination-wrap">
        {{ $teachers->links() }}
    </div>
    @endif
</div>

@endsection