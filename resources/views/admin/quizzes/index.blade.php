@extends('layouts.admin')

@section('title', 'Quizora — Quizzes')
@section('page-title', 'Quizzes')
@section('page-subtitle', 'Manage all quizzes across the platform')

@push('styles')
<style>
    .quizzes-toolbar {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .quizzes-search {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 10px;
        padding: 0 14px;
        flex: 1;
        max-width: 320px;
        height: 40px;
    }

    .quizzes-search input {
        background: none;
        border: none;
        outline: none;
        color: #fff;
        font-size: 13px;
        width: 100%;
        font-family: var(--font);
    }

    .quizzes-search input::placeholder {
        color: var(--color-text-muted);
    }

    .filter-btn {
        height: 40px;
        padding: 0 14px;
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

    .quizzes-table-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        overflow: hidden;
    }

    .quizzes-table {
        width: 100%;
        border-collapse: collapse;
    }

    .quizzes-table th {
        text-align: left;
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 600;
        color: var(--color-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        border-bottom: 1px solid var(--color-border-light);
        background: rgba(255, 255, 255, 0.02);
    }

    .quizzes-table td {
        padding: 13px 16px;
        font-size: 13px;
        color: var(--color-text-secondary);
        border-bottom: 1px solid var(--color-border-light);
        vertical-align: middle;
    }

    .quizzes-table tr:last-child td {
        border-bottom: none;
    }

    .quizzes-table tr:hover td {
        background: var(--color-bg-row-hover);
    }

    .quiz-title {
        font-weight: 600;
        color: #fff;
        font-size: 13.5px;
    }

    .quiz-meta {
        font-size: 11.5px;
        color: var(--color-text-muted);
        margin-top: 2px;
    }

    .status-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-badge.active {
        background: rgba(16, 185, 129, 0.15);
        color: #34D399;
    }

    .status-badge.draft {
        background: rgba(107, 114, 128, 0.2);
        color: #9CA3AF;
    }

    .status-badge.closed,
    .status-badge.ended {
        background: rgba(248, 113, 113, 0.15);
        color: #F87171;
    }

    .visibility-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 6px;
    }

    .visibility-public {
        background: rgba(59, 130, 246, 0.15);
        color: #60A5FA;
    }

    .visibility-private {
        background: rgba(245, 158, 11, 0.15);
        color: #F59E0B;
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
    .vis-option:hover {
        background: var(--color-bg-row-hover);
        color: #fff;
    }
    .vis-option.active {
        background: rgba(16, 185, 129, 0.15);
        color: #34D399;
        font-weight: 600;
    }
</style>
@endpush

@section('content')

{{-- Toolbar --}}
<div class="quizzes-toolbar">
    <div class="quizzes-search">
        <i class="ti ti-search" style="color:var(--color-text-muted); font-size:16px;"></i>
        <input type="text" id="searchInput" value="{{ request('search') }}" placeholder="Search quizzes...">
    </div>

    <button type="button" class="filter-btn {{ !request('status') ? 'active' : '' }}" data-name="status" data-value="">
        All Status
    </button>
    <button type="button" class="filter-btn {{ request('status') === 'active' ? 'active' : '' }}" data-name="status" data-value="active">
        Active
    </button>
    <button type="button" class="filter-btn {{ request('status') === 'draft' ? 'active' : '' }}" data-name="status" data-value="draft">
        Draft
    </button>
    <button type="button" class="filter-btn {{ request('status') === 'closed' ? 'active' : '' }}" data-name="status" data-value="closed">
        Closed
    </button>

    {{-- Visibility Dropdown --}}
    <div style="position:relative;">
        <button type="button" class="filter-btn" id="visibilityBtn" style="min-width:120px; justify-content:space-between; display:inline-flex; align-items:center; gap:8px;">
            <span id="visibilityLabel">
                @if(request('visibility') === 'public') Public
                @elseif(request('visibility') === 'private') Private
                @else All Visibility
                @endif
            </span>
            <i class="ti ti-chevron-down" style="font-size:14px;"></i>
        </button>
        <div id="visibilityDropdown" style="display:none; position:absolute; top:calc(100% + 6px); left:0; background:var(--color-bg-card); border:1px solid var(--color-border-light); border-radius:10px; min-width:140px; z-index:50; overflow:hidden; box-shadow:0 12px 32px rgba(0,0,0,0.4);">
            <div class="vis-option {{ !request('visibility') ? 'active' : '' }}" data-value="" style="padding:10px 14px; font-size:13px; color:var(--color-text-secondary); cursor:pointer;">All Visibility</div>
            <div class="vis-option {{ request('visibility') === 'public' ? 'active' : '' }}" data-value="public" style="padding:10px 14px; font-size:13px; color:var(--color-text-secondary); cursor:pointer;">Public</div>
            <div class="vis-option {{ request('visibility') === 'private' ? 'active' : '' }}" data-value="private" style="padding:10px 14px; font-size:13px; color:var(--color-text-secondary); cursor:pointer;">Private</div>
        </div>
    </div>
</div>

{{-- Table --}}
<div id="quizTableContainer">
    <div class="quizzes-table-card">
        <table class="quizzes-table">
            <thead>
                <tr>
                    <th>Quiz</th>
                    <th>Teacher</th>
                    <th>Status</th>
                    <th>Visibility</th>
                    <th>Submissions</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizzes as $quiz)
                <tr>
                    <td>
                        <div class="quiz-title">{{ Str::limit($quiz->title, 35) }}</div>
                        <div class="quiz-meta">
                            {{ $quiz->category ?? 'General' }} · {{ ucfirst($quiz->difficulty ?? 'medium') }}
                        </div>
                    </td>
                    <td>{{ $quiz->teacher->name ?? '—' }}</td>
                    <td>
                        <span class="status-badge {{ $quiz->display_status }}">
                            <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                            {{ ucfirst($quiz->display_status) }}
                        </span>
                    </td>
                    <td>
                        <span class="visibility-badge visibility-{{ $quiz->visibility }}">
                            {{ ucfirst($quiz->visibility) }}
                        </span>
                    </td>
                    <td>{{ $quiz->submitted_count }}</td>
                    <td>{{ $quiz->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="action-btns">
                            @if($quiz->status === 'active')
                            <form method="POST" action="{{ route('admin.quizzes.close', $quiz) }}" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-btn" title="Force Close"
                                    onclick="return confirm('Force close this quiz?')">
                                    <i class="ti ti-player-stop"></i>
                                </button>
                            </form>
                            @endif

                            <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to delete this quiz?')">
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
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="ti ti-file-off" style="font-size:36px; display:block; margin-bottom:12px; opacity:0.4;"></i>
                            No quizzes found.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($quizzes->hasPages())
        <div class="pagination-wrap">
            {{ $quizzes->links() }}
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    const container = document.getElementById('quizTableContainer');
    const searchInput = document.getElementById('searchInput');
    const visibilityBtn = document.getElementById('visibilityBtn');
    const visibilityDropdown = document.getElementById('visibilityDropdown');
    const visibilityLabel = document.getElementById('visibilityLabel');

    let filters = {
        search: searchInput.value || '',
        status: '{{ request('status') }}',
        visibility: '{{ request('visibility') }}'
    };

    function loadQuizzes() {
        const params = new URLSearchParams();
        if (filters.search) params.set('search', filters.search);
        if (filters.status) params.set('status', filters.status);
        if (filters.visibility) params.set('visibility', filters.visibility);

        const url = `{{ route('admin.quizzes.index') }}?${params.toString()}`;
        window.history.replaceState({}, '', url);

        container.style.opacity = '0.5';

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.getElementById('quizTableContainer');
            if (newContent) {
                container.innerHTML = newContent.innerHTML;
            }
            container.style.opacity = '1';
        })
        .catch(() => {
            container.style.opacity = '1';
        });
    }

    // Status filter buttons
    document.querySelectorAll('.filter-btn[data-name="status"]').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.filter-btn[data-name="status"]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            filters.status = this.dataset.value;
            loadQuizzes();
        });
    });

    // Visibility dropdown toggle
    visibilityBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        visibilityDropdown.style.display = visibilityDropdown.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', () => {
        visibilityDropdown.style.display = 'none';
    });

    // Visibility options
    document.querySelectorAll('.vis-option').forEach(opt => {
        opt.addEventListener('click', function () {
            document.querySelectorAll('.vis-option').forEach(o => o.classList.remove('active'));
            this.classList.add('active');

            filters.visibility = this.dataset.value;
            visibilityLabel.textContent = this.textContent.trim();
            visibilityDropdown.style.display = 'none';
            loadQuizzes();
        });
    });

    // Search with debounce
    let searchTimeout;
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filters.search = this.value;
            loadQuizzes();
        }, 400);
    });
</script>
@endpush