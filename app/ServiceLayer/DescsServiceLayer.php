<?php

namespace App\ServiceLayer;

use App\Repository\DescsRepository;
use App\ServiceLayer\Base\ServiceResource;

class DescsServiceLayer extends ServiceResource {
    public function __construct(
        private readonly DescsRepository $descsRepository,
    )
    {
        $this->repository = $descsRepository;
    }
}