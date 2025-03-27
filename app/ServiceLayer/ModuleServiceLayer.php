<?php

namespace App\ServiceLayer;

use App\Repository\ModuleRepository;
use App\ServiceLayer\Base\ServiceResource;

class ModuleServiceLayer extends ServiceResource
{
    public function __construct(
        private readonly ModuleRepository $moduleRepository
    )
    {
        $this->repository = $moduleRepository;
    }
}