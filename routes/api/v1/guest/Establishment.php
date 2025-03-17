<?php

use App\Http\Controllers\EstablishmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('establishment')->controller(EstablishmentController::class)->group(function() {
    Route::get('/{uuid}', 'show');
    Route::get('', 'index');
});