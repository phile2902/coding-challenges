<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', '=', 'test@example.com')->first();

        Quiz::factory()->count(10)->create([
            'created_by' => $user->id,
            'title' => fake()->title(),
            'description' => fake()->sentence(),
        ]);
    }
}
