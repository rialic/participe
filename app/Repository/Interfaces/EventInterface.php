<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface EventInterface
{
    public function filterByStartAt($query, $data, $field): Builder;
    public function filterByEndAt($query, $data, $field): Builder;
    public function filterByEventsAvailables($query, $data, $field): Builder;
}