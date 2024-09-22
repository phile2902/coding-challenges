<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\QuizSession;
use App\Models\UserAnswer;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizJoinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        /** @var QuizSession $resource */
        $resource = $this->resource;

        $quiz = $resource->quiz;
        $questions = $quiz->questions;
        $userAnswers = UserAnswer::query()
            ->where('quiz_session_id', $resource->id)
            ->where('user_id', $resource->user_id)
            ->get();

        return [
            'id' => $resource->id,
            'score' => $resource->score,
            'is_completed' => (bool) $resource->is_completed,
            'temp_score' => $resource->temp_score,
            'expired_at' => $resource->expired_at,
            'ended_at' => $resource->ended_at,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
            'questions' => QuestionResource::collection($questions),
            'user_answers' => UserAnswerResource::collection($userAnswers),
        ];
    }
}
