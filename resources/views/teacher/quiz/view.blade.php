@extends('layouts.teacher')
@section('title', 'Quizora — ' . $quiz->title)
@section('page-title', 'Quiz Details')
@section('page-subtitle', 'View the questions, settings, and information of this quiz.')

@push('styles')
<link rel="stylesheet" href="{{ asset('teacher.css') }}">
@endpush

@section('content')

<div class="qv-layout">


    <div class="qv-hero">
        <div class="qv-hero-top">
            <div class="qv-hero-pill"><i class="ti ti-file-description"></i> Quiz Overview</div>
            <div class="qv-hero-actions">
                <a href="javascript:history.back()" class="qv-hero-btn ghost"><i class="ti ti-arrow-left"></i> Back</a>
                <a href="{{ route('teacher.quiz.edit', $quiz->id) }}" class="qv-hero-btn ghost"><i class="ti ti-edit"></i> Edit</a>
                <a href="{{ route('teacher.quiz.print', $quiz->id) }}" target="_blank" class="qv-hero-btn solid"><i class="ti ti-printer"></i> Print</a>
            </div>
        </div>

        <h1>{{ $quiz->title }}</h1>
        <div class="qv-hero-meta">
            <span><i class="ti ti-tag"></i> {{ $quiz->category ?? 'General' }}</span>
            <span><i class="ti ti-gauge"></i> {{ ucfirst($quiz->difficulty) }}</span>
            <span><i class="ti ti-circle-dot"></i> {{ ucfirst($quiz->display_status) }}</span>
        </div>

        @if($quiz->description)
        <div class="qv-hero-desc">
            <div class="qv-hero-desc-label">Overview</div>
            <p>{{ $quiz->description }}</p>
        </div>
        @endif
    </div>
    <div class="qv-stats-card">
        <div class="qv-metric purple">
            <div class="qv-metric-icon"><i class="ti ti-help-circle"></i></div>
            <div>
                <div class="qv-metric-value">{{ $quiz->questions->count() }}</div>
                <div class="qv-metric-label">Questions</div>
            </div>
        </div>
        <div class="qv-metric amber">
            <div class="qv-metric-icon"><i class="ti ti-medal"></i></div>
            <div>
                <div class="qv-metric-value">{{ $totalMarks }}</div>
                <div class="qv-metric-label">Total Marks</div>
            </div>
        </div>
        <div class="qv-metric cyan">
            <div class="qv-metric-icon"><i class="ti ti-clock"></i></div>
            <div>
                <div class="qv-metric-value">{{ $quiz->time_limit ? $quiz->time_limit . ' min' : '—' }}</div>
                <div class="qv-metric-label">Time Limit</div>
            </div>
        </div>
        <div class="qv-metric green">
            <div class="qv-metric-icon"><i class="ti ti-users"></i></div>
            <div>
                <div class="qv-metric-value">{{ $submittedCount }}</div>
                <div class="qv-metric-label">Submissions</div>
            </div>
        </div>
    </div>
</div>

<div class="qv-section-title">
    <h2>Questions</h2>
    <span>{{ $quiz->questions->count() }} total</span>
</div>

@forelse($quiz->questions as $index => $question)
<div class="qv-q-card">
    <div class="qv-q-top">
        <div class="qv-q-badge">{{ $index + 1 }}</div>
        <div class="qv-q-text">{{ $question->question_text }}</div>
        <div class="qv-q-marks">{{ $question->marks }} mark{{ $question->marks > 1 ? 's' : '' }}</div>
    </div>
    <div class="qv-opts">
        @foreach($question->options as $i => $option)
        <div class="qv-opt {{ $option->is_correct ? 'correct' : '' }}">
            <span class="letter">{{ chr(65+$i) }}</span> {{ $option->option_text }}
        </div>
        @endforeach
    </div>
</div>
@empty
<div class="qv-empty">No questions added yet.</div>
@endforelse

@endsection