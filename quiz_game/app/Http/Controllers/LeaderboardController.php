<?php

namespace App\Http\Controllers;

use App\Services\LeaderboardService;
use Illuminate\Http\JsonResponse;

class LeaderboardController extends Controller
{
    public function __construct(
        private LeaderboardService $leaderboardService
    ) {
    }

    /**
     * Fetch the global leaderboard for all users.
     *
     * @return JsonResponse
     */
    public function getGlobalLeaderboard(): JsonResponse
    {
        $leaderboard = $this->leaderboardService->getGlobalLeaderboard();

        return response()->json($leaderboard);
    }
}
