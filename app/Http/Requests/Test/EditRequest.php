<?php

namespace App\Http\Requests\Test;

use App\Enums\QuestionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'desc' => 'nullable|string',
            'image' => 'required|string',
            'limit_questions' => 'nullable|integer',
            'attempts' => 'nullable|integer',
            'time_complete' => 'nullable|integer',
            'published' => 'required',
            'questions' => 'required|array',
            'questions.*.name' => 'required|string',
            'questions.*.image' => 'nullable|string',
            'questions.*.question_type_id' => ['required', Rule::enum(QuestionEnum::class)],
            'questions.*.options' => 'required_if:questions.*.question_type_id,' . QuestionEnum::Close->value . '|nullable|array',
            'questions.*.options.*.name' => 'required_if:questions.*.question_type_id,' . QuestionEnum::Close->value . '|nullable|string',
            'questions.*.options.*.value' => 'required_if:questions.*.question_type_id,' . QuestionEnum::Close->value . '|nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'questions.*.name.required_if' => 'Заголовок вопроса № :position не заполнен.',
            'questions.*.options.*.name.required_if' => 'Варианты для вопроса № :position не заполнены.',
        ];
    }
}
