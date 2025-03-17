<?php

namespace App\Http\Controllers;

use App\Http\Requests\Establishment\IndexEstablishmentRequest;
use App\Http\Resources\EstablishmentResource;
use App\ServiceLayer\EstablishmentServiceLayer;

class EstablishmentController extends Controller
{
    public function __construct(
        protected readonly EstablishmentServiceLayer $service,
        protected readonly string $resourceCollection = EstablishmentResource::class
    )
    {
        $this->indexValidatorRequest = IndexEstablishmentRequest::class;
    }
}
