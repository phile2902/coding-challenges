<?php

declare(strict_types=1);

namespace App\Repositories\Params;

use Carbon\CarbonInterface;

class PutQuizSessionParam
{
    public function __construct(
        public int $quizId,
        public int $userId,
        public int|null $score = null,
        public bool|null $isCompleted = null,
        public CarbonInterface|null $expiredAt = null,
        public CarbonInterface|null $endedAt = null,
        public string|null $tempScore = null,
    ) {
    }
}
