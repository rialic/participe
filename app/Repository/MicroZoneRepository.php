<?php

namespace App\Repository;

use App\Models\MicroZone;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\MicroZoneInterface;
use Illuminate\Database\Eloquent\Builder;

class MicroZoneRepository extends DBRepository implements MicroZoneInterface
{
    protected function model()
    {
        return MicroZone::class;
    }

    public function filterByName($query, $data, $field): Builder
    {
        return $query->where($field, 'like', "%{$data}%");
    }

    public function filterByMacroZone($query, $data, $field): Builder
    {
        return $query->whereRelation('macroZone', 'uuid', $data);
    }
}