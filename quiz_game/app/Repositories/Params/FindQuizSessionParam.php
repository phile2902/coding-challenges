<?php

declare(strict_types=1);

namespace App\Repositories\Params;

class FindQuizSessionParam
{
    public function __construct(
        public int|null $userId = null,
        public int|null $sessionId = null,
        public int|null $quizId = null,
        public bool|null $isActive = null,
    ) {
    }
}
