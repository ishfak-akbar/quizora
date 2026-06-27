<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Models\Quiz;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

        $recommendedQuizzes = Quiz::where('status', 'active')
            ->where('visibility', 'public')
            ->withCount('questions')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('student.dashboard', compact(
            'totalAttempts',
            'avgScore',
            'bestScore',
            'quizzesPassed',
            'recentAttempts',
            'bookmarkCount',
            'recommendedQuizzes'
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
    public function aiTutor()
    {
        return view('student.ai-tutor');
    }

    public function aiChat(Request $request)
    {
        $student = Auth::user();

        $attempts = Attempt::where('student_id', $student->id)
            ->where('status', 'submitted')
            ->with([
                'quiz',
                'answers.question.options',
                'answers.option',
            ])
            ->latest('submitted_at')
            ->get();

        $contextLines = [];
        $contextLines[] = "You are an AI tutor for a student named {$student->name} on the Quizora quiz platform.";
        $contextLines[] = "You have full knowledge of all quizzes this student has attempted. Help them understand topics, explain correct answers, identify weak areas, and motivate them.";
        $contextLines[] = "Be concise, friendly, and educational. Use the student's actual data when answering.";
        $contextLines[] = "";
        $contextLines[] = "=== STUDENT QUIZ HISTORY ===";

        if ($attempts->isEmpty()) {
            $contextLines[] = "This student has not attempted any quizzes yet.";
        }

        foreach ($attempts as $attempt) {
            $pct = $attempt->score_percentage;
            $date = $attempt->submitted_at?->format('M d, Y') ?? 'Unknown date';

            $contextLines[] = "";
            $contextLines[] = "--- Quiz: \"{$attempt->quiz->title}\" ---";
            $contextLines[] = "Category: " . ($attempt->quiz->category ?? 'N/A');
            $contextLines[] = "Difficulty: " . ($attempt->quiz->difficulty ?? 'N/A');
            $contextLines[] = "Attempted on: {$date}";
            $contextLines[] = "Score: {$attempt->score} / {$attempt->total_marks} ({$pct}%)";
            $contextLines[] = "Result: " . ($pct >= 50 ? 'Passed' : 'Failed');
            $contextLines[] = "Questions:";

            foreach ($attempt->answers as $answer) {
                $question = $answer->question;
                if (!$question) continue;

                $correctOption = $question->options->firstWhere('is_correct', true);
                $selectedOption = $answer->option;

                $contextLines[] = "  Q: {$question->question_text}";
                $contextLines[] = "  Student answered: " . ($selectedOption?->option_text ?? 'No answer');
                $contextLines[] = "  Correct answer: " . ($correctOption?->option_text ?? 'N/A');
                $contextLines[] = "  Result: " . ($answer->is_correct ? 'Correct' : 'Wrong') . " ({$answer->marks_obtained}/{$question->marks} marks)";
            }
        }

        $systemPrompt = implode("\n", $contextLines);

        $history = $request->input('history', []);
        $userMessage = $request->input('message', '');

        if (!$userMessage) {
            return response()->json(['error' => 'No message provided'], 422);
        }

        $messages = [['role' => 'system', 'content' => $systemPrompt]];

        foreach ($history as $msg) {
            if (in_array($msg['role'] ?? '', ['user', 'assistant']) && !empty($msg['content'])) {
                $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $userMessage];

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.groq.key'),
            'Content-Type'  => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model'      => 'llama-3.3-70b-versatile',
            'messages'   => $messages,
            'max_tokens' => 1024,
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'AI service error'], 500);
        }

        $data = $response->json();
        $reply = $data['choices'][0]['message']['content'] ?? 'Sorry, I could not generate a response.';

        return response()->json(['reply' => $reply]);
    }
}
