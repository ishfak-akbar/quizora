<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attempt;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')
            ->withCount(['attempts as total_attempts' => function ($q) {
                $q->where('status', 'submitted');
            }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->latest()->paginate(15)->withQueryString();

        // Calculate average score for each student
        $students->getCollection()->transform(function ($student) {
            $attempts = Attempt::where('student_id', $student->id)
                ->where('status', 'submitted')
                ->get();

            $student->avg_score = $attempts->count() > 0
                ? round($attempts->avg(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0))
                : null;

            return $student;
        });

        return view('admin.students.index', compact('students'));
    }

    public function show(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $attempts = Attempt::where('student_id', $student->id)
            ->where('status', 'submitted')
            ->with('quiz')
            ->latest('submitted_at')
            ->get();

        $totalAttempts = $attempts->count();

        $avgScore = $totalAttempts > 0
            ? round($attempts->avg(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0))
            : 0;

        $bestScore = $totalAttempts > 0
            ? round($attempts->max(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0))
            : 0;

        $quizzesPassed = $attempts->filter(
            fn($a) => $a->total_marks > 0 && ($a->score / $a->total_marks) * 100 >= 50
        )->count();

        return view('admin.students.show', compact(
            'student',
            'attempts',
            'totalAttempts',
            'avgScore',
            'bestScore',
            'quizzesPassed'
        ));
    }
    public function attempt(User $student, \App\Models\Attempt $attempt)
    {
        if ($student->role !== 'student' || $attempt->student_id !== $student->id) {
            abort(404);
        }

        $attempt->load(['quiz.questions.options', 'answers.option']);

        $quiz = $attempt->quiz;

        $answers = $attempt->answers()->with(['question.options', 'option'])->get();

        $questionIds = $quiz->questions->pluck('id');
        $answeredIds = $answers->pluck('question_id');

        foreach ($quiz->questions as $question) {
            if (!$answeredIds->contains($question->id)) {
                $answers->push((object)[
                    'question_id' => $question->id,
                    'option_id'   => null,
                    'is_correct'  => false,
                    'question'    => $question,
                    'option'      => null,
                ]);
            }
        }

        // Sort by question order
        $answers = $answers->sortBy(fn($a) => $a->question->order ?? 0)->values();

        return view('admin.students.attempt', compact('student', 'quiz', 'attempt', 'answers'));
    }
}
