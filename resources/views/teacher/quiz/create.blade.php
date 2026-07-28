<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('quizora.css') }}">
    <title>Quizora — Create Quiz</title>
    <style>
        :root {
            --color-primary-glow: #818CF8;
            --color-primary-solid: #4F46E5;
            --color-primary-dark: #2E2570;
            --color-bg-main: #0E0B20;
            --color-bg-card: #1E1A3E;
            --color-bg-row-hover: #241E47;
            --color-border-light: rgba(255, 255, 255, 0.08);
            --color-text-primary: #FFFFFF;
            --color-text-secondary: #9CA3AF;
            --color-text-muted: #6B7280;
            --color-status-success: #34D399;
            --color-status-error: #F87171;
            --font: 'Plus Jakarta Sans', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font);
            background: #0E0B20;
            color: var(--color-text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #quizForm {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        /* TOP NAV */
        .topbar {
            height: 64px;
            background: #1e1b45;
            border-bottom: 1px solid var(--color-border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--color-text-secondary);
            font-size: 13px;
            text-decoration: none;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid var(--color-border-light);
            transition: background 0.2s, color 0.2s;
        }

        .back-btn:hover {
            background: var(--color-bg-row-hover);
            color: #fff;
        }

        .topbar-title {
            font-size: 15px;
            font-weight: 600;
            color: #fff;
        }

        /* STEPPER */
        .stepper-wrap {
            max-width: 1000px;
            margin: 0 auto;
            padding: 32px 24px 0;
        }

        .stepper {
            display: flex;
            align-items: center;
            margin-bottom: 36px;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }

        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            border: 2px solid var(--color-border-light);
            color: var(--color-text-muted);
            flex-shrink: 0;
            transition: all 0.3s;
        }

        .step.active .step-circle {
            background: var(--color-primary-solid);
            border-color: var(--color-primary-solid);
            color: #fff;
        }

        .step.done .step-circle {
            background: var(--color-status-success);
            border-color: var(--color-status-success);
            color: #fff;
        }

        .step-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--color-text-muted);
        }

        .step.active .step-label {
            color: #fff;
        }

        .step.done .step-label {
            color: var(--color-status-success);
        }

        .step-line {
            flex: 1;
            height: 2px;
            background: var(--color-border-light);
            margin: 0 12px;
            transition: background 0.3s;
            min-width: 30px;
        }

        .step-line.done {
            background: var(--color-status-success);
        }

        /* CARD */
        .form-card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border-light);
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 20px;
        }

        .form-card h2 {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 6px;
        }

        .form-card p {
            font-size: 13px;
            color: var(--color-text-muted);
            margin-bottom: 24px;
        }

        /* FIELDS */
        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: var(--color-text-muted);
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .input {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1.5px solid var(--color-border-light);
            border-radius: 10px;
            padding: 10px 14px;
            color: #fff;
            font-size: 14px;
            font-family: var(--font);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input:focus {
            border-color: rgba(79, 70, 229, 0.6);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .input::placeholder {
            color: var(--color-text-muted);
        }

        textarea.input {
            resize: vertical;
            min-height: 80px;
        }

        .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* TOGGLE */
        .toggle-field {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--color-border-light);
            border-radius: 10px;
            margin-bottom: 12px;
        }

        .toggle-info {
            font-size: 13px;
            font-weight: 500;
            color: #fff;
        }

        .toggle-info span {
            display: block;
            font-size: 11px;
            color: var(--color-text-muted);
            font-weight: 400;
            margin-top: 2px;
        }

        .toggle {
            width: 40px;
            height: 22px;
            border-radius: 11px;
            background: rgba(255, 255, 255, 0.1);
            position: relative;
            cursor: pointer;
            transition: background 0.25s;
            flex-shrink: 0;
            border: none;
        }

        .toggle.on {
            background: var(--color-primary-solid);
        }

        .toggle::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #fff;
            top: 3px;
            left: 3px;
            transition: left 0.25s;
        }

        .toggle.on::after {
            left: 21px;
        }

        /* QUESTION CARD */
        .question-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--color-border-light);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 14px;
            position: relative;
        }

        .question-card:hover {
            border-color: rgba(79, 70, 229, 0.3);
        }

        .question-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .question-num {
            font-size: 12px;
            font-weight: 700;
            color: var(--color-primary-glow);
            background: rgba(79, 70, 229, 0.15);
            padding: 3px 10px;
            border-radius: 20px;
        }

        .question-actions {
            display: flex;
            gap: 6px;
        }

        .q-action-btn {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            border: 1px solid var(--color-border-light);
            background: transparent;
            color: var(--color-text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.15s, color 0.15s;
        }

        .q-action-btn:hover {
            background: var(--color-bg-row-hover);
            color: #fff;
        }

        .q-action-btn.delete:hover {
            background: rgba(248, 113, 113, 0.15);
            color: var(--color-status-error);
        }

        .options-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 14px;
        }

        .option-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.04);
            border: 1.5px solid var(--color-border-light);
            border-radius: 9px;
            padding: 8px 12px;
            transition: border-color 0.2s;
        }

        .option-wrap.correct {
            border-color: var(--color-status-success);
            background: rgba(52, 211, 153, 0.08);
        }

        .option-radio {
            display: none;
        }

        .option-label {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid var(--color-border-light);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            font-size: 11px;
            font-weight: 700;
            color: var(--color-text-muted);
            transition: all 0.2s;
        }

        .option-wrap.correct .option-label {
            border-color: var(--color-status-success);
            background: var(--color-status-success);
            color: #fff;
        }

        .option-input {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            color: #fff;
            font-size: 13px;
            font-family: var(--font);
        }

        .option-input::placeholder {
            color: var(--color-text-muted);
        }

        /* BOTTOM BAR */
        .bottom-bar {
            position: sticky;
            bottom: 0;
            background: rgba(14, 11, 32, 0.95);
            backdrop-filter: blur(12px);
            border-top: 1px solid var(--color-border-light);
            padding: 10px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            font-family: var(--font);
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--color-primary-solid);
            color: #fff;
        }

        .btn-primary:hover {
            background: #4338CA;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: transparent;
            color: var(--color-text-secondary);
            border: 1px solid var(--color-border-light);
        }

        .btn-secondary:hover {
            background: var(--color-bg-row-hover);
            color: #fff;
        }

        .btn-success {
            background: var(--color-status-success);
            color: #0E0B20;
        }

        .btn-success:hover {
            background: #2EBD87;
        }

        .btn-ghost {
            background: transparent;
            color: var(--color-text-muted);
            border: none;
            font-size: 13px;
        }

        .btn-ghost:hover {
            color: #fff;
        }

        /* REVIEW */
        .review-quiz-info {
            margin-bottom: 20px;
        }

        .review-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--color-border-light);
            font-size: 13px;
        }

        .review-row:last-child {
            border-bottom: none;
        }

        .review-row span:first-child {
            color: var(--color-text-muted);
        }

        .review-row span:last-child {
            color: #fff;
            font-weight: 500;
        }

        .review-question {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--color-border-light);
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 10px;
        }

        .review-question-text {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 10px;
        }

        .review-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .review-option {
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 7px;
            color: var(--color-text-secondary);
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--color-border-light);
        }

        .review-option.correct {
            background: rgba(52, 211, 153, 0.1);
            border-color: var(--color-status-success);
            color: var(--color-status-success);
        }

        .visibility-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 20px;
        }

        .visibility-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1.5px solid var(--color-border-light);
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .visibility-card:hover {
            border-color: rgba(79, 70, 229, 0.4);
        }

        .visibility-card.selected {
            border-color: var(--color-primary-solid);
            background: rgba(79, 70, 229, 0.1);
        }

        .visibility-card-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(79, 70, 229, 0.15);
            color: var(--color-primary-glow);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
            margin-bottom: 12px;
        }

        .visibility-card.selected .visibility-card-icon {
            background: var(--color-primary-solid);
            color: #fff;
        }

        .visibility-card-title {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 4px;
        }

        .visibility-card-desc {
            font-size: 12px;
            color: var(--color-text-muted);
            line-height: 1.5;
        }

        .access-code-display {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(79, 70, 229, 0.08);
            border: 1.5px solid rgba(79, 70, 229, 0.3);
            border-radius: 10px;
            padding: 14px 18px;
        }

        #accessCodeText {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 6px;
            color: var(--color-primary-glow);
            font-family: monospace;
        }

        .code-shuffle-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid var(--color-border-light);
            background: transparent;
            color: var(--color-text-secondary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            transition: background 0.2s, color 0.2s;
        }

        .code-shuffle-btn:hover {
            background: var(--color-bg-row-hover);
            color: #fff;
        }
    </style>
</head>

<body>
    <form id="quizForm" method="POST" action="{{ route('teacher.quiz.store') }}">
        @csrf

        @if ($errors->any())
        <div style="background:#F87171;color:#fff;padding:16px;margin:16px 32px;border-radius:10px;font-size:13px;">
            <strong>Validation failed:</strong>
            <ul style="margin-top:8px;padding-left:18px;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="topbar-left">
                <a href="{{ route('teacher.dashboard') }}" class="back-btn">
                    <i class="ti ti-arrow-left"></i> Back
                </a>
                <div class="topbar-title">Create New Quiz</div>
            </div>
            <div style="font-size:13px;color:var(--color-text-muted);">
                Step <span id="currentStepLabel">1</span> of 4
            </div>
        </div>

        <div class="stepper-wrap">

            <!-- STEPPER -->
            <div class="stepper">
                <div class="step active" id="step-indicator-1">
                    <div class="step-circle" id="circle-1">1</div>
                    <div class="step-label">Quiz Details</div>
                </div>
                <div class="step-line" id="line-1"></div>
                <div class="step" id="step-indicator-2">
                    <div class="step-circle" id="circle-2">2</div>
                    <div class="step-label">Add Questions</div>
                </div>
                <div class="step-line" id="line-2"></div>
                <div class="step" id="step-indicator-3">
                    <div class="step-circle" id="circle-3">3</div>
                    <div class="step-label">Visibility&nbsp;& Access</div>
                </div>
                <div class="step-line" id="line-3"></div>
                <div class="step" id="step-indicator-4">
                    <div class="step-circle" id="circle-4">4</div>
                    <div class="step-label">Review&nbsp;& Publish</div>
                </div>
            </div>

            <!-- STEP 1: QUIZ DETAILS -->
            <div id="step1">
                <div class="form-card">
                    <h2>Quiz Details</h2>
                    <p>Fill in the basic information about your quiz.</p>

                    <div class="field">
                        <label>Quiz Title *</label>
                        <input type="text" class="input" id="quizTitle" name="title"
                            placeholder="e.g. Data Structures Midterm" required />
                    </div>

                    <div class="field">
                        <label>Description</label>
                        <textarea class="input" name="description"
                            placeholder="Brief description of what this quiz covers..."></textarea>
                    </div>

                    <div class="row-2">
                        <div class="field">
                            <label>Time Limit (minutes)</label>
                            <input type="number" class="input" name="time_limit"
                                placeholder="e.g. 30" min="1" />
                        </div>
                        <div class="field">
                            <label>Max Attempts</label>
                            <input type="number" class="input" name="max_attempts"
                                value="1" min="1" required />
                        </div>
                    </div>
                    <div class="row-2">
                        <div class="field">
                            <label>Category *</label>
                            <input type="text" class="input" name="category"
                                placeholder="e.g. Mathematics, BCS, Science" required />
                        </div>
                        <div class="field">
                            <label>Difficulty</label>
                            <div id="difficultySelectContainer"></div>
                            <input type="hidden" name="difficulty" id="difficultyInput" value="medium">
                        </div>
                    </div>

                    <div class="field">
                        <label>Tags (comma separated)</label>
                        <input type="text" class="input" name="tags"
                            placeholder="e.g. algebra, geometry, mcq" />
                    </div>

                    <div class="field">
                        <label>Passing Score (%)</label>
                        <input type="number" class="input" name="passing_score"
                            placeholder="e.g. 50" min="0" max="100" />
                    </div>

                    <div class="row-2">
                        <div class="field">
                            <label>Start Date & Time</label>
                            <input type="datetime-local" class="input" name="starts_at" />
                        </div>
                        <div class="field">
                            <label>End Date & Time</label>
                            <input type="datetime-local" class="input" name="ends_at" />
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <h2>Settings</h2>
                    <p>Configure quiz behaviour for students.</p>

                    <div class="toggle-field">
                        <div class="toggle-info">
                            Shuffle Questions
                            <span>Randomize question order for each student</span>
                        </div>
                        <button type="button" class="toggle" id="shuffleToggle"
                            onclick="toggleSwitch(this, 'shuffle_questions')"></button>
                        <input type="hidden" name="shuffle_questions" id="shuffle_questions" value="0">
                    </div>

                    <div class="toggle-field">
                        <div class="toggle-info">
                            Show Results After Submission
                            <span>Students can see their score and correct answers</span>
                        </div>
                        <button type="button" class="toggle on" id="resultsToggle"
                            onclick="toggleSwitch(this, 'show_results')"></button>
                        <input type="hidden" name="show_results" id="show_results" value="1">
                    </div>
                </div>
            </div>

            <!-- STEP 2: ADD QUESTIONS -->
            <div id="step2" style="display:none;">
                <div class="form-card">
                    <h2>Add Questions</h2>
                    <p>Add MCQ questions with 4 options. Click the circle to mark the correct answer.</p>

                    <div id="questionsContainer"></div>

                    <div style="display:flex; gap:10px;">
                        <button type="button" class="btn btn-secondary" onclick="addQuestion()" style="flex:1;justify-content:center;">
                            <i class="ti ti-plus"></i> Add Question
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="goToBankForImport()" style="flex:1;justify-content:center;">
                            <i class="ti ti-database"></i> Import from Bank
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 3: VISIBILITY & ACCESS -->
            <div id="step3" style="display:none;">
                <div class="form-card">
                    <h2>Visibility & Access</h2>
                    <p>Choose who can see and attempt this quiz.</p>

                    <div class="visibility-cards">
                        <div class="visibility-card selected" id="card-public" onclick="selectVisibility('public')">
                            <div class="visibility-card-icon"><i class="ti ti-world"></i></div>
                            <div class="visibility-card-title">Public</div>
                            <div class="visibility-card-desc">Anyone can find and attempt this quiz from Browse.</div>
                        </div>
                        <div class="visibility-card" id="card-private" onclick="selectVisibility('private')">
                            <div class="visibility-card-icon"><i class="ti ti-lock"></i></div>
                            <div class="visibility-card-title">Private</div>
                            <div class="visibility-card-desc">Only students with the access code can attempt it.</div>
                        </div>
                    </div>

                    <input type="hidden" name="visibility" id="visibilityInput" value="public">

                    <div id="accessCodeBox" style="display:none;">
                        <label style="display:block;font-size:11px;font-weight:600;color:var(--color-text-muted);letter-spacing:0.8px;text-transform:uppercase;margin-bottom:8px;">
                            Access Code
                        </label>
                        <div class="access-code-display">
                            <span id="accessCodeText">------</span>
                            <button type="button" class="code-shuffle-btn" onclick="generateAccessCode()" title="Generate a new code">
                                <i class="ti ti-refresh"></i>
                            </button>
                        </div>
                        <p style="font-size:12px;color:var(--color-text-muted);margin-top:8px;">
                            Share this code with students so they can unlock the quiz.
                        </p>
                    </div>

                    <input type="hidden" name="proposed_code" id="proposedCodeInput" value="">
                </div>
            </div>

            <!-- STEP 4: REVIEW -->
            <div id="step4" style="display:none;">
                <div class="form-card">
                    <h2>Review Quiz</h2>
                    <p>Check everything before publishing.</p>
                    <div class="review-quiz-info" id="reviewDetails"></div>
                </div>
                <div class="form-card">
                    <h2>Questions <span id="reviewQCount" style="color:var(--color-text-muted);font-weight:400;font-size:13px;"></span></h2>
                    <p>Verify all questions and correct answers.</p>
                    <div id="reviewQuestions"></div>
                </div>

                <input type="hidden" name="status" id="statusInput" value="draft">
            </div>

        </div>

        <!-- BOTTOM BAR -->
        <div class="bottom-bar">
            <button type="button" class="btn btn-secondary" id="prevBtn"
                style="visibility:hidden;" onclick="prevStep()">
                <i class="ti ti-arrow-left"></i> Previous
            </button>
            <div style="display:flex;gap:10px;">
                <button type="button" class="btn btn-ghost" id="saveDraftBtn"
                    style="display:none;" onclick="submitAs('draft')">
                    Save as Draft
                </button>
                <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextStep()">
                    Next <i class="ti ti-arrow-right"></i>
                </button>
                <button type="button" class="btn btn-success" id="publishBtn"
                    style="display:none;" onclick="submitAs('active')">
                    <i class="ti ti-rocket"></i> Publish Quiz
                </button>
            </div>
        </div>
    </form>
    <script src="{{ asset('quizora.js') }}"></script>
    <script>
        createCustomSelect(
            document.getElementById('difficultySelectContainer'),
            [{
                    value: 'easy',
                    label: 'Easy'
                },
                {
                    value: 'medium',
                    label: 'Medium'
                },
                {
                    value: 'hard',
                    label: 'Hard'
                }
            ],
            'Medium',
            (value) => document.getElementById('difficultyInput').value = value
        );
        const CODE_CHARS = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

        function generateAccessCode() {
            let code = '';
            for (let i = 0; i < 6; i++) {
                code += CODE_CHARS[Math.floor(Math.random() * CODE_CHARS.length)];
            }
            document.getElementById('accessCodeText').textContent = code;
            document.getElementById('proposedCodeInput').value = code;
        }

        function selectVisibility(value) {
            document.getElementById('visibilityInput').value = value;
            document.getElementById('card-public').classList.toggle('selected', value === 'public');
            document.getElementById('card-private').classList.toggle('selected', value === 'private');
            const box = document.getElementById('accessCodeBox');
            if (value === 'private') {
                box.style.display = 'block';
                if (!document.getElementById('proposedCodeInput').value) {
                    generateAccessCode();
                }
            } else {
                box.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const oldVisibility = "{{ old('visibility') }}";
            const oldCode = "{{ old('proposed_code') }}";
            if (oldVisibility === 'private') {
                selectVisibility('private');
                if (oldCode) {
                    document.getElementById('accessCodeText').textContent = oldCode;
                    document.getElementById('proposedCodeInput').value = oldCode;
                }
            }
        });
        let currentStep = 1;
        let questionCount = 0;

        //TOGGLE SWITCH
        function toggleSwitch(btn, fieldId) {
            btn.classList.toggle('on');
            document.getElementById(fieldId).value = btn.classList.contains('on') ? '1' : '0';
        }

        //STEP NAVIGATION
        function nextStep() {
            if (currentStep === 1) {
                const requiredFields = document.querySelectorAll('#step1 [required]');
                let firstInvalid = null;

                for (let field of requiredFields) {
                    if (!field.value.trim()) {
                        firstInvalid = field;
                        break;
                    }
                }

                if (firstInvalid) {
                    const labelEl = firstInvalid.closest('.field')?.querySelector('label');
                    const labelText = labelEl ? labelEl.textContent.replace('*', '').trim() : firstInvalid.name;
                    alert('Please fill in "' + labelText + '" before continuing.');
                    firstInvalid.focus();
                    return;
                }
            }
            if (currentStep === 2) {
                if (questionCount === 0) {
                    alert('Please add at least one question.');
                    return;
                }
                const questions = document.querySelectorAll('.question-card');
                for (let q of questions) {
                    const text = q.querySelector('.q-text').value.trim();
                    if (!text) {
                        alert('Please fill in all question texts.');
                        return;
                    }
                    const opts = q.querySelectorAll('.option-input');
                    for (let o of opts) {
                        if (!o.value.trim()) {
                            alert('Please fill in all options.');
                            return;
                        }
                    }
                    const correct = q.querySelector('.option-radio:checked');
                    if (!correct) {
                        alert('Please mark a correct answer for each question.');
                        return;
                    }
                }
            }
            goToStep(currentStep + 1);
        }

        function prevStep() {
            goToStep(currentStep - 1);
        }

        function goToStep(step) {
            document.getElementById('step' + currentStep).style.display = 'none';
            document.getElementById('step-indicator-' + currentStep).classList.remove('active');
            document.getElementById('step-indicator-' + currentStep).classList.add('done');
            document.getElementById('circle-' + currentStep).innerHTML = '<i class="ti ti-check" style="font-size:13px"></i>';

            if (step < currentStep) {
                document.getElementById('step-indicator-' + currentStep).classList.remove('done');
                document.getElementById('circle-' + currentStep).innerHTML = currentStep;
            }

            currentStep = step;
            document.getElementById('step' + currentStep).style.display = 'block';
            document.getElementById('step-indicator-' + currentStep).classList.add('active');
            document.getElementById('step-indicator-' + currentStep).classList.remove('done');
            document.getElementById('circle-' + currentStep).innerHTML = currentStep;
            document.getElementById('currentStepLabel').textContent = currentStep;

            if (currentStep > 1) document.getElementById('line-1').classList.add('done');
            else document.getElementById('line-1').classList.remove('done');
            if (currentStep > 2) document.getElementById('line-2').classList.add('done');
            else document.getElementById('line-2').classList.remove('done');
            if (currentStep > 3) document.getElementById('line-3').classList.add('done');
            else document.getElementById('line-3').classList.remove('done');

            if (currentStep === 4) buildReview();

            document.getElementById('prevBtn').style.visibility = currentStep === 1 ? 'hidden' : 'visible';
            document.getElementById('nextBtn').style.display = currentStep === 4 ? 'none' : 'inline-flex';
            document.getElementById('publishBtn').style.display = currentStep === 4 ? 'inline-flex' : 'none';
            document.getElementById('saveDraftBtn').style.display = currentStep === 4 ? 'inline-flex' : 'none';
        }

        //ADD QUESTION
        function addQuestion() {
            const index = questionCount;
            const container = document.getElementById('questionsContainer');
            const div = document.createElement('div');
            div.className = 'question-card';
            div.id = 'question-' + index;
            div.innerHTML = `
        <div class="question-header">
            <span class="question-num">Q${index + 1}</span>
            <div class="question-actions">
                <button type="button" class="q-action-btn delete" onclick="deleteQuestion(${index})">
                    <i class="ti ti-trash"></i>
                </button>
            </div>
        </div>
        <div class="field">
            <label>Question Text *</label>
            <input type="text" class="input q-text"
                name="questions[${index}][text]"
                placeholder="Type your question here..." required />
        </div>
        <div class="field">
            <label>Marks</label>
            <input type="number" class="input" name="questions[${index}][marks]"
                value="1" min="1" style="width:100px;" required />
        </div>
        <input type="hidden" name="questions[${index}][correct]" class="correct-input" value="">
        <div class="options-grid">
            ${['A','B','C','D'].map((letter, i) => `
                <div class="option-wrap" id="opt-wrap-${index}-${i}">
                    <input type="radio" class="option-radio"
                        name="q-correct-${index}" value="${i}"
                        id="opt-radio-${index}-${i}"
                        onchange="markCorrect(${index}, ${i})" />
                    <label class="option-label" for="opt-radio-${index}-${i}">${letter}</label>
                    <input type="text" class="option-input"
                        name="questions[${index}][options][${i}]"
                        placeholder="Option ${letter}" required />
                </div>
            `).join('')}
        </div>
    `;
            container.appendChild(div);
            questionCount++;
            renumberQuestions();
        }

        //MARK CORRECT ANSWER
        function markCorrect(qIndex, optIndex) {
            for (let i = 0; i < 4; i++) {
                const wrap = document.getElementById(`opt-wrap-${qIndex}-${i}`);
                const label = wrap.querySelector('.option-label');
                const letters = ['A', 'B', 'C', 'D'];
                if (i === optIndex) {
                    wrap.classList.add('correct');
                    label.innerHTML = '<i class="ti ti-check" style="font-size:12px"></i>';
                } else {
                    wrap.classList.remove('correct');
                    label.textContent = letters[i];
                }
            }
            document.querySelector(`#question-${qIndex} .correct-input`).value = optIndex;
        }

        //DELETE QUESTION
        function deleteQuestion(index) {
            const el = document.getElementById('question-' + index);
            if (el) el.remove();
            renumberQuestions();
        }

        //RENUMBER QUESTIONS
        function renumberQuestions() {
            const cards = document.querySelectorAll('.question-card');
            cards.forEach((card, i) => {
                card.querySelector('.question-num').textContent = 'Q' + (i + 1);
            });
        }

        //BUILD REVIEW
        function buildReview() {
            const title = document.getElementById('quizTitle').value;
            const timeLimit = document.querySelector('[name="time_limit"]').value;
            const maxAttempts = document.querySelector('[name="max_attempts"]').value;
            const startsAt = document.querySelector('[name="starts_at"]').value;
            const endsAt = document.querySelector('[name="ends_at"]').value;
            const shuffle = document.getElementById('shuffle_questions').value === '1' ? 'Yes' : 'No';
            const showResults = document.getElementById('show_results').value === '1' ? 'Yes' : 'No';
            const visibility = document.getElementById('visibilityInput').value;
            const code = document.getElementById('proposedCodeInput').value;

            document.getElementById('reviewDetails').innerHTML = `
                <div class="review-row"><span>Title</span><span>${title}</span></div>
                <div class="review-row"><span>Time Limit</span><span>${timeLimit ? timeLimit + ' minutes' : 'No limit'}</span></div>
                <div class="review-row"><span>Max Attempts</span><span>${maxAttempts}</span></div>
                <div class="review-row"><span>Starts At</span><span>${startsAt || 'Immediately'}</span></div>
                <div class="review-row"><span>Ends At</span><span>${endsAt || 'No deadline'}</span></div>
                <div class="review-row"><span>Shuffle Questions</span><span>${shuffle}</span></div>
                <div class="review-row"><span>Show Results</span><span>${showResults}</span></div>
                <div class="review-row"><span>Visibility</span><span>${visibility === 'private' ? 'Private (code: ' + code + ')' : 'Public'}</span></div>
                <div class="review-row"><span>Total Questions</span><span>${document.querySelectorAll('.question-card').length}</span></div>
            `;

            const cards = document.querySelectorAll('.question-card');
            document.getElementById('reviewQCount').textContent = '(' + cards.length + ' questions)';
            const letters = ['A', 'B', 'C', 'D'];
            let reviewHTML = '';
            cards.forEach((card, i) => {
                const qText = card.querySelector('.q-text').value;
                const correctVal = card.querySelector('.correct-input').value;
                const opts = card.querySelectorAll('.option-input');
                reviewHTML += `
            <div class="review-question">
                <div class="review-question-text">Q${i+1}. ${qText}</div>
                <div class="review-options">
                    ${Array.from(opts).map((o, j) => `
                        <div class="review-option ${j == correctVal ? 'correct' : ''}">
                            ${letters[j]}. ${o.value}
                            ${j == correctVal ? ' ✓' : ''}
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
            });
            document.getElementById('reviewQuestions').innerHTML = reviewHTML;
        }

        //SUBMIT
        function submitAs(status) {
            document.getElementById('statusInput').value = status;
            document.getElementById('quizForm').submit();
        }

        function goToBankForImport() {
            const form = document.querySelector('form');
            const data = {};
            new FormData(form).forEach((value, key) => {
                if (data[key] !== undefined) {
                    if (!Array.isArray(data[key])) data[key] = [data[key]];
                    data[key].push(value);
                } else {
                    data[key] = value;
                }
            });
            sessionStorage.setItem('quiz_draft_state', JSON.stringify(data));
            window.location.href = "{{ route('teacher.question-bank') }}?pick_for_quiz=1";
        }

        function restoreDraftState(savedData) {
            const form = document.getElementById('quizForm');

            let maxIndex = -1;
            Object.keys(savedData).forEach(key => {
                const match = key.match(/^questions\[(\d+)\]/);
                if (match) maxIndex = Math.max(maxIndex, parseInt(match[1]));
            });

            for (let i = 0; i <= maxIndex; i++) {
                addQuestion();
            }

            Object.entries(savedData).forEach(([key, value]) => {
                const values = Array.isArray(value) ? value : [value];
                const els = form.querySelectorAll(`[name="${CSS.escape(key)}"]`);
                els.forEach((el, i) => {
                    const val = values[i] ?? values[0];
                    if (el.type === 'radio' || el.type === 'checkbox') {
                        el.checked = (el.value === val);
                        if (el.checked) el.dispatchEvent(new Event('change'));
                    } else {
                        el.value = val;
                    }
                });
            });
        }

        function handleCsvImport(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);

            fetch("{{ route('teacher.quiz.import-csv') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error + (data.row_errors?.length ? '\n\n' + data.row_errors.join('\n') : ''));
                        return;
                    }

                    data.questions.forEach(q => {
                        addQuestion();
                        const index = questionCount - 1;
                        const card = document.getElementById('question-' + index);
                        card.querySelector('.q-text').value = q.text;
                        card.querySelector('input[name*="marks"]').value = q.marks;
                        q.options.forEach((opt, i) => {
                            card.querySelector(`[name="questions[${index}][options][${i}]"]`).value = opt.text;
                        });
                        document.getElementById(`opt-radio-${index}-${q.correct}`).checked = true;
                        markCorrect(index, q.correct);
                    });

                    let msg = `Imported ${data.imported} question(s) from CSV.`;
                    if (data.row_errors.length > 0) {
                        msg += `\n\n${data.row_errors.length} row(s) skipped:\n` + data.row_errors.join('\n');
                    }
                    alert(msg);
                })
                .catch(() => alert('Failed to import CSV file.'));

            event.target.value = '';
        }

        const savedDraft = JSON.parse(sessionStorage.getItem('quiz_draft_state') || 'null');
        const importIds = JSON.parse(sessionStorage.getItem('bank_import_ids') || '[]');

        if (savedDraft) {
            restoreDraftState(savedDraft);
            sessionStorage.removeItem('quiz_draft_state');
        }

        if (importIds.length > 0) {
            fetch("{{ route('teacher.question-bank.fetch-by-ids') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        ids: importIds
                    })
                })
                .then(r => r.json())
                .then(questions => {
                    questions.forEach(q => {
                        addQuestion();
                        const index = questionCount - 1;
                        const card = document.getElementById('question-' + index);
                        card.querySelector('.q-text').value = q.text;
                        card.querySelector('input[name*="marks"]').value = q.marks;
                        q.options.forEach((opt, i) => {
                            card.querySelector(`[name="questions[${index}][options][${i}]"]`).value = opt.text;
                            if (opt.is_correct) {
                                document.getElementById(`opt-radio-${index}-${i}`).checked = true;
                                markCorrect(index, i);
                            }
                        });
                    });
                    sessionStorage.removeItem('bank_import_ids');
                })
                .catch(() => {
                    if (!savedDraft) addQuestion();
                });
        } else if (!savedDraft) {
            addQuestion();
        }
    </script>
</body>

</html>