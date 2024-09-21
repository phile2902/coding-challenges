<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\UserAnswer;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAnswerResource extends JsonResource
{
    /**
     * @param $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        /** @var UserAnswer $resource */
        $resource = $this->resource;

        return [
            'id' => $resource->id,
            'user_id' => $resource->user_id,
            'quiz_session_id' => $resource->quiz_session_id,
            'question_id' => $resource->question_id,
            'selected_option_id' => $resource->selected_option_id,
            'is_correct' => (bool) $resource->is_correct,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
        ];
    }
}
