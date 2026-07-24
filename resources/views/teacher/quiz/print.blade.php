<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quiz->title }} — Question Paper</title>
    <style>
        /* Base Page Setup */
        @page {
            size: A4;
            margin: 15mm 15mm 18mm 15mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Georgia, serif;
            color: #111;
            font-size: 13px;
            line-height: 1.5;
            background-color: #f8fafc;
        }

        /* Interactive Toolbar (Screen Only) */
        .toolbar {
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .toolbar button,
        .toolbar a {
            font-size: 13px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            border: 1px solid #4f46e5;
            background: #4f46e5;
            color: #ffffff;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .toolbar button:hover,
        .toolbar a:hover {
            background: #4338ca;
            border-color: #4338ca;
        }

        .toolbar a.secondary {
            background: #ffffff;
            color: #374151;
            border-color: #d1d5db;
        }

        .toolbar a.secondary:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        /* Paper Container */
        .paper {
            max-width: 210mm;
            margin: 24px auto;
            padding: 12mm 10mm;
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Paper Header */
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .header .institution {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #4b5563;
            margin-bottom: 4px;
        }

        .header h1 {
            font-size: 22px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #000;
            margin-bottom: 4px;
        }

        .header .subline {
            font-size: 12px;
            color: #4b5563;
            margin-bottom: 10px;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            font-weight: 700;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            margin-top: 6px;
        }

        /* Student Info Line */
        .info-fields {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin: 16px 0;
            font-size: 12.5px;
            font-weight: 600;
        }

        .info-fields div {
            flex: 1;
            display: flex;
            align-items: flex-end;
        }

        .info-fields span {
            flex-grow: 1;
            border-bottom: 1px dotted #4b5563;
            margin-left: 6px;
        }

        /* Instructions Box */
        .instructions {
            font-size: 11.5px;
            font-style: italic;
            color: #1f2937;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-left: 3px solid #111827;
            padding: 8px 12px;
            margin-bottom: 20px;
            border-radius: 0 4px 4px 0;
        }

        /* Question Block */
        .question {
            margin-bottom: 16px;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .question-line {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            font-weight: 700;
            font-size: 13.5px;
            line-height: 1.4;
        }

        .question-line .q-text {
            flex: 1;
        }

        .question-line .q-marks {
            font-weight: 600;
            font-size: 12px;
            white-space: nowrap;
            color: #4b5563;
        }

        /* Options Grid */
        .options-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px 24px;
            margin-top: 8px;
            padding-left: 20px;
            font-size: 13px;
        }

        .option-item {
            display: flex;
            align-items: baseline;
            gap: 6px;
        }

        .option.correct {
            font-weight: 700;
            color: #065f46;
            background-color: #ecfdf5;
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Answer Key Section */
        .answer-key {
            margin-top: 36px;
            border-top: 2px dashed #000;
            padding-top: 16px;
            break-before: auto;
        }

        .answer-key h2 {
            font-size: 13px;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            color: #111827;
        }

        .answer-key-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 6px;
            font-size: 12px;
            text-align: center;
        }

        .answer-key-card {
            border: 1px solid #e5e7eb;
            padding: 4px 2px;
            background: #f9fafb;
            border-radius: 4px;
        }

        /* Print Specific Media Styles */
        @media print {
            body {
                background: #ffffff;
                font-size: 12px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .toolbar {
                display: none !important;
            }

            .paper {
                margin: 0;
                padding: 0;
                max-width: none;
                box-shadow: none;
            }

            .instructions {
                background-color: transparent;
                border-top: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
                border-left: none;
                border-right: none;
                border-radius: 0;
                padding: 6px 0;
            }

            .option.correct {
                background-color: transparent;
                color: #000;
                text-decoration: underline;
            }

            .answer-key-card {
                border: 1px solid #ccc;
                background: transparent;
            }
        }
    </style>
</head>

<body>

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
        <div class="header">
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