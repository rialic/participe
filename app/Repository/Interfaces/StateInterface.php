<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface StateInterface {
    public function filterByAcronym($query, $data, $field): Builder;
    public function filterByName($query, $data, $field): Builder;
}