<?php

use App\Http\Controllers\MagicLoginLinkController;
use Illuminate\Support\Facades\Route;

Route::get('login-magico/{user:email}', MagicLoginLinkController::class)->name('login.magic')->middleware(['signed', 'guest']);

Route::get('{any}', fn() => view('app'))->where('any','.*');