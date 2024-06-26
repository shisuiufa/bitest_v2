<?php

namespace App\Http\Middleware;

use App\Enums\TestStatus;
use App\Models\Test;
use App\Services\TestService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TestAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $test = $request->route()->parameter('test');

        if (!is_object($test)) {
            $test = Test::findOrFail($test);
        }

        $service = new TestService();

        $testPublished = boolval($test->published);
        $testAttempts = $test->attempts;

        if (!$testPublished) {
            return new Response(__('Test not found'), 404);
        }

        $user = $request->user();
        $lastUserTest = $user->test()
            ->where('test_id', $test->id)
            ->latest()
            ->first();

        if (
            !empty($lastUserTest)
            && !empty($testAttempts)
            && $testAttempts <= $lastUserTest->attempt
            && $lastUserTest->status !== TestStatus::ONGOING->value
        ) {
            return new Response(__('User has reached maximum attempts for this test'), 403);
        }


        if (!empty($lastUserTest) && $lastUserTest->status === TestStatus::ONGOING->value) {

            $lastUserTest = $service->checkTimeTest($lastUserTest);

            if($lastUserTest->status === TestStatus::PASSED->value) {
                return new Response(__('The test was closed'), 403);
            }
        }

        return $next($request);
    }
}
