<?php

namespace App\Observers;

use App\Enums\TestStatus;
use App\Models\TestUser;

class TestUserObserver
{
    public function creating(TestUser $testUser): void
    {
        $testUser->user_id = auth()->user()->id;
        $testUser->status = TestStatus::ONGOING;
    }
}
