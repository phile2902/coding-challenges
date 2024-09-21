<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Question $resource */
        $resource = $this->resource;

        return [
            'id' => $resource->id,
            'quiz_id' => $resource->quiz_id,
            'question_text' => $resource->question_text,
            'question_type' => $resource->question_type,
            'score' => $resource->score,
            'options' => OptionResource::collection($resource->options),
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
        ];
    }
}
