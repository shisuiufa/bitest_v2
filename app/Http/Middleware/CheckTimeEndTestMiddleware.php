<?php

namespace App\Http\Middleware;

use App\Enums\TestStatus;
use App\Models\TestUser;
use App\Services\TestService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTimeEndTestMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $testUser = $request->route()->parameter('test_user');

        if (!is_object($testUser)) {
            $testUser = TestUser::findOrFail($testUser);
        }

        $service = new TestService();

        if (!empty($testUser) && $testUser->status === TestStatus::ONGOING->value) {

            $testUser = $service->checkTimeTest($testUser);

            if($testUser->status != TestStatus::ONGOING->value) {
                return new Response(__('messages.test_time_end'), 403);
            }
        }

        return $next($request);
    }
}
