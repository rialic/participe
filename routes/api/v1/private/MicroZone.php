<?php

use App\Http\Controllers\MicroZoneController;
use Illuminate\Support\Facades\Route;

Route::prefix('micro-zone')->controller(MicroZoneController::class)->group(function() {
    Route::get('', 'index')->name('API.MICRO-ZONE');
});