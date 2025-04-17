<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\ACLMiddleware;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

// TODO BLOQUEAR O BOTÃO SALVAR QUANDO OS EMAILS INSERIDOS NA TELA DE WEBAULA ESTIVEREM ERRADOS
// TODO QUANDO EU SALVO O EVENTO E DEPOIS REFAÇO O REDIRECIONAMENTO O SISTEMA APRENSETA UMA TELA DE ALERTA MUITO MAIOR QUE O NORMAL
// TODO QUANDO O USUÁRIO FICA PARADO POR MUITO TEMPO NA PÁGINA PÚBLICA O TOKEN DO LARAVEL É PERDIDO E QUANDO O MESMO FAZ ALGUM REQUEST PARA O BACK-END É RETORNADO 419, RESOLVER ISSO
// TODO - COMPONENTIZAR o Webclass - Final de Semana

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