<?php

namespace App\ServiceLayer;

use App\Repository\Interfaces\MacroZoneInterface as MacroZoneRepository;
use App\ServiceLayer\Base\ServiceResource;

class MacroZoneServiceLayer extends ServiceResource {
    public function __construct(
        private readonly MacroZoneRepository $macroZoneRepository,
    )
    {
        $this->repository = $macroZoneRepository;
    }
}