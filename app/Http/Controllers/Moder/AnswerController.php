<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Moder\AnswerRequest;
use App\Models\UserAnswer;
use App\Services\TestService;

class AnswerController extends Controller
{
    public function __invoke(UserAnswer $answer, AnswerRequest $request, TestService $service): void
    {
        $answer->update($request->validated());

        $service->checkTest($answer->testUser);
    }
}
