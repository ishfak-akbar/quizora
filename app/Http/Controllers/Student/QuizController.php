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
        if ($quiz->status !== 'active' || $quiz->visibility !== 'public') {
            abort(404);
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
        if ($quiz->status !== 'active' || $quiz->visibility !== 'public') {
            abort(404);
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

        return view('student.take-quiz', compact('quiz', 'questions', 'totalQuestions'));
    }
    public function submit(Request $request, Quiz $quiz)
    {
        $student = Auth::user();

        $attemptCount = Attempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->where('status', 'submitted')
            ->count();

        if ($attemptCount >= $quiz->max_attempts) {
            return redirect()->route('student.quiz.detail', $quiz->id);
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
            'started_at'   => now(),
            'submitted_at' => now(),
        ]);

        foreach ($answerRows as $row) {
            $attempt->answers()->create($row);
        }

        return redirect()->route('student.quiz.result', $quiz->id);
    }
}
