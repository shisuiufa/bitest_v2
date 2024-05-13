<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestStatisticsResource;
use App\Models\Test;

class TestStatisticsController extends Controller
{
    public function index(Test $test)
    {
        return new TestStatisticsResource($test);
    }

    public function show()
    {

    }

    public function update()
    {

    }
}
