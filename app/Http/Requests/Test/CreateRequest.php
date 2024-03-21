<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'info.title' => 'required|string',
            'info.time' => 'nullable|integer',
            'info.attempts' => 'nullable|integer',
            'info.limitQuestions' => 'nullable|integer',
            'info.userId' => 'required|integer',
            'info.image' => 'required|string',
            'questions' => 'required|array',
        ];
    }
}
