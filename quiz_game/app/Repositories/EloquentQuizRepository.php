<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizSession;
use App\Models\UserAnswer;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class EloquentQuizRepository implements QuizRepository
{
    /**
     * @inheritDoc
     */
    public function getAvailableQuizzes(int $userId): Collection
    {
        return Quiz::where('created_by', '!=', $userId)
            ->whereDoesntHave('sessions', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function findById(int $quizId): Quiz
    {
        return Quiz::find($quizId);
    }

    /**
     * @param int $quizId
     * @param int $userId
     * @param array $answers
     *
     * @return int
     */
    public function submitAnswers(int $quizId, int $userId, array $answers): int
    {
        $totalScore = 0;

        foreach ($answers as $answer) {
            $question = Question::find($answer['question_id']);
            $correctOption = $question->correctOption;

            UserAnswer::create([
                'user_id' => $userId,
                'question_id' => $answer['question_id'],
                'selected_option_id' => $answer['selected_option_id'],
                'submitted_at' => Carbon::now(),
            ]);

            if ($correctOption->id === $answer['selected_option_id']) {
                $totalScore += $question->score;
            }
        }

        // Mark the session as completed
        $quizSession = QuizSession::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->first();

        $quizSession->update(['is_completed' => true, 'score' => $totalScore]);

        return $totalScore;
    }
}
