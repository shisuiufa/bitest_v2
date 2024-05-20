<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $customMessages = [
            'email.required' => 'Поле Email обязательно для заполнения.',
            'email.email' => 'Пожалуйста, введите корректный адрес электронной почты.',
            'password.required' => 'Поле пароля обязательно для заполнения.',
        ];

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], $customMessages);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json(['success' => 'Аутентификация прошла успешно']);
        }

        return response()->json(['errors' => ["Ошибка входа. Пожалуйста, проверьте правильность введенного логина и пароля и попробуйте снова"]], 403);
    }

    public function logout(Request $request): void
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

    }
}
