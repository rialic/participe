<?php

namespace App\ServiceLayer;

use App\Repository\Interfaces\StateInterface as StateRepository;
use App\ServiceLayer\Base\ServiceResource;

class StateServiceLayer extends ServiceResource {
    public function __construct(
        private readonly StateRepository $stateRepository,
    )
    {
        $this->repository = $stateRepository;
    }
}