<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\QuizSession;
use App\Repositories\Params\FindQuizSessionParam;
use App\Repositories\Params\PutQuizSessionParam;
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
    public function isSessionExpired(int $quizId, int $userId): bool
    {
        return QuizSession::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->where('expired_at', '>', Carbon::now()->toDateTimeString())
            ->exists();
    }

    /**
     * @inheritDoc
     */
    public function createSession(PutQuizSessionParam $param): QuizSession
    {
        $data = [
            'quiz_id' => $param->quizId,
            'user_id' => $param->userId,
            'expired_at' => $param->expiredAt?->toDateTimeString(),
        ];

        return QuizSession::create($data);
    }

    /**
     * @inheritDoc
     */
    public function findSession(FindQuizSessionParam $param): QuizSession|null
    {
        return QuizSession::query()
            ->when($param->quizId, fn ($query) => $query->where('quiz_id', $param->quizId))
            ->when($param->userId, fn ($query) => $query->where('user_id', $param->userId))
            ->when($param->sessionId, fn ($query) => $query->where('id', $param->sessionId))
            ->when($param->isActive, fn ($query) => $query->where('expired_at', '>', Carbon::now()->toDateTimeString()))
            ->first();
    }

    /**
     * Update the temp score of a user during a quiz.
     *
     * @param QuizSession $session
     * @param int $scoreIncrement
     *
     * @return void
     */
    public function updateTempScore(QuizSession $session, int $scoreIncrement): void
    {
        $session->temp_score += $scoreIncrement;
        $session->save();
    }

    /**
     * @inheritDoc
     */
    public function completeSession(QuizSession $session): void
    {
        $session->score = $session->temp_score;
        $session->is_completed = true;
        $session->ended_at = Carbon::now();
        $session->save();
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
