<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('events')->controller(EventController::class)->group(function() {
    Route::get('{uuid}', 'show');
    Route::get('', 'index');
    Route::post('', 'store');
    Route::put('{uuid}', 'update');
    Route::delete('{uuid}', 'delete');
    Route::post('sync-participants', 'syncParticipants');
    Route::put('participant-rating/{uuid}', 'storeParticipantRating');
});