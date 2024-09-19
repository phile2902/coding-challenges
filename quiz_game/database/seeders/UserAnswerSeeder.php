<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
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
        $users = User::all();
        $questions = Question::all();

        $users->each(function ($user) use ($questions) {
            $questions->each(function ($question) use ($user) {
                $option = Option::where('question_id', $question->id)->inRandomOrder()->first();
                UserAnswer::create([
                    'user_id' => $user->id,
                    'question_id' => $question->id,
                    'selected_option_id' => $option->id,
                    'submitted_at' => Carbon::now(),
                ]);
            });
        });
    }
}
