<?php

namespace App\ServiceLayer;

use App\Repository\Interfaces\DescsInterface as DescsRepository;
use App\ServiceLayer\Base\ServiceResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DescsServiceLayer extends ServiceResource {
    public function __construct(
        private readonly DescsRepository $descsRepository,
    ) {
        $this->repository = $descsRepository;
    }
}