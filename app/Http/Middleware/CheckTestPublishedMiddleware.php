<?php

namespace App\Http\Middleware;

use App\Enums\TestStatus;
use App\Models\Test;
use App\Services\TestService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTestPublishedMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $test = $request->route()->parameter('test');

        if (!is_object($test)) {
            $test = Test::findOrFail($test);
        }

        $testPublished = boolval($test->published);

        if (!$testPublished) {
            return new Response(__('messages.test_not_found'), 404);
        }

        return $next($request);
    }
}
