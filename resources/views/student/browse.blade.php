@extends('layouts.student')
@section('title', 'Quizora — Browse Quizzes')
@push('styles')
<style>
    .page-header {
        margin-bottom: 20px;
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

    /* ── SEARCH BAR ── */
    .search-bar-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .search-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.05);
        border: 1.5px solid var(--color-border-light);
        border-radius: 12px;
        padding: 0 16px;
        flex: 1;
        min-width: 240px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .search-wrap:focus-within {
        border-color: rgba(79, 70, 229, 0.6);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .search-wrap input {
        flex: 1;
        height: 44px;
        background: none;
        border: none;
        outline: none;
        color: #fff;
        font-size: 14px;
        font-family: var(--font);
    }

    .search-wrap input::placeholder {
        color: var(--color-text-muted);
    }

    .search-wrap i {
        color: var(--color-text-muted);
        font-size: 18px;
    }

    .filter-pill {
        height: 44px;
        padding: 0 18px;
        border-radius: 12px;
        border: 1.5px solid var(--color-border-light);
        background: var(--color-bg-card);
        color: var(--color-text-secondary);
        font-size: 13px;
        font-weight: 500;
        font-family: var(--font);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        white-space: nowrap;
        appearance: none;
        -webkit-appearance: none;
    }

    .filter-pill:hover {
        border-color: rgba(79, 70, 229, 0.4);
        color: #fff;
    }

    /* ── CATEGORY CHIPS ── */
    .category-scroll {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 6px;
        margin-bottom: 28px;
        scrollbar-width: none;
    }

    .category-scroll::-webkit-scrollbar {
        display: none;
    }

    .category-chip {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        border-radius: 24px;
        background: var(--color-bg-card);
        border: 1.5px solid var(--color-border-light);
        color: var(--color-text-secondary);
        font-size: 13px;
        font-weight: 500;
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
        text-decoration: none;
    }

    .category-chip:hover {
        border-color: rgba(79, 70, 229, 0.4);
        color: #fff;
    }

    .category-chip.active {
        background: var(--color-primary-solid);
        border-color: var(--color-primary-solid);
        color: #fff;
    }

    /* ── SECTION ── */
    .quiz-section {
        margin-bottom: 36px;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .section-header h2 {
        font-size: 16px;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-header h2 i {
        color: var(--color-primary-glow);
        font-size: 18px;
    }

    /* ── QUIZ GRID ── */
    .quiz-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(268px, 1fr));
        gap: 18px;
    }

    /* ── QUIZ CARD ── */
    .pquiz-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 16px;
        overflow: hidden;
        transition: border-color 0.22s, transform 0.22s, box-shadow 0.22s;
        text-decoration: none;
        display: block;
        position: relative;
    }

    .pquiz-card:hover {
        border-color: rgba(79, 70, 229, 0.5);
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
    }

    /* ── CARD BANNER ── */
    .pquiz-banner {
        height: 96px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    /* Subtle noise texture overlay */
    .pquiz-banner::after {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.08'/%3E%3C/svg%3E");
        pointer-events: none;
    }

    .pquiz-banner-icon {
        font-size: 38px;
        color: rgba(255, 255, 255, 0.9);
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3));
    }

    /* Decorative circles in banner */
    .pquiz-banner-deco {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        pointer-events: none;
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

    /* ── CARD BODY ── */
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

    /* ── CARD FOOTER ── */
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
        transition: background 0.2s, border-color 0.2s;
        font-family: var(--font);
    }

    .pquiz-take-btn:hover {
        background: rgba(79, 70, 229, 0.3);
        border-color: rgba(79, 70, 229, 0.6);
        color: #fff;
    }

    /* ── EMPTY STATE ── */
    .empty-section {
        padding: 48px 40px;
        text-align: center;
        color: var(--color-text-muted);
        font-size: 13px;
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 16px;
    }

    .empty-section i {
        font-size: 36px;
        display: block;
        margin-bottom: 12px;
        color: rgba(79, 70, 229, 0.3);
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <h1>Discover Quizzes</h1>
    <p>Browse public quizzes from school basics to BCS preparation</p>
</div>

{{-- SEARCH + FILTERS --}}
<form method="GET" action="{{ route('student.browse') }}" id="browseForm">
    <div class="search-bar-wrap">
        <div class="search-wrap">
            <i class="ti ti-search"></i>
            <input type="text" name="search" placeholder="Search quizzes, topics, or tags..."
                value="{{ $search }}" id="searchInput" autocomplete="off" />
        </div>
        <select name="difficulty" class="filter-pill" onchange="document.getElementById('browseForm').submit()">
            <option value="">All Difficulties</option>
            <option value="easy" {{ request('difficulty') === 'easy'   ? 'selected' : '' }}>🟢 Easy</option>
            <option value="medium" {{ request('difficulty') === 'medium' ? 'selected' : '' }}>🟡 Medium</option>
            <option value="hard" {{ request('difficulty') === 'hard'   ? 'selected' : '' }}>🔴 Hard</option>
        </select>
    </div>

    {{-- CATEGORY CHIPS --}}
    <div class="category-scroll">
        <a href="{{ route('student.browse') }}"
            class="category-chip {{ $activeCategory === 'all' ? 'active' : '' }}">
            <i class="ti ti-apps"></i> All
        </a>
        @foreach($categories as $cat)
        <a href="{{ route('student.browse', ['category' => $cat, 'difficulty' => request('difficulty')]) }}"
            class="category-chip {{ $activeCategory === $cat ? 'active' : '' }}">
            <i class="{{ \App\Helpers\QuizHelper::categoryIcon($cat) }}"></i> {{ $cat }}
        </a>
        @endforeach
    </div>
</form>

@php
/**
* Banner config: gradient + icon per category keyword.
* Falls back to a colour cycle if no keyword matches.
*/
function quizBannerConfig(string $category, int $index): array {
$map = [
'math' => ['linear-gradient(135deg,#7C3AED,#A78BFA)', 'ti ti-math'],
'calculus' => ['linear-gradient(135deg,#7C3AED,#A78BFA)', 'ti ti-math'],
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
'gk' => ['linear-gradient(135deg,#4F46E5,#818CF8)', 'ti ti-bulb'],
'religion' => ['linear-gradient(135deg,#78350F,#D97706)', 'ti ti-moon-stars'],
'islamic' => ['linear-gradient(135deg,#78350F,#D97706)', 'ti ti-moon-stars'],
'economics' => ['linear-gradient(135deg,#0F766E,#2DD4BF)', 'ti ti-chart-line'],
'business' => ['linear-gradient(135deg,#0F766E,#2DD4BF)', 'ti ti-briefcase'],
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
if (str_contains($lower, $keyword)) {
return $config;
}
}
return [
$fallbacks[$index % count($fallbacks)],
$fallbackIcons[$index % count($fallbackIcons)],
];
}
@endphp

{{-- ── TRENDING ── --}}
<div class="quiz-section">
    <div class="section-header">
        <h2><i class="ti ti-flame"></i> Trending Now</h2>
    </div>

    @if($trending->isEmpty())
    <div class="empty-section">
        <i class="ti ti-mood-empty"></i>
        No quizzes available yet. Check back soon!
    </div>
    @else
    <div class="quiz-grid">
        @foreach($trending as $quiz)
        @php [$bg, $icon] = quizBannerConfig($quiz->category ?? '', $loop->index); @endphp
        <a href="{{ route('student.quiz.detail', $quiz->id) }}" class="pquiz-card">

            {{-- BANNER --}}
            <div class="pquiz-banner" style="background: {{ $bg }};">
                <div class="pquiz-banner-deco d1"></div>
                <div class="pquiz-banner-deco d2"></div>
                <i class="{{ $icon }} pquiz-banner-icon"></i>
            </div>

            {{-- BOOKMARK --}}
            <button class="bookmark-btn {{ in_array($quiz->id, $bookmarkedIds ?? []) ? 'bookmarked' : '' }}"
                onclick="toggleBookmark(event, this, {{ $quiz->id }})"
                data-quiz-id="{{ $quiz->id }}"
                title="{{ in_array($quiz->id, $bookmarkedIds ?? []) ? 'Remove bookmark' : 'Bookmark this quiz' }}">

                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2 2 2 0 0 1 2-2 2 2 0 0 1 2-2 2 2 0 0 1 2 2 2 2 0 0 1 2-2z"></path>
                </svg>
            </button>

            {{-- BODY --}}
            <div class="pquiz-body">
                @if($quiz->category)
                <div class="pquiz-category">{{ $quiz->category }}</div>
                @endif
                <div class="pquiz-title">{{ $quiz->title }}</div>
                <div class="pquiz-meta">
                    <div class="pquiz-meta-item">
                        <i class="ti ti-help-circle"></i> {{ $quiz->questions_count }} Qs
                    </div>
                    @if($quiz->time_limit)
                    <div class="pquiz-meta-item">
                        <i class="ti ti-clock"></i> {{ $quiz->time_limit }} min
                    </div>
                    @endif
                    <div class="pquiz-meta-item">
                        <i class="ti ti-users"></i> {{ number_format($quiz->attempts_count) }}
                    </div>
                </div>
                <div class="pquiz-footer">
                    <span class="diff-badge diff-{{ $quiz->difficulty }}">{{ ucfirst($quiz->difficulty) }}</span>
                    <span class="pquiz-take-btn">
                        <i class="ti ti-player-play"></i> Start
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @endif
</div>

{{-- ── RECENTLY ADDED ── --}}
<div class="quiz-section">
    <div class="section-header">
        <h2><i class="ti ti-sparkles"></i> Recently Added</h2>
    </div>

    @if($latest->isEmpty())
    <div class="empty-section">
        <i class="ti ti-mood-empty"></i>
        No quizzes available yet.
    </div>
    @else
    <div class="quiz-grid">
        @foreach($latest as $quiz)
        @php [$bg, $icon] = quizBannerConfig($quiz->category ?? '', $loop->index + 3); @endphp
        <a href="{{ route('student.quiz.detail', $quiz->id) }}" class="pquiz-card">

            <div class="pquiz-banner" style="background: {{ $bg }};">
                <div class="pquiz-banner-deco d1"></div>
                <div class="pquiz-banner-deco d2"></div>
                <i class="{{ $icon }} pquiz-banner-icon"></i>
            </div>

            <button class="bookmark-btn {{ in_array($quiz->id, $bookmarkedIds ?? []) ? 'bookmarked' : '' }}"
                onclick="toggleBookmark(event, this, {{ $quiz->id }})"
                data-quiz-id="{{ $quiz->id }}"
                title="{{ in_array($quiz->id, $bookmarkedIds ?? []) ? 'Remove bookmark' : 'Bookmark this quiz' }}">

                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round">
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
                        <i class="ti ti-help-circle"></i> {{ $quiz->questions_count }} Qs
                    </div>
                    @if($quiz->time_limit)
                    <div class="pquiz-meta-item">
                        <i class="ti ti-clock"></i> {{ $quiz->time_limit }} min
                    </div>
                    @endif
                    <div class="pquiz-meta-item">
                        <i class="ti ti-users"></i> {{ number_format($quiz->attempts_count) }}
                    </div>
                </div>
                <div class="pquiz-footer">
                    <span class="diff-badge diff-{{ $quiz->difficulty }}">{{ ucfirst($quiz->difficulty) }}</span>
                    <span class="pquiz-take-btn">
                        <i class="ti ti-player-play"></i> Start
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    // Debounced search
    let searchTimer;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => document.getElementById('browseForm').submit(), 500);
    });

    function showToast(msg, iconClass, isError = false) {
        const existing = document.getElementById('browseToast');
        if (existing) existing.remove();

        const bg = isError ? 'rgba(248,113,113,0.15)' : 'rgba(52,211,153,0.15)';
        const br = isError ? 'rgba(248,113,113,0.4)' : 'rgba(52,211,153,0.4)';
        const col = isError ? '#F87171' : '#34D399';

        const toast = document.createElement('div');
        toast.id = 'browseToast';
        toast.style.cssText = `
            position:fixed; top:80px; left:50%; transform:translateX(-50%);
            background:${bg}; border:1px solid ${br}; color:${col};
            padding:11px 22px; border-radius:12px; font-size:13px; font-weight:600;
            display:flex; align-items:center; gap:9px; z-index:9999;
            backdrop-filter:blur(12px); box-shadow:0 8px 32px rgba(0,0,0,0.3);
            transition:opacity 0.4s ease, transform 0.4s ease;
        `;
        toast.innerHTML = `<i class="ti ${iconClass}" style="font-size:16px;"></i> ${msg}`;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(-50%) translateY(-10px)';
            setTimeout(() => toast.remove(), 400);
        }, 2500);
    }
</script>
@endpush