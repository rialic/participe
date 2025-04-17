<?php

use App\Http\Controllers\DescsController;
use Illuminate\Support\Facades\Route;

Route::prefix('descs')->controller(DescsController::class)->group(function() {
    Route::get('', 'index')->name('API.DESCS');
});