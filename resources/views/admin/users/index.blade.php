@extends('layouts.admin')

@section('title', 'Quizora — Users')
@section('page-title', 'Users')
@section('page-subtitle', 'Manage all teachers and students')

@push('styles')
<style>
    .users-toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .users-search {
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

    .users-search input {
        background: none;
        border: none;
        outline: none;
        color: #fff;
        font-size: 13px;
        width: 100%;
        font-family: var(--font);
    }

    .users-search input::placeholder {
        color: var(--color-text-muted);
    }

    .filter-btn {
        height: 40px;
        padding: 0 16px;
        border-radius: 10px;
        border: 1px solid var(--color-border-light);
        background: var(--color-bg-card);
        color: var(--color-text-secondary);
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        font-family: var(--font);
        transition: all 0.2s;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: rgba(16, 185, 129, 0.15);
        border-color: rgba(16, 185, 129, 0.4);
        color: #34D399;
    }

    .users-table-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        overflow: hidden;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table th {
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

    .users-table td {
        padding: 14px 18px;
        font-size: 13px;
        color: var(--color-text-secondary);
        border-bottom: 1px solid var(--color-border-light);
        vertical-align: middle;
    }

    .users-table tr:last-child td {
        border-bottom: none;
    }

    .users-table tr:hover td {
        background: var(--color-bg-row-hover);
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar-sm {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: linear-gradient(135deg, #10B981, #059669);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
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

    .role-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
    }

    .role-teacher {
        background: rgba(16, 185, 129, 0.15);
        color: #34D399;
    }

    .role-student {
        background: rgba(59, 130, 246, 0.15);
        color: #60A5FA;
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
<form method="GET" action="{{ route('admin.users.index') }}" class="users-toolbar">
    <div class="users-search">
        <i class="ti ti-search" style="color:var(--color-text-muted); font-size:16px;"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email...">
    </div>

    <button type="submit" class="filter-btn {{ request('role') === null ? 'active' : '' }}" name="role" value="">
        All
    </button>
    <button type="submit" class="filter-btn {{ request('role') === 'teacher' ? 'active' : '' }}" name="role" value="teacher">
        Teachers
    </button>
    <button type="submit" class="filter-btn {{ request('role') === 'student' ? 'active' : '' }}" name="role" value="student">
        Students
    </button>
</form>

{{-- Table --}}
<div class="users-table-card">
    <table class="users-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>
                    <div class="user-cell">
                        <div class="user-avatar-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="user-name">{{ $user->name }}</div>
                            <div class="user-email">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="role-badge role-{{ $user->role }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td>
                    <span class="status-badge status-{{ $user->status ?? 'active' }}">
                        {{ ucfirst($user->status ?? 'active') }}
                    </span>
                </td>
                <td>{{ $user->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="action-btns">
                        {{-- Suspend / Activate --}}
                        <form method="POST" action="{{ route('admin.users.suspend', $user) }}" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="action-btn" title="{{ ($user->status ?? 'active') === 'suspended' ? 'Activate' : 'Suspend' }}">
                                <i class="ti ti-{{ ($user->status ?? 'active') === 'suspended' ? 'player-play' : 'player-pause' }}"></i>
                            </button>
                        </form>

                        {{-- Delete --}}
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;"
                            onsubmit="return confirm('Are you sure you want to delete this user?')">
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
                <td colspan="5">
                    <div class="empty-state">
                        <i class="ti ti-users" style="font-size:36px; display:block; margin-bottom:12px; opacity:0.4;"></i>
                        No users found.
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
    <div class="pagination-wrap">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection