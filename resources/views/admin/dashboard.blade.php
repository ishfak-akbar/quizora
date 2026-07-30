@extends('layouts.admin')

@section('title', 'Quizora — Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . auth()->user()->name . ' 👋')

@push('styles')
<style>
    .admin-metrics {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 14px;
        margin-bottom: 22px;
    }

    .metric-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 12px;
        padding: 16px 18px;
        transition: border-color 0.2s;
    }

    .metric-card:hover {
        border-color: rgba(16, 185, 129, 0.35);
    }

    .metric-label {
        font-size: 12px;
        color: var(--color-text-muted);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .metric-value {
        font-size: 24px;
        font-weight: 700;
        color: #fff;
        line-height: 1.1;
    }

    .metric-trend {
        font-size: 11px;
        color: #34D399;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .admin-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    .admin-right-column {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .admin-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        overflow: hidden;
    }

    .admin-card-header {
        padding: 14px 18px;
        border-bottom: 1px solid var(--color-border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .admin-card-header h3 {
        font-size: 13.5px;
        font-weight: 600;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .admin-card-body {
        padding: 14px 18px;
    }

    .view-all {
        font-size: 12px;
        color: #34D399;
        text-decoration: none;
        font-weight: 500;
    }

    .view-all:hover {
        text-decoration: underline;
    }

    /* Activity Feed */
    .activity-item {
        display: flex;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--color-border-light);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
        min-width: 0;
    }

    .activity-title {
        font-size: 13px;
        font-weight: 600;
        color: #fff;
    }

    .activity-desc {
        font-size: 12px;
        color: var(--color-text-muted);
        margin-top: 2px;
    }

    .activity-time {
        font-size: 11px;
        color: var(--color-text-muted);
        white-space: nowrap;
    }

    /* Health */
    .health-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 0;
        border-bottom: 1px solid var(--color-border-light);
        font-size: 13px;
    }

    .health-row:last-child {
        border-bottom: none;
    }

    .health-label {
        color: var(--color-text-secondary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .badge-online {
        background: rgba(16, 185, 129, 0.15);
        color: #34D399;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 20px;
    }

    /* Attention */
    .attention-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--color-border-light);
        font-size: 13px;
    }

    .attention-item:last-child {
        border-bottom: none;
    }

    .attention-count {
        background: rgba(245, 158, 11, 0.15);
        color: #F59E0B;
        font-size: 12px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        min-width: 28px;
        text-align: center;
    }

    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .quick-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 14px;
        background: rgba(16, 185, 129, 0.08);
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-radius: 10px;
        color: #34D399;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .quick-btn:hover {
        background: rgba(16, 185, 129, 0.16);
        border-color: rgba(16, 185, 129, 0.4);
        color: #fff;
    }

    /* Bottom tables */
    .admin-bottom {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .mini-table {
        width: 100%;
        border-collapse: collapse;
    }

    .mini-table th {
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: var(--color-text-muted);
        padding: 8px 14px;
        border-bottom: 1px solid var(--color-border-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .mini-table td {
        padding: 11px 14px;
        font-size: 13px;
        color: var(--color-text-secondary);
        border-bottom: 1px solid var(--color-border-light);
    }

    .mini-table tr:last-child td {
        border-bottom: none;
    }

    .mini-table tr:hover td {
        background: var(--color-bg-row-hover);
    }

    .role-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 6px;
    }

    .role-teacher {
        background: rgba(16, 185, 129, 0.15);
        color: #34D399;
    }

    .role-student {
        background: rgba(59, 130, 246, 0.15);
        color: #60A5FA;
    }

    .status-active {
        color: #34D399;
        font-weight: 600;
        font-size: 12px;
    }

    .status-inactive {
        color: #F87171;
        font-weight: 600;
        font-size: 12px;
    }

    @media (max-width: 1400px) {
        .admin-metrics {
            grid-template-columns: repeat(3, 1fr);
        }

        .admin-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>
@endpush

@section('content')

{{-- TOP METRICS --}}
<div class="admin-metrics">
    <div class="metric-card">
        <div class="metric-label"><i class="ti ti-school"></i> Total Teachers</div>
        <div class="metric-value">{{ $totalTeachers }}</div>
        <div class="metric-trend"><i class="ti ti-trending-up"></i> +{{ $newTeachersThisWeek }} this week</div>
    </div>

    <div class="metric-card">
        <div class="metric-label"><i class="ti ti-users"></i> Total Students</div>
        <div class="metric-value">{{ number_format($totalStudents) }}</div>
        <div class="metric-trend"><i class="ti ti-trending-up"></i> +{{ $newStudentsThisWeek }} this week</div>
    </div>

    <div class="metric-card">
        <div class="metric-label"><i class="ti ti-file-description"></i> Total Quizzes</div>
        <div class="metric-value">{{ $totalQuizzes }}</div>
        <div class="metric-trend"><i class="ti ti-circle-filled" style="font-size:8px;"></i> {{ $activeQuizzes }} active</div>
    </div>

    <div class="metric-card">
        <div class="metric-label"><i class="ti ti-player-play"></i> Active Quizzes</div>
        <div class="metric-value">{{ $activeQuizzes }}</div>
        <div class="metric-trend">Live right now</div>
    </div>

    <div class="metric-card">
        <div class="metric-label"><i class="ti ti-clipboard-check"></i> Total Submissions</div>
        <div class="metric-value">{{ number_format($totalSubmissions) }}</div>
        <div class="metric-trend"><i class="ti ti-trending-up"></i> {{ $submissionsThisWeek }} this week</div>
    </div>

    <div class="metric-card">
        <div class="metric-label"><i class="ti ti-user-plus"></i> New Users</div>
        <div class="metric-value">{{ $newUsersThisWeek }}</div>
        <div class="metric-trend">This week</div>
    </div>
</div>

{{-- MAIN GRID --}}
<div class="admin-grid">

    {{-- LEFT: Activity Feed --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="ti ti-activity" style="color:#34D399;"></i> Activity Feed</h3>
            <a href="#" class="view-all">View all</a>
        </div>
        <div class="admin-card-body">
            <div class="activity-item">
                <div class="activity-icon" style="background:rgba(16,185,129,0.15);color:#34D399;">
                    <i class="ti ti-school"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">New teacher registered</div>
                    <div class="activity-desc">A new teacher joined the platform</div>
                </div>
                <div class="activity-time">2m ago</div>
            </div>
            <div class="activity-item">
                <div class="activity-icon" style="background:rgba(59,130,246,0.15);color:#60A5FA;">
                    <i class="ti ti-user"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">New student registered</div>
                    <div class="activity-desc">A new student joined the platform</div>
                </div>
                <div class="activity-time">5m ago</div>
            </div>
            <div class="activity-item">
                <div class="activity-icon" style="background:rgba(16,185,129,0.15);color:#34D399;">
                    <i class="ti ti-file-description"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Quiz published</div>
                    <div class="activity-desc">A new quiz was published</div>
                </div>
                <div class="activity-time">12m ago</div>
            </div>
            <div class="activity-item">
                <div class="activity-icon" style="background:rgba(245,158,11,0.15);color:#F59E0B;">
                    <i class="ti ti-alert-triangle"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">High submission volume</div>
                    <div class="activity-desc">Unusual activity detected on a quiz</div>
                </div>
                <div class="activity-time">1h ago</div>
            </div>
            <div class="activity-item">
                <div class="activity-icon" style="background:rgba(248,113,113,0.15);color:#F87171;">
                    <i class="ti ti-user-off"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">User suspended</div>
                    <div class="activity-desc">An account was suspended</div>
                </div>
                <div class="activity-time">2h ago</div>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN --}}
    <div class="admin-right-column">

        {{-- Platform Health --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="ti ti-heart-rate-monitor" style="color:#34D399;"></i> Platform Health</h3>
            </div>
            <div class="admin-card-body">
                <div class="health-row">
                    <div class="health-label"><i class="ti ti-robot"></i> AI Service Status</div>
                    <span class="badge-online">Online</span>
                </div>
                <div class="health-row">
                    <div class="health-label"><i class="ti ti-clipboard-check"></i> Submissions Today</div>
                    <strong style="color:#fff;">{{ $submissionsToday }}</strong>
                </div>
                <div class="health-row">
                    <div class="health-label"><i class="ti ti-users"></i> Total Users</div>
                    <strong style="color:#fff;">{{ $totalTeachers + $totalStudents }}</strong>
                </div>
                <div class="health-row">
                    <div class="health-label"><i class="ti ti-server"></i> System Status</div>
                    <span class="badge-online">Operational</span>
                </div>
            </div>
        </div>

        {{-- Attention Needed --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="ti ti-alert-circle" style="color:#F59E0B;"></i> Attention Needed</h3>
            </div>
            <div class="admin-card-body">
                <div class="attention-item">
                    <span style="color:var(--color-text-secondary);">Teachers with 0 quizzes</span>
                    <span class="attention-count">{{ $teachersWithNoQuizzes }}</span>
                </div>
                <div class="attention-item">
                    <span style="color:var(--color-text-secondary);">Recently suspended</span>
                    <span class="attention-count">0</span>
                </div>
                <div class="attention-item">
                    <span style="color:var(--color-text-secondary);">Quizzes with unusual activity</span>
                    <span class="attention-count">0</span>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="ti ti-bolt" style="color:#34D399;"></i> Quick Actions</h3>
            </div>
            <div class="admin-card-body">
                <div class="quick-actions">
                    <a href="#" class="quick-btn"><i class="ti ti-users"></i> Manage Users</a>
                    <a href="#" class="quick-btn"><i class="ti ti-file-description"></i> Manage Quizzes</a>
                    <a href="#" class="quick-btn"><i class="ti ti-speakerphone"></i> Announcement</a>
                    <a href="#" class="quick-btn"><i class="ti ti-chart-bar"></i> View Reports</a>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- BOTTOM TABLES --}}
<div class="admin-bottom">

    {{-- Recent Users --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="ti ti-users" style="color:#34D399;"></i> Recent Users</h3>
            <a href="#" class="view-all">View all</a>
        </div>
        <table class="mini-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentUsers as $user)
                <tr>
                    <td style="color:#fff; font-weight:500;">{{ $user->name }}</td>
                    <td>
                        <span class="role-badge role-{{ $user->role }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align:center; color:var(--color-text-muted);">No users yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Recent Quizzes --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="ti ti-file-description" style="color:#34D399;"></i> Recent Quizzes</h3>
            <a href="#" class="view-all">View all</a>
        </div>
        <table class="mini-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Teacher</th>
                    <th>Status</th>
                    <th>Subs</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentQuizzes as $quiz)
                <tr>
                    <td style="color:#fff; font-weight:500;">{{ Str::limit($quiz->title, 22) }}</td>
                    <td>{{ $quiz->teacher->name ?? '—' }}</td>
                    <td>
                        <span class="status-{{ $quiz->status === 'active' ? 'active' : 'inactive' }}">
                            {{ ucfirst($quiz->display_status) }}
                        </span>
                    </td>
                    <td>{{ $quiz->submitted_count }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:var(--color-text-muted);">No quizzes yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection