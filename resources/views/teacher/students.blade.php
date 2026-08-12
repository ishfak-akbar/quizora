@extends('layouts.teacher')
@section('title', 'Quizora — Students')
@section('page-title', 'Students')
@section('page-subtitle', 'View and monitor the students taking your quizzes.')

@section('content')


<div class="stats-row">
    <div class="stat-card-mini">
        <div class="stat-value">{{ $totalStudentsCount }}</div>
        <div class="stat-label">Total Students</div>
    </div>
    <div class="stat-card-mini">
        <div class="stat-value" style="color:var(--color-primary-glow)">{{ $activeThisMonth }}</div>
        <div class="stat-label">Active This Month</div>
    </div>
    <div class="stat-card-mini">
        <div class="stat-value" style="color:#34D399">{{ $avgScore }}%</div>
        <div class="stat-label">Avg. Score</div>
    </div>
    <div class="stat-card-mini">
        <div class="stat-value" style="color:#F59E0B">{{ $totalAttemptsCount }}</div>
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
                        data-recent="{{ json_encode($student['recent_quizzes']) }}"
                        onclick="openModal(this,'{{ $student['name'] }}','{{ $student['email'] }}','{{ $student['initial'] }}','{{ $bg }}','{{ $student['quizzes_taken'] }}','{{ $student['avg_score'] }}%','{{ $student['quizzes_passed'] }}')">
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

        <div class="modal-recent-wrap">
            <div class="modal-section-label">Last 5 Quizzes</div>
            <div id="modalRecentList"></div>
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

    function openModal(btn, name, email, initial, bg, quizzes, avg, passed) {
        document.getElementById('modalName').textContent = name;
        document.getElementById('modalEmail').textContent = email;
        document.getElementById('modalAvatar').textContent = initial;
        document.getElementById('modalAvatar').style.background = bg;
        document.getElementById('modalQuizzes').textContent = quizzes;
        document.getElementById('modalAvgScore').textContent = avg;
        document.getElementById('modalPassed').textContent = passed;

        const recentQuizzes = JSON.parse(btn.dataset.recent || '[]');
        const list = document.getElementById('modalRecentList');

        if (recentQuizzes.length === 0) {
            list.innerHTML = '<div class="modal-quiz-empty">No quiz attempts yet.</div>';
        } else {
            list.innerHTML = recentQuizzes.map(q => {
                const scoreClass = q.score >= 75 ? 'score-high' : (q.score >= 50 ? 'score-mid' : 'score-low');
                return `
                    <div class="modal-quiz-row">
                        <div class="modal-quiz-info">
                            <div class="modal-quiz-title">${q.title}</div>
                            <div class="modal-quiz-category">${q.category}</div>
                        </div>
                        <div class="modal-quiz-teacher"><i class="ti ti-user"></i> ${q.teacher}</div>
                        <span class="score-pill ${scoreClass}">${q.score}%</span>
                    </div>`;
            }).join('');
        }

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