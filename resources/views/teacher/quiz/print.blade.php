<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('teacher.css') }}">
    <title>{{ $quiz->title }} — Question Paper</title>
    @stack('styles')
</head>

<body class="print-body">

    <div class="toolbar no-print">
        @if(!$includeAnswers)
        <a href="{{ route('teacher.quiz.print', $quiz->id) }}?with_answers=1" class="secondary">Show Answer Key</a>
        @else
        <a href="{{ route('teacher.quiz.print', $quiz->id) }}" class="secondary">Hide Answer Key</a>
        @endif
        <button onclick="window.print()">🖨️ Print Paper</button>
    </div>

    <div class="paper">

        <!-- Header Section -->
        <div class="print-header">
            <div class="institution">Quizora Examination System</div>
            <h1>{{ $quiz->title }}</h1>
            <div class="subline">
                Course: <strong>{{ $quiz->category ?? 'General' }}</strong>
            </div>
            <div class="meta-row">
                <span>Time: {{ $quiz->time_limit ? $quiz->time_limit . ' Mins' : 'Unspecified' }}</span>
                <span>Marks: {{ $totalMarks }}</span>
            </div>
        </div>

        <!-- Student Details -->
        <div class="info-fields">
            <div>Name: <span></span></div>
            <div>Roll/ID: <span></span></div>
            <div>Date: <span></span></div>
        </div>

        <!-- Instructions -->
        <div class="instructions">
            <strong>Instructions:</strong> Read all questions carefully before answering. Select the single best option for each question. Marks are indicated against each question.
        </div>

        <!-- Questions Block -->
        @foreach($quiz->questions as $index => $question)
        <div class="question">
            <div class="question-line">
                <span class="q-text">Q{{ $index + 1 }}. {{ $question->question_text }}</span>
                <span class="q-marks">[{{ $question->marks }} {{ Str::plural('mark', $question->marks) }}]</span>
            </div>
            <div class="options-grid">
                @foreach($question->options as $optIndex => $option)
                <div class="option-item">
                    <span class="option {{ $includeAnswers && $option->is_correct ? 'correct' : '' }}">
                        ({{ chr(65 + $optIndex) }}) {{ $option->option_text }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <!-- Optional Teacher Answer Key -->
        @if($includeAnswers)
        <div class="answer-key">
            <h2>Confidential Answer Key (Teacher's Copy)</h2>
            <div class="answer-key-grid">
                @foreach($quiz->questions as $index => $question)
                @php
                $correctOpt = $question->options->firstWhere('is_correct', true);
                $correctLetter = $correctOpt ? chr(65 + $question->options->search(fn($o) => $o->id === $correctOpt->id)) : '-';
                @endphp
                <div class="answer-key-card">
                    <strong>Q{{ $index + 1 }}:</strong> {{ $correctLetter }}
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

</body>

</html>