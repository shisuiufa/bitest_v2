<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('{any?}', 'app')->where('any', '.*');

Auth::routes();

