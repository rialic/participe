<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface CityInterface
{
    public function filterByName($query, $data, $field): Builder;
    public function filterByState($query, $data, $field): Builder;
}