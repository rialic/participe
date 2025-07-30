<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\ACLMiddleware;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::post('magic-link', [UserController::class, 'sendMagicLink']);

    foreach (File::allFiles(base_path() . '/routes/api/v1/guest') as $partial) {
        require $partial->getPathname();
    }

    Route::middleware(['auth:sanctum', ACLMiddleware::class])->group(function() {
        foreach (File::allFiles(base_path() . '/routes/api/v1/private') as $partial) {
            require $partial->getPathname();
        }
    });

    //404 fallback
    Route::any('{any}', function(){
        $e = new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('This is an invalid endpoint. Check the documentation to confirm the endpoint is correct.');
        return \App\Exceptions\ApiException::handleException($e, func_get_args());
    })->where('any', '.*');
});

//404 fallback
Route::any('{any}', function(){
    $e = new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('This is an invalid endpoint. Check the documentation to confirm the endpoint is correct.');
    return \App\Exceptions\ApiException::handleException($e, func_get_args());
})->where('any', '.*');