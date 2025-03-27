<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface PermissionInterface
{
    public function filterByName($query, $data, $field): Builder;
}