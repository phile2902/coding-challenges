<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\UserAnswer;
use App\Repositories\Params\PutUserAnswerParam;

class EloquentUserAnswerRepository implements UserAnswerRepository
{
    /**
     * @inheritDoc
     */
    public function findAnswerOfUser(int $userId, int $sessionId, int $questionId): UserAnswer|null
    {
        return UserAnswer::query()
            ->where('user_id', $userId)
            ->where('quiz_session_id', $sessionId)
            ->where('question_id', $questionId)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function save(PutUserAnswerParam $param): UserAnswer
    {
        $data = [
            'user_id' => $param->userId,
            'quiz_session_id' => $param->sessionId,
            'question_id' => $param->question,
            'selected_option_id' => $param->optionId,
            'is_correct' => $param->isCorrect ? ((int) $param->isCorrect) : null,
        ];

        $data = array_filter($data, fn ($value) => $value !== null);

        if (empty($data)) {
            throw new \Exception('Failed to save user answer');
        }

        return UserAnswer::query()->create($data);
    }
}
