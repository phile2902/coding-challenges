<?php

namespace App\Http\Controllers;

use App\Events\ScoreUpdated;
use App\Events\UserJoinedQuiz;
use App\Http\Requests\JoinQuizRequest;
use App\Http\Requests\SubmitQuizAnswersRequest;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(private QuizService $quizService)
    {
    }

    /**
     * GET /quizzes/available
     * Controller Method to fetch available quizzes for a user
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getAvailableQuizzes(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $quizzes = $this->quizService->getAvailableQuizzes($userId);

        return response()->json($quizzes);
    }

    /**
     * GET /quizzes/{quiz}/questions
     * Controller Method to fetch quiz questions
     *
     * @param Request $request
     * @param Quiz $quiz
     *
     * @return JsonResponse
     */
    public function getQuizQuestions(Request $request, Quiz $quiz): JsonResponse
    {
        $userId = $request->integer('user_id');

        try {
            $questions = $this->quizService->getQuestions($quiz->id, $userId);

            return response()->json($questions);
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
    public function submitQuizAnswers(SubmitQuizAnswersRequest $request, Quiz $quiz): JsonResponse
    {
        $userId = $request->input('user_id');
        $totalScore = $this->quizService->submitAnswers($userId, $quiz, $request->input('answers'));

        // Broadcast event when score is updated after answers are submitted
        event(new ScoreUpdated($quiz->id, $userId, $totalScore));

        return response()->json([
            'message' => 'Quiz completed successfully',
            'total_score' => $totalScore,
        ]);
    }

    public function selectOption(Request $request, Quiz $quiz, Question $question): JsonResponse
    {
        $userId = $request->input('user_id');
        $optionId = $request->input('option_id');

        try {
            $this->quizService->selectOption($quiz->id, $userId, $question->id, $optionId);

            return response()->json(['message' => 'Option selected and score updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * POST /quizzes/{quiz}/join
     * Controller Method to join a quiz
     *
     * @param JoinQuizRequest $request
     * @param Quiz $quiz
     *
     * @return JsonResponse
     */
    public function joinQuiz(JoinQuizRequest $request, Quiz $quiz): JsonResponse
    {
        $userId = $request->input('user_id');

        try {
            $response = $this->quizService->joinQuiz($quiz->id, $userId);

            // Broadcast event when user joins a quiz successfully
            event(new UserJoinedQuiz($quiz->id, User::find($userId)));

            return response()->json($response, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }
}
