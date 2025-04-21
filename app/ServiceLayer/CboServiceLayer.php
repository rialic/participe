<?php

namespace App\ServiceLayer;

use App\Repository\Interfaces\CboInterface as CboRepository;
use App\ServiceLayer\Base\ServiceResource;

class CboServiceLayer extends ServiceResource {
    public function __construct(
        private readonly CboRepository $cboRepository,
    )
    {
        $this->repository = $cboRepository;
    }
}