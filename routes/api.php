<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

// TODO - FAZER A PARTE DE AUTENTICAÇÃO
// TODO - COMPONENTIZAR o Webclass - Final de Semana

Route::group(['prefix' => 'v1'], function () {
    foreach (File::allFiles(base_path() . '/routes/api/v1/guest') as $partial) {
        require $partial->getPathname();
    }

    Route::middleware(['auth:sanctum'])->group(function() {
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