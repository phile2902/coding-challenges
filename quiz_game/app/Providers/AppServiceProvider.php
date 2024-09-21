<?php

namespace App\Providers;

use App\Repositories\EloquentLeaderBoardRepository;
use App\Repositories\EloquentOptionRepository;
use App\Repositories\EloquentQuizRepository;
use App\Repositories\EloquentQuizSessionRepository;
use App\Repositories\EloquentUserAnswerRepository;
use App\Repositories\LeaderBoardRepository;
use App\Repositories\OptionRepository;
use App\Repositories\QuizRepository;
use App\Repositories\QuizSessionRepository;
use App\Repositories\UserAnswerRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(QuizRepository::class, EloquentQuizRepository::class);
        $this->app->bind(LeaderBoardRepository::class, EloquentLeaderBoardRepository::class);
        $this->app->bind(OptionRepository::class, EloquentOptionRepository::class);
        $this->app->bind(QuizSessionRepository::class, EloquentQuizSessionRepository::class);
        $this->app->bind(UserAnswerRepository::class, EloquentUserAnswerRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
