<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Leaderboard;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaderBoardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request *
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Leaderboard $resource */
        $resource = $this->resource;
        $user = $resource->user;

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'quiz_id' => $resource->quiz_id,
            'score' => $resource->score,
        ];
    }
}
