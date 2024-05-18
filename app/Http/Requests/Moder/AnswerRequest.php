<?php

namespace App\Http\Requests\Moder;

use Illuminate\Foundation\Http\FormRequest;

class AnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'correct' => 'required | boolean',
        ];
    }
}
