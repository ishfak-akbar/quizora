<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attempt;

class QuizController extends Controller
{
    public function browse(Request $request)
    {
        $query = Quiz::where('status', 'active')
            ->where('visibility', 'public')
            ->withCount(['questions', 'attempts']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('category', 'like', '%' . $request->search . '%')
                    ->orWhere('tags', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        $trending = (clone $query)->orderByDesc('attempts_count')->take(4)->get();
        $latest   = (clone $query)->latest()->take(4)->get();
        $categories = Quiz::where('status', 'active')
            ->where('visibility', 'public')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        $activeCategory = $request->get('category', 'all');
        $search = $request->get('search', '');

        $bookmarkedIds = Bookmark::where('student_id', Auth::id())
            ->pluck('quiz_id')
            ->toArray();

        return view('student.browse', compact(
            'trending',
            'latest',
            'categories',
            'activeCategory',
            'search',
            'bookmarkedIds'
        ));
    }
    public function detail(Quiz $quiz)
    {
        if (!$quiz->isActive()) {
            abort(404);
        }

        if ($quiz->visibility === 'private') {
            $unlocked = \App\Models\QuizUnlock::where('student_id', Auth::id())
                ->where('quiz_id', $quiz->id)
                ->exists();

            if (!$unlocked) {
                abort(404);
            }
        }

        $student = Auth::user();

        $questionCount = $quiz->questions()->count();

        $attemptCount = Attempt::where('quiz_id', $quiz->id)
            ->where('status', 'submitted')
            ->count();

        $avgScore = $attemptCount > 0
            ? round(
                Attempt::where('quiz_id', $quiz->id)
                    ->where('status', 'submitted')
                    ->get()
                    ->avg(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0)
            )
            : 0;

        $studentAttempts = Attempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->where('status', 'submitted')
            ->count();

        $attemptsLeft = max(0, $quiz->max_attempts - $studentAttempts);

        $isBookmarked = Bookmark::where('student_id', $student->id)
            ->where('quiz_id', $quiz->id)
            ->exists();

        $bookmarkCount = Bookmark::where('student_id', $student->id)->count();

        return view('student.quiz-detail', compact(
            'quiz',
            'questionCount',
            'attemptCount',
            'avgScore',
            'attemptsLeft',
            'isBookmarked',
            'bookmarkCount'
        ));
    }
    public function take(Quiz $quiz)
    {
        if (!$quiz->isActive()) {
            abort(404);
        }

        if ($quiz->visibility === 'private') {
            $unlocked = \App\Models\QuizUnlock::where('student_id', Auth::id())
                ->where('quiz_id', $quiz->id)
                ->exists();

            if (!$unlocked) {
                abort(404);
            }
        }

        $student = Auth::user();

        $attemptCount = Attempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->where('status', 'submitted')
            ->count();

        if ($attemptCount >= $quiz->max_attempts) {
            return redirect()->route('student.quiz.detail', $quiz->id)
                ->with('error', 'You have used all attempts for this quiz.');
        }

        $questionsRaw = $quiz->questions()
            ->with('options')
            ->orderBy('order')
            ->get();

        if ($quiz->shuffle_questions) {
            $questionsRaw = $questionsRaw->shuffle();
        }

        $questions = $questionsRaw->map(fn($q) => [
            'id'            => $q->id,
            'question_text' => $q->question_text,
            'options'       => $q->options->map(fn($o) => [
                'id'          => $o->id,
                'option_text' => $o->option_text,
            ])->values(),
        ])->values();

        $totalQuestions = $questions->count();
        session(["quiz_started_at_{$quiz->id}" => now()]);

        return view('student.take-quiz', compact('quiz', 'questions', 'totalQuestions'));
    }
    public function submit(Request $request, Quiz $quiz)
    {
        $student = Auth::user();

        if (!$quiz->isActive()) {
            abort(404);
        }

        if ($quiz->visibility === 'private') {
            $unlocked = \App\Models\QuizUnlock::where('student_id', $student->id)
                ->where('quiz_id', $quiz->id)
                ->exists();

            if (!$unlocked) {
                abort(404);
            }
        }

        $attemptCount = Attempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->where('status', 'submitted')
            ->count();

        if ($attemptCount >= $quiz->max_attempts) {
            return redirect()->route('student.quiz.detail', $quiz->id);
        }
        if ($quiz->time_limit) {
            $startedAt = session("quiz_started_at_{$quiz->id}");
            if ($startedAt && now()->diffInMinutes($startedAt) > $quiz->time_limit + 1) {
                return redirect()->route('student.quiz.detail', $quiz->id)
                    ->with('error', 'Time limit exceeded. This attempt was not submitted.');
            }
        }

        $questions = $quiz->questions()->with('options')->get()->keyBy('id');

        $totalMarks = 0;
        $score = 0;
        $answerRows = [];

        foreach ($questions as $qId => $question) {
            $totalMarks += $question->marks;
            $selectedOptionId = $request->input("answers.{$qId}");
            $selectedOption = $selectedOptionId
                ? $question->options->firstWhere('id', $selectedOptionId)
                : null;

            $isCorrect = $selectedOption?->is_correct ?? false;
            $marksObtained = $isCorrect ? $question->marks : 0;
            $score += $marksObtained;

            $answerRows[] = [
                'question_id'    => $qId,
                'option_id'      => $selectedOptionId,
                'is_correct'     => $isCorrect,
                'marks_obtained' => $marksObtained,
            ];
        }

        $attempt = Attempt::create([
            'quiz_id'      => $quiz->id,
            'student_id'   => $student->id,
            'status'       => 'submitted',
            'score'        => $score,
            'total_marks'  => $totalMarks,
            'started_at'   => session("quiz_started_at_{$quiz->id}") ?? now(),
            'submitted_at' => now(),
        ]);

        session()->forget("quiz_started_at_{$quiz->id}");

        foreach ($answerRows as $row) {
            $attempt->answers()->create($row);
        }
        $this->updateStreakCache($student->id);
        return redirect()->route('student.quiz.result', $quiz->id);
    }
    public function result(Quiz $quiz)
    {
        $student = Auth::user();

        $attempt = Attempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->where('status', 'submitted')
            ->latest('submitted_at')
            ->firstOrFail();

        $answers = $attempt->answers()
            ->with(['question.options', 'option'])
            ->get();

        return view('student.quiz-result', compact('quiz', 'attempt', 'answers'));
    }
    private function updateStreakCache($studentId)
    {
        $streak = $this->calculateStreak($studentId);
        \Illuminate\Support\Facades\Cache::put("student_streak_{$studentId}", $streak, now()->addDays(7));
    }

    private function calculateStreak($studentId)
    {
        $activeDates = Attempt::where('student_id', $studentId)
            ->where('status', 'submitted')
            ->selectRaw('DATE(submitted_at) as day')
            ->distinct()
            ->pluck('day')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->sort()
            ->values();

        if ($activeDates->isEmpty()) {
            return 0;
        }

        $today = \Carbon\Carbon::today();
        $yesterday = \Carbon\Carbon::yesterday()->format('Y-m-d');
        $todayStr = $today->format('Y-m-d');

        if (!$activeDates->contains($todayStr) && !$activeDates->contains($yesterday)) {
            return 0;
        }

        $streak = 0;
        $cursor = $activeDates->contains($todayStr) ? $today->copy() : $today->copy()->subDay();

        $dateSet = $activeDates->flip();

        while ($dateSet->has($cursor->format('Y-m-d'))) {
            $streak++;
            $cursor->subDay();
        }

        return $streak;
    }
    public function unlockByCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $student = Auth::user();
        $code = strtoupper(trim($request->code));

        $quiz = Quiz::where('access_code', $code)
            ->where('visibility', 'private')
            ->where('status', 'active')
            ->first();

        if (!$quiz) {
            return back()->with('error', 'Invalid or expired code.');
        }

        \App\Models\QuizUnlock::firstOrCreate([
            'student_id' => $student->id,
            'quiz_id'    => $quiz->id,
        ]);

        return redirect()->route('student.quiz.detail', $quiz->id)
            ->with('success', 'Quiz unlocked! You now have permanent access.');
    }
    public function privateQuizzes()
    {
        $student = Auth::user();

        $unlockedQuizIds = \App\Models\QuizUnlock::where('student_id', $student->id)
            ->pluck('quiz_id');

        $unlockedQuizzes = Quiz::whereIn('id', $unlockedQuizIds)
            ->where('status', 'active')
            ->withCount('questions')
            ->latest()
            ->get();

        return view('student.private-quizzes', compact('unlockedQuizzes'));
    }
}
