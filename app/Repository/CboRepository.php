<?php

namespace App\Repository;

use App\Models\Cbo;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\CboInterface;
use Illuminate\Database\Eloquent\Builder;

class CboRepository extends DBRepository implements CboInterface
{
    protected function model()
    {
        return Cbo::class;
    }

    public function filterByName($query, $data, $field): Builder
    {
        return $query->where($field, 'like', "%{$data}%");
    }

    public function filterByCode($query, $data, $field): Builder
    {
        return $query->where($field, $data);
    }
}