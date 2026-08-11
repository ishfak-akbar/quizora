<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attempt;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class QuizController extends Controller
{
    public function create()
    {
        return view('teacher.quiz.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'              => 'required|string|max:255',
            'description'        => 'nullable|string',
            'time_limit'         => 'nullable|integer|min:1',
            'max_attempts'       => 'required|integer|min:1',
            'starts_at'          => 'nullable|date',
            'ends_at'            => 'nullable|date|after_or_equal:starts_at',
            'shuffle_questions'  => 'boolean',
            'show_results'       => 'boolean',
            'status'             => 'required|in:draft,active',
            'questions'          => 'required|array|min:1',
            'questions.*.text'   => 'required|string',
            'questions.*.marks'  => 'required|integer|min:1',
            'questions.*.correct' => 'required|integer|between:0,3',
            'questions.*.options' => 'required|array|size:4',
            'visibility'     => 'required|in:public,private',
            'category'       => 'required|string|max:100',
            'difficulty'     => 'required|in:easy,medium,hard',
            'tags'           => 'nullable|string|max:255',
            'passing_score'  => 'nullable|integer|min:0|max:100',
            'proposed_code'  => 'nullable|string|size:6',
        ]);

        $accessCode = $request->visibility === 'private'
            ? $this->resolveAccessCode($request->proposed_code)
            : null;

        $quiz = Quiz::create([
            'teacher_id'        => Auth::id(),
            'title'             => $request->title,
            'description'       => $request->description,
            'type'              => 'mcq',
            'status'            => $request->status,
            'time_limit'        => $request->time_limit,
            'max_attempts'      => $request->max_attempts,
            'starts_at'         => $request->starts_at,
            'ends_at'           => $request->ends_at,
            'shuffle_questions'  => $request->boolean('shuffle_questions'),
            'show_results'      => $request->boolean('show_results'),
            'visibility'        => $request->visibility,
            'access_code'       => $accessCode,
            'category'          => $request->category,
            'difficulty'        => $request->difficulty,
            'tags'              => $request->tags,
            'passing_score'     => $request->passing_score,
        ]);

        foreach ($request->questions as $index => $q) {
            $question = Question::create([
                'quiz_id'       => $quiz->id,
                'question_text' => $q['text'],
                'type'          => 'mcq',
                'marks'         => $q['marks'],
                'order'         => $index,
            ]);

            foreach ($q['options'] as $optIndex => $optText) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $optText,
                    'is_correct'  => $optIndex == $q['correct'],
                    'order'       => $optIndex,
                ]);
            }

            $alreadyInBank = \App\Models\BankQuestion::where('teacher_id', $quiz->teacher_id)
                ->where('question_text', $q['text'])
                ->exists();

            if (!$alreadyInBank) {
                $bankQuestion = \App\Models\BankQuestion::create([
                    'teacher_id'    => $quiz->teacher_id,
                    'question_text' => $q['text'],
                    'marks'         => $q['marks'],
                    'category'      => $quiz->category,
                    'tags'          => $quiz->tags,
                ]);

                foreach ($q['options'] as $optIndex => $optText) {
                    \App\Models\BankOption::create([
                        'bank_question_id' => $bankQuestion->id,
                        'option_text'      => $optText,
                        'is_correct'       => $optIndex == $q['correct'],
                        'order'            => $optIndex,
                    ]);
                }
            }
        }
        if ($quiz->status === 'active' && $quiz->visibility === 'public') {
            $this->notifyStudentsOfNewQuiz($quiz);
        }

        return redirect()->route('teacher.dashboard')
            ->with('success', 'Quiz created successfully!');
    }
    private function notifyStudentsOfNewQuiz(Quiz $quiz)
    {
        $followerIds = Attempt::whereHas('quiz', function ($q) use ($quiz) {
            $q->where('teacher_id', $quiz->teacher_id);
        })
            ->distinct()
            ->pluck('student_id');

        $categoryInterestedIds = Attempt::whereHas('quiz', function ($q) use ($quiz) {
            $q->where('category', $quiz->category);
        })
            ->distinct()
            ->pluck('student_id')
            ->diff($followerIds);

        foreach ($followerIds as $studentId) {
            \App\Models\AppNotification::notify(
                $studentId,
                'new_quiz_followed_teacher',
                "New quiz from a teacher you've learned from: \"{$quiz->title}\"",
                $quiz->category,
                route('student.quiz.detail', $quiz->id)
            );
        }

        foreach ($categoryInterestedIds as $studentId) {
            \App\Models\AppNotification::notify(
                $studentId,
                'new_quiz',
                "New quiz in {$quiz->category}: \"{$quiz->title}\"",
                null,
                route('student.quiz.detail', $quiz->id)
            );
        }
    }

    public function destroy(Quiz $quiz)
    {
        if ($quiz->teacher_id !== Auth::id()) {
            abort(403);
        }

        $quiz->delete();

        return redirect()->back()
            ->with('success', 'Quiz deleted successfully.');
    }

    public function edit(Quiz $quiz)
    {
        if ($quiz->teacher_id !== Auth::id()) {
            abort(403);
        }

        $quiz->load('questions.options');
        return view('teacher.quiz.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        if ($quiz->teacher_id !== Auth::id()) {
            abort(403);
        }
        $wasNewlyPublished = $quiz->status !== 'active' && $request->status === 'active';

        $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'nullable|string',
            'time_limit'        => 'nullable|integer|min:1',
            'max_attempts'      => 'required|integer|min:1',
            'starts_at'         => 'nullable|date',
            'ends_at'           => 'nullable|date|after_or_equal:starts_at',
            'shuffle_questions' => 'boolean',
            'show_results'      => 'boolean',
            'status'            => 'required|in:draft,active',
            'questions'         => 'required|array|min:1',
            'questions.*.text'  => 'required|string',
            'questions.*.marks' => 'required|integer|min:1',
            'questions.*.correct' => 'required|integer|between:0,3',
            'questions.*.options' => 'required|array|size:4',
            'visibility'     => 'required|in:public,private',
            'category'       => 'required|string|max:100',
            'difficulty'     => 'required|in:easy,medium,hard',
            'tags'           => 'nullable|string|max:255',
            'passing_score'  => 'nullable|integer|min:0|max:100',
        ]);

        $accessCode = $request->visibility === 'private'
            ? $this->resolveAccessCode($request->proposed_code)
            : null;

        $quiz->update([
            'title'             => $request->title,
            'description'       => $request->description,
            'time_limit'        => $request->time_limit,
            'max_attempts'      => $request->max_attempts,
            'starts_at'         => $request->starts_at,
            'ends_at'           => $request->ends_at,
            'shuffle_questions'  => $request->boolean('shuffle_questions'),
            'show_results'      => $request->boolean('show_results'),
            'status'            => $request->status,
            'visibility'        => $request->visibility,
            'access_code'       => $accessCode,
            'category'          => $request->category,
            'difficulty'        => $request->difficulty,
            'tags'              => $request->tags,
            'passing_score'     => $request->passing_score,
        ]);

        $quiz->questions()->delete();

        foreach ($request->questions as $index => $q) {
            $question = Question::create([
                'quiz_id'       => $quiz->id,
                'question_text' => $q['text'],
                'type'          => 'mcq',
                'marks'         => $q['marks'],
                'order'         => $index,
            ]);

            foreach ($q['options'] as $optIndex => $optText) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $optText,
                    'is_correct'  => $optIndex == $q['correct'],
                    'order'       => $optIndex,
                ]);
            }

            $alreadyInBank = \App\Models\BankQuestion::where('teacher_id', $quiz->teacher_id)
                ->where('question_text', $q['text'])
                ->exists();

            if (!$alreadyInBank) {
                $bankQuestion = \App\Models\BankQuestion::create([
                    'teacher_id'    => $quiz->teacher_id,
                    'question_text' => $q['text'],
                    'marks'         => $q['marks'],
                    'category'      => $quiz->category,
                    'tags'          => $quiz->tags,
                ]);

                foreach ($q['options'] as $optIndex => $optText) {
                    \App\Models\BankOption::create([
                        'bank_question_id' => $bankQuestion->id,
                        'option_text'      => $optText,
                        'is_correct'       => $optIndex == $q['correct'],
                        'order'            => $optIndex,
                    ]);
                }
            }
        }
        if ($wasNewlyPublished && $quiz->visibility === 'public') {
            $this->notifyStudentsOfNewQuiz($quiz);
        }

        return redirect()->route('teacher.dashboard')
            ->with('success', 'Quiz updated successfully.');
    }
    public function index()
    {
        $quizzes = Quiz::where('teacher_id', Auth::id())
            ->withCount([
                'questions',
                'attempts',
                'attempts as submitted_count' => function ($q) {
                    $q->where('status', 'submitted');
                }
            ])
            ->with(['attempts' => function ($q) {
                $q->where('status', 'submitted');
            }])
            ->latest()
            ->get()
            ->each(function ($quiz) {
                $quiz->avg_score = $quiz->attempts->count() > 0
                    ? round($quiz->attempts->avg(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0))
                    : null;
            });

        return view('teacher.quizzes', compact('quizzes'));
    }

    public function results()
    {
        $quizzes = Quiz::where('teacher_id', Auth::id())
            ->withCount([
                'attempts',
                'attempts as submitted_count' => fn($q) => $q->where('status', 'submitted')
            ])
            ->latest()
            ->get();

        return view('teacher.results', compact('quizzes'));
    }

    public function quizResults(Quiz $quiz)
    {
        if ($quiz->teacher_id !== Auth::id()) abort(403);

        $attempts = Attempt::where('quiz_id', $quiz->id)
            ->where('status', 'submitted')
            ->with('student')
            ->orderByDesc('score')
            ->get()
            ->map(function ($attempt) {
                return [
                    'name'       => $attempt->student->name,
                    'email'      => $attempt->student->email,
                    'score'      => $attempt->score,
                    'total'      => $attempt->total_marks,
                    'percentage' => $attempt->score_percentage,
                    'submitted'  => $attempt->submitted_at->format('M d, Y h:i A'),
                ];
            });

        $stats = [
            'submissions' => $attempts->count(),
            'avg'         => $attempts->count() > 0 ? round($attempts->avg('percentage')) : 0,
            'highest'     => $attempts->count() > 0 ? $attempts->max('percentage') : 0,
            'lowest'      => $attempts->count() > 0 ? $attempts->min('percentage') : 0,
        ];

        return response()->json(['attempts' => $attempts, 'stats' => $stats]);
    }

    public function leaderboard()
    {
        $quizzes = Quiz::where('teacher_id', Auth::id())
            ->latest()
            ->get();

        return view('teacher.leaderboard', compact('quizzes'));
    }

    public function students()
    {
        return view('teacher.students');
    }

    public function settings()
    {
        $user = User::findOrFail(Auth::id());
        return view('teacher.settings', ['user' => $user]);
    }

    public function updateSettings(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $section = $request->input('section', 'profile');

        if ($section === 'profile') {
            $validated = $request->validate([
                'name'          => 'required|string|max:255',
                'email'         => 'required|email|unique:users,email,' . $user->id,
                'phone'         => 'nullable|string|max:20',
                'date_of_birth' => 'nullable|date|before:today',
                'gender'        => 'nullable|in:male,female,other,prefer_not_to_say',
                'location'      => 'nullable|string|max:100',
                'bio'           => 'nullable|string|max:300',
                'avatar'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:20480',
            ]);

            $user->fill(collect($validated)->except('avatar')->toArray());
            $user->save();

            if ($request->hasFile('avatar')) {
                // delete old if it was a real storage path
                if ($user->avatar && !str_contains($user->avatar, 'tmp') && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
                }

                $user->avatar = $request->file('avatar')->store('avatars', 'public');
                $user->save();
            }

            if ($request->boolean('remove_avatar')) {
                if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = null;
                $user->save();
            }
        } elseif ($section === 'professional') {
            $validated = $request->validate([
                'institution' => 'nullable|string|max:200',
                'designation' => 'nullable|string|max:150',
            ]);
            $user->fill($validated);
            $user->save();
        } else {
            return back()->withErrors(['section' => 'Invalid settings section.']);
        }

        return redirect()->route('teacher.settings', ['tab' => $section])
            ->with('success', ucfirst($section) . ' updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'current_password' => 'required|current_password',
            'password'          => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('teacher.settings')->with('success', 'Password updated successfully.');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = User::findOrFail(Auth::id());
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Account deleted.');
    }
    public function generateAccessCode(Quiz $quiz)
    {
        if ($quiz->teacher_id !== Auth::id()) {
            abort(403);
        }

        if ($quiz->visibility !== 'private') {
            return back()->with('error', 'Only private quizzes can have an access code.');
        }

        $code = $this->createUniqueCode();

        $quiz->update(['access_code' => $code]);

        return back()->with('success', "Access code generated: {$code}");
    }

    private function createUniqueCode()
    {
        $chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (Quiz::where('access_code', $code)->exists());

        return $code;
    }
    private function resolveAccessCode(?string $proposed): string
    {
        $code = strtoupper(trim((string) $proposed));

        if (preg_match('/^[A-HJ-NP-Z2-9]{6}$/', $code) && !Quiz::where('access_code', $code)->exists()) {
            return $code;
        }

        return $this->createUniqueCode();
    }
    public function print(Quiz $quiz)
    {
        if ($quiz->teacher_id !== Auth::id()) {
            abort(403);
        }

        $quiz->load(['questions' => function ($q) {
            $q->orderBy('order');
        }, 'questions.options' => function ($q) {
            $q->orderBy('order');
        }]);

        $totalMarks = $quiz->questions->sum('marks');
        $includeAnswers = request()->boolean('with_answers');

        return view('teacher.quiz.print', compact('quiz', 'totalMarks', 'includeAnswers'));
    }
    public function view(Quiz $quiz)
    {
        if ($quiz->teacher_id !== Auth::id()) {
            abort(403);
        }

        $quiz->load(['questions' => function ($q) {
            $q->orderBy('order');
        }, 'questions.options' => function ($q) {
            $q->orderBy('order');
        }]);

        $totalMarks = $quiz->questions->sum('marks');
        $submittedCount = Attempt::where('quiz_id', $quiz->id)->where('status', 'submitted')->count();

        return view('teacher.quiz.view', compact('quiz', 'totalMarks', 'submittedCount'));
    }
    public function importCsv(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'csv_file' => 'required|file|mimes:csv,txt|max:20480',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            return back()->with('error', 'Could not read this file.');
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'The CSV file appears to be empty.');
        }

        $header = array_map(fn($h) => strtolower(trim($h)), $header);
        $required = ['question', 'option_a', 'option_b', 'option_c', 'option_d', 'correct'];

        foreach ($required as $col) {
            if (!in_array($col, $header)) {
                fclose($handle);
                return back()->with('error', "Missing required column: {$col}");
            }
        }

        $questions = [];
        $rowErrors = [];
        $rowNum = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;

            if (count(array_filter($row)) === 0) continue;

            $data = array_combine($header, array_pad($row, count($header), null));

            $questionText = trim($data['question'] ?? '');
            $options = [
                trim($data['option_a'] ?? ''),
                trim($data['option_b'] ?? ''),
                trim($data['option_c'] ?? ''),
                trim($data['option_d'] ?? ''),
            ];
            $correctRaw = strtoupper(trim($data['correct'] ?? ''));
            $marks = is_numeric($data['marks'] ?? null) ? (int) $data['marks'] : 1;

            if (empty($questionText)) {
                $rowErrors[] = "Row {$rowNum}: question text is empty — skipped.";
                continue;
            }

            if (in_array('', $options, true)) {
                $rowErrors[] = "Row {$rowNum}: one or more options are empty — skipped.";
                continue;
            }

            $correctIndex = match ($correctRaw) {
                'A', '1' => 0,
                'B', '2' => 1,
                'C', '3' => 2,
                'D', '4' => 3,
                default => null,
            };

            if ($correctIndex === null) {
                $rowErrors[] = "Row {$rowNum}: invalid 'correct' value (\"{$correctRaw}\") — skipped.";
                continue;
            }

            $questions[] = [
                'text'    => $questionText,
                'marks'   => $marks > 0 ? $marks : 1,
                'options' => $options,
                'correct' => $correctIndex,
            ];
        }

        fclose($handle);

        if (empty($questions)) {
            return back()->with('error', 'No valid questions found in this file.')
                ->with('row_errors', $rowErrors);
        }

        $quiz = \DB::transaction(function () use ($request, $questions) {
            $quiz = Quiz::create([
                'teacher_id'        => Auth::id(),
                'title'             => $request->title,
                'type'              => 'mcq',
                'status'            => 'draft',
                'visibility'        => 'public',
                'category'          => 'General',
                'difficulty'        => 'medium',
                'max_attempts'      => 1,
                'show_results'      => true,
                'shuffle_questions' => false,
            ]);

            foreach ($questions as $index => $q) {
                $question = Question::create([
                    'quiz_id'       => $quiz->id,
                    'question_text' => $q['text'],
                    'type'          => 'mcq',
                    'marks'         => $q['marks'],
                    'order'         => $index,
                ]);

                foreach ($q['options'] as $optIndex => $optText) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $optText,
                        'is_correct'  => $optIndex == $q['correct'],
                        'order'       => $optIndex,
                    ]);
                }
            }

            return $quiz;
        });

        $successMsg = count($questions) . ' question(s) imported successfully. Review and publish your quiz.';

        return redirect()->route('teacher.quiz.edit', $quiz->id)
            ->with('success', $successMsg)
            ->with('row_errors', $rowErrors);
    }

    public function csvTemplate()
    {
        $csv = "question,option_a,option_b,option_c,option_d,correct,marks\n";
        $csv .= "What is 2+2?,3,4,5,6,B,1\n";
        $csv .= "Capital of France?,London,Berlin,Paris,Madrid,C,1\n";

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="quizora-quiz-template.csv"',
        ]);
    }
    public function importPage()
    {
        return view('teacher.quiz-import');
    }
}
