<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Models\Quiz;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user();

        $totalAttempts = Attempt::where('student_id', $student->id)
            ->where('status', 'submitted')
            ->count();

        $attempts = Attempt::where('student_id', $student->id)
            ->where('status', 'submitted')
            ->get();

        $avgScore = $totalAttempts > 0
            ? round($attempts->avg(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0))
            : 0;

        $bestScore = $totalAttempts > 0
            ? round($attempts->max(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0))
            : 0;

        $quizzesPassed = $attempts->filter(
            fn($a) => $a->total_marks > 0 && ($a->score / $a->total_marks) * 100 >= 50
        )->count();

        $recentAttempts = Attempt::where('student_id', $student->id)
            ->where('status', 'submitted')
            ->with('quiz')
            ->latest()
            ->take(5)
            ->get();

        $bookmarkCount = Bookmark::where('student_id', $student->id)->count();

        return view('student.dashboard', compact(
            'totalAttempts',
            'avgScore',
            'bestScore',
            'quizzesPassed',
            'recentAttempts',
            'bookmarkCount'
        ));
    }

    public function results()
    {
        $student = Auth::user();

        $attempts = Attempt::where('student_id', $student->id)
            ->where('status', 'submitted')
            ->with('quiz')
            ->latest()
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

        return view('student.my-results', compact(
            'attempts',
            'totalAttempts',
            'avgScore',
            'bestScore',
            'quizzesPassed'
        ));
    }

    public function leaderboard()
    {
        $quizzes = Quiz::where('status', 'active')
            ->where('visibility', 'public')
            ->latest()
            ->get();

        return view('student.leaderboard', compact('quizzes'));
    }

    public function bookmarks()
    {
        $student = Auth::user();

        $bookmarks = Bookmark::where('student_id', $student->id)
            ->with('quiz')
            ->latest()
            ->get();

        return view('student.bookmarks', compact('bookmarks'));
    }

    public function toggleBookmark(Quiz $quiz)
    {
        $student = Auth::user();

        $bookmark = Bookmark::where('student_id', $student->id)
            ->where('quiz_id', $quiz->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json(['bookmarked' => false]);
        }

        Bookmark::create([
            'student_id' => $student->id,
            'quiz_id'    => $quiz->id,
        ]);

        return response()->json(['bookmarked' => true]);
    }
}
