<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attempt;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'teacher')
            ->withCount('quizzes')
            ->withCount(['quizzes as total_submissions' => function ($q) {
                $q->join('attempts', 'quizzes.id', '=', 'attempts.quiz_id')
                    ->where('attempts.status', 'submitted');
            }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $teachers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.teachers.index', compact('teachers'));
    }
    public function show(User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        $teacher->loadCount('quizzes');

        $quizzes = $teacher->quizzes()
            ->withCount(['attempts as submitted_count' => function ($q) {
                $q->where('status', 'submitted');
            }])
            ->latest()
            ->get();

        $totalSubmissions = $quizzes->sum('submitted_count');

        $uniqueStudents = \App\Models\Attempt::whereIn('quiz_id', $quizzes->pluck('id'))
            ->where('status', 'submitted')
            ->distinct('student_id')
            ->count('student_id');

        return view('admin.teachers.show', compact(
            'teacher',
            'quizzes',
            'totalSubmissions',
            'uniqueStudents'
        ));
    }
}
