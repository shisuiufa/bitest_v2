<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(): ResourceCollection
    {
        $users = User::where('id', '!=', Auth::id())->get();

        return UserResource::collection($users);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function store(CreateRequest $request): void
    {
        $data = $request->safe()->except('roles');
        $roles = $request->safe()->only('roles')['roles'];

        $user = User::create($data);
        $user->roles()->attach($roles);
    }

    public function update(UpdateRequest $request, User $user): void
    {
        $data = $request->safe()->except('roles');
        $roles = $request->safe()->only('roles')['roles'];

        $user->update($data);
        $user->roles()->sync($roles);
    }
}
