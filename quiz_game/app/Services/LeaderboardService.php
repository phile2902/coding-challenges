<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\QuizSessionRepository;
use Illuminate\Support\Collection;

class LeaderboardService
{
    public function __construct(
        private QuizSessionRepository $quizSessionRepository
    ) {
    }

    /**
     * Get the global leaderboard, ranked by total score across all completed quizzes.
     *
     * @return Collection
     */
    public function getGlobalLeaderboard(): Collection
    {
        return $this->quizSessionRepository->getGlobalLeaderboard();
    }
}
