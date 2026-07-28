@extends('layouts.teacher')
@section('title', 'Quizora — Import Quiz')

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

    .import-hero {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 28px 24px;
        background: linear-gradient(135deg, #2E2570 0%, #4F46E5 50%, #818CF8 100%);
        border-radius: 18px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }

    .import-hero::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
    }

    .import-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: #fff;
        margin-bottom: 18px;
        position: relative;
        z-index: 1;
    }

    .import-hero h2 {
        font-size: 20px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 6px;
        position: relative;
        z-index: 1;
    }

    .import-hero p {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.75);
        margin-bottom: 24px;
        max-width: 580px;
        position: relative;
        z-index: 1;
    }

    .import-form {
        display: flex;
        flex-direction: column;
        gap: 14px;
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 420px;
    }

    .import-dropzone {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(8px);
        border: 1.5px dashed rgba(255, 255, 255, 0.35);
        border-radius: 12px;
        padding: 22px 16px;
        color: #fff;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }

    .import-dropzone:hover,
    .import-dropzone.dragover {
        background: rgba(255, 255, 255, 0.18);
        border-color: rgba(255, 255, 255, 0.6);
    }

    .import-dropzone i {
        font-size: 26px;
        display: block;
        margin-bottom: 8px;
    }

    .import-dropzone .filename {
        font-size: 12.5px;
        font-weight: 600;
        margin-top: 8px;
        color: #fff;
    }

    .import-dropzone .hint {
        font-size: 11.5px;
        color: rgba(255, 255, 255, 0.6);
        margin-top: 4px;
    }

    .import-actions {
        display: flex;
        gap: 10px;
    }

    .import-submit {
        flex: 1;
        background: #fff;
        color: var(--color-primary-solid);
        border: none;
        font-size: 13px;
        font-weight: 700;
        padding: 13px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .import-submit:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-1px);
    }

    .import-template-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        padding: 13px 16px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        white-space: nowrap;
        transition: all 0.2s;
    }

    .import-template-link:hover {
        background: rgba(255, 255, 255, 0.12);
    }

    .import-error,
    .import-success {
        font-size: 12.5px;
        font-weight: 600;
        padding: 10px 16px;
        border-radius: 10px;
        margin-top: 4px;
        position: relative;
        z-index: 1;
        max-width: 420px;
    }

    .import-error {
        color: #FECACA;
        background: rgba(248, 113, 113, 0.2);
        border: 1px solid rgba(248, 113, 113, 0.4);
    }

    .import-success {
        color: #A7F3D0;
        background: rgba(52, 211, 153, 0.2);
        border: 1px solid rgba(52, 211, 153, 0.4);
    }

    .guide-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
    }

    .guide-card {
        background: linear-gradient(180deg, rgba(79, 70, 229, 0.45) 0%, rgba(79, 70, 229, 0.10) 45%, var(--color-bg-card) 100%);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        padding: 16px 18px;
        position: relative;
        overflow: hidden;
        transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
    }

    .guide-card:hover {
        border-color: rgba(129, 140, 248, 0.45);
        transform: translateY(-3px);
        box-shadow:
            0 0 0 1px rgba(129, 140, 248, 0.10),
            0 10px 26px rgba(79, 70, 229, 0.20);
        cursor: pointer;
    }

    .guide-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(360px circle at var(--mx, 50%) var(--my, 0%), rgba(129, 140, 248, 0.14), transparent 60%);
        opacity: 0;
        transition: opacity 0.25s;
        pointer-events: none;
    }

    .guide-card:hover::after {
        opacity: 1;
    }

    .guide-card .col-name {
        font-size: 12.5px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 4px;
        position: relative;
        z-index: 1;
    }

    .guide-card .col-desc {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.65);
        line-height: 1.5;
        position: relative;
        z-index: 1;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Import Quiz</h1>
        <p>Bulk-create a quiz from a CSV file instead of adding questions one by one</p>
    </div>
</div>

{{-- IMPORT HERO --}}
<div class="import-hero">
    <div class="import-icon"><i class="ti ti-file-upload"></i></div>
    <h2>Upload your CSV file</h2>
    <p>Each row becomes a question. Make sure your columns match the required format below.</p>

    <form method="POST" action="{{ route('teacher.quiz.import-csv') }}" enctype="multipart/form-data" class="import-form" id="importForm">
        @csrf

        <input type="text" name="title" class="input" placeholder="Quiz title *" required
            style="background:rgba(255,255,255,0.12); border:1.5px solid rgba(255,255,255,0.3); border-radius:12px; padding:13px 16px; color:#fff; font-size:13px; font-family:var(--font); outline:none;">

        <label class="import-dropzone" id="dropzone" for="csvFile">
            <i class="ti ti-cloud-upload"></i>
            <span>Click to browse or drag & drop your CSV here</span>
            <div class="filename" id="fileName"></div>
            <div class="hint">.csv files only</div>
            <input type="file" name="csv_file" id="csvFile" accept=".csv" required style="display:none;">
        </label>

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

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) {
            fileNameEl.textContent = fileInput.files[0].name;
        }
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
            fileNameEl.textContent = file.name;
        }
    });
</script>
@endpush