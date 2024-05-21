<?php

use App\Http\Controllers\Web\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('/profile/update_password', [ProfileController::class, 'updatePassword'])->name('profile.update_password');

    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
