<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Teacher\QuizController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;

Route::get('/', function () {
    return view('welcome');
});

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
    Route::get('/question-bank', [QuizController::class, 'questionBank'])->name('teacher.question-bank');
    Route::get('/settings', [QuizController::class, 'settings'])->name('teacher.settings');
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

    Route::get('/bookmarks', [StudentDashboard::class, 'bookmarks'])->name('student.bookmarks');
    Route::post('/bookmarks/{quiz}/toggle', [StudentDashboard::class, 'toggleBookmark'])->name('student.bookmark.toggle');
});

require __DIR__ . '/auth.php';
