<?php

use App\Http\Controllers\ParticipantController;
use Illuminate\Support\Facades\Route;

Route::prefix('participant')->controller(ParticipantController::class)->group(function() {
    Route::get('', 'show');
    Route::post('', 'store');
    Route::put('/{uuid}', 'update');
});