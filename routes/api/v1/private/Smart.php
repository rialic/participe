<?php

use App\Http\Controllers\SmartController;
use Illuminate\Support\Facades\Route;

Route::prefix('smart')->controller(SmartController::class)->group(function() {
    Route::get('establishments', 'index')->name('API.SMART.ESTABLISHMENT-GET');
    Route::post('establishments', 'sendEstablishments')->name('API.SMART.ESTABLISHMENT-SEND');
    Route::get('professionals', 'index')->name('API.SMART.PROFESSIONALS-GET');
    Route::post('professionals', 'sendProfessionals')->name('API.SMART.PROFESSIONALS-SEND');
});