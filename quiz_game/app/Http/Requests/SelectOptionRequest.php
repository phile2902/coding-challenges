<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelectOptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'option_id' => 'required|exists:options,id',
            'user_id' => 'required|exists:users,id',
            'quiz_session_id' => 'required|exists:quiz_sessions,id',
        ];
    }
}
