<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\AnnouncementRead;
use App\Models\AppNotification;
use App\Models\Attempt;
use App\Models\Answer;
use App\Models\BankOption;
use App\Models\BankQuestion;
use App\Models\Bookmark;
use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizUnlock;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    // Pools used to build realistic MCQ content without external packages.
    protected array $categories = ['Mathematics', 'Physics', 'Chemistry', 'Biology', 'English', 'ICT', 'General Knowledge', 'History'];
    protected array $difficulties = ['easy', 'medium', 'hard'];

    public function run(): void
    {
        $this->command?->info('Seeding Quizora demo data...');

        // ---------------------------------------------------------------
        // 1. TEACHERS (10)
        // ---------------------------------------------------------------
        $teachers = collect();
        $teacherNames = [
            'Rafiq Ahmed',
            'Nusrat Jahan',
            'Kamal Hossain',
            'Farhana Akter',
            'Sabbir Rahman',
            'Tanjina Islam',
            'Mahfuz Karim',
            'Shirin Sultana',
            'Imran Chowdhury',
            'Ayesha Siddika',
        ];
        foreach ($teacherNames as $i => $name) {
            $teachers->push(User::updateOrCreate(
                ['email' => 'teacher' . ($i + 1) . '@quizora.com'],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                    'status' => 'active',
                    'phone' => '01' . rand(700000000, 999999999),
                    'gender' => ['male', 'female'][array_rand(['male', 'female'])],
                    'institution' => ['Sylhet Cadet College', 'Notre Dame College', 'Rajshahi University School', 'BUET', 'Dhaka Residential Model College'][array_rand([0, 1, 2, 3, 4])],
                    'designation' => ['Assistant Professor', 'Senior Lecturer', 'Subject Teacher', 'Head of Department'][array_rand([0, 1, 2, 3])],
                    'preferred_language' => 'english',
                    'avatar_color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                    'email_verified_at' => now(),
                ]
            ));
        }

        // ---------------------------------------------------------------
        // 2. STUDENTS (10)
        // ---------------------------------------------------------------
        $students = collect();
        $studentNames = [
            'Arif Hasan',
            'Mim Akter',
            'Tanvir Ahmed',
            'Nabila Rahman',
            'Shanto Das',
            'Ruma Begum',
            'Fahim Muntasir',
            'Priya Sarkar',
            'Rakibul Islam',
            'Jannatul Ferdous',
        ];
        foreach ($studentNames as $i => $name) {
            $students->push(User::updateOrCreate(
                ['email' => 'student' . ($i + 1) . '@quizora.com'],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'status' => $i === 9 ? 'suspended' : 'active', // one suspended student for testing
                    'phone' => '01' . rand(300000000, 599999999),
                    'gender' => ['male', 'female'][array_rand(['male', 'female'])],
                    'institution' => ['Sylhet Cadet College', 'Notre Dame College', 'Viqarunnisa Noon School', 'Dhaka College'][array_rand([0, 1, 2, 3])],
                    'class_level' => ['Class 9', 'Class 10', 'HSC 1st Year', 'HSC 2nd Year', 'Undergraduate 1st Year'][array_rand([0, 1, 2, 3, 4])],
                    'education_level' => ['ssc', 'hsc', 'bachelor'][array_rand(['ssc', 'hsc', 'bachelor'])],
                    'study_goal' => ['exam_prep', 'university_admission', 'bcs', 'self_learning'][array_rand([0, 1, 2, 3])],
                    'preferred_language' => ['english', 'bangla'][array_rand(['english', 'bangla'])],
                    'target_score' => rand(70, 100),
                    'avatar_color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                    'email_verified_at' => now(),
                ]
            ));
        }

        $this->command?->info('Created ' . $teachers->count() . ' teachers and ' . $students->count() . ' students.');

        // ---------------------------------------------------------------
        // 3. QUESTION BANK per teacher (5-8 bank questions each)
        // ---------------------------------------------------------------
        foreach ($teachers as $teacher) {
            $bankCount = rand(5, 8);
            for ($i = 0; $i < $bankCount; $i++) {
                $category = $this->categories[array_rand($this->categories)];
                $bq = BankQuestion::create([
                    'teacher_id' => $teacher->id,
                    'question_text' => $this->fakeQuestionText($category, $i),
                    'marks' => [1, 2][array_rand([0, 1])],
                    'category' => $category,
                    'tags' => strtolower($category) . ',practice',
                ]);
                $this->createOptionsFor($bq, BankOption::class, 'bank_question_id');
            }
        }
        $this->command?->info('Seeded question bank entries.');

        // ---------------------------------------------------------------
        // 4. QUIZZES per teacher (5-10 each), with variance:
        //    - status: draft / active / closed
        //    - visibility: public / private (with access_code)
        //    - type: mcq / true_false / mixed
        // ---------------------------------------------------------------
        $allQuizzes = collect();

        foreach ($teachers as $teacher) {
            $quizCount = rand(5, 10);

            for ($q = 0; $q < $quizCount; $q++) {
                $category = $this->categories[array_rand($this->categories)];
                $visibility = (($q + $teacher->id) % 3 === 0) ? 'private' : 'public'; // ~1/3 private
                $status = match (true) {
                    $q === 0 => 'draft',                         // ensure at least one draft per teacher
                    $q === 1 => 'closed',                         // ensure at least one closed per teacher
                    default => ['active', 'active', 'closed'][array_rand([0, 1, 2])],
                };
                $type = ['mcq', 'mcq', 'true_false', 'mixed'][array_rand([0, 1, 2, 3])];

                $startsAt = now()->subDays(rand(1, 30));
                $endsAt = $status === 'closed' ? now()->subDays(rand(0, 5)) : now()->addDays(rand(3, 30));

                $quiz = Quiz::create([
                    'teacher_id' => $teacher->id,
                    'title' => $category . ' ' . ['Basics', 'Practice Test', 'Chapter Review', 'Mock Exam', 'Weekly Quiz'][array_rand([0, 1, 2, 3, 4])] . ' ' . ($q + 1),
                    'description' => 'A ' . strtolower($category) . ' assessment covering key topics for ' . strtolower($category) . ' learners.',
                    'type' => $type,
                    'status' => $status,
                    'visibility' => $visibility,
                    'access_code' => $visibility === 'private' ? strtoupper(Str::random(6)) : null,
                    'category' => $category,
                    'difficulty' => $this->difficulties[array_rand($this->difficulties)],
                    'tags' => strtolower($category) . ',' . $type,
                    'passing_score' => [40, 50, 60][array_rand([0, 1, 2])],
                    'time_limit' => [10, 15, 20, 30][array_rand([0, 1, 2, 3])],
                    'max_attempts' => [1, 1, 2, 3][array_rand([0, 1, 2, 3])],
                    'starts_at' => $startsAt,
                    'ends_at' => $endsAt,
                    'show_results' => true,
                    'shuffle_questions' => (bool) rand(0, 1),
                ]);

                // 5-12 questions per quiz
                $questionCount = rand(5, 12);
                $order = 1;
                for ($i = 0; $i < $questionCount; $i++) {
                    $qType = $type === 'mixed'
                        ? ['mcq', 'true_false', 'short_answer'][array_rand([0, 1, 2])]
                        : ($type === 'true_false' ? 'true_false' : 'mcq');

                    $question = Question::create([
                        'quiz_id' => $quiz->id,
                        'question_text' => $this->fakeQuestionText($category, $i, $qType),
                        'type' => $qType,
                        'marks' => [1, 2][array_rand([0, 1])],
                        'order' => $order++,
                    ]);

                    if ($qType === 'true_false') {
                        $correctFirst = (bool) rand(0, 1);
                        Option::create(['question_id' => $question->id, 'option_text' => 'True', 'is_correct' => $correctFirst, 'order' => 1]);
                        Option::create(['question_id' => $question->id, 'option_text' => 'False', 'is_correct' => !$correctFirst, 'order' => 2]);
                    } elseif ($qType === 'mcq') {
                        $this->createOptionsFor($question, Option::class, 'question_id');
                    }
                    // short_answer questions intentionally get no options
                }

                $allQuizzes->push($quiz);
            }
        }
        $this->command?->info('Seeded ' . $allQuizzes->count() . ' quizzes with questions and options.');

        // ---------------------------------------------------------------
        // 5. ATTEMPTS + ANSWERS (students attempting active/closed quizzes)
        // ---------------------------------------------------------------
        $attemptableQuizzes = $allQuizzes->where('status', '!=', 'draft');
        $activeStudents = $students->take(9); // exclude the suspended one from attempts

        foreach ($attemptableQuizzes as $quiz) {
            // For private quizzes, unlock a random subset of students first
            $eligibleStudents = $activeStudents;
            if ($quiz->visibility === 'private') {
                $eligibleStudents = $activeStudents->random(rand(3, 6));
                foreach ($eligibleStudents as $student) {
                    QuizUnlock::firstOrCreate([
                        'student_id' => $student->id,
                        'quiz_id' => $quiz->id,
                    ]);
                }
            }

            // Random subset of eligible students actually attempt this quiz
            $attemptCount = min($eligibleStudents->count(), rand(2, 7));
            $attemptingStudents = $eligibleStudents->random($attemptCount);
            if (!$attemptingStudents instanceof \Illuminate\Support\Collection) {
                $attemptingStudents = collect([$attemptingStudents]);
            }

            $quizQuestions = $quiz->questions()->with('options')->get();
            $totalMarks = $quizQuestions->sum('marks');

            foreach ($attemptingStudents as $student) {
                $startedAt = now()->subDays(rand(0, 20))->subMinutes(rand(5, 120));
                $submittedAt = (clone $startedAt)->addMinutes(rand(3, $quiz->time_limit ?? 20));

                $attempt = Attempt::create([
                    'quiz_id' => $quiz->id,
                    'student_id' => $student->id,
                    'status' => 'submitted',
                    'started_at' => $startedAt,
                    'submitted_at' => $submittedAt,
                    'total_marks' => $totalMarks,
                    'score' => 0, // filled below
                ]);

                $scoreObtained = 0;
                foreach ($quizQuestions as $question) {
                    $isCorrect = (bool) rand(0, 1) && rand(1, 100) <= 65; // ~65% correctness bias, realistic spread
                    $marksObtained = 0;
                    $optionId = null;
                    $answerText = null;

                    if ($question->type === 'short_answer') {
                        $answerText = 'Sample student answer for question #' . $question->id;
                        $isCorrect = rand(0, 1) === 1;
                        $marksObtained = $isCorrect ? $question->marks : 0;
                    } else {
                        $correctOption = $question->options->firstWhere('is_correct', true);
                        $chosenOption = $isCorrect
                            ? $correctOption
                            : $question->options->where('is_correct', false)->random();
                        $optionId = $chosenOption?->id;
                        $marksObtained = $isCorrect ? $question->marks : 0;
                    }

                    $scoreObtained += $marksObtained;

                    Answer::create([
                        'attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'option_id' => $optionId,
                        'answer_text' => $answerText,
                        'is_correct' => $isCorrect,
                        'marks_obtained' => $marksObtained,
                    ]);
                }

                $attempt->update(['score' => $scoreObtained]);
            }

            // Leave one active quiz with an in-progress attempt, for realism
            if ($quiz->status === 'active' && rand(0, 4) === 0 && $eligibleStudents->isNotEmpty()) {
                Attempt::create([
                    'quiz_id' => $quiz->id,
                    'student_id' => $eligibleStudents->random()->id,
                    'status' => 'in_progress',
                    'started_at' => now()->subMinutes(rand(1, 10)),
                    'submitted_at' => null,
                    'total_marks' => $totalMarks,
                    'score' => null,
                ]);
            }
        }
        $this->command?->info('Seeded attempts and answers.');

        // ---------------------------------------------------------------
        // 6. BOOKMARKS (students bookmarking a few quizzes)
        // ---------------------------------------------------------------
        foreach ($activeStudents as $student) {
            $bookmarkQuizzes = $allQuizzes->where('visibility', 'public')->random(min(3, $allQuizzes->count()));
            if (!$bookmarkQuizzes instanceof \Illuminate\Support\Collection) {
                $bookmarkQuizzes = collect([$bookmarkQuizzes]);
            }
            foreach ($bookmarkQuizzes as $quiz) {
                Bookmark::firstOrCreate([
                    'student_id' => $student->id,
                    'quiz_id' => $quiz->id,
                ]);
            }
        }
        $this->command?->info('Seeded bookmarks.');

        // ---------------------------------------------------------------
        // 7. ANNOUNCEMENTS (created by admin) + read tracking
        // ---------------------------------------------------------------
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $announcements = collect([
                ['title' => 'Welcome to Quizora', 'body' => 'The new semester quiz schedule is now live. Please check your dashboard.', 'audience' => 'all', 'type' => 'info'],
                ['title' => 'Scheduled Maintenance', 'body' => 'The platform will be briefly unavailable this weekend for maintenance.', 'audience' => 'all', 'type' => 'warning'],
                ['title' => 'New Question Bank Feature', 'body' => 'Teachers can now bulk import questions via CSV from the Question Bank page.', 'audience' => 'teachers', 'type' => 'success'],
                ['title' => 'Exam Season Reminder', 'body' => 'Make sure to attempt your assigned quizzes before the deadline.', 'audience' => 'students', 'type' => 'info'],
            ])->map(function ($a) use ($admin) {
                return Announcement::create($a + ['is_active' => true, 'created_by' => $admin->id]);
            });

            // Mark some announcements as read by some users
            $allUsers = $teachers->merge($students);
            foreach ($announcements as $announcement) {
                foreach ($allUsers->random(rand(5, $allUsers->count())) as $user) {
                    AnnouncementRead::firstOrCreate([
                        'user_id' => $user->id,
                        'announcement_id' => $announcement->id,
                    ], ['read_at' => now()->subDays(rand(0, 10))]);
                }
            }
        }
        $this->command?->info('Seeded announcements.');

        // ---------------------------------------------------------------
        // 8. NOTIFICATIONS (a few per user)
        // ---------------------------------------------------------------
        $allUsers = $teachers->merge($students);
        foreach ($allUsers as $user) {
            $notifCount = rand(1, 4);
            for ($i = 0; $i < $notifCount; $i++) {
                AppNotification::create([
                    'user_id' => $user->id,
                    'type' => ['quiz', 'result', 'system'][array_rand([0, 1, 2])],
                    'title' => ['New quiz available', 'Your result is ready', 'Account update'][array_rand([0, 1, 2])],
                    'body' => 'This is a sample notification generated for demo purposes.',
                    'link' => null,
                    'read_at' => rand(0, 1) ? now()->subDays(rand(0, 5)) : null,
                ]);
            }
        }
        $this->command?->info('Seeded notifications.');

        $this->command?->info('Demo data seeding complete.');
    }

    /**
     * Create 4 options for a question/bank-question with exactly one correct answer.
     */
    protected function createOptionsFor($owner, string $optionClass, string $foreignKey): void
    {
        $correctIndex = rand(0, 3);
        $letters = ['A', 'B', 'C', 'D'];
        for ($i = 0; $i < 4; $i++) {
            $optionClass::create([
                $foreignKey => $owner->id,
                'option_text' => 'Option ' . $letters[$i] . ' for "' . Str::limit($owner->question_text, 30) . '"',
                'is_correct' => $i === $correctIndex,
                'order' => $i + 1,
            ]);
        }
    }

    /**
     * Generate a plausible-looking question text without external fake data packages.
     */
    protected function fakeQuestionText(string $category, int $seed, string $type = 'mcq'): string
    {
        $templates = [
            'Which of the following best describes a core concept in %s?',
            'What is the correct term used in %s for this scenario?',
            'Identify the correct statement related to %s topic %d.',
            'Which option correctly completes the statement about %s?',
            'In the context of %s, which choice is accurate?',
        ];
        $template = $templates[$seed % count($templates)];

        if ($type === 'true_false') {
            return 'True or False: This statement about ' . $category . ' (item #' . ($seed + 1) . ') is correct.';
        }
        if ($type === 'short_answer') {
            return 'Briefly explain a key idea from ' . $category . ' related to topic #' . ($seed + 1) . '.';
        }

        return sprintf($template, $category, $seed + 1);
    }
}
