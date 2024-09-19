<?php

use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::prefix('quizzes')->group(function () {
    Route::get('/available', [QuizController::class, 'getAvailableQuizzes']);
    Route::get('/{quiz}/questions', [QuizController::class, 'getQuizQuestions']);
    Route::post('/{quiz}/submit', [QuizController::class, 'submitQuizAnswers']);
    Route::post('/{quiz}/questions/{question}/select', [QuizController::class, 'selectOption']);
    Route::post('/{quiz}/join', [QuizController::class, 'joinQuiz']);
});

Route::prefix('leaderboard')->group(function () {
    Route::get('/global', [LeaderboardController::class, 'getGlobalLeaderboard']);
});
