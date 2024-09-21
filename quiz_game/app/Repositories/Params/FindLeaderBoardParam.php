<?php

declare(strict_types=1);

namespace App\Repositories\Params;

class FindLeaderBoardParam
{
    public function __construct(
        public int|null $userId = null,
        public int|null $quizId = null,
        public int|null $limit = null,
        public int|null $offset = null,
    ) {
    }
}
