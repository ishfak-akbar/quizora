<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attempt;

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
        ]);

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
        }

        return redirect()->route('teacher.dashboard')
            ->with('success', 'Quiz created successfully!');
    }

    public function destroy(Quiz $quiz)
    {
        if ($quiz->teacher_id !== Auth::id()) {
            abort(403);
        }

        $quiz->delete();

        return redirect()->route('teacher.dashboard')
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
        ]);

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
            ->latest()
            ->get();

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
}
