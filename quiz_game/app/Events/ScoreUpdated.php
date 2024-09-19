<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScoreUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $quizId;
    public int $userId;
    public int $score;

    /**
     * Create a new event instance.
     *
     * @param int $quizId
     * @param int $userId
     * @param int $score
     */
    public function __construct(int $quizId, int $userId, int $score)
    {
        $this->quizId = $quizId;
        $this->userId = $userId;
        $this->score = $score;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('quiz.' . $this->quizId);
    }
}
