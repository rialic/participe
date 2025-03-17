<?php

use App\Http\Controllers\CertificateController;
use Illuminate\Support\Facades\Route;

Route::prefix('certificate')->controller(CertificateController::class)->group(function() {
    Route::get('', 'show');
    Route::get('print/{user}/{event}', 'print');
});