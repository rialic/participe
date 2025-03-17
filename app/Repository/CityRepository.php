<?php

namespace App\Repository;

use App\Models\City;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\CityInterface;
use Illuminate\Database\Eloquent\Builder;

class CityRepository extends DBRepository implements CityInterface
{
    public function model()
    {
        return City::class;
    }

    public function filterByName($query, $data, $field): Builder
    {
        return $query->where($field, 'like', "%{$data}%");
    }

    public function filterByState($query, $data, $field): Builder
    {
        return $query->whereRelation('state', 'uuid', $data);
    }
}