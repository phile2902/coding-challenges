<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\QuizSession;
use Illuminate\Support\Collection;

interface QuizSessionRepository
{
    /**
     * Check if a quiz has been completed by a user.
     *
     * @param int $quizId
     * @param int $userId
     *
     * @return bool
     */
    public function isCompletedByUser(int $quizId, int $userId): bool;

    /**
     * Get the global leaderboard of all users based on their total score.
     *
     * @return Collection
     */
    public function getGlobalLeaderboard(): Collection;

    /**
     * @param int $quizId
     * @param int $userId
     *
     * @return QuizSession
     */
    public function createSession(int $quizId, int $userId): QuizSession;

    /**
     * Update the temp score of a user during a quiz.
     *
     * @param int $quizId
     * @param int $userId
     * @param int $scoreIncrement
     *
     * @return void
     */
    public function updateTempScore(int $quizId, int $userId, int $scoreIncrement): void;

    /**
     * Commit the temp score to the total score of a user.
     *
     * @param int $quizId
     * @param int $userId
     *
     * @return void
     */
    public function commitTempScoreToTotal(int $quizId, int $userId): void;

    /**
     * Discard the temp score of a user when the quiz times out.
     *
     * @param int $quizId
     * @param int $userId
     *
     * @return void
     */
    public function discardTempScore(int $quizId, int $userId): void;
}
