<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Quiz;
use App\Models\QuizSession;
use App\Repositories\QuizRepository;
use App\Repositories\QuizSessionRepository;
use Illuminate\Support\Collection;

class QuizService
{
    public function __construct(
        private QuizRepository $quizRepository,
        private QuizSessionRepository $quizSessionRepository
    ) {
    }

    /**
     * @param int $userId
     *
     * @return Collection
     */
    public function getAvailableQuizzes(int $userId)
    {
        return $this->quizRepository->getAvailableQuizzes($userId);
    }

    /**
     * @param int $quizId
     * @param int $userId
     *
     * @return Collection
     * @throws \Exception
     */
    public function getQuestions(int $quizId, int $userId)
    {
        if ($this->quizSessionRepository->isCompletedByUser($quizId, $userId)) {
            throw new \Exception('You have already completed this quiz.');
        }

        return $this->quizRepository->getQuestions($quizId);
    }

    /**
     * Submit answers for a quiz.
     *
     * @param int $userId
     * @param Quiz $quiz
     * @param array $answers
     *
     * @return int
     */
    public function submitAnswers(int $userId, Quiz $quiz, array $answers): int
    {
        return $this->quizRepository->submitAnswers($quiz->id, $userId, $answers);
    }

    /**
     * @param int $quizId
     * @param int $userId
     *
     * @return QuizSession
     * @throws \Exception
     */
    public function joinQuiz(int $quizId, int $userId): QuizSession
    {
        if ($this->quizSessionRepository->isCompletedByUser($quizId, $userId)) {
            throw new \Exception('You have already completed this quiz.');
        }

        // Create a new session
        return $this->quizSessionRepository->createSession($quizId, $userId);
    }

    public function selectOption(int $quizId, int $userId, int $questionId, int $optionId): void
    {
        $answer = $this->quizSessionRepository->findUserAnswer($quizId, $userId, $questionId);

        if ($answer && $answer->is_final) {
            throw new \Exception('You have already selected an option for this question.');
        }

        // Record the answer
        $this->quizSessionRepository->recordAnswer($quizId, $userId, $questionId, $optionId);

        // Update temp score only during the quiz
        $isCorrect = $this->quizSessionRepository->isCorrectOption($questionId, $optionId);
        $scoreIncrement = $isCorrect ? $this->quizSessionRepository->getQuestionScore($questionId) : 0;

        $this->quizSessionRepository->updateTempScore($quizId, $userId, $scoreIncrement);

        // Broadcast progress update
        $progress = $this->quizSessionRepository->getQuizProgress($quizId, $userId);
        event(new ScoreUpdated($quizId, $userId, $progress->temp_score));

        // Broadcast quiz progress update
        event(new QuizProgressUpdated($quizId, $progress));
    }

    public function completeQuiz(int $quizId, int $userId): void
    {
        // Finalize the score when the quiz is completed
        $session = $this->quizSessionRepository->findActiveSession($quizId, $userId);
        $this->quizSessionRepository->commitTempScoreToTotal($quizId, $userId);
        $this->quizSessionRepository->completeSession($session->id);
    }

    public function handleTimeout(int $quizId, int $userId): void
    {
        $session = $this->quizSessionRepository->findActiveSession($quizId, $userId);

        if ($session && $this->isTimedOut($session)) {
            // If timed out, do NOT commit the score to the user's total
            $this->quizSessionRepository->discardTempScore($quizId, $userId);
            $this->quizSessionRepository->completeSession($session->id);

            // Broadcast a quiz timeout event if needed
            event(new QuizTimeout($quizId, $userId));
        }
    }

    private function isTimedOut($session): bool
    {
        $quizDuration = $session->quiz->duration; // Get the quiz duration
        $elapsedTime = now()->diffInMinutes($session->started_at); // Calculate time since quiz started

        return $elapsedTime >= $quizDuration;
    }
}
