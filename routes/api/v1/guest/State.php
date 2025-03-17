<?php

use App\Http\Controllers\StateController;
use Illuminate\Support\Facades\Route;

Route::prefix('state')->controller(StateController::class)->group(function() {
    Route::get('', 'index');
});