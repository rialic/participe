<?php

use App\Http\Controllers\CityController;
use Illuminate\Support\Facades\Route;

Route::prefix('city')->controller(CityController::class)->group(function() {
    Route::get('', 'index');
});