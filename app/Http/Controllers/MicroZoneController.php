<?php

namespace App\Http\Controllers;

use App\Http\Resources\MicroZoneResource;
use App\ServiceLayer\MicroZoneServiceLayer;
use App\Http\Requests\MicroZone\IndexMicroZoneRequest;

class MicroZoneController extends Controller
{
    public function __construct(
        protected readonly MicroZoneServiceLayer $service,
        protected readonly string $resourceCollection = MicroZoneResource::class
    )
    {
        $this->indexValidatorRequest = IndexMicroZoneRequest::class;
    }
}
