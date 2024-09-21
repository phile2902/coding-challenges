<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\QuizSession;
use App\Repositories\Params\FindQuizSessionParam;
use App\Repositories\Params\PutQuizSessionParam;
use Illuminate\Support\Collection;

interface QuizSessionRepository
{
    /**
     * Get the global leaderboard of all users based on their total score.
     *
     * @return Collection
     */
    public function getGlobalLeaderboard(): Collection;

    /**
     * @param PutQuizSessionParam $param
     *
     * @return QuizSession
     */
    public function createSession(PutQuizSessionParam $param): QuizSession;

    /**
     * Find a quiz session by quiz ID and user ID.
     *
     * @param FindQuizSessionParam $param
     *
     * @return QuizSession|null
     */
    public function findSession(FindQuizSessionParam $param): QuizSession|null;

    /**
     * Update the temp score of a user during a quiz.
     *
     * @param QuizSession $session
     * @param int $scoreIncrement
     *
     * @return void
     */
    public function updateTempScore(QuizSession $session, int $scoreIncrement): void;

    /**
     * Commit the temp score to the total score of a user.
     *
     * @param QuizSession $session
     *
     * @return void
     */
    public function completeSession(QuizSession $session): void;
}
