<?php

namespace App\Repositories;

use App\Models\Quiz;
use Illuminate\Support\Collection;

interface QuizRepository
{
    /**
     * Get all available quizzes for the user.
     *
     * @param int $userId
     *
     * @return Collection
     */
    public function getAvailableQuizzes(int $userId): Collection;

    /**
     * Find a quiz by its ID.
     *
     * @param int $quizId
     *
     * @return Quiz
     */
    public function findById(int $quizId): Quiz;

    /**
     * Get all questions for a quiz.
     *
     * @param int $quizId
     *
     * @return Collection
     */
    public function getQuestions(int $quizId): Collection;

    public function submitAnswers(int $quizId, int $userId, array $answers): int;
}
