<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetGlobalLeaderBoardRequest;
use App\Http\Requests\GetQuizLeaderBoardRequest;
use App\Http\Resources\GlobalLeaderBoardResource;
use App\Http\Resources\LeaderBoardResource;
use App\Models\Quiz;
use App\Services\LeaderboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function __construct(
        private LeaderboardService $leaderboardService
    ) {
    }

    /**
     * GET /leaderboard/global
     * Fetch the global leaderboard for all users.
     *
     * @param GetGlobalLeaderBoardRequest $request
     *
     * @return JsonResponse
     */
    public function getGlobalLeaderboard(GetGlobalLeaderBoardRequest $request): JsonResponse
    {
        $leaderboards = $this->leaderboardService->getGlobalLeaderboard(
            $request->input('per_page'),
            $request->input('page'),
        );

        return response()->json(GlobalLeaderboardResource::collection($leaderboards));
    }

    /**
     * GET /leaderboard/quiz/{quiz}
     * Fetch the global leaderboard for all users by quiz.
     *
     * @param GetQuizLeaderBoardRequest $request
     * @param Quiz $quiz
     *
     * @return JsonResponse
     */
    public function getQuizLeaderboard(GetQuizLeaderBoardRequest $request, Quiz $quiz): JsonResponse
    {
        $leaderboards = $this->leaderboardService->getQuizLeaderboard(
            $quiz->id,
            $request->input('per_page'),
            $request->input('page'),
        );

        return response()->json(LeaderBoardResource::collection($leaderboards));
    }
}
