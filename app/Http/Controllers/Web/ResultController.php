<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResultResource;
use App\Services\ResultService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResultController extends Controller
{
    public function index(Request $request, ResultService $service): ResourceCollection
    {
        $page = $request->input('page');
        $perPage = $request->input('per_page');

        $results = $service->getResults(true, $page, $perPage);

        return ResultResource::collection($results);
    }

    public function show()
    {

    }
}
