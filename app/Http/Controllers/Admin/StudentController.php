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
}
