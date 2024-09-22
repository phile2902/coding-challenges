<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAvailableQuizzesRequest;
use App\Http\Requests\JoinQuizRequest;
use App\Http\Requests\SelectOptionRequest;
use App\Http\Requests\SubmitQuizAnswersRequest;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuizJoinResource;
use App\Http\Resources\QuizResource;
use App\Http\Resources\UserAnswerResource;
use App\Models\Question;
use App\Models\Quiz;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;

class QuizController extends Controller
{
    public function __construct(private QuizService $quizService)
    {
    }

    /**
     * GET /quizzes/available
     * Controller Method to fetch available quizzes for a user
     *
     * @param GetAvailableQuizzesRequest $request
     *
     * @return JsonResponse
     */
    public function getAvailableQuizzes(GetAvailableQuizzesRequest $request): JsonResponse
    {
        $userId = $request->integer('user_id');
        $page = $request->integer('page', 1); // Default to page 1
        $perPage = $request->integer('per_page', 10); // Default to 10 quizzes per page

        $quizzes = $this->quizService->getAvailableQuizzes($userId)->forPage($page, $perPage);

        return response()->json(QuizResource::collection($quizzes));
    }

    /**
     * GET /quizzes/{quiz}/join
     * Controller Method to join a quiz. It returns the questions for the quiz and creates a session for the user
     *
     * @param JoinQuizRequest $request
     * @param Quiz $quiz
     *
     * @return JsonResponse
     */
    public function joinQuiz(JoinQuizRequest $request, Quiz $quiz): JsonResponse
    {
        $userId = $request->integer('user_id');

        try {
            $session = $this->quizService->joinQuiz($quiz->id, $userId);

            return response()->json(QuizJoinResource::make($session), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    /**
     * POST /quizzes/{quiz}/submit
     * Controller Method to submit answers and complete quiz
     *
     * @param SubmitQuizAnswersRequest $request
     * @param Quiz $quiz
     *
     * @return JsonResponse
     */
    public function submitQuiz(SubmitQuizAnswersRequest $request, Quiz $quiz): JsonResponse
    {
        $userId = $request->input('user_id');
        $sessionId = $request->input('quiz_session_id');

        try {
            $this->quizService->submitQuizSession($sessionId, $userId, $quiz);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        return response()->json([
            'message' => 'Quiz completed successfully',
        ]);
    }

    /**
     * POST /quizzes/{quiz}/questions/{question}/answer
     * Controller Method to select an option for a question
     *
     * @param SelectOptionRequest $request
     * @param Quiz $quiz
     * @param Question $question
     *
     * @return JsonResponse
     */
    public function selectOption(SelectOptionRequest $request, Quiz $quiz, Question $question): JsonResponse
    {
        $userId = $request->input('user_id');
        $optionId = $request->input('option_id');
        $sessionId = $request->input('quiz_session_id');

        try {
            $answer = $this->quizService->selectOption($quiz, $question, $userId, $optionId, $sessionId);

            return response()->json(UserAnswerResource::make($answer), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
