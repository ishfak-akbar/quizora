@extends('layouts.teacher')
@section('title', 'Quizora — Question Bank')

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
        grid-template-columns: repeat(3, 1fr);
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
        max-width: 360px;
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

    .select-toggle-btn {
        margin-left: auto;
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
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .select-toggle-btn:hover,
    .select-toggle-btn.active {
        background: rgba(79, 70, 229, 0.15);
        border-color: rgba(79, 70, 229, 0.4);
        color: #fff;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--color-primary-solid);
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 10px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-family: var(--font);
        transition: background 0.2s;
    }

    .btn-primary:hover {
        background: #4338CA;
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.06);
        color: var(--color-text-secondary);
        border: 1px solid var(--color-border-light);
        padding: 9px 16px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        font-family: var(--font);
    }

    .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #EF4444;
        color: #fff;
        border: none;
        padding: 9px 16px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        font-family: var(--font);
    }

    .bank-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .bank-row {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        padding: 16px 20px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        transition: all 0.15s;
        cursor: default;
    }

    .bank-row.selectable {
        cursor: pointer;
        border-style: dashed;
        border-color: rgba(79, 70, 229, 0.35);
    }

    .bank-row.selectable:hover {
        border-color: rgba(79, 70, 229, 0.35);
    }

    .bank-row.selected {
        background: rgba(79, 70, 229, 0.12);
        border-color: var(--color-primary-solid);
    }

    .bank-q-text {
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        margin-bottom: 6px;
    }

    .bank-meta {
        font-size: 11px;
        color: var(--color-text-muted);
        margin-bottom: 10px;
    }

    .bank-opts {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 5px 20px;
        font-size: 13px;
        color: var(--color-text-secondary);
    }

    .bank-opts .correct {
        color: var(--color-status-success);
        font-weight: 600;
    }

    .bank-row-actions {
        flex-shrink: 0;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--color-border-light);
        color: var(--color-text-secondary);
        cursor: pointer;
        transition: all 0.2s;
    }

    .action-btn.danger:hover {
        background: rgba(248, 113, 113, 0.15);
        color: var(--color-status-error);
        border-color: rgba(248, 113, 113, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--color-text-muted);
    }

    .empty-state i {
        font-size: 48px;
        display: block;
        margin-bottom: 16px;
        color: rgba(79, 70, 229, 0.3);
    }

    .empty-state h3 {
        font-size: 16px;
        font-weight: 600;
        color: #9ca3af;
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 13px;
    }

    /* FLOATING SELECTION BAR */
    .selection-bar {
        position: fixed;
        bottom: 28px;
        left: 50%;
        transform: translateX(-50%);
        background: #1e1a3e;
        border: 1px solid rgba(79, 70, 229, 0.4);
        padding: 14px 20px;
        border-radius: 14px;
        font-size: 13px;
        display: none;
        align-items: center;
        gap: 14px;
        z-index: 9999;
        backdrop-filter: blur(12px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        white-space: nowrap;
    }

    .selection-bar.visible {
        display: flex;
    }

    .selection-count {
        color: #fff;
        font-weight: 600;
    }

    /* MODAL */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-overlay.open {
        display: flex;
    }

    .modal {
        background: var(--color-bg-card);
        border: 1px solid var(--color-border-light);
        border-radius: 16px;
        padding: 26px;
        max-width: 520px;
        width: 92%;
        max-height: 88vh;
        overflow-y: auto;
    }

    .modal h2 {
        font-size: 16px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 16px;
    }

    .field {
        margin-bottom: 16px;
    }

    .field label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: var(--color-text-muted);
        letter-spacing: 0.6px;
        text-transform: uppercase;
        margin-bottom: 7px;
    }

    .input,
    textarea.input {
        width: 100%;
        background: rgba(255, 255, 255, 0.05);
        border: 1.5px solid var(--color-border-light);
        border-radius: 10px;
        padding: 10px 14px;
        color: #fff;
        font-size: 13px;
        font-family: var(--font);
        outline: none;
    }

    textarea.input {
        resize: vertical;
        min-height: 64px;
    }

    .row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .opt-row {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 10px;
    }

    .modal-footer {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 8px;
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div>
        <h1>Question Bank</h1>
        <p>Save questions once, reuse them across any quiz</p>
    </div>
    <button class="btn-primary" onclick="document.getElementById('addModal').classList.add('open')">
        <i class="ti ti-plus"></i> Add Question
    </button>
</div>
<div class="filters">
    <form method="GET" class="search-wrap">
        <i class="ti ti-search"></i>
        <input type="text" name="search" placeholder="Search questions..." value="{{ request('search') }}" onchange="this.form.submit()">
    </form>
    <div id="categoryFilterWrap" style="min-width:180px;"></div>
    <button type="button" class="select-toggle-btn" id="selectModeBtn" onclick="toggleSelectMode()">
        <i class="ti ti-checkbox" id="selectModeIcon"></i> <span id="selectModeLabel">Select Multiple</span>
    </button>
</div>

@if($questions->isEmpty())
<div class="empty-state">
    <i class="ti ti-database-off"></i>
    <h3>No saved questions yet</h3>
    <p>Add your first question to start building your reusable bank</p>
</div>
@else
<div class="bank-list" id="bankList">
    @foreach($questions as $q)
    <div class="bank-row" data-id="{{ $q->id }}" data-category="{{ $q->category }}" onclick="handleRowClick(event, this)">
        <div style="flex:1;">
            <div class="bank-q-text">{{ $q->question_text }}</div>
            <div class="bank-meta">{{ $q->category ?? 'Uncategorized' }} · {{ $q->marks }} mark(s){{ $q->tags ? ' · ' . $q->tags : '' }}</div>
            <div class="bank-opts">
                @foreach($q->options as $i => $opt)
                <div class="{{ $opt->is_correct ? 'correct' : '' }}">
                    {{ chr(65+$i) }}. {{ $opt->option_text }} {{ $opt->is_correct ? '✓' : '' }}
                </div>
                @endforeach
            </div>
        </div>
        <div class="bank-row-actions">
            <button class="action-btn danger" title="Delete" onclick="event.stopPropagation(); openSingleDelete({{ $q->id }})">
                <i class="ti ti-trash"></i>
            </button>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- FLOATING SELECTION BAR --}}
<div class="selection-bar" id="selectionBar">
    <span class="selection-count" id="selectionCount">0 selected</span>
    <button class="btn-secondary" onclick="selectAll()">Select All</button>
    <button class="btn-primary" onclick="useSelectedInQuiz()"><i class="ti ti-plus"></i> Use in Quiz</button>
    <button class="btn-danger" onclick="deleteSelected()"><i class="ti ti-trash"></i> Delete</button>
    <button class="btn-secondary" onclick="cancelSelection()">Cancel</button>
</div>

{{-- ADD QUESTION MODAL --}}
<div class="modal-overlay" id="addModal">
    <div class="modal">
        <h2>Add Question to Bank</h2>
        <form method="POST" action="{{ route('teacher.question-bank.store') }}">
            @csrf
            <div class="field">
                <label>Question Text *</label>
                <textarea class="input" name="question_text" required></textarea>
            </div>
            <div class="row-2">
                <div class="field">
                    <label>Category</label>
                    <input class="input" name="category" placeholder="e.g. Mathematics">
                </div>
                <div class="field">
                    <label>Marks</label>
                    <input type="number" class="input" name="marks" value="1" min="1" required>
                </div>
            </div>
            <div class="field">
                <label>Tags (comma separated)</label>
                <input class="input" name="tags" placeholder="e.g. algebra, geometry">
            </div>
            <div class="field">
                <label>Options — select the correct one</label>
                @foreach(['A','B','C','D'] as $i => $letter)
                <div class="opt-row">
                    <input type="radio" name="correct" value="{{ $i }}" {{ $i == 0 ? 'checked' : '' }} required>
                    <input class="input" name="options[]" placeholder="Option {{ $letter }}" required>
                </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="document.getElementById('addModal').classList.remove('open')">Cancel</button>
                <button type="submit" class="btn-primary">Save Question</button>
            </div>
        </form>
    </div>
</div>

{{-- DELETE CONFIRM MODAL --}}
<div class="modal-overlay" id="deleteModal">
    <div class="modal" style="max-width:420px; text-align:center;">
        <h2 id="deleteModalTitle">Delete Question?</h2>
        <p style="font-size:13px; color:var(--color-text-muted); margin-bottom:20px;" id="deleteModalDesc">
            This will permanently remove it from your bank.
        </p>
        <div class="modal-footer" style="justify-content:center;">
            <button class="btn-secondary" onclick="document.getElementById('deleteModal').classList.remove('open')">Cancel</button>
            <button class="btn-danger" onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let selectMode = false;
    let selectedIds = new Set();
    let deleteTarget = null; // single id or '__bulk__'
    
    if (new URLSearchParams(window.location.search).get('pick_for_quiz')) {
        toggleSelectMode();
    }

    createCustomSelect(
        document.getElementById('categoryFilterWrap'),
        [{
                value: 'all',
                label: 'All Categories'
            },
            @foreach($categories as $cat) {
                value: '{{ $cat }}',
                label: @json($cat)
            },
            @endforeach
        ],
        '{{ request("category", "All Categories") }}',
        (val) => {
            const url = new URL(window.location);
            url.searchParams.set('category', val);
            window.location = url;
        }
    );

    function toggleSelectMode() {
        selectMode = !selectMode;
        document.getElementById('selectModeBtn').classList.toggle('active', selectMode);
        document.getElementById('selectModeLabel').textContent = selectMode ? 'Cancel Selecting' : 'Select Multiple';
        document.getElementById('selectModeIcon').className = selectMode ? 'ti ti-x' : 'ti ti-checkbox';
        document.querySelectorAll('.bank-row').forEach(row => row.classList.toggle('selectable', selectMode));
        if (!selectMode) cancelSelection();
    }

    function handleRowClick(e, row) {
        if (!selectMode) return;
        const id = row.dataset.id;
        if (selectedIds.has(id)) {
            selectedIds.delete(id);
            row.classList.remove('selected');
        } else {
            selectedIds.add(id);
            row.classList.add('selected');
        }
        updateSelectionBar();
    }

    function selectAll() {
        document.querySelectorAll('.bank-row').forEach(row => {
            selectedIds.add(row.dataset.id);
            row.classList.add('selected');
        });
        updateSelectionBar();
    }

    function cancelSelection() {
        selectedIds.clear();
        document.querySelectorAll('.bank-row').forEach(row => row.classList.remove('selected'));
        updateSelectionBar();
    }

    function updateSelectionBar() {
        const bar = document.getElementById('selectionBar');
        bar.classList.toggle('visible', selectedIds.size > 0);
        document.getElementById('selectionCount').textContent = `${selectedIds.size} selected`;
    }

    function openSingleDelete(id) {
        deleteTarget = String(id);
        document.getElementById('deleteModalTitle').textContent = 'Delete Question?';
        document.getElementById('deleteModalDesc').textContent = 'This will permanently remove it from your bank.';
        document.getElementById('deleteModal').classList.add('open');
    }

    function deleteSelected() {
        deleteTarget = '__bulk__';
        document.getElementById('deleteModalTitle').textContent = `Delete ${selectedIds.size} Questions?`;
        document.getElementById('deleteModalDesc').textContent = 'This will permanently remove all selected questions from your bank.';
        document.getElementById('deleteModal').classList.add('open');
    }

    function confirmDelete() {
        if (deleteTarget === '__bulk__') {
            fetch("{{ route('teacher.question-bank.bulk-delete') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        ids: Array.from(selectedIds)
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        selectedIds.forEach(id => document.querySelector(`.bank-row[data-id="${id}"]`)?.remove());
                        cancelSelection();
                        checkEmpty();
                    }
                })
                .catch(() => alert('Failed to delete selected questions.'));
        } else if (deleteTarget) {
            fetch(`/teacher/question-bank/${deleteTarget}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector(`.bank-row[data-id="${deleteTarget}"]`)?.remove();
                        deleteTarget = null;
                        checkEmpty();
                    }
                })
                .catch(() => alert('Failed to delete question.'));
        }
        document.getElementById('deleteModal').classList.remove('open');
    }

    function useSelectedInQuiz() {
        sessionStorage.setItem('bank_import_ids', JSON.stringify(Array.from(selectedIds)));
        window.location.href = "{{ route('teacher.quiz.create') }}?import_from_bank=1";
    }

    function checkEmpty() {
        if (document.querySelectorAll('.bank-row').length === 0) {
            document.getElementById('bankList')?.remove();
            document.querySelector('.filters').insertAdjacentHTML('afterend', `
                <div class="empty-state">
                    <i class="ti ti-database-off"></i>
                    <h3>No saved questions yet</h3>
                    <p>Add your first question to start building your reusable bank</p>
                </div>`);
        }
    }
</script>
@endpush