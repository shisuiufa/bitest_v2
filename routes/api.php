<?php

use App\Http\Controllers\ImageController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return new UserResource($request->user());
});

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('/upload-image', [ImageController::class, 'store']);

});
