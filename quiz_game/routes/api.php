<?php

use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::prefix('quizzes')->group(function () {
    Route::get('/available', [QuizController::class, 'getAvailableQuizzes']);
    Route::get('/{quiz}/join', [QuizController::class, 'joinQuiz']);
    Route::post('/{quiz}/submit', [QuizController::class, 'submitQuiz']);
    Route::post('/{quiz}/questions/{question}/answer', [QuizController::class, 'selectOption']);
});

Route::prefix('leaderboard')->group(function () {
    Route::get('/global', [LeaderboardController::class, 'getGlobalLeaderboard']);
    Route::get('/quiz/{quiz}', [LeaderboardController::class, 'getQuizLeaderboard']);
});
