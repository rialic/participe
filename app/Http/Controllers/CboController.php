<?php

namespace App\Http\Controllers;

use App\Http\Resources\CboResource;
use App\ServiceLayer\CboServiceLayer;
use App\Http\Requests\Cbo\IndexCboRequest;

class CboController extends Controller
{
    public function __construct(
        protected readonly CboServiceLayer $service,
        protected readonly string $resourceCollection = CboResource::class
    )
    {
        $this->indexValidatorRequest = IndexCboRequest::class;
    }
}
