<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->controller(UserController::class)->group(function() {
    Route::get('me', 'me')->name('API.USER-ME');
    Route::post('', 'store')->name('API.USER-SAVE');
    Route::put('{uuid}', 'update')->name('API.USER-UPDATE');
});