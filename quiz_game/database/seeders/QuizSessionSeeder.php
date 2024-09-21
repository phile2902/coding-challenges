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
        $users = User::query()->where('email', '!=', 'test@example.com')->limit(5)->get();
        $quizzes = Quiz::all();

        $users->each(function ($user) use ($quizzes) {
            $quizzes->each(function ($quiz) use ($user) {
                // Create a new quiz session
                QuizSession::create([
                    'quiz_id' => $quiz->id,
                    'user_id' => $user->id,
                    'score' => rand(0, 10),
                    'is_completed' => 1,
                    'ended_at' => Carbon::now()->subSeconds(rand(1, 1800)),
                    'expired_at' => Carbon::now(),
                    'temp_score' => 0,
                ]);
            });
        });
    }
}
