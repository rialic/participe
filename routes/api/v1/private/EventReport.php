<?php

use App\Http\Controllers\EventReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('events-report')->controller(EventReportController::class)->group(function() {
    Route::get('', 'index')->name('API.EVENT.WEBCLASS-REPORT');
    Route::get('export/pdf', 'print')->name('API.EVENT.WEBCLASS-REPORT-PDF');
    Route::get('export/excel', 'print')->name('API.EVENT.WEBCLASS-REPORT-EXCEL');
});