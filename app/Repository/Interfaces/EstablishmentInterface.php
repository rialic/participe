<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface EstablishmentInterface {
    public function filterByCnes($query, $data, $field): Builder;
    public function filterByCity($query, $data, $field): Builder;
}