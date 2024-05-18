<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Moder\AnswerRequest;
use App\Models\UserAnswer;

class AnswerController extends Controller
{
    public function __invoke(UserAnswer $answer, AnswerRequest $request): void
    {
        $answer->update($request->validated());
    }
}
