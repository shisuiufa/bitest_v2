<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Moder\TestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\QuestionTypeController;

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/question-type', [QuestionTypeController::class, 'index'])->name('question_type.index');

    Route::get('users/export', [UserController::class, 'export']);

    Route::get('tests/export', [TestController::class, 'export']);

    Route::apiResource('users', UserController::class)->except(['destroy']);
});
