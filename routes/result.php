<?php

use App\Http\Controllers\Web\ResultController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::apiResource('results' , ResultController::class)->only('index', 'show');

});
