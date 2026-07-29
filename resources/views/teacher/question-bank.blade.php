@extends('layouts.teacher')
@section('title', 'Quizora — Question Bank')

@push('styles')
    <link rel="stylesheet" href="{{ asset('teacher.css') }}">
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