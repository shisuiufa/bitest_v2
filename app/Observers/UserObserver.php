<?php

namespace App\Observers;

use App\Enums\RoleType;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class UserObserver
{
    public function created(User $user): void
    {
        if($user->roles()->count() === 0){
            try {
                $user->roles()->attach(RoleType::User->value);
            } catch (\Exception $err) {
                Log::error($err->getMessage());
            }
        }
    }
}
