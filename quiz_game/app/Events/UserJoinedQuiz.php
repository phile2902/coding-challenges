<?php

namespace App\Events;

use App\Models\QuizSession;
use App\Models\UserAnswer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserJoinedQuiz implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $quizId;
    public int $userId;
    public int $sessionId;

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
        return 'quiz.joined';
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

        return [
            'user_id' => $this->userId,
            'quiz_id' => $this->quizId,
            'session' => $quizSession->toArray(),
            'user_answers' => $userAnswers->toArray(),
        ];
    }
}
