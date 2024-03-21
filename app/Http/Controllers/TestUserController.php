<?php

namespace App\Http\Controllers;

use App\Http\Requests\Test\CreatePassRequest;
use App\Http\Resources\TestPassResource;
use App\Models\Test;

class TestUserController extends Controller
{
    public function show(Test $test): TestPassResource
    {
        $testUser = auth()->user()->test()
            ->where('test_id', $test->id)
            ->whereNull('score')
            ->whereNull('percent')
            ->latest()
            ->first();

        if ($testUser->questions->isEmpty()) {
            $randomQuestions = $test->questions()->inRandomOrder();

            if (!empty($test->limit_questions)) {
                $randomQuestions->take($test->limit_questions);
            }

            $questionIds = $randomQuestions->pluck('id');

            $testUser->questions()->attach($questionIds);

            $testUser->load('questions');
        }

        return new TestPassResource($testUser); //возращаем рандом вопросы пользователя
    }

    public function store(CreatePassRequest $request, Test $test)
    {
        $answers = $request->safe(['answers']);


    }
}
