<?php

namespace App\Http\Middleware;

use App\Enums\TestStatus;
use App\Models\Test;
use App\Models\TestUser;
use App\Services\TestService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserTestStatusMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $testUser = $request->route()->parameter('test_user');

        if (!is_object($testUser)) {
            $testUser = TestUser::findOrFail($testUser);
        }

        if ($testUser->status != TestStatus::ONGOING->value) {
            return new Response(__('messages.test_close'), 403);
        }

        return $next($request);
    }
}
