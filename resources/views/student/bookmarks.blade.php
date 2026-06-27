@extends('layouts.student')
@section('title', 'Quizora — Bookmarks')
@push('styles')
<style>
    .page-header {
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

    .quiz-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(268px, 1fr));
        gap: 18px;
    }

    .pquiz-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 16px;
        transition: border-color 0.22s, transform 0.22s, box-shadow 0.22s;
        text-decoration: none;
        display: block;
        position: relative;
        overflow: hidden;
    }

    .pquiz-card:hover {
        border-color: rgba(79, 70, 229, 0.5);
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
    }

    .pquiz-banner {
        height: 96px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .pquiz-banner-icon {
        font-size: 38px;
        color: rgba(255, 255, 255, 0.9);
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3));
    }

    .pquiz-banner-deco {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        pointer-events: none;
        overflow: hidden;
    }

    .pquiz-banner-deco.d1 {
        width: 80px;
        height: 80px;
        bottom: -24px;
        right: -20px;
    }

    .pquiz-banner-deco.d2 {
        width: 44px;
        height: 44px;
        top: -10px;
        left: 16px;
    }

    .pquiz-body {
        padding: 16px 18px 18px;
    }

    .pquiz-category {
        font-size: 10px;
        font-weight: 700;
        color: var(--color-primary-glow);
        text-transform: uppercase;
        letter-spacing: 0.7px;
        margin-bottom: 6px;
    }

    .pquiz-title {
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 10px;
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .pquiz-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }

    .pquiz-meta-item {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        color: var(--color-text-muted);
    }

    .pquiz-meta-item i {
        font-size: 13px;
    }

    .pquiz-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 12px;
        border-top: 1px solid var(--color-border-light);
    }

    .diff-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .diff-easy {
        background: rgba(52, 211, 153, 0.15);
        color: #34D399;
    }

    .diff-medium {
        background: rgba(245, 158, 11, 0.15);
        color: #F59E0B;
    }

    .diff-hard {
        background: rgba(248, 113, 113, 0.15);
        color: #F87171;
    }

    .pquiz-take-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(79, 70, 229, 0.18);
        border: 1px solid rgba(79, 70, 229, 0.35);
        color: var(--color-primary-glow);
        font-size: 12px;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 8px;
        text-decoration: none;
        transition: background 0.2s;
        font-family: var(--font);
    }

    .pquiz-take-btn:hover {
        background: rgba(79, 70, 229, 0.3);
        color: #fff;
    }

    .empty-state {
        text-align: center;
        padding: 70px 20px;
        color: var(--color-text-muted);
    }

    .empty-state i {
        font-size: 52px;
        margin-bottom: 16px;
        color: rgba(79, 70, 229, 0.3);
        display: block;
    }

    .empty-state h3 {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-text-secondary);
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 13px;
        margin-bottom: 20px;
    }

    .empty-browse-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--color-primary-solid);
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        transition: background 0.2s;
    }

    .empty-browse-btn:hover {
        background: #4338CA;
    }
</style>
@endpush

@section('content')

@php
function bookmarkBannerConfig(string $category, int $index): array {
$map = [
'math' => ['linear-gradient(135deg,#7C3AED,#A78BFA)', 'ti ti-math'],
'science' => ['linear-gradient(135deg,#0891B2,#22D3EE)', 'ti ti-flask'],
'physics' => ['linear-gradient(135deg,#0891B2,#22D3EE)', 'ti ti-atom'],
'chemistry' => ['linear-gradient(135deg,#059669,#34D399)', 'ti ti-flask-2'],
'biology' => ['linear-gradient(135deg,#15803D,#4ADE80)', 'ti ti-dna'],
'english' => ['linear-gradient(135deg,#B45309,#F59E0B)', 'ti ti-alphabet-latin'],
'language' => ['linear-gradient(135deg,#B45309,#F59E0B)', 'ti ti-language'],
'history' => ['linear-gradient(135deg,#92400E,#D97706)', 'ti ti-building-castle'],
'geography' => ['linear-gradient(135deg,#065F46,#10B981)', 'ti ti-map'],
'bcs' => ['linear-gradient(135deg,#1E3A5F,#3B82F6)', 'ti ti-building-bank'],
'bangladesh' => ['linear-gradient(135deg,#166534,#22C55E)', 'ti ti-building-bank'],
'computer' => ['linear-gradient(135deg,#1D4ED8,#60A5FA)', 'ti ti-device-desktop'],
'coding' => ['linear-gradient(135deg,#DB2777,#F472B6)', 'ti ti-code'],
'algorithm' => ['linear-gradient(135deg,#DB2777,#F472B6)', 'ti ti-binary-tree'],
'data' => ['linear-gradient(135deg,#DB2777,#F472B6)', 'ti ti-chart-dots'],
'general' => ['linear-gradient(135deg,#4F46E5,#818CF8)', 'ti ti-bulb'],
'economics' => ['linear-gradient(135deg,#0F766E,#2DD4BF)', 'ti ti-chart-line'],
'business' => ['linear-gradient(135deg,#0F766E,#2DD4BF)', 'ti ti-briefcase'],
'religion' => ['linear-gradient(135deg,#78350F,#D97706)', 'ti ti-moon-stars'],
'islamic' => ['linear-gradient(135deg,#78350F,#D97706)', 'ti ti-moon-stars'],
];
$fallbacks = [
'linear-gradient(135deg,#4F46E5,#818CF8)',
'linear-gradient(135deg,#059669,#34D399)',
'linear-gradient(135deg,#DB2777,#F472B6)',
'linear-gradient(135deg,#B45309,#F59E0B)',
'linear-gradient(135deg,#0891B2,#22D3EE)',
'linear-gradient(135deg,#7C3AED,#A78BFA)',
];
$fallbackIcons = ['ti ti-help-circle','ti ti-star','ti ti-bolt','ti ti-flame','ti ti-rocket','ti ti-crown'];
$lower = strtolower($category);
foreach ($map as $keyword => $config) {
if (str_contains($lower, $keyword)) return $config;
}
return [$fallbacks[$index % count($fallbacks)], $fallbackIcons[$index % count($fallbackIcons)]];
}
@endphp

<div class="page-header">
    <h1>Bookmarks</h1>
    <p>Quizzes you've saved for later</p>
</div>

@if($bookmarks->isEmpty())
<div class="empty-state">
    <i class="ti ti-bookmark-off"></i>
    <h3>No bookmarks yet</h3>
    <p>Save quizzes you want to attempt later</p>
    <a href="{{ route('student.browse') }}" class="empty-browse-btn">
        <i class="ti ti-compass"></i> Browse Quizzes
    </a>
</div>
@else
<div class="quiz-grid" id="bookmarksGrid">
    @foreach($bookmarks as $bookmark)
    @php
    $quiz = $bookmark->quiz;
    [$bg, $icon] = bookmarkBannerConfig($quiz->category ?? '', $loop->index);
    @endphp
    <a href="{{ route('student.quiz.detail', $quiz->id) }}" class="pquiz-card" id="bookmark-card-{{ $quiz->id }}">

        <div class="pquiz-banner" style="background: {{ $bg }};">
            <div class="pquiz-banner-deco d1"></div>
            <div class="pquiz-banner-deco d2"></div>
            <i class="{{ $icon }} pquiz-banner-icon"></i>
        </div>

        <button class="bookmark-btn bookmarked"
            onclick="toggleBookmark(event, this, {{ $quiz->id }})"
            data-quiz-id="{{ $quiz->id }}"
            title="Remove bookmark">

            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2 2 2 0 0 1 2-2 2 2 0 0 1 2-2 2 2 0 0 1 2 2 2 2 0 0 1 2-2z"></path>
            </svg>
        </button>

        <div class="pquiz-body">
            @if($quiz->category)
            <div class="pquiz-category">{{ $quiz->category }}</div>
            @endif
            <div class="pquiz-title">{{ $quiz->title }}</div>
            <div class="pquiz-meta">
                <div class="pquiz-meta-item">
                    <i class="ti ti-help-circle"></i> {{ $quiz->questions()->count() }} Qs
                </div>
                @if($quiz->time_limit)
                <div class="pquiz-meta-item">
                    <i class="ti ti-clock"></i> {{ $quiz->time_limit }} min
                </div>
                @endif
            </div>
            <div class="pquiz-footer">
                <span class="diff-badge diff-{{ $quiz->difficulty }}">{{ ucfirst($quiz->difficulty) }}</span>
                <span class="pquiz-take-btn"><i class="ti ti-player-play"></i> Start</span>
            </div>
        </div>
    </a>
    @endforeach
</div>
@endif

@endsection

@push('scripts')
<script>
    window.toggleBookmark = function(e, btn, quizId) {
        e.preventDefault();
        e.stopPropagation();

        showConfirmToast(() => {
            fetch(`/student/bookmarks/${quizId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (!data.bookmarked) {
                        const card = document.getElementById(`bookmark-card-${quizId}`);
                        card.style.transition = 'opacity 0.2s, transform 0.2s';
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            card.remove();
                            if (!document.querySelector('#bookmarksGrid .pquiz-card')) {
                                document.getElementById('bookmarksGrid').remove();
                                document.querySelector('.page-header').insertAdjacentHTML('afterend', `
                            <div class="empty-state">
                                <i class="ti ti-bookmark-off" style="font-size:52px;display:block;margin-bottom:16px;color:rgba(79,70,229,0.3);"></i>
                                <h3 style="font-size:16px;font-weight:600;color:#9ca3af;margin-bottom:8px;">No bookmarks yet</h3>
                                <p style="font-size:13px;color:#6b7280;margin-bottom:20px;">Save quizzes you want to attempt later</p>
                                <a href="/student/browse" class="empty-browse-btn"><i class="ti ti-compass"></i> Browse Quizzes</a>
                            </div>
                        `);
                            }
                        }, 220);
                    }
                })
                .catch(() => alert('Something went wrong.'));
        });
    };

    function showConfirmToast(onConfirm) {
        const existing = document.getElementById('confirmToast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.id = 'confirmToast';
        toast.style.cssText = `
        position:fixed; bottom:28px; left:50%; transform:translateX(-50%);
        background:#1e1a3e; border:1px solid rgba(248,113,113,0.4);
        padding:14px 20px; border-radius:14px; font-size:13px;
        display:flex; align-items:center; gap:14px; z-index:9999;
        backdrop-filter:blur(12px); box-shadow:0 8px 32px rgba(0,0,0,0.4);
        white-space:nowrap;
    `;
        toast.innerHTML = `
        <i class="ti ti-bookmark-off" style="font-size:18px;color:#F87171;"></i>
        <span style="color:#fff;font-weight:500;">Remove this bookmark?</span>
        <button id="confirmYes" style="background:#EF4444;border:none;color:#fff;font-size:12px;
            font-weight:700;padding:7px 16px;border-radius:8px;cursor:pointer;font-family:var(--font);">
            Remove
        </button>
        <button id="confirmNo" style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);
            color:#9ca3af;font-size:12px;font-weight:600;padding:7px 14px;border-radius:8px;
            cursor:pointer;font-family:var(--font);">
            Cancel
        </button>
    `;
        document.body.appendChild(toast);

        document.getElementById('confirmYes').onclick = () => {
            toast.remove();
            onConfirm();
        };
        document.getElementById('confirmNo').onclick = () => toast.remove();
    }
</script>
@endpush