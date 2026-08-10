<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('quizora.css') }}">
    <link rel="stylesheet" href="{{ asset('teacher.css') }}">
    <title>Quizora — Create Quiz</title>
    @stack('styles')
</head>

<body>
    @if(session('row_errors') && count(session('row_errors')) > 0)
    <div style="background:rgba(245,158,11,0.15); border:1px solid rgba(245,158,11,0.4); color:#F59E0B; padding:14px 18px; border-radius:10px; margin-bottom:16px; font-size:13px;">
        <strong>{{ count(session('row_errors')) }} row(s) were skipped during import:</strong>
        <ul style="margin-top:6px; padding-left:18px;">
            @foreach(session('row_errors') as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form id="quizForm" method="POST" action="{{ route('teacher.quiz.update', $quiz->id) }}">
        @csrf
        @method('PUT')

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="topbar-left">
                <a href="javascript:history.back()" class="back-btn">
                    <i class="ti ti-arrow-left"></i> Back
                </a>
                <div class="topbar-title">Edit Quiz</div>
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
                            value="{{ $quiz->title }}" placeholder="e.g. Data Structures Midterm" required />
                    </div>

                    <div class="field">
                        <label>Description</label>
                        <textarea class="input" name="description"
                            placeholder="Brief description...">{{ $quiz->description }}</textarea>
                    </div>

                    <div class="row-2">
                        <div class="field">
                            <label>Time Limit (minutes)</label>
                            <input type="number" class="input" name="time_limit"
                                value="{{ $quiz->time_limit }}" placeholder="e.g. 30" min="1" />
                        </div>
                        <div class="field">
                            <label>Max Attempts</label>
                            <input type="number" class="input" name="max_attempts"
                                value="{{ $quiz->max_attempts }}" min="1" required />
                        </div>
                    </div>

                    <div class="row-2">
                        <div class="field">
                            <label>Start Date & Time</label>
                            <input type="datetime-local" class="input" name="starts_at"
                                value="{{ $quiz->starts_at ? $quiz->starts_at->format('Y-m-d\TH:i') : '' }}" />
                        </div>
                        <div class="field">
                            <label>End Date & Time</label>
                            <input type="datetime-local" class="input" name="ends_at" id="endsAtInput"
                                value="{{ $quiz->ends_at ? $quiz->ends_at->format('Y-m-d\TH:i') : '' }}"
                                {{ !$quiz->ends_at ? 'disabled' : '' }} />
                            <label style="display:flex; align-items:center; gap:8px; margin-top:8px; font-size:12.5px; font-weight:500; color:var(--color-text-secondary); text-transform:none; letter-spacing:0; cursor:pointer;">
                                <input type="checkbox" id="unlimitedToggle" onchange="toggleUnlimited()" {{ !$quiz->ends_at ? 'checked' : '' }} style="width:16px; height:16px; accent-color:var(--color-primary-solid); cursor:pointer;">
                                Keep this quiz open indefinitely (no end date)
                            </label>
                        </div>
                    </div>
                    <div class="row-2">
                        <div class="field">
                            <label>Category *</label>
                            <input type="text" class="input" name="category"
                                value="{{ old('category', $quiz->category) }}"
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
                            value="{{ old('tags', $quiz->tags) }}"
                            placeholder="e.g. algebra, geometry, mcq" />
                    </div>

                    <div class="field">
                        <label>Passing Score (%)</label>
                        <input type="number" class="input" name="passing_score"
                            value="{{ old('passing_score', $quiz->passing_score) }}"
                            placeholder="e.g. 50" min="0" max="100" />
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
                        <button type="button" class="toggle {{ $quiz->shuffle_questions ? 'on' : '' }}"
                            id="shuffleToggle" onclick="toggleSwitch(this, 'shuffle_questions')"></button>
                        <input type="hidden" name="shuffle_questions" id="shuffle_questions"
                            value="{{ $quiz->shuffle_questions ? '1' : '0' }}">
                    </div>

                    <div class="toggle-field">
                        <div class="toggle-info">
                            Show Results After Submission
                            <span>Students can see their score and correct answers</span>
                        </div>
                        <button type="button" class="toggle {{ $quiz->show_results ? 'on' : '' }}"
                            id="resultsToggle" onclick="toggleSwitch(this, 'show_results')"></button>
                        <input type="hidden" name="show_results" id="show_results"
                            value="{{ $quiz->show_results ? '1' : '0' }}">
                    </div>
                </div>
            </div>

            <!-- STEP 2: ADD QUESTIONS -->
            <div id="step2" style="display:none;">
                <div class="form-card">
                    <h2>Add Questions</h2>
                    <p>Add MCQ questions with 4 options. Click the circle to mark the correct answer.</p>

                    <div id="questionsContainer"></div>

                    <button type="button" class="btn btn-secondary" onclick="addQuestion()" style="width:100%;justify-content:center;">
                        <i class="ti ti-plus"></i> Add Question
                    </button>
                </div>
            </div>

            <!-- STEP 3: VISIBILITY & ACCESS -->
            <div id="step3" style="display:none;">
                <div class="form-card">
                    <h2>Visibility & Access</h2>
                    <p>Choose who can see and attempt this quiz.</p>

                    <div class="visibility-cards">
                        <div class="visibility-card {{ $quiz->visibility === 'public' ? 'selected' : '' }}" id="card-public" onclick="selectVisibility('public')">
                            <div class="visibility-card-icon"><i class="ti ti-world"></i></div>
                            <div class="visibility-card-title">Public</div>
                            <div class="visibility-card-desc">Anyone can find and attempt this quiz from Browse.</div>
                        </div>
                        <div class="visibility-card {{ $quiz->visibility === 'private' ? 'selected' : '' }}" id="card-private" onclick="selectVisibility('private')">
                            <div class="visibility-card-icon"><i class="ti ti-lock"></i></div>
                            <div class="visibility-card-title">Private</div>
                            <div class="visibility-card-desc">Only students with the access code can attempt it.</div>
                        </div>
                    </div>

                    <input type="hidden" name="visibility" id="visibilityInput" value="{{ $quiz->visibility }}">

                    <div id="accessCodeBox" style="{{ $quiz->visibility === 'private' ? '' : 'display:none;' }}">
                        <label style="display:block;font-size:11px;font-weight:600;color:var(--color-text-muted);letter-spacing:0.8px;text-transform:uppercase;margin-bottom:8px;">
                            Access Code
                        </label>
                        <div class="access-code-display">
                            <span id="accessCodeText">{{ $quiz->access_code ?? '------' }}</span>
                            <button type="button" class="code-shuffle-btn" onclick="generateAccessCode()" title="Generate a new code">
                                <i class="ti ti-refresh"></i>
                            </button>
                        </div>
                        <p style="font-size:12px;color:var(--color-text-muted);margin-top:8px;">
                            Share this code with students so they can unlock the quiz. Click refresh to generate a new one.
                        </p>
                    </div>

                    <input type="hidden" name="proposed_code" id="proposedCodeInput" value="{{ $quiz->access_code }}">
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
                    <i class="ti ti-rocket"></i> Update Quiz
                </button>
            </div>
        </div>

    </form>
    <script>
        @php
        $existingQuestions = $quiz -> questions() -> with('options') -> get() -> map(function($q) {
            return [
                'text' => $q -> question_text ?? '',
                'marks' => $q -> marks ?? 1,
                'options' => $q -> options -> map(function($opt) {
                    return [
                        'text' => $opt -> option_text ?? '',
                        'is_correct' => (bool) $opt -> is_correct
                    ];
                }) -> toArray()
            ];
        }) -> toArray();
        @endphp

        let existingQuestions = @json($existingQuestions);
    </script>
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
            '{{ ucfirst($quiz->difficulty) }}',
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
        let currentStep = 1;
        let questionCount = 0;

        //TOGGLE SWITCH
        function toggleSwitch(btn, fieldId) {
            btn.classList.toggle('on');
            document.getElementById(fieldId).value = btn.classList.contains('on') ? '1' : '0';
        }
        function toggleUnlimited() {
            const endsAtInput = document.getElementById('endsAtInput');
            const isUnlimited = document.getElementById('unlimitedToggle').checked;

            endsAtInput.disabled = isUnlimited;
            if (isUnlimited) {
                endsAtInput.value = '';
                endsAtInput.style.opacity = '0.4';
            } else {
                endsAtInput.style.opacity = '1';
            }
        }

        //STEP NAVIGATION
        function nextStep() {
            if (currentStep === 1) {
                const title = document.getElementById('quizTitle').value.trim();
                if (!title) {
                    alert('Please enter a quiz title.');
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
            if (currentStep === 3) {
                buildReview();
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

            document.getElementById('prevBtn').style.visibility = currentStep === 1 ? 'hidden' : 'visible';
            document.getElementById('nextBtn').style.display = currentStep === 4 ? 'none' : 'inline-flex';
            document.getElementById('publishBtn').style.display = currentStep === 4 ? 'inline-flex' : 'none';
            document.getElementById('saveDraftBtn').style.display = currentStep === 4 ? 'inline-flex' : 'none';
        }

        function loadExistingQuestions() {
            if (!existingQuestions || existingQuestions.length === 0) {
                addQuestion();
                return;
            }

            existingQuestions.forEach((q) => {
                addQuestion();
                const index = questionCount - 1;
                const card = document.getElementById('question-' + index);
                if (!card) return;

                //Fill Question Text
                const textInput = card.querySelector('.q-text');
                if (textInput) textInput.value = q.text;

                //Fill Marks
                const marksInput = card.querySelector('input[name*="marks"]');
                if (marksInput) marksInput.value = q.marks;

                //Fill Options & Correct Answer
                q.options.forEach((opt, i) => {
                    const optInput = card.querySelector(`[name="questions[${index}][options][${i}]"]`);
                    if (optInput) optInput.value = opt.text;

                    if (opt.is_correct) {
                        const radio = document.getElementById(`opt-radio-${index}-${i}`);
                        if (radio) {
                            radio.checked = true;
                            markCorrect(index, i);
                        }
                    }
                });
            });
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

        //Pre-load existing questions from DB
        document.addEventListener('DOMContentLoaded', function() {
            loadExistingQuestions();
        });
    </script>
</body>

</html>