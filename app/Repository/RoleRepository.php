<?php

namespace App\Repository;

use App\Models\Role;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\RoleInterface;
use Illuminate\Database\Eloquent\Builder;

class RoleRepository extends DBRepository implements RoleInterface
{
    protected function model()
    {
        return Role::class;
    }

    public function filterByName($query, $data, $field): Builder
    {
        return $query->where($field, 'like', "%{$data}%");
    }
}