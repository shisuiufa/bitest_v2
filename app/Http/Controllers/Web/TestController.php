<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestResource;
use App\Http\Resources\TestShowResource;
use App\Models\Test;
use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TestController extends Controller
{
    public function index(Request $request, TestService $service): ResourceCollection
    {
        $search = $request->input('search') ?? '';
        $page = $request->input('page');
        $perPage = $request->input('per_page');
        $filter = $request->input('filter');

        $tests = $service->getListTests($search, true, $page, $perPage, $filter);

        return TestResource::collection($tests);
    }

    public function show(Test $test): TestShowResource
    {
        return new TestShowResource($test);
    }
}
