<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Option;

class EloquentOptionRepository implements OptionRepository
{
    /**
     * @inheritDoc
     */
    public function findById(int $optionId): Option|null
    {
        return Option::query()->find($optionId);
    }
}
