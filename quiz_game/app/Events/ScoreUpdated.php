<?php

namespace App\Events;

use App\Models\Quiz;
use App\Models\QuizSession;
use App\Models\UserAnswer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScoreUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $quizId;
    public int $userId;
    public int $sessionId;

    /**
     * Create a new event instance.
     *
     * @param int $quizId
     * @param int $userId
     * @param int $score
     */
    public function __construct(int $quizId, int $userId, int $sessionId)
    {
        $this->quizId = $quizId;
        $this->userId = $userId;
        $this->sessionId = $sessionId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('quiz.' . $this->quizId . '.session.' . $this->sessionId);
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'quiz.session.updated';
    }

    /**
     * @return array
     */
    public function broadcastWith(): array
    {
        $userAnswers = UserAnswer::query()
            ->where('quiz_session_id', $this->sessionId)
            ->where('user_id', $this->userId)
            ->get();

        $quizSession = QuizSession::find($this->sessionId);
        $quiz = Quiz::find($this->quizId);
        $questions = $quiz->questions;

        return [
            'user_id' => $this->userId,
            'quiz_id' => $this->quizId,
            'session' => $quizSession->toArray(),
            'user_answers' => $userAnswers->toArray(),
            'total_questions' => $questions->count(),
            'total_corrects' => $userAnswers->where('is_correct', true)->count(),
            'total_incorrects' => $userAnswers->where('is_correct', false)->count(),
            'total_score' => $quizSession->temp_score,
            'total_left' => $questions->count() - $userAnswers->count(),
        ];
    }
}
