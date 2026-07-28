<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Teacher\QuizController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route(
            Auth::user()->role === 'teacher' ? 'teacher.dashboard' : 'student.dashboard'
        );
    }
    return view('welcome');
})->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->group(function () {

    Route::get('/dashboard', [TeacherDashboard::class, 'index'])->name('teacher.dashboard');

    Route::get('/quiz/create', [QuizController::class, 'create'])->name('teacher.quiz.create');
    Route::post('/quiz/store', [QuizController::class, 'store'])->name('teacher.quiz.store');
    Route::get('/quiz/{quiz}/edit', [QuizController::class, 'edit'])->name('teacher.quiz.edit');
    Route::put('/quiz/{quiz}', [QuizController::class, 'update'])->name('teacher.quiz.update');
    Route::delete('/quiz/{quiz}', [QuizController::class, 'destroy'])->name('teacher.quiz.destroy');

    Route::get('/quizzes', [QuizController::class, 'index'])->name('teacher.quizzes');
    Route::get('/results', [QuizController::class, 'results'])->name('teacher.results');
    Route::get('/results/{quiz}', [QuizController::class, 'quizResults'])->name('teacher.quiz.results');
    Route::get('/quiz/{quiz}/results-summary', [TeacherDashboard::class, 'resultsSummary'])->name('teacher.results.summary');

    Route::get('/leaderboard', [QuizController::class, 'leaderboard'])->name('teacher.leaderboard.page');
    Route::get('/leaderboard/{quiz}', [TeacherDashboard::class, 'leaderboard'])->name('teacher.leaderboard');

    Route::get('/students', [TeacherDashboard::class, 'students'])->name('teacher.students');
    Route::get('/settings', [QuizController::class, 'settings'])->name('teacher.settings');

    Route::post('/quiz/{quiz}/generate-code', [QuizController::class, 'generateAccessCode'])->name('teacher.quiz.generate-code');
    Route::get('/quiz/{quiz}/print', [QuizController::class, 'print'])->name('teacher.quiz.print');

    Route::get('/question-bank', [\App\Http\Controllers\Teacher\QuestionBankController::class, 'index'])->name('teacher.question-bank');
    Route::post('/question-bank', [\App\Http\Controllers\Teacher\QuestionBankController::class, 'store'])->name('teacher.question-bank.store');
    Route::delete('/question-bank/{bankQuestion}', [\App\Http\Controllers\Teacher\QuestionBankController::class, 'destroy'])->name('teacher.question-bank.destroy');
    Route::post('/question-bank/bulk-delete', [\App\Http\Controllers\Teacher\QuestionBankController::class, 'bulkDestroy'])->name('teacher.question-bank.bulk-delete');
    Route::post('/question-bank/fetch-by-ids', [\App\Http\Controllers\Teacher\QuestionBankController::class, 'fetchByIds'])->name('teacher.question-bank.fetch-by-ids');

    Route::get('/quiz/{quiz}/view', [QuizController::class, 'view'])->name('teacher.quiz.view');
    Route::get('/ai-assistant', [TeacherDashboard::class, 'aiAssistant'])->name('teacher.ai-assistant');
    Route::post('/ai-assistant/chat', [TeacherDashboard::class, 'aiChat'])->name('teacher.ai-assistant.chat');
    Route::post('/ai-assistant/upload', [TeacherDashboard::class, 'aiUpload'])->name('teacher.ai-assistant.upload');
    Route::delete('/ai-assistant/upload', [TeacherDashboard::class, 'aiRemoveUpload'])->name('teacher.ai-assistant.upload.remove');

    Route::get('/quiz/import', [QuizController::class, 'importPage'])->name('teacher.quiz.import');
    Route::post('/quiz/import-csv', [QuizController::class, 'importCsv'])->name('teacher.quiz.import-csv');
    Route::get('/quiz/csv-template', [QuizController::class, 'csvTemplate'])->name('teacher.quiz.csv-template');

    Route::patch('/settings', [QuizController::class, 'updateSettings'])->name('teacher.settings.update');
    Route::put('/settings/password', [QuizController::class, 'updatePassword'])->name('teacher.settings.password');
    Route::delete('/settings/account', [QuizController::class, 'deleteAccount'])->name('teacher.settings.delete');
});

Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {

    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('student.dashboard');

    Route::get('/browse', [StudentQuizController::class, 'browse'])->name('student.browse');
    Route::get('/quiz/{quiz}', [StudentQuizController::class, 'detail'])->name('student.quiz.detail');
    Route::get('/quiz/{quiz}/take', [StudentQuizController::class, 'take'])->name('student.quiz.take');
    Route::post('/quiz/{quiz}/submit', [StudentQuizController::class, 'submit'])->name('student.quiz.submit');
    Route::get('/quiz/{quiz}/result', [StudentQuizController::class, 'result'])->name('student.quiz.result');

    Route::get('/results', [StudentDashboard::class, 'results'])->name('student.results');

    Route::get('/leaderboard', [StudentDashboard::class, 'leaderboard'])->name('student.leaderboard.page');
    Route::get('/leaderboard/{quiz}', [StudentDashboard::class, 'leaderboardData'])->name('student.leaderboard.data');

    Route::get('/bookmarks', [StudentDashboard::class, 'bookmarks'])->name('student.bookmarks');
    Route::post('/bookmarks/{quiz}/toggle', [StudentDashboard::class, 'toggleBookmark'])->name('student.bookmark.toggle');

    Route::get('/ai-tutor', [StudentDashboard::class, 'aiTutor'])->name('student.ai-tutor');
    Route::post('/ai-tutor/chat', [StudentDashboard::class, 'aiChat'])->name('student.ai-tutor.chat');

    Route::get('/settings', [StudentDashboard::class, 'settings'])->name('student.settings');
    Route::patch('/settings', [StudentDashboard::class, 'updateSettings'])->name('student.settings.update');
    Route::put('/settings/password', [StudentDashboard::class, 'updatePassword'])->name('student.settings.password');
    Route::delete('/settings/account', [StudentDashboard::class, 'deleteAccount'])->name('student.settings.delete');
    Route::get('/profile', [StudentDashboard::class, 'profile'])->name('student.profile');

    Route::post('/browse/unlock', [StudentQuizController::class, 'unlockByCode'])->name('student.quiz.unlock');
    Route::get('/private-quizzes', [StudentQuizController::class, 'privateQuizzes'])->name('student.private-quizzes');
});

require __DIR__ . '/auth.php';
