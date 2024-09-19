<?php

namespace App\Providers;

use App\Repositories\EloquentQuizRepository;
use App\Repositories\QuizRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(QuizRepository::class, EloquentQuizRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
