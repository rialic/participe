<?php

namespace App\Http\Controllers;

use App\Http\Resources\MacroZoneResource;
use App\ServiceLayer\MacroZoneServiceLayer;
use App\Http\Requests\MacroZone\IndexMacroZoneRequest;

class MacroZoneController extends Controller
{
    public function __construct(
        protected readonly MacroZoneServiceLayer $service,
        protected readonly string $resourceCollection = MacroZoneResource::class
    )
    {
        $this->indexValidatorRequest = IndexMacroZoneRequest::class;
    }
}
