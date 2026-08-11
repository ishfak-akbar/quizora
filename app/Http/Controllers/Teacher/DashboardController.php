<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Attempt;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser as PdfParser;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();

        $totalQuizzes = Quiz::where('teacher_id', $teacher->id)->count();

        $activeQuizzes = Quiz::where('teacher_id', $teacher->id)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->count();

        $totalSubmissions = Attempt::whereHas('quiz', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->where('status', 'submitted')->count();

        $totalStudents = Attempt::whereHas('quiz', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->distinct('student_id')->count('student_id');

        $recentQuizzes = Quiz::where('teacher_id', $teacher->id)
            ->withCount([
                'attempts as total_attempts',
                'attempts as submitted_attempts' => function ($q) {
                    $q->where('status', 'submitted');
                }
            ])
            ->latest()
            ->take(5)
            ->get();

        $quizzes = Quiz::where('teacher_id', $teacher->id)
            ->where('status', 'active')
            ->get();

        $draftQuizzes = Quiz::where('teacher_id', $teacher->id)
            ->where('status', 'draft')
            ->count();

        $endingSoon = Quiz::where('teacher_id', $teacher->id)
            ->where('status', 'active')
            ->whereNotNull('ends_at')
            ->whereBetween('ends_at', [now(), now()->addDays(3)])
            ->count();

        $newStudentsThisWeek = Attempt::whereHas('quiz', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })
            ->where('created_at', '>=', now()->subDays(7))
            ->distinct('student_id')
            ->count('student_id');

        $newStudentsThisMonth = Attempt::whereHas('quiz', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })
            ->where('created_at', '>=', now()->subDays(30))
            ->distinct('student_id')
            ->count('student_id');

        $avgSubmissionsPerQuiz = $totalQuizzes > 0 ? round($totalSubmissions / $totalQuizzes, 1) : 0;

        $submissionsThisWeek = Attempt::whereHas('quiz', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })
            ->where('status', 'submitted')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $dailyAttempts = collect(range(6, 0))->map(function ($daysAgo) use ($teacher) {
            return Attempt::whereHas('quiz', fn($q) => $q->where('teacher_id', $teacher->id))
                ->whereDate('created_at', now()->subDays($daysAgo))
                ->count();
        })->values();

        $dailySubmissions = collect(range(6, 0))->map(function ($daysAgo) use ($teacher) {
            return Attempt::whereHas('quiz', fn($q) => $q->where('teacher_id', $teacher->id))
                ->where('status', 'submitted')
                ->whereDate('created_at', now()->subDays($daysAgo))
                ->count();
        })->values();

        $nearestEndingQuiz = Quiz::where('teacher_id', $teacher->id)
            ->where('status', 'active')
            ->whereNotNull('ends_at')
            ->where('ends_at', '>=', now())
            ->orderBy('ends_at')
            ->first();

        $nearestEndingLabel = null;
        if ($nearestEndingQuiz) {
            $diff = now()->diff($nearestEndingQuiz->ends_at);

            if ($diff->days >= 1) {
                $nearestEndingLabel = $diff->days . 'd ' . $diff->h . 'h left';
            } elseif ($diff->h >= 1) {
                $nearestEndingLabel = $diff->h . 'h ' . $diff->i . 'm left';
            } elseif ($diff->i >= 1) {
                $nearestEndingLabel = $diff->i . 'm left';
            } else {
                $nearestEndingLabel = 'under a minute left';
            }
        }

        $submissionsRaw = Attempt::whereHas('quiz', fn($q) => $q->where('teacher_id', $teacher->id))
            ->where('status', 'submitted')
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as day, COUNT(*) as count')
            ->groupBy('day')
            ->pluck('count', 'day');

        $dailySubmissions30 = collect(range(29, 0))->map(function ($daysAgo) use ($submissionsRaw) {
            $date = now()->subDays($daysAgo)->format('Y-m-d');
            return [
                'label' => now()->subDays($daysAgo)->format('M d'),
                'count' => (int) ($submissionsRaw[$date] ?? 0),
            ];
        });

        $categoryDistribution = Quiz::where('teacher_id', $teacher->id)
            ->whereNotNull('category')
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();

        return view('teacher.dashboard', compact(
            'totalQuizzes',
            'activeQuizzes',
            'totalSubmissions',
            'totalStudents',
            'recentQuizzes',
            'quizzes',
            'draftQuizzes',
            'endingSoon',
            'newStudentsThisWeek',
            'newStudentsThisMonth',
            'avgSubmissionsPerQuiz',
            'submissionsThisWeek',
            'dailyAttempts',
            'dailySubmissions',
            'nearestEndingQuiz',
            'nearestEndingLabel',
            'dailySubmissions30',
            'categoryDistribution'
        ));
    }
    public function leaderboard($quizId)
    {
        $teacher = Auth::user();

        $quiz = Quiz::where('id', $quizId)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $leaderboard = Attempt::where('quiz_id', $quiz->id)
            ->where('status', 'submitted')
            ->with('student')
            ->orderByDesc('score')
            ->get()
            ->map(function ($attempt) {
                return [
                    'name'       => $attempt->student->name,
                    'initials'   => strtoupper(substr($attempt->student->name, 0, 1) . (strpos($attempt->student->name, ' ') ? substr($attempt->student->name, strpos($attempt->student->name, ' ') + 1, 1) : '')),
                    'score'      => $attempt->score_percentage,
                    'raw_score'  => $attempt->score,
                    'total'      => $attempt->total_marks,
                ];
            });

        return response()->json($leaderboard);
    }
    public function resultsSummary($quizId)
    {
        $teacher = Auth::user();

        $quiz = Quiz::where('id', $quizId)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $attempts = Attempt::where('quiz_id', $quiz->id)
            ->where('status', 'submitted')
            ->get();

        $submissions = $attempts->count();
        $avg = $submissions > 0
            ? round($attempts->avg(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0))
            : 0;
        $highest = $submissions > 0
            ? round($attempts->max(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0))
            : 0;

        return response()->json([
            'submissions' => $submissions,
            'avg'         => $avg . '%',
            'highest'     => $highest . '%',
        ]);
    }

    public function students()
    {
        $teacher = Auth::user();

        // 1. Fetch all student IDs who have interacted with this teacher's quizzes
        $teacherQuizIds = Quiz::where('teacher_id', $teacher->id)->pluck('id');

        // 2. Base query for this teacher's student attempts
        $baseAttemptsQuery = Attempt::whereIn('quiz_id', $teacherQuizIds)->where('status', 'submitted');

        // --- CARDS/STATS LOGIC ---
        $totalStudentsCount = (clone $baseAttemptsQuery)->distinct('student_id')->count('student_id');

        // Active this month (June 2026)
        $activeThisMonth = (clone $baseAttemptsQuery)->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->distinct('student_id')
            ->count('student_id');

        $totalAttemptsCount = (clone $baseAttemptsQuery)->count();

        // Overall Average Score calculation across all submissions
        $allAttempts = (clone $baseAttemptsQuery)->get();
        $avgScore = $allAttempts->count() > 0
            ? round($allAttempts->avg(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0))
            : 0;

        // --- DATA TABLE LOGIC ---
        // Pull unique students with eager loaded user data, and compute aggregates on the fly
        $students = User::whereHas('attempts', function ($q) use ($teacherQuizIds) {
            $q->whereIn('quiz_id', $teacherQuizIds)->where('status', 'submitted');
        })
            ->with(['attempts' => function ($q) use ($teacherQuizIds) {
                $q->whereIn('quiz_id', $teacherQuizIds)
                    ->where('status', 'submitted')
                    ->with('quiz.teacher')
                    ->latest('submitted_at');
            }])
            ->get()
            ->map(function ($student) {
                $studentAttempts = $student->attempts;

                $quizzesTaken = $studentAttempts->count();

                $avg = $quizzesTaken > 0
                    ? round($studentAttempts->avg(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0))
                    : 0;

                // Simple calculation for passed quizzes (assuming passing score is >= 50%)
                $quizzesPassed = $studentAttempts->filter(fn($a) => $a->total_marks > 0 ? (($a->score / $a->total_marks) * 100) >= 50 : false)->count();

                $lastActiveAttempt = $studentAttempts->sortByDesc('created_at')->first();
                $lastActiveDate = $lastActiveAttempt ? $lastActiveAttempt->created_at : null;

                // Determine status badge based on timing window
                if ($lastActiveDate && $lastActiveDate->greaterThanOrEqualTo(now()->subDays(7))) {
                    $status = 'active';
                } elseif ($lastActiveDate && $lastActiveDate->greaterThanOrEqualTo(now()->subMonths(1))) {
                    $status = 'recent';
                } else {
                    $status = 'inactive';
                }

                $recentQuizzes = $studentAttempts->take(5)->map(function ($a) {
                    $pct = $a->total_marks > 0 ? round(($a->score / $a->total_marks) * 100) : 0;
                    return [
                        'title'   => $a->quiz->title ?? 'Deleted Quiz',
                        'category' => $a->quiz->category ?? 'General',
                        'teacher' => $a->quiz->teacher->name ?? 'Unknown',
                        'score'   => $pct,
                    ];
                })->values();

                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'initial' => strtoupper(substr($student->name, 0, 1)),
                    'quizzes_taken' => $quizzesTaken,
                    'quizzes_passed' => $quizzesPassed,
                    'avg_score' => $avg,
                    'last_active_raw' => $lastActiveDate,
                    'last_active' => $lastActiveDate ? $lastActiveDate->diffForHumans() : 'Never',
                    'status' => $status,
                    'recent_quizzes' => $recentQuizzes,
                ];
            });

        return view('teacher.students', compact(
            'totalStudentsCount',
            'activeThisMonth',
            'avgScore',
            'totalAttemptsCount',
            'students'
        ));
    }
    public function questionBank()
    {
        return view('teacher.question-bank');
    }
    public function aiAssistant()
    {
        $uploadedFileName = session('ai_uploaded_filename');
        return view('teacher.ai-assistant', compact('uploadedFileName'));
    }

    public function aiUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,txt|max:20480', // 20MB max
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $text = '';

        try {
            if ($extension === 'pdf') {
                $parser = new PdfParser();
                $pdf = $parser->parseFile($file->getPathname());
                $text = $pdf->getText();
            } else {
                $text = file_get_contents($file->getPathname());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not read this file. Try a different PDF or a plain text file.'], 422);
        }

        $text = trim($text);

        if (empty($text)) {
            return response()->json(['error' => 'No readable text found in this file.'], 422);
        }

        // Cap context size to keep prompts reasonable
        $text = mb_substr($text, 0, 12000);

        session([
            'ai_uploaded_text' => $text,
            'ai_uploaded_filename' => $file->getClientOriginalName(),
        ]);

        return response()->json([
            'success' => true,
            'filename' => $file->getClientOriginalName(),
        ]);
    }

    public function aiRemoveUpload()
    {
        session()->forget(['ai_uploaded_text', 'ai_uploaded_filename']);
        return response()->json(['success' => true]);
    }

    public function aiChat(Request $request)
    {
        $teacher = Auth::user();

        $quizzes = Quiz::where('teacher_id', $teacher->id)
            ->withCount(['questions', 'attempts as submitted_attempts' => function ($q) {
                $q->where('status', 'submitted');
            }])
            ->get();

        $contextLines = [];
        $contextLines[] = "You are an AI teaching assistant for a teacher named {$teacher->name} on the Quizora quiz platform.";
        $contextLines[] = "You have full knowledge of all quizzes this teacher has created and how their students performed.";
        $contextLines[] = "Help them understand class performance, identify which quizzes or topics students struggle with, suggest improvements to quiz content, and answer questions about their data.";
        $contextLines[] = "Be concise, professional, and actionable. Use the teacher's actual data when answering.";
        $contextLines[] = "";
        $contextLines[] = "=== TEACHER'S QUIZZES ===";

        if ($quizzes->isEmpty()) {
            $contextLines[] = "This teacher has not created any quizzes yet.";
        }

        foreach ($quizzes as $quiz) {
            $attempts = Attempt::where('quiz_id', $quiz->id)
                ->where('status', 'submitted')
                ->get();

            $avgScore = $attempts->count() > 0
                ? round($attempts->avg(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0))
                : null;

            $contextLines[] = "";
            $contextLines[] = "--- Quiz: \"{$quiz->title}\" ---";
            $contextLines[] = "Category: " . ($quiz->category ?? 'N/A');
            $contextLines[] = "Difficulty: " . ($quiz->difficulty ?? 'N/A');
            $contextLines[] = "Status: " . ucfirst($quiz->display_status);
            $contextLines[] = "Questions: {$quiz->questions_count}";
            $contextLines[] = "Submissions: {$quiz->submitted_attempts}";
            $contextLines[] = "Average Score: " . ($avgScore !== null ? "{$avgScore}%" : 'No submissions yet');

            $questions = $quiz->questions()->with('options', 'answers')->get();
            foreach ($questions as $question) {
                $answered = $question->answers->where('is_correct', '!=', null);
                $correctCount = $answered->where('is_correct', true)->count();
                $totalAnswered = $answered->count();
                $pctCorrect = $totalAnswered > 0 ? round(($correctCount / $totalAnswered) * 100) : null;

                $contextLines[] = "  Q: {$question->question_text} — " .
                    ($pctCorrect !== null ? "{$pctCorrect}% of students got this right" : "not yet answered by anyone");
            }
        }

        // Include uploaded file content if present
        $uploadedText = session('ai_uploaded_text');
        $uploadedFilename = session('ai_uploaded_filename');

        if ($uploadedText) {
            $contextLines[] = "";
            $contextLines[] = "=== ATTACHED DOCUMENT: \"{$uploadedFilename}\" ===";
            $contextLines[] = "The teacher has attached this document for you to reference in your answers.";
            $contextLines[] = $uploadedText;
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

        $response = Http::withHeaders([
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
