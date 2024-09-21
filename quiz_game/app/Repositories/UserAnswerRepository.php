<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\UserAnswer;
use App\Repositories\Params\PutUserAnswerParam;

interface UserAnswerRepository
{
    /**
     * Find answer of user
     *
     * @param int $userId
     * @param int $sessionId
     * @param int $questionId
     *
     * @return UserAnswer|null
     */
    public function findAnswerOfUser(int $userId, int $sessionId, int $questionId): UserAnswer|null;

    /**
     * Save user answer
     *
     * @param PutUserAnswerParam $param
     *
     * @return UserAnswer
     * @throws \Exception
     */
    public function save(PutUserAnswerParam $param): UserAnswer;
}
