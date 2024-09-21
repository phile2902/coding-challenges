<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Option;

interface OptionRepository
{
    /**
     * Find option by id
     *
     * @param int $optionId
     *
     * @return Option|null
     */
    public function findById(int $optionId): Option|null;
}
