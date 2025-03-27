<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface RoleInterface
{
    public function filterByName($query, $data, $field): Builder;
}