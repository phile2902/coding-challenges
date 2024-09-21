<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Leaderboard;
use App\Repositories\Params\FindLeaderBoardParam;
use Illuminate\Support\Collection;

interface LeaderBoardRepository
{
    /**
     * Find leaderboard entries based on the given parameters.
     *
     * @param FindLeaderBoardParam $param
     *
     * @return Collection<int, Leaderboard>
     */
    public function find(FindLeaderBoardParam $param): Collection;
}
