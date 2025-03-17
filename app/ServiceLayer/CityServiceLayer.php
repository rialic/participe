<?php

namespace App\ServiceLayer;

use App\Repository\CityRepository;
use App\ServiceLayer\Base\ServiceResource;

class CityServiceLayer extends ServiceResource
{
    public function __construct(
        private readonly CityRepository $cityRepository
    )
    {
        $this->repository = $cityRepository;
    }
}