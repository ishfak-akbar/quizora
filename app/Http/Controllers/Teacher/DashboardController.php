<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Attempt;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();

        $totalQuizzes = Quiz::where('teacher_id', $teacher->id)->count();

        $activeQuizzes = Quiz::where('teacher_id', $teacher->id)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->count();

        $totalSubmissions = Attempt::whereHas('quiz', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->where('status', 'submitted')->count();

        $totalStudents = Attempt::whereHas('quiz', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->distinct('student_id')->count('student_id');

        $recentQuizzes = Quiz::where('teacher_id', $teacher->id)
            ->withCount([
                'attempts as total_attempts',
                'attempts as submitted_attempts' => function ($q) {
                    $q->where('status', 'submitted');
                }
            ])
            ->latest()
            ->take(5)
            ->get();

        $quizzes = Quiz::where('teacher_id', $teacher->id)
            ->where('status', 'active')
            ->get();

        return view('teacher.dashboard', compact(
            'totalQuizzes',
            'activeQuizzes',
            'totalSubmissions',
            'totalStudents',
            'recentQuizzes',
            'quizzes'
        ));
    }
    public function leaderboard($quizId)
    {
        $teacher = Auth::user();

        $quiz = Quiz::where('id', $quizId)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $leaderboard = Attempt::where('quiz_id', $quiz->id)
            ->where('status', 'submitted')
            ->with('student')
            ->orderByDesc('score')
            ->get()
            ->map(function ($attempt) {
                return [
                    'name'       => $attempt->student->name,
                    'initials'   => strtoupper(substr($attempt->student->name, 0, 1) . (strpos($attempt->student->name, ' ') ? substr($attempt->student->name, strpos($attempt->student->name, ' ') + 1, 1) : '')),
                    'score'      => $attempt->score_percentage,
                    'raw_score'  => $attempt->score,
                    'total'      => $attempt->total_marks,
                ];
            });

        return response()->json($leaderboard);
    }
}
