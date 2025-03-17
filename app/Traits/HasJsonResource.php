<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait HasJsonResource
{
    protected $model;

    public function with(Request $request)
    {
        $method = $request->method();

        if (!in_array($method, ['POST', 'PUT', 'DELETE'])) {
            return [];
        }

        $message = match ($method) {
            'DELETE' => "{$this->model} excluído com sucesso.",
            'POST' => "{$this->model} salvo com sucesso.",
            'PUT' => "{$this->model} alterado com sucesso.",
        };

        return ['message' => $message];
    }
}