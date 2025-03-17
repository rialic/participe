<?php

namespace App\Repository;

use App\Models\Establishment;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\EstablishmentInterface;
use Illuminate\Database\Eloquent\Builder;

class EstablishmentRepository extends DBRepository implements EstablishmentInterface
{
    protected function model()
    {
        return Establishment::class;
    }

    public function filterByCnes($query, $data, $field): Builder
    {
        return $query->where($field, $data);
    }

    public function filterByCity($query, $data, $field): Builder
    {
        return $query->whereRelation('city', 'uuid', $data);
    }
}