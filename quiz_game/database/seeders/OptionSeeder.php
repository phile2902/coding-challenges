<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = Question::all();

        $questions->each(function ($question) {
            Option::factory()->count(4)->create([
                'question_id' => $question->id,
                'is_correct' => false,
            ])->first()->update(['is_correct' => 1]);
        });
    }
}
