<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\State\IndexStateRequest;
use App\Http\Resources\StateResource;
use App\ServiceLayer\StateServiceLayer;

class StateController extends Controller
{
    public function __construct(
        protected readonly StateServiceLayer $service,
        protected readonly string $resourceCollection = StateResource::class
    )
    {
        $this->indexValidatorRequest = IndexStateRequest::class;
    }
}
