<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizSession;
use App\Models\User;
use App\Models\UserAnswer;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class QuizSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $quizzes = Quiz::all();

        $users->each(function ($user) use ($quizzes) {
            $quizzes->each(function ($quiz) use ($user) {
                // Create a new quiz session
                $quizSession = QuizSession::create([
                    'quiz_id' => $quiz->id,
                    'user_id' => $user->id,
                    'status' => 'completed',
                    'score' => 0,
                    'started_at' => Carbon::now()->subMinutes(rand(1, 60)),
                    'ended_at' => Carbon::now(),
                ]);

                $quizSession->calculateScore();
            });
        });
    }
}
