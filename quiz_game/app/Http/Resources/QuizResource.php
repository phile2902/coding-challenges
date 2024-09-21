<?php

namespace App\Http\Resources;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Quiz $quiz */
        $resource = $this->resource;

        return [
            'id' => $resource->id,
            'title' => $resource->title,
            'description' => $resource->description,
            'duration' => $resource->duration,
            'created_by' => $resource->created_by,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
        ];
    }
}
