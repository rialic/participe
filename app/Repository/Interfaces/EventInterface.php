<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface EventInterface
{
    public function filterByName($query, $data, $field): Builder;
    public function filterByTypeEvent($query, $data, $field): Builder;
    public function filterByEventsAvailables($query, $data, $field): Builder;
    public function filterByStartAt($query, $data, $field): Builder;
    public function filterByEndAt($query, $data, $field): Builder;
    public function filterByOrganization($query, $data, $field): Builder;
    public function filterByBiremeCode($query, $data, $field): Builder;
}