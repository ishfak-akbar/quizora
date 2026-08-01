<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $query = Quiz::with('teacher')
            ->withCount(['attempts as submitted_count' => function ($q) {
                $q->where('status', 'submitted');
            }]);

        // Search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                // Real active = status active + not ended + already started
                $query->where('status', 'active')
                    ->where(function ($q) {
                        $q->whereNull('ends_at')
                            ->orWhere('ends_at', '>', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('starts_at')
                            ->orWhere('starts_at', '<=', now());
                    });
            } elseif ($request->status === 'closed') {
                // Closed or naturally ended
                $query->where(function ($q) {
                    $q->where('status', 'closed')
                        ->orWhere(function ($q2) {
                            $q2->where('status', 'active')
                                ->whereNotNull('ends_at')
                                ->where('ends_at', '<=', now());
                        });
                });
            } else {
                // draft
                $query->where('status', $request->status);
            }
        }

        // Filter by visibility
        if ($request->filled('visibility') && in_array($request->visibility, ['public', 'private'])) {
            $query->where('visibility', $request->visibility);
        }

        $quizzes = $query->latest()->paginate(15)->withQueryString();

        if ($request->ajax()) {
            return view('admin.quizzes.index', compact('quizzes'));
        }

        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return back()->with('success', 'Quiz deleted successfully.');
    }

    public function forceClose(Quiz $quiz)
    {
        $quiz->update(['status' => 'closed']);
        return back()->with('success', 'Quiz has been closed.');
    }
}
