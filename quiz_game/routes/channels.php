<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('quiz.{quizId}', function ($quizId) {
    return true; // Public channel, no authorization
});
