<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
