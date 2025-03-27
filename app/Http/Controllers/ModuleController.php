<?php

namespace App\Http\Controllers;

use App\Http\Resources\ModuleResource;
use App\ServiceLayer\ModuleServiceLayer;

class ModuleController extends Controller
{
    public function __construct(
        protected readonly ModuleServiceLayer $service,
        protected readonly string $resourceCollection = ModuleResource::class
    )
    {}
}
