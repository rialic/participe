<?php

namespace App\Repository;

use App\Models\Permission;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\PermissionInterface;
use Illuminate\Database\Eloquent\Builder;

class PermissionRepository extends DBRepository implements PermissionInterface
{
    protected function model()
    {
        return Permission::class;
    }

    public function filterByName($query, $data, $field): Builder
    {
        return $query->where($field, $data);
    }
}