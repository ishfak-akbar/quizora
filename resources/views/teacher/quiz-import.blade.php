@extends('layouts.teacher')
@section('title', 'Quizora — Import Quiz')
@section('page-title', 'Import Quiz')
@section('page-subtitle', 'Import quiz questions and options using a CSV file.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('teacher.css') }}">
@endpush

@section('content')

{{-- IMPORT HERO --}}
<div class="import-hero">
    <div class="import-icon"><i class="ti ti-file-upload"></i></div>
    <h2>Upload your CSV file</h2>
    <p>Each row becomes a question. Make sure your columns match the required format below.</p>

    <form method="POST" action="{{ route('teacher.quiz.import-csv') }}" enctype="multipart/form-data" class="import-form" id="importForm">
        @csrf

        <input type="text" name="title" class="input" placeholder="Quiz title *" required
            style="background:rgba(255,255,255,0.12); border:1.5px solid rgba(255,255,255,0.3); border-radius:12px; padding:13px 16px; color:#fff; font-size:13px; font-family:var(--font); outline:none;">

        <div class="import-dropzone" id="dropzone">
            <label for="csvFile" style="cursor:pointer; display:block;" id="dropzoneLabel">
                <i class="ti ti-cloud-upload"></i>
                <span id="dropzoneText">Click to browse or drag & drop your CSV here</span>
            </label>
            <div class="filename" id="fileName">
                <span id="fileNameText"></span>
                <button type="button" class="filename-remove-btn" id="removeFileBtn" title="Remove file">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="hint">.csv files only</div>
            <input type="file" name="csv_file" id="csvFile" accept=".csv" required style="display:none;">
        </div>

        <div class="import-actions">
            <button type="submit" class="import-submit">
                <i class="ti ti-upload"></i> Import Quiz
            </button>
            <a href="{{ route('teacher.quiz.csv-template') }}" class="import-template-link">
                <i class="ti ti-download"></i> Template
            </a>
        </div>
    </form>

    @error('title')
    <div class="import-error">{{ $message }}</div>
    @enderror

    @error('csv_file')
    <div class="import-error">{{ $message }}</div>
    @enderror

    @if(session('error'))
    <div class="import-error">{{ session('error') }}</div>
    @endif

    @if(session('success'))
    <div class="import-success">{{ session('success') }}</div>
    @endif
</div>

{{-- FORMAT GUIDE --}}
<div class="section-title" style="font-size:14px;font-weight:700;color:#fff;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
    <i class="ti ti-table" style="color:var(--color-primary-glow);"></i>
    Expected CSV Columns
</div>

<div class="guide-grid">
    <div class="guide-card">
        <div class="col-name">question</div>
        <div class="col-desc">The full text of the question.</div>
    </div>
    <div class="guide-card">
        <div class="col-name">option_a – option_d</div>
        <div class="col-desc">The four answer choices for the question.</div>
    </div>
    <div class="guide-card">
        <div class="col-name">correct</div>
        <div class="col-desc">Number (1–4) matching the correct option column.</div>
    </div>
    <div class="guide-card">
        <div class="col-name">marks</div>
        <div class="col-desc">Points awarded for a correct answer.</div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('csvFile');
    const fileNameEl = document.getElementById('fileName');
    const fileNameText = document.getElementById('fileNameText');
    const dropzoneText = document.getElementById('dropzoneText');
    const removeFileBtn = document.getElementById('removeFileBtn');

    function showSelectedFile(name) {
        fileNameText.textContent = name;
        fileNameEl.style.display = 'flex';
        dropzoneText.style.display = 'none';
    }

    function clearSelectedFile() {
        fileInput.value = '';
        fileNameEl.style.display = 'none';
        dropzoneText.style.display = 'inline';
    }

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) {
            showSelectedFile(fileInput.files[0].name);
        }
    });

    removeFileBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        clearSelectedFile();
    });

    ['dragover', 'dragenter'].forEach(evt => {
        dropzone.addEventListener(evt, (e) => {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(evt => {
        dropzone.addEventListener(evt, (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
        });
    });

    dropzone.addEventListener('drop', (e) => {
        const file = e.dataTransfer.files[0];
        if (file) {
            fileInput.files = e.dataTransfer.files;
            showSelectedFile(file.name);
        }
    });
</script>
@endpush