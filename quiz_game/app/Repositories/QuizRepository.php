<?php

namespace App\Repositories;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Collection;

interface QuizRepository
{
    /**
     * Get all available quizzes for the user. Available quizzes are those that the user has not created and has not joined.
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
}
