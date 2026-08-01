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
        $activeQuizzes     = Quiz::where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->count();

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

        // ===== Activity Feed (real) =====
        $activity = collect();

        // New teachers
        User::where('role', 'teacher')->latest()->take(3)->get()->each(function ($u) use ($activity) {
            $activity->push([
                'type'  => 'teacher',
                'icon'  => 'ti-school',
                'color' => '#34D399',
                'title' => 'New teacher registered',
                'desc'  => $u->name . ' joined the platform',
                'time'  => $u->created_at,
            ]);
        });

        // New students
        User::where('role', 'student')->latest()->take(3)->get()->each(function ($u) use ($activity) {
            $activity->push([
                'type'  => 'student',
                'icon'  => 'ti-user',
                'color' => '#60A5FA',
                'title' => 'New student registered',
                'desc'  => $u->name . ' joined the platform',
                'time'  => $u->created_at,
            ]);
        });

        // New quizzes
        Quiz::with('teacher')->latest()->take(4)->get()->each(function ($q) use ($activity) {
            $activity->push([
                'type'  => 'quiz',
                'icon'  => 'ti-file-description',
                'color' => '#34D399',
                'title' => 'Quiz published',
                'desc'  => '"' . \Illuminate\Support\Str::limit($q->title, 30) . '" by ' . ($q->teacher->name ?? 'Unknown'),
                'time'  => $q->created_at,
            ]);
        });

        $activityFeed = $activity->sortByDesc('time')->take(8)->values();

        // ===== Growth Chart (last 7 days) =====
        $days = collect();
        $usersData = collect();
        $quizzesData = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $label = $date->format('M d');

            $days->push($label);

            $usersData->push(
                User::whereIn('role', ['teacher', 'student'])
                    ->whereDate('created_at', $date)
                    ->count()
            );

            $quizzesData->push(
                Quiz::whereDate('created_at', $date)->count()
            );
        }

        // ===== Category Distribution =====
        $categories = Quiz::selectRaw("COALESCE(NULLIF(category, ''), 'General') as cat_name")
            ->selectRaw('count(*) as total')
            ->groupBy('cat_name')
            ->orderByDesc('total')
            ->get();

        $categoryLabels = $categories->pluck('cat_name')->toArray();
        $categoryValues = $categories->pluck('total')->toArray();

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
            'activityFeed',
            'days',
            'usersData',
            'quizzesData',
            'categoryLabels',
            'categoryValues'
        ));
    }
}
