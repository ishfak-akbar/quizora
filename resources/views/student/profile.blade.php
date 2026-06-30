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
        }

        .profile-page {
            max-width: 760px;
            margin: 0 auto;
            padding: 40px 24px 60px;
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
            transition: color 0.2s;
        }

        .profile-back:hover {
            color: #fff;
        }

        .profile-hero {
            background: linear-gradient(135deg, #2E2570 0%, #4F46E5 50%, #818CF8 100%);
            border-radius: 20px;
            padding: 36px 32px;
            display: flex;
            align-items: center;
            gap: 24px;
            position: relative;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .profile-hero::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
        }

        .profile-avatar-xl {
            width: 92px;
            height: 92px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
        }

        .profile-hero-info {
            position: relative;
            z-index: 1;
            flex: 1;
        }

        .profile-hero-info h1 {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 4px;
        }

        .profile-hero-info p {
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 10px;
        }

        .profile-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 20px;
        }

        .edit-btn {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.2s;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .edit-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 24px;
        }

        .mini-stat {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 14px;
            padding: 18px;
            text-align: center;
        }

        .mini-stat-value {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
        }

        .mini-stat-label {
            font-size: 11px;
            color: var(--color-text-muted);
            margin-top: 4px;
        }

        .info-card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 16px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .info-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--color-border-light);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card-header i {
            font-size: 18px;
            color: var(--color-primary-glow);
        }

        .info-card-header h2 {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
        }

        .info-card-body {
            padding: 24px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 24px;
        }

        .info-item-full {
            grid-column: 1 / -1;
        }

        .info-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 14px;
            color: #fff;
            font-weight: 500;
            line-height: 1.5;
        }

        .info-value.empty {
            color: var(--color-text-muted);
            font-weight: 400;
            font-style: italic;
        }

        @media (max-width: 640px) {
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .profile-hero {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>
    <div class="profile-page">

        <a href="{{ route('student.dashboard') }}" class="profile-back">
            <i class="ti ti-arrow-left"></i> Back to Dashboard
        </a>

        {{-- HERO --}}
        <div class="profile-hero">
            <div class="profile-avatar-xl" style="background: {{ $student->avatar_color ?? 'rgba(255,255,255,0.15)' }}">
                {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
            <div class="profile-hero-info">
                <h1>{{ $student->name }}</h1>
                <p>{{ $student->email }}</p>
                <span class="profile-role-badge"><i class="ti ti-school"></i> Student</span>
            </div>
            <a href="{{ route('student.settings') }}" class="edit-btn">
                <i class="ti ti-pencil"></i> Edit Profile
            </a>
        </div>

        {{-- QUIZ STATS --}}
        <div class="stats-row">
            <div class="mini-stat">
                <div class="mini-stat-value">{{ $totalAttempts }}</div>
                <div class="mini-stat-label">Quizzes Taken</div>
            </div>
            <div class="mini-stat">
                <div class="mini-stat-value" style="color:#22D3EE">{{ $avgScore }}%</div>
                <div class="mini-stat-label">Average Score</div>
            </div>
            <div class="mini-stat">
                <div class="mini-stat-value" style="color:#34D399">{{ $bestScore }}%</div>
                <div class="mini-stat-label">Best Score</div>
            </div>
            <div class="mini-stat">
                <div class="mini-stat-value" style="color:#F59E0B">{{ $quizzesPassed }}</div>
                <div class="mini-stat-label">Quizzes Passed</div>
            </div>
        </div>

        {{-- PERSONAL INFO --}}
        <div class="info-card">
            <div class="info-card-header">
                <i class="ti ti-user"></i>
                <h2>Personal Information</h2>
            </div>
            <div class="info-card-body">
                <div class="info-grid">
                    <div>
                        <div class="info-label">Phone</div>
                        <div class="info-value {{ $student->phone ? '' : 'empty' }}">
                            {{ $student->phone ?? 'Not set' }}
                        </div>
                    </div>
                    <div>
                        <div class="info-label">Date of Birth</div>
                        <div class="info-value {{ $student->date_of_birth ? '' : 'empty' }}">
                            {{ $student->date_of_birth?->format('M d, Y') ?? 'Not set' }}
                        </div>
                    </div>
                    <div>
                        <div class="info-label">Gender</div>
                        <div class="info-value {{ $student->gender ? '' : 'empty' }}">
                            {{ $student->gender ? ucfirst(str_replace('_', ' ', $student->gender)) : 'Not set' }}
                        </div>
                    </div>
                    <div>
                        <div class="info-label">Location</div>
                        <div class="info-value {{ $student->location ? '' : 'empty' }}">
                            {{ $student->location ?? 'Not set' }}
                        </div>
                    </div>
                    <div class="info-item-full">
                        <div class="info-label">Bio</div>
                        <div class="info-value {{ $student->bio ? '' : 'empty' }}">
                            {{ $student->bio ?? 'No bio added yet.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ACADEMIC INFO --}}
        <div class="info-card">
            <div class="info-card-header">
                <i class="ti ti-school"></i>
                <h2>Academic Information</h2>
            </div>
            <div class="info-card-body">
                <div class="info-grid">
                    <div class="info-item-full">
                        <div class="info-label">Institution</div>
                        <div class="info-value {{ $student->institution ? '' : 'empty' }}">
                            {{ $student->institution ?? 'Not set' }}
                        </div>
                    </div>
                    <div>
                        <div class="info-label">Class / Year Level</div>
                        <div class="info-value {{ $student->class_level ? '' : 'empty' }}">
                            {{ $student->class_level ?? 'Not set' }}
                        </div>
                    </div>
                    <div>
                        <div class="info-label">Education Level</div>
                        <div class="info-value {{ $student->education_level ? '' : 'empty' }}">
                            {{ $student->education_level ? strtoupper($student->education_level) : 'Not set' }}
                        </div>
                    </div>
                    <div>
                        <div class="info-label">Study Goal</div>
                        <div class="info-value {{ $student->study_goal ? '' : 'empty' }}">
                            {{ $student->study_goal ? ucfirst(str_replace('_', ' ', $student->study_goal)) : 'Not set' }}
                        </div>
                    </div>
                    <div>
                        <div class="info-label">Preparing For</div>
                        <div class="info-value {{ $student->preparing_for ? '' : 'empty' }}">
                            {{ $student->preparing_for ?? 'Not set' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PREFERENCES --}}
        <div class="info-card">
            <div class="info-card-header">
                <i class="ti ti-adjustments"></i>
                <h2>Preferences</h2>
            </div>
            <div class="info-card-body">
                <div class="info-grid">
                    <div>
                        <div class="info-label">Preferred Language</div>
                        <div class="info-value">
                            {{ $student->preferred_language === 'bangla' ? 'বাংলা' : 'English' }}
                        </div>
                    </div>
                    <div>
                        <div class="info-label">Target Score</div>
                        <div class="info-value {{ $student->target_score ? '' : 'empty' }}">
                            {{ $student->target_score ? $student->target_score . '%' : 'Not set' }}
                        </div>
                    </div>
                    <div>
                        <div class="info-label">Bookmarked Quizzes</div>
                        <div class="info-value">{{ $bookmarkCount }}</div>
                    </div>
                    <div>
                        <div class="info-label">Member Since</div>
                        <div class="info-value">{{ $memberSince->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>