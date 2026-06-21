<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <title>Quizora — Taking Quiz</title>
    <style>
        :root {
            --color-primary-glow: #818CF8;
            --color-primary-solid: #4F46E5;
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
            background: var(--color-bg-main);
            color: var(--color-text-primary);
            min-height: 100vh;
        }

        /* TOP BAR */
        .quiz-topbar {
            height: 64px;
            background: #1e1b45;
            border-bottom: 1px solid var(--color-border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .quiz-name-tag {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
        }

        .timer-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(248, 113, 113, 0.12);
            border: 1px solid rgba(248, 113, 113, 0.3);
            padding: 8px 18px;
            border-radius: 20px;
        }

        .timer-wrap.warning {
            background: rgba(245, 158, 11, 0.12);
            border-color: rgba(245, 158, 11, 0.3);
        }

        .timer-wrap i {
            color: var(--color-status-error);
            font-size: 18px;
        }

        .timer-wrap.warning i {
            color: #F59E0B;
        }

        .timer-text {
            font-size: 16px;
            font-weight: 700;
            color: var(--color-status-error);
            font-variant-numeric: tabular-nums;
        }

        .timer-wrap.warning .timer-text {
            color: #F59E0B;
        }

        .exit-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            color: var(--color-text-secondary);
            border: 1px solid var(--color-border-light);
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            font-family: var(--font);
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .exit-btn:hover {
            background: rgba(248, 113, 113, 0.1);
            border-color: rgba(248, 113, 113, 0.3);
            color: var(--color-status-error);
        }

        /* PROGRESS */
        .progress-section {
            padding: 16px 28px 0;
            max-width: 760px;
            margin: 0 auto;
        }

        .progress-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .progress-text {
            font-size: 13px;
            color: var(--color-text-muted);
        }

        .progress-text strong {
            color: #fff;
        }

        .progress-track {
            height: 6px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--color-primary-solid);
            border-radius: 3px;
            transition: width 0.3s;
        }

        /* QUESTION AREA */
        .quiz-body {
            max-width: 760px;
            margin: 0 auto;
            padding: 32px 28px 140px;
        }

        .question-number-tag {
            font-size: 12px;
            font-weight: 700;
            color: var(--color-primary-glow);
            background: rgba(79, 70, 229, 0.15);
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 16px;
        }

        .question-text {
            font-size: 19px;
            font-weight: 700;
            color: #fff;
            line-height: 1.5;
            margin-bottom: 28px;
        }

        .options-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .option-card {
            display: flex;
            align-items: center;
            gap: 14px;
            background: var(--color-bg-card);
            border: 1.5px solid var(--color-border-light);
            border-radius: 12px;
            padding: 16px 18px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .option-card:hover {
            border-color: rgba(79, 70, 229, 0.4);
            background: var(--color-bg-row-hover);
        }

        .option-card.selected {
            border-color: var(--color-primary-solid);
            background: rgba(79, 70, 229, 0.12);
        }

        .option-letter {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            border: 1.5px solid var(--color-border-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: var(--color-text-muted);
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .option-card.selected .option-letter {
            background: var(--color-primary-solid);
            border-color: var(--color-primary-solid);
            color: #fff;
        }

        .option-text {
            font-size: 14px;
            color: var(--color-text-secondary);
            flex: 1;
        }

        .option-card.selected .option-text {
            color: #fff;
            font-weight: 500;
        }

        /* QUESTION NAV GRID (mobile floating panel could go here, simplified as bottom dots) */
        .qnav-dots {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--color-border-light);
        }

        .qnav-dot {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.04);
            border: 1.5px solid var(--color-border-light);
            color: var(--color-text-muted);
            cursor: pointer;
            transition: all 0.2s;
        }

        .qnav-dot.answered {
            background: rgba(52, 211, 153, 0.12);
            border-color: rgba(52, 211, 153, 0.4);
            color: var(--color-status-success);
        }

        .qnav-dot.current {
            background: var(--color-primary-solid);
            border-color: var(--color-primary-solid);
            color: #fff;
        }

        /* BOTTOM BAR */
        .quiz-bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(14, 11, 32, 0.95);
            backdrop-filter: blur(12px);
            border-top: 1px solid var(--color-border-light);
            padding: 16px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 60;
        }

        .nav-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 11px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            font-family: var(--font);
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .nav-btn-secondary {
            background: transparent;
            color: var(--color-text-secondary);
            border: 1px solid var(--color-border-light);
        }

        .nav-btn-secondary:hover {
            background: var(--color-bg-row-hover);
            color: #fff;
        }

        .nav-btn-secondary:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .nav-btn-primary {
            background: var(--color-primary-solid);
            color: #fff;
        }

        .nav-btn-primary:hover {
            background: #4338CA;
        }

        .nav-btn-submit {
            background: var(--color-status-success);
            color: #0E0B20;
        }

        .nav-btn-submit:hover {
            background: #2EBD87;
        }
    </style>
</head>

<body>

    <div class="quiz-topbar">
        <div class="quiz-name-tag">BCS Preliminary — Bangladesh Affairs Full Set</div>
        <div class="timer-wrap" id="timerWrap">
            <i class="ti ti-clock"></i>
            <span class="timer-text" id="timerText">58:42</span>
        </div>
        <a href="{{ route('student.quiz.detail') }}" class="exit-btn">
            <i class="ti ti-x"></i> Exit
        </a>
    </div>

    <div class="progress-section">
        <div class="progress-info">
            <div class="progress-text">Question <strong id="currentQNum">1</strong> of <strong>50</strong></div>
            <div class="progress-text"><strong id="answeredCount">0</strong> answered</div>
        </div>
        <div class="progress-track">
            <div class="progress-fill" id="progressFill" style="width:2%"></div>
        </div>
    </div>

    <div class="quiz-body">

        <span class="question-number-tag">Question 1</span>
        <h2 class="question-text">
            Which article of the Constitution of Bangladesh declares the country as a unitary state?
        </h2>

        <div class="options-list">
            <div class="option-card" onclick="selectOption(this)">
                <div class="option-letter">A</div>
                <div class="option-text">Article 1</div>
            </div>
            <div class="option-card" onclick="selectOption(this)">
                <div class="option-letter">B</div>
                <div class="option-text">Article 2</div>
            </div>
            <div class="option-card" onclick="selectOption(this)">
                <div class="option-letter">C</div>
                <div class="option-text">Article 3</div>
            </div>
            <div class="option-card" onclick="selectOption(this)">
                <div class="option-letter">D</div>
                <div class="option-text">Article 4</div>
            </div>
        </div>

        <div class="qnav-dots" id="qnavDots"></div>

    </div>

    <div class="quiz-bottom-bar">
        <button class="nav-btn nav-btn-secondary" id="prevBtn" disabled>
            <i class="ti ti-arrow-left"></i> Previous
        </button>
        <button class="nav-btn nav-btn-primary" id="nextBtn">
            Next <i class="ti ti-arrow-right"></i>
        </button>
        <button class="nav-btn nav-btn-submit" id="submitBtn" style="display:none;" onclick="confirmSubmit()">
            <i class="ti ti-check"></i> Submit Quiz
        </button>
    </div>

    <script>
        let totalQuestions = 50;
        let currentQuestion = 1;
        let answered = new Set();

        function buildDots() {
            const dotsContainer = document.getElementById('qnavDots');
            let html = '';
            for (let i = 1; i <= totalQuestions; i++) {
                let cls = 'qnav-dot';
                if (i === currentQuestion) cls += ' current';
                else if (answered.has(i)) cls += ' answered';
                html += `<div class="${cls}" onclick="goToQuestion(${i})">${i}</div>`;
            }
            dotsContainer.innerHTML = html;
        }

        function goToQuestion(num) {
            currentQuestion = num;
            document.getElementById('currentQNum').textContent = num;
            document.getElementById('progressFill').style.width = (num / totalQuestions * 100) + '%';
            document.querySelector('.question-number-tag').textContent = 'Question ' + num;
            document.getElementById('prevBtn').disabled = num === 1;
            document.getElementById('nextBtn').style.display = num === totalQuestions ? 'none' : 'inline-flex';
            document.getElementById('submitBtn').style.display = num === totalQuestions ? 'inline-flex' : 'none';
            buildDots();
        }

        function selectOption(el) {
            document.querySelectorAll('.option-card').forEach(o => o.classList.remove('selected'));
            el.classList.add('selected');
            answered.add(currentQuestion);
            document.getElementById('answeredCount').textContent = answered.size;
            buildDots();
        }

        document.getElementById('nextBtn').addEventListener('click', () => {
            if (currentQuestion < totalQuestions) goToQuestion(currentQuestion + 1);
        });
        document.getElementById('prevBtn').addEventListener('click', () => {
            if (currentQuestion > 1) goToQuestion(currentQuestion - 1);
        });

        function confirmSubmit() {
            if (confirm('Are you sure you want to submit the quiz?')) {
                window.location.href = "{{ route('student.quiz.result') }}";
            }
        }

        // Timer
        let timeLeft = 60 * 60;

        function updateTimer() {
            const m = Math.floor(timeLeft / 60);
            const s = timeLeft % 60;
            document.getElementById('timerText').textContent =
                String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');

            if (timeLeft <= 300) {
                document.getElementById('timerWrap').classList.add('warning');
            }
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                alert('Time is up! Submitting your quiz.');
                window.location.href = "{{ route('student.quiz.result') }}";
                return;
            }
            timeLeft--;
        }
        const timerInterval = setInterval(updateTimer, 1000);

        buildDots();
    </script>
</body>

</html>