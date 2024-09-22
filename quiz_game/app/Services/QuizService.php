<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\ScoreUpdated;
use App\Events\UserJoinedQuiz;
use App\Events\UserSubmittedQuiz;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizSession;
use App\Models\UserAnswer;
use App\Repositories\OptionRepository;
use App\Repositories\Params\FindQuizSessionParam;
use App\Repositories\Params\PutQuizSessionParam;
use App\Repositories\Params\PutUserAnswerParam;
use App\Repositories\QuizRepository;
use App\Repositories\QuizSessionRepository;
use App\Repositories\UserAnswerRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class QuizService
{
    public function __construct(
        private QuizRepository $quizRepository,
        private QuizSessionRepository $quizSessionRepository,
        private UserAnswerRepository $userAnswerRepository,
        private OptionRepository $optionRepository
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
     * @return QuizSession
     * @throws \Exception
     */
    public function joinQuiz(int $quizId, int $userId): QuizSession
    {
        $existingSession = $this->quizSessionRepository->findSession(
            new FindQuizSessionParam(userId: $userId, quizId: $quizId),
        );

        // Handle already completed or expired sessions
        if ($existingSession && ($existingSession->isCompleted() || $existingSession->isExpired())) {
            $status = $existingSession->isCompleted() ? 'completed' : 'expired';
            throw new \Exception("Quiz has already been $status.");
        }

        // Resume or create new quiz session
        $session = $existingSession ?? $this->createSession($quizId, $userId);

        // Broadcast event
        event(new UserJoinedQuiz($quizId, $userId, $session->id));

        return $session;
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
    public function submitQuizSession(int $sessionId, int $userId, Quiz $quiz): void
    {
        $session = $this->quizSessionRepository->findSession(
            new FindQuizSessionParam(userId: $userId, sessionId: $sessionId, isActive: true),
        );

        if (!$session) {
            throw new \Exception('Quiz session not found.');
        }

        $this->completeSession($session);

        event(new UserSubmittedQuiz($quiz->id, $userId, $session->id));
    }

    private function completeSession(QuizSession $session): void
    {
        $this->quizSessionRepository->completeSession($session);
    }

    /**
     * @param int $quizId
     * @param int $userId
     *
     * @return QuizSession
     * @throws \Exception
     */
    private function createSession(int $quizId, int $userId): QuizSession
    {
        $quiz = $this->quizRepository->findById($quizId);

        // Create a new session
        return $this->quizSessionRepository->createSession(
            new PutQuizSessionParam(
                quizId: $quizId,
                userId: $userId,
                expiredAt: Carbon::now()->addSeconds($quiz->duration)
            )
        );
    }

    /**
     * Select an option for a question. This method also updates the user's score.
     *
     * @param Quiz $quiz
     * @param Question $question
     * @param int $userId
     * @param int $optionId
     * @param int $sessionId
     *
     * @return UserAnswer
     * @throws \Exception
     */
    public function selectOption(Quiz $quiz, Question $question, int $userId, int $optionId, int $sessionId): UserAnswer
    {
        // Check if the user has already answered this question
        $this->ensureNoPreviousAnswer($userId, $sessionId, $question->id);

        // Find active quiz session
        $session = $this->quizSessionRepository->findSession(new FindQuizSessionParam(
            userId: $userId,
            sessionId: $sessionId,
            isActive: true
        )) ?? throw new \Exception('Quiz session not found.');

        // Record and return the answer
        $userAnswer = $this->recordAnswer($question, $session, $userId, $optionId);

        // Broadcast score update event
        event(new ScoreUpdated($quiz->id, $userId, $session->id));

        return $userAnswer;
    }

    /**
     * Ensure the user has not already answered the question.
     */
    private function ensureNoPreviousAnswer(int $userId, int $sessionId, int $questionId): void
    {
        if ($this->userAnswerRepository->findAnswerOfUser($userId, $sessionId, $questionId)) {
            throw new \Exception('You have already selected an option for this question.');
        }
    }

    private function recordAnswer(Question $question, QuizSession $session, int $userId, int $optionId): UserAnswer
    {
        $option = $this->optionRepository->findById($optionId);
        $isCorrect = (bool) $option->is_correct;

        // Save the user's answer
        $userAnswer = $this->userAnswerRepository->save(
            new PutUserAnswerParam(
                userId: $userId,
                question: $question->id,
                sessionId: $session->id,
                optionId: $optionId,
                isCorrect: $isCorrect
            )
        );

        // Update the temporary score if the answer is correct
        if ($isCorrect) {
            $this->quizSessionRepository->updateTempScore($session, $question->score);
        }

        return $userAnswer;
    }
}
