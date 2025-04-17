<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function() {
    Route::get('', DashboardController::class)->name('API.DASHBOARD');
});