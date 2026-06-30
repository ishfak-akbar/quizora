<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Models\Quiz;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Models\User;

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

        [$recommendedQuizzes, $isAiRecommended] = $this->getRecommendedQuizzes($student);

        return view('student.dashboard', compact(
            'totalAttempts',
            'avgScore',
            'bestScore',
            'quizzesPassed',
            'recentAttempts',
            'bookmarkCount',
            'recommendedQuizzes',
            'isAiRecommended'
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
    private function getRecommendedQuizzes($student)
    {
        $totalAttempts = Attempt::where('student_id', $student->id)
            ->where('status', 'submitted')
            ->count();

        if ($totalAttempts === 0) {
            $fallback = Quiz::where('status', 'active')
                ->where('visibility', 'public')
                ->withCount('questions')
                ->inRandomOrder()
                ->take(4)
                ->get();

            return [$fallback, false];
        }

        $cacheKey = "recommended_quizzes_{$student->id}";
        $cached = Cache::get($cacheKey);

        //Reuse cache if it's still valid for today AND no new attempts since it was generated
        if ($cached && $cached['attempt_count'] === $totalAttempts && now()->lt($cached['expires_at'])) {
            $quizzes = Quiz::whereIn('id', $cached['quiz_ids'])
                ->where('status', 'active')
                ->where('visibility', 'public')
                ->withCount('questions')
                ->get()
                ->sortBy(fn($q) => array_search($q->id, $cached['quiz_ids']))
                ->values();

            if ($quizzes->count() > 0) {
                return [$quizzes, true];
            }
        }

        //Generate fresh AI recommendations
        $aiResult = $this->generateAiRecommendations($student);

        if ($aiResult === null) {
            //Groq failed — fall back to random
            $fallback = Quiz::where('status', 'active')
                ->where('visibility', 'public')
                ->withCount('questions')
                ->inRandomOrder()
                ->take(4)
                ->get();

            return [$fallback, false];
        }

        Cache::put($cacheKey, [
            'quiz_ids'      => $aiResult->pluck('id')->toArray(),
            'attempt_count' => $totalAttempts,
            'expires_at'    => now()->addDay(),
        ], now()->addDay());

        return [$aiResult, true];
    }

    private function generateAiRecommendations($student)
    {
        //Build category performance summary
        $attempts = Attempt::where('student_id', $student->id)
            ->where('status', 'submitted')
            ->with('quiz')
            ->get();

        $categoryStats = $attempts
            ->filter(fn($a) => $a->quiz && $a->total_marks > 0)
            ->groupBy(fn($a) => $a->quiz->category ?? 'General')
            ->map(function ($group) {
                return round($group->avg(fn($a) => ($a->score / $a->total_marks) * 100));
            })
            ->sortBy(fn($score) => $score) // weakest first
            ->take(5);

        $attemptedQuizIds = $attempts->pluck('quiz_id')->unique();

        $availableQuizzes = Quiz::where('status', 'active')
            ->where('visibility', 'public')
            ->whereNotIn('id', $attemptedQuizIds) // don't recommend already-attempted quizzes
            ->get(['id', 'title', 'category', 'difficulty', 'tags']);

        if ($availableQuizzes->isEmpty()) {
            return null;
        }

        $performanceLines = $categoryStats->isEmpty()
            ? "No category performance data available."
            : $categoryStats->map(fn($score, $cat) => "- {$cat}: {$score}% average")->implode("\n");

        $quizListLines = $availableQuizzes->map(function ($q) {
            return "ID:{$q->id} | Title: {$q->title} | Category: " . ($q->category ?? 'General') . " | Difficulty: {$q->difficulty}";
        })->implode("\n");

        $prompt = <<<PROMPT
            You are a quiz recommendation engine for a student learning platform called Quizora.

            Student's performance by category (weakest first):
            {$performanceLines}

            Available quizzes the student has NOT yet attempted:
            {$quizListLines}

            Pick exactly 4 quiz IDs that would best help this student improve, prioritizing categories where their score is lowest. If they have no weak categories or insufficient data, pick a varied, sensible set.

            Respond with ONLY raw JSON, no markdown, no explanation, in this exact format:
            {"recommendations": [{"id": 12, "reason": "short reason"}, ...]}
            PROMPT;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.groq.key'),
                'Content-Type'  => 'application/json',
            ])->timeout(10)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'      => 'llama-3.3-70b-versatile',
                'messages'   => [['role' => 'user', 'content' => $prompt]],
                'max_tokens' => 500,
            ]);

            if ($response->failed()) {
                return null;
            }

            $raw = $response->json('choices.0.message.content', '');
            $raw = trim(preg_replace('/```json|```/', '', $raw));

            $parsed = json_decode($raw, true);

            if (!isset($parsed['recommendations']) || !is_array($parsed['recommendations'])) {
                return null;
            }

            $ids = collect($parsed['recommendations'])->pluck('id')->take(4)->toArray();

            $validQuizzes = Quiz::whereIn('id', $ids)
                ->where('status', 'active')
                ->where('visibility', 'public')
                ->withCount('questions')
                ->get()
                ->sortBy(fn($q) => array_search($q->id, $ids))
                ->values();

            return $validQuizzes->count() > 0 ? $validQuizzes : null;
        } catch (\Exception $e) {
            return null;
        }
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
    public function leaderboardData(Quiz $quiz)
    {
        if ($quiz->status !== 'active' || $quiz->visibility !== 'public') {
            abort(404);
        }

        $student = Auth::user();

        $entries = Attempt::where('quiz_id', $quiz->id)
            ->where('status', 'submitted')
            ->with('student')
            ->orderByDesc('score')
            ->get()
            ->map(function ($attempt, $index) use ($student) {
                return [
                    'rank'      => $index + 1,
                    'name'      => $attempt->student->name,
                    'score'     => $attempt->score_percentage,
                    'raw_score' => $attempt->score,
                    'total'     => $attempt->total_marks,
                    'is_me'     => $attempt->student_id === $student->id,
                ];
            });

        $myEntry = $entries->firstWhere('is_me', true);

        return response()->json([
            'entries' => $entries,
            'my_rank' => $myEntry ? $myEntry['rank'] : null,
            'my_score' => $myEntry ? $myEntry['score'] : null,
        ]);
    }

    public function settings()
    {
        $user = User::findOrFail(Auth::id());

        return view('student.settings', ['user' => $user]);
    }

    public function updateSettings(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $section = $request->input('section', 'profile');

        if ($section === 'profile') {
            $validated = $request->validate([
                'name'         => 'required|string|max:255',
                'email'        => 'required|email|unique:users,email,' . $user->id,
                'phone'        => 'nullable|string|max:20',
                'date_of_birth' => 'nullable|date|before:today',
                'gender'       => 'nullable|in:male,female,other,prefer_not_to_say',
                'location'     => 'nullable|string|max:100',
                'bio'          => 'nullable|string|max:300',
                'avatar_color' => 'nullable|string|max:7',
            ]);
        } elseif ($section === 'academic') {
            $validated = $request->validate([
                'institution'     => 'nullable|string|max:200',
                'class_level'     => 'nullable|string|max:100',
                'education_level' => 'nullable|in:ssc,hsc,bachelor,master,other',
                'study_goal'      => 'nullable|in:exam_prep,self_learning,bcs,university_admission,other',
                'preparing_for'   => 'nullable|string|max:200',
            ]);
        } elseif ($section === 'preferences') {
            $validated = $request->validate([
                'preferred_language' => 'nullable|in:english,bangla',
                'target_score'       => 'nullable|integer|min:1|max:100',
            ]);
        } else {
            return back()->withErrors(['section' => 'Invalid settings section.']);
        }

        $user->fill($validated);
        $user->save();

        return redirect()->route('student.settings', ['tab' => $section])
            ->with('success', ucfirst($section) . ' updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('student.settings')->with('success', 'Password updated successfully.');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = User::findOrFail(Auth::id());
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Account deleted.');
    }

    public function profile()
    {
        $student = User::findOrFail(Auth::id());

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

        $bookmarks = Bookmark::where('student_id', $student->id)
            ->with('quiz')
            ->latest()
            ->get();

        $bookmarkCount = $bookmarks->count();

        $memberSince = $student->created_at;

        $heatmapData = Attempt::where('student_id', $student->id)
            ->where('status', 'submitted')
            ->where('submitted_at', '>=', now()->subWeeks(53))
            ->selectRaw('DATE(submitted_at) as day, COUNT(*) as count')
            ->groupBy('day')
            ->pluck('count', 'day');

        $currentStreak = self::getCachedStreak($student->id);

        $totalActiveDays = Attempt::where('student_id', $student->id)
            ->where('status', 'submitted')
            ->selectRaw('DATE(submitted_at) as day')
            ->distinct()
            ->count();

        $maxStreak = $this->calculateMaxStreak($student->id);

        $recentAttempts = $attempts->take(10);

        return view('student.profile', compact(
            'student',
            'totalAttempts',
            'avgScore',
            'bestScore',
            'quizzesPassed',
            'bookmarkCount',
            'bookmarks',
            'memberSince',
            'heatmapData',
            'currentStreak',
            'totalActiveDays',
            'maxStreak',
            'recentAttempts'
        ));
    }

    private function calculateMaxStreak($studentId)
    {
        $activeDates = Attempt::where('student_id', $studentId)
            ->where('status', 'submitted')
            ->selectRaw('DATE(submitted_at) as day')
            ->distinct()
            ->pluck('day')
            ->map(fn($d) => \Carbon\Carbon::parse($d))
            ->sort()
            ->values();

        if ($activeDates->isEmpty()) {
            return 0;
        }

        $maxStreak = 1;
        $currentRun = 1;

        for ($i = 1; $i < $activeDates->count(); $i++) {
            $diff = $activeDates[$i]->diffInDays($activeDates[$i - 1]);
            if ($diff === 1) {
                $currentRun++;
                $maxStreak = max($maxStreak, $currentRun);
            } else {
                $currentRun = 1;
            }
        }

        return $maxStreak;
    }
    public static function getCachedStreak($studentId)
    {
        $cached = Cache::get("student_streak_{$studentId}");

        if ($cached !== null) {
            return $cached;
        }

        //Cache miss — calculate and store
        $controller = new \App\Http\Controllers\Student\QuizController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('calculateStreak');
        $method->setAccessible(true);
        $streak = $method->invoke($controller, $studentId);

        Cache::put("student_streak_{$studentId}", $streak, now()->addDays(7));

        return $streak;
    }
}
