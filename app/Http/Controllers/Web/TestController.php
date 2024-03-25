<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Test\CreatePassRequest;
use App\Http\Resources\TestPassResource;
use App\Http\Resources\TestResource;
use App\Http\Resources\TestShowResource;
use App\Models\Test;
use App\Services\TestService;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request, TestService $service)
    {
        $search = $request->input('search') ?? '';
        $page = $request->input('page');
        $perPage = $request->input('per_page');

        $tests = $service->getListTests($search, true, $page, $perPage);

        return TestResource::collection($tests);
    }

    public function show(Test $test): TestShowResource
    {

        return new TestShowResource($test);
    }

    public function showPass(Test $test, TestService $service): TestPassResource
    {
        $testUser = $service->getTestUser($test->id);

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

    public function storePass(CreatePassRequest $request, Test $test, TestService $service): void
    {
        $answers = $request->input('answers');

        $userTest = $service->getTestUser($test->id);

        $service->createAnswers($userTest, $answers);

        $service->checkTest($userTest);
    }
}
