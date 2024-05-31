<?php

namespace App\Http\Requests\User;

use App\Enums\RoleType;
use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_name' => 'required|string',
            'roles' => 'required|array',
            'roles.*' => [Rule::enum(RoleType::class)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user->id),],
            'password' => ['nullable', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(), 'confirmed'],
        ];
    }
}
