<?php

namespace App\Http\Middleware;

use App\Enums\TestStatus;
use App\Models\Test;
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

        $testPublished = boolval($test->published);
        $testAttempts = $test->attempts;

        if (!$testPublished) {
            return new Response('Test not found', 404);
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
            return new Response('User has reached maximum attempts for this test', 403);
        }

        if (empty($lastUserTest)) {
            $user->tests()->attach($test->id, ['attempt' => 1, 'status' => TestStatus::ONGOING]);
        } elseif ($lastUserTest->status !== TestStatus::ONGOING->value) {
            $user->tests()->attach($test->id, ['attempt' => $lastUserTest->attempt + 1, 'status' => TestStatus::ONGOING]);
        }

        return $next($request);
    }
}
