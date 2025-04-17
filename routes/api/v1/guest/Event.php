<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('event')->controller(EventController::class)->group(function() {
    Route::get('', 'index');
    Route::post('store', 'store');
    Route::post('sync-participants', 'syncParticipants');
    Route::put('participant-rating/{uuid}', 'storeParticipantRating');
});