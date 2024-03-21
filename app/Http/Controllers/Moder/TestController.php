<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Test\CreateRequest;
use App\Http\Requests\Test\EditRequest;
use App\Http\Resources\TestDetailResource;
use App\Http\Resources\TestResource;
use App\Models\Test;
use App\Services\TestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;


class TestController extends Controller
{
    public function index(Request $request, $id, TestService $testService): ResourceCollection
    {
        $search = $request->input('search') ?? '';
        $page = $request->input('page');
        $perPage = $request->input('per_page');

        $tests = $testService->getTestsAuthor($id, $search, true, $page, $perPage);

        return TestResource::collection($tests);
    }

    public function show(Test $test): TestDetailResource
    {
        return new TestDetailResource($test);
    }

    public function store(CreateRequest $request, TestService $testService): JsonResponse
    {
        $mainInfo = $request->input('info');
        $questions = $request->input('questions');

        $testService->createTest($mainInfo, $questions);

        return response()->json(['success' => true, 'message' => "test created"], 201);
    }

    public function update(Test $test, EditRequest $request, TestService $testService): JsonResponse
    {
        $mainInfo = $request->input('info');
        $questions = $request->input('questions') ?? [];

        $testService->editTest($test, $mainInfo, $questions);

        return response()->json(['success' => true, 'message' => "test updated"]);
    }

    public function destroy(Test $test): JsonResponse
    {
        $test->delete();

        return response()->json('', 204);
    }

}
