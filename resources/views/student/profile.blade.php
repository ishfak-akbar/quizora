<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizora — My Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('quizora.css') }}">
    <style>
        body {
            background: var(--color-bg-main);
            min-height: 100vh;
            font-family: var(--font);
            color: var(--color-text-primary);
            overflow-x: hidden;
        }

        .profile-page {
            max-width: 1000px;
            margin: 0 auto;
            padding: 36px 24px 70px;
        }

        .profile-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--color-text-secondary);
            text-decoration: none;
            margin-bottom: 24px;
            padding: 9px 16px;
            border-radius: 10px;
            border: 1px solid var(--color-border-light);
            background: rgba(255, 255, 255, .03);
            transition: all 0.2s;
        }

        .profile-back:hover {
            color: #fff;
            background: var(--color-bg-row-hover);
            border-color: rgba(79, 70, 229, 0.4);
        }

        .card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 12px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, .20);
            margin-bottom: 16px;
        }

        /* ===== IDENTITY HEADER ===== */
        .identity-card {
            display: flex;
            align-items: center;
            gap: 22px;
            padding: 28px 30px;
            background:
                radial-gradient(circle at top right, rgba(129, 140, 248, .14), transparent 45%),
                linear-gradient(180deg, #1f1a45 0%, #161233 100%);
            border: 1px solid rgba(129, 140, 248, .18);
        }

        .identity-avatar {
            width: 88px;
            height: 88px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
            border: 4px solid rgba(255, 255, 255, .12);
            box-shadow: 0 0 0 8px rgba(129, 140, 248, .08);
        }

        .identity-info {
            flex: 1;
            min-width: 0;
        }

        .identity-name {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 3px;
        }

        .identity-email {
            font-size: 13px;
            color: var(--color-text-muted);
            margin-bottom: 10px;
        }

        .identity-role-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(34, 211, 238, 0.12);
            border: 1px solid rgba(34, 211, 238, 0.25);
            color: var(--color-stat-cyan);
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .edit-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #4F46E5, #818CF8);
            border: none;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            padding: 11px 22px;
            border-radius: 12px;
            text-decoration: none;
            box-shadow: 0 12px 30px rgba(79, 70, 229, .35);
            transition: transform 0.2s;
            flex-shrink: 0;
        }

        .edit-btn:hover {
            transform: translateY(-1px);
        }

        /* ===== BIO ===== */
        .bio-card {
            padding: 22px 26px;
        }

        .section-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .bio-text {
            font-size: 14px;
            color: var(--color-text-secondary);
            line-height: 1.7;
        }

        .bio-text.empty {
            color: var(--color-text-muted);
            font-style: italic;
        }

        /* ===== INFO GRID ===== */
        .info-card {
            padding: 26px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 28px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .info-item i {
            font-size: 17px;
            color: var(--color-primary-glow);
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .info-item-text {
            min-width: 0;
        }

        .info-item-label {
            font-size: 10.5px;
            font-weight: 700;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .info-item-value {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
        }

        .info-item-value.empty {
            color: var(--color-text-muted);
            font-weight: 400;
            font-style: italic;
        }

        /* ===== HEATMAP ===== */
        .heatmap-card {
            padding: 24px;
            background:
                radial-gradient(circle at top right, rgba(129, 140, 248, .10), transparent 40%),
                var(--color-bg-card);
        }

        .heatmap-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 10px;
        }

        .heatmap-title {
            font-size: 14px;
            color: var(--color-text-secondary);
        }

        .heatmap-title strong {
            color: #fff;
            font-weight: 700;
            font-size: 17px;
        }

        .heatmap-meta {
            display: flex;
            align-items: center;
            gap: 18px;
            font-size: 13px;
            color: var(--color-text-muted);
        }

        .heatmap-meta strong {
            color: #fff;
        }

        .heatmap-scroll {
            overflow-x: auto;
            max-width: 100%;
            padding-bottom: 8px;
        }

        .heatmap-wrap {
            display: flex;
            gap: 4px;
            width: max-content;
        }

        .heatmap-week {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .heatmap-day {
            width: 16px;
            height: 16px;
            border-radius: 3.5px;
            background: rgba(255, 255, 255, 0.05);
            transition: transform 0.15s;
        }

        .heatmap-day:hover {
            transform: scale(1.25);
        }

        .heatmap-day.is-today {
            box-shadow: 0 0 0 1.5px #fff;
        }

        .heatmap-day[data-level="1"] {
            background: #312984;
        }

        .heatmap-day[data-level="2"] {
            background: #4F46E5;
            box-shadow: 0 0 6px rgba(79, 70, 229, .5);
        }

        .heatmap-day[data-level="3"] {
            background: #6366F1;
            box-shadow: 0 0 9px rgba(99, 102, 241, .7);
        }

        .heatmap-day[data-level="4"] {
            background: #818CF8;
            box-shadow: 0 0 12px rgba(129, 140, 248, .8);
        }

        .heatmap-months {
            display: flex;
            font-size: 10.5px;
            color: var(--color-text-muted);
            margin-top: 8px;
            width: max-content;
        }

        @media (max-width: 700px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .identity-card {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>

<body>

    @php
    $backUrl = url()->previous();
    if ($backUrl === url()->current() || !str_contains($backUrl, url('/student'))) {
    $backUrl = route('student.dashboard');
    }

    $educationLabels = [
    'ssc' => 'SSC', 'hsc' => 'HSC', 'bachelor' => "Bachelor's", 'master' => "Master's", 'other' => 'Other',
    ];
    $studyGoalLabels = [
    'exam_prep' => 'Exam Preparation', 'self_learning' => 'Self Learning', 'bcs' => 'BCS Preparation',
    'university_admission' => 'University Admission', 'other' => 'Other',
    ];
    $genderLabels = [
    'male' => 'Male', 'female' => 'Female', 'other' => 'Other', 'prefer_not_to_say' => 'Prefer not to say',
    ];
    @endphp

    <div class="profile-page">

        <a href="javascript:history.back()" class="profile-back">
            <i class="ti ti-arrow-left"></i> Back
        </a>

        {{-- IDENTITY HEADER --}}
        <div class="identity-card card">
            <div class="identity-avatar" style="background: {{ $student->avatar_color ?? 'var(--color-primary-solid)' }}">
                {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
            <div class="identity-info">
                <div class="identity-name">{{ $student->name }}</div>
                <div class="identity-email">{{ $student->email }}</div>
                <span class="identity-role-pill"><i class="ti ti-school"></i> Student</span>
            </div>
            <a href="{{ route('student.settings') }}" class="edit-btn">
                <i class="ti ti-pencil"></i> Edit Profile
            </a>
        </div>

        {{-- BIO --}}
        <div class="bio-card card">
            <div class="section-label"><i class="ti ti-quote"></i> Bio</div>
            <p class="bio-text {{ $student->bio ? '' : 'empty' }}">
                {{ $student->bio ?? 'No bio added yet.' }}
            </p>
        </div>

        {{-- PERSONAL INFO --}}
        <div class="info-card card">
            <div class="section-label"><i class="ti ti-user"></i> Personal Information</div>
            <div class="info-grid">
                <div class="info-item">
                    <i class="ti ti-phone"></i>
                    <div class="info-item-text">
                        <div class="info-item-label">Phone</div>
                        <div class="info-item-value {{ $student->phone ? '' : 'empty' }}">{{ $student->phone ?? 'Not set' }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <i class="ti ti-cake"></i>
                    <div class="info-item-text">
                        <div class="info-item-label">Date of Birth</div>
                        <div class="info-item-value {{ $student->date_of_birth ? '' : 'empty' }}">
                            {{ $student->date_of_birth?->format('M d, Y') ?? 'Not set' }}
                        </div>
                    </div>
                </div>
                <div class="info-item">
                    <i class="ti ti-gender-bigender"></i>
                    <div class="info-item-text">
                        <div class="info-item-label">Gender</div>
                        <div class="info-item-value {{ $student->gender ? '' : 'empty' }}">
                            {{ $genderLabels[$student->gender] ?? 'Not set' }}
                        </div>
                    </div>
                </div>
                <div class="info-item">
                    <i class="ti ti-map-pin"></i>
                    <div class="info-item-text">
                        <div class="info-item-label">Location</div>
                        <div class="info-item-value {{ $student->location ? '' : 'empty' }}">{{ $student->location ?? 'Not set' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ACADEMIC INFO --}}
        <div class="info-card card">
            <div class="section-label"><i class="ti ti-school"></i> Academic Information</div>
            <div class="info-grid">
                <div class="info-item" style="grid-column: 1 / -1;">
                    <i class="ti ti-building-bank"></i>
                    <div class="info-item-text">
                        <div class="info-item-label">Institution</div>
                        <div class="info-item-value {{ $student->institution ? '' : 'empty' }}">{{ $student->institution ?? 'Not set' }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <i class="ti ti-stairs-up"></i>
                    <div class="info-item-text">
                        <div class="info-item-label">Class / Year</div>
                        <div class="info-item-value {{ $student->class_level ? '' : 'empty' }}">{{ $student->class_level ?? 'Not set' }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <i class="ti ti-certificate"></i>
                    <div class="info-item-text">
                        <div class="info-item-label">Education Level</div>
                        <div class="info-item-value {{ $student->education_level ? '' : 'empty' }}">
                            {{ $educationLabels[$student->education_level] ?? 'Not set' }}
                        </div>
                    </div>
                </div>
                <div class="info-item">
                    <i class="ti ti-flag"></i>
                    <div class="info-item-text">
                        <div class="info-item-label">Study Goal</div>
                        <div class="info-item-value {{ $student->study_goal ? '' : 'empty' }}">
                            {{ $studyGoalLabels[$student->study_goal] ?? 'Not set' }}
                        </div>
                    </div>
                </div>
                <div class="info-item">
                    <i class="ti ti-target-arrow"></i>
                    <div class="info-item-text">
                        <div class="info-item-label">Preparing For</div>
                        <div class="info-item-value {{ $student->preparing_for ? '' : 'empty' }}">{{ $student->preparing_for ?? 'Not set' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PREFERENCES --}}
        <div class="info-card card">
            <div class="section-label"><i class="ti ti-adjustments"></i> Preferences</div>
            <div class="info-grid">
                <div class="info-item">
                    <i class="ti ti-language"></i>
                    <div class="info-item-text">
                        <div class="info-item-label">Preferred Language</div>
                        <div class="info-item-value">{{ $student->preferred_language === 'bangla' ? 'বাংলা' : 'English' }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <i class="ti ti-trophy"></i>
                    <div class="info-item-text">
                        <div class="info-item-label">Target Score</div>
                        <div class="info-item-value {{ $student->target_score ? '' : 'empty' }}">
                            {{ $student->target_score ? $student->target_score . '%' : 'Not set' }}
                        </div>
                    </div>
                </div>
                <div class="info-item">
                    <i class="ti ti-calendar"></i>
                    <div class="info-item-text">
                        <div class="info-item-label">Member Since</div>
                        <div class="info-item-value">{{ $memberSince->format('M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- HEATMAP --}}
        <div class="heatmap-card card">
            <div class="heatmap-card-header">
                <div class="heatmap-title">
                    <strong>{{ $totalAttempts }}</strong> quiz attempts logged
                </div>
                <div class="heatmap-meta">
                    <span>Active days: <strong>{{ $totalActiveDays }}</strong></span>
                    <span>Max streak: <strong>{{ $maxStreak }}</strong></span>
                    <span><i class="ti ti-flame" style="color:#F59E0B;"></i> Current: <strong>{{ $currentStreak }}</strong></span>
                </div>
            </div>
            <div class="heatmap-scroll">
                <div class="heatmap-wrap" id="heatmapWrap"></div>
                <div class="heatmap-months" id="heatmapMonths"></div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const heatmapData = @json($heatmapData);
            const wrap = document.getElementById('heatmapWrap');
            const monthsRow = document.getElementById('heatmapMonths');
            const today = new Date();
            const weeks = 47;
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const todayKey = today.toISOString().split('T')[0];

            let html = '';
            let monthsHtml = '';
            let lastMonth = null;

            for (let w = weeks - 1; w >= 0; w--) {
                const weekStartDate = new Date(today);
                weekStartDate.setDate(weekStartDate.getDate() - (w * 7 + 6));
                const m = weekStartDate.getMonth();

                if (m !== lastMonth) {
                    monthsHtml += `<div style="width:14px;flex-shrink:0;">${monthNames[m]}</div>`;
                    lastMonth = m;
                } else {
                    monthsHtml += `<div style="width:14px;flex-shrink:0;"></div>`;
                }

                html += '<div class="heatmap-week">';
                for (let d = 6; d >= 0; d--) {
                    const date = new Date(today);
                    date.setDate(date.getDate() - (w * 7 + d));
                    const key = date.toISOString().split('T')[0];
                    const count = heatmapData[key] || 0;
                    let level = 0;
                    if (count >= 1) level = 1;
                    if (count >= 2) level = 2;
                    if (count >= 3) level = 3;
                    if (count >= 5) level = 4;
                    const isToday = key === todayKey ? ' is-today' : '';
                    html += `<div class="heatmap-day${isToday}" data-level="${level}" title="${key}: ${count} quiz${count !== 1 ? 'zes' : ''}"></div>`;
                }
                html += '</div>';
            }

            wrap.innerHTML = html;
            monthsRow.innerHTML = monthsHtml;
        });
    </script>

</body>

</html>