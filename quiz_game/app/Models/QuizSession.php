<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'score',
        'status',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $table = 'quiz_sessions';

    /**
     * @return BelongsTo
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Assigns a score to the quiz session based on the user's answers.
     *
     * @return void
     */
    public function calculateScore(): void
    {
        $totalScore = 0;

        foreach ($this->quiz->questions as $question) {
            $userAnswer = $this->user->answers->where('question_id', $question->id)->first();

            if ($userAnswer && $userAnswer->selected_option_id === $question->correctOption->id) {
                $totalScore += $question->score;
            }
        }

        $this->score = $totalScore;
        $this->save();
    }

    /**
     * Session has expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return Carbon::parse($this->expired_at)->isPast();
    }

    /**
     * Session has completed.
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return (bool) $this->is_completed;
    }
}
