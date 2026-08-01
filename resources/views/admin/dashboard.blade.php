@extends('layouts.admin')

@section('title', 'Quizora — Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . auth()->user()->name . ' 👋')

@push('styles')
<style>
    .admin-metrics {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }

    .metric-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 12px;
        padding: 16px 18px;
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
        grid-template-columns: minmax(0, 1.6fr) minmax(0, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }

    .admin-right-column {
        display: flex;
        flex-direction: column;
        gap: 16px;
        min-width: 0;
    }

    .admin-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        overflow: hidden;
        min-width: 0;
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

    .charts-row {
        display: grid;
        grid-template-columns: minmax(0, 1.6fr) minmax(0, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }

    .chart-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        padding: 18px;
        min-height: 300px;
        min-width: 0;
    }

    .chart-card h3 {
        font-size: 13.5px;
        font-weight: 600;
        color: #fff;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-wrap {
        position: relative;
        height: 230px;
        width: 100%;
    }

    .admin-bottom {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
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

    @media (max-width: 1100px) {

        .admin-grid,
        .charts-row,
        .admin-bottom {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .admin-metrics {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .admin-metrics {
            grid-template-columns: 1fr;
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
{{-- CHARTS --}}
<div class="charts-row">
    <div class="chart-card">
        <h3><i class="ti ti-chart-area-line" style="color:#34D399;"></i> Growth Overview (7 Days)</h3>
        <div class="chart-wrap">
            <canvas id="growthChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <h3><i class="ti ti-chart-pie" style="color:#34D399;"></i> Quiz Categories</h3>
        <div class="chart-wrap">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

{{-- MAIN GRID --}}
<div class="admin-grid">

    {{-- LEFT: Activity Feed --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="ti ti-activity" style="color:#34D399;"></i> Activity Feed</h3>
        </div>
        <div class="admin-card-body">
            @forelse($activityFeed as $item)
            <div class="activity-item">
                <div class="activity-icon" style="background:{{ $item['color'] }}22; color:{{ $item['color'] }};">
                    <i class="ti {{ $item['icon'] }}"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">{{ $item['title'] }}</div>
                    <div class="activity-desc">{{ $item['desc'] }}</div>
                </div>
                <div class="activity-time">{{ $item['time']->diffForHumans() }}</div>
            </div>
            @empty
            <div style="text-align:center; padding:24px; color:var(--color-text-muted); font-size:13px;">
                No recent activity yet.
            </div>
            @endforelse
        </div>
    </div>

    {{-- RIGHT COLUMN --}}
    <div class="admin-right-column">
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

        <div class="admin-card">
            <div class="admin-card-header">
                <h3><i class="ti ti-bolt" style="color:#34D399;"></i> Quick Actions</h3>
            </div>
            <div class="admin-card-body">
                <div class="quick-actions">
                    <a href="{{ route('admin.users.index') }}" class="quick-btn"><i class="ti ti-users"></i> Manage Users</a>
                    <a href="{{ route('admin.quizzes.index') }}" class="quick-btn"><i class="ti ti-file-description"></i> Manage Quizzes</a>
                    <a href="{{ route('admin.teachers.index') }}" class="quick-btn"><i class="ti ti-school"></i> Teachers</a>
                    <a href="{{ route('admin.students.index') }}" class="quick-btn"><i class="ti ti-user"></i> Students</a>
                </div>
            </div>
        </div>
    </div>
</div>



{{-- BOTTOM TABLES --}}
<div class="admin-bottom">

    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="ti ti-users" style="color:#34D399;"></i> Recent Users</h3>
            <a href="{{ route('admin.users.index') }}" class="view-all">View all</a>
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

    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="ti ti-file-description" style="color:#34D399;"></i> Recent Quizzes</h3>
            <a href="{{ route('admin.quizzes.index') }}" class="view-all">View all</a>
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
                        <span class="status-{{ $quiz->display_status === 'active' ? 'active' : 'inactive' }}">
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js failed to load');
        return;
    }

    const days        = {!! json_encode($days ?? []) !!};
    const usersData   = {!! json_encode($usersData ?? []) !!};
    const quizzesData = {!! json_encode($quizzesData ?? []) !!};
    const catLabels   = {!! json_encode($categoryLabels ?? []) !!};
    const catValues   = {!! json_encode($categoryValues ?? []) !!};

    // Growth Chart
    const growthCanvas = document.getElementById('growthChart');
    if (growthCanvas) {
        new Chart(growthCanvas, {
            type: 'line',
            data: {
                labels: days,
                datasets: [
                    {
                        label: 'New Users',
                        data: usersData,
                        borderColor: '#34D399',
                        backgroundColor: 'rgba(52, 211, 153, 0.12)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#34D399',
                        borderWidth: 2
                    },
                    {
                        label: 'New Quizzes',
                        data: quizzesData,
                        borderColor: '#60A5FA',
                        backgroundColor: 'rgba(96, 165, 250, 0.12)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#60A5FA',
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#94A3B8', font: { size: 12 }, boxWidth: 12 }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#64748B' },
                        grid: { color: 'rgba(255,255,255,0.04)' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#64748B', stepSize: 1, precision: 0 },
                        grid: { color: 'rgba(255,255,255,0.04)' }
                    }
                }
            }
        });
    }

    // Category Chart
    const categoryCanvas = document.getElementById('categoryChart');
    if (categoryCanvas) {
        new Chart(categoryCanvas, {
            type: 'doughnut',
            data: {
                labels: catLabels.length ? catLabels : ['No Data'],
                datasets: [{
                    data: catValues.length ? catValues : [1],
                    backgroundColor: [
                        '#10B981', '#3B82F6', '#F59E0B', '#F87171',
                        '#A78BFA', '#2DD4BF', '#FB923C', '#94A3B8'
                    ],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#94A3B8',
                            font: { size: 12 },
                            boxWidth: 12,
                            padding: 14
                        }
                    }
                },
                cutout: '62%'
            }
        });
    }
});
</script>
@endpush