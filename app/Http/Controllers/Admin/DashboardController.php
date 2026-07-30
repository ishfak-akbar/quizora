<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Attempt;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // ===== Top Metrics =====
        $totalTeachers     = User::where('role', 'teacher')->count();
        $totalStudents     = User::where('role', 'student')->count();
        $totalQuizzes      = Quiz::count();
        $activeQuizzes     = Quiz::where('status', 'active')->count();
        $totalSubmissions  = Attempt::where('status', 'submitted')->count();

        $newTeachersThisWeek = User::where('role', 'teacher')
            ->where('created_at', '>=', now()->subDays(7))->count();

        $newStudentsThisWeek = User::where('role', 'student')
            ->where('created_at', '>=', now()->subDays(7))->count();

        $newUsersThisWeek = $newTeachersThisWeek + $newStudentsThisWeek;

        $submissionsToday = Attempt::where('status', 'submitted')
            ->whereDate('created_at', today())->count();

        $submissionsThisWeek = Attempt::where('status', 'submitted')
            ->where('created_at', '>=', now()->subDays(7))->count();

        // ===== Attention Needed =====
        $teachersWithNoQuizzes = User::where('role', 'teacher')
            ->whereDoesntHave('quizzes')->count();

        // ===== Recent Users =====
        $recentUsers = User::whereIn('role', ['teacher', 'student'])
            ->latest()
            ->take(5)
            ->get();

        // ===== Recent Quizzes =====
        $recentQuizzes = Quiz::with('teacher')
            ->withCount(['attempts as submitted_count' => function ($q) {
                $q->where('status', 'submitted');
            }])
            ->latest()
            ->take(5)
            ->get();

        // ===== Top Teachers (by submissions) =====
        $topTeachers = User::where('role', 'teacher')
            ->withCount(['quizzes as total_submissions' => function ($q) {
                $q->join('attempts', 'quizzes.id', '=', 'attempts.quiz_id')
                    ->where('attempts.status', 'submitted');
            }])
            ->orderByDesc('total_submissions')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalTeachers',
            'totalStudents',
            'totalQuizzes',
            'activeQuizzes',
            'totalSubmissions',
            'newTeachersThisWeek',
            'newStudentsThisWeek',
            'newUsersThisWeek',
            'submissionsToday',
            'submissionsThisWeek',
            'teachersWithNoQuizzes',
            'recentUsers',
            'recentQuizzes',
            'topTeachers'
        ));
    }
}
