<?php

use App\Http\Controllers\Moder\AnswerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Moder\TestController;
use App\Http\Controllers\Moder\TestStatisticsController;


Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('tests/created-by/{user}', [TestController::class, 'index']);

    Route::get('tests/{test}', [TestController::class, 'show']);

    Route::post('tests/{test}', [TestController::class, 'update']);

    Route::post('tests', [TestController::class, 'store']);

    Route::delete('tests/{test}', [TestController::class, 'destroy']);

    Route::get('tests/{test}/statistics', [TestStatisticsController::class, 'index'])->name('statistics.index');

    Route::get('tests/{test}/statistics/export', [TestStatisticsController::class, 'export'])->name('statistics.export');

    Route::get('tests/{test}/statistics/{user}', [TestStatisticsController::class, 'show'])->name('statistics.user.show');

    Route::get('tests/{test}/statistics/{user}/attempts', [TestStatisticsController::class, 'totalAttempts'])->name('statistics.user.attempts');

    Route::post('answers/{answer}', AnswerController::class);

});


