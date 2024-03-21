<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TestAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $test = $request->route()->parameter('test');

        $testPublished = boolval($test->published);
        $testAttempts = $test->attempts;

        if (!$testPublished) {
            return new Response('Test not found', 404);
        }

        $user = $request->user();
        $lastUserTest = $user->test()
            ->where('test_id', $test->id)
            ->select('attempt', 'score')
            ->latest()
            ->first();

        if (!empty($lastUserTest) && !empty($testAttempts) && $testAttempts <= $lastUserTest->attempt && !empty($lastUserTest->score)) {
            return new Response('User has reached maximum attempts for this test', 403);
        }

        if (empty($lastUserTest)) {
            $user->tests()->attach($test->id, ['attempt' => 1]);
        } elseif (isset($lastUserTest->score)) {
            $user->tests()->attach($test->id, ['attempt' => $lastUserTest->attempt + 1]);
        }

        return $next($request);
    }
}
