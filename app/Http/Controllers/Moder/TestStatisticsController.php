<?php

namespace App\Http\Controllers\Moder;

use App\Enums\TestStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShowResultResource;
use App\Http\Resources\TestStatisticsResource;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestStatisticsController extends Controller
{
    public function index(Test $test): TestStatisticsResource
    {
        return new TestStatisticsResource($test);
    }

    public function show(Test $test, User $user, Request $request): ShowResultResource
    {
        $userTry = $request->input('try');
        $resultUserQuery = $test->testUsers()
            ->whereNot('status', TestStatus::ONGOING)
            ->where('user_id', $user->id);
        if(empty($userTry)){
            $resultUser = $resultUserQuery->latest()->first();
        } else {
            $resultUser = $resultUserQuery->where('attempt', $userTry)->first();
        }

        return new ShowResultResource($resultUser);
    }

    public function totalAttempts(Test $test, User $user): JsonResponse
    {
        $totalAttempts = $test->testUsers()->where('user_id', $user->id)
            ->orderBy('attempt', 'DESC')
            ->value('attempt');

        return response()->json(['data' => $totalAttempts]);
    }
}
