<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Leaderboard;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserSubmittedQuiz implements ShouldBroadcast
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
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('quiz');
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'quiz.completed';
    }

    /**
     * @return array
     */
    public function broadcastWith(): array
    {
        $leaderboard = Leaderboard::query()
            ->where('quiz_id', $this->quizId)
            ->orderBy('score', 'desc')
            ->get();

        return [
            'leaderboard' => $leaderboard->toArray(),
            'user_id' => $this->userId,
            'quiz_id' => $this->quizId,
        ];
    }
}
