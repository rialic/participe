<?php

namespace App\ServiceLayer;

use App\Repository\EstablishmentRepository;
use App\ServiceLayer\Base\ServiceResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EstablishmentServiceLayer extends ServiceResource
{
    public function __construct(
        private readonly EstablishmentRepository $establishmentRepository
    )
    {
        $this->repository = $establishmentRepository;
    }
}