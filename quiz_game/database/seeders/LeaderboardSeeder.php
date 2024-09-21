<?php

namespace Database\Seeders;

use App\Models\Leaderboard;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeaderboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quizzes = Quiz::all()->random(5);
        $users = User::all()->random(5);

        $quizzes->each(function ($quiz) use ($users) {
            $users->each(function ($user) use ($quiz) {
                Leaderboard::create([
                    'user_id' => $user->id,
                    'score' => random_int(0, 100),
                    'quiz_id' => $quiz->id,
                ]);
            });
        });
    }
}
