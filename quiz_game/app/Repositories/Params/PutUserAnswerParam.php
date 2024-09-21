<?php

declare(strict_types=1);

namespace App\Repositories\Params;

class PutUserAnswerParam
{
    public function __construct(
        public int|null $userId = null,
        public int|null $question = null,
        public int|null $sessionId = null,
        public int|null $optionId = null,
        public bool|null $isCorrect = null,
    ) {
    }
}
