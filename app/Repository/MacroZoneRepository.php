<?php

namespace App\Repository;

use App\Models\MacroZone;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\MacroZoneInterface;
use Illuminate\Database\Eloquent\Builder;

class MacroZoneRepository extends DBRepository implements MacroZoneInterface
{
    protected function model()
    {
        return MacroZone::class;
    }

    public function filterByName($query, $data, $field): Builder
    {
        return $query->where($field, 'like', "%{$data}%");
    }
}