<?php

namespace App\Http\Controllers;

use App\Http\Requests\City\IndexCityRequest;
use App\Http\Resources\CityResource;
use App\ServiceLayer\CityServiceLayer;

class CityController extends Controller
{
    public function __construct(
        protected readonly CityServiceLayer $service,
        protected readonly string $resourceCollection = CityResource::class
    )
    {
        $this->indexValidatorRequest = IndexCityRequest::class;
    }
}
