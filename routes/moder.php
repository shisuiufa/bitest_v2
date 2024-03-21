<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Moder\TestController;


Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('tests/created-by/{user}', [TestController::class, 'index']);

    Route::get('tests/{test}', [TestController::class, 'show']);

    Route::post('tests/{test}', [TestController::class, 'update']);

    Route::post('tests', [TestController::class, 'store']);

    Route::delete('tests/{test}', [TestController::class, 'destroy']);

});


