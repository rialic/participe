<?php

namespace App\ServiceLayer;

use App\Repository\Interfaces\EstablishmentInterface as EstablishmentRepository;
use App\ServiceLayer\Base\ServiceResource;

class EstablishmentServiceLayer extends ServiceResource
{
    public function __construct(
        private readonly EstablishmentRepository $establishmentRepository
    )
    {
        $this->repository = $establishmentRepository;
    }
}