<?php

use App\Http\Controllers\ModuleController;
use Illuminate\Support\Facades\Route;

Route::prefix('module')->controller(ModuleController::class)->group(function() {
    Route::get('', 'index');
});