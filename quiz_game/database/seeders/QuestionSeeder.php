<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quizzes = Quiz::all();

        $quizzes->each(function ($quiz) {
            Question::factory()->count(10)->create([
                'quiz_id' => $quiz->id,
                'question_type' => 'multiple_choice',
                'question_text' => fake()->sentence(),
            ]);
        });
    }
}
