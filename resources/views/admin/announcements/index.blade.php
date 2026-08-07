@extends('layouts.admin')

@section('title', 'Quizora — Announcements')
@section('page-title', 'Announcements')
@section('page-subtitle', 'Send messages to teachers, students, or everyone')

@push('styles')
<style>
    .ann-layout {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 20px;
        align-items: start;
        height: calc(100vh - 120px);
    }

    .ann-form-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        padding: 22px;
        height: fit-content;
    }

    .ann-form-title {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-group {
        margin-bottom: 14px;
    }

    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-muted);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--color-border-light);
        border-radius: 10px;
        padding: 10px 14px;
        color: #fff;
        font-size: 13.5px;
        font-family: var(--font);
        outline: none;
        transition: border-color 0.2s;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: rgba(16, 185, 129, 0.5);
    }

    .form-textarea {
        min-height: 110px;
        resize: vertical;
    }

    .form-select {
        cursor: pointer;
    }

    .form-select option {
        background: #111827;
        color: #fff;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .ann-submit {
        width: 100%;
        margin-top: 6px;
        padding: 12px;
        background: #10B981;
        border: none;
        border-radius: 10px;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        font-family: var(--font);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background 0.2s;
    }

    .ann-submit:hover {
        background: #059669;
    }

    .ann-list-card {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .ann-list-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--color-border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }

    .ann-list-header h3 {
        font-size: 14px;
        font-weight: 600;
        color: #fff;
    }

    .ann-list-body {
        overflow-y: auto;
        flex: 1;
        min-height: 0;
    }

    .ann-item {
        padding: 18px 20px;
        border-bottom: 1px solid var(--color-border-light);
    }

    .ann-item:last-child {
        border-bottom: none;
    }

    .ann-item-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 8px;
    }

    .ann-item-title {
        font-size: 14px;
        font-weight: 600;
        color: #fff;
    }

    .ann-item-body {
        font-size: 13px;
        color: var(--color-text-secondary);
        line-height: 1.5;
        margin-bottom: 10px;
    }

    .ann-item-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        font-size: 11.5px;
        color: var(--color-text-muted);
    }

    .ann-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 20px;
    }

    .badge-all {
        background: rgba(16, 185, 129, 0.15);
        color: #34D399;
    }

    .badge-teachers {
        background: rgba(59, 130, 246, 0.15);
        color: #60A5FA;
    }

    .badge-students {
        background: rgba(167, 139, 250, 0.15);
        color: #A78BFA;
    }

    .badge-info {
        background: rgba(59, 130, 246, 0.15);
        color: #60A5FA;
    }

    .badge-success {
        background: rgba(16, 185, 129, 0.15);
        color: #34D399;
    }

    .badge-warning {
        background: rgba(245, 158, 11, 0.15);
        color: #F59E0B;
    }

    .badge-active {
        background: rgba(16, 185, 129, 0.15);
        color: #34D399;
    }

    .badge-inactive {
        background: rgba(107, 114, 128, 0.2);
        color: #9CA3AF;
    }

    .ann-actions {
        display: flex;
        gap: 6px;
        flex-shrink: 0;
    }

    .ann-btn {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: 1px solid var(--color-border-light);
        background: transparent;
        color: var(--color-text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.15s;
    }

    .ann-btn:hover {
        background: var(--color-bg-row-hover);
        color: #fff;
    }

    .ann-btn.danger:hover {
        background: rgba(248, 113, 113, 0.15);
        color: #F87171;
        border-color: rgba(248, 113, 113, 0.4);
    }

    .empty-box {
        text-align: center;
        padding: 48px 20px;
        color: var(--color-text-muted);
    }

    .pagination-wrap {
        padding: 14px 20px;
        border-top: 1px solid var(--color-border-light);
        display: flex;
        justify-content: flex-end;
    }

    @media (max-width: 900px) {
        .ann-layout {
            grid-template-columns: 1fr;
            height: auto;
        }

        .ann-list-card {
            height: auto;
            max-height: 60vh;
        }
    }
</style>
@endpush

@section('content')

@if(session('success'))
<div id="annToast" style="
    position: fixed;
    top: 90px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.4);
    border-radius: 12px;
    color: #34D399;
    font-size: 13px;
    font-weight: 600;
    backdrop-filter: blur(12px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.35);
    transition: opacity 0.4s ease, transform 0.4s ease;
">
    <i class="ti ti-circle-check" style="font-size:16px;"></i>
    {{ session('success') }}
</div>
<script>
    setTimeout(() => {
        const t = document.getElementById('annToast');
        if (t) {
            t.style.opacity = '0';
            t.style.transform = 'translateX(-50%) translateY(-10px)';
            setTimeout(() => t.remove(), 400);
        }
    }, 2000);
</script>
@endif

<div class="ann-layout">

    {{-- CREATE FORM --}}
    <div class="ann-form-card">
        <div class="ann-form-title">
            <i class="ti ti-speakerphone" style="color:#34D399;"></i>
            New Announcement
        </div>

        <form method="POST" action="{{ route('admin.announcements.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-input" placeholder="Announcement title..." required value="{{ old('title') }}">
                @error('title') <div style="color:#F87171; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Message</label>
                <textarea name="body" class="form-textarea" placeholder="Write your message..." required>{{ old('body') }}</textarea>
                @error('body') <div style="color:#F87171; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Audience</label>
                    <select name="audience" class="form-select">
                        <option value="all" {{ old('audience') === 'all' ? 'selected' : '' }}>Everyone</option>
                        <option value="teachers" {{ old('audience') === 'teachers' ? 'selected' : '' }}>Teachers only</option>
                        <option value="students" {{ old('audience') === 'students' ? 'selected' : '' }}>Students only</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="info" {{ old('type') === 'info' ? 'selected' : '' }}>Info</option>
                        <option value="success" {{ old('type') === 'success' ? 'selected' : '' }}>Success</option>
                        <option value="warning" {{ old('type') === 'warning' ? 'selected' : '' }}>Warning</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="ann-submit">
                <i class="ti ti-send"></i> Publish
            </button>
        </form>
    </div>

    {{-- LIST --}}
    <div class="ann-list-card">
        <div class="ann-list-header">
            <h3>Published Announcements</h3>
            <span style="font-size:12px; color:var(--color-text-muted);">{{ $announcements->total() }} total</span>
        </div>

        <div class="ann-list-body">
            @forelse($announcements as $ann)
            <div class="ann-item">
                <div class="ann-item-top">
                    <div class="ann-item-title">{{ $ann->title }}</div>
                    <div class="ann-actions">
                        <form method="POST" action="{{ route('admin.announcements.toggle', $ann) }}" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="ann-btn" title="{{ $ann->is_active ? 'Deactivate' : 'Activate' }}">
                                <i class="ti ti-{{ $ann->is_active ? 'player-pause' : 'player-play' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.announcements.destroy', $ann) }}" style="display:inline;"
                            onsubmit="return confirm('Delete this announcement?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ann-btn danger" title="Delete">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="ann-item-body">{{ $ann->body }}</div>

                <div class="ann-item-meta">
                    <span class="ann-badge badge-{{ $ann->audience }}">{{ ucfirst($ann->audience) }}</span>
                    <span class="ann-badge badge-{{ $ann->type }}">{{ ucfirst($ann->type) }}</span>
                    <span class="ann-badge {{ $ann->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $ann->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span>· {{ $ann->created_at->format('M d, Y H:i') }}</span>
                    <span>· by {{ $ann->creator->name ?? 'Admin' }}</span>
                </div>
            </div>
            @empty
            <div class="empty-box">
                <i class="ti ti-speakerphone" style="font-size:36px; display:block; margin-bottom:12px; opacity:0.35;"></i>
                No announcements yet. Create one on the left.
            </div>
            @endforelse

            @if($announcements->hasPages())
            <div class="pagination-wrap">
                {{ $announcements->links() }}
            </div>
            @endif
        </div>


    </div>
</div>

@endsection