<?php

use App\Http\Controllers\Web\TestController;
use App\Http\Controllers\Web\TestUserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('', [TestController::class, 'index'])
        ->name('test.list');

    Route::get('{test}', [TestController::class, 'show'])
        ->name('test.show')
        ->where('test', '[0-9]+');;


    Route::middleware('test.access')->group(function (){

        Route::post('{test}/test_user', [TestUserController::class, 'store'])->name('test_user.store');

        Route::middleware('test.status')->group(function (){
            Route::get('{test}/test_user/{test_user}', [TestUserController::class, 'show'])->name('test_user.show');
            Route::put('{test}/test_user/{test_user}', [TestUserController::class, 'update'])->name('test_user.update');
            Route::post('{test}/test_user/{test_user}/answers', [TestUserController::class, 'manipulateAnswer'])
                ->name('test.test_user.answers');
        });
    });
});
