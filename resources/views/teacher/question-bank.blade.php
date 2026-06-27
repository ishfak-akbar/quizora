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

    .filter-select {
        height: 38px;
        padding: 0 14px;
        border-radius: 10px;
        border: 1.5px solid var(--color-border-light);
        background: var(--color-bg-card);
        color: var(--color-text-secondary);
        font-size: 13px;
        font-family: var(--font);
        cursor: pointer;
        outline: none;
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

    .q-text {
        font-size: 13px;
        font-weight: 600;
        color: #fff;
        margin-bottom: 3px;
    }

    .q-options {
        font-size: 11px;
        color: var(--color-text-muted);
    }

    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
    }

    .type-mcq {
        background: rgba(79, 70, 229, 0.15);
        color: var(--color-primary-glow);
    }

    .type-tf {
        background: rgba(34, 211, 238, 0.15);
        color: #22D3EE;
    }

    .type-short {
        background: rgba(245, 158, 11, 0.15);
        color: #F59E0B;
    }

    .points-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        background: rgba(167, 139, 250, 0.15);
        color: var(--color-stat-purple);
    }

    .bulk-bar {
        display: none;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        background: rgba(79, 70, 229, 0.1);
        border-bottom: 1px solid rgba(79, 70, 229, 0.2);
        font-size: 13px;
    }

    .bulk-bar.visible {
        display: flex;
    }

    .bulk-count {
        font-weight: 600;
        color: var(--color-primary-glow);
    }

    .btn-danger-sm {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(248, 113, 113, 0.15);
        color: var(--color-status-error);
        border: 1px solid rgba(248, 113, 113, 0.3);
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        font-family: var(--font);
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-danger-sm:hover {
        background: rgba(248, 113, 113, 0.25);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: transparent;
        color: var(--color-text-secondary);
        border: 1px solid var(--color-border-light);
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        font-family: var(--font);
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-secondary:hover {
        background: var(--color-bg-row-hover);
        color: #fff;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--color-primary-solid);
        color: #fff;
        border: none;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        font-family: var(--font);
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-primary:hover {
        background: #4338CA;
    }

    .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(248, 113, 113, 0.15);
        color: var(--color-status-error);
        border: 1px solid rgba(248, 113, 113, 0.3);
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        font-family: var(--font);
        cursor: pointer;
    }

    .btn-danger:hover {
        background: rgba(248, 113, 113, 0.25);
    }

    .action-btn.danger:hover {
        background: rgba(248, 113, 113, 0.15);
        color: var(--color-status-error);
        border-color: rgba(248, 113, 113, 0.3);
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
        width: 500px;
        max-width: 95vw;
        max-height: 90vh;
        overflow-y: auto;
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
    }

    .modal-close:hover {
        color: #fff;
    }

    .modal-footer {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--color-border-light);
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--color-text-muted);
        margin-bottom: 7px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        background: rgba(255, 255, 255, 0.05);
        border: 1.5px solid var(--color-border-light);
        border-radius: 10px;
        color: #fff;
        font-size: 13px;
        font-family: var(--font);
        padding: 10px 14px;
        outline: none;
        transition: border-color 0.2s;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: rgba(79, 70, 229, 0.6);
    }

    .form-textarea {
        min-height: 80px;
        resize: vertical;
    }

    .options-builder {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 10px;
    }

    .option-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .option-row input[type="radio"] {
        flex-shrink: 0;
        accent-color: var(--color-primary-solid);
        width: 16px;
        height: 16px;
    }

    .option-row .form-input {
        flex: 1;
    }

    .option-remove {
        background: transparent;
        border: none;
        color: var(--color-text-muted);
        cursor: pointer;
        font-size: 16px;
        padding: 4px;
        display: flex;
        align-items: center;
    }

    .option-remove:hover {
        color: var(--color-status-error);
    }

    .add-option-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: transparent;
        border: 1px dashed var(--color-border-light);
        color: var(--color-text-muted);
        font-size: 12px;
        font-weight: 500;
        padding: 7px 14px;
        border-radius: 8px;
        cursor: pointer;
        font-family: var(--font);
        transition: all 0.2s;
    }

    .add-option-btn:hover {
        border-color: rgba(79, 70, 229, 0.4);
        color: var(--color-primary-glow);
    }

    .delete-modal {
        text-align: center;
        max-width: 380px;
    }

    .delete-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: rgba(248, 113, 113, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: var(--color-status-error);
        margin: 0 auto 16px;
    }

    .delete-title {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 8px;
    }

    .delete-desc {
        font-size: 13px;
        color: var(--color-text-muted);
        margin-bottom: 24px;
    }

    .delete-footer {
        display: flex;
        gap: 10px;
        justify-content: center;
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div>
        <h1>Question Bank</h1>
        <p>All questions across your quizzes in one place</p>
    </div>
    <button class="btn-primary" onclick="openAddModal()"><i class="ti ti-plus"></i> Add Question</button>
</div>

@php
use App\Models\Question;
$allQuestions = Question::whereHas('quiz', fn($q) => $q->where('teacher_id', auth()->id()))
->with('quiz')->get();
$totalQ = $allQuestions->count();
$mcqCount = $allQuestions->where('type','mcq')->count();
$tfCount = $allQuestions->where('type','true_false')->count();
$shortCount = $allQuestions->where('type','short_answer')->count();
$quizNames = $allQuestions->pluck('quiz.title','quiz_id')->unique();
@endphp

<div class="stats-row">
    <div class="stat-card purple">
        <div class="stat-value">{{ $totalQ }}</div>
        <div class="stat-label">Total Questions</div>
    </div>
    <div class="stat-card cyan">
        <div class="stat-value">{{ $mcqCount }}</div>
        <div class="stat-label">MCQ</div>
    </div>
    <div class="stat-card green">
        <div class="stat-value">{{ $tfCount }}</div>
        <div class="stat-label">True / False</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-value">{{ $shortCount }}</div>
        <div class="stat-label">Short Answer</div>
    </div>
</div>

<div class="filters">
    <div class="search-wrap">
        <i class="ti ti-search"></i>
        <input type="text" id="searchInput" placeholder="Search questions...">
    </div>
    <select class="filter-select" id="quizFilter" onchange="applyFilters()">
        <option value="all">All Quizzes</option>
        @foreach($quizNames as $qId => $qTitle)
        <option value="{{ $qId }}">{{ $qTitle }}</option>
        @endforeach
    </select>
    <select class="filter-select" id="typeFilter" onchange="applyFilters()">
        <option value="all">All Types</option>
        <option value="mcq">MCQ</option>
        <option value="true_false">True / False</option>
        <option value="short_answer">Short Answer</option>
    </select>
</div>

<div class="table-card">
    <div class="bulk-bar" id="bulkBar">
        <span class="bulk-count" id="bulkCount">0 selected</span>
        <button class="btn-danger-sm" onclick="openBulkDelete()"><i class="ti ti-trash"></i> Delete Selected</button>
        <button class="btn-secondary" onclick="clearSelection()">Cancel</button>
    </div>
    <table id="questionsTable">
        <thead>
            <tr>
                <th style="width:40px;"><input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)"></th>
                <th>Question</th>
                <th>Type</th>
                <th>Quiz</th>
                <th>Points</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="questionsBody">
            @forelse($allQuestions as $question)
            @php
            $typeClass = ['mcq'=>'type-mcq','true_false'=>'type-tf','short_answer'=>'type-short'][$question->type] ?? 'type-mcq';
            $typeLabel = ['mcq'=>'MCQ','true_false'=>'True/False','short_answer'=>'Short Answer'][$question->type] ?? $question->type;
            $typeIcon = ['mcq'=>'ti-list-check','true_false'=>'ti-toggle-left','short_answer'=>'ti-writing'][$question->type] ?? 'ti-help-circle';
            $optionsPreview = $question->options->pluck('option_text')->take(4)->implode(' · ');
            @endphp
            <tr data-type="{{ $question->type }}" data-quiz="{{ $question->quiz_id }}" data-q="{{ strtolower($question->question_text) }}">
                <td><input type="checkbox" class="row-check" onchange="updateBulkBar()"></td>
                <td>
                    <div class="q-text">{{ Str::limit($question->question_text, 80) }}</div>
                    @if($optionsPreview)
                    <div class="q-options">{{ $optionsPreview }}</div>
                    @endif
                </td>
                <td><span class="type-badge {{ $typeClass }}"><i class="ti {{ $typeIcon }}"></i> {{ $typeLabel }}</span></td>
                <td style="color:#fff;font-size:12px;">{{ $question->quiz->title ?? '—' }}</td>
                <td><span class="points-pill"><i class="ti ti-star-filled" style="font-size:11px;"></i> {{ $question->marks }}</span></td>
                <td>
                    <div class="action-btns">
                        <button class="action-btn danger" title="Delete" onclick="openDeleteModal(this)"><i class="ti ti-trash"></i></button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="empty-state"><i class="ti ti-database-off"></i>
                        <p>No questions yet. Create a quiz to add questions.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="empty-state" id="emptyState" style="display:none;">
        <i class="ti ti-database-off"></i>
        <p>No questions match your filters.</p>
    </div>
</div>

@endsection

@push('scripts')

{{-- DELETE MODAL --}}
<div class="modal-overlay" id="deleteModal">
    <div class="modal delete-modal">
        <div class="delete-icon"><i class="ti ti-trash"></i></div>
        <div class="delete-title">Delete Question?</div>
        <div class="delete-desc" id="deleteDesc">This question will be permanently removed.</div>
        <div class="delete-footer">
            <button class="btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
            <button class="btn-danger" onclick="confirmDelete()"><i class="ti ti-trash"></i> Delete</button>
        </div>
    </div>
</div>

<script>
    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
    }
    document.querySelectorAll('.modal-overlay').forEach(o => o.addEventListener('click', e => {
        if (e.target === o) o.classList.remove('open');
    }));

    let rowToDelete = null;

    function openDeleteModal(btn) {
        rowToDelete = btn.closest('tr');
        document.getElementById('deleteModal').classList.add('open');
    }

    function confirmDelete() {
        if (rowToDelete === '__bulk__') {
            document.querySelectorAll('.row-check:checked').forEach(cb => cb.closest('tr').remove());
            clearSelection();
        } else if (rowToDelete) {
            rowToDelete.remove();
            rowToDelete = null;
        }
        closeModal('deleteModal');
        checkEmpty();
    }

    function openBulkDelete() {
        rowToDelete = '__bulk__';
        document.getElementById('deleteModal').classList.add('open');
    }

    function toggleSelectAll(master) {
        document.querySelectorAll('.row-check').forEach(cb => {
            if (cb.closest('tr').style.display !== 'none') cb.checked = master.checked;
        });
        updateBulkBar();
    }

    function updateBulkBar() {
        const checked = document.querySelectorAll('.row-check:checked').length;
        document.getElementById('bulkBar').classList.toggle('visible', checked > 0);
        document.getElementById('bulkCount').textContent = `${checked} selected`;
    }

    function clearSelection() {
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = false);
        document.getElementById('selectAll').checked = false;
        updateBulkBar();
    }

    document.getElementById('searchInput').addEventListener('input', applyFilters);

    function applyFilters() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        const quiz = document.getElementById('quizFilter').value;
        const type = document.getElementById('typeFilter').value;
        document.querySelectorAll('#questionsBody tr').forEach(row => {
            if (!row.dataset.q) return;
            const match = (!q || row.dataset.q.includes(q)) &&
                (quiz === 'all' || row.dataset.quiz === quiz) &&
                (type === 'all' || row.dataset.type === type);
            row.style.display = match ? '' : 'none';
        });
        clearSelection();
        checkEmpty();
    }

    function checkEmpty() {
        const visible = [...document.querySelectorAll('#questionsBody tr')].filter(r => r.style.display !== 'none' && r.dataset.q);
        document.getElementById('emptyState').style.display = visible.length === 0 ? '' : 'none';
    }
</script>
@endpush