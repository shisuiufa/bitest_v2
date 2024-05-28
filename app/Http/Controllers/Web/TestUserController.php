<?php

namespace App\Http\Controllers\Web;

use App\Enums\TestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Test\ManipulateAnswerRequest;
use App\Http\Resources\AnswersPassResource;
use App\Http\Resources\AnswersResource;
use App\Http\Resources\TestPassResource;
use App\Http\Resources\UserResultResource;
use App\Models\Test;
use App\Models\TestUser;
use App\Services\TestService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class TestUserController extends Controller
{
    public function show(Test $test, TestUser $testUser): TestPassResource|Response
    {
        return new TestPassResource($testUser);
    }

    public function store(Test $test, TestService $service): TestPassResource
    {
        $testUser = $service->getTestUser($test->id);

        if (empty($testUser)) {
            $testUser = $test->testUsers()->create(['attempt' => 1]);
        } elseif ($testUser->status !== TestStatus::ONGOING->value) {
            $testUser = $test->testUsers()->create(['attempt' => $testUser->attempt + 1]);
        }

        if ($testUser->questions->isEmpty()) {
            $randomQuestions = $test->questions()->inRandomOrder();

            if (!empty($test->limit_questions)) {
                $randomQuestions->take($test->limit_questions);
            }

            $questionIds = $randomQuestions->pluck('id');

            $testUser->questions()->attach($questionIds);

            $testUser->load('questions');
        }

        return new TestPassResource($testUser);
    }

    public function update(Test $test, TestUser $testUser, TestService $service): UserResultResource
    {
        $checkedTest = $service->checkTest($testUser);

        return new UserResultResource($checkedTest);
    }

    public function manipulateAnswer(ManipulateAnswerRequest $request, Test $test, TestUser $testUser, TestService $service): void
    {
        $questionId = $request->input('questionId');
        $answers = $request->input('answer');

        $service->manipulateAnswer($testUser, $questionId, $answers);
    }

    public function getAnswers(Test $test, TestUser $testUser): ResourceCollection
    {
        $answers = $testUser->answers;

        return AnswersPassResource::collection($answers);
    }

}
