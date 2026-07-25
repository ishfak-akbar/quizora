<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\BankQuestion;
use App\Models\BankOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionBankController extends Controller
{
    public function index(Request $request)
    {
        $query = BankQuestion::where('teacher_id', Auth::id())->with('options');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('question_text', 'like', '%' . $request->search . '%')
                    ->orWhere('category', 'like', '%' . $request->search . '%')
                    ->orWhere('tags', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $questions = $query->latest()->get();

        $categories = BankQuestion::where('teacher_id', Auth::id())
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        $totalQuestions = $questions->count();
        $totalCategories = $categories->count();
        $addedThisWeek = BankQuestion::where('teacher_id', Auth::id())
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        return view('teacher.question-bank', compact(
            'questions',
            'categories',
            'totalQuestions',
            'totalCategories',
            'addedThisWeek'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'marks'         => 'required|integer|min:1',
            'category'      => 'nullable|string|max:100',
            'tags'          => 'nullable|string|max:255',
            'options'       => 'required|array|size:4',
            'options.*'     => 'required|string',
            'correct'       => 'required|integer|between:0,3',
        ]);

        $bankQuestion = BankQuestion::create([
            'teacher_id'    => Auth::id(),
            'question_text' => $request->question_text,
            'marks'         => $request->marks,
            'category'      => $request->category,
            'tags'          => $request->tags,
        ]);

        foreach ($request->options as $index => $optText) {
            BankOption::create([
                'bank_question_id' => $bankQuestion->id,
                'option_text'      => $optText,
                'is_correct'       => $index == $request->correct,
                'order'            => $index,
            ]);
        }

        return back()->with('success', 'Question added to bank.');
    }

    public function destroy(BankQuestion $bankQuestion)
    {
        if ($bankQuestion->teacher_id !== Auth::id()) {
            abort(403);
        }

        $bankQuestion->delete();

        return response()->json(['success' => true]);
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array']);

        BankQuestion::whereIn('id', $request->ids)
            ->where('teacher_id', Auth::id())
            ->delete();

        return response()->json(['success' => true]);
    }

    public function fetchByIds(Request $request)
    {
        $request->validate(['ids' => 'required|array']);

        $questions = BankQuestion::whereIn('id', $request->ids)
            ->where('teacher_id', Auth::id())
            ->with('options')
            ->get()
            ->map(fn($q) => [
                'id'      => $q->id,
                'text'    => $q->question_text,
                'marks'   => $q->marks,
                'options' => $q->options->map(fn($o) => [
                    'text'       => $o->option_text,
                    'is_correct' => $o->is_correct,
                ])->values(),
            ]);

        return response()->json($questions);
    }
}
