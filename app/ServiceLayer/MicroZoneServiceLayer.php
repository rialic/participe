<?php

namespace App\ServiceLayer;

use App\Repository\Interfaces\MicroZoneInterface as MicroZoneRepository;
use App\ServiceLayer\Base\ServiceResource;

class MicroZoneServiceLayer extends ServiceResource {
    public function __construct(
        private readonly MicroZoneRepository $microZoneRepository,
    )
    {
        $this->repository = $microZoneRepository;
    }
}