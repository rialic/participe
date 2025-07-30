<?php

use App\Http\Controllers\MacroZoneController;
use Illuminate\Support\Facades\Route;

Route::prefix('macro-zone')->controller(MacroZoneController::class)->group(function() {
    Route::get('', 'index')->name('API.MACRO-ZONE');
});