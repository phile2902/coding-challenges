<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitQuizAnswersRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'quiz_session_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
        ];
    }
}
