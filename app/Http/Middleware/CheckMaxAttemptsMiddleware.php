<?php

namespace App\Http\Middleware;

use App\Enums\TestStatus;
use App\Models\Test;
use App\Services\TestService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaxAttemptsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $test = $request->route()->parameter('test');

        if (!is_object($test)) {
            $test = Test::findOrFail($test);
        }

        $testAttempts = $test->attempts;
        $user = $request->user();

        $lastUserTest = $user->test()
            ->where('test_id', $test->id)
            ->whereNot('status', TestStatus::ONGOING)
            ->latest()
            ->first();

        if (
            !empty($lastUserTest)
            && !empty($testAttempts)
            && $testAttempts <= $lastUserTest->attempt
        ) {
            return new Response(__('messages.max_attempts'), 403);
        }

        return $next($request);
    }
}
