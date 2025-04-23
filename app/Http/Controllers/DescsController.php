<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexDescsRequest;
use App\Http\Resources\DescsResource;
use App\ServiceLayer\DescsServiceLayer;

class DescsController extends Controller
{
    public function __construct(
        protected readonly DescsServiceLayer $service,
        protected readonly string $resourceCollection = DescsResource::class
    )
    {
        $this->filterFields = ['name', 'bireme_code', 'autocomplete_descs_search'];
    }
}
