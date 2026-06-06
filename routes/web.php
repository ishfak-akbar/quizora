<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Teacher\QuizController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->prefix('teacher')->group(function () {
    Route::get('/dashboard', [TeacherDashboard::class, 'index'])->name('teacher.dashboard');
    Route::get('/leaderboard/{quiz}', [TeacherDashboard::class, 'leaderboard'])->name('teacher.leaderboard');
});

Route::middleware(['auth'])->prefix('student')->group(function () {
    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('student.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->group(function () {
    Route::get('/dashboard', [TeacherDashboard::class, 'index'])->name('teacher.dashboard');
    Route::get('/leaderboard/{quiz}', [TeacherDashboard::class, 'leaderboard'])->name('teacher.leaderboard');
    Route::get('/quiz/create', [QuizController::class, 'create'])->name('teacher.quiz.create');
    Route::post('/quiz/store', [QuizController::class, 'store'])->name('teacher.quiz.store');
});

require __DIR__ . '/auth.php';
