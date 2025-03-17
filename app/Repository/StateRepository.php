<?php

namespace App\Repository;

use App\Models\State;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\StateInterface;
use Illuminate\Database\Eloquent\Builder;

class StateRepository extends DBRepository implements StateInterface
{
    protected function model()
    {
        return State::class;
    }

    public function filterByAcronym($query, $data, $field): Builder
    {
        return $query->where($field, 'like', "%{$data}%");
    }

    public function filterByName($query, $data, $field): Builder
    {
        return $query->where($field, 'like', "%{$data}%");
    }
}