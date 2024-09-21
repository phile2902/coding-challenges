<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use App\Models\QuizSession;
use App\Models\User;
use App\Models\UserAnswer;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quizSession = QuizSession::all();

        $quizSession->each(function ($session) {
            $user = $session->user;
            $quiz = $session->quiz;
            $questions = $quiz->questions->random(5);
            $questions->each(function ($question) use ($user, $session) {
                $option = $question->options->random();
                UserAnswer::create([
                    'user_id' => $user->id,
                    'question_id' => $question->id,
                    'quiz_session_id' => $session->id,
                    'selected_option_id' => $option->id,
                    'is_correct' => $option->is_correct,
                ]);
            });
        });
    }
}
