@extends('layouts.teacher')
@section('title', 'Quizora — Students')

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

    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        padding: 18px 20px;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
    }

    .stat-card.purple::before {
        background: linear-gradient(90deg, #4F46E5, #A78BFA);
    }

    .stat-card.cyan::before {
        background: linear-gradient(90deg, #0891B2, #22D3EE);
    }

    .stat-card.green::before {
        background: linear-gradient(90deg, #059669, #34D399);
    }

    .stat-card.amber::before {
        background: linear-gradient(90deg, #D97706, #F59E0B);
    }

    .stat-value {
        font-size: 26px;
        font-weight: 700;
        color: #fff;
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        color: var(--color-text-muted);
        font-weight: 500;
    }

    .filters {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .search-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.05);
        border: 1.5px solid var(--color-border-light);
        border-radius: 10px;
        padding: 0 14px;
        flex: 1;
        max-width: 400px;
    }

    .search-wrap:focus-within {
        border-color: rgba(79, 70, 229, 0.6);
    }

    .search-wrap i {
        color: var(--color-text-muted);
        font-size: 16px;
    }

    .search-wrap input {
        flex: 1;
        height: 38px;
        background: none;
        border: none;
        outline: none;
        color: #fff;
        font-size: 13px;
        font-family: var(--font);
    }

    .search-wrap input::placeholder {
        color: var(--color-text-muted);
    }

    .filter-btn {
        height: 38px;
        padding: 0 16px;
        border-radius: 10px;
        border: 1.5px solid var(--color-border-light);
        background: transparent;
        color: var(--color-text-secondary);
        font-size: 13px;
        font-weight: 500;
        font-family: var(--font);
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: rgba(79, 70, 229, 0.15);
        border-color: rgba(79, 70, 229, 0.4);
        color: #fff;
    }

    .table-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        overflow: hidden;
    }

    .table-card table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-card th {
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: var(--color-text-muted);
        letter-spacing: 0.8px;
        text-transform: uppercase;
        border-bottom: 1px solid var(--color-border-light);
    }

    .table-card td {
        padding: 14px 16px;
        font-size: 13px;
        color: var(--color-text-secondary);
        border-bottom: 1px solid var(--color-border-light);
        vertical-align: middle;
    }

    .table-card tr:last-child td {
        border-bottom: none;
    }

    .table-card tr:hover td {
        background: var(--color-bg-row-hover);
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .student-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
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

    .score-pill {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
    }

    .score-high {
        background: rgba(52, 211, 153, 0.15);
        color: #34D399;
    }

    .score-mid {
        background: rgba(245, 158, 11, 0.15);
        color: #F59E0B;
    }

    .score-low {
        background: rgba(248, 113, 113, 0.15);
        color: #F87171;
    }

    .activity-dot {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
    }

    .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
    }

    .dot-active {
        background: #34D399;
    }

    .dot-recent {
        background: #F59E0B;
    }

    .dot-inactive {
        background: #6B7280;
    }

    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s;
    }

    .modal-overlay.open {
        opacity: 1;
        pointer-events: all;
    }

    .modal {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 20px;
        padding: 28px;
        width: 440px;
        max-width: 95vw;
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .modal-title {
        font-size: 16px;
        font-weight: 700;
        color: #fff;
    }

    .modal-close {
        background: transparent;
        border: none;
        color: var(--color-text-muted);
        font-size: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .modal-close:hover {
        color: #fff;
    }

    .modal-student-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--color-border-light);
    }

    .modal-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .modal-student-name {
        font-size: 16px;
        font-weight: 700;
        color: #fff;
    }

    .modal-student-email {
        font-size: 12px;
        color: var(--color-text-muted);
        margin-top: 3px;
    }

    .modal-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .modal-stat {
        background: var(--color-bg-main);
        border: 1px solid var(--color-border-light);
        border-radius: 12px;
        padding: 14px;
        text-align: center;
    }

    .modal-stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #fff;
    }

    .modal-stat-label {
        font-size: 11px;
        color: var(--color-text-muted);
        margin-top: 3px;
    }

    .modal-section-title {
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-muted);
        letter-spacing: 0.8px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .attempt-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--color-border-light);
    }

    .attempt-row:last-child {
        border-bottom: none;
    }

    .attempt-quiz {
        font-size: 13px;
        font-weight: 500;
        color: #fff;
    }

    .attempt-date {
        font-size: 11px;
        color: var(--color-text-muted);
        margin-top: 2px;
    }

    .empty-state {
        text-align: center;
        padding: 48px;
        color: var(--color-text-muted);
    }

    .empty-state i {
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
        <h1>Students</h1>
        <p>All students who have attempted your quizzes</p>
    </div>
</div>

<div class="stats-row">
    <div class="stat-card purple">
        <div class="stat-value">{{ $totalStudentsCount }}</div>
        <div class="stat-label">Total Students</div>
    </div>
    <div class="stat-card cyan">
        <div class="stat-value">{{ $activeThisMonth }}</div>
        <div class="stat-label">Active This Month</div>
    </div>
    <div class="stat-card green">
        <div class="stat-value">{{ $avgScore }}%</div>
        <div class="stat-label">Avg. Score</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-value">{{ $totalAttemptsCount }}</div>
        <div class="stat-label">Total Attempts</div>
    </div>
</div>

<div class="filters">
    <div class="search-wrap">
        <i class="ti ti-search"></i>
        <input type="text" id="searchInput" placeholder="Search by name or email...">
    </div>
    <button class="filter-btn active" onclick="filterStudents('all', this)">All</button>
    <button class="filter-btn" onclick="filterStudents('active', this)">Active</button>
    <button class="filter-btn" onclick="filterStudents('recent', this)">Recent</button>
    <button class="filter-btn" onclick="filterStudents('inactive', this)">Inactive</button>
</div>

@if($students->isEmpty())
<div class="empty-state">
    <i class="ti ti-users-off"></i>
    <p>No students have attempted your quizzes yet.</p>
</div>
@else
<div class="table-card">
    <table id="studentsTable">
        <thead>
            <tr>
                <th>Student</th>
                <th>Quizzes Taken</th>
                <th>Avg Score</th>
                <th>Last Active</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="studentsBody">
            @foreach($students as $student)
            @php
            $colors = ['#4F46E5','#7C3AED','#0891B2','#059669','#D97706','#DB2777'];
            $bg = $colors[$loop->index % count($colors)];
            $scoreClass = $student['avg_score'] >= 75 ? 'score-high' : ($student['avg_score'] >= 50 ? 'score-mid' : 'score-low');
            @endphp
            <tr data-status="{{ $student['status'] }}" data-name="{{ strtolower($student['name']) }}">
                <td>
                    <div class="student-info">
                        <div class="student-avatar" style="background:{{ $bg }}">{{ $student['initial'] }}</div>
                        <div>
                            <div class="student-name">{{ $student['name'] }}</div>
                            <div class="student-email">{{ $student['email'] }}</div>
                        </div>
                    </div>
                </td>
                <td style="color:#fff;font-weight:600;">{{ $student['quizzes_taken'] }}</td>
                <td><span class="score-pill {{ $scoreClass }}">{{ $student['avg_score'] }}%</span></td>
                <td style="color:var(--color-text-muted);font-size:12px;">{{ $student['last_active'] }}</td>
                <td>
                    <span class="activity-dot">
                        <span class="dot dot-{{ $student['status'] }}"></span>
                        {{ ucfirst($student['status']) }}
                    </span>
                </td>
                <td>
                    <button class="action-btn" title="View Details"
                        onclick="openModal('{{ $student['name'] }}','{{ $student['email'] }}','{{ $student['initial'] }}','{{ $bg }}','{{ $student['quizzes_taken'] }}','{{ $student['avg_score'] }}%','{{ $student['quizzes_passed'] }}')">
                        <i class="ti ti-eye"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection

{{-- MODAL outside section so it renders at body level --}}
@push('scripts')
<div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Student Details</div>
            <button class="modal-close" onclick="closeModal()"><i class="ti ti-x"></i></button>
        </div>
        <div class="modal-student-header">
            <div class="modal-avatar" id="modalAvatar">N</div>
            <div>
                <div class="modal-student-name" id="modalName">—</div>
                <div class="modal-student-email" id="modalEmail">—</div>
            </div>
        </div>
        <div class="modal-stats">
            <div class="modal-stat">
                <div class="modal-stat-value" id="modalQuizzes">—</div>
                <div class="modal-stat-label">Quizzes Taken</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-value" id="modalAvgScore" style="color:#34D399;">—</div>
                <div class="modal-stat-label">Avg Score</div>
            </div>
            <div class="modal-stat">
                <div class="modal-stat-value" id="modalPassed" style="color:#818CF8;">—</div>
                <div class="modal-stat-label">Quizzes Passed</div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('#studentsBody tr').forEach(row => {
            row.style.display = row.dataset.name.includes(val) ? '' : 'none';
        });
    });

    function filterStudents(status, btn) {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('#studentsBody tr').forEach(row => {
            row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
        });
    }

    function openModal(name, email, initial, bg, quizzes, avg, passed) {
        document.getElementById('modalName').textContent = name;
        document.getElementById('modalEmail').textContent = email;
        document.getElementById('modalAvatar').textContent = initial;
        document.getElementById('modalAvatar').style.background = bg;
        document.getElementById('modalQuizzes').textContent = quizzes;
        document.getElementById('modalAvgScore').textContent = avg;
        document.getElementById('modalPassed').textContent = passed;
        document.getElementById('modalOverlay').classList.add('open');
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('open');
    }
    document.getElementById('modalOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endpush