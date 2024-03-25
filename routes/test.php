<?php

use App\Http\Controllers\Web\TestController;
use App\Http\Controllers\TestUserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('', [TestController::class, 'index'])->name('test.list');

    Route::get('{test}', [TestController::class, 'show'])->name('test.show')->where('test', '[0-9]+');;

    Route::middleware('test.access')->group(function () {

        Route::get('{test}/pass', [TestController::class, 'showPass'])->name('test.pass.show')->where('test', '[0-9]+');

        Route::post('{test}/pass', [TestController::class, 'storePass'])->name('test.pass.store')->where('test', '[0-9]+');

    });
});
