<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function browse(Request $request)
    {
        $query = Quiz::where('status', 'active')
            ->where('visibility', 'public')
            ->withCount(['questions', 'attempts']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('category', 'like', '%' . $request->search . '%')
                    ->orWhere('tags', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        $trending = (clone $query)->orderByDesc('attempts_count')->take(4)->get();
        $latest   = (clone $query)->latest()->take(4)->get();
        $categories = Quiz::where('status', 'active')
            ->where('visibility', 'public')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        $activeCategory = $request->get('category', 'all');
        $search = $request->get('search', '');

        $bookmarkedIds = Bookmark::where('student_id', Auth::id())
        ->pluck('quiz_id')
        ->toArray();

        return view('student.browse', compact(
            'trending',
            'latest',
            'categories',
            'activeCategory',
            'search',
            'bookmarkedIds'
        ));
    }
}
