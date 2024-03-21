<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

class CreatePassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answers' => 'array',
        ];
    }
}
