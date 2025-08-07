<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('events')->controller(EventController::class)->group(function() {
    Route::get('{uuid}', 'show')->name('API.EVENT.WEBCLASS-SHOW');
    Route::get('', 'index');
    Route::post('', 'store')->name('API.EVENT.WEBCLASS-SAVE');
    Route::put('{uuid}', 'update')->name('API.EVENT.WEBCLASS-UPDATE');
    Route::delete('{uuid}', 'delete')->name('API.EVENT.WEBCLASS-DELETE');
    Route::post('sync-participants', 'syncParticipants');
    Route::put('participant-rating/{uuid}', 'storeParticipantRating');
});