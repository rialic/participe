<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface EventInterface
{
    public function filterByScopeSearch($query, $data, $field): Builder;
    public function filterByEventsAvailables($query, $data, $field): Builder;
}