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
        'ended_at',
        'expired_at',
        'is_completed',
        'temp_score',
    ];

    protected $casts = [
        'ended_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'expired_at' => 'datetime',
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
