<?php

namespace App\Http\Resources;

use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Option $resource */
        $resource = $this->resource;

        return [
            'id' => $resource->id,
            'question_id' => $resource->question_id,
            'option_text' => $resource->option_text,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
        ];
    }
}
