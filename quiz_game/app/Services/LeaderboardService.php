<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\LeaderBoardRepository;
use App\Repositories\Params\FindLeaderBoardParam;
use App\Repositories\QuizSessionRepository;
use Illuminate\Support\Collection;

class LeaderboardService
{
    public function __construct(
        private QuizSessionRepository $quizSessionRepository,
        private LeaderBoardRepository $leaderBoardRepository
    ) {
    }

    /**
     * Get the global leaderboard, ranked by total score across all completed quizzes.
     *
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return Collection
     */
    public function getGlobalLeaderboard(int|null $limit, int|null $offset): Collection
    {
        $leaderBoards = $this->leaderBoardRepository->find(
            new FindLeaderBoardParam(
                limit: $limit,
                offset: $offset
            )
        );

        return $leaderBoards->groupBy('user_id')
            ->map(fn ($leaderBoards) => [
                'user_id' => $leaderBoards->first()->user_id,
                'total_score' => $leaderBoards->sum('score')
            ])
            ->sortByDesc('total_score')
            ->values();
    }

    /**
     * Get the leaderboard for a specific quiz, ranked by score.
     *
     * @param int $quizId
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return Collection
     */
    public function getQuizLeaderboard(int $quizId, int|null $limit, int|null $offset): Collection
    {
        return $this->leaderBoardRepository->find(
            new FindLeaderBoardParam(
                quizId: $quizId,
                limit: $limit,
                offset: $offset
            )
        )->sortByDesc('score');
    }
}
