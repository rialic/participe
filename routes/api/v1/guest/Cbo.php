<?php

use App\Http\Controllers\CboController;
use Illuminate\Support\Facades\Route;

Route::prefix('cbo')->controller(CboController::class)->group(function() {
    Route::get('', 'index');
});