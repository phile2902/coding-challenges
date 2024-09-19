<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\QuizSession;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentQuizSessionRepository implements QuizSessionRepository
{
    /**
     * @inheritDoc
     */
    public function isCompletedByUser(int $quizId, int $userId): bool
    {
        return QuizSession::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->where('is_completed', true)
            ->exists();
    }

    /**
     * @inheritDoc
     */
    public function getGlobalLeaderboard(): Collection
    {
        return QuizSession::select('user_id', DB::raw('SUM(score) as total_score'))
            ->where('is_completed', true) // Only consider completed quizzes
            ->groupBy('user_id')
            ->orderByDesc('total_score')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function createSession(int $quizId, int $userId): QuizSession
    {
        return QuizSession::create([
            'quiz_id' => $quizId,
            'user_id' => $userId,
            'status' => 'active',
            'started_at' => Carbon::now(),
        ]);
    }

    /**
     * Update the temp score of a user during a quiz.
     *
     * @param int $quizId
     * @param int $userId
     * @param int $scoreIncrement
     *
     * @return void
     */
    public function updateTempScore(int $quizId, int $userId, int $scoreIncrement): void
    {
        $session = QuizSession::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->first();

        if ($session) {
            $session->temp_score += $scoreIncrement;
            $session->save();
        }
    }

    /**
     * Commit the temp score to the total score of a user.
     *
     * @param int $quizId
     * @param int $userId
     *
     * @return void
     */
    public function commitTempScoreToTotal(int $quizId, int $userId): void
    {
        $session = QuizSession::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->first();

        if ($session) {
            // Add the temp score to the user's total score and reset temp score
            $session->score += $session->temp_score;
            $session->temp_score = 0;
            $session->save();
        }
    }

    /**
     * Discard the temp score of a user when the quiz times out.
     *
     * @param int $quizId
     * @param int $userId
     *
     * @return void
     */
    public function discardTempScore(int $quizId, int $userId): void
    {
        $session = QuizSession::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->first();

        if ($session) {
            // Simply reset the temp score without adding it to the total
            $session->temp_score = 0;
            $session->save();
        }
    }
}
