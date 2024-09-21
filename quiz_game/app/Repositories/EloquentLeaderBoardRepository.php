<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Leaderboard;
use App\Repositories\Params\FindLeaderBoardParam;
use Illuminate\Support\Collection;

class EloquentLeaderBoardRepository implements LeaderBoardRepository
{
    /**
     * Find leaderboard entries based on the given parameters.
     *
     * @param FindLeaderBoardParam $param
     *
     * @return Collection
     */
    public function find(FindLeaderBoardParam $param): Collection
    {
        return Leaderboard::query()
            ->when($param->userId, fn ($query, $userId) => $query->where('user_id', $userId))
            ->when($param->quizId, fn ($query, $quizId) => $query->where('quiz_id', $quizId))
            ->when($param->limit, fn ($query, $limit) => $query->limit($limit))
            ->when($param->offset, fn ($query, $offset) => $query->offset($offset))
            ->get();
    }
}
