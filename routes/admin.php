<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\QuestionTypeController;

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/question-type', [QuestionTypeController::class, 'index'])->name('question_type.index');

});
