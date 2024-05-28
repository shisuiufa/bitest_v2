<?php

namespace App\Http\Controllers\Web;

use App\Filters\SortByFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\TestResource;
use App\Http\Resources\TestShowResource;
use App\Models\Test;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TestController extends Controller
{
    public function index(): ResourceCollection
    {
        $tests = QueryBuilder::for(Test::class)
            ->allowedFilters([
                AllowedFilter::partial('search', 'title'),
                AllowedFilter::custom('sort_by', new SortByFilter)
            ])
            ->allowedIncludes(['author'])
            ->paginate(6)
            ->appends(request()->query());

        return TestResource::collection($tests);
    }

    public function show(Test $test): TestShowResource
    {
        return new TestShowResource($test);
    }
}
