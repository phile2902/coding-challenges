<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GlobalLeaderBoardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $resource = $this->resource;
        $user = User::find($resource['user_id']);

        return [
            'user_id' => $resource['user_id'],
            'user_name' => $user->name,
            'total_score' => $resource['total_score'],
        ];
    }
}
