<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\UpdatePasswordRequest;
use App\Http\Requests\Web\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function update(UpdateProfileRequest $request): void
    {
        $user = Auth::user();
        $data = $request->validated();
        $user->update($data);
    }

    public function updatePassword(UpdatePasswordRequest $request): void
    {
        $user = Auth::user();
        $data = $request->validated();
        $user->update($data);
    }
}
