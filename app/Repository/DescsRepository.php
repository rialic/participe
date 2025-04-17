<?php

namespace App\Repository;

use App\Models\Descs;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\DescsInterface;
use Illuminate\Database\Eloquent\Builder;

class DescsRepository extends DBRepository implements DescsInterface
{
    protected function model()
    {
        return Descs::class;
    }

    public function filterByName($query, $data, $field): Builder
    {
        return $query->orWhere($field, 'like', "%{$data}%");
    }

    public function filterByBiremeCode($query, $data, $field): Builder
    {
        return $query->where($field, 'like', "%{$data}%");
    }
}