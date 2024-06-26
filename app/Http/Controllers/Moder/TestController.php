<?php

namespace App\Http\Controllers\Moder;

use App\Exports\TestsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Test\CreateRequest;
use App\Http\Requests\Test\EditRequest;
use App\Http\Resources\TestDetailResource;
use App\Http\Resources\TestResource;
use App\Models\Test;
use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{
    public function index(Request $request, $id, TestService $testService): ResourceCollection
    {
        $search = $request->input('search') ?? '';
        $page = $request->input('page') ?? 1;
        $perPage = $request->input('per_page');

        $tests = $testService->getTestsAuthor($id, $search, false, $page, $perPage);

        return TestResource::collection($tests);
    }

    public function show(Test $test): TestDetailResource
    {
        return new TestDetailResource($test);
    }

    public function store(CreateRequest $request, TestService $testService):void
    {
        $questions = $request->input('questions');
        $testInfo = $request->safe()->collect()->except('questions');
        $testInfo = $testInfo->merge(['author_id' => Auth::user()->id]);

        $testService->createTest($testInfo, $questions);
    }

    public function update(Test $test, EditRequest $request, TestService $testService): void
    {
        $questions = $request->input('questions');
        $testInfo = $request->safe()->collect()->except('questions');
        $testInfo = $testInfo->merge(['author_id' => Auth::user()->id]);

        $testService->editTest($test, $testInfo, $questions);
    }

    public function destroy(Test $test): void
    {
        $test->delete();
    }

    public function export()
    {
        $users = Test::all();

        return Excel::download(new TestsExport($users), 'tests.xlsx');
    }

}
