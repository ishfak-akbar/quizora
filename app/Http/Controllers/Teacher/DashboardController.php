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
    public function resultsSummary($quizId)
    {
        $teacher = Auth::user();

        $quiz = Quiz::where('id', $quizId)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $attempts = Attempt::where('quiz_id', $quiz->id)
            ->where('status', 'submitted')
            ->get();

        $submissions = $attempts->count();
        $avg = $submissions > 0
            ? round($attempts->avg(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0))
            : 0;
        $highest = $submissions > 0
            ? round($attempts->max(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0))
            : 0;

        return response()->json([
            'submissions' => $submissions,
            'avg'         => $avg . '%',
            'highest'     => $highest . '%',
        ]);
    }

    public function students()
    {
        $teacher = Auth::user();

        // 1. Fetch all student IDs who have interacted with this teacher's quizzes
        $teacherQuizIds = Quiz::where('teacher_id', $teacher->id)->pluck('id');

        // 2. Base query for this teacher's student attempts
        $baseAttemptsQuery = Attempt::whereIn('quiz_id', $teacherQuizIds)->where('status', 'submitted');

        // --- CARDS/STATS LOGIC ---
        $totalStudentsCount = (clone $baseAttemptsQuery)->distinct('student_id')->count('student_id');

        // Active this month (June 2026)
        $activeThisMonth = (clone $baseAttemptsQuery)->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->distinct('student_id')
            ->count('student_id');

        $totalAttemptsCount = (clone $baseAttemptsQuery)->count();

        // Overall Average Score calculation across all submissions
        $allAttempts = (clone $baseAttemptsQuery)->get();
        $avgScore = $allAttempts->count() > 0
            ? round($allAttempts->avg(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0))
            : 0;

        // --- DATA TABLE LOGIC ---
        // Pull unique students with eager loaded user data, and compute aggregates on the fly
        $students = User::whereHas('attempts', function ($q) use ($teacherQuizIds) {
            $q->whereIn('quiz_id', $teacherQuizIds)->where('status', 'submitted');
        })
            ->with(['attempts' => function ($q) use ($teacherQuizIds) {
                $q->whereIn('quiz_id', $teacherQuizIds)->where('status', 'submitted');
            }])
            ->get()
            ->map(function ($student) {
                $studentAttempts = $student->attempts;

                $quizzesTaken = $studentAttempts->count();

                $avg = $quizzesTaken > 0
                    ? round($studentAttempts->avg(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0))
                    : 0;

                // Simple calculation for passed quizzes (assuming passing score is >= 50%)
                $quizzesPassed = $studentAttempts->filter(fn($a) => $a->total_marks > 0 ? (($a->score / $a->total_marks) * 100) >= 50 : false)->count();

                $lastActiveAttempt = $studentAttempts->sortByDesc('created_at')->first();
                $lastActiveDate = $lastActiveAttempt ? $lastActiveAttempt->created_at : null;

                // Determine status badge based on timing window
                if ($lastActiveDate && $lastActiveDate->greaterThanOrEqualTo(now()->subDays(7))) {
                    $status = 'active';
                } elseif ($lastActiveDate && $lastActiveDate->greaterThanOrEqualTo(now()->subMonths(1))) {
                    $status = 'recent';
                } else {
                    $status = 'inactive';
                }

                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'initial' => strtoupper(substr($student->name, 0, 1)),
                    'quizzes_taken' => $quizzesTaken,
                    'quizzes_passed' => $quizzesPassed,
                    'avg_score' => $avg,
                    'last_active_raw' => $lastActiveDate,
                    'last_active' => $lastActiveDate ? $lastActiveDate->diffForHumans() : 'Never',
                    'status' => $status
                ];
            });

        return view('teacher.students', compact(
            'totalStudentsCount',
            'activeThisMonth',
            'avgScore',
            'totalAttemptsCount',
            'students'
        ));
    }
    public function questionBank()
    {
        return view('teacher.question-bank');
    }
}
